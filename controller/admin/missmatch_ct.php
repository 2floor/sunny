<?php
if (!isset($_SESSION)) {
	session_start();
}

require_once __DIR__ . '/../../logic/common/common_logic.php';
require_once __DIR__ . '/../../logic/admin/missmatch_logic.php';
require_once __DIR__ . '/../../logic/admin/dpc_logic.php';
require_once __DIR__ . '/../../common/security_common_logic.php';

use App\Models\Hospital;
use App\Models\MissMatch;
use App\Models\Cancer;

/**
 * セキュリティチェック
 */
// インスタンス生成
$security_common_logic = new security_common_logic();

// XSSチェック、NULLバイトチェック
$security_result = $security_common_logic->security_exection($_POST, $_REQUEST, $_COOKIE);

// セキュリティチェック後の値を再設定
$_POST = $security_result[0];
$_REQUEST = $security_result[1];
$_COOKIE = $security_result[2];

// tokenチェック
$security_common_logic = new security_common_logic();
$data = $security_common_logic->isTokenExection();
if ($data['status']) {

	if (isset($_SERVER['HTTP_REFERER'])) {

		$referer = end(explode("/", $_SERVER['HTTP_REFERER']));
		switch ($referer) {
			case 'missmatch_dpc.php':
				$_GET["const_type"] = "DPC";
				break;
			case 'missmatch_stage.php':
				$_GET["const_type"] = "Stage";
				break;
			case 'missmatch_surv.php':
				$_GET["const_type"] = "SurvHospital";
				break;
			default:
				unset($_GET["const_type"]);
				break;
		}
	}


	// 正常処理 コントローラー呼び出し

	// インスタンス生成
	$missmatch_ct = new missmatch_ct();
	$post_data = $_SERVER['REQUEST_METHOD'] == 'POST' ? $_POST : $_GET;

	// コントローラー呼び出し
	$data = $missmatch_ct->main_control($post_data);
} else {
	// パラメータに不正があった場合
	// AJAX返却用データ成型
	$data = array(
		'status' => false,
		'input_datas' => $_POST,
		'return_url' => 'logout.php'
	);
}

// AJAXへ返却
echo json_encode(compact('data'));

/**
 * 管理画面ユーザー管理処理
 *
 * ViewからLogic呼び出しを行うclass。
 * 本クラスではLogic呼び出しやデータの成型、入力チェックのみを行うものとする。
 * ※：セキュリティ保持の為Logic呼び出元をmain_controlクラスのみとする。
 * 各ロジック呼び出しをクラス化し、かつ、privateとする。
 *
 * @author Seidou
 *
 */
class missmatch_ct
{
	/**
	 * コンストラクタ
	 */
	protected $missmatch_logic;
	protected $dpc_logic;
	protected $common_logic;

	public function __construct()
	{
		// 管理画面ユーザーロジックインスタンス
		$this->missmatch_logic = new missmatch_logic();
		$this->dpc_logic = new dpc_logic();
		$this->common_logic = new common_logic();
	}

	/**
	 * コントローラー
	 * 各処理の振り分けをmethodの文字列により行う
	 */
	public function main_control($post)
	{
		if ($post['method'] == 'init') {
			// 初期処理　HTML生成処理呼び出し
			$data = $this->create_data_list($post);
		} else if ($post['method'] == 'entry') {
			// 新規登録処理
			$data = $this->entry_new_data($post);
		} else if ($post['method'] == 'get_detail') {
			// 編集初期処理
			$data = $this->get_detail($post);
		} else if ($post['method'] == 'update_mm_dpc') {
			// 編集更新処理
			$data = $this->update_mm_dpc($post);
		} else if ($post['method'] == 'delete') {
			// 削除処理
			$data = $this->delete($post['id']);
		} else if ($post['method'] == 'recovery') {
			// 有効化処理
			$data = $this->recovery($post['id']);
		} else if ($post['method'] == 'private') {
			// 非公開化処理
			$data = $this->private_func($post['id']);
		} else if ($post['method'] == 'release') {
			// 公開化処理
			$data = $this->release($post['id']);
		} else if ($post['method'] == 'cancel_list') {
			$data = $this->cancel_list($post);
		} else if ($post['method'] == 'accept_list') {
			$data = $this->accept_list($post['id']);
		}

		return $data;
	}

