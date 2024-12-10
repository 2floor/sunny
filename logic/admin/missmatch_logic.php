<?php

require_once __DIR__ . '/../../logic/admin/base_logic.php';

use App\Models\MissMatch;
use App\Models\DPC;
use App\Models\Stage;
use App\Models\SurvHospital;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class missmatch_logic extends base_logic
{
	public function getModel()
	{
		return MissMatch::class;
	}

	public function create_data_list($params, $search_select = null)
	{
		// DPC , Stage , SurvHospital
		$cancer_name_process = [
			"App\\Models\\DPC" => "cancer_type_dpc",
			"App\\Models\\Stage" => "cancer_type_stage",
			"App\\Models\\SurvHospital" => "cancer_type_surv",
		];

		$const_nspace = "App\\Models\\";
		$instance = isset($search_select['commonSearch']['const_type']) ? $const_nspace . $search_select['commonSearch']['const_type'] : $const_nspace . "DPC";

		$const_model = new $instance();

		$list_year = $const_model->select('year')->distinct()->orderBy('year', 'DESC')->limit(3)->get()->pluck('year')->toArray();

		// $query = $this->getListDataJoin($params, $search_select, [], [], function (&$query, $search_select) use ($list_year) {
		// 	$selects = [
		// 		'area_id',
		// 		'cancer_id',
		// 		'hospital_id',
		// 		'del_flg'
		// 	];

		// 	foreach ($list_year as $key => $year) {
		// 		$selects[] = DB::raw("(SELECT hospital_name FROM t_miss_match WHERE t_miss_match.hospital_id = t_miss_match.hospital_id AND t_miss_match.year = $year AND t_miss_match.status = 0 AND t_miss_match.del_flg = 0 ORDER BY created_at DESC LIMIT 1) AS hospital_name_$key");
		// 	}
		// 	$query->select($selects);
		// }, [], function (&$query) {
		// 	$query->groupBy('area_id', 'cancer_id', 'hospital_id', 'del_flg');
		// }, $const_model);


		$data = $this->getListDataJoin($params, $search_select, [], [], function (&$query, $search_select) use ($list_year, $cancer_name_process, $instance) {
			$query->select([
				't_miss_match.cancer_id',
				'm_cancer.' . $cancer_name_process[$instance] . ' AS cancer_name',
				't_miss_match.area_id',
				'm_area.area_name',
				't_miss_match.hospital_id',
				't_hospital.hospital_name AS hospital_name_master',
				'year_0.hospital_name AS hospital_name_0',
				'year_1.hospital_name AS hospital_name_1',
				'year_2.hospital_name AS hospital_name_2',
				'year_0.percent_match AS percent_match_0',
				'year_1.percent_match AS percent_match_1',
				'year_2.percent_match AS percent_match_2',
				't_miss_match.del_flg'
			]);

			$query->leftJoin('m_cancer', 'm_cancer.id', '=', 't_miss_match.cancer_id');
			$query->leftJoin('m_area', 'm_area.id', '=', 't_miss_match.area_id');
			$query->leftJoin('t_hospital', 't_hospital.id', '=', 't_miss_match.hospital_id');
			foreach ($list_year as $key => $year) {
				$query->leftJoin('t_miss_match AS year_' . $key, function ($join) use ($key, $year) {
					$join->on('year_' . $key . '.hospital_id', '=', 't_miss_match.hospital_id')
						->where('year_' . $key . '.area_id', '=', 't_miss_match.area_id')
						->where('year_' . $key . '.cancer_id', '=', 't_miss_match.cancer_id')
						->where('year_' . $key . '.status', '=', 0)
						->where('year_' . $key . '.del_flg', '=', 0);
				});
			}
		}, [], function (&$query) use ($cancer_name_process, $instance) {
			$query->groupBy(
				't_miss_match.cancer_id',
				't_miss_match.area_id',
				't_miss_match.hospital_id',
				't_miss_match.del_flg',
				'm_area.area_name',
				'm_cancer.' . $cancer_name_process[$instance],
				't_hospital.hospital_name',
				'year_0.hospital_name',
				'year_1.hospital_name',
				'year_2.hospital_name',
				'year_0.percent_match',
				'year_1.percent_match',
				'year_2.percent_match',
			);
		});

		$all_cnt = $data['total'];
		$list = $data['data'];

		$return_html = "";
		$back_color = 1;
		$cnt = ($params[0] * ($params[1] - 1));

		foreach ($list ?? [] as $row) {
			$cnt++;
			$return_html .= $this->generateRowHtml($row, $cnt, $back_color);
			$back_color = $back_color == 2 ? 1 : 2;
		}

		return array(
			"list_html" => $return_html,
			'all_cnt' => $all_cnt
		);
	}

	static function avg_percent_match($row)
	{
		$percent_match_0 = $row['percent_match_0'] ?? 0;
		$percent_match_1 = $row['percent_match_1'] ?? 0;
		$percent_match_2 = $row['percent_match_2'] ?? 0;

		$avg = ($percent_match_0 + $percent_match_1 + $percent_match_2) / 3;
		return round($avg, 2);
	}

	private function generateRowHtml($row, $cnt, $back_color)
	{
		$del_color = $row['del_flg'] == 1 ? "color:#d3d3d3" : "";
		$edit_html_a = $this->generateEditHtml($row);
		$back_color_html = $back_color == 2 ? "style='background: #f7f7f9; $del_color'" : "style='background: #ffffff; $del_color'";
		$edit_html_b = $this->generatePublicHtml($row);
		$create_at = $this->formatTime($row['created_at']);
		$update_at = $this->formatTime($row['updated_at']);
		$question = mb_strlen($row['question'], "UTF-8") > 30 ? mb_substr($row['question'], 0, 30, "UTF-8") . '…' : $row['question'];

		return "
            <tr $back_color_html>
								<td></td>
                <td class='count_no'>$cnt</td>
                <td>{$row['area_name']}</td>
                <td>{$row['cancer_name']}</td>
                <td>{$row['hospital_name_master']}</td>
                <td>{$row['hospital_name_2']}</td>
                <td>{$row['hospital_name_1']}</td>
                <td>{$row['hospital_name_0']}</td>
                <td>" . self::avg_percent_match($row) . "</td>
                <td></td>
            </tr>
        ";
	}

	private function generateEditHtml($row)
	{
		$edit_html_a = "<a href='javascript:void(0);' class='edit clr1' name='edit_{$row['id']}' value='{$row['id']}'><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a><br>";
		if ($row['del_flg'] == 1) {
			$edit_html_a .= "<a href='javascript:void(0);' class='recovery clr2' name='recovery_{$row['id']}' value='{$row['id']}' ><i class=\"fa fa-undo\" aria-hidden=\"true\"></i></a><br>";
		} else {
			$edit_html_a .= "<a href='javascript:void(0);' class='del clr2' name='del_{$row['id']}' value='{$row['id']}'><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a><br>";
		}
		return $edit_html_a;
	}

	private function generatePublicHtml($row)
	{
		if ($row['public_flg'] == 1) {
			return "<a href='javascript:void(0);' class='release btn btn-default waves-effect w-xs btn-xs' name='release_{$row['id']}' value='{$row['id']}'>非公開</a>";
		} else {
			return "<a href='javascript:void(0);' class='private btn btn-custom waves-effect w-xs btn-xs ' name='private_{$row['id']}' value='{$row['id']}'>公開</a>";
		}
	}

	private function formatTime($time)
	{
		$parsed_time = Carbon::parse($time)->format('Y-m-d H:i:s');
		$diff = strtotime(date('YmdHis')) - strtotime($parsed_time);

		if ($diff < 60) {
			return $diff . '秒前';
		} elseif ($diff < 60 * 60) {
			return round($diff / 60) . '分前';
		} elseif ($diff < 60 * 60 * 24) {
			return round($diff / 3600) . '時間前';
		} else {
			return $parsed_time;
		}
	}
}
