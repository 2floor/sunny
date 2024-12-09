<?php

/**
 * Created by PhpStorm.
 * User: 2f_info
 * Date: 2019/01/29
 * Time: 14:46
 */
session_start();
require_once __DIR__ . '/../../logic/common/common_logic.php';
require_once __DIR__ . '/../../logic/front/front_disp_logic.php';

class f_list_logic
{
	private $common_logic;
	private $front_disp_logic;

	public function __construct()
	{
		$this->common_logic = new common_logic();
		$this->front_disp_logic = new front_disp_logic();
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
//		$data = $this->common_logic->select_logic("SELECT * FROM t_agreement WHERE del_flg = 0 AND hightway_fee = 1 ORDER BY create_at DESC LIMIT " . $limit, array());
		
		$data = $this->common_logic->select_logic("select * from t_agreement as a inner join t_member as b on b.member_id = a.received_id where b.del_flg = '0' and a.del_flg = '0' and a.hightway_fee = 1 ORDER BY a.create_at DESC limit " . $limit, array());
		;
		
		
		return $data;
	}
	private function getTransaction($limit = 10)
	{
		$data = $this->common_logic->select_logic(
			"SELECT * FROM (SELECT truck_id,'dummy' as baggage_id, 1 AS bt_type, hope_weight AS bt_id, CONCAT(shipment_date, ' ',CASE shipment_time WHEN '' THEN '00' ELSE shipment_time END ,':00') AS shippment_datetime, shipment_pref, shipment_addr1, CONCAT(arrival_date,' ',CASE arrival_time WHEN '' THEN '00' ELSE arrival_time END,':00') AS arrival_datetime, arrival_pref, arrival_addr1,create_at FROM t_truck WHERE del_flg = 0 ORDER BY create_at DESC LIMIT {$limit} ) as truck
             UNION ALL
            SELECT * FROM (SELECT 'dummy' as truck_id,baggage_id, 0 AS bt_type, hope_weight AS bt_id, CONCAT(shipment_date, ' ',CASE shipment_time WHEN '' THEN '00' ELSE shipment_time END,':00') AS shippment_datetime, shipment_pref, shipment_addr1, CONCAT(arrival_date,' ',CASE arrival_time WHEN '' THEN '00' ELSE arrival_time END,':00') AS arrival_datetime, arrival_pref, arrival_addr1,create_at FROM t_baggage WHERE del_flg = 0 ORDER BY create_at DESC LIMIT {$limit}) as baggage
              ORDER BY create_at DESC LIMIT {$limit}
",
			array()
		);

		return $data;
	}

	public function getNewAgreementHTML($limit = 5, $topFlg = false, $batchFlg = false)
	{
		$data =  $this->getNewAgreement($limit);
		if ($topFlg) {
			$template = $this->getTopListTemplate();
		} elseif ($batchFlg) {
			$template = $this->getIndexAgreementTemplate();
		} else {
			$template = $this->getIndexAgreementListTemplate();
		}
		return $this->makeHtml($data, $template, 5);
	}


	public function getTransactionHTML($limit = 6, $topFlg = false, $headerFlg = false)
	{
		$data =  $this->getTransaction($limit);


		if ($topFlg) {
			$template = $this->getTopListTemplate();
		} elseif ($headerFlg) {
			$template = $this->getIndexAgreementTemplate();
		} else {
			$template = $this->getIndexAgreementListTemplate();
		}
		return $this->makeHtml($data, $template, 5);
	}

	public function getBaggageTruckCnt()
	{

		$se = array("truck", "baggage");

		$data_cnt = array();
		foreach ($se as $type) {
			$table = "";
			$add_col = "";
			$order = " ORDER BY `create_at` DESC ";
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
			}
			$get = array();

			$wp = $this->front_disp_logic->create_where($get, $table);

			$where .= $wp['where'];
			$where_param = array_merge($where_param, $wp['where_param']);

			$data_cnt[$type] = $this->common_logic->select_logic("select count(`" . $id_col_name . "`) AS `cnt` " . $add_col . " from " . $table . " " . $where . " " . $order, $where_param);
		}






