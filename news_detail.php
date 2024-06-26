<?php
// ////  セッションスタート// ////  
session_start();

// ////  設定ファイルの呼び出し // ////  
date_default_timezone_set('Asia/Tokyo');


// ////  データ受け取り // ////  
$n_id   = $_GET['n'];


// ////  URLリンク貼り // ////  
function url_link($b_str)
{
  //return ereg_replace ( "(https?|ftp)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)", "<a href=\"\\1\\2\" target=\"_blank\">\\1\\2</a>", $b_str );


  $replace_url = '/((ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?)/i';
  $text = preg_replace($replace_url, '<a href="$1">$1</a>', $b_str);
  return $text;
}

/*/////////////////////////////////////////

News取得

もし該当する情報がない場合（直URL叩き）
News一覧へ強制的に戻す

///////////////////////////////////////////*/

require_once __DIR__ . '/logic/front/news_logic.php';
$news_logic = new news_logic();

$num_f = $news_logic->check_news($_GET);

// //// 0件の場合 //// //
if ($num_f == '0') {

  header('Location: news.php');
  exit();

  // //// 1件以上の場合 //// //
} else {

  $result = $news_logic->create_news_detail($_GET);
  $n_date = $result['disp_date'];
  $n_cate = 'お知らせ';
  $n_title = $result['title'];
  $n_text = $result['detail'];
  $n_pic1 = $result['img'];

$n_pic2 = '';
$img = '';

  // ////  内容_改行処理 // ////  
  $n_text_kai = nl2br($n_text);

  // ////  内容_URLリンク貼り処理 // ////  
  $str1   = url_link($n_text_kai);

/*
  while ($row = mysqli_fetch_array($res_result, MYSQLI_ASSOC)) {
    $result[] = $row;
  }

  $result   =   $result[0];
  $n_date    =  $result['n_date'];
  $n_cate    =  $result['n_cate'];
  $n_title  =  $result['n_title'];
  $n_text    =  $result['n_text'];
  $n_pic1    =  $result['n_pic1'];
  $n_pic2    =  $result['n_pic2'];
  $n_pic3    =  $result['n_pic3'];
  $n_flg    =  $result['n_flg'];


  // ////  内容_改行処理 // ////  
  $n_text_kai = nl2br($n_text);

  // ////  内容_URLリンク貼り処理 // ////  
  $str1   = url_link($n_text_kai);
*/
}

print <<< EOF
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <meta name="format-detection" content="telephone=no" />
  <!-- meta情報 -->
  <title>会社概要 | 株式会社デルファイレーザージャパン</title>
  <meta name="description" content="株式会社デルファイレーザージャパンは、より長い寿命と末永いサービスの実現を目指しています。" />
  <meta name="keywords" content="二軸押出機用部品,多層基盤圧着機用,プレスプレート,鋳物造型機用部品,C.A.ピカード ジャパン,部品" />
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
                <li class="pc-nav__list js-drop"><a href="./company" class="pc-nav__link pc-nav-drop">会社概要</a>
                  <div class="drop js-drop-open">
                    <div class="drop__inner">
                      <div class="drop--red"></div>
                      <div class="drop__head">
                        <p class="drop__title--en">Company</p>
                        <p class="drop__title">会社概要</p>
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
                          <a href="./company" class="drop__link">
                            <div class="drop-card__img">
                              <img src="./assets/images/common/product02.png" alt="">
                            </div>
                            <p class="drop-card__title">会社情報</p>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div><!-- /.drop -->
                </li>
                <li class="pc-nav__list"><a href="./news.php" class="pc-nav__link">お知らせ</a></li>
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
                            <a href="./products-list/product01.php" class="drop__link">
                              <div class="drop-card__img">
                                <img src="./assets/images/common/product01.png" alt="">
                              </div>
                              <p class="drop-card__title">微細加工関連レーザーシステム</p>
                            </a>
                          </li>
                          <li class="drop__list">
                            <a href="./products-list/product02.php" class="drop__link">
                              <div class="drop-card__img">
                                <img src="./assets/images/common/product02.png" alt="">
                              </div>
                              <p class="drop-card__title">ディスプレイ関連レーザーシステム</p>
                            </a>
                          </li>
                          <li class="drop__list">
                            <a href="./products-list/product03.php" class="drop__link">
                              <div class="drop-card__img">
                                <img src="./assets/images/common/product03.png" alt="">
                              </div>
                              <p class="drop-card__title">半導体関連レーザーシステム</p>
                            </a>
                          </li>
                          <li class="drop__list">
                            <a href="./products-list/product04.php" class="drop__link">
                              <div class="drop-card__img">
                                <img src="./assets/images/common/product04.png" alt="">
                              </div>
                              <p class="drop-card__title">レーザー発振器</p>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div><!-- /.drop -->
                </li>
                <li class="pc-nav__list"><a href="./laser/" class="pc-nav__link">レーザー加工サービス</a></li>
                <li class="pc-nav__list"><a href="./document.php" class="pc-nav__link">技術資料</a></li>
                <li class="pc-nav__list"><a href="./flow.php" class="pc-nav__link">導⼊までの流れ</a></li>
                <li class="pc-nav__list"><a href="./contact-us/" class="pc-nav__link">お問い合わせ</a></li>
                <li class="pc-nav__list"><a href="./recruit.php" class="pc-nav__link">採⽤情報</a></li>
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
        <img src="./assets/images/common/logo.png" alt="株式会社デルファイレーザージャパン">
      </div>
      <nav class="drawer-nav">
        <ul class="drawer-nav__lists">
          <li class="drawer-nav__list"><a href="./" class="drawer-nav__link">TOP</a></li>
          <li class="drawer-nav__list"><a href="./company/" class="drawer-nav__link">会社情報</a></li>
          <li class="drawer-nav__list"><a href="./products-list/" class="drawer-nav__link">製品情報</a></li>
          <li class="drawer-nav__list"><a href="./news.php" class="drawer-nav__link">News</a></li>
          <li class="drawer-nav__list"><a href="./contact-us/" class="drawer-nav__link">お問い合わせ</a></li>
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
	<div class="page-titles page-title--red">
		<div class="page-titles__inner">
			<div class="page-titles__content">
				<p class="page-title">お知らせ詳細</p>
				<span class="page-title--en">NewsDetail</span>
			</div>
		</div>
	</div><!-- /.page-titles -->

	<p class="pan"><i class="fa-solid fa-house"></i>　|　<a href="./news.php">お知らせ</a>　|　{$n_title}</p>

	<section class="company">
		<div class="company__inner l-inner__2">
			<div class="company-summary2">
				<p class="company-summary__lead">{$n_title}</p>
				<time class="news__date">{$n_date}</time>
				<p class="company-summary__text2">
					{$str1}
				</p>
				<ul class="news_img">
