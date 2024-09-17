<?php
require_once __DIR__ . '/../../common/security_common_logic.php';
require_once __DIR__ . '/../../model/t_admin_model.php';
require_once __DIR__ . '/../../logic/common/common_logic.php';

if (!isset($_SESSION)) {
    session_start();
}
class login_logic{
	private $t_admin_model;
	private $security_common_logic;
	private $common_logic;


	/**
	 * コンストラクタ
	 */
	public function __construct() {
		$this->t_admin_model = new t_admin_model();
		$this->security_common_logic = new security_common_logic();
		$this->common_logic = new common_logic();
	}

	/**
	 * ログインチェック
	 *
	 * @param unknown $params
	 */
	public function chk_login_data($params) {
		//パスワード暗号化
		$params[1] = $this->common_logic->convert_password_encode($params[1]);

		//ログインチェック
		$result = $this->t_admin_model->get_login_data_by_id_pass($params);

		if (count ((array) $result ) > 0) {

			//CRF対策用トークンID更新
			$token = $this->security_common_logic->createToken($result [0] ['id']);
			unset($_SESSION ['adminer']['user_id']);
			$_SESSION ['adminer']['login_name'] = $result [0] ['name'];
			$_SESSION ['adminer']['user_id'] = $result [0] ['id'];
			$_SESSION ['adminer']['user_datas'] = $result [0];

			return array (
					'status' => true
			);
		} else {
			return array (
					'status' => false
			);
		}
	}
}