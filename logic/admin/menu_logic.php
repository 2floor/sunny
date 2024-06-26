<?php
session_start();
require_once __DIR__ . '/../../common/common_constant.php';
$ml = new menu_logic();
$ml->create_menu_html();

class menu_logic{

	/**
	 * メニュー画面コンテンツ内メニューHTML生成
	 * return String HTML
	 */
	public function create_menu_html() {
		//メニュー構成取得
		$left_menu_datas = json_decode ( LEFT_MENU_LIST, true );
		$menu_html = "";

		return $menu_html;
	}
}