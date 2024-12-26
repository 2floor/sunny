<?php
session_start();
require_once __DIR__ . '/../../logic/common/common_logic.php';

class cclue_logic
{

	private $common_logic;

	public function __construct()
	{
		$this->common_logic = new common_logic();
	}


	/**
	 * ログインチェック
	 * @param boolean $noRedirect
	 * @return mixed
	 */
	public function login_check($noRedirect = false)
	{
		if ($_SESSION['cclue']['login'] == null || $_SESSION['cclue']['login'] == '') {
			if ($noRedirect) {
				return false;
			} else {
				header("Location: ./index.php");
				exit();
			}
		}
		return true;
	}

	/**
	 * 日付option生成
	 */
	public function create_date_opt()
	{

		$opt = '';

		$now_date = date('Y-m-d'/* , strtotime($now_date  . " +1 day " ) */);
		$end_date = date('Y-m-d',  strtotime($now_date  . " +2 month "));
		$c = 0;
		do {
			if ($now_date == date('Y-m-d')) {
				$opt .= '<option value="' . $now_date . '">今日</option>';
			} elseif ($now_date == date('Y-m-d', strtotime(" + 1 day"))) {
				$opt .= '<option value="' . $now_date . '">明日</option>';
			} else {
				$opt .= '<option value="' . $now_date . '">' . date('Y年m月d日', strtotime($now_date)) . '</option>';
			}
			$now_date = date('Y-m-d',  strtotime($now_date  . " +1 day"));

			++$c;
		} while ($c < 100 && $now_date <= $end_date);

		return $opt;
	}

	/**
	 * 日付option生成
	 */
	public function create_date_opt2()
	{

		$opt = array(
			'y' => '<option value="">年</option>',
			'm' => '<option value="">月</option>',
			'd' => '<option value="">日</option>',
		);

		$now_y = date('Y', strtotime(date('Y')));
		$now_m = date('n');
		$now_d = date('j');
		$end_y = date('Y', strtotime(date('Y') . " + 1 year "));
		$c = 0;
		$n = 1;
		do {
			if ($now_y <= $end_y) {
				// 				$y_sl = '';
				// 				if($now_y == date('Y')) $y_sl = 'selected="selected"';
				$opt['y'] .= '<option value="' . $now_y . '" ' . $y_sl . '>' . ($now_y) . '年</option>';
			}
			$now_y += 1;
			if ($c < 12) {
				// 				$m_sl = '';
				// 				if($n == date('n')) $m_sl = 'selected="selected"';
				$opt['m'] .= '<option value="' . str_pad($n, 2, '0', STR_PAD_LEFT) . '" ' . $m_sl . '>' . $n . '月</option>';
			}
			// 			$d_sl = '';
			// 			if($n == date('j')) $d_sl = 'selected="selected"';
			$opt['d'] .= '<option value="' . str_pad($n, 2, '0', STR_PAD_LEFT) . '" ' . $d_sl . '>' . $n . '日</option>';
			++$c;
			++$n;
		} while ($c < 31);

		return $opt;
	}


	/**
	 * 時間option生成
	 */
	public function create_time_opt($flg = false)
	{

		$opt = '';
		if ($flg) $opt = '';

		$c = 0;
		do {
			$t = $c;
			if (strlen($c) == 1) {
				$t = '0' . $c;
			}
			$opt .= '<option value="' . $c . '">' . $t . '時</option>';

			++$c;
		} while ($c < 24);

		return $opt;
	}

