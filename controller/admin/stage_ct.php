<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../../logic/common/common_logic.php';
require_once __DIR__ . '/../../logic/admin/stage_logic.php';
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
    $stage_ct = new stage_ct();
    $post_data = $_SERVER['REQUEST_METHOD'] == 'POST' ? $_POST : $_GET;

    // コントローラー呼び出し
    $data = $stage_ct->main_control($post_data);
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
class stage_ct
{
    /**
     * コンストラクタ
     */
    protected $stage_logic;

    public function __construct()
    {
        // 管理画面ユーザーロジックインスタンス
        $this->stage_logic = new stage_logic();
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
        $cancers = $this->stage_logic->get_cancer_list()->toArray();
        $hospitals = $this->stage_logic->get_hospital_list()->toArray();

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
        $list_html = $this->stage_logic->create_data_list([
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
        $hospital = $this->stage_logic->get_hospital_by_id($post['hospital_id'] ?? null);
        $cancer = $this->stage_logic->get_cancer_by_id($post['cancer_id'] ?? null);

        if (!$post['year']) {
            return [
                'status' => false,
                'error_code' => 0,
                'error_msg' => '無効な年',
                'return_url' => MEDICALNET_ADMIN_PATH . 'stage.php'
            ];
        }

        if (!$hospital || !$cancer) {
            return [
                'status' => false,
                'error_code' => 0,
                'error_msg' => '病院情報やがん情報が見つからない',
                'return_url' => MEDICALNET_ADMIN_PATH . 'stage.php'
            ];
        }

        $stageData = [
            'cancer_id' => $cancer->id,
            'hospital_id' => $hospital->id,
            'area_id' => $hospital->area_id,
            'hospital_name' => $hospital->hospital_name,
            'cancer_name_stage' => $cancer->cancer_type_stage,
            'year' => $post['year'],
            'total_num_new' => $post['total_num_new'] ?? null,
            'stage_new1' => $post['stage_new1'] ?? null,
            'stage_new2' => $post['stage_new2'] ?? null,
            'stage_new3' => $post['stage_new3'] ?? null,
            'stage_new4' => $post['stage_new4'] ?? null,
            'total_num_rank' => $post['total_num_rank'] ?? null,
            'local_num_rank' => $post['local_num_rank'] ?? null,
            'pref_num_rank' => $post['pref_num_rank'] ?? null,
            'total_num_rank_stage1' => $post['total_num_rank_stage1'] ?? null,
            'total_num_rank_stage2' => $post['total_num_rank_stage2'] ?? null,
            'total_num_rank_stage3' => $post['total_num_rank_stage3'] ?? null,
            'total_num_rank_stage4' => $post['total_num_rank_stage4'] ?? null,
            'local_num_rank_stage1' => $post['local_num_rank_stage1'] ?? null,
            'local_num_rank_stage2' => $post['local_num_rank_stage2'] ?? null,
            'local_num_rank_stage3' => $post['local_num_rank_stage3'] ?? null,
            'local_num_rank_stage4' => $post['local_num_rank_stage4'] ?? null,
            'pref_num_rank_stage1' => $post['pref_num_rank_stage1'] ?? null,
            'pref_num_rank_stage2' => $post['pref_num_rank_stage2'] ?? null,
            'pref_num_rank_stage3' => $post['pref_num_rank_stage3'] ?? null,
            'pref_num_rank_stage4' => $post['pref_num_rank_stage4'] ?? null,
        ];

        $stage = $this->stage_logic->createData($stageData);

        if (!$stage) {
            return [
                'status' => false,
                'error_code' => 0,
                'error_msg' => 'ステージデータが作成できない',
                'return_url' => MEDICALNET_ADMIN_PATH . 'stage.php'
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
    private function get_detail($id)
    {
        $detail = $this->stage_logic->getDetailById($id)->load(['cancer','hospital']);

        // AJAX返却用データ成型
        return array_merge([
            'status' => true,
            'hospital' => $detail->hospital ?? [],
            'cancer' => $detail->cancer ?? []
        ], $detail->toArray());
    }

    /**
     * 編集更新処理
     *
     */
    private function update_detail($post)
    {
        // 編集ロジック呼び出し
        $stage = $this->stage_logic->getDetailById($post['id']);
        if (!$stage) {
            return [
                'status' => false,
                'error_code' => 0,
                'error_msg' => 'Stage データが存在しません',
                'return_url' => MEDICALNET_ADMIN_PATH . 'stage.php'
            ];
        }

        if (!$post['year']) {
            return [
                'status' => false,
                'error_code' => 0,
                'error_msg' => '無効な年',
                'return_url' => MEDICALNET_ADMIN_PATH . 'stage.php'
            ];
        }

        $hospital = $this->stage_logic->get_hospital_by_id($post['hospital_id'] ?? null);
        $cancer = $this->stage_logic->get_cancer_by_id($post['cancer_id'] ?? null);

        if (!$hospital || !$cancer) {
            return [
                'status' => false,
                'error_code' => 0,
                'error_msg' => '病院情報やがん情報が見つからない',
                'return_url' => MEDICALNET_ADMIN_PATH . 'stage.php'
            ];
        }

        $updateData = array_map(function ($value) {
            return ($value == '') ? null : $value;
        }, $post);

        unset($updateData['id']);
        unset($updateData['method']);
        unset($updateData['edit_del_id']);
        unset($updateData['search_select']);

        if (!$this->stage_logic->updateData($stage->id, array_merge($updateData, ['area_id' => $hospital->area_id]))) {
            return [
                'status' => false,
                'error_code' => 0,
                'error_msg' => 'データ更新に失敗しました',
                'return_url' => MEDICALNET_ADMIN_PATH . 'stage.php'
            ];
        }

        // AJAX返却用データ成型
        return [
            'status' => true,
            'method' => 'update',
            'msg' => '変更しました'
        ];
    }

    /**
     * 有効化処理
     *
     */
    public function recovery($id)
    {
        // 更新ロジック呼び出し
        $this->stage_logic->recoveryData($id);

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
        $this->stage_logic->deleteData($id);

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
        $this->stage_logic->privateData($id);

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
        $this->stage_logic->releaseData($id);

        // AJAX返却用データ成型
        $data = array(
            'status' => true,
            'method' => 'release',
            'msg' => '公開しました'
        );
        return $data;
    }
}