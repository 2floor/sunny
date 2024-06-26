<?php
date_default_timezone_set('Asia/Tokyo');
//ini_set('display_errors', "On"); 
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
$errtel_count 		= 0;
$errmateria_count 	= 0;
$errthickness_count 	= 0;
$errprocessing_details_count 	= 0;

// フラグ系  // 
$f = (isset($_GET['f'])) ? $_GET['f'] : '';

$processing_details = '';


// 入力チェック（登録画面→確認画面前） // 
if ($_POST['chk'] == "1") {
//var_dump($_FILES['file']['name']);
//var_dump($_POST);
//exit();


if (is_uploaded_file($_FILES["file"]["tmp_name"])) {
  if (move_uploaded_file($_FILES["file"]["tmp_name"], "../upload_files/processing/" . $_FILES["file"]["name"])) {
    chmod("../upload_files/processing/" . $_FILES["file"]["name"], 0644);
  } 
}

	// エスケープ処理
	$name			=	h($_POST['name']);
	$c_name			=	h($_POST['c_name']);
	$email			=	h($_POST['email']);
	$re_email		=	h($_POST['re_email']);
	$tel			=	h($_POST['tel']);
	$purpose		=	h($_POST['purpose']);
	$materia		=	h($_POST['materia']);
	$thickness		=	h($_POST['thickness']);
	$quality		=	h($_POST['quality']);
	$processing_details	=	h($_POST['processing_details']);
	$file			=	$_FILES['file']['name']; //$_POST['file'];
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

	//素材未入力
	if ($materia == "") {
		$errmateria_count++;
		$err_count++;
	}

	//厚み未入力
	if ($thickness == "") {
		$errthickness_count++;
		$err_count++;
	}

	//加工内容未選択
	if ($processing_details == "") {
		$errprocessing_details_count++;
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
	$purpose		=	$_POST['purpose'];
	$materia		=	$_POST['materia'];
	$thickness		=	$_POST['thickness'];
	$quality		=	$_POST['quality'];
	$processing_details	=	$_POST['processing_details'];
	$file			=	$_POST['file'];
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
  <title>テスト加工申請フォーム | 株式会社デルファイレーザージャパン</title>
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
<header class="header header--top js-drawer-open js-header">
  <div class="header__inner">
    <div class="header__img js-drawer-open">
      <img class="u-mobile" src="../assets/images/common/header_parts.png" alt="">
      <img class="u-desktop" src="../assets/images/common/header_parts-pc.png" alt="">
    </div>
    <div class="header__contents header__contents--top">
      <a href="../" class="header__logo--top js-drawer-open">
        <img src="../assets/images/common/logo.png" alt="株式会社デルファイレーザージャパン">
      </a>

      <div class="hamburger u-mobile js-drawer">
        <span class="drawer-menu"></span>
        <span class="drawer-menu"></span>
        <span class="drawer-menu"></span>
      </div><!-- ../hamburger u-mobile -->

      <div class="heder-pc heder-pc--top u-desktop">
        <nav class="heder-pc__nav">
          <ul class="pc-nav__lists">
            <li class="pc-nav__list"><a href="../" class="pc-nav__link">ホーム</a></li>
            <li class="pc-nav__list js-drop"><a href="../products-list/" class="pc-nav__link pc-nav-drop">製品情報</a></li>
            <li class="pc-nav__list"><a href="../laser/" class="pc-nav__link">レーザー加工サービス</a></li>
            <li class="pc-nav__list"><a href="../flow.php" class="pc-nav__link">導⼊までの流れ</a></li>
            <li class="pc-nav__list"><a href="../document.php" class="pc-nav__link">技術資料</a></li>
            <li class="pc-nav__list js-drop"><a href="./company/reasons.php" class="pc-nav__link pc-nav-drop">会社情報</a>
              <div class="drop js-drop-open">
                <div class="drop__inner">
                  <div class="drop--red"></div>
                  <div class="drop__head">
                    <p class="drop__title--en">Company</p>
                    <p class="drop__title">会社情報</p>
                  </div>
                  <ul class="drop__lists">
                    <li class="drop__list">
                      <a href="../company/reasons.php" class="drop__link">
                        <div class="drop-card__img">
                          <img src="../assets/images/common/product01.png" alt="">
                        </div>
                        <p class="drop-card__title">当社の強み</p>
                      </a>
                    </li>
                    <li class="drop__list">
                      <a href="../company/" class="drop__link">
                        <div class="drop-card__img">
                          <img src="../assets/images/common/product02.png" alt="">
                        </div>
                        <p class="drop-card__title">会社概要</p>
                      </a>
                    </li>
                  </ul>
                </div>
              </div><!-- /.drop -->
            </li>
            <li class="pc-nav__list"><a href="../faq.php" class="pc-nav__link">よくある質問</a></li>
            <li class="pc-nav__list"><a href="../recruit.php" class="pc-nav__link">採用情報</a></li>
            <li class="pc-nav__list"><a href="../contact-us/" class="pc-nav__link">お問い合わせ</a></li>
            <li class="pc-nav__list">
              <div class="global">
                <a href="http://en.delphilaser.com/" target="_blank" class="global__header js-accordion">Global</a>
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
          <li class="drawer-nav__list"><a href="./company/reasons.php" class="drawer-nav__link">会社情報</a></li>
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
				<p class="page-title">テスト加工申請フォーム</p>
				<span class="page-title--en">Processing Form</span>
			</div>
		</div>
	</div><!-- /.page-titles -->

	<p class="pan"><i class="fa-solid fa-house"></i>　|　テスト加工申請フォーム</p>


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
						<form action="processing-form.php#chk" name="post_frm" id="post_frm" method="POST" enctype="multipart/form-data">
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

	// 戻り値セット
	$check1 = $check2 = $check3 = '';
	if ($purpose == 'テスト加工') {
		$check1 = "selected";
	} elseif ($purpose == '受託加工') {
		$check2 = "selected";
	}elseif($purpose == '') {
		$check3 = "selected";
	}

	print <<< EOF
		                        	<p><input type="text" value="{$tel}" placeholder="例：000-000-0000" name="tel" id="kword"  /></p>
								</div>
							</div>
						</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">目的
		                        	<p>
									<select name="purpose" id="about-select" >
										<option value=""{$check3}>--選択してください--</option>
										<option value="テスト加工"{$check1}>テスト加工</option>
										<option value="受託加工"{$check2}>受託加工</option>
									</select>
		                        	</p>
								</div>
							</div>
						</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">素材　<span class="tagBox tag">必須</span></p>
EOF;
	if ($errmateria_count != 0) {
		print <<< EOF
    	<font color="#FF0000" size="2">※素材がご入力されていません。</font><br />
EOF;
	}
	print <<< EOF

		                        	<p><input type="text" value="{$materia}" placeholder="" name="materia" id="kword"  /></p>
								</div>
							</div>
						</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">厚み　<span class="tagBox tag">必須</span></p>
EOF;
	if ($errthickness_count != 0) {
		print <<< EOF
    	<font color="#FF0000" size="2">※厚みがご入力されていません。</font><br />
EOF;
	}
	print <<< EOF

		                        	<p><input type="text" value="{$thickness}" placeholder="" name="thickness" id="kword"  /></p>
								</div>
							</div>
						</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">品質要求</p>
		                        	<p><textarea id="kword" name="quality" placeholder="※加工幅や深さなど規定があればご記入ください。" >{$quality}</textarea></p>
					</div>
				    </div>
				</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">加工内容　<span class="tagBox tag">必須</span></p>
EOF;
	if ($errprocessing_details_count != 0) {
		print <<< EOF
    	<font color="#FF0000" size="2">※加工内容が選択されていません。</font><br />
EOF;
	}



	// 戻り値セット
$pd_check1 = $pd_check2 = $pd_check3 = $pd_check4 = $pd_check5 = $pd_check6 = $pd_check7 = $pd_check8 = '';
	if ($processing_details == 'マーキング') {
		$pd_check1 = "selected";
	} elseif ($processing_details == 'エッチング') {
		$pd_check2 = "selected";
	} elseif ($processing_details == 'スクライブ') {
		$pd_check3 = "selected";
	} elseif ($processing_details == 'ハーフカット') {
		$pd_check4 = "selected";
	} elseif ($processing_details == 'フルカット') {
		$pd_check5 = "selected";
	} elseif ($processing_details == '穴あけ') {
		$pd_check6 = "selected";
	} elseif ($processing_details == 'その他') {
		$pd_check7 = "selected";
	} else {
		$pd_check8 = "selected";
	}
	print <<< EOF
		                        	<p>
									<select name="processing_details" id="about-select" >
										<option value="" {$pd_check8}>--選択してください--</option>
										<option value="マーキング" {$pd_check1}>マーキング</option>
										<option value="エッチング" {$pd_check2}>エッチング</option>
										<option value="スクライブ" {$pd_check3}>スクライブ</option>
										<option value="ハーフカット" {$pd_check4}>ハーフカット</option>
										<option value="フルカット" {$pd_check5}>フルカット</option>
										<option value="穴あけ" {$pd_check6}>穴あけ</option>
										<option value="その他" {$pd_check7}>その他</option>
									</select>
		                        	</p>
								</div>
							</div>
						</div>

		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">資料添付</p>
		                        	<p><input type="file" name="file"></p>
								</div>
							</div>
						</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">備考欄</p>
		                        	<p><textarea id="kword" name="memo" placeholder="※興味のあるレーザーの種類や、スケジュール感などご記入ください。" >{$memo}</textarea></p>
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
		                        	<p class="bold">目的</p>
		                        	<p>{$purpose}</p>
					</div>
				    </div>
				</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">素材</p>
		                        	<p>{$materia}</p>
					</div>
				    </div>
				</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">厚み</p>
		                        	<p>{$thickness}</p>
					</div>
				    </div>
				</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">品質要求</p>
		                        	<p>{$quality}</p>
					</div>
				    </div>
				</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">加工内容</p>
		                        	<p>{$processing_details}</p>
					</div>
				    </div>
				</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">資料添付</p>
		                        	<p>{$file}</p>
					</div>
				    </div>
				</div>
		                <div class="messageInner">
		                    <div class="message_textBox2_2">
		                        <div class="message_text2">
		                        	<p class="bold">備考欄</p>

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


							<form id="contact" name="contact-form" method="post" action="processing-form.php?f=3" enctype="multipart/form-data">
							<input type="submit" value="戻る" class="subb2">
							<input type="hidden" name="name" value="{$name}" />
							<input type="hidden" name="c_name" value="{$c_name}" />
							<input type="hidden" name="email" value="{$email}" />
							<input type="hidden" name="purpose" value="{$purpose}" />
							<input type="hidden" name="materia" value="{$materia}" />
							<input type="hidden" name="thickness" value="{$thickness}" />
							<input type="hidden" name="quality" value="{$quality}" />
							<input type="hidden" name="processing_details" value="{$processing_details}" />
							<input type="hidden" name="file" value="{$file}" />
							<input type="hidden" name="tel" value="{$tel}" />
							<input type="hidden" name="memo" value="{$memo}" />
							</form>


							<form id="contact" name="contact-form" method="post" action="processing-form_comp.php" enctype="multipart/form-data">
							
							<input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
 							
							<input type="submit" value="送信する" class="subb">
							<input type="hidden" name="name" value="{$name}" />
							<input type="hidden" name="c_name" value="{$c_name}" />
							<input type="hidden" name="email" value="{$email}" />
							<input type="hidden" name="purpose" value="{$purpose}" />
							<input type="hidden" name="materia" value="{$materia}" />
							<input type="hidden" name="thickness" value="{$thickness}" />
							<input type="hidden" name="quality" value="{$quality}" />
							<input type="hidden" name="processing_details" value="{$processing_details}" />
							<input type="hidden" name="file" value="{$file}" />
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
	grecaptcha.execute('6LdHDZEhAAAAAGsbfuohRi3mNxOqCpY-6SCglcQP', {action: 'homepage'}).then(function(token) {
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
		require("../send_con.php");
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

<script src="../assets/admin/js/common/plural_file_upload.js"></script>
	<input type="hidden" id="id" value="">
	<input type="hidden" id="page_type" value="">
	<input type="hidden" id="img_path1" value="processing/">
	<input type="hidden" id="img_length1" class="hid_img_length" value="1">
	<input type="hidden" id="img_type1" class="hid_img_type" value="PDF,pdf">

</body>
</html>
EOF;
