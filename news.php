<?php
// ////  セッションスタート// ////  
session_start();

ini_set('display_errors', "On"); 
// ////  設定ファイルの呼び出し // ////  
date_default_timezone_set('Asia/Tokyo');

require_once __DIR__ . '/logic/front/news_logic.php';
$news_logic = new news_logic();

// ////  一ページに表示されるデータ数 //// //
// ////  件数セレクトボックスから選択がない場合 //// //
$p = $lmt = '';

if ($p == '') {
  $lmt = 10;
  $p    = 10;
} else {
  $lmt = $p;
}


// ////  受け取るオフセット //// //
$ff   = (isset($_GET['page'])) ? $_GET['page'] : '';
$offset = (isset($_GET['offset'])) ? $_GET['offset'] : $p;

if ($ff == "") {

  $ff = 1;
}

$res = $news_logic->create_news(10);
$news_html = $res['news_html'];
$num_s = $res['total'];

// ////  ページャー //// //
function pager($c, $t, $k, $p)
{
  $current_page   = $c;   //現在のページ
  $total_rec     = $t;  //総レコード数
  $page_rec     = $k;  //１ページに表示するレコード
  $total_page = ceil($total_rec / $page_rec); //総ページ数
  $show_nav = 3;  //表示するナビゲーションの数
  $path = '?p=' . $p . '&page=';  //パーマリンク

  //全てのページ数が表示するページ数より小さい場合、総ページを表示する数にする
  if ($total_page < $show_nav) {
    $show_nav = $total_page;
  }
  //トータルページ数が2以下か、現在のページが総ページより大きい場合表示しない
  if ($total_page <= 1 || $total_page < $current_page) return;
  //総ページの半分
  $show_navh = floor($show_nav / 2);
  //現在のページをナビゲーションの中心にする
  $loop_start = $current_page - $show_navh;
  $loop_end = $current_page + $show_navh;
  //現在のページが両端だったら端にくるようにする
  if ($loop_start <= 0) {
    $loop_start  = 1;
    $loop_end = $show_nav;
  }
  if ($loop_end > $total_page) {
    $loop_start  = $total_page - $show_nav + 1;
    $loop_end =  $total_page;
  }
?>
  <!-- pager -->
  <div class="pager">
    <ul class="pagination">
      <?php
      //2ページ移行だったら「一番前へ」を表示
      if ($current_page > 2) echo '<li class="pre"><a href="' . $path . '1">&laquo;</a></li>';
      //最初のページ以外だったら「前へ」を表示
      if ($current_page > 1) echo '<li class="pre"><a href="' . $path . ($current_page - 1) . '">&lsaquo;</a></li>';
      for ($i = $loop_start; $i <= $loop_end; $i++) {
        if ($i > 0 && $total_page >= $i) {
          if ($i == $current_page) echo '<li class="active">';
          else echo '<li>';
          echo '<a href="' . $path . $i . '">' . $i . '</a>';
          echo '</li>';
        }
      }
      //最後のページ以外だったら「次へ」を表示
      if ($current_page < $total_page) echo '<li class="next"><a href="' . $path . ($current_page + 1) . '">&rsaquo;</a></li>';
      //最後から２ページ前だったら「一番最後へ」を表示
      if ($current_page < $total_page - 1) echo '<li class="next"><a href="' . $path . $total_page . '">&raquo;</a></li>';
      ?>
    </ul>
  </div>

<?php
}
// ////  ページャーEnd //// //


print <<< EOF
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <meta name="format-detection" content="telephone=no" />
  <!-- meta情報 -->
  <title>お知らせ | 株式会社デルファイレーザージャパン</title>
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

  <div class="header-margin"></div>

EOF;
require("./required/data/header.php");
print <<< EOF

	<div class="page-titles page-title--red">
		<div class="page-titles__inner">
			<div class="page-titles__content">
				<p class="page-title">お知らせ</p>
				<span class="page-title--en">News</span>
			</div>
		</div>
	</div><!-- /.page-titles -->

	<p class="pan"><i class="fa-solid fa-house"></i>　|　お知らせ</p>
	
	
	<section class="products">
		<div class="products__inner l-inner">
			<p class="products__title">お知らせ</p>
			<p class="products__title--en">News</p>
			<ul class="news__lists">
				{$news_html}

EOF;

// //// ページャー //// //
if ($ff == '1') {
  $ff2 = '0';
} else {
  $ff2 = ($ff * $lmt) - 10;
}


print <<< EOF


			</ul><!-- ./news__lists -->

			<!-- pager -->

EOF;
pager($ff, $num_s, $lmt, $p);
print <<< EOF

		</div>
	</section><!-- /.products -->

EOF;
require("./required/data/footer.php");
print <<< EOF

</body>
</html>
EOF;
?>