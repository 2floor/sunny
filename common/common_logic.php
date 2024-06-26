<?php
// 設定ファイル読込
require_once __DIR__ . '/../common/db_access.php';
require_once 'common_constant.php';
class common_logic {
	private $befor;
	private $after;

	/**
	 * コンストラクタ
	 */
	public function __construct() {
		// 設定ファイル読込
		$ini_array = parse_ini_file ( __DIR__ . '/../common/config.ini', true );
		$this->befor = $ini_array ['password_key'] ['befor'];
		$this->after = $ini_array ['password_key'] ['after'];
	}

	/**
	 * Script Insertion
	 *
	 * @param unknown $value
	 * @return string
	 */
	public function h($value) {
		return htmlspecialchars ( $value, ENT_QUOTES );
	}

	/**
	 * メール送信
	 *
	 * @param unknown $to
	 * @param unknown $header
	 * @param unknown $subject
	 * @param unknown $body1
	 */
	public function mail_send($to, $subject, $body1, $header) {
		mb_language ( "Japanese" );
		mb_internal_encoding ( "UTF-8" );
		$order_no = str_pad ( $order_no, 8, "0", STR_PAD_LEFT );

		$header = "From: " . $header;

		mb_send_mail ( $to, $subject, $body1, $header );
	}

	/**
	 * select処理(汎用型)
	 *
	 * @param クエリ $sql
	 * @return Ambigous <結果(array), mixed>
	 */
	public function select_logic($sql, $param) {
		$db = new db_access ();
		return $db->select_executed_param ( $sql, $param );
	}

	/**
	 * select処理(汎用型)
	 *
	 * @param クエリ $sql
	 * @return Ambigous <結果(array), mixed>
	 */
	public function select_logic_no_param($sql) {
		$db = new db_access ();
		return $db->select_executed ( $sql );
	}

	/**
	 * delete処理(汎用型)
	 *
	 * @param クエリ $sql
	 * @return Ambigous <結果(boolean), boolean>
	 */
	public function delete_row_logic_no_param($sql) {
		$db = new db_access ();
		$result = $db->delete_executed_no_param ( $sql );

		return $result;
	}

	/**
	 * delete処理
	 *
	 * @param クエリ $sql
	 * @return Ambigous <結果(boolean), boolean>
	 */
	public function delete_row_logic($sql, $param) {
		$db = new db_access ();
		$result = $db->delete_executed ( $sql, $param );

		return $result;
	}

	/**
	 * insert処理
	 *
	 * @param unknown $tb_name(テーブル名)
	 * @param unknown $param_array(array)
	 * @return 結果(boolean)
	 */
	public function insert_logic($tb_name, $param_array) {
		$common_logic = new common_logic ();
		$query = "";

		// $tb_nameで指定されたテーブルのフィールドを取得
		$rows = $common_logic->select_logic_no_param ( 'SHOW COLUMNS FROM ' . $tb_name );
		$set_value = "";

		// query生成
		for($i = 0; $i < count ( $rows ); $i ++) {

			// PrimaryKeyを除外
			if ($i != 0) {
				if (($rows [$i] ['Field'] == "create_at" || $rows [$i] ['Field'] == "update_at")) {
					$set_value .= ", " . $rows [$i] ['Field'] . " = now()";
				} else {
					$set_value .= ", " . $rows [$i] ['Field'] . " = ?";
				}
			}
		}

		// 不要カンマ削除
		$set_value = preg_replace ( "/,/", "", $set_value . "\n", 1 );

		$query = "insert into " . $tb_name . " set " . $set_value;
		// echo $query;
		// exit();

		// query実行
		$db = new db_access ();
		$result = $db->insert_update_executed ( $query, $param_array );
		return $result;
	}

