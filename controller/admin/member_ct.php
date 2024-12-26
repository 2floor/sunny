<?php
session_start();
// header('Content-Type: applimemberion/json');

require_once __DIR__ . '/../../logic/common/common_logic.php';
require_once __DIR__ . '/../../logic/common/common_string_logic.php';
require_once __DIR__ . '/../../logic/admin/member_logic.php';
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
	$member_ct = new member_ct();

	// コントローラー呼び出し
	$data = $member_ct->main_control($_POST);
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
class member_ct
{
	private $member_ct;
	private $common_logic;

	/**
	 * コンストラクタ
	 */
	public function __construct()
	{
		// 管理画面ユーザーロジックインスタンス
		$this->member_logic = new member_logic();
		$this->common_string_logic = new common_string_logic();
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
		$member_ct = new member_ct();
		if ($post['method'] == 'init') {
			// 初期処理　HTML生成処理呼び出し
			$data = $member_ct->create_data_list($post);
		} else if ($post['method'] == 'entry') {
			// 新規登録処理
			$data = $member_ct->entry_new_data($post);
		} else if ($post['method'] == 'edit_init') {
			// 編集初期処理
			$data = $member_ct->get_detail($post['edit_del_id']);
		} else if ($post['method'] == 'edit') {
			// 編集更新処理
			$data = $member_ct->update_detail($post);
		} else if ($post['method'] == 'delete') {
			// 削除処理
			$data = $member_ct->delete($post['id']);
		} else if ($post['method'] == 'recovery') {
			// 有効化処理
			$data = $member_ct->recovery($post['id']);
		} else if ($post['method'] == 'private') {
			// 非公開化処理
			$data = $member_ct->private_func($post['id']);
		} else if ($post['method'] == 'release') {
			// 公開化処理
			$data = $member_ct->release($post['id']);
		} else if ($post['method'] == 'change_state') {
			$data = $member_ct->change_state($post);
		}

		return $data;
	}

	/**
	 * 初期処理(一覧HTML生成)
	 */
	private function create_data_list($post)
	{

		$list_html = $this->member_logic->create_data_list(array(
			$post['now_page_num'], // 現在のページ
			$post['get_next_disp_page'], // 次に表示するページ
			$post['page_disp_cnt'],
		),  $post['search_select']);

		// AJAX返却用データ成型
		$data = array(
			'status' => true,
			'empty' => '',
			'edit_menu_list_html' => $list_html['entry_menu_list_html'],
			'html' => $list_html['list_html'],
			'cnt' => $list_html['all_cnt'],
			'pager_html' => $list_html['pager_html'],
			'page_cnt' => $list_html['page_cnt'],
		);

		return $data;
	}

	/**
	 * 新規登録処理
	 */
	private function entry_new_data($post)
	{

		$common_logic = new common_logic();
		$post['password'] = $common_logic->convert_password_encode($post['password']);

		// 登録ロジック呼び出し
		$this->member_logic->entry_new_data(array(
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
	 * @param unknown $id
	 */
	private function get_detail($member_id)
	{
		$reult_detail = $this->member_logic->get_detail($member_id);

		// $exp = explode(",", $reult_detail['jigyou']);
		// var_dump($exp);
		// exit;

		// foreach ($exp as $arr) {
		// 	if ($arr == 0) {
		// 		$jigyo_para_1 = 0;
		// 	} elseif ($arr == 1) {
		// 		$jigyo_para_2 = 1;
		// 	}
		// }

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


		// var_dump($data);
		// exit;
		return $data;
	}


	/**
	 * 編集更新処理
	 *
	 * @param unknown $id
	 */
	private function update_detail($post)
	{
		$common_logic = new common_logic();

		if ($post['password'] != null && $post['password'] != '') {
			$this->member_logic->update_detail_pw(array(
				$common_logic->convert_password_encode($post['password']),
				$post['edit_del_id'],
			));
		}


		// 編集ロジック呼び出し
		$this->member_logic->update_detail(array(
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
	 * @param unknown $id
	 */
	public function recovery($id)
	{
		// 更新ロジック呼び出し
		$this->member_logic->recoveryl_func($id);

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
	public function delete($id)
	{
		// 更新ロジック呼び出し
		$this->member_logic->del_func($id);

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
	 *
	 * @param unknown $id
	 */
	public function private_func($id)
	{
		// 更新ロジック呼び出し
		$this->member_logic->private_func($id);

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
		$this->member_logic->release_func($id);

		// AJAX返却用データ成型
		$data = array(
			'status' => true,
			'method' => 'release',
			'msg' => '公開しました'
		);
		return $data;
	}

	public function change_state($post)
	{
		$this->common_logic->update_logic("t_member", " where member_id = ? ", array("questionnaire"), array(
			$post['ty'],
			$post['member_id']
		));

		$member = $this->common_logic->select_logic("select * from t_member where member_id = ? ", array($post['member_id']));


		if ($post['ty'] == 1) {
			$from = "info@logifill.jp";
			$to = $member[0]['mail'];
			$subject = "【LOGI FILL】会員情報認証完了のお知らせ";
			$body = "―――――――――――――――――――――――――――――――――――
  このメッセージは LOGI FILL より自動送信されています。
  心当たりのない場合は、お問い合わせメールinfo@logifill.jp よりご連絡ください。
―――――――――――――――――――――――――――――――――――

" . $member[0]['name'] . "　様

いつもご利用頂き、誠にありがとうございます。
LOGI FILLへの会員情報認証が完了いたしました。

下記URLよりログインをお願い致します。
https://logifill.jp/login.php
今後ともLOGI FILLをよろしくお願いいたします。

================================================================
株式会社LOGI FILL-ロジフィル
住所：　〒253-0044 
　　　　神奈川県茅ヶ崎市新栄町7-5Chigasaki Biz-naz3F
HP　：　https://logifill.jp
Mail：　info@logifill.jp
================================================================
";

			$this->common_logic->mail_send($to, $subject, $body, $from);
		}

		// AJAX返却用データ成型
		$data = array(
			'status' => true,
			'method' => 'release',
			'msg' => '公開しました'
		);
		return $data;
	}
}
