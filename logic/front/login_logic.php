<?php
session_start();
require_once __DIR__ . '/../../logic/common/common_logic.php';
$common_logic = new common_logic();



if ($_POST != null && $_POST != '') {
	// var_dump($_POST);
	// exit;
	$pw = $common_logic->convert_password_encode(htmlspecialchars($_POST['password']));
	$member = $common_logic->select_logic("select * from t_member where mail = ? and password = ? and del_flg = 0 ", array(htmlspecialchars($_POST['mail']), $pw));


	if ($member != null && $member != '') {



		if ($member[0]['questionnaire'] == '1') {

			//認証済み
			unset($member[0]['password']);
			$_SESSION['cclue']['login']  = $member[0];


			header("Location: ../../top.php");
			exit();
		} else if ($member[0]['questionnaire'] == '0') {

			//未認証
			header("Location: ../../login.php?er=2");
			exit();
		} else if ($member[0]['questionnaire'] == '99') {
			//認証不可
			header("Location: ../../login.php?er=3");
			exit();
		} else {

			header("Location: ../../login.php?er=test");
			exit();
		}
	} else {

		header("Location: ../../login.php?er=1");
		exit();
	}
} else {
	header("Location: ../../login.php");
	exit();
}
