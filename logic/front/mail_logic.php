<?php
session_start();
require_once __DIR__ . "/../../logic/common/common_logic.php";

class mail_logic{
	private $common_logic;

	public function __construct(){
		$this->common_logic = new common_logic();
	}


	public function mail($post){
          $c_name = $post['c_name'];
          $name = $post['name'];
          $email = $post['email'];
          $tel = $post['tel'];
          $about = $post['about'];
          $memo = $post['memo'];

	  $subject = "お問い合わせ";
	  $content = 
$c_name.'
'.$name. "様

この度はお問い合わせいただきまして、誠にありがとうございます。

担当よりご連絡いたしますので、ご返信までしばらくお待ちください。

以下お問い合わせ内容

【会社名】" . $c_name. "
【お名前】" . $name. "
【メールアドレス】" .$email. "
【お電話】" .$tel. "
【お問い合わせの種類】" .$about. "
【ご相談・お問い合わせ】" .$memo. "


※本メールは配信専用です。ご返信いただいても回答することができません。
ー―――――――――――――――――――――――――――――――――――――――――
こちらのメールに心当たりのない場合は、お問い合わせフォームよりご連絡ください。
ー―――――――――――――――――――――――――――――――――――――――――

";

	  $from = 'info@delphilaser.co.jp';
	  $mailfrom = "From:" . mb_encode_mimeheader("デルファイレーザージャパン") . "<".$from.">";

	  mb_send_mail($email, $subject, $content, $mailfrom, '-f '.$from);
	  mb_send_mail($from, $subject, $content, $mailfrom, '-f '.$from);

        }

	public function processing_mail($post){
          $c_name = $post['c_name'];
          $name = $post['name'];
          $email = $post['email'];
          $tel = $post['tel'];
          $purpose = $post['purpose'];
          $materia = $post['materia'];
          $thickness = $post['thickness'];
          $quality = $post['quality'];
          $processing_details = $post['processing_details'];
          $file = $post['file'];
          $memo = $post['memo'];

	  $subject = "テスト加工申請フォーム";

$bn = "--__BOUNDARY__\n";
$bn .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n\n";

	$content = "--__BOUNDARY__\n";
	$content .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n\n";
	$content .= 
$c_name.'
'.$name. "様

この度はテスト加工申請いただきまして、誠にありがとうございます。

担当よりご連絡いたしますので、ご返信までしばらくお待ちください。

以下お問い合わせ内容

【会社名】" . $c_name. "
【お名前】" . $name. "
【メールアドレス】" .$email. "
【お電話】" .$tel. "
【目的】" .$purpose. "
【素材】" .$materia. "
【厚み】" .$thickness. "
【品質】" .$quality. "
【加工内容】" .$processing_details. "
【備考欄】" .$memo. "


※本メールは配信専用です。ご返信いただいても回答することができません。
ー―――――――――――――――――――――――――――――――――――――――――
こちらのメールに心当たりのない場合は、お問い合わせフォームよりご連絡ください。
ー―――――――――――――――――――――――――――――――――――――――――

";

	mb_language('japanese');
	mb_internal_encoding('UTF-8');
	date_default_timezone_set('Asia/Tokyo');


$file = $post['file'];
$file_path = '';
if($file != ''){
//if( is_uploaded_file($_FILES['file']['tmp_name']) ) {
	$file_path = '../upload_files/processing/'.$file;
//	move_uploaded_file( $_FILES['file']['tmp_name'], '../../upload_files/processing/'.$file_name);

	// メールにファイルを添付
	$content .= "--__BOUNDARY__\n";
	$content .= "Content-Type: application/octet-stream; name=\"{$file}\"\n";
	$content .= "Content-Disposition: attachment; filename=\"{$file}\"\n";
	$content .= "Content-Transfer-Encoding: base64\n";
	$content .= "\n";
	$content .= chunk_split(base64_encode(file_get_contents($file_path)));
	$content .= "--__BOUNDARY__\n";
}else{
	$content .= "--__BOUNDARY__\n";
}


	  $from = 'info@delphilaser.co.jp';
          $mailfrom = '';
          $mailfrom .= "Content-Type: multipart/mixed;boundary=\"__BOUNDARY__\"\n";
          $mailfrom .= "Return-Path: " . $from . " \n";
	  $mailfrom .= "From:" . mb_encode_mimeheader("デルファイレーザージャパン") . "<".$from.">";
          $mailfrom .= "Sender: " . $from ." \n";
          $mailfrom .= "Reply-To: " . $email . " \n";

	  mb_send_mail($email, $subject, $content, $mailfrom, '-f '.$from);
	  mb_send_mail($from, $subject, $content, $mailfrom, '-f '.$from);

	if($file_path != ''){
	  unlink($file_path);
	}

     }
}

