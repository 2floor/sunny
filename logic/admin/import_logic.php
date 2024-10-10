<?php

require_once __DIR__ . '/../../logic/admin/base_logic.php';

use App\Models\Import;
use Carbon\Carbon;

class import_logic extends base_logic
{
    public function getModel() {
        return Import::class;
    }

    public function create_data_list($params, $search_select = null)
    {
        $data = $this->getListData($params, $search_select, [], ['import_type' => Import::IMPORT_TYPE_MAIN]);
        $all_cnt = $data['total'];
        $list = $data['data'];

        $return_html = "";
        $back_color = 1;
        $cnt = ($params[0] * ($params[1] - 1));

        for($i = 0; $i < count ($list ?? []); $i ++) {
            $row = $list[$i];
            $cnt ++;

            //削除フラグ
            $edit_html_a = "<a herf='javascript:void(0);' class='edit clr1' name='edit_" . $row ['id'] . "' value='" . $row ['id'] . "'><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a><br>";


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

            $data_type_import = IMPORT_DATA_TYPE;
            $data_type = $data_type_import[$row['data_type']] ?? '';

            $status_import = IMPORT_STATUS;
            $status = $status_import[$row['status']] ?? '';

            $back_color_html = match ($row['status']) {
                Import::STATUS_IN_PROCESSING => "style='background: #4CA4E5; color: #ffffff'",
                Import::STATUS_COMPLETED => "style='background: #35C77A; color: #ffffff'",
                Import::STATUS_ERROR_PROCESSING => "style='background: #DC3545; color: #ffffff'",
                Import::STATUS_TIMEOUT => "style='background: #b8b8c2; color: #ffffff'",
                Import::STATUS_REIMPORT => "style='background: #FF9674; color: #ffffff'",
                default => '',
            };

            $return_html .= "
					<tr " . $back_color_html . ">
						<td class='count_no'>" . $cnt . "</td>
						<td>" . $row['id'] . "</td>
						<td>" . $data_type . "</td>
						<td>" . $row['file_name'] . "</td>
						<td>" . $status . "</td>
						<td>" . $row['success'] . "</td>
						<td>" . $row['error'] . "</td>
						<td>" . $create_at . "</td>
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