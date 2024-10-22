<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../../logic/common/common_logic.php';
require_once __DIR__ . '/../../logic/admin/auto_rank_logic.php';
require_once __DIR__ . '/../../common/security_common_logic.php';
require_once __DIR__ . '/../../logic/command/dpc_ranking_command.php';

use App\Models\AutoRank;
use App\Models\DPC;
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
    $auto_rank_ct = new auto_rank_ct();
    $post_data = $_SERVER['REQUEST_METHOD'] == 'POST' ? $_POST : $_GET;

    // コントローラー呼び出し
    $data = $auto_rank_ct->main_control($post_data);
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
class auto_rank_ct
{
    /**
     * コンストラクタ
     */
    protected $auto_rank_logic;

    public function __construct()
    {
        // 管理画面ユーザーロジックインスタンス
        $this->auto_rank_logic = new auto_rank_logic();
    }

    /**
     * コントローラー
     * 各処理の振り分けをmethodの文字列により行う
     *
     * @param unknown $post
     */
    public function main_control($post)
    {
        if ($post['method'] == 'init') {
            // 初期処理　HTML生成処理呼び出し
            $data = $this->create_data_list($post);
        } else if ($post['method'] == 'check_auto_rank') {
            $data = $this->check_auto_rank($post);
        } else if ($post['method'] == 'handle_auto_rank') {
            $data = $this->handle_auto_rank($post);
        }

        return $data;
    }

    public function init_entry_new()
    {
        $cancers = $this->auto_rank_logic->get_cancer_list();

        return [
            'cancers' => $cancers,
        ];
    }

    private function check_auto_rank($post) {
        $check_processing_data = $this->check_processing_data();

        if (!$check_processing_data['status']) {
            return $check_processing_data;
        }

        $processing_data = $this->insert_processing_data($post['data_type'], $post['auto_type'], $post['cancer_id'], $post['year']);

        if (!$processing_data['status']) {
            return $processing_data;
        }

        return [
            'status' => true,
            'message' => "自動生成中です",
            'auto_rank_id' => $processing_data['auto_rank_id'],
        ];
    }

    private function handle_auto_rank($post) {
        session_write_close();
        $processing = AutoRank::find($post['auto_rank_id']);

        if ($processing && $processing->status == AutoRank::STATUS_IN_PROCESSING) {

            try {
                if ($processing->auto_type == AutoRank::AUTO_TYPE_RANK && $processing->data_type == AutoRank::DATA_TYPE_DPC) {
                    $command = new dpc_ranking_command();
                    $command->handle($processing->cancer_id, $processing->year);
                }

                $processing->update([
                    'status' => AutoRank::STATUS_COMPLETED,
                    'completed_time' => now()
                ]);
            } catch (\Exception $e) {
                $processing->update([
                    'status' => AutoRank::STATUS_TIMEOUT,
                    'message' => $e->getMessage()
                ]);

                return [
                    'status' => false,
                    'message' => '自動作成プロセスが失敗しました',
                ];
            }
        }

        return [
            'status' => true,
            'message' => 'データを正常にインポートしました',
        ];
    }

    private function check_processing_data() {
        $processing_auto = AutoRank::where('status', AutoRank::STATUS_IN_PROCESSING)->get();

        if (!empty($processing_auto)) {

            $hasProcessing = false;
            foreach ($processing_auto as $auto) {
                $created = $auto->created_at ? Carbon::parse($auto->created_at) : null;
                if ($created && $created->diffInHours(Carbon::now()) > 1) {
                    $auto->update([
                        'status' => AutoRank::STATUS_TIMEOUT,
                        'message' => '実行時間を超過しました',
                    ]);
                } else {
                    $hasProcessing = true;
                }
            }

            if ($hasProcessing) {
                return [
                    'status' => false,
                    'message' => '別の自動化プロセスが進行中です。前のプロセスが完了するまでお待ちください'
                ];
            }
        }

        return [
            'status' => true,
            'message' => '有効なプロセス'
        ];
    }

    private function insert_processing_data($data_type, $auto_type, $cancer_id, $year) {
        $data_type = match ($data_type) {
            '1' => AutoRank::DATA_TYPE_DPC,
            default => null,
        };

        $auto_type = match ($auto_type) {
            '1' => AutoRank::AUTO_TYPE_RANK,
            default => null,
        };

        if (!$data_type || !$auto_type) {
            return [
                'status' => false,
                'message' => 'インポートデータタイプを認識できません'
            ];
        }

        $existedData = false;
        if ($auto_type == AutoRank::AUTO_TYPE_RANK && $data_type == AutoRank::DATA_TYPE_DPC) {
            $existedData = DPC::where(['cancer_id' => $cancer_id, 'year' => $year])->exists();
        }

        if (!$existedData) {
            return [
                'status' => false,
                'message' => '自動生成する必要があるデータには、選択したがんの種類に対応する年が存在しません。'
            ];
        }

        $new_auto = AutoRank::create([
            'status' => AutoRank::STATUS_IN_PROCESSING,
            'auto_type' => $auto_type,
            'data_type' => $data_type,
            'cancer_id' => $cancer_id,
            'year' => $year,
            'created_at' => Carbon::now()
        ]);

        if ($new_auto) {
            return [
                'status' => true,
                'auto_rank_id' => $new_auto->id,
            ];
        } else {
            return [
                'status' => false,
                'message' => '失敗したプロセスを作成する'
            ];
        }
    }

    /**
     * 初期処理(一覧HTML生成)
     */
    private function create_data_list($post)
    {
        $list_html = $this->auto_rank_logic->create_data_list([
            $post['pageSize'],
            $post['pageNumber']
        ],  $post['search_select']);

        // AJAX返却用データ成型
        return [
            'status' => true,
            'html' => [
                $list_html['list_html'],
                $list_html['all_cnt']
            ],
        ];
    }
}