	/**
	 * update処理($up_field_listで指定したフィールドのみ変更)
	 *
	 * @param unknown $tb_name(テーブル名)
	 * @param unknown $where_query(where)
	 * @param unknown $up_field_list(配列,更新対象フィールド)
	 * @param unknown $param_array(配列,updateパラメータ7)
	 * @return unknown 結果(boolean)
	 */
	public function update_logic($tb_name, $where_query, $up_field_list, $param_array) {
		$common_logic = new common_logic ();

		// $tb_nameで指定されたテーブルのフィールドを取得
		$rows = $common_logic->select_logic_no_param ( 'SHOW COLUMNS FROM ' . $tb_name );
		$set_value = "";

		// query生成
		for($i = 0; $i < count ( $rows ); $i ++) {
			if ($rows [$i] ['Field'] == "update_at") {
				$set_value .= ", " . $rows [$i] ['Field'] . " = now()";
			} else {
				for($n = 0; $n < count ( $up_field_list ); $n ++) {
					// PrimaryKeyを除外
					if ($i != 0 && $up_field_list [$n] == $rows [$i] ['Field']) {
						$set_value .= ", " . $rows [$i] ['Field'] . " = ?";
					}
				}
			}
		}

		// 不要カンマ削除
		$set_value = preg_replace ( "/,/", "", $set_value . "", 1 );

		$query = "update " . $tb_name . " set " . $set_value . " " . $where_query;

		// echo $query . "<br><br>";
		// exit();

		// query実行
		$db = new db_access ();
		$result = $db->insert_update_executed ( $query, $param_array );

		// echo $result;
		// exit();

		return $result;
	}

	/**
	 * 指定桁数0埋め処理
	 *
	 * @param unknown $val
	 *        	パラメータ
	 * @param number $length
	 *        	桁数
	 */
	public function zero_padding($val, $length = 11) {
		return sprintf ( '%0' . $length . 'd', $val );
	}

	/**
	 * 数字型のリストボックスを生成(value数字型)
	 *
	 * @param unknown $strInt
	 * @param unknown $endInt
	 * @param unknown $sel
	 * @return string
	 */
	public function createIntListBoxRetInt($strInt, $endInt, $sel) {
		$a = "";

		for($i = $strInt; $i < $endInt; $i ++) {
			if ($i == $sel) {
				$b = ' selected';
			} else {
				$b = "";
			}
			$a .= "<option value=\"$i\"$b>$i</option>";
		}

		if ($strInt == $endInt) {
			$a .= "<option value=\"$strInt\" selected>$strInt</option>";
		}
		return $a;
	}

	/**
	 * 文字列型のリストボックスを生成(value数字型)
	 *
	 * @param 表示文字列配列 $strList
	 * @param 選択値 $sel
	 * @return string
	 */
	public function createStrListBoxRetInt($strList, $sel) {
		$a = "";

		for($i = 0; $i < count ( $strList ); $i ++) {
			if ($i == $sel) {
				$b = ' selected';
			} else {
				$b = "";
			}
			$a .= "<option value=\"$i\"$b>$strList[$i]</option>";
		}
		return $a;
	}

	/**
	 * 文字列型のリストボックスを生成(value文字列型)
	 *
	 * @param 表示文字列配列 $strList
	 * @param 選択値 $sel
	 * @return string
	 */
	public function createStrListBoxRetStr($strList, $sel) {
		$a = "";

		for($i = 0; $i < count ( $strList ); $i ++) {
			if ($i == $sel) {
				$b = ' selected';
			} else {
				$b = "";
			}
			$a .= "<option value=\"$strList[$i]\"$b>$strList[$i]</option>";
		}
		return $a;
	}

	/**
	 * 文字列型のリストボックスを生成(value文字列型)
	 *
	 * @param 表示文字列配列 $strList
	 * @param 選択値 $sel
	 * @return string
	 */
	public function createStrListBoxEqStrRetStr($strList, $sel) {
		$a = "";

		for($i = 0; $i < count ( $strList ); $i ++) {
			if ($strList [$i] == $sel) {
				$b = ' selected';
			} else {
				$b = "";
			}
			$a .= "<option value=\"$strList[$i]\"$b>$strList[$i]</option>";
		}
		return $a;
	}

