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

      <div class="topText topText_fix topText_padding">
        お問い合わせ頂き、誠にありがとうございます。<br>当社へのお問い合わせは下記のメールフォームもしくはお電話から承っています。<br>
        まずはお気軽にご連絡ください。内容を確認次第、ご連絡させていただきますが、万が一返信がない場合はお手数ですが再度お問い合わせください。
      </div> <!-- /.topText -->


      <div class="partsWrap02_a">
        <!-- messageここから -->
        <div class="message">
          <div class="inner">
            <form action="contact_comp.php" name="post_frm" id="post_frm" method="POST">
              <div class="messageInner">
                <div class="message_textBox2_2">
                  <div class="message_text2">
                    <p class="bold">お名前</p>
                    <p><input type="hidden" value="山田太郎" placeholder="例：山田太郎" name="name" id="kword" /></p>
                    <p>山田太郎</p>
                  </div>
                </div>
              </div>
              <div class="messageInner">
                <div class="message_textBox2_2">
                  <div class="message_text2">
                    <p class="bold">会社名　<span class="tagBox tag">必須</span></p>
                    <p><input type="hidden" value="後藤商事（株）" placeholder="" name="c_name" id="kword" /></p>
                    <p>後藤商事（株）</p>
                  </div>
                </div>
              </div>
              <div class="messageInner">
                <div class="message_textBox2_2">
                  <div class="message_text2">
                    <p class="bold">メールアドレス</p>
                    <p><input type="hidden" value="test@2floor.jp" placeholder="" name="email" id="kword" /></p>
                    <p>test@2floor.jp</p>
                  </div>
                </div>
              </div>
              <div class="messageInner">
                <div class="message_textBox2_2">
                  <div class="message_text2">
                    <p class="bold">お電話</p>
                    <p><input type="hidden" value="048-833-0780" placeholder="例：000-000-0000" name="tel" id="kword" /></p>
                    <p>048-833-0780</p>
                  </div>
                </div>
              </div>
              <div class="messageInner">
                <div class="message_textBox2_2">
                  <div class="message_text2">
                    <p class="bold">お問い合わせの種類</p>
                    <p>
                      製品について
                    </p>
                  </div>
                </div>
              </div>
              <div class="messageInner">
                <div class="message_textBox2_2">
                  <div class="message_text2">
                    <p class="bold">ご相談・お問い合わせ</p>
                    <p>
                      ここにお問い合わせ内容が入ります。<br>
                      ここにお問い合わせ内容が入ります。<br>
                      ここにお問い合わせ内容が入ります。
                    </p>
                  </div>
                </div>
              </div>

              <div class="contact_linkWrap flex pt50">

                <button type="back" class="subb2">戻る</button>

                <input type="submit" value="確認画面に進む" class="subb">

              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </section><!-- /.products -->

  <?php include '../required/data/footer.php'; ?>


</body>

</html>