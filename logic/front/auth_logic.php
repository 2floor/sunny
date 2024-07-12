<?php

use App\Models\User;

if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../../third_party/bootstrap.php';
require_once __DIR__ .  '/../../common/common_constant.php';

class auth_logic
{
    public function check_authentication($perms = null)
    {
        $user = $_SESSION['authentication']['login_user'];

        if (empty($user)) {
            header("Location: " . BASE_URL . "login.php");
            exit();
        }

        $existedUser = User::where([
            'id' => $user['id'] ?? 0,
            'del_flg' => User::NOT_DELETED,
            'public_flg' => User::PUBLISHED
        ])->exists();

        if (!$existedUser) {
            header("Location: " . BASE_URL . "login.php");
            exit();
        }
    }
}