	/**
	 * 文字列型のリストボックスを生成(value文字列型)
	 *
	 * @param 表示文字列配列 $strList
	 * @param value文字列配列 $valueList
	 * @return string
	 */
	public function createStrListBoxValueList($strList, $valueList, $sel) {
		$a = "";

		for($i = 0; $i < count ( $strList ); $i ++) {
			if ($strList [$i] == $sel) {
				$b = ' selected';
			} else {
				$b = "";
			}
			$a .= "<option value=\"$valueList[$i]\"$b>$strList[$i]</option>";
		}
		return $a;
	}

	/**
	 * 空白配列を削除し採番し直し後、配列をカンマ区切りの文字列として返す
	 *
	 * @param unknown $array
	 * @return multitype:
	 */
	public function sort_implode_aray($array) {
		$array = array_filter ( $array, "strlen" );
		$array = array_values ( $array );
		$str = implode ( ",", $array );
		return $str;
	}

	/**
	 * 画像縦横比計算
	 *
	 * @param unknown $in_widht
	 * @param unknown $in_height
	 * @param unknown $img_pass
	 * @return multitype:number |multitype:unknown multitype: |multitype:number unknown
	 */
	public function wh_resize($in_widht, $in_height, $img_pass) {
		list ( $width, $height, $type, $attr ) = getimagesize ( $img_pass );

		$newwidth = 0; // 新しい横幅
		$newheight = 0; // 新しい縦幅
		$w = $in_widht; // 最大横幅
		$h = $in_height; // 最大縦幅

		// 両方オーバーしていた場合
		if ($h < $height && $w < $width) {
			$widthPercent = $w / $width;
			$heightPercent = $h / $height;
			if ($width * $widthPercent <= $w && $height * $widthPercent <= $h) {
				$newwidth = $width * $widthPercent;
				$newheight = $height * $widthPercent;
				return array (
						$newwidth,
						$newheight
				);
			} else {
				$newwidth = $width * $heightPercent;
				$newheight = $height * $heightPercent;
				return array (
						$newwidth,
						$newheight
				);
			}
		} else if ($height < $h && $width < $w) { // 両方オーバーしていない場合
			$newwidth = $width;
			$newheight = $height;
			return array (
					$newwidth,
					$newheight
			);
		} else if ($h < $height && $width <= $w) {
			// 縦がオーバー、横は新しい横より短い場合
			// 縦がオーバー、横は同じ長さの場合
			$newwidth = $width * ($h / $height);
			$newheight = $h;
			return array (
					$newwidth,
					$newheight
			);
		} else if ($height <= $h && $w < $width) {
			// 縦が新しい縦より短く、横はオーバーしている場合
			// 縦は同じ長さ、横はオーバーしている場合
			$newwidth = $w;
			$newheight = $height * ($w / $width);
			return array (
					$newwidth,
					$newheight
			);
		} else if ($height == $h && $width < $w) {
			// 横が新しい横より短く、縦は同じ長さの場合
			$newwidth = $width * ($h / $height);
			$newheight = $h;
			return array (
					$newwidth,
					$newheight
			);
		} else if ($height < $h && $width == $w) {
			// 縦が新しい縦より短く、横は同じ長さの場合
			$newwidth = $w;
			$newheight = $height * ($w / $width);
			return array (
					$newwidth,
					$newheight
			);
		} else {
			// 縦も横も、新しい長さと同じ長さの場合
			// または、縦と横が同じ長さで、かつ最大サイズを超えない場合
			$newwidth = $width;
			$newheight = $height;
			return array (
					$newwidth,
					$newheight
			);
		}
	}
	public function get_files($path) {
		if ($dir = opendir ( $path )) {
			while ( ($file = readdir ( $dir )) !== false ) {
				if ($file != "." && $file != "..") {
					$aaa [] = $file;
				}
			}
			closedir ( $dir );
		}

		return $aaa;
	}
	function getFileList($dir) {
		$iterator = new RecursiveDirectoryIterator ( $dir );
		$iterator = new RecursiveIteratorIterator ( $iterator );

		$list = array ();
		foreach ( $iterator as $fileinfo ) { // $fileinfoはSplFiIeInfoオブジェクト
			if ($fileinfo->isFile ()) {
				$list [] = $fileinfo->getPathname ();
			}
		}

		return $list;
	}

