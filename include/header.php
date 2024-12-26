<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0" />
	<meta name="format-detection" content="telephone=no" />
	<!-- meta情報 -->
	<title>デルファイレーザージャパン</title>
	<meta name="description" content="デルファイレーザージャパン" />
	<meta name="keywords" content="デルファイレーザージャパン" />
	<link rel="shortcut icon" href="favicon.ico" />
	<!-- ogp -->
	<meta property="og:title" content="" />
	<meta property="og:type" content="" />
	<meta property="og:url" content="" />
	<meta property="og:image" content="" />
	<meta property="og:site_name" content="" />
	<meta property="og:description" content="" />
	<!-- フォント -->
	<link rel="stylesheet" href="https://use.typekit.net/xrp3csv.css">
	<!-- css -->
	<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
	<link rel="stylesheet" href="../assets/css/styles.css">
	<link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.0.0/css/all.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.1/css/flag-icon.min.css">
	<!-- JavaScript -->
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
	<script defer type="text/javascript" src="../assets/js/script.js"></script>
	<style>
		@media(min-width: 768px) {
			a[href^="tel:"] {
				pointer-events: none;
			}
		}
	</style>
</head>

<body>
<header class="header header--top js-drawer-open js-header">
    <div class="header__inner">
      <div class="header__img js-drawer-open">
        <img class="u-mobile" src="./assets/images/common/header_parts.png" alt="">
        <img class="u-desktop" src="./assets/images/common/header_parts-pc.png" alt="">
      </div>
      <div class="header__contents header__contents--top">
        <a href="./" class="header__logo--top js-drawer-open">
          <img src="../assets/images/common/logo.png" alt="株式会社デルファイレーザージャパン">
        </a>

          <div class="hamburger u-mobile js-drawer">
            <span class="drawer-menu"></span>
            <span class="drawer-menu"></span>
            <span class="drawer-menu"></span>
          </div><!-- ./hamburger u-mobile -->

          <div class="heder-pc heder-pc--top u-desktop">
            <nav class="heder-pc__nav">
              <ul class="pc-nav__lists">
                <li class="pc-nav__list"><a href="./" class="pc-nav__link">ホーム</a></li>
								<li class="pc-nav__list"><a href="./news.php" class="pc-nav__link">当社の強み</a></li>
                <li class="pc-nav__list js-drop">
                  <a href="./products-list/" class="pc-nav__link pc-nav-drop">製品情報</a>
                    <div class="drop js-drop-open">
                      <div class="drop__inner">
                        <div class="drop--red"></div>
                        <div class="drop__head">
                          <p class="drop__title--en">Products</p>
                          <p class="drop__title">製品情報</p>
                        </div>
                        <ul class="drop__lists">
                          <li class="drop__list">
                            <a href="../products-list/product01.php" class="drop__link">
                              <div class="drop-card__img">
                                <img src="../assets/images/common/product01.png" alt="">
                              </div>
                              <p class="drop-card__title">微細加工関連レーザーシステム</p>
                            </a>
                          </li>
                          <li class="drop__list">
                            <a href="../products-list/product02.php" class="drop__link">
                              <div class="drop-card__img">
                                <img src="../assets/images/common/product02.png" alt="">
                              </div>
                              <p class="drop-card__title">ディスプレイ関連レーザーシステム</p>
                            </a>
                          </li>
                          <li class="drop__list">
                            <a href="../products-list/product03.php" class="drop__link">
                              <div class="drop-card__img">
                                <img src="../assets/images/common/product03.png" alt="">
                              </div>
                              <p class="drop-card__title">半導体関連レーザーシステム</p>
                            </a>
                          </li>
                          <li class="drop__list">
                            <a href="../products-list/product04.php" class="drop__link">
                              <div class="drop-card__img">
                                <img src="../assets/images/common/product04.png" alt="">
                              </div>
                              <p class="drop-card__title">レーザー発振器</p>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div><!-- /.drop -->
                </li>
                <li class="pc-nav__list"><a href="./company/" class="pc-nav__link">会社情報</a></li>
                <li class="pc-nav__list"><a href="./company/" class="pc-nav__link">導⼊までの流れ</a></li>
                <li class="pc-nav__list"><a href="./company/" class="pc-nav__link">よくある質問</a></li>
                <li class="pc-nav__list"><a href="./news.php" class="pc-nav__link">お知らせ</a></li>
                <li class="pc-nav__list"><a href="./news.php" class="pc-nav__link">採⽤情報</a></li>
                <li class="pc-nav__list"><a href="./contact-us/" class="pc-nav__link">お問い合わせ</a></li>
                <li class="pc-nav__list">
                  <div class="global">
                    <p class="global__header js-accordion">Global</p>
                    <ul class="global__lists">
						<li class="global__list"><span class="flag-icon flag-icon-de  flag-icon-squared"></span> <span class="flag-icon flag-icon-us flag-icon-squared"></span> <a href="https://www.capicard.de/en/" target="_blank" class="global__link">ドイツ本社</a></li>
						<li class="global__list"><span class="flag-icon flag-icon-hk  flag-icon-squared"></span> <span class="flag-icon flag-icon-cn  flag-icon-squared"></span> <a href="http://www.capicard.com.cn/index.php?lang=zh_cn" target="_blank" class="global__link">香港・中国</a></li>
                    </ul><!-- ./global__lists -->
                  </div>
                </li>
              </ul><!-- ./drawer-nav__lists -->
            </nav><!-- /.heder-pc__nav -->
          </div><!-- ./heder-pc u-desktop -->


      </div><!-- ./header__contents -->
    </div><!-- ./header__inner -->
  </header>

	<div class="drawer js-drawer-open">
		<div class="drawer__inner">
			<div class="drawer__logo">
				<img src="../assets/images/common/logo.png" alt="株式会社C.A.ピカード ジャパン">
			</div>
			<nav class="drawer-nav">
				<ul class="drawer-nav__lists">
			          <li class="drawer-nav__list"><a href="../" class="drawer-nav__link">TOP</a></li>
			          <li class="drawer-nav__list"><a href="../company/" class="drawer-nav__link">会社情報</a></li>
			          <li class="drawer-nav__list"><a href="../products-list/" class="drawer-nav__link">製品情報</a></li>
			          <li class="drawer-nav__list"><a href="../news.php" class="drawer-nav__link">News</a></li>
			          <li class="drawer-nav__list"><a href="../contact-us/" class="drawer-nav__link">お問い合わせ</a></li>
					<li class="drawer-nav__list">
						<div class="global">
							<p class="global__header js-accordion">Global</p>
							<ul class="global__lists">
								<li class="global__list"><span class="flag-icon flag-icon-de  flag-icon-squared"></span> <span class="flag-icon flag-icon-us flag-icon-squared"></span> <a href="https://www.capicard.de/en/" target="_blank" class="global__link">ドイツ本社</a></li>
								<li class="global__list"><span class="flag-icon flag-icon-hk  flag-icon-squared"></span> <span class="flag-icon flag-icon-cn  flag-icon-squared"></span> <a href="http://www.capicard.com.cn/index.php?lang=zh_cn" target="_blank" class="global__link">香港・中国</a></li>
							</ul><!-- ./global__lists -->
						</div>
					</li>
				</ul><!-- ./drawer-nav__lists -->
			</nav>
		</div><!-- ./drawer__inner -->
	</div><!-- ./drawer -->

	<div class="header-margin"></div>