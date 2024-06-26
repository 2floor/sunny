<?php
session_start();
require_once __DIR__ . '/../../logic/common/common_logic.php';
require_once __DIR__ . '/../../common/security_common_logic.php';
require_once __DIR__ . '/../../logic/admin/member_logic.php';
require_once __DIR__ . '/../../logic/front/registMail.php';

require_once __DIR__ . '/../../claim_logic.php';


// XSSチェック、NULLバイトチェック
$security_common_logic = new security_common_logic();
$security_result = $security_common_logic->security_exection($_POST, $_REQUEST, $_COOKIE);
// セキュリティチェック後の値を再設定
$_POST = $security_result[0];
$_REQUEST = $security_result[1];
$_COOKIE = $security_result[2];

if ($_POST != null && $_POST != '') {

    $logic = new f_member_logic();
    $logic->ct($_POST, false);
} else {
}

//var_dump($_POST);
//exit;

class f_member_logic
{

    private $common_logic;
    private $member_logic;

    public function  __construct()
    {
        $this->common_logic = new common_logic();
        $this->member_logic = new member_logic();
    }

    /**
     * @param $post
     * @param bool $ajaxFlg
     * @return array
     */
    public function ct($post, $ajaxFlg = true)
    {
    
        switch ($post['method']) {
            case 'regist':
                $data = $this->registMember($post);
                if (!$ajaxFlg) {
                    header('Location: ../../member_registration_comp.php');
                    exit();
                } else {
                    return $data;
                }
                break;
            case 'info':
                $data = $this->member_logic->get_detail($post['member_id']);

                return $data;
                break;
            case 'update':
                $data = $this->updateMember($post);
                if (!$ajaxFlg) {
                    header('Location: ../../member_info.php?comp=1');
                    exit();
                } else {
                    return $data;
                }
                break;
            case 'kouza_chg':
                //処理をかけTODO
                $data = $this->bank_info_cng($post);
                if (!$ajaxFlg) {
                    header('Location: ../../bank_info.php?comp=1');
                    exit();
                } else {
                    return $data;
                }
                break;
            case 'plan_change':
                //処理をかけTODO
                $data = $this->plan_change($post);
                if (!$ajaxFlg) {
                    header('Location: ../../usage_list.php?comp=1');
                    exit();
                } else {
                    return $data;
                }
                break;
            default:

                //                var_dump($_POST);
        }
    }


