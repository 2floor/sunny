<?php


/**
 * htmlspecialchars
 * @param $value
 * @return array|string
 */
function hsc($value)
{
    if (is_array($value)) {
        $res = array();
        for ($i=0; $i<count($value); $i++) {
            $res[$i] = hsc($value[$i]);
        }
        return $res;
    } else {
        return htmlspecialchars($value);
    }

}


class registMail{

    private $common_logic;
    private $from;
    private $header;
    private $header_adminer;
    private $header_mini;
    private $footer;

    public function __construct($post){
        $this->common_logic = new common_logic();
        $this->from = 'info@cclue.co.jp';
        $this->header_mini ='
/----------------------------------------------/
こちらは自動配信メールになります。
メールを送られても返信されることはありませんのでご注意ください。
お問い合わせはこちらからお願いします。
https://cclue.co.jp/contact.php
/----------------------------------------------/
';
        $this->header ='
/----------------------------------------------/
こちらは自動配信メールになります。
メールを送られても返信されることはありませんのでご注意ください。
お問い合わせはこちらからお願いします。
https://cclue.co.jp/contact.php
/----------------------------------------------/

'.$post['name'].'様

お問い合わせいただきまして、誠にありがとうございました。
ご返信までしばらくお待ちくださいませ。

以下、お問い合わせ内容

';

        $this->header_adminer ='
/----------------------------------------------/
こちらは自動配信メールになります。
メールを送られても返信されることはありませんのでご注意ください。
お問い合わせはこちらからお願いします。
https://cclue.co.jp/contact.php
/----------------------------------------------/

'.$post['name'].'様より
お問い合わせがありました。
ご対応よろしくお願いいたします。

以下、お問い合わせ内容

';

        $this->footer ='
/----------------------------------------------/
cclue
住所　　：　〒331-0000　
HP　　　：　https://cclue.co.jp/
Mail　　：　'.$this->from.'
/----------------------------------------------/
';
    }


    /**
     * ふりわけ
     * @param unknown $post
     */
    public function ct($post){
        $this->contact($post);
        header('Location: ./contact_comp.php');
        exit();
    }

    /**
     * お問い合わせ
     * @param unknown $post
     */
    private  function contact($post){

        $mail = $post['mail'];
        $subject = '【cclue】WEBからのお問い合わせを受け付けました' ;
        $subject_adminer = '【cclue】WEBからのお問い合わせがありました' ;

        $addr= '〒'.$post['zip'].' '.$post['pref_name'].' '.$post['addr'].'';
        if($post['zip'] == null || $post['zip'] == '' ){
            $addr= '記載無し';
        }

        $img = '添付画像無し';
        if($post['img'] != null || $post['img'] != '' ){
            $img = '添付画像あり';
        }


        $body = $this->header.'

お名前　　　　　　　：　'.$post['name'].'
メールアドレス　　　：　'.$post['mail'].'
ご住所　　　　　　　：　'.$addr.'
添付画像　　　　　　：　'.$img.'
お問い合わせ内容　　：

'.$post['detail'].'


'.$this->footer;

        $body_adminer = $this->header_adminer.'

お名前　　　　　　　：　'.$post['name'].'
メールアドレス　　　：　'.$post['mail'].'
ご住所　　　　　　　：　'.$addr.'
添付画像　　　　　　：　'.$img.'
お問い合わせ内容　　：

'.$post['detail'].'


'.$this->footer;

        if ($_SERVER['HTTP_HOST']=='localhost') {

            echo '<pre>'.'</pre><br>';
            echo '<pre>'.'</pre><br>';
            echo '<pre>'.'</pre><br>';
            echo '<pre>'.'</pre><br>';
            echo '<pre>'.'</pre><br>';

            exit;
        } else {
            $this->common_logic->mail_send($mail, $subject, $body, $this->from);
            $this->common_logic->mail_send($this->from, $subject_adminer, $body_adminer, $this->from);

        }


    }

}