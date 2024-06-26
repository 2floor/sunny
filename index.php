<?php
if (!isset($_SESSION)) {
	session_start();
}

// ////  設定ファイルの呼び出し // //// 
date_default_timezone_set('Asia/Tokyo');

require_once __DIR__ . '/logic/front/news_logic.php';
$news_logic = new news_logic();
ini_set('display_errors', "On");

$res = $news_logic->create_news(3);
$news_html = $res['news_html'];

print <<< EOF

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <meta name="format-detection" content="telephone=no" />
  <!-- meta情報 -->
  <title>TOP | 株式会社デルファイレーザージャパン</title>
  <meta name="description" content="株式会社デルファイレーザージャパンは、より長い寿命と末永いサービスの実現を目指しています。" />
  <meta name="keywords" content="二軸押出機用部品,多層基盤圧着機用,プレスプレート,鋳物造型機用部品,デルファイレーザージャパン,部品" />
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
  <link rel="stylesheet" href="./assets/css/styles.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.0.0/css/all.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.1/css/flag-icon.min.css">
  <!-- JavaScript -->
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
  <script defer type="text/javascript" src="./assets/js/script.js"></script>
  <style>
    @media(min-width: 768px) {
      a[href^="tel:"] {
        pointer-events: none;
      }
    }
		.movie-wrap {
			max-width: 1260px;
			margin: 0 auto;
			padding: 0;
			position: static;
			padding: 0 20px;
		}
		.movie-wrap video {
			aspect-ratio: 16/9;
			position: static;
		}
		.l-inner{
			width: auto;
		}
  </style>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        var slider = $('.slider').bxSlider({
            auto: true,
            pause: 10000,
						touchEnabled: false // タッチイベントを無効にする
        });
    });
</script>


</head>

<style>
@media(min-width: 768px) {
  a[href^="tel:"] {
	pointer-events: none;
  
  }
}
.none_dis{
	display: none;
}
.bx-wrapper{
background:none;
box-shadow:none;
  border:none;
}