	/**
	 * 都道府県option生成
	 */
	public function create_pref_opt()
	{
		return '<option value="北海道">北海道</option>
			<option value="青森県">青森県</option>
			<option value="岩手県">岩手県</option>
			<option value="宮城県">宮城県</option>
			<option value="秋田県">秋田県</option>
			<option value="山形県">山形県</option>
			<option value="福島県">福島県</option>
			<option value="茨城県">茨城県</option>
			<option value="栃木県">栃木県</option>
			<option value="群馬県">群馬県</option>
			<option value="埼玉県">埼玉県</option>
			<option value="千葉県">千葉県</option>
			<option value="東京都">東京都</option>
			<option value="神奈川県">神奈川県</option>
			<option value="新潟県">新潟県</option>
			<option value="富山県">富山県</option>
			<option value="石川県">石川県</option>
			<option value="福井県">福井県</option>
			<option value="山梨県">山梨県</option>
			<option value="長野県">長野県</option>
			<option value="岐阜県">岐阜県</option>
			<option value="静岡県">静岡県</option>
			<option value="愛知県">愛知県</option>
			<option value="三重県">三重県</option>
			<option value="滋賀県">滋賀県</option>
			<option value="京都府">京都府</option>
			<option value="大阪府">大阪府</option>
			<option value="兵庫県">兵庫県</option>
			<option value="奈良県">奈良県</option>
			<option value="和歌山県">和歌山県</option>
			<option value="鳥取県">鳥取県</option>
			<option value="島根県">島根県</option>
			<option value="岡山県">岡山県</option>
			<option value="広島県">広島県</option>
			<option value="山口県">山口県</option>
			<option value="徳島県">徳島県</option>
			<option value="香川県">香川県</option>
			<option value="愛媛県">愛媛県</option>
			<option value="高知県">高知県</option>
			<option value="福岡県">福岡県</option>
			<option value="佐賀県">佐賀県</option>
			<option value="長崎県">長崎県</option>
			<option value="熊本県">熊本県</option>
			<option value="大分県">大分県</option>
			<option value="宮崎県">宮崎県</option>
			<option value="鹿児島県">鹿児島県</option>
			<option value="沖縄県">沖縄県</option>';
	}

	/**
	 * マスタよりoption生成
	 */
	public function create_master_opt()
	{
		$opt = array(
			'weight' => '',
			'car_type' => '',
		);
		$master = $this->common_logic->select_logic("select * from m_code", array());
		foreach ((array)$master as $v) {
			$opt[$v['code']] .= '<option value="' . $v['code_id'] . '">' . $v['param'] . '</option>';
		}

		return $opt;
	}


	/**
	 * マスタよりoption生成
	 */
	public function create_master_mini()
	{
		$opt = array(
			'weight' => array(),
			'car_type' => array(),
		);
		$master = $this->common_logic->select_logic("select * from m_code", array());
		foreach ((array)$master as $v) {
			$opt[$v['code']][$v['code_id']] = $v['param2'];
		}

		return $opt;
	}

	/**
	 * マスタよりoption生成
	 */
	public function create_master_full()
	{
		$opt = array(
			'weight' => array(),
			'car_type' => array(),
			'temper' => array(),
		);
		$master = $this->common_logic->select_logic("select * from m_code", array());
		foreach ((array)$master as $v) {
			$opt[$v['code']][$v['code_id']] = $v['param'];
		}

		return $opt;
	}


