<?php
session_start ();
header ( 'Content-Type: application/json' );

require_once __DIR__ . '/../../logic/common/common_logic.php';
require_once __DIR__ . '/../../logic/admin/login_logic.php';
require_once __DIR__ . '/../../common/security_common_logic.php';

$security_common_logic = new security_common_logic ();

/**
 * セキュリティチェック
 */
// XSSチェック、NULLバイトチェック
$security_result = $security_common_logic->security_exection ( $_POST, $_REQUEST, $_COOKIE );

// セキュリティチェック後の値を再設定
$_POST = $security_result [0];
$_REQUEST = $security_result [1];
$_COOKIE = $security_result [2];

/**
 * コントローラ処理
 */
if (isset ( $_POST ['id'] )) {
	$login_logic = new login_logic ();

// 	$pass = $this->common_logic->convert_password_encode ( $_POST ['pass'] );

	$result = $login_logic->chk_login_data ( array (
			$_POST ['id'],
			$_POST ['pass']
	) );

	if ($result ['status']) {
		$ses_id = (isset($result['ses_id'])) ? $result['ses_id'] : '';
		$data = array (
				'status' => true,
				'id' => $_POST ['id'],
				'pass' => $ses_id
		);
	} else {
		$data = array (
				'status' => false,
				'msg' => 'UserIDとPasswordが一致しません。'
		);
	}
} else {
	$data = array (
			'status' => false
	);
}

echo json_encode ( compact ( 'data' ) );

