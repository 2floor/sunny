<?php

use App\Models\Area;
use App\Models\Cancer;
use App\Models\Category;
use App\Models\Hospital;
use App\Models\SurvAverage;
use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../../common/security_common_logic.php';
require_once __DIR__ . "/../../logic/front/auth_logic.php";
require_once __DIR__ . "/../../logic/front/f_hospital_logic.php";
require_once __DIR__ . '/../../third_party/bootstrap.php';

$auth_logic = new auth_logic();
$auth_logic->check_authentication();
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

if (isset($_GET['method'])) {
    $ct = new f_hospital_ct();
    $data = $ct->mainAjaxGet($_GET);
    echo json_encode($data);
} elseif (isset($_POST['method'])) {
    $ct = new f_hospital_ct();
    $data = $ct->mainAjaxPost($_POST);
    echo json_encode($data);
}

class f_hospital_ct
{
    public function mainAjaxGet($get)
    {
        $data = [
            'status' => false,
            'data' => []
        ];

        if ($get['method'] == 'searchHospitalList') {
            $data = $this->searchHospitalList($get);
        }

        return $data;
    }

    public function mainAjaxPost($post)
    {
        $data = [
            'status' => false,
            'data' => []
        ];

        if ($post['method'] == 'printHospitalList') {
            $data = $this->printHospitalList($post);
        }

        return $data;
    }

