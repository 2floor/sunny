<?php
session_start();
require_once __DIR__ . '/../../logic/common/common_logic.php';
require_once __DIR__ . '/../../logic/front/cclue_logic.php';

class front_disp_logic
{

	private $common_logic;
	private $cclue_logic;

	public function __construct()
	{
		$this->common_logic = new common_logic();
		$this->cclue_logic = new cclue_logic();
	}

	/**
	 * header 現在の日付表示用
	 * @return string
	 */
	static function getNowDate()
	{
		$week_ar = array('(日)', '(月)', '(火)', '(水)', '(木)', '(金)', '(土)');
		$now = date('Y/m/d') . $week_ar[date("w")]  . ' ' . date('H:i');
		return $now;
	}

	/**
	 *
	 * @param int $limit
	 * @return mixed t_agreementからの検索結果
	 */
	public function getNewAgreement($limit = 5)
	{
		$data = $this->common_logic->select_logic("SELECT * FROM t_agreement WHERE del_flg = 0 ORDER BY agreement_datetime DESC LIMIT " . $limit, array());
		return $data;
	}

	public function getNewAgreementHTML($limit = 5, $topFlg = null)
	{
		$html = '';
		$data = $this->getNewAgreement($limit);
		if ($topFlg) {
			$template = $this->getTopAgreementTemplate();
		} else {
			$template = $this->getIndexAgreementTemplate();
		}

		$pattern = array(
			'/##AGREEMENT_CLASS##/',
			'/##AGREEMENT_TYPE##/',
			'/##SHIPMENT_PLACE##/',
			'/##ARRIVAL_PLACE##/',
			'/##TRUCK_TYPE##/',
			'/##MONEY##/',
			'/##SHIPMENT_DATETIME##/',
			'/##ARRIVALT_DATETIME##/',
		);

		$type_arr = array(
			0 => 'leftMenuInfoBatchNimotsu',
			1 => 'leftMenuInfoBatchSyaryou',
		);
		$type_name_arr = array(
			0 => '荷物登録',
			1 => '車両登録',
		);


		for ($i = 0; $i < count($data); $i++) {
			$class = '';
			if (isset($type_arr[$data[$i]['bt_type']])) {
				$class = $type_arr[$data[$i]['bt_type']];
			}
			$type_name = '';
			if (isset($type_name_arr[$data[$i]['bt_type']])) {
				$type_name = $type_name_arr[$data[$i]['bt_type']];
			}
			$truck_type = '';
			if ($data[$i]['bt_type'] == 1) {
				$truck_type = $this->getTruckType($data[$i]['bt_id']);
			}
			$money = $data[$i]['delivery_fee'] + $data[$i]['hightway_fee'];
			$shipment_datetime = '';
			$arrival_datetime = '';

			$replacement = array(
				$class,
				$type_name,
				$data[$i]['shipment_place'],
				$data[$i]['arrival_place'],
				$truck_type,
				$money,
				$shipment_datetime,
				$arrival_datetime,
			);
			$html .= preg_replace($pattern, $replacement, $template);
		}

		return $html;
	}

	private function getTruckType($truck_id)
	{
		$code = null;
		$result = '';
		$data = $this->common_logic->select_logic("SELECT * FROM t_truck WHERE truck_id = " . $truck_id, array());
		if (is_array($data)) {
			$code = $data[0]['hope_weight'];
			$m_code = $this->common_logic->select_logic("select * from m_code WHERE code_id = ?", array($code));
			if (is_array($m_code)) {
				$result = $m_code[0]['param2'];
			}
		}

		return $result;
	}


