
<?php
//  BackTop 画像

$url = $_SERVER['REQUEST_URI'];
$img = '';

if($url == '/' || $url == '/products-list/' || $url == '/twin-screw-systems-for-plastics-and-food/' || $url == '/twin-screw-systems-for-plastics-and-food/service/' || $url == '/contact-us/' || $url == '/contact_conf.php' || $url == '/contact_comp.php' ||  $url == '/imprint/'){
	$img = '<img src="../../assets/images/common/mascot_a.png" alt="">';
}else{
	$img = '<img src="../../assets/images/common/mascot_b.png" alt="">';
}


// BackTop 画像 end
?>

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
								<a href="../" class="footer-nav__link">ホーム</a>
							</li><!-- ./nav-left__list -->
							<li class="nav-left__list">
								<a href="../company/" class="footer-nav__link">会社情報</a>
							</li><!-- ./nav-left__list -->
							<li class="nav-left__list">
								<a href="../news.php" class="footer-nav__link">News</a>
							</li><!-- ./nav-left__list -->
							<li class="nav-left__list">
								<a href="../contact-us/" class="footer-nav__link">お問い合わせ</a>
							</li><!-- ./nav-left__list -->
						</ul><!-- ./nav-left__lists -->
					</div><!-- ./footer-nav__left -->

					<div class="footer-nav__right">
						<a href="../products-list/" class="footer-nav__link">製品紹介</a>
						<ul class="nav-right__lists">
							<li class="nav-right__list">
								<a href="../products-list/product01.php" class="footer-nav__detail">微細加工関連レーザーシステム</a>
							</li><!-- .nav-right__list -->
							<li class="nav-right__list">
								<a href="../products-list/product02.php" class="footer-nav__detail">ディスプレイ関連レーザーシステム</a>
							</li><!-- .nav-right__list -->
							<li class="nav-right__list">
								<a href="../products-list/product03.php" class="footer-nav__detail">半導体関連レーザーシステム</a>
							</li><!-- .nav-right__list -->
							<li class="nav-right__list">
								<a href="../products-list/product04.php" class="footer-nav__detail">レーザー発振器</a>
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
				<a class="footer__privacy" href="../../data-privacy-statement/">プライバシーポリシー</a>
			</div>
		</div><!-- /.footer__inner -->
	</footer><!-- /.footer -->
	<div class="to-top pagetop">
		<?php print $img; ?>
	</div>