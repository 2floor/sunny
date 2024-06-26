<?php
session_start ();
// header('Content-Type: application/json');

require_once __DIR__ . '/../../logic/common/common_logic.php';
require_once __DIR__ . '/../../common/security_common_logic.php';

$common_ct = new common_ct ();

/**
 * コントローラ呼び出し
 */
$data = $common_ct->main_control ( $_POST, $_FILES );

// AJAXへ返却
echo json_encode ( compact ( 'data' ) );

/**
 * 管理画面ユーザー管理処理
 * (セキュリティ保持の為Logic呼び出しをコントローラー限定にする為、各ロジック呼び出しをクラス化し、かつ、privateとする。)
 *
 * @author Seidou
 *
 */
class common_ct {
	private $common_logic;

	/**
	 * コンストラクタ
	 */
	public function __construct() {
		// 管理画面ユーザーロジックインスタンス
		$this->common_logic = new common_logic ();
	}

	/**
	 * コントロール
	 *
	 * @param unknown $post
	 */
	public function main_control($post, $file = null) {
		$ct = new common_ct ();
		if ($post ['method'] == 'area') {
			// エリアセレクトボックスHTML生成
			$data = $ct->create_area_select_html ();
		} else if ($post ['method'] == 'pref') {
			// 都道府県セレクトボックスHTML生成
			$data = $ct->create_pref_select_html ( $post );
		} else if ($post ['method'] == 'area_by_pref') {
			// 都道府県からエリアセレクトボックスHTML生成
			$data = $ct->create_area_select_html_by_pref ( $post );
		} else if ($post ['method'] == 'img_upload') {
			// ファイルアップロード処理
			$data = $this->common_logic->unit_file_upload ( $file ['file1'], '../../upload_files/' . $post ['path'] );
		} else if ($post ['method'] == 'login_id_check') {
			//重複チェック
			$data = $ct->double_check($post);
		} else if ($post ['method'] == 'plural_img_upload') {

			$file_obj_name = 'file' . $post ['file_no'];

			// ファイルアップロード処理
			$data = $this->common_logic->unit_file_upload ( $file [$file_obj_name], '../../upload_files/' . $post ['path'] );

			$data ['file_no'] = $post ['file_no'];
		} else if ($post ['method'] == 'front_plural_img_upload') {

			$file_obj_name = 'file' . $post ['file_no'];

			// ファイルアップロード処理
			$data = $this->common_logic->front_unit_file_upload ( $file [$file_obj_name], '../../upload_files/' . $post ['path'] );

			$data ['file_no'] = $post ['file_no'];
		} else if ($post ['method'] == 'pass_post_data') {
			//POSTデータ受け渡し処理
			$result = $this->common_logic->set_session_by_postdata($post, $post['ses_name']);
			$data = array(
					'status' => isset($_SESSION[$post['ses_name']]),
			);
		}
		return $data;
	}

	/**
	 * エリアセレクトボックスHTML生成
	 */
	private function create_area_select_html() {
		$list_html = $this->common_logic->create_area_select_html ();

		// AJAX返却用データ成型
		$data = array (
				'status' => true,
				'area_html' => $list_html
		);
		return $data;
	}

	/**
	 * 都道府県セレクトボックスHTML生成
	 *
	 * @param unknown $post
	 */
	private function create_pref_select_html($post) {

		// セキュリティクラスインスタンス生成
		$security_common_logic = new security_common_logic ();

		// セキュリティロジック実行
		$post_datas = $security_common_logic->in_request ( array (
				'area' => $post ['area']
		), true, true, true );

		// DB処理はセキュリティロジックの結果配列の0番目がtrueの時のみ行う、falseの場合はパラメータに不正があった場合
		if ($post_datas [0]) {

			$list_html = $this->common_logic->create_pref_select_html ( $post_datas [1] ['area'] );

			// AJAX返却用データ成型
			$data = array (
					'status' => true,
					'pref_html' => $list_html
			);
		} else {
			// パラメータに不正があった場合
			// AJAX返却用データ成型
			$data = array (
					'status' => false,
					'status_code' => 2,
					'return_url' => 'logout.php'
			);
		}
		return $data;
	}

	/**
	 * 都道府県からエリア取得
	 *
	 * @param unknown $post
	 */
	private function create_area_select_html_by_pref($post) {
		// セキュリティクラスインスタンス生成
		$security_common_logic = new security_common_logic ();

		// セキュリティロジック実行
		$post_datas = $security_common_logic->in_request ( array (
				'pref' => $post ['pref']
		), true, true, true );

		// DB処理はセキュリティロジックの結果配列の0番目がtrueの時のみ行う、falseの場合はパラメータに不正があった場合
		if ($post_datas [0]) {

			$area = $this->common_logic->create_area_select_html_by_pref ( $post_datas [1] ['pref'] );

			// AJAX返却用データ成型
			$data = array (
					'status' => true,
					'area' => $area
			);
		} else {
			// パラメータに不正があった場合
			// AJAX返却用データ成型
			$data = array (
					'status' => false,
					'status_code' => 2,
					'return_url' => 'logout.php'
			);
		}
		return $data;
	}


}


