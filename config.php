<?php

/*/////////////////////////////////////////

設定ファイル

///////////////////////////////////////////*/


/**データベース**/

// ホスト名
define("MYSQL_CONNECT_HOST", "127.0.0.1");

// ユーザー名
define("MYSQL_CONNECT_USER", "delphi");

// パスワード
define("MYSQL_CONNECT_PASS", "%Is9d57t0");

// データベース名
define("MYSQL_DB_NAME", "r_13208_delphi");

// テーブル名
//define("MYSQL_TABLE_NAME", "tenpo");


//$mysql_con = mysqli_connect(MYSQL_CONNECT_HOST,MYSQL_CONNECT_USER,MYSQL_CONNECT_PASS, MYSQL_DB_NAME);

$mysql_con = mysqli_connect( MYSQL_CONNECT_HOST, MYSQL_CONNECT_USER, MYSQL_CONNECT_PASS, MYSQL_DB_NAME);


//DBの接続に失敗した場合はエラー表示をおこない処理中断
if ($mysql_con == False) {
	print ("can not connect db\n"); 
	exit;
}

//MySQLのクライアントの文字コードをutf8に設定
mysqli_set_charset($mysql_con, "utf8");

mb_language('Japanese');
ini_set('mbstring.detect_order', 'auto');
ini_set('mbstring.http_input'  , 'auto');
ini_set('mbstring.http_output' , 'pass');
ini_set('mbstring.internal_encoding', 'UTF-8');
ini_set('mbstring.script_encoding'  , 'UTF-8');
ini_set('mbstring.substitute_character', 'none');
mb_regex_encoding('UTF-8');
?>