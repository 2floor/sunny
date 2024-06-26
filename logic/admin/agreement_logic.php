<?php
require_once __DIR__ . '/../../model/t_agreement_model.php';
require_once __DIR__ . '/../../logic/common/common_logic.php';


class agreement_logic {
	private $t_agreement_model;
	private $common_logic;

	/**
	 * コンストラクタ
	 */
	public function __construct() {
		$this->t_agreement_model= new t_agreement_model();
		$this->common_logic = new common_logic ();
	}

	/**
	 * 初期HTML生成
	 */
	public function create_data_list($params, $search_select = null, $where_m){

		$this->common_logic->create_table_dump('t_agreement');

		$sqlAdd = $this->common_logic->create_where($search_select);
		if($where_m['wh_m'] != null && $where_m['wh_m'] != ''){
		    $add = ($sqlAdd['where'] != null && $sqlAdd['where'] != '') ? " AND ": " WHERE " ;
		    $sqlAdd['where'] .= $add . " (orderer_id = ? OR received_id = ? )";
		    array_push($sqlAdd['whereParam'], $where_m['wh_m'], $where_m['wh_m']);
		}

		if($where_m['wh_mo'] != null && $where_m['wh_mo'] != ''){
		    $add = ($sqlAdd['where'] != null && $sqlAdd['where'] != '') ? " AND ": " WHERE " ;
		    $sqlAdd['where'] .= $add . " agreement_datetime between ? and ?  ";
		    array_push($sqlAdd['whereParam'], date("Y-m-d 00:00:00", strtotime($where_m['wh_mo'] . "-01")), date("Y-m-d 23:59:59", strtotime(" last day of " . $where_m['wh_mo'] . " ")));
		}

		$page_title = 'サンプル';

		//総件数取得
		$result_cnt = $this->t_agreement_model->get_agreement_list_cnt($sqlAdd);

		$all_cnt = $result_cnt[0]['cnt'];
		$pager_cnt = ceil($all_cnt / $params[2]);
		$offset = ($params[1] - 1) * $params[2];

		$result_agreement = $this->t_agreement_model->get_agreement_list($offset, $params[2],$sqlAdd);

		$return_html = "";
		$back_color = 1;
		$cnt = $offset;
		for($i = 0; $i < count ( $result_agreement ); $i ++) {
			$row = $result_agreement [$i];

			$cnt ++;
			$edit_html = '&nbsp;';

			$agreement_id = $this->common_logic->zero_padding ( $row ['agreement_id'] );

			//各データをhtmlに変換







			//画像表示処理
			$img_tag_html = '<img src="../assets/admin/img/nophoto.png" style="height:50px">';
			$nmage_list = array ();
			if (strpos ( $row ['image'], ',' ) !== false && ($row ['image'] != null && $row ['image'] != '')) {
				// 'abcd'のなかに'bc'が含まれている場合
				$img_tag_html = '';
				$nmage_list = explode ( ',', $row ['image'] );

				for($n = 0; $n < count ( $nmage_list ); $n ++) {
					$img_tag_html .= '<img src="../upload_files/agreement/' . $nmage_list [$n] . '" style="height:50px">';
				}
			} else if ($row ['image'] != null && $row ['image'] != '') {
				$img_tag_html = '<img src="../upload_files/agreement/' . $row ['image'] . '" style="height:50px">';
			}

			//動画
			if ($row['movie'] != null && $row['movie'] != ""){
				$movie = '<a  href="#modal" class="check_movie" agreement_id="'. $row['agreement_id'] .'">有り</a>';
			}else{
				$movie = '無し';
			}


			//削除フラグ
			$del_color = "";
			$edit_html_a = "<a herf='javascript:void(0);' class='edit clr1' name='edit_" . $row ['agreement_id'] . "' value='" . $row ['agreement_id'] . "'><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a><br>";
			$del_html = "有効";
			if ($row ['del_flg'] == 1) {
				$del_color = "color:#d3d3d3";
				$del_html = "削除";
				$edit_html_a .= "<a herf='javascript:void(0);' class='recovery clr2' name='recovery_" . $row ['agreement_id'] . "' value='" . $row ['agreement_id'] . "' ><i class=\"fa fa-undo\" aria-hidden=\"true\"></i></a><br>";
			} else {
				$edit_html_a .= "<a herf='javascript:void(0);' class='del clr2' name='del_" . $row ['agreement_id'] . "' value='" . $row ['agreement_id'] . "'><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a><br>";
			}

			if ($back_color == 2) {
				$back_color_html = "style='background: #f7f7f9; " . $del_color . "'";
				$back_color_bottom_html = "style='background: #f7f7f9; border-bottom:solid 2px #d0d0d0;'";
			} else {
				$back_color_html = "style='background: #ffffff; " . $del_color . "'";
				$back_color_bottom_html = "style='background: #ffffff; border-bottom:solid 2px #d0d0d0;'";
			}

			$edit_html_b = '';
			$public_html = "公開";
			if ($row ['public_flg'] == 1) {
				$public_html = "非公開";
				$edit_html_b .= "<a herf='javascript:void(0);' class='release btn btn-default waves-effect w-md btn-xs' name='release_" . $row ['agreement_id'] . "' value='" . $row ['agreement_id'] . "'>非公開</a>";
			} else {
				$edit_html_b .= "<a herf='javascript:void(0);' class='private btn btn-custom waves-effect w-md btn-xs ' name='private_" . $row ['agreement_id'] . "' value='" . $row ['agreement_id'] . "'>公開</a>";
			}

			$create_at = $row['create_at'];
			$diff = strtotime(date('YmdHis')) - strtotime($create_at);
			if($diff < 60){
				$time = $diff;
				$create_at = $time . '秒前';
			}elseif($diff < 60 * 60){
				$time = round($diff / 60);
				$create_at = $time . '分前';
			}elseif($diff < 60 * 60 * 24){
				$time = round($diff / 3600);
				$create_at = $time . '時間前';
			}

			$update_at = $row['update_at'];
			$diff = strtotime(date('YmdHis')) - strtotime($update_at);
			if($diff < 60){
				$time = $diff;
				$update_at = $time . '秒前';
			}elseif($diff < 60 * 60){
				$time = round($diff / 60);
				$update_at = $time . '分前';
			}elseif($diff < 60 * 60 * 24){
				$time = round($diff / 3600);
				$update_at = $time . '時間前';
			}

			$received = $this->get_member_name($row['received_id']);
			$orderer = $this->get_member_name($row['orderer_id']);
			$sum = $row['hightway_fee'] + (int)$row['delivery_fee'];




			$return_html .= "
					<tr " . $back_color_html . ">
						<td class='count_no'>" . $cnt . "</td>
						<td>" . $row['agreement_id'] . "</td>
						<td>" . $received . "</td>
						<td>" . $orderer . "</td>
						<td>運賃：" . number_format($row['delivery_fee']) . "円<br>高速：" . number_format((int)$row['hightway_fee']) . "円<br>　計：" . number_format($sum) . "円</td>
						<td>" . $create_at . "</td>
						<td>" . $update_at . "</td>
						<td>
							$edit_html_a
						</td>
					</tr>
";
			$back_color ++;

			if ($back_color >= 3) {
				$back_color = 1;
			}
		}
		// }

		//ページャー部分HTML生成
		$pager_html = '<li><a href="javascript:void(0)" class="page prev" pager_type="prev">prev</a></li>';
		for ($i = 0; $i < $pager_cnt; $i++) {
			$disp_cnt = $i+1;

			if ($i == 0) {
				$pager_html .= '<li><a href="javascript:void(0)" class="page num_link" num_link="true" disp_id="'.$disp_cnt.'">'.$disp_cnt.'</a></li>';
			} else {
				$pager_html .= '<li><a href="javascript:void(0)" class="page num_link" num_link="true" disp_id="'.$disp_cnt.'">'.$disp_cnt.'</a></li>';
			}
		}
		$pager_html .= '<li><a href="javascript:void(0)" class="page next" pager_type="next">next</a></li>';

		return array (
				"entry_menu_list_html" => $admin_menu_list_html,
				"list_html" => $return_html,
				"pager_html" => $pager_html,
				'page_cnt' => $pager_cnt,
				'all_cnt' => $all_cnt,
				'disp_all' => $disp_all,
		);
	}

