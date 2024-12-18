<?php
session_start();
// header('Content-Type: application/json');

require_once __DIR__ . '/../../logic/common/common_logic.php';
require_once __DIR__ . '/../../logic/common/common_string_logic.php';
require_once __DIR__ . '/../../logic/admin/admin_user_logic.php';
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
	$admin_user_ct = new admin_user_ct();

	// コントローラー呼び出し
	$data = $admin_user_ct->main_control($_POST);
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
class admin_user_ct
{

	private $admin_user_logic;
	private $common_string_logic;
	/**
	 * コンストラクタ
	 */
	public function __construct()
	{
		// 管理画面ユーザーロジックインスタンス
		$this->admin_user_logic = new admin_user_logic();
		$this->common_string_logic = new common_string_logic();
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
			$data = $this->create_admin_user_list_html();
		} else if ($post['method'] == 'edit_init') {
			// 編集初期処理
			$data = $this->get_admin_user_detail_html($post['edit_del_id']);
		} else if ($post['method'] == 'edit') {
			// 編集更新処理
			$data = $this->update_admin_user_detail($post);
		} else if ($post['method'] == 'del') {
			// 削除処理
			$data = $this->del_admin_user($post['del_id']);
		} else if ($post['method'] == 'entry') {
			// 新規ユーザー登録処理
			$data = $this->entry_admin_user($post);
		} else if ($post['method'] == 'recovery') {
			// ユーザー有効化処理
			$data = $this->recovery($post['recovery_id']);
		}

		return $data;
	}

	/**
	 * 初期処理(一覧HTML生成)
	 */
	private function create_admin_user_list_html()
	{
		$admin_user_list_html = $this->admin_user_logic->create_admin_user_list_html();

		// AJAX返却用データ成型
		$data = array(
			'status' => true,
			'html' => $admin_user_list_html['list_html'],
			'edit_menu_list_html' => $admin_user_list_html['entry_menu_list_html']
		);

		return $data;
	}

	/**
	 * 管理画面ユーザー登録処理
	 */
	private function entry_admin_user($post)
	{

		// ログインID重複チェック
		$chk_result = $this->admin_user_logic->chk_admin_login_id($post['id']);

		if ($chk_result) {

			// メニュー権限成型
			$authority_menu_comma = $this->common_string_logic->convert_comma_by_array($post['admin_authority']);

			// 登録ロジック呼び出し
			$this->admin_user_logic->new_entry_admin_user(array(
				'0',
				$post['user_id'],
				$post['id'],
				$post['mail'],
				$post['password'],
				$post['admin_authority'],
				'',
				'0'
			), array(
				$post['password'],
				$post['conf_password']
			));

			// AJAX返却用データ成型
			$data = array(
				'status' => true,
				'method' => 'entry',
				'msg' => '登録しました'
			);
		} else {
			// ログインID重複返却
			$data = array(
				'status' => true,
				'method' => 'entry',
				'msg' => 'そのログインIDは既に利用されています。'
			);
		}
		return $data;
	}

	/**
	 * 管理画面ユーザー編集初期処理(ユーザー詳細情報取得)
	 *
	 * @param unknown $id
	 */
	private function get_admin_user_detail_html($id)
	{
		$admin_user_detail = $this->admin_user_logic->create_admin_user_detail_html($id);

		// 配列に変換
		// 		$admin_user_detail ['authority'] = explode ( ',', $admin_user_detail ['authority'] );

		// AJAX返却用データ成型
		$data = array(
			'status' => true,
			'user_id' => $admin_user_detail['login_id'],
			'id' => $admin_user_detail['name'],
			'admin_authority' => $admin_user_detail['authority']
		);

		return $data;
	}

	/**
	 * 管理画面ユーザー編集更新処理
	 *
	 * @param unknown $id
	 */
	private function update_admin_user_detail($post)
	{

		// メニュー権限成型
		$authority_menu_comma = $this->common_string_logic->convert_comma_by_array($post['admin_authority']);

		// 編集ロジック呼び出し
		$this->admin_user_logic->update_admin_user(array(
			$post['user_id'],
			$post['id'],
			$post['mail'],
			$post['password'],
			$authority_menu_comma,
			$post['edit_del_id']
		), array(
			$post['password'],
			$post['conf_password']
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
	 * @param unknown $id
	 */
	public function recovery($id)
	{
		// 更新ロジック呼び出し
		$this->admin_user_logic->recoveryl_admin_user($id);

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
	 *
	 * @param unknown $post
	 */
	public function del_admin_user($id)
	{
		// 更新ロジック呼び出し
		$this->admin_user_logic->del_admin_user($id);

		// AJAX返却用データ成型
		$data = array(
			'status' => true,
			'method' => 'delete',
			'msg' => '削除しました'
		);
		return $data;
	}
}
