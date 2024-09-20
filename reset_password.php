<?php

use App\Models\PasswordReset;

if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/third_party/bootstrap.php';
require_once __DIR__ . '/common/security_common_logic.php';
require_once  __DIR__ . "/required/page_init.php";

$page_init = new page_init();
$pageinfo = $page_init->get_info();

$security = new security_common_logic();
$csrf_token = $security->generateCsrfToken();
$validUrl = false;

if (isset($_GET['en'])) {
    $token = $_GET['en'];

    $reset = PasswordReset::where([
        'token' => $token,
        'status' => PasswordReset::STATUS_ACTIVE
    ])->first();

    if ($reset) {
        if (strtotime($reset->expires_at) > time()) {
            $validUrl = true;
        } else {
            PasswordReset::where([
                'token' => $token,
            ])->update(['status' => PasswordReset::STATUS_INACTIVE]);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no" />
    <!-- meta情報 -->
    <title>Reset Password</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <link rel="shortcut icon" href="favicon.ico" />
    <!-- ogp -->
    <meta property="og:title" content="" />
    <meta property="og:type" content="" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />
    <meta property="og:site_name" content="" />
    <meta property="og:description" content="" />
    <!-- ファビコン -->
    <link rel="”icon”" href="" />
    <!-- フォント -->
    <link rel="stylesheet" href="./assets/css/styles.css">
    <link rel="stylesheet" href="./assets/css/login.css">
    <link href="./assets/admin/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <!-- JavaScript -->
    <style>
        .login-header {
            margin-bottom: 1.5rem;
        }

        .error {
            color: red;
        }

        .error-form{
            border-color: #ff5b5b;
            background: #fdb8b8;
        }


        .toggle-password {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .toggle-password i{
            font-size: 20px;
        }

        .input-group {
            position: relative;
        }
    </style>
</head>

<body>
<header class="header js-drawer-open">
    <div class="header__inner">
        <div class="header__img js-drawer-open">
            <img class="u-mobile" src="./assets/images/common/header_parts.png" alt="">
            <img class="u-desktop" src="./assets/images/common/header_parts-pc.png" alt="">
        </div>
        <div class="header__contents pt20">
            <a href="index.php" class="header__logo header__logo--top js-drawer-open">
                <img src="./assets/images/common/logo.png" alt="株式会社C.A.ピカード ジャパン">
            </a>
            <div class="header__menu">
                <div class="heder-pc u-desktop"></div><!-- ./heder-pc u-desktop -->

            </div><!-- ./header__menu -->
        </div><!-- ./header__contents -->
    </div><!-- ./header__inner -->
</header>

<div class="header-margin"></div>

<div class="page-titles page-title--red">
    <div class="page-titles__inner">
        <div class="page-titles__content">
            <p class="page-title">ログイン</p>
        </div>
    </div>
</div><!-- /.page-titles -->

<section class="login">
    <div class="login-container">
        <?php if ($validUrl): ?>
        <div class="login-card">
            <div class="login-header">
                <h1>パスワード変更</h1>
                <p>新しいパスワードをここでリセットしてください。</p>
            </div>
            <form method="POST" id="form01" action="controller/front/f_authentication_ct.php">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                <input type="hidden" name="method" value="resetPassword">
                <input type="hidden" name="token" value="<?=  $token ?? '' ?>">
                <div class="form-group">
                    <div class="label-info">
                        <label for="password" class="bold">新しいパスワード</label>
                        <span class="status warrant login-warrant">必須</span>　
                    </div>
                    <div class="input-group">
                        <input type="password" class="validate required password" name="password" id="password" placeholder="半角英字と半角数字を含む8～20文字を設定して下さい" required>
                        <span class="toggle-password">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                </div>
                <div class="form-group" style="margin-top: 30px">
                    <div class="label-info">
                        <label for="re-password" class="bold">新しいパスワード（再入力）</label>
                        <span class="status warrant login-warrant">必須</span>　
                    </div>
                    <div class="input-group">
                        <input type="password" class="validate required password_conf" name="re-password" id="re-password" placeholder="半角英字と半角数字を含む8～20文字を設定して下さい" required>
                        <span class="toggle-password">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                </div>
                <button type="submit" class="btn-submit">設定する</button>
            </form>
        </div>
        <?php else: ?>
            <?php
                header("Location: " . BASE_URL . "error/404_page.php");
                exit();
            ?>
        <?php endif; ?>
    </div>
</section>
</body>
<?php print $pageinfo->html_foot; ?>
<script>
    $(document).ready(function(){
        $("#form01").on("submit", function(event){
            if (!validate_all()) {
                event.preventDefault();
            }
        });

        $('.toggle-password').click(function() {
            let passwordField = $(this).siblings('input');
            let passwordFieldType = passwordField.attr('type');

            if (passwordFieldType === 'password') {
                passwordField.attr('type', 'text');
                $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordField.attr('type', 'password');
                $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    });
</script>
</html>
