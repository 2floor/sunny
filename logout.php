<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/common/security_common_logic.php';

$security = new security_common_logic();
$security->destroy_session();
header("Location: login.php");
exit();

