<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../../logic/common/common_logic.php';
require_once __DIR__ . '/../../logic/admin/hospital_logic.php';
require_once __DIR__ . '/../../logic/admin/category_logic.php';
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
    $hospital_ct = new hospital_ct();
    $post_data = $_SERVER['REQUEST_METHOD'] == 'POST' ? $_POST : $_GET;

    // コントローラー呼び出し
    $data = $hospital_ct->main_control($post_data);
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
class hospital_ct
{
    /**
     * コンストラクタ
     */
    protected $hospital_logic;

    public function __construct()
    {
        // 管理画面ユーザーロジックインスタンス
        $this->hospital_logic = new hospital_logic();
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
        $category_logic = new category_logic();
        $cancers = $this->hospital_logic->get_cancer_list()->toArray();
        $area = $this->hospital_logic->get_area_list()->toArray();
        $grouped_category = $category_logic->get_grouped_data_list(\App\Models\Category::HOSPITAL_GROUP)->toArray();


        return [
            'cancers' => $cancers,
            'grouped_category' => $grouped_category,
            'area' => $area
        ];
    }

    /**
     * 初期処理(一覧HTML生成)
     */
    private function create_data_list($post)
    {
        $list_html = $this->hospital_logic->create_data_list([
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
        $this->hospital_logic->entry_new_data(array(
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
            $post['password'],
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
            '0',
        ));

        // AJAX返却用データ成型
        $data = array(
            'status' => true,
            'method' => 'entry',
            'msg' => '登録しました'
        );

        return $data;
    }

    /**
     * 編集初期処理(詳細情報取得)
     *
     */
    private function get_detail($id)
    {
        $detail = $this->hospital_logic->getDetailById($id)->load('area');
        $detail = $detail ? $detail->toArray() : [];

        $categories = $this->hospital_logic->get_category_by_hospital_id($id);
        if ($categories) {
            $categories = $categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'is_whole_cancer' => $category->is_whole_cancer,
                    'content1' => $category->pivot->content1,
                    'cancer_id' => $category->pivot->cancer_id,
                ];
            })->toArray();
        }

        $cancers = $this->hospital_logic->get_cancer_by_hospital_id($id)?->pluck('id');

        // AJAX返却用データ成型
        return [
            'status' => true,
            'hospital_code' => $detail['hospital_code'] ?? '',
            'hospital_name' => $detail['hospital_name'] ?? '',
            'addr' => $detail['addr'] ?? '',
            'tel' => $detail['tel'] ?? '',
            'hp_url' => $detail['hp_url'] ?? '',
            'social_info' => $detail['social_info'] ?? '',
            'support_url' => $detail['support_url'] ?? '',
            'introduction_url' => $detail['introduction_url'] ?? '',
            'remarks' => $detail['remarks'] ?? '',
            'area' => $detail['area'] ?? [],
            'cancers' =>  $cancers ?? [],
            'categories' => $categories ?? [],
        ];
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
    private function recovery($id)
    {
        // 更新ロジック呼び出し
        $this->hospital_logic->recoveryData($id);

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
    private function delete($id)
    {
        // 更新ロジック呼び出し
        $this->hospital_logic->deleteData($id);

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
    private function private_func($id)
    {
        // 更新ロジック呼び出し
        $this->hospital_logic->privateData($id);

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
    private function release($id)
    {
        // 更新ロジック呼び出し
        $this->hospital_logic->releaseData($id);

        // AJAX返却用データ成型
        $data = array(
            'status' => true,
            'method' => 'release',
            'msg' => '公開しました'
        );
        return $data;
    }
}