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

<header class="header header--top js-drawer-open js-header">
  <div class="header__inner">
    <!-- <div class="header__img js-drawer-open">
      <img class="u-mobile" src="[path]assets/images/common/header_parts.png" alt="">
      <img class="u-desktop" src="[path]assets/images/common/header_parts-pc.png" alt="">
    </div> -->
    <div class="header__contents header__contents--top">
      <a href="[path]" class="header__logo--top js-drawer-open">
        <img src="[path]assets/images/common/logo.png" alt="株式会社デルファイレーザージャパン">
      </a>

      <div class="hamburger u-mobile js-drawer">
        <span class="drawer-menu"></span>
        <span class="drawer-menu"></span>
        <span class="drawer-menu"></span>
      </div><!-- ./hamburger u-mobile -->

      <div class="heder-pc heder-pc--top u-desktop">
        <nav class="heder-pc__nav">
          <ul class="pc-nav__lists">
            <li class="pc-nav__list"><a href="[path]" class="pc-nav__link">ホーム</a></li>
            <li class="pc-nav__list js-drop"><a href="[path]products-list/" class="pc-nav__link pc-nav-drop">製品情報</a>
            </li>
            <li class="pc-nav__list"><a href="[path]laser/" class="pc-nav__link">レーザー加工サービス</a></li>
            <li class="pc-nav__list"><a href="[path]flow.php" class="pc-nav__link">導⼊までの流れ</a></li>
            <li class="pc-nav__list"><a href="[path]document.php" class="pc-nav__link">技術資料</a></li>
            <li class="pc-nav__list js-drop"><a href="[path]company/reasons.php" class="pc-nav__link pc-nav-drop">会社情報</a>
              <div class="drop js-drop-open">
                <div class="drop__inner">
                  <div class="drop--red"></div>
                  <div class="drop__head">
                    <p class="drop__title--en">Company</p>
                    <p class="drop__title">会社情報</p>
                  </div>
                  <ul class="drop__lists">
                    <li class="drop__list">
                      <a href="[path]company/reasons.php" class="drop__link">
                        <div class="drop-card__img">
                          <img src="[path]assets/images/common/product01.png" alt="">
                        </div>
                        <p class="drop-card__title">当社の強み</p>
                      </a>
                    </li>
                    <li class="drop__list">
                      <a href="[path]company/" class="drop__link">
                        <div class="drop-card__img">
                          <img src="[path]assets/images/common/product02.png" alt="">
                        </div>
                        <p class="drop-card__title">会社概要</p>
                      </a>
                    </li>
                  </ul>
                </div>
              </div><!-- /.drop -->
            </li>
            <li class="pc-nav__list"><a href="[path]faq.php" class="pc-nav__link">よくある質問</a></li>
            <li class="pc-nav__list"><a href="[path]recruit.php" class="pc-nav__link">採用情報</a></li>
            <li class="pc-nav__list"><a href="[path]contact-us/" class="pc-nav__link">お問い合わせ</a></li>
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
      <img src="../assets/images/common/logo.png" alt="株式会社デルファイレーザージャパン">
    </div>
    <nav class="drawer-nav">
      <ul class="drawer-nav__lists">
        <li class="drawer-nav__list"><a href="[path]" class="drawer-nav__link">ホーム</a></li>
        <li class="drawer-nav__list"><a href="[path]products-list/" class="drawer-nav__link">製品情報</a></li>
        <li class="drawer-nav__list"><a href="[path]laser/" class="drawer-nav__link">レーザー加工サービス</a></li>
        <li class="drawer-nav__list"><a href="[path]flow.php" class="drawer-nav__link">導入までの流れ</a></li>
        <li class="drawer-nav__list"><a href="[path]document.php" class="drawer-nav__link">技術資料</a></li>
        <li class="drawer-nav__list"><a href="[path]company/reasons.php" class="drawer-nav__link">会社情報</a></li>
        <li class="drawer-nav__list"><a href="[path]faq.php" class="drawer-nav__link">よくある質問</a></li>
        <li class="drawer-nav__list"><a href="[path]recruit.php" class="drawer-nav__link">採用情報</a></li>
        <li class="drawer-nav__list"><a href="[path]contact-us/" class="drawer-nav__link">お問い合わせ</a></li>
        <li class="drawer-nav__list">
          <div class="global">
            <p class="global__header js-accordion">Global</p>
            <ul class="global__lists">
              <li class="global__list"><a href="http://en.delphilaser.com/" target="_blank" class="global__link">Global Website</a></li>
              <!-- Adjusted global links based on the PC navigation context -->
            </ul><!-- ./global__lists -->
          </div>
        </li>
      </ul><!-- ./drawer-nav__lists -->
    </nav>
  </div><!-- ./drawer__inner -->
</div><!-- ./drawer -->

<div class="header-margin"></div>
<?php
$html_content = ob_get_clean();
$html_content = str_replace('[path]', $path_base, $html_content);
echo $html_content;
?>