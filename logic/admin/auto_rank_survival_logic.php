<?php

require_once __DIR__ . '/../../logic/admin/base_logic.php';

use App\Models\AutoRank;
use App\Models\SurvHospital;
use Carbon\Carbon;

class auto_rank_survival_logic extends base_logic
{
    public function getModel() {
        return SurvHospital::class;
    }

    public function create_data_list($params, $search_select = null, $auto_type = null)
    {
        $auto_rank_type = match ($auto_type) {
            '2' => AutoRank::AUTO_TYPE_AVG,
            default =>  AutoRank::AUTO_TYPE_RANK,
        };

        $data = $this->getListAutoRankData($params, AutoRank::DATA_TYPE_SURVIVAL, $auto_rank_type, $search_select);
        $all_cnt = $data['total'];
        $list = $data['data'];

        $return_html = "";
        $back_color = 1;
        $cnt = ($params[0] * ($params[1] - 1));

        for($i = 0; $i < count ($list ?? []); $i ++) {
            $row = $list[$i];
            $cnt ++;

            //削除フラグ
            $edit_html_a = "<a herf='javascript:void(0);' class='auto_rank clr1' value='" . $row ['cancer_id'] .','.$row ['year'] . "'><i class=\"fa fa-circle-o-notch\" aria-hidden=\"true\"></i></a><br>";

            $update_at = '';
            if ($row ['updated_at']) {
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
            }

            $completed_time = '';

            if ($row['completed_time']) {
                $completed_time = Carbon::parse($row['completed_time'])->format('Y-m-d H:i:s');
                $diff = strtotime(date('YmdHis')) - strtotime($completed_time);
                if($diff < 60){
                    $time = $diff;
                    $completed_time = $time . '秒前';
                }elseif($diff < 60 * 60){
                    $time = round($diff / 60);
                    $completed_time = $time . '分前';
                }elseif($diff < 60 * 60 * 24){
                    $time = round($diff / 3600);
                    $completed_time = $time . '時間前';
                }

            }

            $status = AUTO_RANK_STATUS[$row['status']] ?? '';

            $back_color_html = match ($row['status']) {
                1 => "class='bg-processing'",
                2 => "class='bg-timeout'",
                3 => "class='bg-error'",
                4 => "class='bg-reAuto'",
                default => "class='bg-completed'",
            };

            $return_html .= "
					<tr " . $back_color_html . ">
						<td class='count_no'>" . $cnt . "</td>
						<td>" . $row['cancer_id'] . "</td>
						<td class='cancer_type'>" . $row['cancer_type'] . "</td>
						<td>" . $row['year'] . "</td>
						<td>" . $row['total_records'] . "</td>
						<td>" . ($row['total_affect'] ?? 0) . "</td>
						<td>" . $status . "</td>
						<td>" . $update_at . "</td>
						<td>" . $completed_time . "</td>
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
}