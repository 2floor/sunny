<?php

$status = isset($_GET['status']) ? $_GET['status'] : 'no';
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
    <!-- JavaScript -->

    <style>
        section.login {
            margin-top: 8rem;
        }

        .btn-back {
            margin-top: 50px;
            background-color: #FFFFFF;
            border: 1px solid #0A74B0;
            color: #0A74B0;
        }

        .btn-back:hover {
            background-color: #EEEEEE;
        }

        header {
            display: none;
        }

        .header-margin {
            margin: 0;
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
                <?php if ($status == 'yes'): ?>
                <h1>パスワードを正常にリセットしました</h1>
                <p>新しいパスワードを使用してログインを続けることができます</p>
                <?php else: ?>
                    <h1>パスワードのリセットに失敗しました</h1>
                    <p>もう一度試すか、カスタマーサービスにお問い合わせください。</p>
                <?php endif; ?>
            </div>
            <?php if ($status == 'yes'): ?>
            <a href="login.php" class="btn btn-back btn-submit">ログイン画面へ戻る</a>
            <?php else: ?>
                <a href="forgot_password.php" class="btn btn-back btn-submit">パスワードを忘れた場合の画面に戻る</a>
            <?php endif; ?>
        </div>
    </div>
</section>
</body>

</html>