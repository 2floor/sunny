<?php

require_once __DIR__ . '/../../logic/admin/base_logic.php';

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;

class user_logic extends base_logic
{
    public function getModel() {
        return User::class;
    }

    public function create_data_list($params, $search_select = null)
    {
        $data = $this->getListData($params, $search_select, ['role']);
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
						<td>" . $row['id'] . "</td>
						<td>" . $row['username'] . "</td>
						<td>" . $row['name'] . "</td>
						<td>" . ($row['role']['role_name'] ?? '') . "</td>
                        <td>" . $row['email'] . "</td>
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

    public function get_role_list()
    {
        return Role::select(['id', 'role_name'])->get();
    }
    public function get_role_by_id($id)
    {
        return Role::find($id);
    }

    public function check_existing_username($username, $id = null)
    {
        $user = User::where('username', $username)->first();

        if ($id && $user && $user->id == $id) {
            return false;
        } else if ($user) {
            return true;
        } else {
            return false;
        }
    }

    public function check_existing_email($email, $id = null)
    {
        $user = User::where('email', $email)->first();

        if ($id && $user && $user->id == $id) {
            return false;
        } else if ($user) {
            return true;
        } else {
            return false;
        }
    }
}