	/**
	 * マスタよりcheckbox生成
	 */
	public function create_master_opt_chk()
	{
		$opt = array(
			'weight' => '',
			'car_type' => '',
		);
		$master = $this->common_logic->select_logic("select * from m_code", array());
		foreach ((array)$master as $v) {
			$opt[$v['code']] .= '
								<label>
									<input type="checkbox" name="' . $v['code'] . '_base" class="checkbox01-input" value="' . $v['code_id'] . '">
									<span class="checkbox01-parts">' . $v['param'] . '</span>
								</label>';
		}

		return $opt;
	}
	public function create_search_date_def($get, $create_where_flg = false)
	{
		$date_search_ar = array(
			'shipment' => array(
				'date' => array(
					's' => array(
						'y' => date('Y'),
						'm' => '01',
						'd' => '01',
					),
					'e' => array(
						'y' => date('Y'),
						'm' => '12',
						'd' => '31',
					),
				),
			),
			'arrival' => array(
				'date' => array(
					's' => array(
						'y' => date('Y'),
						'm' => '01',
						'd' => '01',
					),
					'e' => array(
						'y' => date('Y'),
						'm' => '12',
						'd' => '31',
					),
				),
			),
			'agreement' => array(
				'date' => array(
					's' => array(
						'y' => date('Y'),
						'm' => '01',
						'd' => '01',
					),
					'e' => array(
						'y' => date('Y'),
						'm' => '12',
						'd' => '31',
					),
				),
			),
		);
		$where = '';
		$where_param = array();
		$date_param = array();

		foreach ($date_search_ar as $part => $dsa) {
			foreach ($dsa as $date => $dsa_in) {
				foreach ($dsa_in as $for => $target) {
					$set_flg = false;
					foreach ($target as $ymd => $data_ymd) {
						//デフォ値挿入チェック
						if (!($get[$part . "_" . $date . "_" . $for . "_" . $ymd] == null || $get[$part . "_" . $date . "_" . $for . "_" . $ymd] == '')) $set_flg = true;
					}
					if ($set_flg) {
						foreach ($target as $ymd2 => $data_ymd2) {
							//デフォ値挿入
							if ($get[$part . "_" . $date . "_" . $for . "_" . $ymd2] == null || $get[$part . "_" . $date . "_" . $for . "_" . $ymd2] == '') $get[$part . "_" . $date . "_" . $for . "_" . $ymd2] = $data_ymd2;
						}
					}

					//検索query生成
					$where_base = $part . "_" . $date . "_" . $for;



					if ($create_where_flg) {
						$where_in_param = $get[$where_base . "_y"];

						$date_param[$where_base] = $where_in_param;
						$md = date('md', strtotime($where_in_param));
						$where_in_param = date('Y-m-d', strtotime(" last month of " . $get[$where_base . "_y"]));
						if ($this->validateDateCclueLogic($where_in_param) && $where_in_param != '1970-01-01') {
							if ($for == 's') {
								$where .= " AND  ( ? < `" . $part . "_date` ";
							} elseif ($for == 'e') {
								$where .= " AND  ( `" . $part . "_date` < ?";
							}
							array_push($where_param, $where_in_param);
							if ($get[$where_base . '_t'] != null && $get[$where_base . '_t'] != '') {
								if ($get[$where_base . '_t'] != '1159' && $get[$where_base . '_t'] != '2359') $get[$where_base . '_t'] = $get[$where_base . '_t'] * 100;
								if ($for == 's') {
									if ($get[$where_base . '_t'] == '1159') $get[$where_base . '_t'] = 0;
									if ($get[$where_base . '_t'] == '2359') $get[$where_base . '_t'] = 1200;
									$where .= " OR  ( `" . $part . "_date` = ? AND CAST( ? AS signed ) <= CAST(`" . $part . "_time` AS signed ) ) ";
								} elseif ($for == 'e') {
									$where .= " OR  ( `" . $part . "_date` = ? AND CAST(`" . $part . "_time` AS signed ) <= CAST( ? AS signed ) ) ";
								}
								array_push($where_param, $where_in_param, $get[$where_base . '_t']);
								$date_param[$where_base . '_t'] = $get[$where_base . '_t'];
							} else {
								$where .= " OR `" . $part . "_date` = ? ";
								array_push($where_param, $where_in_param);
							}
							$where .= " ) ";
						}
					}
				}
			}
		}





		$validate = true;
		if (date($date_param["shipment_date_s"]) <= date($date_param["shipment_date_e"])) {
			if ($date_param["shipment_date_s_t"] != null && $date_param["shipment_date_s_t"] != ''  &&  $date_param["shipment_date_e_t"] != null && $date_param["shipment_date_e_t"] != '') {
				if ((int)$date_param["shipment_date_s_t"] > (int)$date_param["shipment_date_e_t"]) {
					$validate = false;
				}
			} else {
				if (date($date_param["shipment_date_s"]) != date($date_param["shipment_date_e"])) {
					$validate = false;
				}
			}
		}

		if (date($date_param["arrival_date_s"]) <= date($date_param["arrival_date_e"])) {
			if ($date_param["arrival_date_s_t"] != null && $date_param["arrival_date_s_t"] != ''  &&  $date_param["arrival_date_e_t"] != null && $date_param["arrival_date_e_t"] != '') {
				if ((int)$date_param["arrival_date_s_t"] > (int)$date_param["arrival_date_e_t"]) {
					$validate = false;
				}
			} else {
				if (date($date_param["arrival_date_s"]) != date($date_param["arrival_date_e"])) {
					$validate = false;
				}
			}
		}

		if ($create_where_flg) {


			return array(
				'where' => $where,
				'where_param' => $where_param,
				'validate' => $validate,
			);
		} else {
			return $get;
		}
	}

	/**
	 * 日付フォーマットチェック
	 */
	function validateDateCclueLogic($date, $format = 'Y-m-d')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
}
