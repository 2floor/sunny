<?php
require_once __DIR__ . '/../common/common_logic.php';
defined('ERR_ILLEGAL') or define('ERR_ILLEGAL', '不正な遷移です');
defined('ERR_ALREADY_MODIFIED') or define('ERR_ALREADY_MODIFIED', 'すでに変更されています');
defined('ERR_EXPIRED') or define('ERR_EXPIRED', '有効期限がきれています');


session_start();
/**
 * Created by PhpStorm.
 * User: 2f_info
 * Date: 2019/01/07
 * Time: 17:20
 */

class change_pass_logic
{
    private $logic;
    private $post;
    public $en;
    private $mail;
    private $member;
    public function __construct($en, $exec=false)
    {
        // Validation
        if ($en =='' || $en ==null ){
            header('Location: ../change_pass.php');
        }
        $this->en = $en;
        $this->logic = new common_logic();
    }

    public function change_pass(){
        $err = $this->explodeEn();
        if ($err=='') {
            $member_id = $this->member[0]['member_id'];
            $password= $this->logic->convert_password_encode($this->post['password']);
            $this->logic->update_logic('t_member','WHERE `member_id` = '.$member_id,array('password'),array($password));

            header('Location: ./change_pass.php?en='.$this->en.'&msg=ps');
        } else {
            header('Location: ./change_pass.php?en='.$this->en.'&msg=pe');
        }
    }

    public function setPost($post) {
        // Validation
        $err = '';
        unset($_POST);
        $this->post=null;

        if ($post['password'] == '' || $post['password'] == null ||
            $post['password_re'] == '' || $post['password_re'] == null
        ) {
            $err='pn';

        } else if ($post['password'] != $post['password_re']) {
            $err='pd';


        } else {
            $this->post = $post;
        }
        return $err;

    }

    public function explodeEn() {
        $err = '';
        $get_en = $this->en;
        $url = urldecode($get_en);
        $de = base64_decode(strrev($url));
        $data = explode("##", $de);

        if ($data[0] != 'passchange' || !is_numeric($data[2])) {
            $err = ERR_ILLEGAL;
        }
        $this->mail = $data[1];
        $limit_min = 30; //制限時間
        $lim = ceil(microtime(true)) - (int)$data[2];
        if ($err != '' && $lim > (60 * $limit_min)) {
            $err = ERR_EXPIRED;
        }
        if ($err == '') {
            $this->getMember();
            if ($this->member != null || $this->member != '') {
                if (strtotime($this->member[0]['update_at']) > (int)$data[2]) {
                    $err = ERR_ALREADY_MODIFIED;
                }
            } else {
                $err = ERR_ILLEGAL;
            }
        }
        return $err;
    }

    private function getMember() {

        $this->member = $this->logic->select_logic("select * from t_member where `mail` = ?  ", array($this->mail));
    }
}