<?php

require_once __DIR__ . '/../../logic/admin/base_logic.php';

use App\Models\AutoRank;
use App\Models\Cancer;
use Carbon\Carbon;

class auto_rank_logic extends base_logic
{
    public function getModel() {
        return AutoRank::class;
    }

    public function create_data_list($params, $search_select = null)
    {
        $data = $this->getListData($params, $search_select, ['cancer']);
        $all_cnt = $data['total'];
        $list = $data['data'];

        $return_html = "";
        $back_color = 1;
        $cnt = ($params[0] * ($params[1] - 1));

        for($i = 0; $i < count ($list ?? []); $i ++) {
            $row = $list[$i];
            $cnt ++;

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
                AutoRank::STATUS_IN_PROCESSING => "class='bg-processing'",
                AutoRank::STATUS_COMPLETED => "class='bg-completed'",
                AutoRank::STATUS_TIMEOUT => "class='bg-timeout'",
                default => '',
            };

            $return_html .= "
					<tr " . $back_color_html . ">
						<td class='count_no'>" . $cnt . "</td>
						<td>" . $row['id'] . "</td>
						<td>" . (AUTO_RANK_TYPE[$row['auto_type']] ?? '') . "</td>
						<td>" . (($row['auto_type'] == AutoRank::AUTO_TYPE_RANK) ?  (RANK_DATA_TYPE[$row['data_type']] ?? '') : (AVG_RANK_DATA_TYPE[$row['data_type']] ?? '')) . "</td>
						<td>" . $row['cancer']['cancer_type'] . "</td>
						<td>" . $row['year'] . "</td>
						<td>" . $status . "</td>
						<td>" . $create_at . "</td>
						<td>" . $completed_time . "</td>
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
}