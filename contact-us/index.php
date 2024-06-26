<?php
date_default_timezone_set('Asia/Tokyo');

// セッションスタート // 
session_start();

include '../required/data/header.php';

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
$f = (isset($_GET['f'])) ? $_GET['f'] : '';

$name = $c_name = $tel = $email = $re_email = $about = $memo = $check1 = $check2 = $check3 = $check4 = $check5 = $check6 = '';
// 入力チェック（登録画面→確認画面前） // 
if(isset($_POST['chk'])){
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
}

// 戻るボタンが押された場合、一覧に戻る //
if ($f == '3') {

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
if ($f == '2') {
	//$f = 2;
  
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
	if ($about == '製品・サービスに関して') {
		$check1 = "selected";
	} elseif ($about == '資料請求に関して') {
		$check2 = "selected";
	} elseif ($about == '協業について') {
		$check3 = "selected";
	} elseif ($about == '採用について') {
		$check4 = "selected";
	} elseif ($about == '取材に関して') {
		$check5 = "selected";
	} else {
		$check6 = "selected";
	}
	print <<< EOF
		                        	<p>
									<select name="about" id="about-select" >
										<option value="" {$check6}>--選択してください--</option>
										<option value="製品・サービスに関して" {$check1}>製品・サービスに関して</option>
										<option value="資料請求に関して" {$check2}>資料請求に関して</option>
										<option value="協業について" {$check3}>協業について</option>
										<option value="採用について" {$check4}>採用について</option>
										<option value="取材に関して" {$check5}>取材に関して</option>
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


							<form id="contact" name="contact-form" method="post" action="../contact-us/contact_comp.php">
							
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

<!--
	<script src="https://www.google.com/recaptcha/api.js?render=6LdHDZEhAAAAAGsbfuohRi3mNxOqCpY-6SCglcQP"></script>
	<script>
		grecaptcha.ready(function() {
			grecaptcha.execute('6LdHDZEhAAAAAGsbfuohRi3mNxOqCpY-6SCglcQP', {
				action: 'homepage'
			}).then(function(token) {
				var recaptchaResponse = document.getElementById('g-recaptcha-response');
				recaptchaResponse.value = token;
			});
		});
	</script>
-->

<?php
} elseif ($f == '2') {

/*
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
		//require("../send_con.php");
		$_SESSION['execute_con'] = 'true';
	}

	print <<< EOF

            <div class="topText topText_fix topText_padding">
                お問い合わせ頂き、誠にありがとうございました。<br />折り返し、担当者の方からご連絡致します。
            </div> <!-- /.topText -->


EOF;
*/
}
print <<< EOF


		</div>
	</section><!-- /.products -->

EOF;
include '../required/data/footer.php';

print <<< EOF



</body>
</html>
EOF;
