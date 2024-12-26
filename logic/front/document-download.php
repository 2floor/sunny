<!--?php
require_once __DIR__ . '/logic/front/document_logic.php';
$document_logic = new document_logic();

$doc_html = $document_logic->create_document();

?-->
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <meta name="format-detection" content="telephone=no" />
  <!-- meta情報 -->

  <title>株式会社デルファイレーザー</title>
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
</style>

<body>
  <!-- header -->
  <?php include './required/data/header.php'; ?>

  <div class="page-titles page-title--red">
    <div class="page-titles__inner">
      <div class="page-titles__content">
        <p class="page-title">技術資料ダウンロード</p>
        <span class="page-title--en">Document Download</span>
      </div>
    </div>
  </div><!-- /.page-titles -->

  <p class="pan"><i class="fa-solid fa-house"></i>　|　技術資料　|　技術資料ダウンロード</p>




  <section class="download-form">
    <div class="products__inner l-inner">
      
      <form action="/form.php" method="post">
        <div>
            <label for="name">名前</label>
            <input type="text" id="name" name="name">
        </div>
        <div>
            <label for="email">メールアドレス</label>
            <input type="mail" id="email" name="email">
        </div>
        <div>
            <label for="message">内容</label>
            <textarea id="message" name="message"></textarea>
        </div>
        <div>
            <label for="message">内容</label>
            <?php 
            session_start();
            print  '<input type="hidden" name="name" value="'.$_SESSION['login_id'].'">';
            ?>
        </div>
        <input type="submit" value="送信する">
    </form>
      </ul>
    </div>
  </section>





  <?php include './required/data/footer.php'; ?>

</body>

</html>