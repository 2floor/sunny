<?php

require_once __DIR__ . '/../../logic/admin/base_logic.php';

use App\Models\SurvHospital;
use Carbon\Carbon;

class surv_hospital_logic extends base_logic
{
    public function getModel() {
        return SurvHospital::class;
    }

    public function create_data_list($params, $search_select = null){
        $data = $this->getListData($params, $search_select, ['hospital', 'cancer']);
        $all_cnt = $data['total'];
        $offset = $data['offset'];
        $pager_cnt = $data['pagerCount'];
        $hospitals = $data['data'];
        $admin_menu_list_html = $disp_all = '';

        $return_html = "";
        $back_color = 1;
        $cnt = $offset;

        for($i = 0; $i < count ($hospitals ?? []); $i ++) {
            $row = $hospitals[$i];
            $cnt ++;

            //削除フラグ
            $del_color = "";
            $edit_html_a = "<a herf='javascript:void(0);' class='edit clr1' name='edit_" . $row ['id'] . "' value='" . $row ['id'] . "'><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a><br>";

            if ($row ['del_flg'] == 1) {
                $del_color = "color:#d3d3d3";
                $edit_html_a .= "<a herf='javascript:void(0);' class='recovery clr2' name='recovery_" . $row ['id'] . "' value='" . $row ['id'] . "' ><i class=\"fa fa-undo\" aria-hidden=\"true\"></i></a><br>";
            } else {
                $edit_html_a .= "<a herf='javascript:void(0);' class='del clr2' name='del_" . $row ['id'] . "' value='" . $row ['id'] . "'><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a><br>";
            }

            if ($back_color == 2) {
                $back_color_html = "style='background: #f7f7f9; " . $del_color . "'";
            } else {
                $back_color_html = "style='background: #ffffff; " . $del_color . "'";
            }

            $edit_html_b = '';
            if ($row ['public_flg'] == 1) {
                $edit_html_b .= "<a herf='javascript:void(0);' class='release btn btn-default waves-effect w-xs btn-xs' name='release_" . $row ['id'] . "' value='" . $row ['id'] . "'>非公開</a>";
            } else {
                $edit_html_b .= "<a herf='javascript:void(0);' class='private btn btn-custom waves-effect w-xs btn-xs ' name='private_" . $row ['id'] . "' value='" . $row ['id'] . "'>公開</a>";
            }

            $create_at = Carbon::parse($row['created_at'])->format('Y-m-d H:i:s');
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

            $update_at = Carbon::parse($row['updated_at'])->format('Y-m-d H:i:s');
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

            $return_html .= "
					<tr " . $back_color_html . ">
						<td class='count_no'>" . $cnt . "</td>
						<td>" . $row['id'] . "</td>
						<td>" . $row['year'] . "</td>
						<td>" . $row['hospital']['hospital_name'] . "</td>
						<td>" . $row['cancer']['cancer_type_surv'] . "</td>
						<td>" . $row['total_num'] . "</td>
						<td>" . $row['survival_rate'] . "</td>
						<td>" . $create_at . "</td>
						<td>" . $update_at . "</td>
						<td>
							$edit_html_a
						</td>
						<td>
							$edit_html_b
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
}