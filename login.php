<?php
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
                    <a href="">Forgot Password ?</a>
                </div>
                <?php
                    if ($msg) echo '<div class="errorMsg"><span>' .$msg. '</span></div>';
                ?>
                <button type="submit" class="btn-submit">Sign In</button>
            </form>
        </div>
    </div>
</section>

<section class="contact">
    <div class="contact__inner">
        <div class="contact__red"></div>
        <div class="contact__contents">
            <p class="contact__lead">お電話またはフォームから<br class="u-mobile">お問い合わせください。</p>
            <p class="contact__lead" style="margin-top:10px;">株式会社C.A.ピカード ジャパン</p>
            <div class="contact__company">
                <div class="contact-company__ca">
                    <p class="contact-company__name">川口本社オフィス</p>
                    <p class="contact-company__tel"><a href="tel:048-263-5017">048-263-5017</a></p>
                    <p class="contact-company__time">平日 9:00～17:00</p>
                    <p class="contact-company__address">〒333-0844 埼玉県川口市
                        <br>上青木2-42-6
                    </p>
                    <a href="https://goo.gl/maps/N3QXM1oqBS9qJAvSA" class="contact-company__map" target="_blank">Google map</a>
                </div><!-- ./contact-company__ca -->
                <div class="contact-company__kobe">
                    <p class="contact-company__name">神戸オフィス</p>
                    <p class="contact-company__tel"><a href="tel:078-862-3736">078-862-3736</a></p>
                    <p class="contact-company__time">平日 9:00～17:00</p>
                    <p class="contact-company__address">〒657-0028 兵庫県神戸市<br class="u-mobile">灘区森後町1-3-19
                        <br>リトルブラザーズ六甲ビル5F-D
                    </p>
                    <a href="https://goo.gl/maps/VGY9cSXtUMHwHnAA7" class="contact-company__map" target="_blank">Google map</a>
                </div><!-- ./contact-company__kobe -->
            </div><!-- ./contact__company -->

            <div class="contact__btn u-desktop">
                <a href="contact.php" class="btn btn--contact">お問い合わせはこちら</a>
            </div>
        </div><!-- ./contact__contents -->

        <div class="contact__btn u-mobile">
            <a href="contact.php" class="btn btn--contact">お問い合わせはこちら</a>
        </div>
    </div><!-- /.contact__inner -->
</section><!-- /.contact -->
</body>

</html>