		return array('baggage' => $data_cnt['baggage'][0]['cnt'], 'truck' => $data_cnt['truck'][0]['cnt']);
	}

	/**
	 * @param int $limit
	 * @param bool $topFlg
	 * @return string
	 */
	private function makeHtml($data, $template, $limit = 6)
	{
		$html = '';

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
			0 => '荷物情報',
			1 => '車両情報',
		);

		$cunt = 0;
		for ($i = 0; $i < count((array)$data); $i++) {
			
			$fff = $this->common_logic->select_logic("select * from t_member where member_id = '".$data[$i]['received_id']."' and del_flg = '1'", array() );
			
			if ($fff != null) {
				continue;
			}
		
			if ($cunt > $limit) {
				break;
			}

			if ($data[$i]["agreement_id"]) {
				$type_arr = array(
					0 => 'leftMenuInfoBatchsyoudan',
					1 => 'leftMenuInfoBatchsyoudan',
				);

				$type_name_arr = array(
					0 => '商談成立',
					1 => '商談成立',
				);
			}


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
				$arrival_datetime = "";
			} else {
				if ($data[$i]['bt_id'] != null) {
					$truck_type = $this->getTruckTypeBaggage($data[$i]['bt_id']);
					$arrival_datetime = $this->getTimeTopFormat($data[$i]['arrival_datetime']);;
				} else {
					continue;
				}
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
			$template = str_replace('<img alt="" src="assets/img/menu_arw.png" style="    width: 13px;">', '発　 　', $template);
			if ($data[$i]['bt_type'] != 1) {
				$template = str_replace('発　 　', '<img alt="" src="assets/img/menu_arw.png" style="    width: 13px;">', $template);
			}
			$html .= preg_replace($pattern, $replacement, $template);
			
			$cunt++;

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
		return date('n/j', strtotime($date));
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

	private function getIndexAgreementTemplate()
	{
		$template = '
                <div class="##AGREEMENT_CLASS##">
        ##AGREEMENT_TYPE##
				</div>
				<p class="leftMenuInfoTxt1">
        ##SHIPMENT_PLACE##→##ARRIVAL_PLACE##<br>
        ##TRUCK_TYPE## ##MONEY##
        </p>
        ';
		return $template;
	}

	private function getIndexAgreementListTemplate()
	{
		$template = '
	    				<li class="indexBoxList">
							<div class="indexBoxListItem1">
								##SHIPMENT_PLACE##→##ARRIVAL_PLACE##
							</div>
							<div class="indexBoxListItem2">
								##TRUCK_TYPE##
							</div>
							<div class="indexBoxListItem3">
								##MONEY##
							</div>
							<div class="indexBoxListItem4">
								##SHIPPMENT_DATETIME##
							</div>
						</li>

	    ';

		return $template;
	}

	private function getTopListTemplate()
	{
		$template = '
	    									<div class="topInfoList">
												<a href="##TARGET_HREF##">
													<div class="##AGREEMENT_CLASS##">
														##AGREEMENT_TYPE##
													</div>
													<p class="leftMenuInfoTxt1">
														##SHIPMENT_PLACE##→##ARRIVAL_PLACE## ##TRUCK_TYPE##
													</p>
													<div class="leftMenuListInner2">
														##SHIPPMENT_DATETIME##
														<img alt="" src="assets/img/menu_arw.png" style="    width: 13px;">
														##ARRIVAL_DATETIME##
													</div>
												</a>
											</div>


	    ';

		if ($_SESSION["cclue"]["login"]["etc2"] == 0) {
			$template = '
	    									<div class="topInfoList">
												<a>
													<div class="##AGREEMENT_CLASS##">
														##AGREEMENT_TYPE##
													</div>
													<p class="leftMenuInfoTxt1">
														##SHIPMENT_PLACE##→##ARRIVAL_PLACE## ##TRUCK_TYPE##
													</p>
													<div class="leftMenuListInner2">
														##SHIPPMENT_DATETIME##
														<img alt="" src="assets/img/menu_arw.png">
														##ARRIVAL_DATETIME##
													</div>
												</a>
											</div>


	    ';
		}

		return $template;
	}
	function displayPlaceFormat($place, $length = 18)
	{
		return mb_strimwidth($place, 0, $length, '…', "UTF-8");
	}
}