	public function get_not_confirm_mm_list($year, $cancer_id, $type)
	{
		return $this->missmatch_logic->getListByWhereClause(
			[
				'year' => $year,
				'cancer_id' => $cancer_id,
				'type' => $type,
				'status' => MissMatch::STATUS_NOT_CONFIRM
			]
		);
	}

	public function get_mm_detail($hospital_id, $cancer_id, $type)
	{
		$hospital = Hospital::find($hospital_id);
		$cancer = Cancer::find($cancer_id);

		if (!$hospital || !$cancer) {
			return [];
		}

		if ($type == MissMatch::TYPE_DPC) {
			$detail = $this->dpc_logic->getLastedYearDPC()->pluck('year')
				->map(function ($year) use ($hospital_id, $cancer_id, $type, $hospital, $cancer) {
					$mm = $this->missmatch_logic->getListByWhereClause(
						[
							'year' => $year,
							'cancer_id' => $cancer_id,
							'hospital_id' => $hospital_id,
							'type' => $type
						]
					)->first();

					if (!$mm) {
						$dpc = $this->dpc_logic->getListByWhereClause([
							'year' => $year,
							'cancer_id' => $cancer_id,
							'hospital_id' => $hospital_id,
						])->first();

						if ($dpc) {
							$mm_lasted = $this->missmatch_logic->getListByWhereClause(
								[
									'cancer_id' => $dpc->cancer_id,
									'hospital_id' => $dpc->hospital_id,
									'type' => $type,
									'status' => MissMatch::STATUS_CONFIRMED
								]
							)->sortByDesc('year')->first();

							return [
								'area_id' => $dpc->hospital?->area_id,
								'hospital_name' => $mm_lasted ? $mm_lasted['hospital_name'] : $dpc->hospital?->hospital_name,
								'hospital_id' => $dpc->hospital_id,
								'year' => $dpc->year,
								'cancer_type' => $dpc->cancer?->cancer_type,
								'cancer_type_dpc' => $dpc->cancer?->cancer_type_dpc,
								'cancer_id' => $dpc->cancer_id,
								'dpc' => $dpc->n_dpc,
								'percent_match' => 100,
								'status' => MissMatch::STATUS_ABSOLUTELY_MATCH,
							];
						} else {
							return [
								'area_id' => $hospital->area_id,
								'hospital_name' => null,
								'hospital_id' => $hospital->id,
								'year' => $year,
								'cancer_type' => $cancer->cancer_type,
								'cancer_type_dpc' => $cancer->cancer_type_dpc,
								'cancer_id' => $cancer->id,
								'dpc' => null,
								'percent_match' => null,
								'status' => -1,
							];
						}
					} else {
						$value = json_decode($mm->import_value, true);
						return [
							'area_id' => $mm->area_id,
							'hospital_name' => $mm->hospital_name,
							'hospital_id' => $mm->hospital_id,
							'year' => $mm->year,
							'cancer_type' => $mm->cancer?->cancer_type,
							'cancer_type_dpc' => $mm->cancer?->cancer_type_dpc,
							'cancer_id' => $mm->cancer?->id,
							'dpc' => $value[2] ?? 0,
							'percent_match' => $mm->percent_match,
							'status' => $mm->status,
						];
					}
				})->toArray();
		}

		return $detail ?? [];
	}

	/**
	 * 初期処理(一覧HTML生成)
	 */
	private function create_data_list($post)
	{

		$whereClause = ['commonSearch' => []];

		if (isset($post['searchnew_select'])) {
			$searchnew_select = json_decode(htmlspecialchars_decode($post['searchnew_select']), true);
		}
		if (!empty($searchnew_select)) {
			if (!empty($searchnew_select['multitext'])) {
				$whereClause['commonSearch']['multitext'] =  ['multitext', 'like', "%" . (trim($searchnew_select['multitext'])) . "%"];
			}
			if (!empty($searchnew_select['search_area'])) {
				$whereClause['commonSearch'][] =  ['id', 'like', "%" . (trim($searchnew_select['search_area'])) . "%"];
			}
			if (!empty($searchnew_select['search_cancer'])) {
				$whereClause['commonSearch']['cancer_id'] = trim($searchnew_select['search_cancer']);
			}
		}


		if (isset($post['search_select'])) {
			$search_select = json_decode(htmlspecialchars_decode($post['search_select']), true);
		}
		if (!empty($search_select)) {
			if (!empty($search_select['order'])) {
				$whereClause['commonOrder'] =  $search_select['order'];
			}
		}

		$list_html = $this->missmatch_logic->create_data_list([
			$post['pageSize'],
			$post['pageNumber']
		],  $whereClause);

		// AJAX返却用データ成型
		return [
			'status' => true,
			'html' => [
				$list_html['list_html'],
				$list_html['all_cnt'],
				$list_html['list_year'],
				$list_html['process_type'],
			],
		];
	}

