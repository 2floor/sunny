<?php
session_start();
require_once __DIR__ . '/../../logic/common/common_logic.php';

if ($_POST != null && $_POST != '') {

	$f_btn_ct = new f_btn_ct();
	$data = $f_btn_ct->ct($_POST);

	echo json_encode(compact('data'));
} else {
	header(__DIR__ . '/../../index.php');
	exit();
}

class f_btn_ct
{

	private $common_logic;

	public function  __construct()
	{
		$this->common_logic = new common_logic();
	}

	public function ct($post)
	{
		if ($post['method'] == "agree_btn") {
			$data = $this->agree_btn($post);
		} elseif ($post['method'] == "del_item") {
			$data = $this->del_item($post);
		}

		return $data;
	}

	private function agree_btn($post)
	{
		if ($post['type'] == 'baggage') {
			$bt_type = 0;
			$target = $this->common_logic->select_logic("select * from t_baggage where baggage_id = ? ", array($post['this_id']));
			if ($target[0]['fin_flg'] != 0) {
				return array(
					'status' => false
				);
			}
			$this->common_logic->update_logic("t_baggage", " where baggage_id = ? ", array('fin_flg'), array(1, $post['this_id']));
		} elseif ($post['type'] == 'truck') {
			$bt_type = 1;
			$target = $this->common_logic->select_logic("select * from t_truck where truck_id = ? ", array($post['this_id']));


			if ($target[0]['fin_flg'] != 0) {
				return array(
					'status' => false
				);
			}
			$this->common_logic->update_logic("t_truck", " where truck_id = ? ", array('fin_flg'), array(1, $post['this_id']));
		}

		$received = $this->common_logic->select_logic("select * from t_member where member_id = ? ", array($target[0]['member_id']));

		$shipment_time = '00:00';
		if ($target[0]['shipment_time'] != '') {
			list($hour, $minute, $socond) = explode(':', $target[0]['shipment_time']);
			$hour = ($hour == '') ? '00' : $hour;
			$minute = ($minute == '') ? '00' : $minute;
			$shipment_time = $hour . ':' . $minute;
		}

		$arrival_time = '00:00';
		if ($target[0]['arrival_time'] != '') {
			list($hour, $minute, $socond) = explode(':', $target[0]['arrival_time']);
			$hour = ($hour == '') ? '00' : $hour;
			$minute = ($minute == '') ? '00' : $minute;
			$shipment_time = $hour . ':' . $minute;
			$arrival_time = $hour . ':' . $minute;
		}


		//        $_SESSION['cclue']['login']['member_id']


		mb_language("ja");
		mb_internal_encoding("UTF-8");
		$to = $received[0]['mail'];
		$title = "【LOGI FILL】商談依頼がありました";
		$body = "
" . $received[0]['name'] . "様

いつもLOGI FILLをご利用頂き、誠にありがとうございます。
商談の依頼がありましたのでLOGI FILLにログインをして商談内容を確認してください。

ログインURLはこちらから
https://2floor.xyz/assort/login.php

今後ともLOGI FILLをよろしくお願いいたします。

/----------------------------------------------/
株式会社LOGI FILL-ロジフィル
住所　　：　〒253-0044 
　　　　　　神奈川県茅ヶ崎市新栄町7-5Chigasaki Biz-naz3F
HP　　　：　https://logifill.jp
Mail　　：　info@logifill.jp
/----------------------------------------------/";

		$header = "From: LOGI FILL <noreply@logifill.jp>\n";

		mb_send_mail($to, $title, $body, $header);



		$this->common_logic->insert_logic('t_agreement', array(
			$received[0]['member_id'], // $post['received_id'],
			$_SESSION['cclue']['login']['member_id'], // $post['orderer_id'],
			$bt_type, //$post['bt_type'],
			$post['this_id'], //$post['bt_id'],
			date('Y-m-d H:i;s'), //$post['agreement_datetime'],
			0, //$post['agreement_number'],
			$target[0]['shipment_date'] . " " . $shipment_time . ":00", //$post['shippment_datetime'],
			$target[0]['shipment_pref'] . $target[0]['shipment_addr1'] . $target[0]['shipment_addr2'], //$post['shippment_place'],
			$target[0]['arrival_date'] . " " . $arrival_time . ":00", //$post['arrival_datetime'],
			$target[0]['arrival_pref'] . $target[0]['arrival_addr1'] . $target[0]['arrival_addr2'], //$post['arrival_place'],
			$target[0]['hope_car_type'],
			$target[0]['hope_weight'],
			$target[0]['hope_fee'], //$post['delivery_fee'],
			$target[0]['hightway_fee'], //$post['hightway_fee'],
			null, //$post['payment_date'],
			null, //$post['security'],
			null, //$post['remarks'],
			1, //$post['status'],
			0, //$post['del_flg'],
		));


		return array(
			'status' => true
		);
	}

	private function del_item($post)
	{
		$this->common_logic->update_logic("t_" . $post['for'], "WHERE  FIND_IN_SET(`" . $post['for'] . "_id`, ?) ", array("del_flg"), array(1, $post['del_id']));

		return array(
			'status' => true
		);
	}
}
