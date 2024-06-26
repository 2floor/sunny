<?php
require_once __DIR__ . './../logic/front/product_logic.php';
$product_logic = new product_logic();

$items_name = '';
$search_tag = $product_logic->create_search_tag($items_name);
$now = '';
if(isset($_GET["now"])){
  $now = $_GET["now"];
}

if(isset($_POST['search'])){
  $s_tag = $_POST['s_tag'];
  $items_html = $product_logic->create_product_list($now, $s_tag, $_GET);
  $items_name = $items_html['cate'];


}else{
  $items_html = $product_logic->create_product_list($now, "", $_GET);
  $items_name = $items_html['cate'];
}

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
.pr_list{
  display: flex;
  flex-wrap:wrap;
}

.pr_list > li{
  width: 32%;
  padding:20px;
  box-sizing: border-box;
  margin-bottom:1%;
  margin-left:1%;
  border:solid 1px #c5c5c5;
}

@media (max-width:1180px){
  .pr_list > li{
    width: 48%;
  }
}

@media (max-width:560px){
  .pr_list > li{
    width: 100%;
  }
}

.tag_w{
  display: flex;
  flex-wrap:wrap;
}

.tag_w > li span{
  font-size:12px;
  background:#fff;
  border:solid 1px #074089;
  color:#074089;
  border-radius:12px;
  padding:1px 8px;
  transition: .2s cubic-bezier(0.45, 0, 0.55, 1);
  margin-right:3px;
}

.tag_w > li a:hover{
  background:#074089;
  border:solid 1px #074089;
  color:#fff ;
}

.title_t{
  font-size:17px;
  margin:6px 0 10px;
}

.detail_btn{
  display: block;
  margin-inline: auto;
  background:#074089;
  border:solid 1px #074089;
  color:#fff;
  padding:5px 10px;
  transition: .2s cubic-bezier(0.45, 0, 0.55, 1);
  font-size: 15px;
  width:200px;
  text-align: center;
  border-radius:22px;
}

.detail_btn:hover{
  background:#aaa;
  color:#074089;
}

.imgt{
  width:100%;
  height:200px;
  object-fit: cover;
  filter: unset !important;
  margin-bottom:10px;
}

.open_w{
  text-align: right;
}

.modal-open{
  width: 200px;
  height: 50px;
  font-weight: bold;
  color: #074089;
  background:#fff;
  border: solid 1px #074089;
  margin: 0 0 0 auto;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: .2s cubic-bezier(0.45, 0, 0.55, 1);
}

.modal-open:hover{
  color: #fff;
  background:#074089;
}

.modal-container{
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  text-align: center;
  background: rgba(0,0,0,50%);
  padding: 40px 20px;
  overflow: auto;
  opacity: 0;
  visibility: hidden;
  transition: .3s;
  box-sizing: border-box;
}
.modal-container:before{
  content: "";
  display: inline-block;
  vertical-align: middle;
  height: 100%;
}
.modal-container.active{
  opacity: 1;
  visibility: visible;
}
.modal-body{
  position: relative;
  display: inline-block;
  vertical-align: middle;
  max-width: 800px;
  width: 90%;
}
.modal-close{
  position: absolute;
  display: flex;
  align-items: center;
  justify-content: center;
  top: 0px;
  right: 0px;
  width: 40px;
  height: 40px;
  font-size: 40px;
  color: #000;
  cursor: pointer;
}
.modal-content{
  background: #fff;
  text-align: left;
  padding: 30px;
}

.search_w{
  display: flex;
  flex-wrap:wrap;
  > li{
    margin: 0 10px 10px 0;
  }
}


.search_btn{
  margin-top:20px;
  display: block;
  margin-inline: auto;
  background:#074089;
  border:solid 1px #074089;
  color:#fff;
  padding:5px 10px;
  transition: .2s cubic-bezier(0.45, 0, 0.55, 1);
  font-size: 15px;
  width:200px;
  text-align: center;
  border-radius:22px;
}

.search_btn:hover{
  background:#aaa;
  color:#074089;
}

.chk_label {
  display: inline-block;
  padding: 6px 13px;
  font-size:14px;
  background-color: #ddd;
  color: #333;
  cursor: pointer;
  margin: 3px;
  border-radius: 25px;
  font-size: 1rem;
  transition: background-color 0.3s ease;
}

.chk_label input[type="checkbox"] {
  display: none;
}

.chk_label:has(input[type="checkbox"]:checked) {
  background-color: #074089;
  color: #fff;
}

.parts2-sub {
    margin-top: 50px;
}

.pager .pagination li a{
  display: flex;
  justify-content: center;
  align-items: center;
}

.tag_ttl{
  font-weight: bold;
  text-align: center;
  margin: 10px auto;
  color: #074089;
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
						<div class="open_w"><div class="modal-open">絞り込み検索</div></div>
					</div><!-- ./parts2-main__bg -->
				</div><!-- ./parts2-main -->
				<div class="parts2-sub">
					<ul class="pr_list">
						<?php echo $items_html["html"]; ?>
					</ul>
					<div class="pager mb50">
						<ul class="pagination">
							<?php echo $items_html["pager"] ?>
						</ul>
					</div>
				</div>
			</div>
		</section>
	</div>
<div class="modal-container">
	<div class="modal-body">
		<!-- 閉じるボタン -->
		<div class="modal-close">×</div>
		<!-- モーダル内のコンテンツ -->
		<div class="modal-content">
			<form method="post" action="product.php">
				<?php echo $search_tag; ?>
			<p><button type="submit" name="search" class="search_btn">検索</button></p>
			</form>
		</div>
	</div>
</div>
	<?php include '../required/data/footer.php'; ?>
	<script>
		mediumZoom(document.querySelectorAll('#scale'), {
			margin: 24,
			background: '#292d3d',
			scrollOffset: 0,
		});
	</script>
<script>
$(function(){
	var open = $('.modal-open'),
		close = $('.modal-close'),
		container = $('.modal-container');

	open.on('click',function(){	
		container.addClass('active');
		return false;
	});

	close.on('click',function(){	
		container.removeClass('active');
	});

	$(document).on('click',function(e) {
		if(!$(e.target).closest('.modal-body').length) {
			container.removeClass('active');
		}
	});
});
</script>

</body>

</html>