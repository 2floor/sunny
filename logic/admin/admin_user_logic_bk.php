<?php
require_once __DIR__ . '/../../model/t_admin_model.php';
require_once __DIR__ . '/../../model/t_admin_menu_model.php';
require_once __DIR__ . '/../../logic/common/common_logic.php';
class admin_user_logic_bk {
	private $t_admin_model;
	private $t_admin_menu_model;
	private $common_logic;

	/**
	 * コンストラクタ
	 */
	public function __construct() {
		// 管理画面ユーザー
		$this->t_admin_model = new t_admin_model ();
		$this->t_admin_menu_model = new t_admin_menu_model ();
		$this->common_logic = new common_logic ();
	}

	/**
	 * 管理画面ユーザー管理初期HTML生成
	 */
	public function create_admin_user_list_html() {
		// 管理画面ユーザー情報取得
		$result = $this->t_admin_model->get_admin_user_list ();

		// 管理画面メニュー情報取得
		$result_menu = $this->t_admin_menu_model->get_admin_menu_list ();

		$return_html = "";
		$back_color = 1;
		$cnt = 0;
		for($i = 0; $i < count ( $result ); $i ++) {
			$row = $result [$i];

			if ($row ['admin_user_id'] != 2) {

				$cnt ++;
				$edit_html = '&nbsp;';

				// 管理画面ユーザー権限成型
				$authority_list = explode ( ',', $row ['authority'] );
				$admin_user_id = $this->common_logic->zero_padding ( $row ['admin_user_id'] );
				$member_id = $this->common_logic->zero_padding ( $row ['member_id'] );

				// 各種変数初期化
				$admin_menu_name_html = "";
				$admin_menu_authority_flg_html = "";
				$admin_menu_list_html = "";

				for($n = 0; $n < count ( $result_menu ); $n ++) {
					$row_menu = $result_menu [$n];

					// 管理画面ユーザー権限デフォルト値設定
					$authority_flg = "×";

					// 管理画面ユーザー権限判定
					if (in_array ( $row_menu ['admin_menu_id'], $authority_list )) {
						$authority_flg = "○";
					}

					// 管理画面メニュー名設定
					$admin_menu_name_html .= "<td style='border: 1px solid rgba(0, 0, 0, 0.1);'>" . $row_menu ['admin_menu_name'] . '</td>';

					// 管理画面ユーザー権限表示用設定
					$admin_menu_authority_flg_html .= "<td style='border: 1px solid rgba(0, 0, 0, 0.1); text-align:center;'>" . $authority_flg . '</td>';

					// 管理画面ユーザー登録用権限一覧HTML設定
					$admin_menu_list_html .= '
								<td>
									<input type="checkbox" name="admin_authority" value="' . $row_menu ['admin_menu_id'] . '" class="checkboxRequired">&nbsp;<label>' . $row_menu ['admin_menu_name'] . '</label>
								</td>';
				}

				$del_color = "";
				$del_html = "有効";
				if ($row ['del_flg'] == 1) {
					$del_color = "color:#d3d3d3";
					$del_html = "削除";

					if ($row ['admin_user_id'] != 23) {
						$edit_html = "<a herf='#' class='edit' name='edit_" . $row ['admin_user_id'] . "' value='" . $row ['admin_user_id'] . "'>管理画面ユーザーの変更をする</a><br>";
						$edit_html .= "<a herf='#' class='recovery' name='recovery_" . $row ['admin_user_id'] . "' value='" . $row ['admin_user_id'] . "'>管理画面ユーザーを有効にする</a>";
					}
				} else {
					if ($row ['admin_user_id'] != 23) {
						$edit_html = "<a herf='#' class='edit' name='edit_" . $row ['admin_user_id'] . "' value='" . $row ['admin_user_id'] . "'>管理画面ユーザーの変更をする</a><br>";
						$edit_html .= "<a herf='#' class='del' name='del_" . $row ['admin_user_id'] . "' value='" . $row ['admin_user_id'] . "'>管理画面ユーザーの削除をする</a>";
					}
				}

				// テーブル偶数行背景色変更設定
				if ($back_color == 2) {
					$back_color_html = "style='background: #f7f7f9; " . $del_color . "'";
					$back_color_bottom_html = "style='background: #f7f7f9; border-bottom:solid 2px #d0d0d0;'";
				} else {
					$back_color_html = "style='background: #ffffff; " . $del_color . "'";
					$back_color_bottom_html = "style='background: #ffffff; border-bottom:solid 2px #d0d0d0;'";
				}

				// 管理画面ユーザー一覧HTML設定
				$return_html .= "
					<tr " . $back_color_html . ">
						<td>" . $cnt . "</td>
						<td>" . $admin_user_id . "</td>
						<td>" . $del_html . "</td>
						<td>" . $row ['login_id'] . "</td>
						<td>**********</td>
						<td>" . $row ['name'] . "</td>
						<td><a href='mailto:" . $row ['mail'] . "'>" . $row ['mail'] . "</a></td>
						<td>" . $row ['create_at'] . "</td>
						<td>" . $row ['update_at'] . "</td>
						<td width='200'>
							$edit_html
						</td>
					</tr>
					<tr " . $back_color_html . ">
						<td colspan='10'>
							<table>
								<tr>
									" . $admin_menu_name_html . "
								</tr>
								<tr>
									" . $admin_menu_authority_flg_html . "
								</tr>
							</table>
						</td>
					</tr>";
				$back_color ++;

				if ($back_color >= 3) {
					$back_color = 1;
				}
			}
		}

		return array (
				"list_html" => $return_html,
				"entry_menu_list_html" => $admin_menu_list_html
		);
	}

	/**
	 * 管理画面ユーザー新規登録処理
	 *
	 * @param unknown $_post_datas
	 * @return boolean
	 */
	public function new_entry_admin_user($params, $pass_array) {
		if ($pass_array [0] === $pass_array [1]) {

			// パスワード暗号化
			$params [4] = $this->common_logic->convert_password_encode ( $params [4] );

			$this->t_admin_model->insert_admin_user ( $params );
			return true;
		}
		return false;
	}

	/**
	 * 管理画面ユーザー更新処理
	 *
	 * @param unknown $name
	 * @param unknown $pass
	 * @param unknown $pass_conf
	 * @param unknown $authority_menu
	 */
	public function update_admin_user($params, $pass_array) {
		if ($pass_array [0] === $pass_array [1]) {

			// パスワード暗号化
			$params [3] = $this->common_logic->convert_password_encode ( $params [3] );

			$this->t_admin_model->update_admin_user ( $params );
			return true;
		}

		return false;
	}

	/**
	 * 管理画面ユーザー情報取得処理
	 *
	 * @param unknown $admin_user_id
	 */
	public function create_admin_user_detail_html($admin_user_id) {
		$result = $this->t_admin_model->get_admin_user_detail ( $admin_user_id );
		return $result [0];
	}

	/**
	 * ログインID重複チェック
	 *
	 * @param unknown $login_id
	 * @return boolean
	 */
	public function chk_admin_login_id($login_id) {
		$result = $this->t_admin_model->count_login_id ( $login_id );

		if ($result [0] ['cnt'] == 0) {
			return true;
		}
		return false;
	}

	/**
	 * 管理画面ユーザー削除
	 *
	 * @param unknown $id
	 */
	public function del_admin_user($id) {
		$this->t_admin_model->del_admin_user ( $id );
	}

	/**
	 * 有効化処理
	 * @param unknown $id
	 */
	public function recoveryl_admin_user($id) {
		$this->t_admin_model->recoveryl_admin_user ( $id );
	}

}