	/**
	 * 指定桁数のランダム英数字を生成
	 *
	 * @param unknown $nLengthRequired
	 * @return string
	 */
	public function getRandomString($nLengthRequired) {
		$sCharList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_";
		mt_srand ();
		$sRes = "";
		for($i = 0; $i < $nLengthRequired; $i ++)
			$sRes .= $sCharList [mt_rand ( 0, strlen ( $sCharList ) - 1 )];
		return $sRes;
	}

	public function pager($html_list, $url, $page, $pager_id, $activ_flg) {
		$ret_html = null;
		$start_no = 0;
		$end_no = 0;
		$hairetu = $html_list;
		// ↑先ほど作成したユーザー関数をarray_map()関数で配列全体に処理を施す
		$cnt = count ( $hairetu ); // レコード数をカウントしておきます
		                           // テスト用、適当レコード作成ここまで

		// ここから表示する内容を作成
		                           // ↓はじめてこのページにやってきたら「1ページ目だよ」とやさしく教えてあげる
		if ($genzai_page == "") {
			$genzai_page = 1;
			$start = 0; // このスタートは配列の中の何個目から取り出すのか？
		}
		$hyouji_kazu = 10; // 表示する数
		$max_page = ceil ( $cnt / $hyouji_kazu ); // ceilは切り上げ（$max_pageは表示最大ページ数）

		// ↓すでにこのページにいたら・・・
		if ($page != "") {
			if ($activ_flg == false) {
				$genzai_page = $page; // $_GETでもらった数字が現在のページ
			} else {
				$genzai_page = 1; // $_GETでもらった数字が現在のページ
			}
			// ↑サンプルコードでは $_GET['page']をエスケープ処理してませんが本番ではして下さい
			$start = ($genzai_page - 1) * $hyouji_kazu; // 表示スタート数（何個目から表示するか？）
		}
		// ↓現在のページと何個のデータを表示しているのか表示する部分
		// $pageing_mes = '<p>現在のページは「' . $genzai_page . '」です。</p>' . '<p>' . ($start + 1) . '個目から' . (($start) + $hyouji_kazu) . '個目のデータを表示しています</p>';
		$start_no = $start + 1;
		$end_no = ($start) + $hyouji_kazu;

		// ↓これをつけないと 場合によっては １ページ目とか最後のページで表示件数がおかしくなる
		if ($genzai_page == $max_page) {
			// $pageing_mes = '<p>現在のページは「' . $genzai_page . '」です。</p>' . '<p>' . ($start + 1) . '個目から' . $cnt . '個目のデータを表示しています</p>';
			$start_no = $start + 1;
			$end_no = $cnt;
		}
		// ↓データがない場合の処理（本番では必要です）
		if ($cnt == 0) {
			$pageing_mes = '&nbsp;<br>&nbsp;&nbsp;&nbsp;現在登録されているデータはありません';
		}

		$ret_html .= $pageing_mes;

		if (is_array ( $hairetu )) {
			$naiyou = array_slice ( $hairetu, $start, $hyouji_kazu ); // 表示する数と内容
		}

		// ↓データ内容を表示する部分
		foreach ( $naiyou as $val ) {
			$ret_html .= $val;
		}
		// ↑内容を表示する部分終り

		$ret_html .= "
				<div class='col-sm-12 hidden-xs' style='text-align: center;' id='pager_" . $pager_id . "'>
				<ul class='pager000'>";
		$ret_html_sp = '<div class="col-xs-12 visible-xs">';
		if ($genzai_page != 1) {
			$ret_html .= '<a href="' . $url . '?page=' . ($genzai_page - 1) . '&id=' . $pager_id . '" id="active_' . $genzai_page . '"><li class="pager001">&laquo;BACK</li></a>　';
			$ret_html_sp .= '
					<div class="col-xs-5">
						<a href="' . $url . '?page=' . ($genzai_page - 1) . '&id=' . $pager_id . '" id="active_' . $genzai_page . '">
							<div class="more_btn1">
								<span class="glyphicon glyphicon-chevron-left"></span>BACK
							</div>
						</a>
					</div>';
		}

		// ↓表示最大数が１０未満でページ表示数が１(表示数１ならページングする必要がない）でなければ・・・
		if (($max_page <= 10) && ($max_page != 1)) {
			$ret_html .= '      　'; // 表示があまり乱れないように全角空スペース４個を入れておく
			for($i = 1; $i <= $max_page; $i ++) {
				$ret_html .= '<a href="' . $url . '?page=' . $i . '&id=' . $pager_id . '"><li class="pager001" id="active_' . $genzai_page . '">' . $i . '</li></a> ';
			}

			// ↓最大表示数が１０以上で現在のページが６未満なら・・・
		} elseif (($max_page > 10) && ($genzai_page < 6)) {
			$ret_html .= '　　　　'; // 表示があまり乱れないように全角空スペース４個を入れておく
			for($a = 1; $a <= 10; $a ++) {
				$ret_html .= '<a href="' . $url . '?page=' . $a . '&id=' . $pager_id . '"><li class="pager001" id="active_' . $genzai_page . '">' . $a . '</li></a> ';
			}

			// ↓最大表示数が１０以上かつ、現在のページが６以上かつ最終ページより５ページ以内にいなければ・・・
		} elseif (($max_page > 10) && ($genzai_page >= 6) && (($genzai_page + 5) < $max_page)) {
			for($a = 1; $a <= 5; $a ++) {
				$ret_html .= '<a href="' . $url . '?page=' . ($genzai_page - 5 + $a) . '&id=' . $pager_id . '"><li class="pager001" id="active_' . $genzai_page . '">' . ($genzai_page - 5 + $a) . '</li></a> ';
			}
			for($a = 1; $a <= 5; $a ++) {
				$ret_html .= '<a href="' . $url . '?page=' . ($genzai_page + $a) . '&id=' . $pager_id . '"><li class="pager001" id="active_' . $genzai_page . '">' . ($genzai_page + $a) . '</li></a> ';
			}

			// ↓最大表示数が１０以上かつ、現在のページも6以上かつ、
			// ↓現在のページが最終ページから５ページ以内にいる場合の処理
		} elseif (($max_page > 10) && ($genzai_page >= 6) && (($genzai_page + 5) >= $max_page)) {
			$b = $max_page - 10;
			while ( $b <= $max_page ) {
				$ret_html .= '<a href="' . $url . '?page=' . $b . '&id=' . $pager_id . '"><li class="pager001" id="active_' . $genzai_page . '">' . $b . '</li></a> ';
				$b ++;
			}
		}

		if ($genzai_page != $max_page && $max_page != 0) {
			$ret_html .= '<a href="' . $url . '?page=' . ($genzai_page + 1) . '&id=' . $pager_id . '"><li class="pager001">NEXT&raquo;</li></a>';
			$ret_html_sp .= '
					<div class="col-xs-5 col-xs-offset-2">
						<a href="' . $url . '?page=' . ($genzai_page + 1) . '&id=' . $pager_id . '">
							<div class="more_btn1">
								NEXT<span class="glyphicon glyphicon-chevron-right"></span>
							</div>
						</a>
					</div>';
		}
		$ret_html_sp .= '</div>';

		$ret_html .= "</ul></div>";

		$ret_html .= "<script>";
		$ret_html .= "$(document).ready(function() {";
		$ret_html .= "$('#active_" . $genzai_page . "').css( {'background-color':'#1D37A2', 'color':'#fff'} );";
		$ret_html .= "});";
		$ret_html .= "</script>";

		$ret_html = $ret_html . $ret_html_sp;
		return array (
				$ret_html,
				$start_no,
				$end_no
		);
	}

