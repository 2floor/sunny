<?php
$domain = $_SERVER['SERVER_NAME'];
$nowDir = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$nowDirAr = explode("/", $nowDir);

// 同一階層では"./"を使用し、下層の階層では"../"を追加
$path_base = "./";
$DirCounter = 0;
if (strpos($domain, "localhost") !== false || strpos($domain, "2floor.xyz") !== false) {
	// ローカル、2fテスト環境時
	$DirCounter -= 0;  // ここは変更なし
}

foreach ($nowDirAr as $ND) {
	if ($ND == '') continue;
	if (strpos($ND, '.php') !== false) break; // PHPファイルが見つかった時点でループを終了
	if (strpos($ND, '?') !== false) break; // クエリパラメータが見つかった時点でループを終了

	if ($domain != $ND) {
		++$DirCounter;
	}
}
// ディレクトリの深さに基づいて相対パスを追加
$path_base .= str_repeat("../", max($DirCounter - 1, 0));

// HTML出力前に[path]トークンを実際のパスで置換
ob_start(); // 出力バッファリングを開始
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
							<a href="[path]" class="footer-nav__link">ホーム</a>
						</li><!-- ./nav-left__list -->
						<li class="nav-left__list">
							<a href="[path]company/" class="footer-nav__link">会社情報</a>
						</li><!-- ./nav-left__list -->
						<li class="nav-left__list">
							<a href="[path]laser/" class="footer-nav__link">レーザー加工サービス</a>
						</li><!-- ./nav-left__list -->
						<li class="nav-left__list">
							<a href="[path]flow.php" class="footer-nav__link">導⼊までの流れ</a>
						</li><!-- ./nav-left__list -->
						<li class="nav-left__list">
							<a href="[path]document.php" class="footer-nav__link">技術資料</a>
						</li><!-- ./nav-left__list -->
						<li class="nav-left__list">
							<a href="[path]faq.php" class="footer-nav__link">よくある質問</a>
						</li><!-- ./nav-left__list -->

					</ul><!-- ./nav-left__lists -->
				</div><!-- ./footer-nav__left -->

				<div class="footer-nav__right">
					<ul class="nav-right__lists">

						<li class="nav-left__list">
							<a href="[path]recruit.php" class="footer-nav__link">採用情報</a>
						</li><!-- ./nav-left__list -->
						<li class="nav-left__list">
							<a href="[path]products-list/" class="footer-nav__link">製品情報</a>
						</li><!-- ./nav-left__list -->
						<li class="nav-right__list">
							<a href="[path]products-list/product01.php" class="footer-nav__detail">電子部品領域</a>
						</li><!-- .nav-right__list -->
						<li class="nav-right__list">
							<a href="[path]products-list/product02.php" class="footer-nav__detail">半導体領域</a>
						</li><!-- .nav-right__list -->
						<li class="nav-right__list">
							<a href="[path]products-list/product03.php" class="footer-nav__detail">ディスプレイ/ガラス領域</a>
						</li><!-- .nav-right__list -->
						<li class="nav-right__list">
							<a href="[path]products-list/product04.php" class="footer-nav__detail">レーザー発振器</a>
						</li><!-- .nav-right__list -->
						<li class="nav-left__list">
							<a href="[path]contact-us/" class="footer-nav__link">お問い合わせ</a>
						</li><!-- ./nav-left__list -->
					</ul><!-- ./nav-right__lists -->
				</div><!-- ./footer-nav__right -->
			</nav><!-- ./footer-nav -->

			<div class="footer__logo">
				<img src="[path]assets/images/common/logo-bk.png" alt="株式会社デルファイレーザージャパン">
			</div>
		</div><!-- /.footer__contents -->

		<div class="footer-bottom">
			<p class="footer__copyright">2022 &copy; Delphi Laser Japan All rights reserved.</p>
			<a class="footer__privacy" href="[path]data-privacy-statement/">プライバシーポリシー</a>
		</div>
	</div><!-- /.footer__inner -->
</footer><!-- /.footer -->


<div class="to-top pagetop">
	<img src="[path]/assets/images/common/mascot_b.png" alt="">
</div>















<?php
$html_content = ob_get_clean();
$html_content = str_replace('[path]', $path_base, $html_content);
echo $html_content;
?>