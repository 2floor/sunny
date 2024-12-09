<?php
require_once __DIR__ . '/../../logic/admin/base_logic.php';

use App\Models\Category;
use Carbon\Carbon;

class category_logic extends base_logic
{
	public function getModel()
	{
		return Category::class;
	}

	public function get_grouped_data_list($group)
	{
		return $this->model->select('id', 'level1', 'level2', 'level3', 'order_num2', 'order_num3', 'is_whole_cancer')
			->where([
				'category_group' => $group,
			])
			->get()
			->groupBy('level1')->map(function ($items) {
				return $items->groupBy('level2')->map(function ($subItems) {
					return $subItems->groupBy('level3')->map(function ($groupedItems) {
						return $groupedItems->sortBy('order_num3')->map(function ($item) {
							return [
								'id' => $item->id,
								'level3' => $item->level3,
								'order_num2' => $item->order_num2,
								'order_num3' => $item->order_num3,
								'is_whole_cancer' => $item->is_whole_cancer,
							];
						});
					})->sortBy(function ($groupedItems) {
						return $groupedItems->first()['order_num3'];
					});
				})->sortBy(function ($subItems, $key) {
					return $subItems->first()->first()['order_num2'];
				});
			});
	}

	/**
	 * 初期HTML生成
	 */
	public function create_data_list($params, $search_select = [])
	{
		// $data = $this->getListDataCustomJoin(
		// 	$params,
		// 	$search_select,
		// 	[],
		// 	[],
		// 	function (&$query) {
		// 		$query->select(
		// 			'level1',
		// 			DB::raw('MIN(id) as id'),
		// 			// DB::raw('MIN(data_type) as data_type'),
		// 			DB::raw('MIN(category_group) as category_group'),
		// 			DB::raw('MIN(is_whole_cancer) as is_whole_cancer'),
		// 			DB::raw('MIN(created_at) as created_at'),
		// 			DB::raw('MAX(updated_at) as updated_at'),
		// 		)
		// 			->distinct('level1');
		// 	},
		// 	[],
		// 	function ($query) {
		// 		$query->groupBy('level1');
		// 	}
		// );

		$data = $this->getListData($params, $search_select);


		$all_cnt = $data['total'];
		$list = $data['data'];

		$return_html = "";
		$cnt = ($params[0] * ($params[1] - 1));

		$return_html = $this->generate_category_list_html($list, $cnt);

		return [
			"list_html" => $return_html,
			'all_cnt' => $all_cnt,
		];
	}

	private function generate_category_list_html($result_category, $offset)
	{
		$return_html = "";
		$back_color = 1;
		$cnt = $offset;
		foreach ($result_category as $row) {
			$cnt++;
			$edit_html_a = $this->generate_edit_html_a($row['category_id'], $row['del_flg']);
			$edit_html_b = $this->generate_edit_html_b($row['category_id'], $row['public_flg']);
			$created_at = $this->format_time($row['created_at']);
			$updated_at = $this->format_time($row['updated_at']);
			$back_color_html = $this->generate_back_color_html($back_color, $row['del_flg']);

			$return_html .= "
			<tr " . $back_color_html . ">
				<td class='count_no'>" . $cnt . "</td>
				<td>" . Category::LIST_CANCER[$row['is_whole_cancer']] . "</td>
				<td>" . Category::LIST_GROUP[$row['category_group']] . "</td>

				<td>" . $row['level1'] . "</td>
				<td>" . $row['level2'] . "</td>
				<td>" . $row['level3'] . "</td>
				<td>" . $created_at . "</td>
				<td>" . $updated_at . "</td>
				<td>
					$edit_html_a
				</td>
				<td>
					$edit_html_b
				</td>
			</tr>";
			// <td>" . Category::LIST_TYPE[$row['data_type']] . "</td>
			$back_color = $back_color >= 3 ? 1 : $back_color + 1;
		}
		return $return_html;
	}

	private function generate_edit_html_a($category_id, $del_flg)
	{
		$edit_html_a = "<a href='javascript:void(0);' class='edit clr1' name='edit_" . $category_id . "' value='" . $category_id . "'><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a><br>";
		// if ($del_flg == 1) {
		// 	$edit_html_a .= "<a href='javascript:void(0);' class='recovery clr2' name='recovery_" . $category_id . "' value='" . $category_id . "' ><i class=\"fa fa-undo\" aria-hidden=\"true\"></i></a><br>";
		// } else {
		// 	$edit_html_a .= "<a href='javascript:void(0);' class='del clr2' name='del_" . $category_id . "' value='" . $category_id . "'><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a><br>";
		// }
		return $edit_html_a;
	}

	private function generate_edit_html_b($category_id, $public_flg)
	{
		if ($public_flg == 1) {
			return "<a href='javascript:void(0);' class='release btn btn-default waves-effect w-md btn-xs' name='release_" . $category_id . "' value='" . $category_id . "'>非公開</a>";
		} else {
			return "<a href='javascript:void(0);' class='private btn btn-custom waves-effect w-md btn-xs ' name='private_" . $category_id . "' value='" . $category_id . "'>公開</a>";
		}
	}

	private function format_time($time)
	{
		return Carbon::parse($time)->format('Y-m-d H:i:s');
	}
	private function format_diff_time($time)
	{
		$diff = strtotime(date('YmdHis')) - strtotime($time);
		if ($diff < 60) {
			return $diff . '秒前';
		} elseif ($diff < 60 * 60) {
			return round($diff / 60) . '分前';
		} elseif ($diff < 60 * 60 * 24) {
			return round($diff / 3600) . '時間前';
		}
		return $time;
	}

	private function generate_back_color_html($back_color, $del_flg)
	{
		$del_color = $del_flg == 1 ? "color:#d3d3d3" : "";
		if ($back_color == 2) {
			return "style='background: #f7f7f9; " . $del_color . "'";
		} else {
			return "style='background: #ffffff; " . $del_color . "'";
		}
	}

	private function generate_image_tag_html($image)
	{
		if (strpos($image, ',') !== false && ($image != null && $image != '')) {
			$img_tag_html = '';
			$image_list = explode(',', $image);
			foreach ($image_list as $img) {
				$img_tag_html .= '<img src="../upload_files/category/' . $img . '" style="height:50px">';
			}
		} elseif ($image != null && $image != '') {
			$img_tag_html = '<img src="../upload_files/category/' . $image . '" style="height:50px">';
		} else {
			$img_tag_html = '<img src="../assets/admin/img/nophoto.png" style="height:50px">';
		}
		return $img_tag_html;
	}

	private function generate_movie_html($movie, $category_id)
	{
		return $movie != null && $movie != "" ? '<a href="#modal" class="check_movie" category_id="' . $category_id . '">有り</a>' : '無し';
	}
}