EOF;
// 画像1があれば
if ($n_pic1 != '') {
  print <<< EOF

					<li><img src="./upload_files/news/{$n_pic1}" alt=""></li>
EOF;
}
// 画像1があれば
if ($n_pic2 != '') {
  print <<< EOF
					<li><img src="./upload_files/news/{$n_pic2}" alt=""></li>
EOF;
}
print <<< EOF
				</ul>
			</div><!-- ./company-summary -->
		</div>
	</section><!-- /.contact -->


  <section class="contact">
  <div class="contact__inner">
    <div class="contact__red"></div>
    <div class="contact__contents" style="background-size: cover; background-position: center; background-repeat: no-repeat;">
      <p class="contact__lead">お電話またはフォームから<br class="u-mobile">お問い合わせください。</p>
      <p class="contact__lead" style="margin-top:10px;">株式会社デルファイレーザージャパン</p>
      <div class="contact__company">
        <div class="contact-company__ca">
          <p class="contact-company__tel"><a href="tel:03-5735-0532">03-5735-0532</a></p>
          <p class="contact-company__time">平日 9:00～17:00</p>
          <p class="contact-company__address">〒144-0042東京都大田区羽田旭町2-1　コーピアス旭町1F</p>
          <a href="https://goo.gl/maps/N3QXM1oqBS9qJAvSA" class="contact-company__map" target="_blank">Google map</a>
        </div><!-- ./contact-company__ca -->
        
      </div><!-- ./contact__company -->

      <div class="contact__btn u-desktop">
        <a href="./contact-us/" class="btn btn--contact">お問い合わせはこちら</a>
      </div>
    </div><!-- ./contact__contents -->

    <div class="contact__btn u-mobile">
      <a href="./contact-us/" class="btn btn--contact">お問い合わせはこちら</a>
    </div>
  </div><!-- /.contact__inner -->
</section><!-- /.contact -->

<footer class="footer">
  <div class="footer__inner">
    <div class="footer__contents">
      <nav class="footer-nav">
        <div class="footer-nav__left">
          <ul class="nav-left__lists">
            <li class="nav-left__list">
              <a href="./" class="footer-nav__link">ホーム</a>
            </li><!-- ./nav-left__list -->
            <li class="nav-left__list">
              <a href="./company/" class="footer-nav__link">会社情報</a>
            </li><!-- ./nav-left__list -->
            <li class="nav-left__list">
              <a href="./news.php" class="footer-nav__link">News</a>
            </li><!-- ./nav-left__list -->
            <li class="nav-left__list">
              <a href="./contact-us/" class="footer-nav__link">お問い合わせ</a>
            </li><!-- ./nav-left__list -->
          </ul><!-- ./nav-left__lists -->
        </div><!-- ./footer-nav__left -->

        <div class="footer-nav__right">
          <a href="./products-list/" class="footer-nav__link">製品紹介</a>
          <ul class="nav-right__lists">
            <li class="nav-right__list">
              <a href="./products-list/product01.php" class="footer-nav__detail">微細加工関連レーザーシステム</a>
            </li><!-- .nav-right__list -->
            <li class="nav-right__list">
              <a href="./products-list/product02.php" class="footer-nav__detail">ディスプレイ関連レーザーシステム</a>
            </li><!-- .nav-right__list -->
            <li class="nav-right__list">
              <a href="./products-list/product03.php" class="footer-nav__detail">半導体関連レーザーシステム</a>
            </li><!-- .nav-right__list -->
            <li class="nav-right__list">
              <a href="./products-list/product04.php" class="footer-nav__detail">レーザー発振器</a>
            </li><!-- .nav-right__list -->
          </ul><!-- ./nav-right__lists -->
        </div><!-- ./footer-nav__right -->
      </nav><!-- ./footer-nav -->

      <div class="footer__logo">
        <img src="./assets/images/common/logo-bk.png" alt="">
      </div>
    </div><!-- /.footer__contents -->

    <div class="footer-bottom">
      <p class="footer__copyright">2022 &copy; Delphi Laser Japan All rights reserved.</p>
      <a class="footer__privacy" href="././data-privacy-statement/">プライバシーポリシー</a>
    </div>
  </div><!-- /.footer__inner -->
</footer><!-- /.footer -->
<div class="to-top pagetop">
  <?php print $img; ?>
</div>

</body>
</html>
EOF;
?>