.bx-viewport{
height:100% !important;
}
</style>
<body>
<header class="header header--top js-drawer-open js-header">
  <div class="header__inner">
    <div class="header__img js-drawer-open">
      <img class="u-mobile" src="./assets/images/common/header_parts.png" alt="">
      <img class="u-desktop" src="./assets/images/common/header_parts-pc.png" alt="">
    </div>
    <div class="header__contents header__contents--top">
      <a href="./" class="header__logo--top js-drawer-open">
        <img src="./assets/images/common/logo.png" alt="株式会社デルファイレーザージャパン">
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
            <li class="pc-nav__list js-drop"><a href="./products-list/" class="pc-nav__link pc-nav-drop">製品情報</a></li>
            <li class="pc-nav__list"><a href="./laser/" class="pc-nav__link">レーザー加工サービス</a></li>
            <li class="pc-nav__list"><a href="./flow.php" class="pc-nav__link">導⼊までの流れ</a></li>
            <li class="pc-nav__list"><a href="./document.php" class="pc-nav__link">技術資料</a></li>
            <li class="pc-nav__list js-drop"><a href="./company/reasons.php" class="pc-nav__link pc-nav-drop">会社情報</a>
              <div class="drop js-drop-open">
                <div class="drop__inner">
                  <div class="drop--red"></div>
                  <div class="drop__head">
                    <p class="drop__title--en">Company</p>
                    <p class="drop__title">会社情報</p>
                  </div>
                  <ul class="drop__lists">
                    <li class="drop__list">
                      <a href="./company/reasons.php" class="drop__link">
                        <div class="drop-card__img">
                          <img src="./assets/images/common/product01.png" alt="">
                        </div>
                        <p class="drop-card__title">当社の強み</p>
                      </a>
                    </li>
                    <li class="drop__list">
                      <a href="./company/" class="drop__link">
                        <div class="drop-card__img">
                          <img src="./assets/images/common/product02.png" alt="">
                        </div>
                        <p class="drop-card__title">会社概要</p>
                      </a>
                    </li>
                  </ul>
                </div>
              </div><!-- /.drop -->
            </li>
            <li class="pc-nav__list"><a href="./faq.php" class="pc-nav__link">よくある質問</a></li>
            <li class="pc-nav__list"><a href="./recruit.php" class="pc-nav__link">採用情報</a></li>
            <li class="pc-nav__list"><a href="./contact-us/" class="pc-nav__link">お問い合わせ</a></li>
            <li class="pc-nav__list">
              <div class="global">
                <a href="http://en.delphilaser.com/" target="_blank" class="global__header js-accordion">Global</a>
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
      <img src="./assets/images/common/logo.png" alt="株式会社デルファイレーザージャパン">
    </div>
    <nav class="drawer-nav">
      <ul class="drawer-nav__lists">
        <li class="drawer-nav__list"><a href="./" class="drawer-nav__link">ホーム</a></li>
        <li class="drawer-nav__list"><a href="./products-list/" class="drawer-nav__link">製品情報</a></li>
        <li class="drawer-nav__list"><a href="./laser/" class="drawer-nav__link">レーザー加工サービス</a></li>
        <li class="drawer-nav__list"><a href="./flow.php" class="drawer-nav__link">導入までの流れ</a></li>
        <li class="drawer-nav__list"><a href="./document.php" class="drawer-nav__link">技術資料</a></li>
        <li class="drawer-nav__list"><a href="./company/reasons.php" class="drawer-nav__link">会社情報</a></li>
        <li class="drawer-nav__list"><a href="./faq.php" class="drawer-nav__link">よくある質問</a></li>
        <li class="drawer-nav__list"><a href="./recruit.php" class="drawer-nav__link">採用情報</a></li>
        <li class="drawer-nav__list"><a href="./contact-us/" class="drawer-nav__link">お問い合わせ</a></li>
        <li class="drawer-nav__list">
          <div class="global">
            <p class="global__header js-accordion">Global</p>
            <ul class="global__lists">
              <li class="global__list"><a href="http://en.delphilaser.com" target="_blank" class="global__link">Global Website</a></li>
              <!-- Adjusted global links based on the PC navigation context -->
            </ul><!-- ./global__lists -->
          </div>
        </li>
      </ul><!-- ./drawer-nav__lists -->
    </nav>
  </div><!-- ./drawer__inner -->
