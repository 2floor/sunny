<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/common/security_common_logic.php';

$security = new security_common_logic();
$csrf_token = $security->generateCsrfToken();

$msg = isset($_GET['msg']) ? $_GET['msg'] : false;

// ID・PWが一致しない場合
if ($msg) {
    $no_msg = '無効な CSRF トークン';
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no" />
    <!-- meta情報 -->
    <title>Forgot Password</title>
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
    <!-- JavaScript -->

    <style>
        section.login {
            margin-top: 4rem;
        }

        form {
            margin-top: 50px;
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
        <div class="login-card">
            <div class="login-header">
                <h1>パスワードを忘れた方</h1>
                <p>ご登録のメールアドレスに再設定用のURLをお送りいたします。<br>
                    現在ご登録のメールアドレスをご入力ください。</p>
            </div>
            <form method="POST" action="controller/front/f_authentication_ct.php">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                <input type="hidden" name="method" value="forgotPassword">
                <div class="form-group">
                    <div class="label-info">
                        <label for="username" class="bold">メールアドレス </label>
                        <span class="status warrant login-warrant">必須</span>　
                    </div>
                    <input type="text" name="email" id="email" placeholder="例）yamataro@gmail.com" required>
                </div>
                <?php
                if ($msg) echo '<div class="errorMsg"><span>' .$no_msg. '</span></div>';
                ?>
                <button type="submit" class="btn-submit">送信する</button>
            </form>
        </div>
    </div>
</section>
</body>

</html>