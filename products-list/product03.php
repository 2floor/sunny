<?php
require_once __DIR__ . './../logic/front/product_logic.php';
$product_logic = new product_logic();

$items_name = 'ディスプレイ/ガラス';
$items_detail = $product_logic->create_product_detail($items_name);


?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0" />
	<meta name="format-detection" content="telephone=no" />
	<!-- meta情報 -->
	<title><?php echo $items_name; ?> | 株式会社デルファイレーザージャパン</title>
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
	<!-- ファビコン -->
	<link rel="”icon”" href="" />
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
	<!-- header -->
	<?php include '../required/data/header.php'; ?>

	<div class="page-titles page-title--yellow">
		<div class="page-titles__inner">
			<div class="page-titles__content">
				<p class="page-title"><?php echo $items_name; ?></p>
				<span class="page-title--en">Product01</span>
			</div>
		</div>
	</div><!-- /.page-titles -->

	<p class="pan"><i class="fa-solid fa-house"></i>　|　<a href="./">製品情報</a>　|　<?php echo $items_name; ?></p>

	<div class="columns l-inner">

		<?php $ber_txt = $product_logic->create_product_ber();echo $ber_txt; ?>
		<section class="parts2">
			<div class="parts2__inner">
				<div class="parts2-main">
					<div class="parts2-main__bg">
						<div class="parts2-main__img top-img">
							<img src="../assets/images/common/parts1-bg.png" alt="">
						</div>

						<div class="parts2-main__head">
							<p class="parts2-main-head__sub">Product01</p>
							<p class="parts2-main-head__lead"><?php echo $items_name; ?></p>
						</div><!-- ./parts2-main__head -->
					</div><!-- ./parts2-main__bg -->
				</div><!-- ./parts2-main -->
				<?php echo $items_detail; ?>
			</div>
		</section>
	</div>

	<?php include '../required/data/footer.php'; ?>
	<script>
		mediumZoom(document.querySelectorAll('#scale'), {
			margin: 24,
			background: '#292d3d',
			scrollOffset: 0,
		});
	</script>


</body>

</html>