	/**
	 * 指定フォルダ内のファイルを削除
	 *
	 * @param unknown $dir
	 */
	function deleteData($dir) {
		if ($dirHandle = opendir ( $dir )) {
			while ( false !== ($fileName = readdir ( $dirHandle )) ) {
				if ($fileName != "." && $fileName != "..") {
					unlink ( $dir . $fileName );
				}
			}
			closedir ( $dirHandle );
		}
	}

	/**
	 * ファイルアップロード
	 *
	 * @param unknown $file
	 * @param unknown $path
	 * @return string
	 */
	function upload_file($file, $path) {
		if (is_uploaded_file ( $file ["tmp_name"] )) {
			if (move_uploaded_file ( $file ["tmp_name"], $path . $file ["name"] )) {
				chmod ( $path . $file ["name"], 0644 );
			}
		}
		return $file ["name"];
	}

	/**
	 * ファイル圧縮処理
	 *
	 * @param unknown $zip_full_path
	 * @param unknown $material_full_path
	 * @param unknown $material_file_list
	 */
	function create_zip($zip_full_path, $material_full_path, $material_file_list) {
		$zip = new ZipArchive ();
		// ZIPファイルをオープン
		$res = $zip->open ( $zip_full_path, ZipArchive::CREATE );

		// zipファイルのオープンに成功した場合
		if ($res === true) {

			for($i = 0; $i < count ( $material_full_path ); $i ++) {
				// 圧縮するファイルを指定する
				$zip->addFile ( $material_full_path [$i], $material_file_list [$i] );
			}
			// ZIPファイルをクローズ
			$zip->close ();
		}
	}

