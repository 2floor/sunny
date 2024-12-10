<?php
if (!isset($_SESSION)) {
	session_start();
}

require_once __DIR__ . '/../../logic/common/common_logic.php';
require_once __DIR__ . '/../../logic/admin/missmatch_logic.php';
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
	$missmatch_ct = new missmatch_ct();
	$post_data = $_SERVER['REQUEST_METHOD'] == 'POST' ? $_POST : $_GET;

	// コントローラー呼び出し
	$data = $missmatch_ct->main_control($post_data);
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
class missmatch_ct
{
	/**
	 * コンストラクタ
	 */
	protected $missmatch_logic;
	protected $common_logic;

	public function __construct()
	{
		// 管理画面ユーザーロジックインスタンス
		$this->missmatch_logic = new missmatch_logic();
		$this->common_logic = new common_logic();
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
		} else if ($post['method'] == 'delete_list') {
			$data = $this->delete_list($post['id']);
		} else if ($post['method'] == 'accept_list') {
			$data = $this->accept_list($post['id']);
		}

		return $data;
	}

	/**
	 * 初期処理(一覧HTML生成)
	 */
	private function create_data_list($post)
	{
		$list_html = $this->missmatch_logic->create_data_list([
			$post['pageSize'],
			$post['pageNumber']
		],  $post['search_select']);

		// AJAX返却用データ成型
		return [
			'status' => true,
			'html' => [
				$list_html['list_html'],
				$list_html['all_cnt'],
				$list_html['list_year'],
				$list_html['process_type'],
			],
		];
	}

	/**
	 * 新規登録処理
	 */
	private function entry_new_data($post)
	{
		// 登録ロジック呼び出し
		$faqData = [
			'question' => $post['question'] ?? null,
			'answer' => $post['answer'] ?? null,
			'group_answer' => $post['group_answer'] ?? null,
		];

		$faq = $this->missmatch_logic->createData($faqData);

		if (!$faq) {
			return [
				'status' => false,
				'error_code' => 0,
				'error_msg' => 'FAQデータを作成できません',
				'return_url' => MEDICALNET_ADMIN_PATH . 'faq.php'
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
		$detail = $this->missmatch_logic->getDetailById($id);

		// AJAX返却用データ成型
		return array_merge([
			'status' => true,
		], $detail->toArray());
	}

	/**
	 * 編集更新処理
	 *
	 */
	private function update_detail($post)
	{
		// 編集ロジック呼び出し
		$faq = $this->missmatch_logic->getDetailById($post['id']);
		if (!$faq) {
			return [
				'status' => false,
				'error_code' => 0,
				'error_msg' => 'faq データが存在しません',
				'return_url' => MEDICALNET_ADMIN_PATH . 'faq.php'
			];
		}

		$updatedData = [
			'question' => $post['question'] ?? null,
			'answer' => $post['answer'] ?? null,
			'group_answer' => $post['group_answer'] ?? null,
		];

		if (!$this->missmatch_logic->updateData($faq->id, $updatedData)) {
			return [
				'status' => false,
				'error_code' => 0,
				'error_msg' => 'データ更新に失敗しました',
				'return_url' => MEDICALNET_ADMIN_PATH . 'faq.php'
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
		$this->missmatch_logic->recoveryData($id);

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
		$this->missmatch_logic->deleteData($id);

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
		$this->missmatch_logic->privateData($id);

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
		$this->missmatch_logic->releaseData($id);

		// AJAX返却用データ成型
		$data = array(
			'status' => true,
			'method' => 'release',
			'msg' => '公開しました'
		);
		return $data;
	}

	public function delete_list($id)
	{
		$ids = explode(',', $id);
		foreach ($ids as $key => $idv) {
			$this->missmatch_logic->deleteData($idv);
		}

		$data = array(
			'status' => true,
			'method' => 'delete',
			'msg' => '削除しました'
		);
		return $data;
	}
	public function accept_list($id)
	{
		$ids = explode(',', $id);
		foreach ($ids as $key => $idv) {
			$this->missmatch_logic->acceptData($idv);
		}

		$data = array(
			'status' => true,
			'method' => 'accept',
			'msg' => '承認しました'
		);
		return $data;
	}
}
