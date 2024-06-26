<?php
date_default_timezone_set('Asia/Tokyo');

// セッションスタート // 
session_start();

// エスケープ処理 // 
function h($str1)
{

	$str1 = htmlspecialchars($str1, ENT_QUOTES);
	return $str1;
}


// エラーカウント // 
$err_count = 0;

$errn_count 		= 0;
$errca_count 		= 0;
$erre_count 		= 0;
$errer_count 		= 0;
$erree_count 		= 0;
$errmemo_count 		= 0;
$errabout_count 	= 0;
$errtel_count 		= 0;

// フラグ系  // 
$f = $_GET['f'];


// 入力チェック（登録画面→確認画面前） // 
if ($_POST['chk'] == "1") {


	// エスケープ処理
	$name			=	h($_POST['name']);
	$c_name			=	h($_POST['c_name']);
	$email			=	h($_POST['email']);
	$re_email		=	h($_POST['re_email']);
	$tel			=	h($_POST['tel']);
	$about			=	h($_POST['about']);
	$memo			=	h($_POST['memo']);


	//お名前未入力
	if ($name == "") {
		$errn_count++;
		$err_count++;
	}

	//会社名未入力
	if ($c_name == "") {
		$errca_count++;
		$err_count++;
	}

	//TEL未入力
	if ($tel == "") {
		$errtel_count++;
		$err_count++;
	}

	//メールアドレス未入力
	if ($email == "") {
		$erre_count++;
		$err_count++;
	}

	//確認用メールアドレス未入力
	if ($re_email == "") {
		$errer_count++;
		$err_count++;
	}

	//メールアドレス確認用チェック
	if ($email != $re_email) {
		$erree_count++;
		$err_count++;
	}

	//お問い合わせ種別未選択
	if ($about == "") {
		$errabout_count++;
		$err_count++;
	}

	//お問い合わせ内容未入力
	if ($memo == "") {
		$errmemo_count++;
		$err_count++;
	}

	//エラーがない場合は、確認画面へ
	if ($err_count == "0") {

		$f = 1;
	}
}

// 戻るボタンが押された場合、一覧に戻る //
if ($_GET['f'] == '3') {

	// エスケープ処理
	$name			=	$_POST['name'];
	$c_name			=	$_POST['c_name'];
	$email			=	$_POST['email'];
	$about			=	$_POST['about'];
	$memo			=	$_POST['memo'];
	$tel			=	$_POST['tel'];


	$f = '';
}

// 確認画面→完了画面） // 
if (($_GET['f'] == '2')) {

	$f = 2;
}