	/**
	 * ディレクトリ存在チェック(無い場合は生成)
	 *
	 * @param unknown $array
	 * @param boolean $create_flg
	 * @return boolean
	 */
	public function chkDirectory($array, $create_flg = true) {
		$directory_path = null;
		for($i = 0; $i < count ( $array ); $i ++) {
			$directory_path .= $array [$i] . "/";
			$return = false;
			if (file_exists ( $directory_path )) {
				$return = true;
			}
			if (! $return) {
				if ($create_flg) {
					mkdir ( $directory_path, 0777 );
					chmod ( $directory_path, 0777 );
				}
				$return = true;
			}
		}

		return $return;
	}

	/**
	 * 指定フォルダの画像ファイル一覧を取得
	 *
	 * @param unknown $path
	 * @return Ambigous <NULL, string>
	 */
	public function get_file_list($path) {
		$ret_array = null;
		foreach ( glob ( $path . '{*.gif,*.jpg,*.png,*.PNG,*.jpeg,*.JPG,*.JPEG,*.GIF}', GLOB_BRACE ) as $file ) {
			if (is_file ( $file )) {
				$ret_array [] = htmlspecialchars ( $file );
			}
		}
		return $ret_array;
	}

	/**
	 * 指定ページの訪問数カウント
	 *
	 * @param unknown $strId
	 * @return number
	 */
	function get_countVisits($strId) {
		$aa = explode ( "/", $strId );

		$strId = end ( $aa );

		$strId = str_replace ( "?", "", $strId );
		$strId = str_replace ( ".", "", $strId );
		$strId = str_replace ( "=", "", $strId );
		$strId = str_replace ( "_", "", $strId );

		$life = 60 * 60 * 24 * 30 * 12;
		if (isset ( $_COOKIE ["count" . $strId] )) {
			$count = $_COOKIE ["count" . $strId] + 1;
		} else {
			$count = 1;
		}
		setcookie ( "count" . $strId, $count, time () + $life );
		return $count;
	}
	public function zip_dl($filename, $path) {
		ob_end_clean ();
		header ( 'Content-Type: application/octet-stream' );
		header ( 'Content-Disposition: attachment; filename="' . $filename . '"' );
		readfile ( $path );
	}