	public function get_list($type, $get)
	{

		$mini_str = $this->cclue_logic->create_master_mini();

		$table = "";
		$add_col = "";
		$order = " ORDER BY `created_at` DESC ";
		$detail_url_base = "";
		if ($type == 'truck_me') {
			//自身の依頼したトラック
			$table = "`t_truck`";
			$id_col_name = "truck_id";
			$detail_url_base = "truck_detail.php?tid=";
			$where = " WHERE `member_id` = ? ";
			$where_param = array($_SESSION['cclue']['login']['member_id']);
		} elseif ($type == 'baggage_me') {
			//自身の依頼した荷物
			$table = "`t_baggage`";
			$id_col_name = "baggage_id";
			$detail_url_base = "luggage_detail.php?bid=";
			$where = " WHERE `member_id` = ? ";
			$where_param = array($_SESSION['cclue']['login']['member_id']);
		} elseif ($type == 'truck') {
			//トラック検索
			$table = "`t_truck`";
			$id_col_name = "truck_id";
			$detail_url_base = "truck_detail.php?tid=";
			$where = " INNER JOIN ( SELECT `member_id`, `name`, `tel`, `resp_name` FROM `t_member` WHERE del_flg = 0 ) AS `tm` USING(`member_id`) WHERE `public_flg` = 0 AND `public_flg` = 0 AND `fin_flg` = 0 ";
			$add_col = ", `member_id`";
			$where_param = array();
		} elseif ($type == 'baggage') {
			//荷物検索
			$table = "`t_baggage`";
			$id_col_name = "baggage_id";
			$detail_url_base = "baggage_detail.php?tid=";
			$where = " INNER JOIN ( SELECT `member_id`, `name`, `tel`, `resp_name` FROM `t_member` WHERE del_flg = 0 ) AS `tm` USING(`member_id`) WHERE `public_flg` = 0 AND `public_flg` = 0 AND `fin_flg` = 0 ";
			$add_col = ", `member_id`";
			$where_param = array();
		} elseif ($type == 'agreement') {
			//成約情報
			$table = "`t_agreement`";
			$id_col_name = "agreement_id";
			$detail_url_base = "javascript:void(0);"; //"agreement_detail.php?tid=";
			$where = " INNER JOIN ( SELECT `member_id`, `name`, `tel`, `resp_name` FROM `t_member` WHERE del_flg = 0 ) AS `tm` ON `tm`.`member_id` = `t_agreement`.`received_id` INNER JOIN ( SELECT `member_id`, `name`, `tel`, `resp_name` FROM `t_member` WHERE del_flg = 0 ) AS `tm_o` ON `tm_o`.`member_id` = `t_agreement`.`orderer_id` WHERE (`orderer_id` = ?  OR `received_id` = ? )AND `del_flg` = 0  ";
			// 			$add_col = ", `member_id`";
			$where_param = array($_SESSION['cclue']['login']['member_id'], $_SESSION['cclue']['login']['member_id']);
		}

		$wp = $this->create_where($get);

		$where .= $wp['where'];
		$where_param = array_merge($where_param, $wp['where_param']);

		$disp_num = 10;

		$now_page = 0;
		if ($get['now'] != null && $get['now'] != '') {
			$now_page = $get['now'];
		}
		$data_cnt = $this->common_logic->select_logic("select count(`" . $id_col_name . "`) AS `cnt` " . $add_col . " from " . $table . " " . $where . " " . $order, $where_param);
		$all_cnt = $data_cnt[0]['cnt'];
		if ($all_cnt == 0) {
			$page_num = 1;
		} else {
			$page_num = ceil($all_cnt / $disp_num);
		}
		$limit = $disp_num;
		$offset = $now_page * $disp_num;
		array_push($where_param, $limit, $offset);

		$data = $this->common_logic->select_logic("select * from " . $table . " " . $where . " " . $order . " LIMIT ? OFFSET ? ", $where_param);
		$html = '';
		if ($data != null && $data != '') {
			foreach ((array)$data as $num => $row) {
				if ($type == 'truck_me') {
					$html .= $this->create_truck_html_me($row, $mini_str);
				} elseif ($type == 'truck') {
					$html .= $this->create_truck_html($row, $mini_str);
				} elseif ($type == 'baggage_me') {
					$html .= $this->create_baggage_html_me($row, $mini_str);
				} elseif ($type == 'baggage') {
					$html .= $this->create_baggage_html($row, $mini_str);
				} elseif ($type == 'agreement') {
					$html .= $this->create_agreement_html($row, $mini_str);
				}
			}
		} else {
			$html .= '<div class="searchResultListRow">
									データがありません
								</div>';
		}

		$pager = '';
		if ($all_cnt > 0) {

			$url_add = '';
			if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
				$url_add_ar = explode('?', $_SERVER['REQUEST_URI']);
				$de = urldecode($url_add_ar[1]);
				$gp = explode('&', $de);
				foreach ($gp as $k => $as) {
					if (strpos($as, 'now') !== false) array_splice($gp, $k, 1);
				}
				array_values($gp);
				$url_add = implode('&', $gp);
			}

			$pager_start = 0;
			if ($now_page > 2) {
				$pager_start = $now_page - 2;
				if ($now_page + 2 >= $page_num) {
					$pager_start = $now_page - 4  + ($page_num - $now_page);
				}
			}
			if ($pager_start < 0) {
				$pager_start = 0;
			}


			//戻る処理
			if ($now_page != 0) {
				$prev_num = $now_page - 1;
				$p = '?now=' . $prev_num;
				if ($url_add != '') $p .= '&' . $url_add;

				$pager .= '
					<li class="searchResultPager">
						<a href="' . $p . '">＜</a>
					</li>
						';
			}

			$max_page = $all_cnt / $disp_num;
			$cnt = 0;
			for ($i = $pager_start; $i < $page_num; $i++) {
				$cnt++;

				$disp_i = $i + 1;
				if ($cnt > 5) {
					break;
				}

				//現在のページクラス付与用
				$active = '';
				$url = '?now=' . $i;
				if ($url_add != '') $url .= '&' . $url_add;
				if ($i == $now_page) {
					$url = "javascript:void(0);";
					$active = 'active';
				}

				//数字処理
				$pager .= '
					<li class="searchResultPager ' . $active . '">
						<a href="' . $url . '">' . $disp_i . '</a>
					</li>
					';
			}

			//次へ処理
			if ($page_num != $now_page + 1) {
				$next_page = $now_page + 1;
				$p = '?now=' . $next_page;
				if ($url_add != '') $p .= '&' . $url_add;
				$pager .= '
					<li  class="searchResultPager">
						<a href="' . $p . '" >＞</a>
					</li>
						';
			}
		}

