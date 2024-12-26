<?php

require_once __DIR__ . '/../../logic/admin/base_logic.php';

use App\Models\Area;
use App\Models\Hospital;
use App\Models\Cancer;
use Carbon\Carbon;

class hospital_logic extends base_logic
{
    public function getModel() {
        return Hospital::class;
    }

    public function create_data_list($params, $search_select = null)
    {
        $data = $this->getListData($params, $search_select, ['area']);
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
						<td>" . $row['hospital_code'] . "</td>
						<td>" . $row['hospital_name'] . "</td>
						<td>" . $row['area']['area_name'] . "</td>
						<td>" . $row['addr'] . "</td>
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

        return array (
            "list_html" => $return_html,
            'all_cnt' => $all_cnt
        );
    }

    public function get_cancer_list()
    {
        return Cancer::select(['id', 'cancer_type'])->orderBy('order_num')->get();
    }

    public function get_area_list()
    {
        return Area::select(['id', 'area_name', 'pref_name'])->get();
    }

    public function get_category_by_hospital_id($id)
    {
        return $this->getQueryWithoutGlobalScopes()->where('id', $id)->first()?->categories;
    }

    public function get_cancer_by_hospital_id($id)
    {
        return $this->getQueryWithoutGlobalScopes()->where('id', $id)->first()?->cancers;
    }

    public function get_hospital_by_code($code)
    {
        return $this->getQueryWithoutGlobalScopes()->where('hospital_code', $code)->first();
    }

    public function attach_cancer_data($hospital, $cancerId, $extraData = [])
    {
        return $hospital->cancers()->attach($cancerId, $extraData);
    }

    public function attach_category_data($hospital, $categoryId, $extraData = [])
    {
        return $hospital->categories()->attach($categoryId, $extraData);
    }

    public function sync_cancer_data($hospital, $data)
    {
        return $hospital->cancers()->sync($data);
    }

    public function sync_category_data($hospital, $data)
    {
        return $hospital->categories()->sync($data);
    }
}