	/**
	 * 新規登録処理
	 */
	private function entry_new_data($post)
	{
		// 登録ロジック呼び出し
		$data = [
			'question' => $post['question'] ?? null,
			'answer' => $post['answer'] ?? null,
			'group_answer' => $post['group_answer'] ?? null,
		];

		$add = $this->missmatch_logic->createData($data);

		if (!$add) {
			return [
				'status' => false,
				'error_code' => 0,
				'error_msg' => 'FAQデータを作成できません',
				'return_url' => MEDICALNET_ADMIN_PATH . self::set_return_url()
			];
		}

		// AJAX返却用データ成型
		return [
			'status' => true,
			'method' => 'entry',
			'msg' => '登録しました'
		];
	}

	/**
	 * 編集初期処理(詳細情報取得)
	 *
	 */
	private function get_detail($post)
	{
		$isGetById = false;
		if ($post['edit_del_id']) {
			$detail = $this->missmatch_logic->getDetailById($post['edit_del_id']);
			$isGetById = true;
		} else {
			$detail = $this->missmatch_logic->getListByWhereClause([
				'cancer_id' => $post['cancer_id'],
				'hospital_id' => $post['hospital_id'],
				'type' => $post['type'],
				'year' => $post['year']
			])->first();
		}

		// AJAX返却用データ成型
		return [
			'status' => true,
			'isGetById' => $isGetById,
			'data' => $detail->toArray()
		];
	}

	/**
	 * 編集更新処理
	 *
	 */
	private function update_mm_dpc($post)
	{
		$request = json_decode(htmlspecialchars_decode($post['request'] ?? ''), true);

		if (empty($request)) {
			return [
				'status' => false,
				'error_code' => 0,
				'error_msg' => '無効なリクエスト',
				'return_url' => MEDICALNET_ADMIN_PATH . self::set_return_url()
			];
		}

		foreach ($request as $k => $v) {
			$hospital = Hospital::find($v['hospital_id']);
			$cancer = Cancer::find($v['cancer_id']);

			if (!$hospital || !$cancer) {
				continue;
			}

			if ($v['search']) {
				$mmChanged = $this->missmatch_logic->getDetailById($v['search']);

				if (!$mmChanged || $mmChanged->status == MissMatch::STATUS_CONFIRMED) {
					continue;
				}

				$importValue = json_decode($mmChanged['import_value'], true);
				$nDPCChanged = $importValue[2] ?? 0;

				if (!$nDPCChanged) {
					continue;
				}

				$currentDPC = $this->dpc_logic->getListByWhereClause([
					'cancer_id' => $cancer->id,
					'hospital_id' => $hospital->id,
					'year' => $v['year']
				])->first();

				if ($currentDPC) {
					$currentDPC->update([
						'n_dpc' => $nDPCChanged,
						'rank_nation_dpc' => null,
						'rank_area_dpc' => null,
						'rank_pref_dpc' => null
					]);
				} else {
					$newDPC = $this->dpc_logic->createData([
						'cancer_id' => $cancer->id,
						'cancer_name_dpc' => $cancer->cancer_type_dpc,
						'hospital_id' => $hospital->id,
						'hospital_name' => $hospital->hospital_name,
						'area_id' => $hospital->area_id,
						'year' => $v['year'],
						'n_dpc' => $nDPCChanged,
					]);
				}

				$mmCurrent = $this->missmatch_logic->getListByWhereClause(
					[
						'cancer_id' => $cancer->id,
						'hospital_id' => $hospital->id,
						'type' => MissMatch::TYPE_DPC,
						'year' => $v['year']
					]
				)->first();

				if ($mmCurrent) {
					$mmCurrent->update([
						'hospital_id' => null,
						'area_id' => null,
						'percent_match' => null
					]);
				}

				$this->dpc_logic->forceDelete([
					'cancer_id' => $mmChanged->cancer_id,
					'hospital_id' => $mmChanged->hospital_id,
					'year' => $mmChanged->year
				]);

				$mmChanged->update([
					'hospital_id' => $hospital->id,
					'area_id' => $hospital->area_id,
					'percent_match' => 100
				]);
			}

			$clause = [
				'cancer_id' => $cancer->id,
				'hospital_id' => $hospital->id,
				'type' => MissMatch::TYPE_DPC,
				'year' => $v['year']
			];

			$this->missmatch_logic->updateMultiData($clause, ['status' => MissMatch::STATUS_CONFIRMED]);
		}

		return [
			'status' => true,
			'method' => 'update',
			'msg' => '変更しました'
		];
	}

