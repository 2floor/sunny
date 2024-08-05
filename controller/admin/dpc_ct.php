<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../../logic/common/common_logic.php';
require_once __DIR__ . '/../../logic/admin/dpc_logic.php';
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
    $dpc_ct = new dpc_ct();
    $post_data = $_SERVER['REQUEST_METHOD'] == 'POST' ? $_POST : $_GET;

    // コントローラー呼び出し
    $data = $dpc_ct->main_control($post_data);
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
class dpc_ct
{
    /**
     * コンストラクタ
     */
    protected $dpc_logic;

    public function __construct()
    {
        // 管理画面ユーザーロジックインスタンス
        $this->dpc_logic = new dpc_logic();
    }

    /**
     * コントローラー
     * 各処理の振り分けをmethodの文字列により行う
     */
    public function main_control($post)
    {
        if ($post['method'] == 'init') {
            // 初期処理　HTML生成処理呼び出し
            $data = $this->create_data_list($post);
        } else if ($post['method'] == 'entry') {
            // 新規登録処理
            $data = $this->entry_new_data($post);
        } else if ($post['method'] == 'edit_init') {
            // 編集初期処理
            $data = $this->get_detail($post['edit_del_id']);
        } else if ($post['method'] == 'edit') {
            // 編集更新処理
            $data = $this->update_detail($post);
        } else if ($post['method'] == 'delete') {
            // 削除処理
            $data = $this->delete($post['id']);
        } else if ($post['method'] == 'recovery') {
            // 有効化処理
            $data = $this->recovery($post['id']);
        } else if ($post['method'] == 'private') {
            // 非公開化処理
            $data = $this->private_func($post['id']);
        } else if ($post['method'] == 'release') {
            // 公開化処理
            $data = $this->release($post['id']);
        }

        return $data;
    }

    public function init_entry_new()
    {
        $cancers = $this->dpc_logic->get_cancer_list()->toArray();
        $hospitals = $this->dpc_logic->get_hospital_list()->toArray();

        return [
            'cancers' => $cancers,
            'hospitals' => $hospitals,
        ];
    }

