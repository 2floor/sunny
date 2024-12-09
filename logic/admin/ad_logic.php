<?php
require_once __DIR__ . '/../../model/t_ad_model.php';
require_once __DIR__ . '/../../logic/common/common_logic.php';


class ad_logic
{
	private $t_ad_model;
	private $common_logic;

	/**
	 * コンストラクタ
	 */
	public function __construct()
	{
		$this->t_ad_model = new t_ad_model();
		$this->common_logic = new common_logic();
	}

	/**
	 * 初期HTML生成
	 */
	public function create_data_list($params, $search_select = null)
	{

		$this->common_logic->create_table_dump('t_ad');

		$sqlAdd = $this->common_logic->create_where($search_select);

		$page_title = 'サンプル';

		//総件数取得
		$result_cnt = $this->t_ad_model->get_ad_list_cnt($sqlAdd);

		$all_cnt = $result_cnt[0]['cnt'];
		$pager_cnt = ceil($all_cnt / $params[2]);
		$offset = ($params[1] - 1) * $params[2];

		$result_ad = $this->t_ad_model->get_ad_list($offset, $params[2], $sqlAdd);

		$return_html = "";
		$back_color = 1;
		$cnt = $offset;
		for ($i = 0; $i < count($result_ad); $i++) {
			$row = $result_ad[$i];

			$cnt++;
			$edit_html = '&nbsp;';

			$ad_id = $this->common_logic->zero_padding($row['ad_id']);

			//各データをhtmlに変換







			//画像表示処理
			$img_tag_html = '<img src="../assets/admin/img/nophoto.png" style="height:50px">';
			$nmage_list = array();
			if (strpos($row['img'], ',') !== false && ($row['img'] != null && $row['img'] != '')) {
				// 'abcd'のなかに'bc'が含まれている場合
				$img_tag_html = '';
				$nmage_list = explode(',', $row['img']);

				for ($n = 0; $n < count($nmage_list); $n++) {
					$img_tag_html .= '<img src="../upload_files/ad/' . $nmage_list[$n] . '" style="height:50px">';
				}
			} else if ($row['img'] != null && $row['img'] != '') {
				$img_tag_html = '<img src="../upload_files/ad/' . $row['img'] . '" style="height:50px">';
			}

			//動画
			if ($row['movie'] != null && $row['movie'] != "") {
				$movie = '<a  href="#modal" class="check_movie" ad_id="' . $row['ad_id'] . '">有り</a>';
			} else {
				$movie = '無し';
			}


			//削除フラグ
			$del_color = "";
			$edit_html_a = "<a herf='javascript:void(0);' class='edit clr1' name='edit_" . $row['ad_id'] . "' value='" . $row['ad_id'] . "'><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a><br>";
			$del_html = "有効";
			if ($row['del_flg'] == 1) {
				$del_color = "color:#d3d3d3";
				$del_html = "削除";
				$edit_html_a .= "<a herf='javascript:void(0);' class='recovery clr2' name='recovery_" . $row['ad_id'] . "' value='" . $row['ad_id'] . "' ><i class=\"fa fa-undo\" aria-hidden=\"true\"></i></a><br>";
			} else {
				$edit_html_a .= "<a herf='javascript:void(0);' class='del clr2' name='del_" . $row['ad_id'] . "' value='" . $row['ad_id'] . "'><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a><br>";
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
			if ($row['public_flg'] == 1) {
				$public_html = "非公開";
				$edit_html_b .= "<a herf='javascript:void(0);' class='release btn btn-default waves-effect w-md btn-xs' name='release_" . $row['ad_id'] . "' value='" . $row['ad_id'] . "'>非公開</a>";
			} else {
				$edit_html_b .= "<a herf='javascript:void(0);' class='private btn btn-custom waves-effect w-md btn-xs ' name='private_" . $row['ad_id'] . "' value='" . $row['ad_id'] . "'>公開</a>";
			}

			$created_at = $row['created_at'];
			$diff = strtotime(date('YmdHis')) - strtotime($created_at);
			if ($diff < 60) {
				$time = $diff;
				$created_at = $time . '秒前';
			} elseif ($diff < 60 * 60) {
				$time = round($diff / 60);
				$created_at = $time . '分前';
			} elseif ($diff < 60 * 60 * 24) {
				$time = round($diff / 3600);
				$created_at = $time . '時間前';
			}

			$updated_at = $row['updated_at'];
			$diff = strtotime(date('YmdHis')) - strtotime($updated_at);
			if ($diff < 60) {
				$time = $diff;
				$updated_at = $time . '秒前';
			} elseif ($diff < 60 * 60) {
				$time = round($diff / 60);
				$updated_at = $time . '分前';
			} elseif ($diff < 60 * 60 * 24) {
				$time = round($diff / 3600);
				$updated_at = $time . '時間前';
			}




			$return_html .= "
					<tr " . $back_color_html . ">
						<td class='count_no'>" . $cnt . "</td>
						<td>" . $row['ad_id'] . "</td>
						<td>" . $row['type'] . "</td>
						<td>" . $row['title'] . "</td>
						<td>" . $img_tag_html . "</td>
						<td>" . $created_at . "</td>
						<td>" . $updated_at . "</td>
						<td>
							$edit_html_a
						</td>
						<td>
							$edit_html_b
						</td>
					</tr>
";
			$back_color++;

			if ($back_color >= 3) {
				$back_color = 1;
			}
		}
		// }

		//ページャー部分HTML生成
		$pager_html = '<li><a href="javascript:void(0)" class="page prev" pager_type="prev">prev</a></li>';
		for ($i = 0; $i < $pager_cnt; $i++) {
			$disp_cnt = $i + 1;

			if ($i == 0) {
				$pager_html .= '<li><a href="javascript:void(0)" class="page num_link" num_link="true" disp_id="' . $disp_cnt . '">' . $disp_cnt . '</a></li>';
			} else {
				$pager_html .= '<li><a href="javascript:void(0)" class="page num_link" num_link="true" disp_id="' . $disp_cnt . '">' . $disp_cnt . '</a></li>';
			}
		}
		$pager_html .= '<li><a href="javascript:void(0)" class="page next" pager_type="next">next</a></li>';

		return array(
			"entry_menu_list_html" => $admin_menu_list_html,
			"list_html" => $return_html,
			"pager_html" => $pager_html,
			'page_cnt' => $pager_cnt,
			'all_cnt' => $all_cnt,
			'disp_all' => $disp_all,
		);
	}


	/**
	 * 新規登録処理
	 */
	public function entry_new_data($params)
	{

		$result = $this->t_ad_model->entry_ad($params);
		return true;
	}

	/**
	 * 取得処理
	 */
	public function get_detail($ad_id)
	{
		$result = $this->t_ad_model->get_ad_detail($ad_id);

		return  $result[0];
	}

	/**
	 * 編集更新処理
	 * @param unknown $post
	 */
	public function update_detail($params)
	{

		$result = $this->t_ad_model->update_ad($params);
		return true;
	}

	/**
	 * 有効化処理
	 *
	 * @param unknown $id
	 */
	public function recoveryl_func($id)
	{
		$this->t_ad_model->recoveryl_ad($id);
	}


	/**
	 * 削除処理
	 *
	 * @param unknown $id
	 */
	public function del_func($id)
	{
		$this->t_ad_model->del_ad($id);
	}

	/**
	 * 非公開化処理
	 *
	 * @param unknown $id
	 */
	public function private_func($id)
	{
		$this->t_ad_model->private_ad($id);
	}


	/**
	 * 公開処理
	 *
	 * @param unknown $id
	 */
	public function release_func($id)
	{
		$this->t_ad_model->release_ad($id);
	}
}
