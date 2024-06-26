<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../common/security_common_logic.php';
require_once __DIR__ . '/../logic/common/common_logic.php';

/**
 * セキュリティチェック(全てのビューに必須)
 * @var unknown
 */
$security_common_logic = new security_common_logic ();
$result = $security_common_logic->isToken($_SESSION['adminer']['user_id']);
if (!$result) {
	header('location: login.php');
	exit();
}


//ログンネーム表示用
$l_name = $_SESSION ['adminer']['login_name'];
$l_icon = (isset($_SESSION ['adminer']['icon'])) ? $_SESSION ['adminer']['icon'] : '';;
$l_icon = 'owner_icon.png';
