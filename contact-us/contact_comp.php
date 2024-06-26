<?php
require_once __DIR__ . './../logic/front/mail_logic.php';
$mail_logic = new mail_logic();
$mail_logic->mail($_POST);

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <meta name="format-detection" content="telephone=no" />
  <!-- meta情報 -->
  <title>お問い合わせ | 株式会社デルファイレーザージャパン</title>
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

  <div class="page-titles page-title--red">
    <div class="page-titles__inner">
      <div class="page-titles__content">
        <p class="page-title">お問い合わせ</p>
        <span class="page-title--en">Contact</span>
      </div>
    </div>
  </div><!-- /.page-titles -->

  <p class="pan"><i class="fa-solid fa-house"></i>　|　お問い合わせ</p>

  <section class="products">
    <div class="products__inner l-inner">
      <p class="products__title">お問い合わせ</p>
      <p class="products__title--en">Contact</p>

      <div class="topText topText_fix topText_padding pt50">
        お問い合わせ頂き、誠にありがとうございます。<br>
        内容を確認次第、ご希望の連絡方法にてご連絡させていただきますが、万が一返信がない場合はお手数ですが再度お問い合わせください。
      </div> <!-- /.topText -->
    </div>

  </section><!-- /.products -->



  <?php include '../required/data/footer.php'; ?>

</body>

</html>