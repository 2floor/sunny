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

<div class="parts-menu u-desktop">
  <ul class="parts-menu__lists">
    <!-- -----------------------------電子部品------------------------ -->
    <li class="parts-menu__list">
      <p class="parts-menu__name js-accordion2">電子部品</p>
      <ul class="parts-detail__lists">
        <li class="parts-detail__list">
          <a href="[path]products-list/product01.php#01" class="parts-detail__link">レーザー微細加工装置</a>
        </li>
        <li class="parts-detail__list">
          <a href="[path]products-list/product01.php#02" class="parts-detail__link">ナノ秒UV切断装置</a>
        </li>
        <li class="parts-detail__list">
          <a href="[path]products-list/product01.php#03" class="parts-detail__link">セラミック切断装置</a>
        </li>
        <li class="parts-detail__list">
          <a href="[path]products-list/product01.php#04" class="parts-detail__link">セラミック穴あけ装置</a>
        </li>
        <li class="parts-detail__list">
          <a href="[path]products-list/product01.php#05" class="parts-detail__link">フィルム切断装置</a>
        </li>
        <li class="parts-detail__list">
          <a href="[path]products-list/product01.php#06" class="parts-detail__link">5軸ガルバノスキャナ穴あけ装置</a>
        </li>

      </ul>
    </li>

    <!-- -----------------------------半導体------------------------ -->
    <li class="parts-menu__list">
      <p class="parts-menu__name js-accordion2">半導体</p>
      <ul class="parts-detail__lists">
        <li class="parts-detail__list">
          <a href="[path]products-list/product02.php#07" class="parts-detail__link">ウエハマーキング装置</a>
        </li>
        <li class="parts-detail__list">
          <a href="[path]products-list/product02.php#08" class="parts-detail__link">ウエハスクライブ装置</a>
        </li>
        <li class="parts-detail__list">
          <a href="[path]products-list/product02.php#09" class="parts-detail__link">ウエハグルービング装置</a>
        </li>
      </ul>
    </li>


    <!-- -----------------------------ディスプレイ/ガラス------------------------ -->
    <li class="parts-menu__list">
      <p class="parts-menu__name js-accordion2">ディスプレイ/ガラス</p>
      <ul class="parts-detail__lists">
        <li class="parts-detail__list">
          <a href="[path]products-list/product03.php#10" class="parts-detail__link">微細穴あけ装置</a>
        </li>
        <li class="parts-detail__list">
          <a href="[path]products-list/product03.php#11" class="parts-detail__link">ガラス切断装置</a>
        </li>
        <li class="parts-detail__list">
          <a href="[path]products-list/product03.php#12" class="parts-detail__link">超薄ガラス切断装置</a>
        </li>
        <li class="parts-detail__list">
          <a href="[path]products-list/product03.php#13" class="parts-detail__link">レーザーエッチング装置</a>
        </li>
        <li class="parts-detail__list">
          <a href="[path]products-list/product03.php#14" class="parts-detail__link">曲面ガラス加工装置</a>
        </li>
      </ul>
    </li>

    <!-- -----------------------------発振器------------------------ -->

    <li class="parts-menu__list">
      <p class="parts-menu__name js-accordion2">発振器</p>
      <ul class="parts-detail__lists">
        <li class="parts-detail__list">
          <a href="[path]products-list/product04.php#15" class="parts-detail__link">ピコ秒レーザー(Amber NX series)</a>
        </li>
        <li class="parts-detail__list">
          <a href="[path]products-list/product04.php#16" class="parts-detail__link">フェムト秒レーザー(Axinite series)</a>
        </li>
        <li class="parts-detail__list">
          <a href="[path]products-list/product04.php#17" class="parts-detail__link">QCWファイバーレーザー(AFL series)</a>
        </li>
      </ul>
    </li>

  </ul>
</div><!-- /.parts-menu -->

<?php
$html_content = ob_get_clean();
$html_content = str_replace('[path]', $path_base, $html_content);
echo $html_content;
?>