print <<< EOF
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
<header class="header header--top js-drawer-open js-header">
    <div class="header__inner">
      <div class="header__img js-drawer-open">
        <img class="u-mobile" src="./assets/images/common/header_parts.png" alt="">
        <img class="u-desktop" src="./assets/images/common/header_parts-pc.png" alt="">
      </div>
      <div class="header__contents header__contents--top">
        <a href="../" class="header__logo--top js-drawer-open">
          <img src="../assets/images/common/logo.png" alt="株式会社デルファイレーザージャパン">
        </a>

          <div class="hamburger u-mobile js-drawer">
            <span class="drawer-menu"></span>
            <span class="drawer-menu"></span>
            <span class="drawer-menu"></span>
          </div><!-- ./hamburger u-mobile -->

          <div class="heder-pc heder-pc--top u-desktop">
            <nav class="heder-pc__nav">
              <ul class="pc-nav__lists">
                <li class="pc-nav__list"><a href="../" class="pc-nav__link">ホーム</a></li>
								<li class="pc-nav__list"><a href="../news.php" class="pc-nav__link">当社の強み</a></li>
                <li class="pc-nav__list js-drop">
                  <a href="../products-list" class="pc-nav__link pc-nav-drop">製品情報</a>
                    <div class="drop js-drop-open">
                      <div class="drop__inner">
                        <div class="drop--red"></div>
                        <div class="drop__head">
                          <p class="drop__title--en">Products</p>
                          <p class="drop__title">製品情報</p>
                        </div>
                        <ul class="drop__lists">
                          <li class="drop__list">
                            <a href="../products-list/product01.php" class="drop__link">
                              <div class="drop-card__img">
                                <img src="../assets/images/common/product01.png" alt="">
                              </div>
                              <p class="drop-card__title">微細加工関連レーザーシステム</p>
                            </a>
                          </li>
                          <li class="drop__list">
                            <a href="../products-list/product02.php" class="drop__link">
                              <div class="drop-card__img">
                                <img src="../assets/images/common/product02.png" alt="">
                              </div>
                              <p class="drop-card__title">ディスプレイ関連レーザーシステム</p>
                            </a>
                          </li>
                          <li class="drop__list">
                            <a href="../products-list/product03.php" class="drop__link">
                              <div class="drop-card__img">
                                <img src="../assets/images/common/product03.png" alt="">
                              </div>
                              <p class="drop-card__title">半導体関連レーザーシステム</p>
                            </a>
                          </li>
                          <li class="drop__list">
                            <a href="../products-list/product04.php" class="drop__link">
                              <div class="drop-card__img">
                                <img src="../assets/images/common/product04.png" alt="">
                              </div>
                              <p class="drop-card__title">レーザー発振器</p>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div><!-- /.drop -->
                </li>
                <li class="pc-nav__list"><a href="../company/" class="pc-nav__link">会社情報</a></li>
                <li class="pc-nav__list"><a href="../company/" class="pc-nav__link">導⼊までの流れ</a></li>
                <li class="pc-nav__list"><a href="../company/" class="pc-nav__link">よくある質問</a></li>
                <li class="pc-nav__list"><a href="../news.php" class="pc-nav__link">お知らせ</a></li>
                <li class="pc-nav__list"><a href="../news.php" class="pc-nav__link">採⽤情報</a></li>
                <li class="pc-nav__list"><a href="../contact-us/" class="pc-nav__link">お問い合わせ</a></li>
                <li class="pc-nav__list">
                  <div class="global">
                    <p class="global__header js-accordion">Global</p>
                    <ul class="global__lists">
						<li class="global__list"><span class="flag-icon flag-icon-de  flag-icon-squared"></span> <span class="flag-icon flag-icon-us flag-icon-squared"></span> <a href="https://www.capicard.de/en/" target="_blank" class="global__link">ドイツ本社</a></li>
						<li class="global__list"><span class="flag-icon flag-icon-hk  flag-icon-squared"></span> <span class="flag-icon flag-icon-cn  flag-icon-squared"></span> <a href="http://www.capicard.com.cn/index.php?lang=zh_cn" target="_blank" class="global__link">香港・中国</a></li>
                    </ul><!-- ./global__lists -->
                  </div>
                </li>
              </ul><!-- ./drawer-nav__lists -->
            </nav><!-- /.heder-pc__nav -->
          </div><!-- ./heder-pc u-desktop -->


      </div><!-- ./header__contents -->
    </div><!-- ./header__inner -->
  </header>

  <div class="drawer js-drawer-open">
    <div class="drawer__inner">
      <div class="drawer__logo">
        <img src="../assets/images/common/logo.png" alt="株式会社デルファイレーザージャパン">
      </div>
      <nav class="drawer-nav">
        <ul class="drawer-nav__lists">
          <li class="drawer-nav__list"><a href="../" class="drawer-nav__link">TOP</a></li>
          <li class="drawer-nav__list"><a href="../company/" class="drawer-nav__link">会社情報</a></li>
          <li class="drawer-nav__list"><a href="../products-list/" class="drawer-nav__link">製品情報</a></li>
          <li class="drawer-nav__list"><a href="../news.php" class="drawer-nav__link">News</a></li>
          <li class="drawer-nav__list"><a href="../contact-us/" class="drawer-nav__link">お問い合わせ</a></li>
          <li class="drawer-nav__list">
            <div class="global">
              <p class="global__header js-accordion">Global</p>
              <ul class="global__lists">
				<li class="global__list"><span class="flag-icon flag-icon-de  flag-icon-squared"></span> <span class="flag-icon flag-icon-us flag-icon-squared"></span> <a href="https://www.capicard.de/en/" target="_blank" class="global__link">ドイツ本社</a></li>
				<li class="global__list"><span class="flag-icon flag-icon-hk  flag-icon-squared"></span> <span class="flag-icon flag-icon-cn  flag-icon-squared"></span> <a href="http://www.capicard.com.cn/index.php?lang=zh_cn" target="_blank" class="global__link">香港・中国</a></li>
              </ul><!-- ./global__lists -->
            </div>
          </li>
        </ul><!-- ./drawer-nav__lists -->
      </nav>
    </div><!-- ./drawer__inner -->
  </div><!-- ./drawer -->

  <div class="header-margin"></div>
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
			<p class="products__title">テスト加工申請フォーム</p>
			<p class="products__title--en">Processing Form</p>