	/**
	 * 一行の文字数と行の行数を指定し改行する
	 *
	 * @param unknown $line_max_length(1行の半角文字数)
	 * @param unknown $lin_max_cnt(表示する最大行数)
	 * @param unknown $string(文字列)
	 * @return unknown $string(文字列)
	 */
	public function disp_list_detail($line_max_length, $lin_max_cnt, $string) {
		if (strlen ( $string ) >= $line_max_length) {
			$string = mb_substr ( $string, 0, $line_max_length, 'UTF-8' ) . "<br>";
		} else {
			$string = mb_substr ( $string, 0, $line_max_length, 'UTF-8' );
		}
		$strin = nl2br ( $string );
		$string_list = explode ( "\r\n", $string );

		$ret_string = $string_list [0];
		if ($string_list [1] != "" && $string_list [1] != null) {
			$ret_string .= "<br>" . $string_list [1];
			if ($string_list [2] != "" && $string_list [2] != null) {
				$ret_string .= "<br>" . $string_list [2];
			}
		}

		return $ret_string;
	}

	/**
	 *
	 * IPアドレスのマッチング
	 *
	 * @param string $ip_list
	 * @param string $remote_addr
	 * @return boolean
	 */
	public function getIpMatching($ip_list, $remote_addr) {
		$acsess_flg = false;

		if (strpos ( $ip_list, ',' ) === false) {
			// IPの指定が1つ XXX.XXX.XXX.XXX/XX
			$ip_list_arr = array (
					$ip_list
			);
		} else {
			// IPの指定が複数 XXX.XXX.XXX.XXX/XX,XXX.XXX.XXX.XXX/XX,XXX.XXX.XXX.XXX/XX
			$ip_list_arr = explode ( ',', $ip_list );
		}

		foreach ( $ip_list_arr as $ipListKey => $ipListVal ) {
			if (strpos ( $ipListVal, '/' ) === false) {
				$ip = $ipListVal;
				// IPの形式がXXX.XXX.XXX.XXX
				if ($ip == $remote_addr) {
					$acsess_flg = true;
					break;
				}
			} else {
				list ( $ip, $bit_mask ) = explode ( '/', $ipListVal );
				// IPの形式がXXX.XXX.XXX.XXX/XX
				$allow_ip_long = ip2long ( $ip ) >> (32 - $bit_mask);
				$acsess_ip_long = ip2long ( $remote_addr ) >> (32 - $bit_mask);
				if ($acsess_ip_long == $allow_ip_long) {
					$acsess_flg = true;
					break;
				}
			}
		}

		return $acsess_flg;
	}

	/**
	 * NULL、空文字チェック(該当しない場合はtrueを返却)
	 *
	 * @param unknown $value
	 * @return boolean
	 */
	public function isNullBlank($value) {
		if ($value == null || $value == "") {
			return false;
		}
		return true;
	}


	/**
	 * ファイルアップロード
	 * @param unknown $file(ファイルオブジェクト)
	 * @param unknown $upload_path(アップロード先パス　../test_uploads/)
	 * @return 結果配列(結果ステータス, ファイルフルパス, ファイル名)
	 */
	public function unit_file_upload($file, $upload_path){
		$fileName = $file ["name"]; // The file name
		$fileTmpLoc = $file ["tmp_name"]; // File in the PHP tmp folder
		$fileType = $file ["type"]; // The type of file it is
		$fileSize = $file ["size"]; // File size in bytes
		$fileErrorMsg = $file ["error"]; // 0 for false... and 1 for true
		if (! $fileTmpLoc) { // if file not chosen
			echo "ERROR: Please browse for a file before clicking the upload button.";
			exit ();
		}
		if (move_uploaded_file ( $fileTmpLoc, $upload_path . $fileName )) {
			$ret_array = implode(',', array (
					$upload_path . $fileName,
					$fileName
			));
			return array(
					"status" => true,
					"full_path" => $upload_path . $fileName,
					"file_name" => $fileName
			);
		} else {
			return array(
					"status" => false,
					"full_path" => $upload_path . $fileName,
					"file_name" => $fileName
			);
		}
	}