		return  array(
			'html' => $html,
			'pager' => $pager,
			'all_cnt' => $all_cnt,
		);
	}


	function create_where($get)
	{

		$where = "";
		$where_param = array();

		if ($get != null && $get != '') {
			$shipment_date_s = $get['shipment_date_s_y'] . '-' . $get['shipment_date_s_m'] . '-' . $get['shipment_date_s_d'];
			$shipment_date_e = $get['shipment_date_e_y'] . '-' . $get['shipment_date_e_m'] . '-' . $get['shipment_date_e_d'];
			$arrival_date_s = $get['arrival_date_s_y'] . '-' . $get['arrival_date_s_m'] . '-' . $get['arrival_date_s_d'];
			$arrival_date_e = $get['arrival_date_e_y'] . '-' . $get['arrival_date_e_m'] . '-' . $get['arrival_date_e_d'];

			if ($this->validateDate($shipment_date_s)) {
				$where .= " AND  ( ? < `shipment_date` ";
				array_push($where_param, $shipment_date_s);
				if ($get['shipment_date_s_t'] != null && $get['shipment_date_s_t'] != '') {
					$where .= " OR  ( `shipment_date` = ? AND ? <= CAST(`shipment_time` AS signed ) ) ";
					array_push($where_param, $shipment_date_s, $get['shipment_date_s_t']);
				} else {
					$where .= " OR `shipment_date` = ? ";
					array_push($where_param, $shipment_date_s);
				}
				$where .= " ) ";
			}


			if ($this->validateDate($shipment_date_e)) {
				$where .= " AND  ( `shipment_date` < ?";
				array_push($where_param, $shipment_date_e);
				if ($get['shipment_date_e_t'] != null && $get['shipment_date_e_t'] != '') {
					$where .= " OR  ( `shipment_date` = ? AND CAST(`shipment_time` AS signed ) <= ? ) ";
					array_push($where_param, $shipment_date_e, $get['shipment_date_e_t']);
				} else {
					$where .= " OR `shipment_date` = ? ";
					array_push($where_param, $shipment_date_e);
				}
				$where .= " ) ";
			}

			if ($this->validateDate($arrival_date_s)) {
				$where .= " AND  ( ? < `arrival_date` ";
				array_push($where_param, $arrival_date_s);
				if ($get['arrival_date_s_t'] != null && $get['arrival_date_s_t'] != '') {
					$where .= " OR  ( `arrival_date` = ? AND ? <= CAST(`arrival_time` AS signed ) ) ";
					array_push($where_param, $arrival_date_s, $get['arrival_date_s_t']);
				} else {
					$where .= " OR `arrival_date` = ? ";
					array_push($where_param, $arrival_date_s);
				}
				$where .= " ) ";
			}


			if ($this->validateDate($arrival_date_e)) {
				$where .= " AND  ( `arrival_date` < ?";
				array_push($where_param, $arrival_date_e);
				if ($get['arrival_date_e_t'] != null && $get['arrival_date_e_t'] != '') {
					$where .= " OR  ( `arrival_date` = ? AND CAST(`arrival_time` AS signed ) <= ? ) ";
					array_push($where_param, $arrival_date_e, $get['arrival_date_e_t']);
				} else {
					$where .= " OR `arrival_date` = ? ";
					array_push($where_param, $arrival_date_e);
				}
				$where .= " ) ";
			}


			if ($get['weight'] != null && $get['weight'] != '') {
				$where .= " AND  FIND_IN_SET(`hope_weight`, ?)";
				array_push($where_param, $get['weight']);
			}

			if ($get['car_type'] != null && $get['car_type'] != '') {
				$where .= " AND  FIND_IN_SET(`hope_car_type`, ?)";
				array_push($where_param, $get['car_type']);
			}

			$pref = array('1' => '北海道', '2' => '青森', '3' => '岩手', '4' => '宮城', '5' => '秋田', '6' => '山形', '7' => '福島', '8' => '茨城', '9' => '栃木', '10' => '群馬', '11' => '埼玉', '12' => '千葉', '13' => '東京', '14' => '神奈川', '15' => '山梨', '16' => '長野', '17' => '新潟', '18' => '富山', '19' => '石川', '20' => '福井', '21' => '岐阜', '22' => '静岡', '23' => '愛知', '24' => '三重', '25' => '滋賀', '26' => '京都', '27' => '大阪', '28' => '兵庫', '29' => '奈良', '30' => '和歌山', '31' => '鳥取', '32' => '島根', '33' => '岡山', '34' => '広島', '35' => '山口', '36' => '徳島', '37' => '香川', '38' => '愛媛', '39' => '高知', '40' => '福岡', '41' => '佐賀', '42' => '長崎', '43' => '熊本', '44' => '大分', '45' => '宮崎', '46' => '鹿児島', '47' => '沖縄');
		}

		return array(
			'where' => $where,
			'where_param' => $where_param,
		);
	}

	/**
	 * 日付フォーマットチェック
	 */
	function validateDate($date, $format = 'Y-m-d')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}

	/**
	 * 自身の依頼したトラックHTML生成
	 */
	function create_truck_html_me($row, $mini_str)
	{
		$week_str = array("日", "月", "火", "水", "木", "金", "土");
		$id_col_name = "truck_id";
		$detail_url_base = "truck_detail.php?tid=";
		$html = '';
		$shipment_date = date('m/d', strtotime($row['shipment_date'])) . "(" . $week_str[date('w', strtotime($row['shipment_date']))] . ")";
		$arrival_date = date('m/d', strtotime($row['arrival_date'])) . "(" . $week_str[date('w', strtotime($row['arrival_date']))] . ")";
		$remarks_exist = "×";
		if ($row["remarks"] != null && $row["remarks"] != '') $remarks_exist = "〇";

		$html .= '
							<div class="searchResultListRow">
								<div class="searchResultItem itemWid1">
									<a href="' . $detail_url_base . $row[$id_col_name] . '">' . $this->common_logic->zero_padding($row[$id_col_name], 8) . '</a>
								</div>
								<div class="searchResultItem itemWid1">
									' . $shipment_date . '<br>
									' . $row['shipment_time'] . '時
								</div>
								<div class="searchResultItem itemWid3">
									' . $row['shipment_pref'] . '' . $row['shipment_addr1'] . '' . $row['shipment_addr2'] . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $arrival_date . '<br>
									' . $row['arrival_time'] . '時
								</div>
								<div class="searchResultItem itemWid3">
									' . $row['arrival_pref'] . '' . $row['arrival_addr1'] . '' . $row['arrival_addr2'] . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $mini_str['car_type'][$row['hope_car_type']] . '
								</div>
								<div class="searchResultItem itemWid2">
									' . $mini_str['weight'][$row['hope_weight']] . '
								</div>
								<div class="searchResultItem itemWid1">
									￥' . number_format($row['hope_fee']) . '
								</div>
								<div class="searchResultItem itemWid1">
									￥' . number_format($row['hightway_fee']) . '
								</div>
								<div class="searchResultItem itemWid2">
									' . $remarks_exist . '
								</div>
							</div>
						</a>';
		return $html;
	}



	/**
	 * トラック検索結果HTML生成
	 */
	function create_truck_html($row, $mini_str)
	{
		$week_str = array("日", "月", "火", "水", "木", "金", "土");
		$id_col_name = "truck_id";
		$detail_url_base = "truck_detail.php?tid=";
		$html = '';


		$shipment_date = date('m/d', strtotime($row['shipment_date'])) . "(" . $week_str[date('w', strtotime($row['shipment_date']))] . ")";
		$shipment_place = $row['shipment_pref'] . '' . $row['shipment_addr1'] . '' . $row['shipment_addr2'];

		$arrival_date = date('m/d', strtotime($row['arrival_date'])) . "(" . $week_str[date('w', strtotime($row['arrival_date']))] . ")";
		$remarks_exist = "×";
		if ($row["remarks"] != null && $row["remarks"] != '') $remarks_exist = "〇";

		$btn = '';
		if ($_SESSION['cclue']['login']['member_id'] != $row["member_id"]) {
			$btn = '<button type="button" name="agree_btn" value="' . $row[$id_col_name] . '" t="truck" class="btnCancel">
				成約
			</button>';
		}

		$html .= '
							<div class="searchResultListRow">
								<div class="searchResultItem itemWid1 innnerWidBasis100">
									<a href="' . $detail_url_base . $row[$id_col_name] . '">' . $this->common_logic->zero_padding($row[$id_col_name], 8) . '</a><br>
									' . $btn . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $shipment_date . '<br>
									' . $row['shipment_time'] . '時
								</div>
								<div class="searchResultItem itemWid3">
									' . $row['shipment_pref'] . '' . $row['shipment_addr1'] . '' . $row['shipment_addr2'] . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $arrival_date . '<br>
									' . $row['arrival_time'] . '時
								</div>
								<div class="searchResultItem itemWid3">
									' . $row['arrival_pref'] . '' . $row['arrival_addr1'] . '' . $row['arrival_addr2'] . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $mini_str['car_type'][$row['hope_car_type']] . '
								</div>
								<div class="searchResultItem itemWid2">
									' . $mini_str['weight'][$row['hope_weight']] . '
								</div>
								<div class="searchResultItem itemWid1">
									￥' . number_format($row['hope_fee']) . '
								</div>
								<div class="searchResultItem itemWid1">
									￥' . number_format($row['hightway_fee']) . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $this->conv_company($row['name']) . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $row['resp_name'] . '
								</div>
								<div class="searchResultItem itemWid4">
									' . $row['tel'] . '
								</div>
								<div class="searchResultItem itemWid2">
									' . $remarks_exist . '
								</div>
							</div>';
		return $html;
	}


	/**
	 * 自身の依頼した荷物HTML生成
	 */
	function create_baggage_html_me($row, $mini_str)
	{
		$week_str = array("日", "月", "火", "水", "木", "金", "土");
		$id_col_name = "baggage_id";
		$detail_url_base = "luggage_detail.php?bid=";
		$html = '';

		$shipment_date = date('m/d', strtotime($row['shipment_datetime'])) . "(" . $week_str[date('w', strtotime($row['shipment_datetime']))] . ")";
		$arrival_date = date('m/d', strtotime($row['arrival_datetime'])) . "(" . $week_str[date('w', strtotime($row['arrival_datetime']))] . ")";
		$Integration_flg = "×";
		if ($row["Integration_flg"] == '1') $Integration_flg = "〇";
		$remarks_exist = "×";
		if ($row["remarks"] != null && $row["remarks"] != '') $remarks_exist = "〇";

		$html .= '
							<div class="searchResultListRow">
								<div class="searchResultItem itemWid1">
									<a href="' . $detail_url_base . $row[$id_col_name] . '">' . $this->common_logic->zero_padding($row[$id_col_name], 8) . '</a>
								</div>
								<div class="searchResultItem itemWid1">
									' . $shipment_date . '<br>
									' . $row['shipment_time'] . '時
								</div>
								<div class="searchResultItem itemWid3">
									' . $row['shipment_pref'] . '' . $row['shipment_addr1'] . '' . $row['shipment_addr2'] . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $arrival_date . '<br>
									' . $row['arrival_time'] . '時
								</div>
								<div class="searchResultItem itemWid3">
									' . $row['arrival_pref'] . '' . $row['arrival_addr1'] . '' . $row['arrival_addr2'] . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $mini_str['car_type'][$row['hope_car_type']] . '
								</div>
								<div class="searchResultItem itemWid2">
									' . $mini_str['weight'][$row['hope_weight']] . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $row['baggage_detail'] . '
								</div>
								<div class="searchResultItem itemWid1">
									￥' . number_format($row['hope_fee']) . '
								</div>
								<div class="searchResultItem itemWid2">
									' . $Integration_flg . '
								</div>
								<div class="searchResultItem itemWid2">
									' . $remarks_exist . '
								</div>
							</div>
						';
		return $html;
	}

	function create_baggage_html($row, $mini_str)
	{
		$week_str = array("日", "月", "火", "水", "木", "金", "土");
		$id_col_name = "baggage_id";
		$detail_url_base = "luggage_detail.php?bid=";
		$html = '';

		$shipment_date = date('m/d', strtotime($row['shipment_date'])) . "(" . $week_str[date('w', strtotime($row['shipment_date']))] . ")";
		$arrival_date = date('m/d', strtotime($row['arrival_date'])) . "(" . $week_str[date('w', strtotime($row['arrival_date']))] . ")";
		$Integration_flg = "×";
		if ($row["Integration_flg"] == '1') $Integration_flg = "〇";
		$remarks_exist = "×";
		if ($row["remarks"] != null && $row["remarks"] != '') $remarks_exist = "〇";


		$btn = '';
		if ($_SESSION['cclue']['login']['member_id'] != $row["member_id"]) {
			$btn = '<button type="button" name="agree_btn" value="' . $row[$id_col_name] . '" t="baggage" class="btnCancel">
				成約
			</button>';
		}


		$html .= '
							<div class="searchResultListRow">
								<div class="searchResultItem itemWid1">
									<a href="' . $detail_url_base . $row[$id_col_name] . '">' . $this->common_logic->zero_padding($row[$id_col_name], 8) . '</a><br>
									' . $btn . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $shipment_date . '<br>
									' . $row['shipment_time'] . '時
								</div>
								<div class="searchResultItem itemWid3">
									' . $row['shipment_pref'] . '' . $row['shipment_addr1'] . '' . $row['shipment_addr2'] . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $arrival_date . '<br>
									' . $row['arrival_time'] . '時
								</div>
								<div class="searchResultItem itemWid3">
									' . $row['arrival_pref'] . '' . $row['arrival_addr1'] . '' . $row['arrival_addr2'] . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $mini_str['car_type'][$row['hope_car_type']] . '
								</div>
								<div class="searchResultItem itemWid2">
									' . $mini_str['weight'][$row['hope_weight']] . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $row['baggage_detail'] . '
								</div>
								<div class="searchResultItem itemWid1">
									￥' . number_format($row['hope_fee']) . '
								</div>
								<div class="searchResultItem itemWid2">
									' . $Integration_flg . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $this->conv_company($row['name']) . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $row['resp_name'] . '
								</div>
								<div class="searchResultItem itemWid4">
									' . $row['tel'] . '
								</div>
								<div class="searchResultItem itemWid2">
									' . $remarks_exist . '
								</div>
							</div>';
		return $html;
	}

	function create_agreement_html($row, $mini_str)
	{
		$week_str = array("日", "月", "火", "水", "木", "金", "土");
		$id_col_name = "agreement_id";
		$detail_url_base = "agreement_detail.php?aid=";
		$html = '';

		$create_date = date('m/d', strtotime($row['created_at'])) . "(" . $week_str[date('w', strtotime($row['created_at']))] . ")";
		$shipment_date = date('m/d', strtotime($row['shipment_date'])) . "(" . $week_str[date('w', strtotime($row['shipment_date']))] . ")";
		$arrival_date = date('m/d', strtotime($row['arrival_date'])) . "(" . $week_str[date('w', strtotime($row['arrival_date']))] . ")";
		// 		$Integration_flg = "×";
		// 		if($row["Integration_flg"] == '1')$Integration_flg = "〇";
		// 		$remarks_exist = "×";
		if ($row["remarks"] != null && $row["remarks"] != '') $remarks_exist = "〇";


		// 		$btn = '';
		// 		if($_SESSION['cclue']['login']['member_id'] != $row["member_id"]){
		// 			$btn = '<button type="button" name="" value="'.$row[$id_col_name].'" t="baggage" class="btnCancel">
		// 				成約
		// 			</button>';
		// 		}
		$status = "取引中";


		$html .= '
								<div class="searchResultListRow">
									<div class="searchResultItem itemWid1">
										' . $create_date . '<br>
										午前
									</div>
									<div class="searchResultItem itemWid1">
										<a href="' . $detail_url_base . $row[$id_col_name] . '">' . $this->common_logic->zero_padding($row[$id_col_name], 8) . '</a><br>
									</div>
									<div class="searchResultItem itemWid3">
										' . $status . '
									</div>
									<div class="searchResultItem itemWid1">
										' . $row['name'] . '
									</div>
									<div class="searchResultItem itemWid1">
										' . $shipment_date . '<br>
										午前
									</div>
									<div class="searchResultItem itemWid1">
										広島県福山市
									</div>
									<div class="searchResultItem itemWid1">
										' . $arrival_date . '<br>
										午前
									</div>
									<div class="searchResultItem itemWid1">
										広島県福山市
									</div>
									<div class="searchResultItem itemWid1">
										￥124,000
									</div>
									<div class="searchResultItem itemWid1">
										￥124,000
									</div>
									<div class="searchResultItem itemWid1">
										2019<br>
										01-31
									</div>
									<div class="searchResultItem itemWid3">
										ー
									</div>
									<div class="searchResultItem itemWid2">
										〇
									</div>
								</div>

';
		return $html;
	}

	function conv_company($name)
	{
		$search = array("株式会社", "有限会社");

		return str_replace($search, "", $name);
	}


	private function getIndexAgreementTemplate()
	{
		$template = '
                <div class="##AGREEMENT_CLASS##">
        ##AGREEMENT_TYPE##
				</div>
				<p class="leftMenuInfoTxt1">
        ##SHIPMENT_PLACE##→##ARRIVAL_PLACE##<br>
        ##TRUCK_TYPE## ￥##MONEY##
        </p>
        ';
		return $template;
	}

	private function getTopAgreementTemplate()
	{
		$template = '
	    									<div class="topInfoList">
												<a href="">
													<div class="##AGREEMENT_CLASS##">
														荷物登録
													</div>
													<p class="leftMenuInfoTxt1">
														##SHIPMENT_PLACE##→##ARRIVAL_PLACE##
													</p>
													<div class="leftMenuListInner2">
														##ARRIVAL_DATETIME##
														<img alt="" src="assets/img/menu_arw.png">
														##SHIPMENT_DATETIME##
													</div>
												</a>
											</div>

	    ';
	}
}
