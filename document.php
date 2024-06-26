<?php
require_once __DIR__ . '/logic/front/document_logic.php';
$document_logic = new document_logic();

$doc_html = $document_logic->create_document();

if (isset($_POST['send'])) {
  $name = $_POST['name'];
  $mail = $_POST['email'];
  $tel = $_POST['tel'];
  $company = $_POST['company'];
  $title = $_POST['title'];
  $file = $_POST['file'];

  $to = "info@delphilaser.co.jp";
  $subject = "技術資料がダウンロードされました。";
  $message = "技術資料がダウンロードされました。\n\n";
  $message .= '[氏名]' . "\n" . $name . "\n";
  $message .= '[会社名]' . "\n" . $company . "\n";
  $message .= '[電話番号]' . "\n" . $tel . "\n";
  $message .= '[メールアドレス]' . "\n" . $mail . "\n";
  $message .= '[資料名]' . "\n" . $file . "\n";

  $headers = "From: " . $mail;
  $header .= 'Reply-To: ' . $mail . "\r\n";

  $status = mb_send_mail($to, $subject, $message, $header);
  if ($status) {
    header('Location: /delphi/upload_files/technical/' . $file);
    exit();
  }
}
?>
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

    .none_dis {
      display: none;
    }

    /*モーダルを開くボタン*/
    .modal-open {
      cursor: pointer;
    }

    /*モーダル本体の指定 + モーダル外側の背景の指定*/
    .modal-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      text-align: center;
      background: rgba(0, 0, 0, 50%);
      padding: 40px 20px;
      overflow: auto;
      opacity: 0;
      visibility: hidden;
      transition: .3s;
      box-sizing: border-box;
    }

    /*モーダル本体の擬似要素の指定*/
    .modal-container:before {
      content: "";
      display: inline-block;
      vertical-align: middle;
      height: 100%;
    }

    /*モーダル本体に「active」クラス付与した時のスタイル*/
    .modal-container.active {
      opacity: 1;
      visibility: visible;
    }

    /*モーダル枠の指定*/
    .modal-body {
      position: relative;
      display: inline-block;
      vertical-align: middle;
      max-width: 600px;
      width: 90%;
    }

    /*モーダルを閉じるボタンの指定*/
    .modal-close {
      position: absolute;
      display: flex;
      align-items: center;
      justify-content: center;
      top: -40px;
      right: -40px;
      width: 40px;
      height: 40px;
      font-size: 40px;
      color: #fff;
      cursor: pointer;
    }

    /*モーダル内のコンテンツの指定*/
    .modal-content {
      background: #fff;
      text-align: left;
      padding: 20px;
    }

    .mail_w {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      margin-bottom: 15px;

      >dt {
        width: 200px;
        margin-bottom: 5px;
        display: flex;
        flex-wrap: nowrap;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;

        >span {
          border-radius: 100px;
          background-color: #fff;
          border: 1px solid #00479d;
          font-size: 11px;
          display: inline-block;
          line-height: 1.5;
          color: #00479d;
          padding: 2px 7px;
          margin-right: 0.5em;
          margin-left: 0.5em;
        }
      }

      >dd {
        width: calc(100% - 220px);
        margin-bottom: 5px;
        padding: 6px 0;
      }
    }

    button[type="submit"] {
      background: #00479d;
      color: #fff;
      width: 200px;
      border-radius: 5px;
      padding: 10px 30px 10px;
      border: none;
      font-size: 15px;
      cursor: pointer;
    }

    input[type="text"],
    input[type="tel"],
    input[type="email"] {
      outline: none;
      padding: 3px 2px;
      box-sizing: border-box;
      border: solid 1px #ccc;
      font-size: 15px;
      width: 100%;
    }

    .btn_w {
      text-align: center;
    }
  </style>
</head>

<body>
  <!-- header -->
  <?php include './required/data/header.php'; ?>

  <div class="page-titles page-title--red">
    <div class="page-titles__inner">
      <div class="page-titles__content">
        <p class="page-title">技術資料</p>
        <span class="page-title--en">Document</span>
      </div>
    </div>
  </div><!-- /.page-titles -->

  <p class="pan"><i class="fa-solid fa-house"></i>　|　技術資料</p>




  <section class="parts2">
    <div class="products__inner l-inner">
      <ul class="document-list">
        <li class="document-item">
          <div class="document-item__thum">
            <img src="./upload_files/technical/27806866_s.jpg" alt="新規技術新規技術新規技術" loading="lazy">
          </div>
          <div class="document-item__body">
            <p class="document-item__text modal-open" data-title="新規技術新規技術新規技術" data-file="test.pdf" id="doc_2">新規技術新規技術新規技術</p>
            <a class="document-item__d-link modal-open" href="./assets/pdf/01.pdf" target="_blank">ダウンロード</a>
          </div>
        </li>
        <li class="document-item">
          <div class="document-item__thum">
            <img src="./upload_files/technical/about01.jpg" alt="TEST" loading="lazy">
          </div>
          <div class="document-item__body">
            <p class="document-item__text modal-open" data-title="TEST" data-file="test (1).pdf" id="doc_3">TEST</p>
            <a class="document-item__d-link modal-open" href="./assets/pdf/02.pdf" target="_blank">ダウンロード</a>
          </div>
        </li>
      </ul>
    </div>
  </section>





  <?php include './required/data/footer.php'; ?>
  <div class="modal-container">
    <div class="modal-body">
      <div class="modal-close">×</div>
      <div class="modal-content">
        <form action="document.php" method="post" id="form">
          <dl class="mail_w">
            <dt>氏名<span>※必須</sapn>
            </dt>
            <dd><input type="text" name="name" value="" required></dd>
            <dt>会社名<span>※必須</sapn>
            </dt>
            <dd><input type="text" name="company" value="" required></dd>
            <dt>電話番号<span>※必須</sapn>
            </dt>
            <dd><input type="tel" name="tel" value="" required></dd>
            <dt>メールアドレス<span>※必須</sapn>
            </dt>
            <dd><input type="email" name="email" value="" required></dd>
          </dl>
          <input type="hidden" name="title" id="title">
          <input type="hidden" name="file" id="file">
          <p class="btn_w"><button type="submit" id="submit" name="send" value="post">送信</button></p>
        </form>
      </div>
    </div>
  </div>
</body>

</html>