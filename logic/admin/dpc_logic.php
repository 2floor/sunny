<?php

require_once __DIR__ . '/../../logic/admin/base_logic.php';

use App\Models\DPC;
use App\Models\Hospital;
use App\Models\Cancer;
use Carbon\Carbon;

class dpc_logic extends base_logic
{
    public function getModel() {
        return DPC::class;
    }

    public function create_data_list($params, $search_select = null)
    {
        $data = $this->getListData($params, $search_select, ['hospital', 'cancer']);
        $all_cnt = $data['total'];
        $list = $data['data'];

        $return_html = "";
        $back_color = 1;
        $cnt = ($params[0] * ($params[1] - 1));

        for($i = 0; $i < count ($list ?? []); $i ++) {
            $row = $list[$i];
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

//            $edit_html_b = '';
//            if ($row ['public_flg'] == 1) {
//                $edit_html_b .= "<a herf='javascript:void(0);' class='release btn btn-default waves-effect w-xs btn-xs' name='release_" . $row ['id'] . "' value='" . $row ['id'] . "'>非公開</a>";
//            } else {
//                $edit_html_b .= "<a herf='javascript:void(0);' class='private btn btn-custom waves-effect w-xs btn-xs ' name='private_" . $row ['id'] . "' value='" . $row ['id'] . "'>公開</a>";
//            }

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
						<td>" . $row['cancer']['cancer_type_dpc'] . "</td>
						<td>" . $row['hospital']['hospital_name'] . "</td>
						<td>" . $row['n_dpc'] . "</td>
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

        return array (
            "list_html" => $return_html,
            'all_cnt' => $all_cnt
        );
    }

    public function get_cancer_list()
    {
        return Cancer::select(['id', 'cancer_type', 'cancer_type_dpc'])->orderBy('order_num')->get();
    }

    public function get_hospital_list()
    {
        return Hospital::select(['id', 'hospital_code', 'hospital_name'])->orderBy('created_at', 'desc')->get();
    }

    public function get_hospital_by_id($id)
    {
        return Hospital::find($id);
    }

    public function get_cancer_by_id($id)
    {
        return Cancer::find($id);
    }

    public function getLastedYearData($num_lasted_year = 3)
    {
        return DPC::select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->limit($num_lasted_year)
            ->get();
    }

    public function getListByWhereClause($clause)
    {
        return DPC::where($clause)->get();
    }

    public function forceDelete($clause)
    {
        return DPC::where($clause)->forceDelete();
    }
}