	public function get_member_name($member_id){
	    $member = $this->common_logic->select_logic("select `name` from t_member where member_id = ? ", array($member_id));
	    return $member[0]['name'];

	}


	/**
	 * 新規登録処理
	 */
	public function entry_new_data($params) {


		$result = $this->t_agreement_model->entry_agreement( $params );
		return true;
	}

	/**
	 * 取得処理
	 */
	public function get_detail($agreement_id ){

		$result = $this->t_agreement_model->get_agreement_detail ( $agreement_id );

		$result[0]['received'] = $this->get_member_name($result[0]['received_id']);
		$result[0]['orderer'] = $this->get_member_name($result[0]['orderer_id']);

		return  $result [0];
	}

	/**
	 * 編集更新処理
	 * @param unknown $post
	 */
	public function update_detail($params){



		$result = $this->t_agreement_model->update_agreement($params);
		return true;
	}

	/**
	 * 有効化処理
	 *
	 * @param unknown $id
	 */
	public function recoveryl_func($id) {
		$this->t_agreement_model->recoveryl_agreement ( $id );
	}


	/**
	 * 削除処理
	 *
	 * @param unknown $id
	 */
	public function del_func($id) {
		$this->t_agreement_model->del_agreement ( $id );
	}

	/**
	 * 非公開化処理
	 *
	 * @param unknown $id
	 */
	public function private_func($id) {
		$this->t_agreement_model->private_agreement ( $id );
	}


	/**
	 * 公開処理
	 *
	 * @param unknown $id
	 */
	public function release_func($id) {
		$this->t_agreement_model->release_agreement ( $id );
	}

}