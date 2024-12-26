<?php
require_once __DIR__ . '/logic/front/recruit_logic.php';
$recruit_logic = new recruit_logic();
$html = $recruit_logic->create_recruit_html();

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <meta name="format-detection" content="telephone=no" />
  <!-- meta情報 -->
  <title>RECRUIT | 株式会社デルファイレーザージャパン</title>
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

<style>
  @media(min-width: 768px) {
    a[href^="tel:"] {
      pointer-events: none;
    }
  }

  .none_dis {
    display: none;
  }

  .page__title--jp-wrapper {
    display: flex;
    justify-content: center;
    position: relative;
    column-gap: 20px;
    align-items: center;
  }

  .toggle-icon {
    width: 30px;
    height: 30px;
    position: relative;
  }

  .toggle-icon::after,
  .toggle-icon::before {
    background-color: #00479d;
    width: 100%;
    height: 2px;
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    transition: transform .3s;
  }

  .toggle-icon::before {
    transform: translate(-50%, -50%) rotate(-90deg);
  }

  .toggle-icon.is-active::before {
    transform: translate(-50%, -50%) rotate(0deg);

  }

  .accordion dl {
    max-height: 0;
    visibility: hidden;
    opacity: 0;
  }

  .accordion dl.is-active {
    max-height: 10000000px;
    visibility: visible;
    opacity: 1;
  }

  .recruit-content {
    row-gap: 50px;
  }

  @media screen and (max-width: 768px) {
    .page__title--jp {
      font-size: 20px;
    }

    .l-inner {
      padding: 0 10px;
    }

    .toggle-icon {
      width: 15px;
      height: 15px;
    }

    .page__title--jp {
      width: 80%;
    }
  }
</style>

<body>
  <!-- header -->
  <?php include './required/data/header.php'; ?>

  <div class="page-titles page-title--red">
    <div class="page-titles__inner">
      <div class="page-titles__content">
        <p class="page-title">採用情報</p>
        <span class="page-title--en">Recruit</span>
      </div>
    </div>
  </div><!-- /.page-titles -->

  <p class="pan"><i class="fa-solid fa-house"></i>　|　採用情報</p>

  <section class="parts2">
    <div class="products__inner l-inner">
      <div class="recruit-content">

	<?php echo $html; ?>

    </div>
  </section><!-- /.parts2 -->

  <?php include './required/data/footer.php'; ?>


  <script>
    const btns = document.querySelectorAll('.page__title--jp-wrapper');
    btns.forEach(btn => {
      const dl = btn.nextElementSibling;
      const icon = btn.querySelector('span');
      btn.addEventListener('click', function() {
        dl.classList.toggle('is-active');
        icon.classList.toggle('is-active');
      });
    });
  </script>

  </script>
</body>

</html>