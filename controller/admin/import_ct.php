<?php

use Carbon\Carbon;

session_start();

require_once __DIR__ . '/../../logic/common/common_logic.php';
require_once __DIR__ . '/../../logic/admin/import_logic.php';
require_once __DIR__ . '/../../common/security_common_logic.php';

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
    $import_ct = new import_ct();
    $post_data = $_SERVER['REQUEST_METHOD'] == 'POST' ? $_POST : $_GET;

    // コントローラー呼び出し
    $data = $import_ct->main_control($post_data);
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
class import_ct
{
    /**
     * コンストラクタ
     */
    protected $import_logic;

    public function __construct()
    {
        // 管理画面ユーザーロジックインスタンス
        $this->import_logic = new import_logic();
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
        } else if ($post['method'] == 'edit_init') {
            // 編集初期処理
            $data = $this->get_detail($post['edit_del_id']);
        }

        return $data;
    }

    /**
     * 初期処理(一覧HTML生成)
     */
    private function create_data_list($post)
    {
        $list_html = $this->import_logic->create_data_list([
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

    /**
     * 編集初期処理(詳細情報取得)
     */
    private function get_detail($id)
    {
        $detail = $this->import_logic->getDetailById($id);
        // AJAX返却用データ成型
        return [
            'status' => true,
            'data' => [
                'data_type' => IMPORT_DATA_TYPE[$detail['data_type']] ?? '',
                'file_name' => $detail['file_name'] ?? '',
                'status' => IMPORT_STATUS[$detail['status']] ?? '',
                'status_code' => $detail['status'] ?? null,
                'success' => $detail['success'] ?? '',
                'error' => $detail['error'] ?? '',
                'error_file' => $detail['error_file'] ?? '',
                'created_at' => $detail['created_at'] ?  Carbon::parse($detail['created_at'])->format('Y-m-d H:i:s') : '',
                'completed_time' => $detail['completed_time'] ?  Carbon::parse($detail['completed_time'])->format('Y-m-d H:i:s') : '',
            ]
        ];
    }
}