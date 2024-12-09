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

	public function getNewAgreementHTML($limit = 5)
	{
		$html = '';
		$data = $this->getNewAgreement($limit);
		$template = '
                <div class="##AGREEMENT_CLASS##">
        ##AGREEMENT_TYPE##
				</div>
				<p class="leftMenuInfoTxt1">
        ##SHIPMENT_PLACE##→##ARRIVAL_PLACE##<br>
        ##TRUCK_TYPE## ##MONEY##
        </p>
        ';

		$pattern = array(
			'/##AGREEMENT_CLASS##/',
			'/##AGREEMENT_TYPE##/',
			'/##SHIPMENT_PLACE##/',
			'/##ARRIVAL_PLACE##/',
			'/##TRUCK_TYPE##/',
			'/##MONEY##/',
			'/##SHIPPMENT_DATETIME##/',
			'/##ARRIVAL_DATETIME##/',
			'/##TARGET_HREF##/',
		);

		$type_arr = array(
			0 => 'leftMenuInfoBatchNimotsu',
			1 => 'leftMenuInfoBatchSyaryou',
		);
		$type_name_arr = array(
			0 => '荷物登録',
			1 => '車両登録',
		);


		for ($i = 0; $i < count((array)$data); $i++) {

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
			} else {
				$truck_type = $this->getTruckTypeBaggage($data[$i]['bt_id']);
			}

			if (isset($data[$i]['shippment_place'])) {
				$shipment_place = $data[$i]['shippment_place'];
			} else {
				$shipment_place = $data[$i]['shipment_pref'] . $data[$i]['shipment_addr1'];
			}

			if (!isset($data[$i]['arrival_place'])) {
				$arrival_place  = $data[$i]['arrival_pref'] . $data[$i]['arrival_addr1'];
			} else {
				$arrival_place  = $data[$i]['arrival_place'];
			}

			$money = $data[$i]['delivery_fee']/* +$data[$i]['hightway_fee'] */;
			if ($data[$i]['delivery_fee'] == 0) {
				$money = '要相談';
			}
			$shippment_datetime = $this->getTimeTopFormat($data[$i]['shippment_datetime']);
			$arrival_datetime = $this->getTimeTopFormat($data[$i]['arrival_datetime']);;

			$target_href = '';
			if (isset($data[$i]['baggage_id']) && $data[$i]['baggage_id'] !== 'dummy') {
				$target_href = "./luggage_detail.php?bid={$data[$i]['baggage_id']}";
			} else if (isset($data[$i]['truck_id']) && $data[$i]['truck_id'] !== 'dummy') {
				$target_href = "./truck_detail.php?tid={$data[$i]['truck_id']}";
			}


			$replacement = array(
				$class,
				$type_name,
				$this->displayPlaceFormat($shipment_place),
				$this->displayPlaceFormat($arrival_place),
				$truck_type,
				$money,
				$shippment_datetime,
				$arrival_datetime,
				$target_href,
			);
			$html .= preg_replace($pattern, $replacement, $template);
		}

		return $html;
	}


	/**
	 * topリスト用 日付フォーマット変換
	 *  例） 9月5日午後
	 * @param $datetime
	 * @return string
	 */
	private function getTimeTopFormat($datetime)
	{
		list($date, $time) = explode(' ', $datetime);
		list($hour, $minute, $second) = explode(':', $time);
		if ((int)$hour >= 12) {
			$result = '午後';
		} else {
			$result = '午前';
		}
		return date('n月j日', strtotime($date));
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

	private function getTruckTypeBaggage($baggage_id)
	{
		$code = null;
		$result = '';
		$data = $this->common_logic->select_logic("SELECT * FROM t_baggage WHERE baggage_id = " . $baggage_id, array());
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
			$where = " WHERE `member_id` = ?  and del_flg = 0  AND `shipment_date` >= CURDATE() ";
			$where_param = array($_SESSION['cclue']['login']['member_id']);
		} elseif ($type == 'baggage_me') {
			//自身の依頼した荷物
			$table = "`t_baggage`";
			$id_col_name = "baggage_id";
			$detail_url_base = "luggage_detail.php?bid=";
			$where = " WHERE `member_id` = ?  and del_flg = 0  AND `shipment_date` >= CURDATE() ";
			$where_param = array($_SESSION['cclue']['login']['member_id']);
		} elseif ($type == 'truck') {
			//トラック検索
			$table = "`t_truck`";
			$id_col_name = "truck_id";
			$detail_url_base = "truck_detail.php?tid=";
			$where = " INNER JOIN ( SELECT `member_id`, `name`, `tel`, `resp_name` FROM `t_member` WHERE del_flg = 0 ) AS `tm` USING(`member_id`) WHERE `del_flg` = 0 AND `public_flg` = 0 AND `fin_flg` = 0 AND `shipment_date` >= CURDATE() ";
			$add_col = ", `member_id`";
			$where_param = array();
		} elseif ($type == 'baggage') {
			//荷物検索
			$table = "`t_baggage`";
			$id_col_name = "baggage_id";
			$detail_url_base = "baggage_detail.php?tid=";
			$where = " INNER JOIN ( SELECT `member_id`, `name`, `tel`, `resp_name` FROM `t_member` WHERE del_flg = 0 ) AS `tm` USING(`member_id`) WHERE `del_flg` = 0 AND `public_flg` = 0 AND `fin_flg` = 0  AND `shipment_date` >= CURDATE() ";
			$add_col = ", `member_id`";
			$where_param = array();
		} elseif ($type == 'agreement') {
			//商談情報
			$table = "`t_agreement`";
			$id_col_name = "agreement_id";
			$detail_url_base = "javascript:void(0);"; //"agreement_detail.php?tid=";
			$where = " INNER JOIN ( SELECT `member_id`, `name` AS `received_name`, `tel` AS `received_tel`, `resp_name` AS `received_resp_name` FROM `t_member` WHERE del_flg = 0 ) AS `tm` ON `tm`.`member_id` = `t_agreement`.`received_id` INNER JOIN ( SELECT `member_id` , `name` AS `ordere_name`, `tel` AS `ordere_tel`, `resp_name` AS `ordere_resp_name` FROM `t_member` WHERE del_flg = 0 ) AS `tm_o` ON `tm_o`.`member_id` = `t_agreement`.`orderer_id` WHERE (`orderer_id` = ?  OR `received_id` = ? ) ";
			// 			$add_col = ", `member_id`";
			$where_param = array($_SESSION['cclue']['login']['member_id'], $_SESSION['cclue']['login']['member_id']);
		} elseif ($type == 'evaluation') {
			//商談情報
			$table = "`t_evaluation`";
			$id_col_name = "evaluation_id";
			$detail_url_base = "javascript:void(0);"; //"agreement_detail.php?tid=";
			$where = " WHERE `del_flg` = 0 and for_id = ? ";
			$where_param = array($get['mid']);
		}

		$wp = $this->create_where($get, $table, $type);




		if ($type == 'agreement') {
			$wp["where"] = str_replace('`shipment_date`', ' SUBSTRING(shippment_datetime, 1 , 10) ', $wp["where"]);
			$wp["where"] = str_replace('`arrival_date`', ' SUBSTRING(arrival_datetime, 1 , 10) ', $wp["where"]);
			$wp["where"] = str_replace('`agreement_date`', ' SUBSTRING(agreement_datetime, 1 , 10) ', $wp["where"]);
			if ($get['company_name'] != '' || $get['company_name'] != null) {
				// $wp['where'] .= " AND ( received_id = " . $get['company_name'] . " OR orderer_id = " . $get['company_name'] . " )";
				$wp['where'] .= " AND ( received_name LIKE '%" . $get['company_name'] . "%' OR ordere_name LIKE '%" . $get['company_name'] . "%' )";
			}
		}



		//検索条件がなかったとき検索結果を0にするんだ
		if (($get == null || $get == "") && ($type == 't_evaluation' || $type == 'agreement' || $type == 'baggage' || $type == 'truck')) {
			$wp['where'] .= " AND created_at = 'y' ";
		}

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
				} elseif ($type == 'evaluation') {
					$html .= $this->create_evaluation_html($row);
				}
			}
		} else {
			if ($wp['validate']) {
				$html .= '<div class="searchResultListRow">
									データがありません
								</div>';
			} else {
				// 				$html .= '<div class="searchResultListRow" style="color: red;">
				// 									日付のフォーマットに誤りがございます。
				// 								</div>';
			}
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


	function create_where($get, $table, $type = null)
	{
		$where = "";
		$where_param = array();
		$validate = true;

		if ($get != null && $get != '') {

			$cre = $this->cclue_logic->create_search_date_def($get, true);
			$where = $cre['where'];
			$where_param = $cre['where_param'];
			$validate = $cre['validate'];




			$shipment_date_s = $get['shipment_date_s_y'];
			$shipment_date_e = $get['shipment_date_e_y'];
			$arrival_date_s = $get['arrival_date_s_y'];
			$arrival_date_e = $get['arrival_date_e_y'];


			$agreement_date_s = $get['agreement_date_s_y'];
			$agreement_date_e = $get['agreement_date_e_y'];

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

			if ($this->validateDate($agreement_date_s)) {
				$where .= " AND  ( ? < `agreement_date` ";
				array_push($where_param, $agreement_date_s);
				if ($get['agreement_date_s_t'] != null && $get['agreement_date_s_t'] != '') {
					$where .= " OR  ( `agreement_date` = ? AND ? <= CAST(`agreement_time` AS signed ) ) ";
					array_push($where_param, $agreement_date_s, $get['agreement_date_s_t']);
				} else {
					$where .= " OR `agreement_date` = ? ";
					array_push($where_param, $agreement_date_s);
				}
				$where .= " ) ";
			}


			if ($this->validateDate($agreement_date_e)) {
				$where .= " AND  ( `agreement_date` < ?";
				array_push($where_param, $agreement_date_e);
				if ($get['agreement_date_e_t'] != null && $get['agreement_date_e_t'] != '') {
					$where .= " OR  ( `agreement_date` = ? AND CAST(`agreement_time` AS signed ) <= ? ) ";
					array_push($where_param, $agreement_date_e, $get['agreement_date_e_t']);
				} else {
					$where .= " OR `agreement_date` = ? ";
					array_push($where_param, $agreement_date_e);
				}
				$where .= " ) ";
			}




			if ($get['weight'] != null && $get['weight'] != '') {

				if ($type == 'agreement') {
					$where .= " AND  FIND_IN_SET(`weight`, ?)";
				} else {
					$where .= " AND  FIND_IN_SET(`hope_weight`, ?)";
				}


				$weight = explode(",", $get['weight']);
				$f1 = false;
				$f2 = false;
				foreach ($weight as $value) {
					if ($value == '5') $f1 = true;
					if ($value == '4') $f2 = true;
				}
				if ($f1 && !$f2) $get['weight'] .= ",4";

				array_push($where_param, $get['weight']);
			}

			if ($get['car_type'] != null && $get['car_type'] != '') {

				if ($type == 'agreement') {
					$where .= " AND  FIND_IN_SET(`car_type`, ?)";
				} else {
					$where .= " AND  FIND_IN_SET(`hope_car_type`, ?)";
				}

				array_push($where_param, $get['car_type']);
			}

			if ($get['car_type'] != null && $get['car_type'] != '') {
				if ($type == 'agreement') {
					$where .= " AND  FIND_IN_SET(`car_type`, ?)";
				} else {
					$where .= " AND  FIND_IN_SET(`hope_car_type`, ?)";
				}


				array_push($where_param, $get['car_type']);
			}

			$pref_ar = array('1' => '北海道', '2' => '青森県', '3' => '岩手県', '4' => '宮城県', '5' => '秋田県', '6' => '山形県', '7' => '福島県', '8' => '茨城県', '9' => '栃木県', '10' => '群馬県', '11' => '埼玉県', '12' => '千葉県', '13' => '東京都', '14' => '神奈川県', '15' => '山梨県', '16' => '長野県', '17' => '新潟県', '18' => '富山県', '19' => '石川県', '20' => '福井県', '21' => '岐阜県', '22' => '静岡県', '23' => '愛知県', '24' => '三重県', '25' => '滋賀県', '26' => '京都府', '27' => '大阪府', '28' => '兵庫県', '29' => '奈良県', '30' => '和歌山県', '31' => '鳥取県', '32' => '島根県', '33' => '岡山県', '34' => '広島県', '35' => '山口県', '36' => '徳島県', '37' => '香川県', '38' => '愛媛県', '39' => '高知県', '40' => '福岡県', '41' => '佐賀県', '42' => '長崎県', '43' => '熊本県', '44' => '大分県', '45' => '宮崎県', '46' => '鹿児島県', '47' => '沖縄県');

			if ($get['ar_sl_pref'] != null && $get['ar_sl_pref'] != '') {
				$where_in_ar = array();
				$ar_sl_pref = explode(",", $get['ar_sl_pref']);
				foreach ($ar_sl_pref as $ar_pr) {

					if ($type == 'agreement') {
						$where_in_ar[] = " `arrival_place` LIKE ? ";
					} else {
						$where_in_ar[] = " `arrival_pref` LIKE ? ";
					}
					array_push($where_param, "%" . $pref_ar[$ar_pr] . "%");
				}
				$where .= " AND ( " . implode(" OR ", $where_in_ar) . " ) ";
			}

			if ($get['sh_sl_pref'] != null && $get['sh_sl_pref'] != '') {
				$where_in_ar = array();
				$sh_sl_pref = explode(",", $get['sh_sl_pref']);
				foreach ($sh_sl_pref as $sh_pr) {

					if ($type == 'agreement') {
						$where_in_ar[] = " `shippment_place` like ? ";
						array_push($where_param, "%" . $pref_ar[$sh_pr] . "%");
					} else {
						$where_in_ar[] = " `shipment_pref` = ? ";
						array_push($where_param, $pref_ar[$sh_pr]);
					}
				}
				$where .= " AND ( " . implode(" OR ", $where_in_ar) . " ) ";
			}

			if ($get['baggage_type'] != null && $get['baggage_type'] != '') {
				$where_in_ar = array();
				$baggage_type_ar = explode(",", $get['baggage_type']);
				foreach ($baggage_type_ar as $b_t_ar) {

					$where_in_ar[] = " `baggage_type` = ? ";
					array_push($where_param, $b_t_ar);
				}
				$where .= " AND ( " . implode(" OR ", $where_in_ar) . " ) ";
			}
		}

		return array(
			'where' => $where,
			'where_param' => $where_param,
			'validate' => $validate,
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

		$shipment_place = $this->displayPlaceFormat($row['shipment_pref'] . '' . $row['shipment_addr1']);
		$arrival_place =  $this->displayPlaceFormat($row['arrival_pref'] . '' . $row['arrival_addr1']);

		$remarks_exist = "　";
		if ($row["remarks"] != null && $row["remarks"] != '') $remarks_exist = "〇";

		$hope_fee = "￥" . number_format($row['hope_fee']);
		if ($row['hope_fee'] == 0) $hope_fee  = "要相談";

		$hightway_fee = mb_strimwidth($row['hightway_fee'], 0, 14, "...", "UTF-8");
		if ($row['hightway_fee'] == '0') $hightway_fee = "要相談";

		$html .= '
							<div class="searchResultListRow">
								<div class="searchResultItem itemWid1">
									<input type="checkbox" name="del_item" value="' . $row[$id_col_name] . '">

								</div>
								<div class="searchResultItem itemWid1">

									<a href="' . $detail_url_base . $row[$id_col_name] . '">' . $this->common_logic->zero_padding($row[$id_col_name], 8) . '</a>
									<br><a href="./truck_entry.php?cp=' . $row[$id_col_name] . '" t="truck" class="btnCancel">コピー</a>
								</div>
								<div class="searchResultItem itemWid1">
									' . $shipment_date . '<br>
									' . $this->displayTimeFormat($row['shipment_time']) . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $shipment_place . '
								</div>

								<div class="searchResultItem itemWid3">
									' . $arrival_place . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $mini_str['car_type'][$row['hope_car_type']] . '
								</div>
								<div class="searchResultItem itemWid2">
									' . $mini_str['weight'][$row['hope_weight']] . '
								</div>

								<div class="searchResultItem itemWid2">
									' . $remarks_exist . '
								</div>
								<div class="searchResultItem itemWid2">
									<a herf="javascript:void(0);" class="del clr3" name="del_11040" value="11040">
										<img src="./assets/img/delete_icon.png" alt="" class="del_icon">
										削除
									</a>
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
		$shipment_place = $this->displayPlaceFormat($row['shipment_pref'] . '' . $row['shipment_addr1']);

		$arrival_date = date('m/d', strtotime($row['arrival_date'])) . "(" . $week_str[date('w', strtotime($row['arrival_date']))] . ")";
		$arrival_place = $this->displayPlaceFormat($row['arrival_pref'] . '' . $row['arrival_addr1']);
		$remarks_exist = "　";
		if ($row["remarks"] != null && $row["remarks"] != '') $remarks_exist = "〇";

		$hope_fee = "￥" . number_format($row['hope_fee']);
		if ($row['hope_fee'] == 0) $hope_fee  = "要相談";

		$hightway_fee = mb_strimwidth($row['hightway_fee'], 0, 14, "...", "UTF-8");
		if ($row['hightway_fee'] == '0') $hightway_fee = "要相談";


		$btn = '';
		if ($_SESSION['cclue']['login']['member_id'] != $row["member_id"] && $_SESSION['cclue']['login']['etc2'] == "1") {
			$btn = '<button type="button" name="agree_btn" value="' . $row[$id_col_name] . '" t="truck" class="btnCancel">
				商談
			</button>';
		}

		if ($_SESSION['cclue']['login']['etc2'] == "0") {
			$row['name'] = "******";
			$row['resp_name'] = "******";
			$row['tel'] = "******";
			$hope_fee = "******";
		}

		$html .= '
							<div class="searchResultListRow">
							<div class="searchResultItem itemWid1">
									<a href="' . $detail_url_base . $row[$id_col_name] . '">' . $this->common_logic->zero_padding($row[$id_col_name], 8) . '</a>
								</div>

								<div class="searchResultItem itemWid1 innnerWidBasis100">
									<a href="' . $detail_url_base . $row[$id_col_name] . '" style="    font-size: 12px;
    color: #293f54;
    background: #eeeeee;
    border: none;
    border-radius: 4px;
    padding: 6px 8px;
    box-shadow: 0px 2px 0px 0px rgba(230, 230, 230, 1);
   ">詳細</a><br>
								</div>
								<div class="searchResultItem itemWid1">
									' . $btn . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $shipment_date . '<br>
									' . $this->displayTimeFormat($row['shipment_time']) . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $shipment_place . '
								</div>

								<div class="searchResultItem itemWid3">
									' . $arrival_place . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $mini_str['car_type'][$row['hope_car_type']] . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $hope_fee . '
								</div>
								<div class="searchRsesultItem itemWid1">
									' . $this->conv_company($row['name']) . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $row['resp_name'] . '
								</div>
								<div class="searchResultItem itemWid4">
									' . $row['tel'] . '
								</div>
							</div>';

		if ($_SESSION["cclue"]["login"]["etc2"] == 0) {
			$html = '
							<div class="searchResultListRow">
								<div class="searchResultItem itemWid1 innnerWidBasis100">
									<p>' . $this->common_logic->zero_padding($row[$id_col_name], 8) . '</p><br>
									' . $btn . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $shipment_date . '<br>
									' . $this->displayTimeFormat($row['shipment_time']) . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $shipment_place . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $arrival_date . '<br>
									' . $this->displayTimeFormat($row['arrival_time']) . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $arrival_place . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $mini_str['car_type'][$row['hope_car_type']] . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $hope_fee . '
								</div>
								<div class="searchRsesultItem itemWid1">
									' . $this->conv_company($row['name']) . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $row['resp_name'] . '
								</div>
								<div class="searchResultItem itemWid4">
									' . $row['tel'] . '
								</div>
							</div>';
		}

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

		$shipment_date = date('m/d', strtotime($row['shipment_date'])) . "(" . $week_str[date('w', strtotime($row['shipment_date']))] . ")";
		$arrival_date = date('m/d', strtotime($row['arrival_date'])) . "(" . $week_str[date('w', strtotime($row['arrival_date']))] . ")";
		$Integration_flg = "×";
		if ($row["Integration_flg"] == '1') $Integration_flg = "〇";
		$remarks_exist = "　";
		if ($row["remarks"] != null && $row["remarks"] != '') $remarks_exist = "〇";


		$hope_fee = "￥" . number_format($row['hope_fee']);
		if ($row['hope_fee'] == 0) $hope_fee  = "要相談";

		// 		$hightway_fee = mb_strimwidth($row['hightway_fee'], 0, 14, "...", "UTF-8");
		// 		if($row['hightway_fee'] == '0')$hightway_fee = "要相談";



		$html .= '
							<div class="searchResultListRow">
							<div class="searchResultItem itemWid1">
									<input type="checkbox" name="del_item" value="' . $row[$id_col_name] . '">

								</div>
								<div class="searchResultItem itemWid1">

									<a href="' . $detail_url_base . $row[$id_col_name] . '">' . $this->common_logic->zero_padding($row[$id_col_name], 8) . '</a>
									<br><a href="./luggage_entry.php?cp=' . $row[$id_col_name] . '" t="truck" class="btnCancel">コピー</a>
								</div>
								<div class="searchResultItem itemWid1">
									' . $shipment_date . '<br>
									' . $this->displayTimeFormat($row['shipment_time']) . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $this->displayPlaceFormat($row['shipment_pref'] . '' . $row['shipment_addr1'] . '' . $row['shipment_addr2']) . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $arrival_date . '<br>
									' . $this->displayTimeFormat($row['arrival_time']) . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $this->displayPlaceFormat($row['arrival_pref'] . '' . $row['arrival_addr1'] . '' . $row['arrival_addr2']) . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $mini_str['car_type'][$row['hope_car_type']] . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $row['baggage_detail'] . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $hope_fee . '
								</div>
								<div class="searchResultItem itemWid2">
									' . $Integration_flg . '
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
		$remarks_exist = "　";
		if ($row["remarks"] != null && $row["remarks"] != '') $remarks_exist = "〇";


		$hope_fee = "￥" . number_format($row['hope_fee']);
		if ($row['hope_fee'] == 0) $hope_fee  = "要相談";


		$btn = '';
		if ($_SESSION['cclue']['login']['member_id'] != $row["member_id"] && $_SESSION['cclue']['login']['etc2'] == "1") {
			$btn = '<button type="button" name="agree_btn" value="' . $row[$id_col_name] . '" t="baggage" class="btnCancel">
				商談
			</button>';
		}

		$shipment_time = $this->displayTimeFormat($row['shipment_time']);
		$arrival_time =  $this->displayTimeFormat($row['arrival_time']);

		if ($_SESSION['cclue']['login']['etc2'] == "0") {
			$row['name'] = "******";
			$row['resp_name'] = "******";
			$row['tel'] = "******";
			$hope_fee = "******";
		}


		$html .= '
		<div class="searchResultListRow">
		<div class="searchResultItem itemWid1">
									<a href="' . $detail_url_base . $row[$id_col_name] . '">' . $this->common_logic->zero_padding($row[$id_col_name], 8) . '</a>
								</div>


								<div class="searchResultItem itemWid1 innnerWidBasis100">
									<a href="' . $detail_url_base . $row[$id_col_name] . '" style="    font-size: 12px;
    color: #293f54;
    background: #eeeeee;
    border: none;
    border-radius: 4px;
    padding: 6px 8px;
    box-shadow: 0px 2px 0px 0px rgba(230, 230, 230, 1);
   ">詳細</a><br>
								</div>
								<div class="searchResultItem itemWid1">
									' . $btn . '
								</div>


								<div class="searchResultItem itemWid1">
									' . $shipment_date . '<br>
									' . $shipment_time . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $this->displayPlaceFormat($row['shipment_pref'] . '' . $row['shipment_addr1'] . '' . $row['shipment_addr2']) . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $arrival_date . '<br>
									' . $arrival_time . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $this->displayPlaceFormat($row['arrival_pref'] . '' . $row['arrival_addr1'] . '' . $row['arrival_addr2']) . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $mini_str['car_type'][$row['hope_car_type']] . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $row['baggage_detail'] . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $hope_fee . '
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
							</div>';


		if ($_SESSION["cclue"]["login"]["etc2"] == 0) {
			$html = '
							<div class="searchResultListRow">
								<div class="searchResultItem itemWid1">
									<p>' . $this->common_logic->zero_padding($row[$id_col_name], 8) . '</p><br>
									' . $btn . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $shipment_date . '<br>
									' . $shipment_time . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $this->displayPlaceFormat($row['shipment_pref'] . '' . $row['shipment_addr1'] . '' . $row['shipment_addr2']) . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $arrival_date . '<br>
									' . $arrival_time . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $this->displayPlaceFormat($row['arrival_pref'] . '' . $row['arrival_addr1'] . '' . $row['arrival_addr2']) . '
								</div>
								<div class="searchResultItem itemWid3">
									' . $mini_str['car_type'][$row['hope_car_type']] . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $row['baggage_detail'] . '
								</div>
								<div class="searchResultItem itemWid1">
									' . $hope_fee . '
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
							</div>';
		}
		return $html;
	}

	public function displayTimeFormat($time)
	{
		if (preg_match('/:/', $time)) {
			if ($time == '11:59') {
				return '午前';
			} elseif ($time == '23:59') {
				return '午後';
			} else {
				list($hour, $minute) = explode(':', $time);
				$hour = ($hour == '') ? '00' : $hour;
				$minute = ($minute == '') ? '00' : $minute;
				return $hour . '時' . $minute . '分';
			}
		} else {
			return $time . '時';
		}
	}
	function displayPlaceFormat($place, $length = 18)
	{
		return mb_strimwidth($place, 0, $length, '…', "UTF-8");
	}

	function create_agreement_html($row, $mini_str)
	{
		$week_str = array("日", "月", "火", "水", "木", "金", "土");
		$id_col_name = "agreement_id";
		$detail_url_base = "agreement_detail.php?aid=";
		$html = '';

		$create_date = date('m/d', strtotime($row['created_at'])) . "(" . $week_str[date('w', strtotime($row['created_at']))] . ")<br>" . $this->displayTimeFormat($row['shipment_time']);

		$shipment_time = explode(" ", $row['shippment_datetime']);
		$shipment_date = date('m/d', strtotime($row['shippment_datetime'])) . "(" . $week_str[date('w', strtotime($row['shippment_datetime']))] . ")<br>" . $this->displayTimeFormat($shipment_time[1]);

		$arrival_time = explode(" ", $row['arrival_datetime']);
		$arrival_date = date('m/d', strtotime($row['arrival_datetime'])) . "(" . $week_str[date('w', strtotime($row['arrival_datetime']))] . ")<br>" . $this->displayTimeFormat($arrival_time[1]);;
		// 		$Integration_flg = "×";
		// 		if($row["Integration_flg"] == '1')$Integration_flg = "〇";
		$remarks_exist = "×";
		if ($row["remarks"] != null && $row["remarks"] != '') $remarks_exist = "〇";


		// 		$btn = '';
		// 		if($_SESSION['cclue']['login']['member_id'] != $row["member_id"]){
		// 			$btn = '<button type="button" name="" value="'.$row[$id_col_name].'" t="baggage" class="btnCancel">
		// 				商談
		// 			</button>';
		// 		}
		$status = "商談中";

		if ($row["hightway_fee"] == 1) {
			$status = "ご成約";
		}

		if ($row["del_flg"] == 1) {
			$status = "不成約";
		}


		$delivery_fee = "￥" . number_format($row['delivery_fee']);
		if ($row['delivery_fee'] == 0) $delivery_fee = "要相談";

		$hightway_fee = mb_strimwidth($row['hightway_fee'], 0, 14, "...", "UTF-8");
		if ($row['hightway_fee'] == '0') $hightway_fee = "要相談";

		$payment_date = "";
		if ($row['payment_date'] != null && $row['payment_date'] = '') {
			$payment_date = date('Y-m-d', strtotime($row['payment_date']));
		}


		$html .= '
								<div class="searchResultListRow">
									<div class="searchResultItem itemWid1">
										' . $create_date . '
									</div>
									<div class="searchResultItem itemWid1">
										<a href="' . $detail_url_base . $row[$id_col_name] . '" class="btnCancel">詳細</a><br>
									</div>
									<div class="searchResultItem itemWid1">
										<a href="' . $detail_url_base . $row[$id_col_name] . '">' . $this->common_logic->zero_padding($row[$id_col_name], 8) . '</a><br>
									</div>
									<div class="searchResultItem itemWid3">
										' . $status . '<br>
										<a href="evaluation_entry.php?aid=' . $row[$id_col_name] . '" class="btnCancel">
											評価
										</a>
									</div>
									<div class="searchResultItem itemWid1">
										' . $row['ordere_name'] . '
									</div>
									<div class="searchResultItem itemWid1">
										' . $row['received_name'] . '
									</div>
									<div class="searchResultItem itemWid1">
										' . $shipment_date . '
									</div>
									<div class="searchResultItem itemWid1">
										' . $row['shippment_place'] . '
									</div>
									<div class="searchResultItem itemWid1">
										' . $arrival_date . '
									</div>
									<div class="searchResultItem itemWid1">
										' . $row['arrival_place'] . '
									</div>
									<div class="searchResultItem itemWid1">
										' . $delivery_fee . '
									</div>
									<div class="searchResultItem itemWid2">
										' . $remarks_exist . '
									</div>
								</div>

';
		return $html;
	}

	function create_member_html($row, $link = true)
	{

		$url = '';
		if ($row['URL'] != null && $row['URL'] != '') {
			$url = $row['URL'];
			$url = '<a href="' . $row['URL'] . '" target="_blank">' . $row['URL'] . '</a>';
		}

		$link_str = "";
		if ($link) $link_str = '<a href="evaluation_info.php?mid=' . $row['member_id'] . '" style="text-decoration: underline;">評価を見る</a>';


		$row['jigyou'] = str_replace("0", "倉庫業", $row['jigyou']);
		$row['jigyou'] = str_replace("1", "運送業", $row['jigyou']);
		$row['jigyou'] = str_replace("2", "メーカー", $row['jigyou']);
		$row['jigyou'] = str_replace("3", "その他", $row['jigyou']);
		$truck_existence = "無";
		if ($row['truck_num'] > 0) {
			$truck_existence = "有";
		}


		$html .= '<tr>
												<th>
													法人名、事業者名
												</th>
												<td>
													' . $row['name'] . '　' . $link_str . '
												</td>
											</tr>
											<tr>
												<th>
													法人名・営業所名ふりがな
												</th>
												<td>
													' . $row['name_kana'] . '
												</td>
											</tr>
											<tr>
												<th>
													住所
												</th>
												<td>
													〒' . $row['zip'] . ' ' . $row['pref'] . $row['addr'] . '
												</td>
											</tr>
											<tr>
												<th>
													担当者名
												</th>
												<td>
													' . $row['resp_name'] . '
												</td>
											</tr>
											<tr>
												<th>
													電話番号
												</th>
												<td>
													' . $row['tel'] . '
												</td>
											</tr>
											<tr>
												<th>
													メールアドレス
												</th>
												<td>
													' . $row['mail'] . '
												</td>
											</tr>

											<tr>
												<th>
													業務内容
												</th>
												<td>
													' . nl2br($row['jigyou']) . '
												</td>
											</tr>
											<tr>
												<th>
													トラック有無
												</th>
												<td>
													' . $truck_existence . '
												</td>
											</tr>
											<tr>
												<th>
													ウェブサイトURL
												</th>
												<td>
													' . $url . '
												</td>
											</tr>
											<tr>
												<th>
													登録年月
												</th>
												<td>
													' . date('Y年m月', strtotime($row['created_at'])) . '
												</td>
											</tr>';
		return $html;
	}

	function create_evaluation_html($row)
	{

		$write_member = $this->common_logic->select_logic("select * from t_member where member_id = ? ", array($row['write_member']));
		$evaluation = 'とても良い';
		if ($row['evaluation'] == '1') {
			$evaluation = 'とても悪い';
		} elseif ($row['evaluation'] == '2') {
			$evaluation = 'どちらでもない';
		}

		$html .= '<div class="luggageEntryInnerBox">
					<div class="luggageEntryTitBg">
						<h3 class="luggageEntryTit">
							評価<span>' . $evaluation . '</span>　評価者<span>' . $write_member[0]['name'] . '</span>
						</h3>
					</div>
					<div class="luggageEntryRow">
						<p>' . $row['comment'] . '</p>
					</div>
				</div>';

		return $html;
	}

	function conv_company($name)
	{
		$search = array("株式会社", "有限会社");

		return str_replace($search, "", $name);
	}
}
