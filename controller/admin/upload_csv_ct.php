<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../../logic/common/common_logic.php';
require_once __DIR__ . '/../../common/security_common_logic.php';
require_once __DIR__ . '/../../logic/import/hospital_import.php';
require_once __DIR__ . '/../../logic/import/hospital_cancer_import.php';
require_once __DIR__ . '/../../logic/import/dpc_import.php';
require_once __DIR__ . '/../../logic/import/stage_import.php';
require_once __DIR__ . '/../../logic/import/survival_import.php';
require_once __DIR__ . '/../../logic/import/cancer_import.php';
require_once __DIR__ . '/../../logic/export/error_data_import.php';
require_once __DIR__ . '/../../third_party/bootstrap.php';


use Maatwebsite\Excel\Facades\Excel;
use App\Models\Import;
use Carbon\Carbon;


/**
 * セキュリティチェック
 */
// インスタンス生成
$security_common_logic = new security_common_logic();

// XSSチェック、NULLバイトチェック
$security_result = $security_common_logic->security_exection($_POST, $_REQUEST, $_COOKIE);

// セキュリティチェック後の値を再設定
$_POST = $security_result[0];
$_REQUEST = $security_result[1];
$_COOKIE = $security_result[2];

// tokenチェック
$security_common_logic = new security_common_logic();
$data = $security_common_logic->isTokenExection();
if ($data['status']) {
    // 正常処理 コントローラー呼び出し

    // インスタンス生成
    $ct = new upload_csv_ct();
    $post_data = $_SERVER['REQUEST_METHOD'] == 'POST' ? $_POST : $_GET;

    // コントローラー呼び出し
    $data = $ct->main_control($post_data);
} else {
    // パラメータに不正があった場合
    // AJAX返却用データ成型
    $data = array(
        'status' => false,
        'input_datas' => $_POST,
        'return_url' => 'logout.php'
    );
}

// AJAXへ返却
echo json_encode(compact('data'));

/**
 * 管理画面ユーザー管理処理
 *
 * ViewからLogic呼び出しを行うclass。
 * 本クラスではLogic呼び出しやデータの成型、入力チェックのみを行うものとする。
 * ※：セキュリティ保持の為Logic呼び出元をmain_controlクラスのみとする。
 * 各ロジック呼び出しをクラス化し、かつ、privateとする。
 *
 * @author Seidou
 *
 */
class upload_csv_ct {
    /**
     * コンストラクタ
     */

    public function __construct()
    {}

    /**
     * コントローラー
     * 各処理の振り分けをmethodの文字列により行う
     */
    public function main_control($post)
    {
        $data = [
            'status' => false,
            'data' => [],
        ];

        if ($post['method'] == 'check') {
            // 初期処理　HTML生成処理呼び出し
            $data = $this->check_import_data($post);
        } else if ($post['method'] == 'import') {
            $data = $this->import_data($post);
        }

        return $data;
    }

    private function check_import_data($post)
    {
        $check_processing_data = $this->check_processing_data($post['parent_id'] ?? null);

        if (!$check_processing_data['status']) {
            return $check_processing_data;
        }

        $fileData = $this->validate_uploaded_file($post['type']);

        if (!$fileData['status']) {
            return $fileData;
        }

        $processing_data = $this->insert_processing_import($post['type'], $post['parent_id'] ?? null);

        if (!$processing_data['status']) {
            return $processing_data;
        }

        Import::find($processing_data['import_id'])->update([
            'file_name' => $fileData['file_name'],
        ]);

        return [
            'status' => true,
            'message' => "CSVファイルのアップロードと処理が行われています。この間、他のファイルのアップロードはできませんが、他の作業は継続できます。",
            'import_id' => $processing_data['import_id'],
        ];
    }

