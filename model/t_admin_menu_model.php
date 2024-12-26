<?php
class t_admin_menu_model{
	private $common_logic;

	/**
	 * コンストラクタ
	 */
	function __construct() {
		$this->common_logic = new common_logic();
	}

	/**
	 * 下層メニュー一覧取得
	 */
	public function get_admin_menu_list(){
		return $this->common_logic->select_logic_no_param( 'select * from t_admin_menu where admin_menu_class_level <> 0');
	}
}