    /**
     * 初期処理(一覧HTML生成)
     */
    private function create_data_list($post)
    {
        $list_html = $this->dpc_logic->create_data_list([
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
     * 新規登録処理
     */
    private function entry_new_data($post)
    {
        // 登録ロジック呼び出し
        $hospital = $this->dpc_logic->get_hospital_by_id($post['hospital_id'] ?? null);
        $cancer = $this->dpc_logic->get_cancer_by_id($post['cancer_id'] ?? null);

        if (!$post['year']) {
            return [
                'status' => false,
                'error_code' => 0,
                'error_msg' => '無効な年',
                'return_url' => MEDICALNET_ADMIN_PATH . 'dpc.php'
            ];
        }

        if (!$hospital || !$cancer) {
            return [
                'status' => false,
                'error_code' => 0,
                'error_msg' => '病院情報やがん情報が見つからない',
                'return_url' => MEDICALNET_ADMIN_PATH . 'dpc.php'
            ];
        }

        $dpcData = [
            'cancer_id' => $cancer->id,
            'hospital_id' => $hospital->id,
            'area_id' => $hospital->area_id,
            'cancer_name_dpc' => $cancer->cancer_type_dpc,
            'hospital_name' => $hospital->hospital_name,
            'year' => $post['year'],
            'n_dpc' => ($post['n_dpc'] && $post['n_dpc'] != '') ? $post['n_dpc'] : null,
            'rank_nation_dpc' => ($post['rank_nation_dpc'] && $post['rank_nation_dpc'] != '') ? $post['rank_nation_dpc'] : null,
            'rank_area_dpc' => ($post['rank_area_dpc'] && $post['rank_area_dpc'] != '') ? $post['rank_area_dpc'] : null,
            'rank_pref_dpc' => ($post['rank_pref_dpc'] && $post['rank_pref_dpc'] != '') ? $post['rank_pref_dpc'] : null,
        ];

        $dpc = $this->dpc_logic->createData($dpcData);

        if (!$dpc) {
            return [
                'status' => false,
                'error_code' => 0,
                'error_msg' => 'DPCデータの作成に失敗しました',
                'return_url' => MEDICALNET_ADMIN_PATH . 'dpc.php'
            ];
        }

        // AJAX返却用データ成型
        return [
            'status' => true,
            'method' => 'entry',
            'msg' => '登録しました'
        ];
    }

    /**
     * 編集初期処理(詳細情報取得)
     *
     */
    private function get_detail($member_id)
    {
        $reult_detail = $this->hospital_logic->get_detail($member_id);

        // AJAX返却用データ成型
        $data = array(
            'status' => true,
            'name' => $reult_detail['name'],
            'name_kana' => $reult_detail['name_kana'],
            'office_name' => $reult_detail['office_name'],
            'office_name_kana' => $reult_detail['office_name_kana'],
            'zip' => $reult_detail['zip'],
            'pref' => $reult_detail['pref'],
            'addr' => $reult_detail['addr'],
            'tel' => $reult_detail['tel'],
            'tel2' => $reult_detail['tel2'],
            'fax' => $reult_detail['fax'],
            'resp_name' => $reult_detail['resp_name'],
            'job' => $reult_detail['job'],
            'mail' => $reult_detail['mail'],

            'payment' => $reult_detail['payment'],
            'jigyou' => $reult_detail['jigyou'],
            'truck_num' => $reult_detail['truck_num'],
            'url' => $reult_detail['url'],
            'questionnaire' => $reult_detail['questionnaire'],
            'etc1' => $reult_detail['etc1'],
            'etc2' => $reult_detail['etc2'],
            'etc3' => $reult_detail['s_code'],
            'etc4' => $reult_detail['etc4'],
            'etc5' => $reult_detail['etc5'],
            'etc6' => $reult_detail['etc6'],
            'etc7' => $reult_detail['etc7'],
            'etc8' => $reult_detail['etc8'],

        );

        return $data;
    }


    /**
     * 編集更新処理
     *
     */
    private function update_detail($post)
    {
        // 編集ロジック呼び出し
        $this->hospital_logic->update_detail(array(
            $post['name'],
            $post['name_kana'],
            $post['office_name'],
            $post['office_name_kana'],
            $post['zip'],
            $post['pref'],
            $post['addr'],
            $post['tel'],
            $post['tel2'],
            $post['fax'],
            $post['resp_name'],
            $post['job'],
            $post['mail'],
            $post['payment'],
            $post['jigyou'],
            $post['truck_num'],
            $post['url'],
            $post['questionnaire'],
            $post['etc1'],
            $post['etc2'],
            $post['s_code'],
            $post['etc4'],
            $post['etc5'],
            $post['etc6'],
            $post['etc7'],
            $post['etc8'],
            $post['edit_del_id']
        ));

        // AJAX返却用データ成型
        $data = array(
            'status' => true,
            'method' => 'update',
            'msg' => '変更しました'
        );

        return $data;
    }

    /**
     * 有効化処理
     *
     */
    public function recovery($id)
    {
        // 更新ロジック呼び出し
        $this->dpc_logic->recoveryData($id);

        // AJAX返却用データ成型
        $data = array(
            'status' => true,
            'method' => 'recovery',
            'msg' => '有効にしました'
        );
        return $data;
    }

    /**
     * 削除処理
     */
    public function delete($id)
    {
        // 更新ロジック呼び出し
        $this->dpc_logic->deleteData($id);

        // AJAX返却用データ成型
        $data = array(
            'status' => true,
            'method' => 'delete',
            'msg' => '削除しました'
        );
        return $data;
    }

    /**
     * 非公開処理
     */
    public function private_func($id)
    {
        // 更新ロジック呼び出し
        $this->dpc_logic->privateData($id);

        // AJAX返却用データ成型
        $data = array(
            'status' => true,
            'method' => 'private',
            'msg' => '非公開にしました'
        );
        return $data;
    }

    /**
     * 公開処理
     */
    public function release($id)
    {
        // 更新ロジック呼び出し
        $this->dpc_logic->releaseData($id);

        // AJAX返却用データ成型
        $data = array(
            'status' => true,
            'method' => 'release',
            'msg' => '公開しました'
        );
        return $data;
    }
}