<?php
session_start();
require_once __DIR__ . '/../../logic/common/common_logic.php';


if ($_POST != null && $_POST != '') {
	$f_baggage_logic = new f_baggage_logic();
	$f_baggage_logic->ct($_POST);
} else {
	header(__DIR__ . '/../../index.php');
	exit();
}

class f_baggage_logic
{

	private $common_logic;

	public function  __construct()
	{
		$this->common_logic = new common_logic();
	}

	public function ct($post)
	{
		if ($post['method'] == "insert") {
			$this->insert($post);
		} elseif ($post['method'] == "update") {
			$this->update($post);
		} elseif ($post['method'] == "multi_insert") {
			$this->multi_insert($post);
		}
	}

	private function insert($post)
	{


		if ($post['Integration_flg'] == null ||  ['Integration_flg'] == '') $post['Integration_flg'] = 0;
		if ($post['urgent_flg'] == null || $post['urgent_flg'] == '') $post['urgent_flg'] = 0;

		$shipment_time = $post['shipment_time'];
		if ($post['shipment_time'] != '11:59' && $post['shipment_time'] != '23:59') $shipment_time = $post['shipment_time'] . ':' . $post['shipment_time_min'];

		$arrival_time = $post['arrival_time'];
		if ($post['arrival_time'] != '11:59' && $post['arrival_time'] != '23:59') $arrival_time = $post['arrival_time'] . ':' . $post['arrival_time_min'];

		$res = $this->common_logic->insert_logic("t_baggage", array(
			$post['baggage_number'],
			$post['shipment_date'],
			$shipment_time,
			$post['shipment_pref'],
			$post['shipment_addr1'],
			$post['shipment_addr2'],
			$post['arrival_date'],
			$arrival_time,
			$post['arrival_pref'],
			$post['arrival_addr1'],
			$post['arrival_addr2'],
			$post['baggage_detail'],
			$post['Integration_flg'],
			$post['hope_weight'],
			$post['hope_car_type'],
			$post['hope_car_temper'],
			$post['urgent_flg'],
			$post['powergate_choice'],
			$post['hope_truck_num'],
			$post['baggage_type'],
			(int)$post['hope_fee'],
			$post['pic'],
			$post['remarks'],
			$_SESSION['cclue']['login']['member_id'],
			0,
			0,
			0,
		));

		header("Location: ../../luggage_entry_comp.php");
		exit();
	}

	private function update($post)
	{

		// var_dump($post);
		// exit;
		if ($post['Integration_flg'] == null ||  ['Integration_flg'] == '') $post['Integration_flg'] = 0;
		if ($post['urgent_flg'] == null || $post['urgent_flg'] == '') $post['urgent_flg'] = 0;

		$shipment_time = $post['shipment_time'];
		if ($post['shipment_time'] != '11:59' && $post['shipment_time'] != '23:59') {
			$shipment_time = $post['shipment_time'] . ':' . $post['shipment_time_min'];
		}
		$arrival_time = $post['arrival_time'];
		if ($post['arrival_time'] != '11:59' && $post['arrival_time'] != '23:59') {
			$arrival_time = $post['arrival_time'] . ':' . $post['arrival_time_min'];
		}


		$this->common_logic->update_logic("t_baggage", " where baggage_id = ? and member_id = ? ", array(
			'shipment_date',
			'shipment_time',
			'shipment_pref',
			'shipment_addr1',
			'shipment_addr2',
			'arrival_date',
			'arrival_time',
			'arrival_pref',
			'arrival_addr1',
			'arrival_addr2',
			'baggage_detail',
			'Integration_flg',
			'hope_weight',
			'hope_car_type',
			'hope_car_temper',
			'urgent_flg',
			'powergate_choice',
			'hope_truck_num',
			'baggage_type',
			'hope_fee',
			'pic',
			'remarks',
		), array(
			$post['shipment_date'],
			$shipment_time,
			$post['shipment_pref'],
			$post['shipment_addr1'],
			$post['shipment_addr2'],
			$post['arrival_date'],
			$arrival_time,
			$post['arrival_pref'],
			$post['arrival_addr1'],
			$post['arrival_addr2'],
			$post['baggage_detail'],
			$post['Integration_flg'],
			$post['hope_weight'],
			$post['hope_car_type'],
			$post['hope_car_temper'],
			$post['urgent_flg'],
			$post['powergate_choice'],
			$post['hope_truck_num'],
			$post['baggage_type'],
			(int)$post['hope_fee'],
			$post['pic'],
			$post['remarks'],
			$post['baggage_id'],
			$_SESSION['cclue']['login']['member_id'],
		));

		header("Location: ../../luggage_entry_comp.php");
		exit();
	}


	private function multi_insert($post)
	{
		$c = 1;
		do {
			$name_add = '';
			if ($c != 1) $name_add = '-' . $c;
			if ($post['submit_info' . $name_add] != 'on') {
				++$c;
				continue;
			}

			if ($post['Integration_flg' . $name_add] == null ||  ['Integration_flg' . $name_add] == '' . $name_add) $post['Integration_flg' . $name_add] = 0;
			if ($post['urgent_flg' . $name_add] == null || $post['urgent_flg' . $name_add] == '') $post['urgent_flg' . $name_add] = 0;

			$shipment_time = $post['shipment_time' . $name_add];
			if ($post['shipment_time' . $name_add] != '11:59' && $post['shipment_time' . $name_add] != '23:59') $shipment_time = $post['shipment_time' . $name_add] . ':' . $post['shipment_time_min' . $name_add];

			$arrival_time = $post['arrival_time' . $name_add];
			if ($post['arrival_time' . $name_add] != '11:59' && $post['arrival_time' . $name_add] != '23:59') $arrival_time = $post['arrival_time' . $name_add] . ':' . $post['arrival_time_min' . $name_add];

			$res = $this->common_logic->insert_logic("t_baggage", array(
				$post['baggage_number' . $name_add],
				$post['shipment_date' . $name_add],
				$shipment_time,
				$post['shipment_pref' . $name_add],
				$post['shipment_addr1' . $name_add],
				$post['shipment_addr2' . $name_add],
				$post['arrival_date' . $name_add],
				$arrival_time,
				$post['arrival_pref' . $name_add],
				$post['arrival_addr1' . $name_add],
				$post['arrival_addr2' . $name_add],
				$post['baggage_detail' . $name_add],
				$post['Integration_flg' . $name_add],
				$post['hope_weight' . $name_add],
				$post['hope_car_type' . $name_add],
				$post['hope_car_temper' . $name_add],
				$post['urgent_flg' . $name_add],
				$post['powergate_choice' . $name_add],
				$post['hope_truck_num' . $name_add],
				$post['baggage_type' . $name_add],
				(int)$post['hope_fee' . $name_add],
				$post['pic' . $name_add],
				$post['remarks' . $name_add],
				$_SESSION['cclue']['login']['member_id'],
				0,
				0,
				0,
			));
			++$c;
		} while ($c < 50);

		$_SESSION['cclue']['multi_baggage'] = $post;

		header("Location: ../../luggage_entry_comp.php");
		exit();
	}
}
