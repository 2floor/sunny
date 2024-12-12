<?php

require_once __DIR__ . '/../../logic/admin/base_logic.php';

use App\Models\MissMatch;
use App\Models\Cancer;
use App\Models\Hospital;
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

		$list_type_process = [
			"App\\Models\\DPC" => MissMatch::TYPE_DPC,
			"App\\Models\\Stage" => MissMatch::TYPE_STAGE,
			"App\\Models\\SurvHospital" => MissMatch::TYPE_SURVIVAL,
		];

		$const_nspace = "App\\Models\\";
		$process_type = isset($_GET["const_type"]) ? $_GET["const_type"] : "DPC";

		$instance = $const_nspace . $process_type;
		$const_model = new $instance();

		$cancer_model = new Cancer();

		$data = $this->getListDataJoin($params, $search_select, [], [], function (&$query, &$search_select) {

			$query->crossJoin('t_hospital')
				->select([
					't_hospital.id as hospital_id',
					't_hospital.hospital_name as hospital_name_master',
					'm_cancer.id as cancer_id',
					'm_cancer.cancer_type as cancer_name',
					'm_area.id as area_id',
					'm_area.area_name as area_name',
				]);

			$query->leftJoin('m_area', 'm_area.id', '=', 't_hospital.area_id');

			$cancer_id_find = 1;
			if (!empty($search_select['commonSearch'])) {
				if (!empty($search_select['commonSearch']['multitext'])) {

					$multitext = $search_select['commonSearch']['multitext'];
					unset($search_select['commonSearch']['multitext']);

					$query->where(function ($query) use ($multitext) {
						$query->orWhere("t_hospital.hospital_name", $multitext[1], $multitext[2]);
					});
				}
				if (!empty($search_select['commonSearch']['cancer_id'])) {
					$cancer_id_find = $search_select['commonSearch']['cancer_id'];
					unset($search_select['commonSearch']['cancer_id']);
				}
			}
			$query->where('m_cancer.id', '=', $cancer_id_find);
		}, [
			'id' => 'm_area',
		], function (&$query, &$search_select) {

			if (!empty($search_select['commonOrder'])) {
				$query->orderBy($search_select['commonOrder']['target'], $search_select['commonOrder']['order']);
			} else {
				$query->orderBy('hospital_id');
			}
			// $query->orderBy('hospital_id');
			$query->orderBy('hospital_id');
		}, ['*'], $cancer_model);

		$all_cnt = $data['total'];
		$list = $data['data'];

		$return_html = "";
		$back_color = 1;
		$cnt = ($params[0] * ($params[1] - 1));

		$find_list_year = $const_model->select('year')->distinct()->orderBy('year', 'DESC')->limit(3)->get()->pluck('year');
		$list_year = $find_list_year->toArray();


		foreach ($list ?? [] as $key => $row) {

			$hospital_id = $row['hospital_id'];
			$cancer_id = $row['cancer_id'];

			$hospital = Hospital::find($hospital_id);
			$cancer = Cancer::find($cancer_id);
			$type = $list_type_process[$instance];



			$detail = $this->getDetailsForYears($list_year, $instance, $cancer_name_process, $hospital, $cancer, $type);

			foreach ($detail as $yearkey => $datayear) {
				$row['hospital_name_' . $yearkey] = $datayear['hospital_name'];
				$row['percent_match_' . $yearkey] = $datayear['percent_match'];
				$row['status_' . $yearkey] = $datayear['status'];
				if (isset($datayear['missmatch_id'])) {
					$row['id_' . $yearkey] = $datayear['missmatch_id'];
				}
			}

			$cnt++;

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

	private function getDetailsForYears($years, $instance, $cancer_name_process, $hospital, $cancer, $type)
	{
		return collect($years)->map(function ($year) use ($instance, $cancer_name_process, $hospital, $cancer, $type) {
			$mm = $this->getListByWhereClause([
				'year' => $year,
				'cancer_id' => $cancer->id,
				'hospital_id' => $hospital->id,
				'type' => $type
			])->first();

			if ($mm) {
				return [
					'missmatch_id' => $mm->id,
					'area_id' => $mm->area_id,
					'hospital_name' => $mm->hospital_name,
					'year' => $mm->year,
					'cancer_type' => $mm->cancer->cancer_type,
					$cancer_name_process[$instance] => $mm->cancer->{$cancer_name_process[$instance]},
					'percent_match' => $mm->percent_match,
					'status' => $mm->status,
				];
			}

			return [
				'hospital_name' => null,
				'year' => $year,
				'cancer_type' => $cancer->cancer_type,
				$cancer_name_process[$instance] => $cancer->{$cancer_name_process[$instance]},
				'percent_match' => null,
				'status' => -1,
			];
		})->toArray();
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
			'percent_match' => null,
			'status' => MissMatch::STATUS_NOT_CONFIRM
		]);
	}

	static function avg_percent_match($row)
	{
		$percents = [];
		if ((int)$row['percent_match_0'] > 0) $percents[] = $row['percent_match_0'];
		if ((int)$row['percent_match_1'] > 0) $percents[] = $row['percent_match_1'];
		if ((int)$row['percent_match_2'] > 0) $percents[] = $row['percent_match_2'];

		$avg = (count($percents) > 0) ? (array_sum($percents) / count($percents)) : 0;
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
					<td " . self::getStatusHtml($row['status_2']) . ">" . ($row['hospital_name_2'] ?? "-") . "</td>
					<td " . self::getStatusHtml($row['status_1']) . ">" . ($row['hospital_name_1'] ?? "-") . "</td>
					<td " . self::getStatusHtml($row['status_0']) . ">" . ($row['hospital_name_0'] ?? "-") . "</td>
					<td>" . self::avg_percent_match($row) . "</td>

					<td>" . ($this->generateHtml('accept', $row, $params) ?? "-") . "</td>
					<td>" . ($this->generateHtml('edit', $row, $params) ?? "-") . "</td>
					<td>" . ($this->generateHtml('cancel', $row, $params) ?? "-") . "</td>
			</tr>";
	}

	static function getStatusHtml($status)
	{
		return ($status == 0) ? '' : ($status == 1 ? "class='mm_status_confirmed'" : "");
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

	public function getListByWhereClause($clause)
	{
		return MissMatch::where($clause)->get();
	}
}
