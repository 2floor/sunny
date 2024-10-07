<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../../logic/common/common_logic.php';
require_once __DIR__ . '/../../common/security_common_logic.php';
require_once __DIR__ . '/../../logic/import/hospital_import.php';
require_once __DIR__ . '/../../logic/export/error_data_import.php';
require_once __DIR__ . '/../../third_party/bootstrap.php';


use Maatwebsite\Excel\Facades\Excel;


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

        if ($post['type'] == 'hospital') {
            // 初期処理　HTML生成処理呼び出し
            $data = $this->import_hospital_data($post);
        }

        return $data;
    }

    private function import_hospital_data($post)
    {
        $fileData = $this->validate_uploaded_file($post['type']);

        if (!$fileData['status']) {
            return $fileData;
        }

        $import = new hospital_import();
        Excel::import($import, $fileData['path']);

        return $this->upload_csv_responsive($import);
    }

    private function upload_csv_responsive($import)
    {
        $totalError = 0;
        $errorFile = '';
        $success = $import->getSuccess();
        $errors = $import->getErrors();
        $randomFileName = uniqid(time() . '_' . mt_rand(1000, 9999), true);


        if (!empty($errors)) {
            $totalError = count($errors);
            Excel::store(new error_data_import($errors), $randomFileName . '.csv', 'export_error');
            $errorFile = $randomFileName . '.csv';
        }

        return [
            'success' => $success,
            'totalError' => $totalError,
            'errorFile' => $errorFile,
        ];
    }

    private function validate_uploaded_file($type) {
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

                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0777, true);
                }

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    chmod($dest_path, 0777);
                    return [
                        'status' => true,
                        'path' => $dest_path
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
                'message' => 'ファイルがアップロードされていません'
            ];
        }
    }
}