<?php
// 設定ファイル読込
require_once __DIR__ . '/../../common/db_access.php';
require_once __DIR__ . '/../../common/common_constant.php';



/**
 * 本クラスは文字列操作を行う共通ロジックとする。
 * @author Seidou
 *
 */
class common_string_logic {
	private $befor;
	private $after;

	/**
	 * コンストラクタ
	 */
	public function __construct() {
		// 設定ファイル読込
		$ini_array = parse_ini_file ( __DIR__ . '/../../common/config.ini', true );
		$this->befor = $ini_array ['password_key'] ['befor'];
		$this->after = $ini_array ['password_key'] ['after'];
	}

	/**
	 * 配列をカンマ区切りの文字列として返却
	 * @param array or String $param(文字列可、配列可)
	 * @return String
	 */
	public function convert_comma_by_array($param){
		if (is_array ($param)) {
			$param = implode ( ",", $param);
		} else {
			$param = $param;
		}
		return $param;
	}

	/**
	 * カンマ区切りを配列として返却
	 * @param unknown $param
	 * @return Ambigous <NULL, multitype:, multitype:unknown >
	 */
	public function convert_comma_by_comma($param){
		$ret_array = null;
		if(strpos($param,',') !== false){
			$ret_array = explode(',', $param);
		} else {
			$ret_array = array($param);
		}

		return $ret_array;
	}

}

?>