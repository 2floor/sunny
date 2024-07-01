<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/common/security_common_logic.php';

$security = new security_common_logic();
$csrf_token = $security->generateCsrfToken();

// エラーメッージ取得
$msg = isset($_GET['msg']) ? $_GET['msg'] : false;

// ID・PWが一致しない場合
if ($msg) {
$no_msg = 'ログインID、もしくは、パスワードが違います。再度、ログインID、PWが正しいかご確認をお願いします';
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no" />
    <!-- meta情報 -->
    <title>Login</title>
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
    <script defer type="text/javascript" src="./assets/js/script.js"></script>
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
                <img src="./assets/images/common/logo-bk.png" alt="株式会社C.A.ピカード ジャパン">
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
            <span class="page-title--en">Login</span>
        </div>
    </div>
</div><!-- /.page-titles -->

<section class="login">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Sign In</h1>
                <p>Your Social Campaigns</p>
            </div>
            <form method="POST" action="logon.php">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                <div class="form-group">
                    <div class="label-info">
                        <label for="username" class="bold">ログインID </label>
                        <span class="status warrant login-warrant">必須</span>　
                    </div>
                    <input type="text" name="username" id="username" placeholder="例）yamataro" required>
                </div>
                <div class="form-group">
                    <div class="label-info">
                        <label for="password" class="bold">パスワード </label>
                        <span class="status warrant login-warrant">必須</span>　
                    </div>
                    <input type="password" name="password" id="password" placeholder="半角英数8～12桁" required>
                </div>
                <div class="form-footer">
                    <a href="">パスワードをお忘れですか？</a>
                </div>
                <?php
                    if ($msg) echo '<div class="errorMsg"><span>' .$no_msg. '</span></div>';
                ?>
                <button type="submit" class="btn-submit">ログインする</button>
            </form>
        </div>
    </div>
</section>
</body>

</html>