	/**
	 * エリアセレクトボックス生成
	 */
	public function create_area_select_html() {
		$common_logic = new common_logic ();
		$area_result = $common_logic->select_logic_no_param ( 'select distinct area_code, area_name from m_area_pref' );
		$area_select_html = "<option value=''>エリア</option>";
		for($i = 0; $i < count ( $area_result ); $i ++) {
			$area_row = $area_result [$i];
			$area_select_html .= "<option value='" . $area_row ['area_name'] . "'>" . $area_row ['area_name'] . "</option>";
		}
		return $area_select_html;
	}


	/**
	 * 都道府県セレクトボックス生成
	 * @param unknown $pref_name
	 */
	public function create_pref_select_html($area_name) {
		$common_logic = new common_logic ();
		$pref_result = $common_logic->select_logic ( 'select * from m_area_pref where area_name = ?', array (
				$area_name
		) );
		$pref_select_html = "<option value=''>都道府県</option>";
		for($i = 0; $i < count ( $pref_result ); $i ++) {
			$pref_row = $pref_result [$i];
			$pref_select_html .= "<option value='" . $pref_row ['pref_name'] . "'>" . $pref_row ['pref_name'] . "</option>";
		}
		return $pref_select_html;
	}

	/**
	 * 都道府県からエリア取得
	 * @param unknown $pref_name
	 */
	public function create_area_select_html_by_pref($pref_name) {
		$common_logic = new common_logic ();
		$area_result = $common_logic->select_logic ( 'select * from m_area_pref where pref_name = ?', array (
				$pref_name
		) );
		return $area_result[0]['area_name'];
	}

	/**
	 * 都道府県名から都道府県ID取得
	 * @param unknown $pref_name
	 */
	public function get_pref_code_by_pref_name($pref_name) {
		$common_logic = new common_logic ();
		$area_result = $common_logic->select_logic ( 'select * from m_area_pref where pref_name = ?', array (
				$pref_name
		) );
		return $area_result[0]['pref_code'];
	}

	/**
	 * 都道府県コードから都道府県名取得
	 * @param unknown $pref_name
	 */
	public function get_pref_name_by_pref_code($pref_code) {
		$common_logic = new common_logic ();
		$area_result = $common_logic->select_logic ( 'select * from m_area_pref where pref_code = ?', array (
				$pref_code
		) );
		return $area_result[0]['pref_name'];
	}


// 	public function get
	/**
	 * パスワード暗号化処理
	 * (セキュリティ向上の為以下の複数暗号化を行う)
	 * 1.パラメータのパスワードをMD5変換
	 * 2.変換したパスワードの前後に設定ファイルの値を設定
	 * 3.上記の値をhash変換
	 * @param unknown $pass
	 */
	public function convert_password_encode($pass){
		//MD5変換
		$pass2 = MD5($pass);

		// パスワード用キーをパスワード前後に付与
		$pass3 = $this->befor.$pass2.$this->after;

		//hash変換
		$super_pass = hash('sha256',$pass3);

		return $pass2;
	}

	public function isNullEmpty($val){
		if ($val != "" || $val != null) {
			return false;
		}
		return true;
	}

	public function convert_pref_name_to_code($area_name){
		$m_area_pref_model = new m_area_pref_model();

		$result = $m_area_pref_model->get_area_pref_data_pref_name($area_name);

		return $result[0]['pref_code'];
	}

// 	require_once '../model/m_interest_model.php';
// 	require_once '../model/m_area_pref_model.php';

}

?>