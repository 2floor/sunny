<?php

use App\Models\User;

if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/common/security_common_logic.php';
require_once __DIR__ . '/logic/common/common_logic.php';
require_once __DIR__ . '/third_party/bootstrap.php';

$security = new security_common_logic();
$common_logic = new common_logic();

// ID、PWの取得
$m_login	=	$_POST["username"];
$m_pass		=	$_POST["password"];
$token      =   $_POST["csrf_token"];

if (!$security->validateCsrfToken($token)) {
    header("Location: login.php");
    exit();
}

$user = User::where([
    'username' => $m_login,
    'password' => $common_logic->convert_password_encode($m_pass),
    'del_flg' => User::NOT_DELETED,
    'public_flg' => User::PUBLISHED
])->first();

if ($user) {
    $_SESSION['authentication']['login_user'] = $user->toArray();
    header("Location: index.php");
    exit();
} else {
    header("Location: login.php?msg=x");
    exit();
}