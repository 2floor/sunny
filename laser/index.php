<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <meta name="format-detection" content="telephone=no" />
  <!-- meta情報 -->
  <title>レーザー加工サービス | 株式会社デルファイレーザージャパン</title>
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
        <p class="page-title">レーザー加工サービス</p>
        <span class="page-title--en">Bellin Laser</span>
      </div>
    </div>
  </div><!-- /.page-titles -->

  <p class="pan"><i class="fa-solid fa-house"></i>　|　レーザー加工サービス</p>

  <div class="products__inner l-inner">
    <p class="page__title--jp">テスト加工について</p>
    <p class="about__text" style="text-align: center">装置導入案件を前提としています。日本、中国にて有償で対応します。<br>エッチング、スクライブ、穴あけ、切断などあらゆる加工が可能です</p>
  </div>

  <div class="contact__btn">
    <a href="./processing-form.php" class="btn btn--common">テスト加工申請フォーム</a>
  </div>

  <div style="margin-top: 60px"></div>

  <div class="products__inner l-inner">
    <p class="page__title--jp">受託加工について</p>
    <p class="about__text" style="text-align: center">基本的には日本国内で対応します。<br>加工条件出しのためのサンプルとお時間を頂きます。(加工条件は開示できません。)</p>
  </div>

  <div class="laser__text l-inner">
    <p>事例1)テスト加工後の結果が良かったので、装置納入までの期間の生産に対応したい</p>
    <p>事例2)装置を導入するほどでは無いが、小規模な生産を行いたい</p>
    <p>事例3)開発中の製品の加工検証をしたい</p>
  </div>

  <div class="contact__btn">
    <a href="./processing-form.php" class="btn btn--common">テスト加工申請フォーム</a>
  </div>








  <?php include '../required/data/footer.php'; ?>

  <script src="./assets/js/jquery.bgswitcher.js"></script>
  <script>
    jQuery(function($) {
      $('.mv').bgSwitcher({
        images: ['./assets/images/common/mv-pc2.jpg', './assets/images/common/mv-pc3.png', './assets/images/common/mv-pc.png'],
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