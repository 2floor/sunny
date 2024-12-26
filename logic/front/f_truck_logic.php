<?php
session_start();
ini_set("display_errors", "on");
require_once __DIR__ . '/../../logic/common/common_logic.php';


if ($_POST != null && $_POST != '') {
	$f_truck_logic = new f_truck_logic();
	$f_truck_logic->ct($_POST);
} else {
	header(__DIR__ . '/../../index.php');
	exit();
}

class f_truck_logic
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
		} elseif ($post['method'] == "csv_insert") {
			$this->csv_insert($post, $_FILES);
		}
	}

	private function insert($post)
	{
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
		$res = $this->common_logic->insert_logic("t_truck", array(
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
			$post['truck_size'],
			$post['powergate_choice'],
			$post['number_of_truck'],
			$post['urgent_flg'],
			$post['baggage_type'],
			(int)$post['hope_fee'],
			$post['hightway_fee'],
			$post['pic'],
			$post['remarks'],
			$_SESSION['cclue']['login']['member_id'],
			0,
			0,
			0,
		));

		header("Location: ../../truck_entry_comp.php");
		exit();
	}

	private function update($post)
	{
		if ($post['Integration_flg'] == null ||  ['Integration_flg'] == '') $post['Integration_flg'] = 0;
		if ($post['urgent_flg'] == null || $post['urgent_flg'] == '') $post['urgent_flg'] = 0;
		if (isset($post['arrival_time_min'])) $post['arrival_time'] . ':' . $post['arrival_time_min'];
		if (isset($post['shipment_time_min'])) $post['shipment_time'] . ':' . $post['shipment_time_min'];

		$shipment_time = $post['shipment_time'];
		if ($post['shipment_time'] != '11:59' && $post['shipment_time'] != '23:59') {
			$shipment_time = $post['shipment_time'] . ':' . $post['shipment_time_min'];
		}

		$arrival_time = $post['arrival_time'];
		if ($post['arrival_time'] != '11:59' && $post['arrival_time'] != '23:59') {
			$arrival_time = $post['arrival_time'] . ':' . $post['arrival_time_min'];
		}

		$this->common_logic->update_logic("t_truck", " where truck_id = ? and member_id = ? ", array(
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
			'truck_size',
			'powergate_choice',
			'number_of_truck',
			'urgent_flg',
			'baggage_type',
			'hope_fee',
			'hightway_fee',
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
			$post['truck_size'],
			$post['powergate_choice'],
			$post['number_of_truck'],
			$post['urgent_flg'],
			$post['baggage_type'],
			(int)$post['hope_fee'],
			$post['hightway_fee'],
			$post['pic'],
			$post['remarks'],
			$post['truck_id'],
			$_SESSION['cclue']['login']['member_id'],
		));

		header("Location: ../../truck_entry_comp.php");
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

			$res = $this->common_logic->insert_logic("t_truck", array(
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
				$post['truck_size' . $name_add],
				$post['powergate_choice' . $name_add],
				$post['number_of_truck' . $name_add],
				$post['urgent_flg' . $name_add],
				$post['baggage_type' . $name_add],
				(int)$post['hope_fee' . $name_add],
				$post['hightway_fee' . $name_add],
				$post['pic' . $name_add],
				$post['remarks' . $name_add],
				$_SESSION['cclue']['login']['member_id'],
				0,
				0,
				0,
			));

			++$c;
		} while ($c < 50);

		$_SESSION['cclue']['multi_truck'] = $post;

		header("Location: ../../truck_entry_comp.php");
		exit();
	}



	private function csv_insert($post, $file)
	{
		$log = '';
		$file_data = $this->common_logic->front_unit_file_upload($file['csvFile'], "../../upload_files/csv/");


		$master = $this->common_logic->select_logic("select * from m_code", array());
		$mas = array(
			'weight' => array(),
			'car_type' => array(),
		);
		foreach ((array)$master as $v) {
			$mas[$v['code']][$v['code_id']] = true;
		}




		$file_data;

		if (file_exists($file_data['full_path'])) {
			$all_cnt = 0;
			$i = 0;
			$j = 0;

			$csv_file = file_get_contents($file_data['full_path']);

			$rep_flle = preg_replace("/\r\n|\r|\n/", "\n", $csv_file);
			$buf = mb_convert_encoding($rep_flle, "utf-8", "sjis");
			file_put_contents($file_data['full_path'], $buf);

			$fp = fopen($file_data['full_path'], 'r');
			setlocale(LC_ALL, 'ja_JP');

			$insert_cnt = 0;
			while (($line = fgetcsv($fp)) !== FALSE) {

				if ($all_cnt == 0 || $all_cnt == 1) {
					//ヘッダ無視
					++$all_cnt;
					continue;
				}

				$validate = true;

				$date = array();

				foreach ($line as $k => $li) {
					if (mb_detect_encoding($line[$k]) == 'sjis-win') {
						mb_convert_variables('UTF-8', 'sjis-win', $line[$k]);
					}

					if ($k == 0 || $k == 5) {
						$date_name = ($k == 5) ? "arrival_date" : "shipment_date";
						if ($this->validateDate($line[$k], "Y-m-d")) {
							$date[$date_name] = $line[$k];
						} elseif ($this->validateDate($line[$k], "Y/m/d")) {
							$date[$date_name] = str_replace("/", "-", $line[$k]);
						} elseif ($this->validateDate($line[$k], "Y-n-j")) {
							$date[$date_name] = date("Y-m-d", strtotime($line[$k]));
						} elseif ($this->validateDate($line[$k], "Y/n/j")) {
							$date[$date_name] = date("Y-m-d", strtotime(str_replace("/", "-", $line[$k])));;
						} else {
							$log .= ($all_cnt + 1) . '行目' . $this->eng($k) . '列の日付フォーマットが不正です' . "\n";
							$validate = false;
						}
					}

					if ($k == 1 || $k == 6) {
						$date_name = ($k == 6) ? "arrival_time" : "shipment_time";
						if ($this->validateDate($line[$k], "H:i")) {
							$date[$date_name] = $line[$k] . ":00";
						} elseif ($this->validateDate($line[$k], "H:i:s")) {
							$date[$date_name] = $line[$k];
						} elseif ($this->validateDate($line[$k], "G:i:s")) {
							$date[$date_name] = $line[$k];
						} elseif ($this->validateDate($line[$k], "G:i")) {
							$date[$date_name] = $line[$k] . ":00";
						} else {
							$log .= ($all_cnt + 1) . '行目' . $this->eng($k) . '列の時間フォーマットが不正です' . "\n";
							$validate = false;
						}
					}
				}

				if (!$this->time_diff($date["shipment_date"] . " " . $date["shipment_time"], $date["arrival_date"] . " " . $date["arrival_time"])) {
					$log .= ($all_cnt + 1) . '行目の着日が発日より前、もしくは同時刻に設定されています。' . "\n";
					$validate = false;
				}

				if (!$mas["weight"][$line[10]]) {
					$log .= ($all_cnt + 1) . '行目' . $this->eng(10) . '列の重量が変換できませんでした。（変換表を参照して入力してください）' . "\n";
					$validate = false;
				}
				if (!$mas["car_type"][$line[11]]) {
					$log .= ($all_cnt + 1) . '行目' . $this->eng(11) . '列の車種が変換できませんでした。（変換表を参照して入力してください）' . "\n";
					$validate = false;
				}

				if (!is_numeric($line[12])) {
					$log .= ($all_cnt + 1) . '行目' . $this->eng(12) . '列の税込み料金は半角数字でご入力ください' . "\n";
					$validate = false;
				}

				if (!is_numeric($line[13]) && $line[13] != "") {
					$log .= ($all_cnt + 1) . '行目' . $this->eng(13) . '列の高速料金は半角数字もしくは空白でご入力ください' . "\n";
					$validate = false;
				}

				if ($validate) {
					$res = $this->common_logic->insert_logic("t_truck", array(
						$line[0],
						$date['shipment_time'],
						$line[2],
						$line[3],
						$line[4],
						$line[5],
						$date['arrival_time'],
						$line[7],
						$line[8],
						$line[9],
						null, //$line['baggage_detail'],
						0, //$line['Integration_flg'],
						$line[10],
						$line[11],
						0, //$line['urgent_flg'],
						0, //$line['baggage_type'],
						(int)$post[12],
						$post[13],
						null, //$post['pic'],
						$post[14],
						$_SESSION['cclue']['login']['member_id'],
						0,
						0,
						0,
					));
					$insert_cnt++;
				}
				++$all_cnt;
			}

			fclose($fp);

			$log .= $insert_cnt . "行のデータが登録されました";

			$res =  array(
				'status' => true,
				'log' => $log,
			);

			header("Location:../../truck_entry_csv_comp.php?res=" . urlencode(json_encode($res)));
			exit();
		} else {
			return array(
				'status' => false,
				'msg' => 'ファイルが見つかりません。'
			);
		}
	}




	/**
	 * 日付妥当性チェック
	 */
	public function validateDate($date, $format = 'Y-m-d H:i:s')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}

	/**
	 * 英語変換
	 */
	public function eng($num)
	{
		$eng = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P");
		return $eng[$num];
	}
	/**
	 * 差分取得
	 */
	public function time_diff($time_from_b, $time_to_b)
	{
		$time_from = strtotime($time_from_b);
		$time_to = strtotime($time_to_b);
		// 日時差を秒数で取得
		$dif = $time_to - $time_from;
		if ($dif == 0) return false;
		if ($dif <  0) return false;
		else return true;
	}
}
