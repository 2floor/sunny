<?php

use App\Models\User;
use App\Models\PasswordReset;

if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../../third_party/bootstrap.php';
require_once __DIR__ . '/../../common/security_common_logic.php';
require_once __DIR__ . "/../../logic/common/common_logic.php";

/**
 * セキュリティチェック
 */
// インスタンス生成
$security_common_logic = new security_common_logic();

// XSSチェック、NULLバイトチェック
$security_result = $security_common_logic->security_exection($_POST, $_REQUEST, $_COOKIE);

// セキュリティチェック後の値を再設定
$_POST = $security_result[0];
$_REQUEST = $security_result[1];
$_COOKIE = $security_result[2];

if (isset($_GET['method'])) {
    $ct = new f_authentication_ct();
    $data = $ct->mainAjaxGet($_GET);
    echo json_encode($data);
} elseif (isset($_POST['method'])) {
    $ct = new f_authentication_ct();
    $data = $ct->mainAjaxPost($_POST);
    echo json_encode($data);
}

class f_authentication_ct
{
    protected $security;
    protected $common_logic;

    public function __construct() {
        $this->security = new security_common_logic();
        $this->common_logic = new common_logic();
    }
    public function mainAjaxGet($get)
    {
        $data = [
            'status' => false,
            'data' => []
        ];

        return $data;
    }

    public function mainAjaxPost($post)
    {
        $data = [
            'status' => false,
            'data' => []
        ];

        if ($post['method'] == 'forgotPassword') {
            $data = $this->forgotPassword($post);
        }

        if ($post['method'] == 'resetPassword') {
            $data = $this->resetPassword($post);
        }

        return $data;
    }

    public function resetPassword($post)
    {
        $csrf_token = $post["csrf_token"] ?? '';

        if (!$this->security->validateCsrfToken($csrf_token)) {
            header("Location: " . BASE_URL . "error/404_page.php");
            exit();
        }

        $reset = PasswordReset::where([
            'token' => $post['token'] ?? null,
            'status' => PasswordReset::STATUS_ACTIVE
        ])->first();

        $isSuccess = false;

        if ($reset && ($user = $reset->user) && $post['password']) {
            if (strtotime($reset->expires_at) > time()) {
                $user->password = $this->common_logic->convert_password_encode($post['password']);

                if ($user->save()) {
                    $reset->update(['status' => PasswordReset::STATUS_INACTIVE]);
                    $isSuccess = true;
                }
            } else {
                $reset->update(['status' => PasswordReset::STATUS_INACTIVE]);
            }
        }

        header("Location: " . BASE_URL . "reset_password_end.php?status=" . ($isSuccess ? 'yes' : 'no'));
        exit();
    }

    public function forgotPassword($post)
    {
        $csrf_token = $post["csrf_token"] ?? '';

        if (!$this->security->validateCsrfToken($csrf_token)) {
            header("Location: " . BASE_URL . "error/404_page.php");
            exit();
        }

        $user = User::where('email', $post['email'])->first();

        if ($user) {
            PasswordReset::where([
                'status' => PasswordReset::STATUS_ACTIVE,
                'user_id' => $user->id
            ])->update([
                'status' => PasswordReset::STATUS_INACTIVE,
            ]);

            $token = bin2hex(random_bytes(32));
            $expires_at = date("Y-m-d H:i:s", strtotime('+10 minutes'));

            $nps = PasswordReset::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'token' => $token,
                'status' => PasswordReset::STATUS_ACTIVE,
                'expires_at' => $expires_at
            ]);

            if ($nps) {
                $this->sendMailForgotPwd(['name' => $user->name, 'token' => $token], $user->email);
            }
        }

        header("Location: " . BASE_URL . "forgot_password_end.php");
        exit();
    }

    private function sendMailForgotPwd($post, $mail)
    {
        $name = $post['name'] ?? '';
        $token = $post['token'] ?? '';

        $body = <<<EOF

―――――――――――――――――――――――――――――――――――
このメッセージは 病院検査サイトより自動送信されています。
※no-reply@sunny.comは送信専用です。ご返信されても対応出来ませんのでご了承下さい。
―――――――――――――――――――――――――――――――――――

{$name} 様
　　　　
いつもご利用頂き、誠にありがとうございます。
以下のURLよりパスワードの再設定をお願いします。
有効期限は10分です。
*******************************************************************


https://2floor.space/sunny_health/reset_password.php?en={$token}


*******************************************************************




================================================================
■病院検査サイト
https://2floor.space/sunny_health/

こちらのメールに関してのお問い合わせはこちらからお願いいたします。
■お問い合わせフォーム
https://2floor.space/sunny_health/contact.php

================================================================


EOF;
        $title = "【病院検査サイト】パスワード再設定に関するご連絡です";
        $this->common_logic->mail_send($mail, $title, $body, "no-reply@sunny.com");
    }
}