    /**
     * 新規登録
     * @param $post
     * @return array
     */
    private function registMember($post)
    {

    

        $post['password'] = $this->common_logic->convert_password_encode($post['password']);


        $zip = $post['c_zip1'] . '-' . $post['c_zip2'];
        // $truck_num = (is_numeric($post['truck_num'])) ? $post['truck_num'] : 0;

        $j_imp = "";
        if ($post['jigyou'] != null && $post['jigyou'] != "") {
            $j_imp = implode(',', $post['jigyou']);
        }

        // 登録ロジック呼び出し
        $this->member_logic->entry_new_data(array(
            $post['name'],
            $post['name_kana'],
            $post['office_name'],
            $post['office_name_kana'],
            $zip,
            $post['pref'],
            $post['addr'],
            $post['tel'],
            $post['tel2'],
            $post['fax'],
            $post['resp_name'],
            $post['job'],
            $post['mail'],
            $post['password'],
            $post['sending_way'],
            $j_imp,
            $post['truck'],
            $post['url'],
            0, //$post['questionnaire'],
            $post['disp_date'],
            $post['plan'],
            $post['s_code'],
            $post['etc4'],
            $post['etc5'],
            $post['etc6'],
            $post['etc7'],
            $post['etc8'],
            '0',
        ));


        //請求ロボ請求先登録
        $claim_logic = new claim_logic();
        $result = $claim_logic->entry($post);




        if ($post['plan'] != 0) {
            $mem_sel = $this->common_logic->select_logic("SELECT * FROM t_member ORDER BY member_id DESC LIMIT 1", array())[0];

            $mail = $post['mail'];
            if ($post['sending_way'] == 0) {
                $mail = $post['sending_add'];

                //クレカの場合のみ
                $result = $claim_logic->entry_card($result[1], $post['tkn']);
            }

            $tel = $post['tel'];
            if ($post['invoice_tel'] == 1) {
                $tel = $post['invoice_tel_other'];
            }

            //口座情報インサ
            $this->common_logic->insert_logic(
                't_member_bank',
                array(
                    $mem_sel["member_id"],
                    $post['bank_name'],
                    $post['bank_code'],
                    $post['branch_name'],
                    $post['branch_code'],
                    $post['deposit_kind'],
                    $post['b_a_number'],
                    $post['b_a_name'],
                    $post['sending_way'],
                    $mail,
                    $tel,
                    $post['etc1'],
                    $post['etc2'],
                    $post['etc3'],
                    $post['etc4'],
                    $post['etc5'],
                    $post['etc6'],
                    $post['etc7'],
                    $post['etc8'],
                    '0',
                )
            );
        }


        $from = "info@logifill.jp";
        $to = $post['mail'];
        $subject = "【LOGI FILL】会員登録完了のお知らせ";
        $body = "―――――――――――――――――――――――――――――――――――
  このメッセージは LOGI FILL より自動送信されています。
  心当たりのない場合は、お問い合わせメールinfo@logifill.jp よりご連絡ください。
―――――――――――――――――――――――――――――――――――

" . $post['name'] . "　様

いつもご利用頂き、誠にありがとうございます。
LOGI FILLへの登録申請を受付いたしました。

認証が完了するまで今しばらくお待ちくださいませ。
今後ともLOGI FILLをよろしくお願いいたします。


================================================================
株式会社LOGI FILL-ロジフィル
住所：　〒253-0044 
　　　　神奈川県茅ヶ崎市新栄町7-5Chigasaki Biz-naz3F
HP　：　https://logifill.jp
Mail：　info@logifill.jp
================================================================

";


$to = "seidou2floor@gma.jp";
$subject = "TEST MAIL";
$message = "Hello!\r\nThis is TEST MAIL.";
$headers = "From: from@samurai.jp";
 
$res = mail($to, $subject, $message, $headers);

var_dump($res);

print phpinfo();

mb_language("ja");
mb_internal_encoding("utf-8");
$to="seidou@2floor.jp";
$subject="お問い合わせ";
$msg="メッセージが入ります。";
$from = "info@logifill.jp";
$header="From: {$from}\nReply-To: {$from}\nContent-Type: text/plain;";
if(mb_send_mail($to,$subject,$msg,$header)){
var_dump( "メールが送信されました。");
} else {
var_dump( "メールが送信できませんでした。");
}

        $this->common_logic->mail_send($to, $subject, $body, $from);

        $subject = "【LOGI FILL】会員登録がありました";
        $body = "―――――――――――――――――――――――――――――――――――
  このメッセージは LOGI FILL より自動送信されています。
  心当たりのない場合は、お問い合わせメールinfo@logifill.jp よりご連絡ください。
―――――――――――――――――――――――――――――――――――

" . $post['name'] . "　様より登録がありました。
認証の処理をお願い致します。

================================================================
株式会社LOGI FILL-ロジフィル
住所：　〒253-0044 
　　　　神奈川県茅ヶ崎市新栄町7-5Chigasaki Biz-naz3F
HP　：　https://logifill.jp
Mail：　info@logifill.jp
================================================================

";
        $this->common_logic->mail_send($from, $subject, $body, $from);




        // AJAX返却用データ成型
        $data = array(
            'status' => true,
            'method' => 'entry',
            'msg' => '登録しました'
        );

        return $data;
    }

    /**
     * 会員情報編集
     * @param $post
     * @return bool
     */
    private function updateMember($post)
    {
        if ($post['password'] != null && $post['password'] != '') {
            $passchg = $this->common_logic->convert_password_encode($post['password']);
        } else {
            $passchg = $this->common_logic->select_logic("SELECT * FROM t_member WHERE member_id = ? ", array($post['member_id']))[0]["password"];
        }

        $zip = $post['c_zip1'] . '-' . $post['c_zip2'];
        $j_imp = implode(',', $post['jigyou']);

        // var_dump($post['jigyou']);
        // exit;

        $data = array(
            $post['name'],
            $post['name_kana'],
            $zip,
            $post['pref'],
            $post['addr'],
            $post['tel'],
            $post['tel2'],
            $post['fax'],
            $post['resp_name'],
            $post['job'],
            $post['mail'],
            $passchg,
            $post['sending_way'],
            $j_imp,
            (int)$post['truck'],
            $post['URL'],
            $post['etc4'],
            $post['etc5'],
            $post['etc6'],
            $post['etc7'],
            $post['etc8'],
            $post['member_id']
        );

        $res = $this->common_logic->update_logic("t_member", " where member_id = ?", array(
            'name',
            'name_kana',
            'zip',
            'pref',
            'addr',
            'tel',
            'tel2',
            'fax',
            'resp_name',
            'job',
            'mail',
            'password',
            'payment',
            'jigyou',
            'truck_num',
            'url',
            'etc4',
            'etc5',
            'etc6',
            'etc7',
            'etc8',
        ), $data);

        // var_dump($res);
        // exit;

        foreach ($_SESSION['logifill']['login'] as $key => $item) {
            if (isset($post[$key])) {
                if (trim($item) != trim($post[$key])) {
                    $_SESSION['logifill']['login'][$key] = $post[$key];
                }
            }
        }

        return $res;
    }