    public function searchPageIndex($categoryType) {
        $cancer = Cancer::select('id', 'cancer_type')
            ->where(['public_flg' => Cancer::PUBLISHED, 'del_flg' => Cancer::NOT_DELETED])
            ->orderBy('order_num', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $area = Area::select('id', 'area_name', 'prec_cd', 'pref_name')
            ->where(['public_flg' => Area::PUBLISHED, 'del_flg' => Area::NOT_DELETED])
            ->get()
            ->groupBy('area_name');

        $category = Category::select('id', 'level1', 'level2', 'level3', 'order_num')
            ->where([
                'category_group' => Category::HOSPITAL_GROUP,
                'public_flg' => Category::PUBLISHED,
                'del_flg' => Category::NOT_DELETED
            ])
            ->when($categoryType == 'detail', function ($query) {
                $query->where('data_type', Category::HOSPITAL_DETAIL_TYPE);
            })
            ->get()
            ->groupBy('level1')->map(function ($items) {
                return $items->groupBy('level2')->map(function ($subItems) {
                    return $subItems->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'level3' => $item->level3,
                            'order_num' => $item->order_num
                        ];
                    });
                })->sortBy(function ($subItems, $key) {
                    return $subItems->first()['order_num'];
                });
            });

        return [
            'cancer' => $cancer->toArray(),
            'area' => $area->toArray(),
            'category' => $category->toArray(),
        ];
    }

    public function getDetailById($id, $cancerId)
    {
        $hospital = Hospital::where('id', $id)
            ->where(['del_flg' => Hospital::NOT_DELETED, 'public_flg' => Hospital::PUBLISHED])
            ->whereHas('cancers', function ($query) use ($cancerId) {
                $query->where('m_cancer.id', $cancerId);
                $query->where(['m_cancer.del_flg' => Cancer::NOT_DELETED, 'm_cancer.public_flg' => Cancer::PUBLISHED]);
            })->first();

        if (!$hospital) {
            header("Location: " . BASE_URL . "error/404_page.php");
            exit();
        }

        $cancer = Cancer::select('cancer_type')->where('id', $cancerId)->first();
        $avgData = $hospital->calculateAvgCommonData($cancerId);

        $yearDpc = $hospital->dpcs()
            ->select('year')
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->pluck('year')
            ->implode('、');

        $yearStage = $hospital->stages()
            ->select('year')
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->pluck('year')
            ->implode('、');

        $yearSurvival = $hospital->survivals()
            ->select('year')
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->pluck('year')
            ->implode('、');

        $categories = $hospital->categories()
            ->where(function ($query) {
                $query->where('data_type', Category::HOSPITAL_DETAIL_TYPE);
                $query->orWhere('data_type', Category::HOSPITAL_TREATMENT_TYPE);
            })
            ->where(['del_flg' => Category::NOT_DELETED, 'public_flg' => Category::PUBLISHED])
            ->get();

        $hospitalType = $categories->firstWhere('hard_name2', 'hospital_type');
        $hospitalGen = $categories->firstWhere('hard_name2', 'hospital_gen');
        $specialClinic = $categories->firstWhere('hard_name3', 'special_clinic');
        $advancedMedical = $categories->firstWhere('hard_name3', 'advanced_medical');
        $famousDoctor = $categories->firstWhere('hard_name3', 'famous_doctor');
        $multiTreatment = $categories->firstWhere('hard_name3', 'multi_treatment');

        $treatment = $hospital->categories()->where('data_type', Category::HOSPITAL_TREATMENT_TYPE)
            ->where(['del_flg' => Category::NOT_DELETED, 'public_flg' => Category::PUBLISHED])
            ->pluck('level3')
            ->implode(' ');

        $stages = $hospital->stages()
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->get();

        $dpcs = $hospital->dpcs()
            ->select(['year', 'n_dpc', 'rank_nation_dpc', 'rank_area_dpc', 'rank_pref_dpc'])
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->get();

        $survivals = $hospital->survivals()
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->get();

        $averageSurv = SurvAverage::where('cancer_id', $cancerId)
            ->whereIn('year', $survivals->pluck('year'))
            ->orderBy('year', 'desc')
            ->get();

        $infoHospital = [
            'name' => $hospital->hospital_name,
            'tel' => $hospital->tel,
            'address' => $hospital->addr,
            'hpUrl' => $hospital->hp_url,
            'supportUrl' => $hospital->support_url,
            'specialClinicUrl' => $specialClinic?->pivot->content1
        ];

        $infoTreatment = [
            'hospitalType' => $hospitalType?->level3,
            'hospitalGen' => $hospitalGen?->level3,
            'hasAdvancedMedical' => $advancedMedical ? 1 : 0,
            'advancedMedical' => $advancedMedical?->pivot->content1,
            'famousDoctor' => $famousDoctor ? 1 : 0,
            'multiTreatment' => $multiTreatment ? 1 : 0,
            'treatment' => $treatment
        ];

        return [
            'cancerName' => $cancer->cancer_type,
            'avgData' => $avgData,
            'yearSummary' => [
                'dpc' => $yearDpc,
                'stage' => $yearStage,
                'survival' => $yearSurvival,
            ],
            'infoHospital' => $infoHospital,
            'infoTreatment' => $infoTreatment,
            'dpcs' => $dpcs,
            'stages' => $stages,
            'survivals' => $survivals,
            'averageSurv' => $averageSurv,
        ];
    }

    public function searchHospitalList($get)
    {
        $cancers = $get['cancer'] ?? [];
        $areaRaw = $get['area'] ?? [];
        $categories = $get['category'] ?? [];
        $keyword = $get['keyword'] ?? '';
        $sort = $get['sort'] ?? '';
        $page = $get['pageNumber'] ?? 1;
        $limit = $get['pageSize'] ?? 5;

        if (empty($cancers)) {
            return [];
        }

        $areas = [];
        if (!empty($areaRaw)) {
            if (!empty($areaRaw['region'])) {
                if ($areaRaw['region'][0] != '全国') {
                    $areaIds = Area::whereIn('area_name', $areaRaw['region'])->pluck('id')->toArray();
                    $areas = array_merge($areas, $areaIds);
                }
            }

            if (!empty($areaRaw['area'])) {
                $areas = array_merge($areas, $areaRaw['area']);
            }
        }

        $hospitalLogic = new f_hospital_logic();
        $data = $hospitalLogic->getHospitalsFromFilter($keyword, $cancers, $areas, $categories, $sort, $page, $limit);
        $totalNumber = $data['total'];
        $hospitals = $data['hospitals'];

        $html = '';
        $hospitals->each(function ($hospital) use ($cancers, &$html) {
            $areaName = $hospital->area->pref_name;
            $categories = $hospital->categories()->select('level3')
                ->where('data_type', Category::HOSPITAL_DETAIL_TYPE)
                ->whereIn('hard_name2', ['hospital_type', 'hospital_gen'])
                ->get()
                ->pluck('level3');

            $avgData = $hospital->calculateAvgCommonData($cancers[0]);

            $html .= '<div class="hospital-card">';
            $html .= '<div class="card-checkbox">';
            $html .= '<label><input type="checkbox" class="checkbox-print"></label>';
            $html .= '</div>';
            $html .= '<div class="card-info">';
            $html .= '<div class="tag">'.$areaName.'</div>';
            $html .= '<div class="hospital-info" data-id="'.$hospital->id.'" data-cancer-id="'.$cancers[0].'">';
            $html .= '<h2>'.$hospital->hospital_name.'</h2>';
            $html .= '<a target="_blank" href="'.$hospital->hp_url.'"><span class="info-icon"><img src="../img/icons/icon-go-home.png" alt="icon-home"> ホームページはこちら（外部リンク)</span></a>';
            $html .= '<p class="m-b-0">'.$hospital->addr.'</p>';
            $html .= '<p>'.($hospital->tel ? $hospital->tel . ' (代表)': '').'</p>';
            $html .= '</div>';
            $html .= '<div class="categories">';

            foreach ($categories as $category) {
                $html .= '<span>'.$category.'</span>';
            }

            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="card-stats">';
            $html .= '<div class="hospital-rank">';
            $html .= '<div class="header1 m-b-15">都道府県</div>';
            $html .= '<div class="header2 m-b-15">地方</div>';
            $html .= '<div class="header3 m-b-15">全国</div>';
            $html .= '<div class="header4 m-b-15"></div>';
            $html .= $this->renderRankStat('stat1', $avgData['avgPrefDpcRank'], "../img/icons/");
            $html .= $this->renderRankStat('stat2',  $avgData['avgAreaDpcRank'], "../img/icons/");
            $html .= $this->renderRankStat('stat3', $avgData['avgGlobalDpcRank'], "../img/icons/");
            $html .= '<div class="stat4 stat m-b-25"><div class="stat-info stat-info-extended rank-row-1"><p>年間入院患者数:</p><p>'.($avgData['avgDpc'] ? number_format($avgData['avgDpc']) . '人' : "-").'</p></div></div>';
            $html .= $this->renderRankStat('stat5', $avgData['avgPrefNewNumRank'], "../img/icons/");
            $html .= $this->renderRankStat('stat6', $avgData['avgLocalNewNumRank'], "../img/icons/");
            $html .= $this->renderRankStat('stat7', $avgData['avgGlobalNewNumRank'], "../img/icons/");
            $html .= '<div class="stat8 stat m-b-25"><div class="stat-info stat-info-extended rank-row-2"><p>年閒新現患者数:</p><p>'.($avgData['avgNewNum'] ? number_format($avgData['avgNewNum']) . '人' : "-").'</p></div></div>';
            $html .= $this->renderRankStat('stat9', $avgData['avgPrefRate'], "../img/icons/");
            $html .= $this->renderRankStat('stat10', $avgData['avgLocalRate'], "../img/icons/");
            $html .= $this->renderRankStat('stat11', $avgData['avgGlobalRate'], "../img/icons/");
            $html .= '<div class="stat12 stat m-b-25"><div class="stat-info stat-info-extended rank-row-3"><p>5年後生存率係数:</p><p>'.($avgData['avgSurvivalRate'] ?? "-").'</p></div></div>';
            $html .= '</div>';
            $html .= '<div class="dpc-info">';
            $html .= '<p>DPC治療指数: <span>'.($avgData['avgDpc'] ? number_format($avgData['avgDpc']) : "-").'</span></p>';
            $html .= '<a target="_bank" href="detail/index.php?id='.$hospital->id.'&cancerId='.$cancers[0].'" class="detail-button">この医療機関の詳細を見る</a>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        });

        return [
            'status' => true,
            'data' => [
                'html' => [$html, $totalNumber]
            ]
        ];
    }

    public function printHospitalList($post)
    {
        $selectedItems = $_POST['selectedItems'] ?? [];
        $pdfFiles = [];

        foreach ($selectedItems as $item) {
            $hospitalId = $item['hospitalId'] ?? null;
            $cancerId = $item['cancerId'] ?? null;
            $param = $this->getPrintData($hospitalId, $cancerId);

            if (empty($param)) continue;

            extract($param);
            ob_start();
            include '../../hospital/pdf/hospital-sample-pdf.php';
            $html = ob_get_clean();

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();


            $pdfFilePath = "download_files/hospital/{$param['hospitalName']}-{$hospitalId}.pdf";
            file_put_contents('../../'.$pdfFilePath, $dompdf->output());
            $pdfFiles[] = $pdfFilePath;
        }

        return [
            'status' => true,
            'data' => [
                'pdfFiles' => $pdfFiles
            ]
        ];
    }

    private function renderRankStat($class, $rank, $imgPath)
    {
        $roundedRank = $rank ?? '-';
        if (in_array($roundedRank, [1, 2, 3])) {
            $html = '<div class="'.$class.' rank-icon m-b-25 h-27">';
            $html .= '<div class="rank-icon"><img src="' . $imgPath . 'rank' . $roundedRank . '.png" alt="rank-img"></div>';
        } else {
            $html = '<div class="'.$class.' m-b-25 h-27">';
            $html .= ($roundedRank === '-') ? '-' : $roundedRank . '位';
        }

        $html .= '</div>';
        return $html;
    }

    private function getPrintData ($hospitalId, $cancerId)
    {
        $hospital = Hospital::where('id', $hospitalId)
            ->where(['del_flg' => Hospital::NOT_DELETED, 'public_flg' => Hospital::PUBLISHED])
            ->whereHas('cancers', function ($query) use ($cancerId) {
                $query->where('m_cancer.id', $cancerId);
                $query->where(['m_cancer.del_flg' => Cancer::NOT_DELETED, 'm_cancer.public_flg' => Cancer::PUBLISHED]);
            })->first();

        if (!$hospital) {
            return [];
        }

        $cancer = Cancer::select('cancer_type')->where('id', $cancerId)->first();
        $categories = $hospital->categories()
            ->where(function ($query) {
                $query->where('data_type', Category::HOSPITAL_DETAIL_TYPE);
                $query->orWhere('data_type', Category::HOSPITAL_TREATMENT_TYPE);
            })
            ->where(['del_flg' => Category::NOT_DELETED, 'public_flg' => Category::PUBLISHED])
            ->get();

        $hospitalType = $categories->firstWhere('hard_name2', 'hospital_type');
        $hospitalGen = $categories->firstWhere('hard_name2', 'hospital_gen');
        $specialClinic = $categories->firstWhere('hard_name3', 'special_clinic');
        $advancedMedical = $categories->firstWhere('hard_name3', 'advanced_medical');
        $famousDoctor = $categories->firstWhere('hard_name3', 'famous_doctor');
        $multiTreatment = $categories->firstWhere('hard_name3', 'multi_treatment');

        $treatment = $hospital->categories()
            ->where(['del_flg' => Category::NOT_DELETED, 'public_flg' => Category::PUBLISHED])
            ->where('data_type', Category::HOSPITAL_TREATMENT_TYPE)
            ->pluck('level3')
            ->implode(' ');

        $yearSummaryDpc = $hospital->dpcs()
            ->select('year')
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->pluck('year')
            ->implode('、');

        $avgDpc = $hospital->dpcs()
            ->select('n_dpc')
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->avg('n_dpc');

        $yearSummaryStage = $hospital->stages()
            ->select('year')
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->pluck('year')
            ->implode('、');

        $avgNewNum = $hospital->stages()
            ->select('total_num_new')
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->avg('total_num_new');

        $yearSummarySurvival = $hospital->survivals()
            ->select('year')
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->pluck('year')
            ->implode('、');

        $avgSurvivalRate = $hospital->survivals()
            ->select('survival_rate')
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->avg('survival_rate');

        return [
            'baseUrl' => BASE_URL,
            'hospitalName' => $hospital->hospital_name,
            'cancerName' => $cancer->cancer_type,
            'yearSummaryDpc' => $yearSummaryDpc,
            'yearSummaryStage' => $yearSummaryStage,
            'yearSummarySurvival' => $yearSummarySurvival,
            'avgDpc' => $avgDpc ? round($avgDpc) : null,
            'avgNewNum' => $avgNewNum ? round($avgNewNum) : null,
            'avgSurvivalRate' => $avgSurvivalRate ? round($avgSurvivalRate, 2) : null,
            'hospitalTel' => $hospital->tel,
            'hospitalAddress' => $hospital->addr,
            'hospitalUrl' => $hospital->hp_url,
            'hospitalSpUrl' => $hospital->support_url,
            'hospitalScUrl' => $specialClinic?->pivot->content1,
            'hospitalType' => $hospitalType?->level3,
            'hospitalGen' => $hospitalGen?->level3,
            'hasAdvancedMedical' => $advancedMedical ? 1 : 0,
            'advancedMedical' => $advancedMedical?->pivot->content1,
            'famousDoctor' => $famousDoctor ? 1 : 0,
            'multiTreatment' => $multiTreatment ? 1 : 0,
            'treatment' => $treatment
        ];
    }
}
