<?php
session_start();
require_once '../common/common_logic.php';
// defined('SITE_URL') or define('SITE_URL', 'http://xxx.com');
defined('SITE_URL') or define('SITE_URL', 'https://2floor.xyz/assort');

$common_logic = new common_logic();


$mail = trim($_POST['email']);
if ($mail == null || $mail == '') {
	header("Location: ../../reminder.php?msg=n");
	exit();
}


$member = $common_logic->select_logic("select * from t_member where `mail` = ?  ", array($mail));





if ($member != null && $member != '') {

	mb_language("Japanese");
	mb_internal_encoding("UTF-8");


	$base = 'passchange##' . $mail . '##' . ceil(microtime(true));
	$enc = strrev(base64_encode($base));
	$url = SITE_URL . '/change_pass.php?en=' . urlencode($enc);
	$name = $member[0]['name'];
	$company = 'cclue';

	// お客様宛てに問い合わせ送信 ////////////////////////////////////////////

	$body = preg_replace(array('/##name##/', '/##url##/'), array($name, $url), reminder_template::getTemplate());
	$footer = reminder_template::getFooter();

	// メールの送信（お客様向け向け）
	$subject = "【LOGIFILL】パスワード再設定に関するご連絡です";
	if ($_SERVER['HTTP_HOST'] == 'localhost') {
		//
		//		echo ($body);
		//		exit;
	} else {
		$common_logic->mail_send($mail, $subject, $body . $footer, "tech@2floor.jp");
	}


	header("Location: ../../reminder.php?msg=k");
	exit();
} else {
	header("Location: ../../reminder.php?msg=x");
	exit();
}


class reminder_template
{
	static function getTemplate()
	{
		$template = '―――――――――――――――――――――――――――――――――――
  このメッセージは LOGIFILL より自動送信されています。
  心当たりのない場合は、お問い合わせメールinfo@logifill.co.jp よりご連絡ください。
―――――――――――――――――――――――――――――――――――

##name##　様
　　　　
いつもご利用頂き、誠にありがとうございます。
以下のURLよりパスワードの再設定をお願いします。
*******************************************************************

##url##

*******************************************************************

';
		return $template;
	}
	static function getFooter()
	{
		$footer = '
今後とも LOGIFILLを宜しくお願いします。

================================================================
株式会社LOGI FILL-ロジフィル
住所　　：　〒253-0044
　　　　　　神奈川県茅ヶ崎市新栄町7-5Chigasaki Biz-naz3F
HP　　　：　https://logifill.com/
Mail　　：　info@logifill.jp
================================================================';
		return $footer;
	}
}