EOF;
// 初期画面
if ($f == "") {

	// 再読み込み禁止処理
	$_SESSION["execute_con"] 	= 'false';


	print <<< EOF

            <div class="topText topText_fix topText_padding" id="chk">
                お問い合わせ頂き、誠にありがとうございます。<br>
								詳細はフォーム送信後に、弊社より連絡差し上げます
            </div> <!-- /.topText -->

		    <div class="partsWrap02_a">
		        <!-- messageここから -->
		        <div class="message">
		            <div class="inner">
						<form action="../contact-us/#chk" name="post_frm" id="post_frm" method="POST">
						<input type="hidden" name="chk" value="1">
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">会社名　<span class="tagBox tag">必須</span></p>
EOF;
	if ($errca_count != 0) {
		print <<< EOF
    	<font color="#FF0000" size="2">※会社名がご入力されていません。</font><br />
EOF;
	}
	print <<< EOF

		                        	<p><input type="text" value="{$c_name}" placeholder="例：後藤商事（株）" name="c_name" id="kword"  /></p>
								</div>
							</div>
						</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">お名前　<span class="tagBox tag">必須</span></p>
EOF;
	if ($errn_count != 0) {
		print <<< EOF
    	<font color="#FF0000" size="2">※お名前がご入力されていません。</font><br />
EOF;
	}
	print <<< EOF

		                        	<p><input type="text" value="{$name}" placeholder="例：山田太郎" name="name" id="kword"  /></p>
								</div>
							</div>
						</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">メールアドレス　<span class="tagBox tag">必須</span></p>
EOF;
	if ($erre_count != 0) {
		print <<< EOF
    	<font color="#FF0000" size="2">※メールアドレスがご入力されていません。</font><br />
EOF;
	}
	print <<< EOF

		                        	<p><input type="text" value="{$email}" placeholder="sample@domain.co.jp" name="email" id="kword" /></p>
EOF;
	if ($errer_count != 0) {
		print <<< EOF
    	<font color="#FF0000" size="2">※確認用メールアドレスがご入力されていません。</font><br />
EOF;
	} else {

		if ($erree_count != 0) {
			print <<< EOF

    	<font color="#FF0000" size="2">※メールアドレスと確認用が一致していません。</font><br />
EOF;
		}
	}
	print <<< EOF
		                        	<p><input type="text" value="" placeholder="確認のため再度入力してください。" name="re_email" id="kword"  /></p>
								</div>
							</div>
						</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">お電話　<span class="tagBox tag">必須</span></p>
EOF;
	if ($errtel_count != 0) {
		print <<< EOF
    	<font color="#FF0000" size="2">※お電話がご入力されていません。</font><br />
EOF;
	}
	print <<< EOF
		                        	<p><input type="text" value="{$tel}" placeholder="例：000-000-0000" name="tel" id="kword"  /></p>
								</div>
							</div>
						</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">お問い合わせの種類　<span class="tagBox tag">必須</span></p>
EOF;
	if ($errabout_count != 0) {
		print <<< EOF
    	<font color="#FF0000" size="2">※お問い合わせの種類が選択されていません。</font><br />
EOF;
	}


	print $about;

	// 戻り値セット
	if ($about == '二軸押出機用部品') {
		$check1 = "selected";
	} elseif ($about == '多層基盤圧着機用プレスプレート他部品') {
		$check2 = "selected";
	} elseif ($about == '鋳物造型機用部品') {
		$check3 = "selected";
	} elseif ($about == '搾油機及び関連消耗部品') {
		$check4 = "selected";
	} elseif ($about == 'その他') {
		$check5 = "selected";
	} else {
		$check6 = "selected";
	}
	print <<< EOF
		                        	<p>
									<select name="about" id="about-select" >
										<option value="" {$check6}>--選択してください--</option>
										<option value="二軸押出機用部品" {$check1}>二軸押出機用部品</option>
										<option value="多層基盤圧着機用プレスプレート他部品" {$check2}>多層基盤圧着機用プレスプレート他部品</option>
										<option value="鋳物造型機用部品" {$check3}>鋳物造型機用部品</option>
										<option value="搾油機及び関連消耗部品" {$check4}>搾油機及び関連消耗部品</option>
										<option value="その他" {$check5}>その他</option>
									</select>
		                        	</p>
								</div>
							</div>
						</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">ご相談・お問い合わせ　<span class="tagBox tag">必須</span></p>
EOF;
	if ($errmemo_count != 0) {
		print <<< EOF
    	<font color="#FF0000" size="2">※ご相談・お問い合わせがご入力されていません。</font><br />
EOF;
	}
	print <<< EOF
		                        	<p><textarea id="kword" name="memo" placeholder="※ご相談内容やお問い合わせ内容についてご記入ください。" >{$memo}</textarea></p>
								</div>
							</div>
						</div>
				        <div class="topTitleWrap">
				            <div class="topText">
				                <i class="fa-solid fa-arrow-up-right-from-square"></i> <a href="../privacy.php">プライバシーポリシー</a>を必ずご覧いただき、同意される場合はお進みください。
				            </div> <!-- /.topText -->
				        </div> <!-- /.topTitleWrap -->

						<div class="contact_linkWrap">


						<input type="submit" value="確認画面に進む" class="subb">

						

						</div>
						</form>
					</div>
				</div>
			</div>

EOF;
	// 確認画面
} elseif ($f == "1") {
	print <<< EOF


            <div class="topText topText_fix topText_padding">
                以下の内容で送信します、内容をご確認の上「送信する」ボタンを押してください。<br>
                なお、入力内容が異なっている場合は、「戻る」ボタンを押し、入力し直してください。
            </div> <!-- /.topText -->

		    <div class="partsWrap02_a">
		        <!-- messageここから -->
		        <div class="message">
		            <div class="inner">
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">会社名</p>
		                        	<p>{$c_name}</p>
								</div>
							</div>
						</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">お名前</p>
		                        	<p>{$name}</p>
								</div>
							</div>
						</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">メールアドレス</p>
		                        	<p>{$email}</p>
								</div>
							</div>
						</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">お電話</p>
		                        	<p>{$tel}</p>
								</div>
							</div>
						</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">お問い合わせの種類</p>
		                        	<p>
										{$about}
		                        	</p>
								</div>
							</div>
						</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">ご相談・お問い合わせ</p>

EOF;
	// 改行処理 //
	$memo2 = nl2br($memo);
	print <<< EOF

		                        	<p>
		                        		{$memo2}
		                        	</p>
								</div>
							</div>
						</div>

						<div class="contact_linkWrap flex pt50">


							<form id="contact" name="contact-form" method="post" action="../contact-us/?f=3">
							<input type="submit" value="戻る" class="subb2">
							<input type="hidden" name="name" value="{$name}" />
							<input type="hidden" name="c_name" value="{$c_name}" />
							<input type="hidden" name="email" value="{$email}" />
							<input type="hidden" name="about" value="{$about}" />
							<input type="hidden" name="tel" value="{$tel}" />
							<input type="hidden" name="memo" value="{$memo}" />
							</form>


							<form id="contact" name="contact-form" method="post" action="../contact-us/?f=2">
							
							<input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
 							
							<input type="submit" value="送信する" class="subb">
							<input type="hidden" name="name" value="{$name}" />
							<input type="hidden" name="c_name" value="{$c_name}" />
							<input type="hidden" name="email" value="{$email}" />
							<input type="hidden" name="about" value="{$about}" />
							<input type="hidden" name="tel" value="{$tel}" />
							<input type="hidden" name="memo" value="{$memo}" />
							</form>
						</div>
					</div>
				</div>
			</div>



EOF;
?>


<script src="https://www.google.com/recaptcha/api.js?render=6LdHDZEhAAAAAGsbfuohRi3mNxOqCpY-6SCglcQP"></script> 
<script>
	grecaptcha.ready(function() {
	grecaptcha.execute('6LdHDZEhAAAAAGsbfuohRi3mNxOqCpY-6SCglcQP', {action: 'homepage'}).then(function(token) {
		var recaptchaResponse = document.getElementById('g-recaptcha-response');
		recaptchaResponse.value = token;
		});
	});
</script>


<?php
} elseif ($f == '2') {

	// reCAPTCHA

	$secretKey =  '6LeAnXQgAAAAAM9Fs2N3V4XPdXP9GQyxodlIoPoZ';
	$captchaResponse = $_POST['g-recaptcha-response'];

	// APIリクエスト
	$verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LdHDZEhAAAAAK6mwduq78jLtuoQCSfXsuAazCUX&response={$captchaResponse}");

	// APIレスポンス確認
	$responseData = json_decode($verifyResponse);
	if ($responseData->success) {
	} else {
		header('Location: ../contact-us/');
		exit();
	}

	// reCAPTCHA

	// エスケープ処理
	$name			=	h($_POST['name']);
	$c_name			=	h($_POST['c_name']);
	$email			=	h($_POST['email']);
	$about			=	h($_POST['about']);
	$tel			=	h($_POST['tel']);
	$memo			=	h($_POST['memo']);


	$aaa = $_SESSION['execute_con'];


	// 再読み込みによる増加禁止
	if ($aaa == 'false') {

		//メールを送る
		require("../send_con.php");
		$_SESSION['execute_con'] = 'true';
	}

	print <<< EOF

            <div class="topText topText_fix topText_padding">
                お問い合わせ頂き、誠にありがとうございました。<br />折り返し、担当者の方からご連絡致します。
            </div> <!-- /.topText -->


EOF;
}
print <<< EOF


		</div>
	</section><!-- /.products -->

	<section class="contact">
		<div class="contact__inner">
			<div class="contact__red"></div>
			<div class="contact__contents">
				<p class="contact__lead">お電話またはフォームから<br class="u-mobile">お問い合わせください。</p>
        <p class="contact__lead" style="margin-top:10px;">株式会社デルファイレーザージャパン</p>
				<div class="contact__company">
					<div class="contact-company__ca">
						<p class="contact-company__name">川口本社オフィス</p>
						<p class="contact-company__tel"><a href="tel:048-263-5017">048-263-5017</a></p>
						<p class="contact-company__time">平日 9:00～17:00</p>
						<p class="contact-company__address">〒333-0844 埼玉県川口市
						<br>上青木2-42-6</p>
						<a href="https://goo.gl/maps/N3QXM1oqBS9qJAvSA" class="contact-company__map" target="_blank">Google map</a>
					</div><!-- ./contact-company__ca -->
					<div class="contact-company__kobe">
						<p class="contact-company__name">神戸オフィス</p>
						<p class="contact-company__tel"><a href="tel:078-862-3736">078-862-3736</a></p>
						<p class="contact-company__time">平日 9:00～17:00</p>
						<p class="contact-company__address">〒657-0028 兵庫県神戸市<br class="u-mobile">灘区森後町1-3-19
						<br>リトルブラザーズ六甲ビル5F-D</p>
						<a href="https://goo.gl/maps/VGY9cSXtUMHwHnAA7" class="contact-company__map" target="_blank">Google map</a>
					</div><!-- ./contact-company__kobe -->
				</div><!-- ./contact__company -->

				<div class="contact__btn u-desktop">
					<a href="../contact-us/" class="btn btn--contact">お問い合わせはこちら</a>
				</div>
			</div><!-- ./contact__contents -->

			<div class="contact__btn u-mobile">
				<a href="../contact-us/" class="btn btn--contact">お問い合わせはこちら</a>
			</div>
		</div><!-- /.contact__inner -->
	</section><!-- /.contact -->

EOF;
require("../include/footer.php");
print <<< EOF



</body>
</html>
EOF;
