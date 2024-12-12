<?php

use Illuminate\Support\Facades\DB;

session_start();
// header('Content-Type: applisampleion/json');

require_once __DIR__ . '/../../logic/common/common_logic.php';
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
	$category_ct = new category_ct();
	$post_data = $_SERVER['REQUEST_METHOD'] == 'POST' ? $_POST : $_GET;

	// コントローラー呼び出し
	$data = $category_ct->main_control($post_data);
} else {
	// パラメータに不正があった場合
	// AJAX返却用データ成型
	$data = array(
		'status' => false,
		'input_datas' => $post,
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
class category_ct
{
	private $category_logic;
	private $common_logic;

	/**
	 * コンストラクタ
	 */
	public function __construct()
	{
		// 管理画面ユーザーロジックインスタンス
		$this->category_logic = new category_logic();
		$this->common_logic = new common_logic();
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

	/**
	 * 初期処理(一覧HTML生成)
	 */
	private function create_data_list($post)
	{
		$list_html = $this->category_logic->create_data_list([
			$post['pageSize'],
			$post['pageNumber']
		],  $post['search_select']);

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
        $data_type = null;
        $level1 = $this->category_logic->getListByWhereClause(['level1' => $post['level1'] ?? ''])->first();
        $level2 = $this->category_logic->getListByWhereClause(
            [
                'level1' => $post['level1'] ?? '',
                'level2' => $post['level2'] ?? '',
            ]
        )->sortByDesc('order_num3')->first();

        if ($level1) {
            $data_type = $level1->data_type;
        }

        if (!$post['order_num3']) {
            $order_num3 = $level2 ? ($level2->order_num3 + 1) : 1;
        } else {
            $existed_level2 = $this->category_logic->getListByWhereClause(
                [
                    'level1' => $post['level1'] ?? '',
                    'level2' => $post['level2'] ?? '',
                    'order_num3' => $post['order_num3'],
                ]
            )->first();

            if ($existed_level2) {
                $existed_level2->update(['order_num3' => $level2->order_num3 + 1]);
                $order_num3 = $post['order_num3'];
            } else {
                $order_num3 = $level2 ? ($level2->order_num3 + 1) : 1;
            }
        }


		$data = [
            'level1' => $post['level1'] ?? '',
            'level2' => $post['level2'] ?? '',
            'level3' => $post['level3'] ?? '',
            'category_group' => $post['category_group'] ?? '',
            'is_whole_cancer' => $post['is_whole_cancer'] ?? '',
            'order_num3' => $order_num3,
            'data_type' => $data_type,
        ];

		// 登録ロジック呼び出し
		$category = $this->category_logic->createData($data);
		if (!$category) {
			return [
				'status' => false,
				'error_code' => 0,
				'error_msg' => 'ディレクトリデータを作成できません',
				'return_url' =>  MEDICALNET_ADMIN_PATH . 'category.php'
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
	 * @param unknown $id
	 */
	private function get_detail($id)
	{
		$detail = $this->category_logic->getDetailById($id);

		// AJAX返却用データ成型
		return array_merge([
			'status' => true,
		], $detail->toArray());
	}


	/**
	 * 編集更新処理
	 *
	 * @param unknown $id
	 */
	private function update_detail($post)
	{
		// 編集ロジック呼び出し
		$category = $this->category_logic->getDetailById($post['id']);
		if (!$category) {
			return [
				'status' => false,
				'error_code' => 0,
				'error_msg' => '',
				'return_url' => ''
			];
		}

        $data_type = null;
        $level1 = $this->category_logic->getListByWhereClause(['level1' => $post['level1'] ?? ''])->first();
        $level2 = $this->category_logic->getListByWhereClause(
            [
                'level1' => $post['level1'] ?? '',
                'level2' => $post['level2'] ?? '',
            ]
        )->sortByDesc('order_num3')->first();

        if ($level1) {
            $data_type = $level1->data_type;
        }

        if ($level2) {
            if ($level2->level1 == $category->level1 && $level2->level2 == $category->level2) {
                $order_num3 = $category->order_num3;
                if ($post['order_num3'] && $post['order_num3'] != $category->order_num3) {
                    $existed_level2 = $this->category_logic->getListByWhereClause(
                        [
                            'level1' => $post['level1'] ?? '',
                            'level2' => $post['level2'] ?? '',
                            'order_num3' => $post['order_num3'],
                        ]
                    )->first();

                    if ($existed_level2) {
                        $existed_level2->update(['order_num3' => $category->order_num3]);
                        $order_num3 = $post['order_num3'];
                    } else {
                        $order_num3 = $level2->order_num3;
                        \App\Models\Category::where([
                            'level1' => $post['level1'] ?? '',
                            'level2' => $post['level2'] ?? '',
                        ])->where('order_num3', '>', $category->order_num3)->update(['order_num3'  => DB::raw('order_num3 - 1')]);
                    }
                }
            } else {
                if (!$post['order_num3']) {
                    $order_num3 = $level2->order_num3 + 1;
                } else {
                    $existed_level2 = $this->category_logic->getListByWhereClause(
                        [
                            'level1' => $post['level1'] ?? '',
                            'level2' => $post['level2'] ?? '',
                            'order_num3' => $post['order_num3'],
                        ]
                    )->first();

                    if ($existed_level2) {
                        $existed_level2->update(['order_num3' => $level2->order_num3 + 1]);
                        $order_num3 = $post['order_num3'];
                    } else {
                        $order_num3 = $level2->order_num3 + 1;
                    }
                }
            }
        } else {
            $order_num3 = 1;
        }

        $updatedData = [
            'level1' => $post['level1'] ?? '',
            'level2' => $post['level2'] ?? '',
            'level3' => $post['level3'] ?? '',
            'category_group' => $post['category_group'] ?? '',
            'is_whole_cancer' => $post['is_whole_cancer'] ?? '',
            'order_num3' => $order_num3,
            'data_type' => $data_type,
        ];

		if (!$this->category_logic->updateData($category->id, $updatedData)) {
			return [
				'status' => false,
				'error_code' => 0,
				'error_msg' => '',
				'return_url' => 'category.php'
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
	 * @param unknown $id
	 */
	public function recovery($id)
	{
		// 更新ロジック呼び出し
		$this->category_logic->recoveryData($id);

		// AJAX返却用データ成型
		$data = [
			'status' => true,
			'method' => 'recovery',
			'msg' => '有効にしました'
		];
		return $data;
	}

	/**
	 * 削除処理
	 *
	 * @param unknown $post
	 */
	public function delete($id)
	{
		// 更新ロジック呼び出し
		$this->category_logic->deleteData($id);

		// AJAX返却用データ成型
		$data = [
			'status' => true,
			'method' => 'delete',
			'msg' => '削除しました'
		];
		return $data;
	}

	/**
	 * 非公開処理
	 *
	 * @param unknown $id
	 */
	public function private_func($id)
	{
		// 更新ロジック呼び出し
		$this->category_logic->privateData($id);

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
	 *
	 * @param unknown $post
	 */
	public function release($id)
	{
		// 更新ロジック呼び出し
		$this->category_logic->releaseData($id);

		// AJAX返却用データ成型
		$data = array(
			'status' => true,
			'method' => 'release',
			'msg' => '公開しました'
		);
		return $data;
	}
}
