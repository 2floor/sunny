<?php

use App\Models\User;
use App\Models\Role;

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
            'id' => $user['id'] ?? 0
        ]);

        if (!$existedUser->exists()) {
            header("Location: " . BASE_URL . "login.php");
            exit();
        }

        return $existedUser;
    }

    public function check_permission($perms = null)
    {
        $user = $this->check_authentication($perms);

        if (!$perms) {
            return false;
        }

        $cloneUser = $user;
        if ($cloneUser->first()->role?->is_supper_role == Role::IS_SUPER_ADMIN) {
            return true;
        }

        return $user->whereHas('role.permissions', function ($query) use ($perms) {
            $query->where('parse', $perms);
        })->exists();
    }
}