    private function  bank_info_cng($post)
    {
        $bank_chk = $this->common_logic->select_logic("SELECT * FROM t_member_bank WHERE member_id = ? ", array($post['member_id']))[0];
        $mem_chk = $this->common_logic->select_logic("SELECT * FROM t_member WHERE member_id = ? ", array($post['member_id']))[0];
        $tel = $mem_chk["tel"];
        if ($post['invoice_tel_chk'] == 1) {
            $tel = $post['invoice_tel'];
        }
        if ($bank_chk == "" || $bank_chk == null) {
            //口座情報なかったらインサ
            $res = $this->common_logic->insert_logic(
                't_member_bank',
                array(
                    $post['member_id'],
                    $post['bank_name'],
                    $post['bank_code'],
                    $post['branch_name'],
                    $post['branch_code'],
                    $post['deposit_kind'],
                    $post['b_a_number'],
                    $post['b_a_name'],
                    $post['sending_way'],
                    $post['sending_add'],
                    $tel,
                    $post['etc1'],
                    $post['etc2'],
                    $post['etc3'],
                    $post['etc4'],
                    $post['etc5'],
                    $post['etc6'],
                    $post['etc7'],
                    $post['etc8'],
                    '0',
                )
            );
        } else {
            //口座情報あったらアプデ
            $data = array(
                $post['bank_name'],
                $post['bank_code'],
                $post['branch_name'],
                $post['branch_code'],
                $post['deposit_kind'],
                $post['b_a_number'],
                $post['b_a_name'],
                $post['sending_way'],
                $post['sending_add'],
                $tel,
                $post['etc1'],
                $post['etc2'],
                $post['etc3'],
                $post['etc4'],
                $post['etc5'],
                $post['etc6'],
                $post['etc7'],
                $post['etc8'],
                $bank_chk['member_bank_id']
            );
            $res = $this->common_logic->update_logic("t_member_bank", " where member_bank_id = ?", array(
                'bank_name',
                'bank_code',
                'branch_name',
                'branch_code',
                'deposit_kind',
                'b_a_number',
                'b_a_name',
                'sending_way',
                'sending_add',
                'invoice_tel',
                'etc1',
                'etc2',
                'etc3',
                'etc4',
                'etc5',
                'etc6',
                'etc7',
                'etc8',
            ), $data);
        }


        return $res;
    }

    private function  plan_change($post)
    {

        $m_row = $this->common_logic->select_logic("select * from t_member where member_id = ? ", array($post["member_id"]))[0];

        $zip_ar = explode("-", $m_row['zip']);

        $post['name'] = $m_row['name'];
        $post['c_zip1'] = $zip_ar[0];
        $post['c_zip2'] = $zip_ar[1];
        $post['office_name'] = $m_row['office_name'];
        $post['pref'] = $m_row['pref'];
        $post['addr'] = $m_row['addr'];
        $post['mail'] = $m_row['mail'];

        //請求ロボ請求先登録
        $claim_logic = new claim_logic();
        $result = $claim_logic->entry($post);



        $res = $this->common_logic->update_logic("t_member", " where member_id = ?", array(
            'etc1',
            'etc2',
        ), array(date("Y-m-d"), $post["plan"], $post["member_id"]));

        $_SESSION["cclue"]["login"]["etc2"] = $post["plan"];

        return  $res;
    }

    private function  getMember()
    {

        $data = array();
        return $data;
    }

    private function registMail($post)
    {
    }
}