</div><!-- ./drawer -->

	<div class="mv">

		<div class="mv__content l-inner js-drawer-open">
			<p class="mv__lead">LASERS ARE CREATING A MICRO WORLD</p>
			<p class="mv__text">微細加工に特化したレーザー加工装置<br>レーザー発振器を販売しております</p>
		</div><!-- /.mv__content -->
		<!-- News L1 -->
		<div class="mv-news js-drawer-open">
			<div class="mv-news__inner">
				{$res['top_news']}
				<div class="mv-news__btn u-desktop">
					<a href="news.php" class="btn btn__mv-news">一覧</a>
				</div>
			</div>
		</div><!-- /.mv__news -->
		<!-- /News L1 -->

		<!--
		<div class="swiper2 mv-bg">
			<div class="swiper-wrapper">
				<div class="swiper-slide">
					<div class="mv-bg__img">
						<img src="./assets/images/common/mv-pc2.jpg" alt="">
					</div>
				</div>
			</div>
		</div>
		-->

		<div class="scrolldown2 js-drawer-open"><span>Scroll</span></div>

	</div><!-- ./mv -->

	<section class="about">
		<div class="about__inner">
			<div class="swiper" id="about-swiper" >
				<div class="swiper-wrapper slider">
					<div class="swiper-slide ">
						<div class="about__content">
							<div class="about__left">
								<p class="about__title">About</p>
								<p class="about__lead">デルファイレーザージャパン</p>
								<p class="about__text">中国蘇州に本社を構えるSuzhou Delphi Laser Co., Ltd.の日本法人として2014年に設立されました。<br><br>微細加工に特化したレーザー加工装置メーカーとして、半導体、電子部品、セラミックス、ディスプレイ分野のお客様へ多数の装置の導入頂いております。お客様の希望する加工を丁寧にヒアリングし、最適な製品を選定の上、カスタマイズさせて頂きます。<br>
                また弊社ではDelphiLaserグループ内のSuzhou Bellin Laser Co., Ltd.で製造されたレーザー発振器も取り扱っております。
                </p>
								<p class="about__sub">
									<span class="about__next"><span class="about__next--red">NEXT</span><span
									class="about__next--bar"></span>02</span>
									<span class="about__sublead">微細加工に特化したレーザー加工システム</span>
								</p>
								<div class="about__btn">
									<a href="./company/index.php" class="btn btn--common">会社情報</a>
								</div>
							</div><!-- ./about__left -->
							<div class="about__right">
								<div class="about__img">
									<img src="./assets/images/common/top-about1.jpg" alt="デルファイレーザージャパン">
								</div>
								<div class="about__bottom">
									<div class="about__page"><span class="about__page--red">01</span> / 03</div>
								</div>
							</div><!-- ./about__right -->
						</div><!-- ./about__content -->
						<div class="swiper-pagination"></div>
					</div><!-- ./swiper-slide -->

          <div class="swiper-slide">
						<div class="about__content">
							<div class="about__left">
								<p class="about__title">About</p>
								<p class="about__lead">微細加工に特化したレーザー加工システム</p>
								<p class="about__text">自動ステージやレーザー発振器など、全ての装置の主要部品は微細加工を前提として選定、設計されております。<br><br>半導体領域や、電子部品領域では非常に高い品質が要求されます。装置に搭載されるガルバノスキャナや自動ステージ、光学部品は全て高精度、高品質な物を使用し、高性能なレーザー発振器を内製する事で、精度、品質面での最善の提案を行います。</p>
								<p class="about__sub">
									<span class="about__next"><span class="about__next--red">NEXT</span><span
									class="about__next--bar"></span>03</span>
									<span class="about__sublead">自動化や顧客要求によるカスタマイズ可能</span>
								</p>
								<div class="about__btn">
									<a href="./products-list/index.php" class="btn btn--common">製品情報</a>
								</div>
							</div><!-- ./about__left -->
							<div class="about__right">
								<div class="about__img">
									<img src="./assets/images/common/top-about2.jpg" alt="微細加工に特化したレーザー加工システム">
								</div>
								<div class="about__bottom">
									<div class="about__page"><span class="about__page--red">02</span> / 03</div>
								</div>
							</div><!-- ./about__right -->
						</div><!-- ./about__content -->
						<div class="swiper-pagination"></div>
					</div><!-- ./swiper-slide -->
					
          <div class="swiper-slide">
						<div class="about__content">
							<div class="about__left">
								<p class="about__title">About</p>
								<p class="about__lead">自動化や顧客要求によるカスタマイズ可能</p>
								<p class="about__text">弊社の提供する製品の殆どが自動化に対応しております。また顧客要求によるカスタマイズも可能です。<br><br>RolltoRoll式の装置構成や、基板搬送、半導体ウェハの自動ロード、アンロードに対応しております。また装置にはCCDカメラが搭載されており、自動でアライメントマークを読み取り、位置情報の補正を行います。
                また2台のレーザーを搭載して加工スピードを向上したい、カメラを複数台搭載したい、指定のレーザー発振器を搭載したい、主要部品や周辺機器は日本国内で手に入る物を使った装置にして欲しい、等のあらゆる要求に対してカスタマイズ可能です。
                </p>
								<p class="about__sub">
									<span class="about__next"><span class="about__next--red">NEXT</span><span
									class="about__next--bar"></span>01</span>
									<span class="about__sublead">デルファイレーザージャパン</span>
								</p>
								<div class="about__btn">
									<a href="./company/reasons.php" class="btn btn--common">私たちの強み</a>
								</div>
							</div><!-- ./about__left -->
							<div class="about__right">
								<div class="about__img">
									<img src="./assets/images/common/mv-pc3.jpg" alt="自動化や顧客要求によるカスタマイズ可能">
								</div>
								<div class="about__bottom">
									<div class="about__page"><span class="about__page--red">03</span> / 03</div>
								</div>
							</div><!-- ./about__right -->
						</div><!-- ./about__content -->
						<div class="swiper-pagination"></div>
					</div><!-- ./swiper-slide -->


				</div><!-- ./swiper-wrapper -->
			</div><!-- ./swiper -->
		</div><!-- ./about__inner -->
	</section><!-- ./about -->


	

	<section class="top-products">
		<div class="top-products__inner l-inner">
			<p class="products__title2">製品情報</p>
			<p class="products__title2--en">Products</p>
			<ul class="top-products__lists">
				<li class="top-products__list top-card">
					<a href="./products-list/product.php?cate=01" class="top-card__link">
						<div class="top-card__img">
							<img src="./assets/images/common/product01.png" alt="">
						</div>
						<div class="top-card__body">
							<p class="top-card__title">電子部品領域</p>
							<p class="top-card__subtitle"></p>
							<p class="top-card__num">PRODUCT 01</p>
							<p class="top-card__text"></p>
						</div>
					</a>
				</li><!-- ./top-products__list top-card -->

				<li class="top-products__list top-card">
					<a href="./products-list/product.php?cate=02" class="top-card__link">
						<div class="top-card__img">
							<img src="./assets/images/common/product03.png" alt="">
						</div>
						<div class="top-card__body">
							<p class="top-card__title">半導体領域</p>
							<p class="top-card__subtitle"></p>
							<p class="top-card__num">PRODUCT 02</p>
							<p class="top-card__text"></p>
						</div>
					</a>
				</li><!-- ./top-products__list top-card -->

				<li class="top-products__list top-card">
					<a href="./products-list/product.php?cate=03" class="top-card__link">
						<div class="top-card__img">
							<img src="./assets/images/common/product02.png" alt="">
						</div>
						<div class="top-card__body">
							<p class="top-card__title">ディスプレイ／ガラス領域</p>
							<p class="top-card__subtitle"></p>
							<p class="top-card__num">PRODUCT 03</p>
							<p class="top-card__text"></p>
						</div>
					</a>
				</li><!-- ./top-products__list top-card -->

				<li class="top-products__list top-card">
					<a href="./products-list/product.php?cate=04" class="top-card__link">
						<div class="top-card__img">
							<img src="./assets/images/common/product04.png" alt="">
						</div>
						<div class="top-card__body">
							<p class="top-card__title">レーザー発振機</p>
							<p class="top-card__subtitle"></p>
							<p class="top-card__num">PRODUCT 04</p>
							<p class="top-card__text"></p>
						</div>
					</a>
				</li><!-- ./top-products__list top-card -->

			</ul><!-- ./top-products__lists -->

			<div class="top-products__btn">
				<div class="products-btn_area">
					<a href="./document.php"><img src="./assets/images/common/products-download_btn.png" alt=""></a>
					<a href="./laser"><img src="./assets/images/common/products-contact_btn.png" alt=""></a>
				</div>

				<div class="products-btn">
					<a href="./products-list">製品一覧をみる</a>
				</div>
			</div>

			

		</div><!-- ./top-products__inner -->

		
	</section><!-- /.top-products -->

	<div class="movie">
		<div class="movie-wrap">
			<video src="./assets/images/video/delphi-movie.mov" controls="" buffered="" height="" width="100%" class="movie-style" style="cursor:pointer;"></video>
		</div>
	</div><!-- ./movie -->

	<section class="news">
		<div class="news__inner l-inner">
			<p class="news__title--en">News</p>
			<p class="news__title">お知らせ</p>
			<ul class="news__lists">
				{$news_html}
			</ul><!-- ./news__lists -->

			<div class="news__btn">
				<a href="news.php" class="btn btn--news">ニュース一覧を見る</a>
			</div>
		</div><!-- ./news__inner -->
	</section><!-- /.news -->
EOF;
include './required/data/footer.php';
print <<< EOF

<script src="./assets/js/jquery.bgswitcher.js"></script>
<script>
jQuery(function($) {
    $('.mv').bgSwitcher({
        images: ['./assets/images/common/mv-pc2.jpg','./assets/images/common/mv-pc1.jpg'],
        interval: 5000,
        loop: false,
        shuffle: false,
        effect: "drop",
        duration: 2000,
        easing: "swing"
		
    });
});
</script>

</body>
</html>
EOF;