	/**
	 * 有効化処理
	 *
	 */
	public function recovery($id)
	{
		// 更新ロジック呼び出し
		$this->missmatch_logic->recoveryData($id);

		// AJAX返却用データ成型
		$data = array(
			'status' => true,
			'method' => 'recovery',
			'msg' => '有効にしました'
		);
		return $data;
	}

	/**
	 * 削除処理
	 */
	public function delete($id)
	{
		// 更新ロジック呼び出し
		$this->missmatch_logic->deleteData($id);

		// AJAX返却用データ成型
		$data = array(
			'status' => true,
			'method' => 'delete',
			'msg' => '削除しました'
		);
		return $data;
	}

	/**
	 * 非公開処理
	 */
	public function private_func($id)
	{
		// 更新ロジック呼び出し
		$this->missmatch_logic->privateData($id);

		// AJAX返却用データ成型
		$data = array(
			'status' => true,
			'method' => 'private',
			'msg' => '非公開にしました'
		);
		return $data;
	}

	/**
	 * 公開処理
	 */
	public function release($id)
	{
		// 更新ロジック呼び出し
		$this->missmatch_logic->releaseData($id);

		// AJAX返却用データ成型
		$data = array(
			'status' => true,
			'method' => 'release',
			'msg' => '公開しました'
		);
		return $data;
	}

	public function cancel_list($post)
	{
		$id = $post['id'] ?? null;

		if (!$id && $post['hospital_id'] && $post['cancer_id'] && $post['type']) {
			$whereClause = [
				'hospital_id' => $post['hospital_id'],
				'cancer_id' => $post['cancer_id'],
				'type' => $post['type']
			];

			if (is_numeric($post['year'])) {
				$whereClause['year'] = $post['year'];
			}

			$mm = $this->missmatch_logic->getListByWhereClause($whereClause);
			if (!is_numeric($post['year']) && $yearList = json_decode(htmlspecialchars_decode($post['year']), true)) {
				$mm->whereIn('year', $yearList);
			}

			$ids = $mm->pluck('id')->toArray();
		} else {
			$ids = explode(',', $id);
		}

		foreach ($ids as $idv) {
			if (empty($idv)) {
				return [
					'status' => false,
					'method' => 'cancel_list',
					'error_msg' => '無効な値'
				];
			}
		}
		foreach ($ids as $idv) {
			$missmatch = $this->missmatch_logic->getDetailById($idv);
			if ($missmatch) {
				$this->dpc_logic->forceDelete([
					'cancer_id' => $missmatch->cancer_id,
					'hospital_id' => $missmatch->hospital_id,
					'year' => $missmatch->year,
				]);

				$this->missmatch_logic->cancel_data($idv);
			}
		}
		return [
			'status' => true,
			'method' => 'cancel_list',
			'msg' => '削除しました'
		];
	}

	public function accept_list($id)
	{
		$ids = explode(',', $id);
		foreach ($ids as $idv) {
			if (empty($idv)) {
				return [
					'status' => false,
					'method' => 'accept_list',
					'error_msg' => '無効な値'
				];
			}
		}
		foreach ($ids as $idv) {
			$this->missmatch_logic->accept_data($idv);
		}

		return [
			'status' => true,
			'method' => 'accept_list',
			'msg' => '承認しました'
		];
	}

	static function set_return_url()
	{
		if (isset($_GET["const_type"])) {
			switch ($_GET["const_type"]) {
				case 'DPC':
					return 'missmatch_dpc.php';
					break;
				case 'Stage':
					return 'missmatch_stage.php';
					break;
				case 'SurvHospital':
					return 'missmatch_surv.php';
					break;
			}
		}
		return '';
	}
}
