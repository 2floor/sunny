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

		$process_type = isset($search_select['commonSearch']['const_type']) ? $search_select['commonSearch']['const_type'] : "DPC";
		unset($search_select['commonSearch']['const_type']);

		$const_nspace = "App\\Models\\";
		$instance = $const_nspace . $process_type;

		$const_model = new $instance();

		$list_year = $const_model->select('year')->distinct()->orderBy('year', 'DESC')->limit(3)->get()->pluck('year')->toArray();

		$data = $this->getListDataJoin($params, $search_select, [], [], function (&$query, &$search_select) use ($list_year) {
			$query->distinct();
			$query->select([
				't_miss_match.hospital_id',
				't_miss_match.cancer_id',
				't_miss_match.area_id',
				'm_cancer.cancer_type AS cancer_name',
				'm_area.area_name',
				't_hospital.hospital_name AS hospital_name_master',
				'year_0.hospital_name AS hospital_name_0',
				'year_1.hospital_name AS hospital_name_1',
				'year_2.hospital_name AS hospital_name_2',
				'year_0.percent_match AS percent_match_0',
				'year_1.percent_match AS percent_match_1',
				'year_2.percent_match AS percent_match_2',
				'year_0.id AS id_0',
				'year_1.id AS id_1',
				'year_2.id AS id_2',
				't_miss_match.del_flg',
			]);

			$query->leftJoin('m_cancer', 'm_cancer.id', '=', 't_miss_match.cancer_id');
			$query->leftJoin('m_area', 'm_area.id', '=', 't_miss_match.area_id');
			$query->leftJoin('t_hospital', 't_hospital.id', '=', 't_miss_match.hospital_id');

			foreach ($list_year as $key => $year) {
				$query->leftJoinSub(
					DB::table('t_miss_match')->where('year', $year)->where('status', 0)->where('del_flg', 0),
					'year_' . $key,
					function ($join) use ($key) {
						$join
							->on('year_' . $key . '.hospital_id', '=', 't_miss_match.hospital_id')
							->on('year_' . $key . '.area_id', '=', 't_miss_match.area_id')
							->on('year_' . $key . '.cancer_id', '=', 't_miss_match.cancer_id');
					}
				);
			}
			$query->where('t_miss_match.status', 0);
			$query->whereNotNull('t_miss_match.hospital_id')
				->where('t_miss_match.hospital_id', '>', 0)
				->whereNotNull('t_miss_match.area_id')
				->where('t_miss_match.area_id', '>', 0);

			if (!empty($search_select['commonSearch'])) {
				if (!empty($search_select['commonSearch']['multitext'])) {

					$multitext = $search_select['commonSearch']['multitext'];
					unset($search_select['commonSearch']['multitext']);

					$query->where(function ($query) use ($multitext, $list_year) {
						$query->orWhere("t_miss_match.hospital_name", $multitext[1], $multitext[2]);
						$query->orWhere("t_miss_match.year", $multitext[1], $multitext[2]);
						$query->orWhere("m_cancer.cancer_type", $multitext[1], $multitext[2]);
						$query->orWhere("m_area.area_name", $multitext[1], $multitext[2]);
						$query->orWhere("t_miss_match.hospital_name", $multitext[1], $multitext[2]);

						foreach ($list_year as $key => $year) {
							$query->orWhere("year_" . $key . ".hospital_name", $multitext[1], $multitext[2]);
						}
					});
				}
			}
		}, [
			'area_id' => 't_miss_match'
		], function (&$query) {});

		$all_cnt = $data['total'];
		$list = $data['data'];

		$return_html = "";
		$back_color = 1;
		$cnt = ($params[0] * ($params[1] - 1));

		foreach ($list ?? [] as $key => $row) {
			$cnt++;
			$list[$key]['id'] = self::min_of_ids($row);
			$row['id'] = self::min_of_ids($row);
			$return_html .= $this->generateRowHtml($row, $cnt, $back_color, $params);
			$back_color = $back_color == 2 ? 1 : 2;
		}

		// var_dump(json_encode($list));
		// die();

		return array(
			"list_html" => $return_html,
			'all_cnt' => $all_cnt,
			'list_year' => $list_year,
			'process_type' => $process_type
		);
	}

	public function accept_data($id)
	{
		return $this->getQueryWithoutGlobalScopes()->where('id', $id)->update(['status' => MissMatch::STATUS_CONFIRMED]);
	}
	public function cancel_data($id)
	{
		return $this->getQueryWithoutGlobalScopes()->where('id', $id)->update([
			'hospital_id' => null,
			'area_id' => null,
			'percent_match' => 0,
			'status' => MissMatch::STATUS_NOT_CONFIRM
		]);
	}

	static function avg_percent_match($row)
	{
		$percent_match_0 = $row['percent_match_0'] ?? 0;
		$percent_match_1 = $row['percent_match_1'] ?? 0;
		$percent_match_2 = $row['percent_match_2'] ?? 0;

		$avg = ($percent_match_0 + $percent_match_1 + $percent_match_2) / 3;
		return round($avg, 2);
	}

	static function get_ids($row)
	{
		$ids = [];
		if ((int)$row['id_0'] > 0) $ids[] = $row['id_0'];
		if ((int)$row['id_1'] > 0) $ids[] = $row['id_1'];
		if ((int)$row['id_2'] > 0) $ids[] = $row['id_2'];
		return $ids;
	}
	static function implode_ids($row)
	{
		$ids = self::get_ids($row);
		return implode(',', $ids);
	}
	static function min_of_ids($row)
	{
		$ids = self::get_ids($row);
		return count($ids) > 0 ? min($ids) : 0;
	}

	private function generateRowHtml($row, $cnt, $back_color, $params)
	{
		$del_color = $row['del_flg'] == 1 ? "color:#d3d3d3" : "";
		$back_color_html = $back_color == 2 ? "style='background: #f7f7f9; $del_color'" : "style='background: #ffffff; $del_color'";

		$checkbox_name = "checkbox_{$row['id']}";

		return "
			<tr $back_color_html>
					<td><input type='checkbox' class='row-checkbox' name='$checkbox_name' value='" . self::implode_ids($row) . "'></td>
					<td class='count_no'>$cnt</td>
					<td>" . ($row['area_name'] ?? "-") . "</td>
					<td>" . ($row['cancer_name'] ?? "-") . "</td>
					<td>" . ($row['hospital_name_master'] ?? "-") . "</td>
					<td>" . ($row['hospital_name_2'] ?? "-") . "</td>
					<td>" . ($row['hospital_name_1'] ?? "-") . "</td>
					<td>" . ($row['hospital_name_0'] ?? "-") . "</td>
					<td>" . self::avg_percent_match($row) . "</td>

					<td>" . ($this->generateHtml('accept', $row, $params) ?? "-") . "</td>
					<td>" . ($this->generateHtml('edit', $row, $params) ?? "-") . "</td>
					<td>" . ($this->generateHtml('cancel', $row, $params) ?? "-") . "</td>
			</tr>";
	}

	private function generateHtml($type, $row, $params)
	{
		switch ($type) {
			case 'edit':
				return "<a href='missmatch_detail.php?cancer_id={$row['cancer_id']}&hospital_id={$row['hospital_id']}&cur_page=" . $params[1] . "' class='edit clr1'><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a>";
				break;
			case 'cancel':
				return "<a href='javascript:void(0);' class='cancel clr2' name='cancel_{$row['id']}' value='" . self::implode_ids($row) . "'><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a><br>";
				break;
			case 'accept':
				return "<a href='javascript:void(0);' class='accept clr1' name='accept_{$row['id']}' value='" . self::implode_ids($row) . "'><i class=\"fa fa-check\" aria-hidden=\"true\"></i></a><br>";
				break;

			default:
				return '-';
				break;
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