    private function import_data($post) {
        session_write_close();
        $processing_import = Import::find($post['import_id']);

        if ($processing_import && $processing_import->status == Import::STATUS_IN_PROCESSING) {
            $uploadFileDir = '../../upload_files/import_data/'.$post['type'].'/';
            $file_path = $uploadFileDir . $processing_import->file_name;

            if (file_exists($file_path)) {
                if ($post['type'] == 'hospital') {
                    $import = new hospital_import();
                } elseif ($post['type'] == 'hospital_cancer') {
                    $import = new hospital_cancer_import();
                } elseif ($post['type'] == 'dpc') {
                    $import = new dpc_import();
                } elseif ($post['type'] == 'stage') {
                    $import = new stage_import();
                } elseif ($post['type'] == 'survival') {
                    $import = new survival_import();
                } elseif ($post['type'] == 'cancer') {
                    $import = new cancer_import();
                } else {
                    return [
                        'status' => false,
                        'message' => '正しく定義されていない無効なインポート',
                    ];
                }

                try {
                    Excel::import($import, $file_path);
                    $this->complete_import($import, $processing_import->id, $post['type'], $post['parent_id'] ?? null);
                } catch (\Exception $e) {
                    $this->exception_import($processing_import->id, $e->getMessage(), $post['parent_id'] ?? null);
                }
            } else {
                $processing_import->update([
                    'status' => Import::STATUS_TIMEOUT,
                    'success' => 0,
                    'error' => 0
                ]);

                return [
                    'status' => false,
                    'message' => 'インポートファイルが見つかりません',
                ];
            }
        }

        return [
            'status' => true,
            'message' => 'データを正常にインポートしました',
        ];
    }

    private function exception_import($import_id, $message, $parent_id = null)
    {
        Import::find($import_id)->update([
            'status' => Import::STATUS_TIMEOUT,
            'success' => 0,
            'error' => 0,
            'error_message' => $message
        ]);

        if ($parent_id) {
            $existingImport = Import::find($parent_id);

            if ($existingImport && ($existingImport->status == Import::STATUS_REIMPORT)) {
                $existingImport->update([
                    'status' => Import::STATUS_ERROR_PROCESSING,
                ]);
            }
        }
    }

    private function complete_import($import, $import_id, $type, $parent_id = null)
    {
        $errorFile = '';
        $success = $import->getSuccess();
        $totalError = $import->getCountError();
        $errors = $import->getErrors();
        $randomFileName = $type . '_error_file_' .uniqid(time() . '_' . mt_rand(1, 9), false);


        if ($totalError > 0) {
            Excel::store(new error_data_import($errors), $randomFileName . '.csv', 'export_error');
            $errorFile = $randomFileName . '.csv';
        }

        Import::find($import_id)->update([
            'status' => $totalError ? Import::STATUS_ERROR_PROCESSING : Import::STATUS_COMPLETED,
            'success' => $success,
            'error' => $totalError,
            'completed_time' => now(),
            'error_file' => $errorFile,
        ]);

        if ($parent_id) {
            $existingImport = Import::find($parent_id);

            if ($existingImport && ($existingImport->status == Import::STATUS_REIMPORT)) {
                $status =  $totalError ? Import::STATUS_ERROR_PROCESSING : Import::STATUS_COMPLETED;

                if ($status == Import::STATUS_COMPLETED) {
                    $parentSuccess = $existingImport->success + $success;
                    $parentError = 0;
                } else {
                    $parentSuccess = $existingImport->success + $success;
                    $parentError =  ($existingImport->error - $success) > 0 ? ($existingImport->error - $success) : $totalError;
                }

                $existingImport->update([
                    'status' => $status,
                    'success' => $parentSuccess,
                    'error' => $parentError,
                ]);
            }
        }
    }

    private function check_processing_data($parent_id = null) {
        $processing_import = Import::where('status', Import::STATUS_IN_PROCESSING)->get();

        if (!empty($processing_import)) {

            $hasProcessingImport = false;
            foreach ($processing_import as $import) {
                $created = $import->created_at ? Carbon::parse($import->created_at) : null;
                if ($created && $created->diffInHours(Carbon::now()) > 1) {
                    $import->update([
                        'status' => Import::STATUS_TIMEOUT,
                        'success' => 0,
                        'error' => 0
                    ]);
                } else {
                    $hasProcessingImport = true;
                }
            }

            if ($hasProcessingImport) {
                return [
                    'status' => false,
                    'message' => 'CSVファイルのアップロードと処理が行われています。この間、他のファイルのアップロードはできませんが、他の作業は継続できます。'
                ];
            }
        }

        if ($parent_id) {
            $existingImport = Import::where('id', $parent_id)
                ->where(function ($query) {
                    $query->where('status', Import::STATUS_ERROR_PROCESSING);
                    $query->orWhere('status', Import::STATUS_TIMEOUT);
                })
                ->exists();

            if (!$existingImport) {
                return [
                    'status' => false,
                    'message' => 'エラーのないデータを再インポートできません'
                ];
            }
        }

        return [
            'status' => true,
            'message' => '有効なプロセス'
        ];
    }

    private function insert_processing_import($type, $parent_id = null) {

        $data_type = match ($type) {
            'hospital' => Import::DATA_TYPE_HOSPITAL,
            'hospital_cancer' => Import::DATA_TYPE_HOSPITAL_CANCER,
            'dpc' => Import::DATA_TYPE_DPC,
            'stage' => Import::DATA_TYPE_STAGE,
            'survival' => Import::DATA_TYPE_SURVIVAL,
            'cancer' => Import::DATA_TYPE_CANCER,
            default => null,
        };

        if (!$data_type) {
            return [
                'status' => false,
                'message' => 'インポートデータタイプを認識できません'
            ];
        }

        if ($parent_id) {
            $new_import = Import::create([
                'status' => Import::STATUS_IN_PROCESSING,
                'import_type' => Import::IMPORT_TYPE_REIMPORT,
                'parent_id' => $parent_id,
                'success' => 0,
                'error' => 0,
                'data_type' =>  $data_type,
                'created_at' => Carbon::now()
            ]);

            Import::find($parent_id)->update([
                'status' => Import::STATUS_REIMPORT,
            ]);
        } else {
            $new_import = Import::create([
                'status' => Import::STATUS_IN_PROCESSING,
                'import_type' => Import::IMPORT_TYPE_MAIN,
                'success' => 0,
                'error' => 0,
                'data_type' =>  $data_type,
                'created_at' => Carbon::now()
            ]);
        }

        if ($new_import) {
            return [
                'status' => true,
                'import_id' => $new_import->id,
            ];
        } else {
            return [
                'status' => false,
                'message' => '失敗したプロセスを作成する'
            ];
        }
    }

    private function validate_uploaded_file($type) {

        if (!$type) {
            return [
                'status' => false,
                'message' => 'インポートデータタイプを認識できません'
            ];
        }

        if (isset($_FILES['upload-file']) && $_FILES['upload-file']['error'] == UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['upload-file']['tmp_name'];
            $fileName = $_FILES['upload-file']['name'];
//            $fileSize = $_FILES['upload-file']['size'];
//            $fileType = $_FILES['upload-file']['type'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedFileTypes = ['csv', 'xls', 'xlsx'];
            if (in_array($fileExtension, $allowedFileTypes)) {
                $uploadFileDir = '../../upload_files/import_data/' . $type;
                $dest_path = $uploadFileDir . '/' . $fileName;

                if (!is_dir($uploadFileDir) && !mkdir($uploadFileDir, 0777, true) && !is_dir($uploadFileDir)) {
                    return [
                        'status' => false,
                        'message' => 'フォルダの作成に失敗しました'
                    ];
                }

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    chmod($dest_path, 0777);
                    return [
                        'status' => true,
                        'path' => $dest_path,
                        'file_name' => $fileName
                    ];
                } else {
                    return [
                        'status' => false,
                        'message' => 'ファイルのアップロードに失敗しました'
                    ];
                }
            } else {
                return [
                    'status' => false,
                    'message' => 'CSVおよびXLSXファイルのみ許可されます'
                ];
            }
        } else {
            return [
                'status' => false,
                'message' => 'ファイルのアップロードに失敗しました: ' . $this->getUploadError($_FILES['upload-file']['error'])
            ];
        }
    }

    private function getUploadError($errorCode) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'アップロードされたファイルが大きすぎます。',
            UPLOAD_ERR_FORM_SIZE => 'アップロードされたファイルが大きすぎます。',
            UPLOAD_ERR_PARTIAL => 'ファイルが部分的にしかアップロードされませんでした。',
            UPLOAD_ERR_NO_FILE => 'ファイルがアップロードされませんでした。',
            UPLOAD_ERR_NO_TMP_DIR => '一時フォルダがありません。',
            UPLOAD_ERR_CANT_WRITE => 'ディスクにファイルを書き込めませんでした。',
            UPLOAD_ERR_EXTENSION => 'ファイルのアップロードがPHPの拡張によって停止されました。'
        ];

        return $errors[$errorCode] ?? '不明なエラーが発生しました。';
    }
}