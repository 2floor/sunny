<?php

use App\Models\Area;
use App\Models\Cancer;
use App\Models\Category;
use App\Models\Hospital;
use App\Models\SurvAverage;
use App\Models\HospitalUser;
use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../../common/security_common_logic.php';
require_once __DIR__ . "/../../logic/front/auth_logic.php";
require_once __DIR__ . "/../../logic/front/f_hospital_logic.php";
require_once __DIR__ . '/../../third_party/bootstrap.php';
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
    protected $auth_logic;

    public function  __construct()
    {
        $this->auth_logic = new auth_logic();
    }

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

        if ($post['method'] == 'createRemark') {
            $data = $this->createRemark($post);
        }

        if ($post['method'] == 'updateRemark') {
            $data = $this->updateRemark($post);
        }

        return $data;
    }

    public function searchPageIndex($categoryType)
    {
        $permSH = $this->auth_logic->check_permission('search.hospital');
        if (!$permSH) {
            return [];
        }

        $cancer = Cancer::select('id', 'cancer_type')
            ->orderBy('order_num', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $area = Area::select('id', 'area_name', 'prec_cd', 'pref_name')
            ->get()
            ->groupBy('area_name');

        $category = Category::select('id', 'level1', 'level2', 'level3', 'order_num2', 'order_num3')
            ->where([
                'category_group' => Category::HOSPITAL_GROUP,
            ])
            ->when($categoryType == 'detail', function ($query) {
                $query->where('data_type', Category::HOSPITAL_DETAIL_TYPE);
            })
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
                            ];
                        });
                    })->sortBy(function ($groupedItems) {
                        return $groupedItems->first()['order_num3'];
                    });
                })->sortBy(function ($subItems, $key) {
                    return $subItems->first()->first()['order_num2'];
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
        $permVDH = $this->auth_logic->check_permission('view.detail.hospital');
        if (!$permVDH) {
            return [];
        }

        $hospital = Hospital::where('id', $id)
            ->whereHas('cancers', function ($query) use ($cancerId) {
                $query->where('m_cancer.id', $cancerId);
            })->first();

        if (!$hospital) {
            header("Location: " . BASE_URL . "error/404_page.php");
            exit();
        }

        $cancer = Cancer::select('cancer_type', 'cancer_type_dpc', 'cancer_type_stage', 'cancer_type_surv')->where('id', $cancerId)->first();
        $avgData = $hospital->calculateAvgCommonData($cancerId);

        $yearDpc = $hospital->dpcs()
            ->select('year')
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->pluck('year')
            ->sort()
            ->implode('、');

        $yearStage = $hospital->stages()
            ->select('year')
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->pluck('year')
            ->sort()
            ->implode('、');

        $yearSurvival = $hospital->survivals()
            ->select('year')
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->pluck('year')
            ->sort()
            ->map(function ($year) {
                return $year . '～' . ($year + 1) .'年';
            })
            ->implode('、');

        $categories = $hospital->categories()
            ->where(function ($query) {
                $query->where('data_type', Category::HOSPITAL_DETAIL_TYPE);
                $query->orWhere('data_type', Category::HOSPITAL_TREATMENT_TYPE);
            })
            ->get();

        $hospitalType = $categories->firstWhere('hard_name2', 'hospital_type');
        $hospitalGen = $categories->firstWhere('hard_name2', 'hospital_gen');
        $specialClinic = $categories->firstWhere('hard_name3', 'special_clinic');
        $lightCare = $categories->firstWhere('hard_name3', 'light_care');

        $advancedMedical = $categories->filter(function ($value) use ($cancerId) {
            return $value->pivot['cancer_id'] == $cancerId;
        })->firstWhere('hard_name3', 'advanced_medical');

        $famousDoctor = $categories->filter(function ($value) use ($cancerId) {
            return $value->pivot['cancer_id'] == $cancerId;
        })->firstWhere('hard_name3', 'famous_doctor');

        $multiTreatment = $categories->filter(function ($value) use ($cancerId) {
            return $value->pivot['cancer_id'] == $cancerId;
        })->firstWhere('hard_name3', 'multi_treatment');

        $hc = $hospital->cancers()->where('cancer_id', $cancerId)?->first();
        $treatment = $hc?->pivot?->sp_treatment;
        $cancerSocial = $hc?->pivot?->social_info;

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
            'specialClinicUrl' => $specialClinic?->pivot->content1,
            'lightCare' => $lightCare?->pivot->content1,
        ];

        $infoTreatment = [
            'hospitalType' => $hospitalType?->level3,
            'hospitalGen' => $hospitalGen?->level3,
            'hasAdvancedMedical' => $advancedMedical ? 1 : 0,
            'advancedMedical' => $advancedMedical?->pivot->content1,
            'famousDoctor' => $famousDoctor ? 1 : 0,
            'multiTreatment' => $multiTreatment ? 1 : 0,
            'treatment' => $treatment,
            'cancerSocial' => $cancerSocial,
            'commonSocial' => $hospital->social_info
        ];

        $remarks = $this->getRemarksList($hospital);

        return [
            'cancerName' => $cancer->cancer_type,
            'cancerNameDPC' => $cancer->cancer_type_dpc,
            'cancerNameStage' => $cancer->cancer_type_stage,
            'cancerNameSurv' => $cancer->cancer_type_surv,
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
            'remarks' => $remarks,
        ];
    }

    public function searchHospitalList($get)
    {
        $permSH = $this->auth_logic->check_permission('search.hospital');
        if (!$permSH) {
            return [
                'status' => false,
                'data' => []
            ];
        }

        $permVDH = $this->auth_logic->check_permission('view.detail.hospital');

        $cancers = $get['cancer'] ?? [];
        $areaRaw = $get['area'] ?? [];
        $categories = $get['category'] ?? [];
        $stages = $get['stage'] ?? [];
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
        $data = $hospitalLogic->getHospitalsFromFilter($keyword, $cancers, $areas, $categories, $stages, $sort, $page, $limit);
        $totalNumber = $data['total'];
        $hospitals = $data['hospitals'];

        $html = '';
        $hospitals->each(function ($hospital) use ($cancers, &$html, $permVDH) {
            $areaName = $hospital->area->pref_name;
            $categories = $hospital->categories()->select('level3')
                ->where('data_type', Category::HOSPITAL_DETAIL_TYPE)
                ->whereIn('hard_name2', ['hospital_type', 'hospital_gen'])
                ->get()
                ->pluck('level3');

            $categoryTreatment = $hospital->categories()
                ->where(function ($query) use ($cancers) {
                    $query->where('t_category_hospital.cancer_id', $cancers[0]);
                    $query->orWhereNull('t_category_hospital.cancer_id');
                })
                ->select('hard_name3')
                ->where('data_type', Category::HOSPITAL_DETAIL_TYPE)
                ->whereIn('hard_name3', [
                    'multi_treatment', 'famous_doctor', 'advanced_medical', 'special_clinic'
                ])
                ->get()
                ->pluck('hard_name3')->toArray();

            $treatment = $hospital->cancers()->where('cancer_id', $cancers[0])?->first()?->pivot?->sp_treatment;
            $social_info = $hospital->cancers()->where('cancer_id', $cancers[0])?->first()?->pivot?->social_info;

            $avgData = $hospital->calculateAvgCommonData($cancers[0]);

            $html .= '<div class="hospital-card">';
            $html .= '<div class="card-checkbox">';
            $html .= '<label><input type="checkbox" class="checkbox-print"></label>';
            $html .= '</div>';
            $html .= '<div class="card-info">';
            $html .= '<div class="tag">'.$areaName.'</div>';
            $html .= '<div class="hospital-info" data-id="'.$hospital->id.'" data-cancer-id="'.$cancers[0].'">';
            $html .= '<h3>'.$hospital->hospital_name.'</h3>';
            $html .= '<a target="_blank" href="'.$hospital->hp_url.'"><span class="info-icon"><img src="../img/icons/icon-go-home.png" alt="icon-home"> ホームページはこちら（外部リンク)</span></a>';
            $html .= '<p class="m-b-0 m-t-20">'.$hospital->addr.'</p>';
            $html .= '<p>'.($hospital->tel ? $hospital->tel . ' (代表)': '').'</p>';
            $html .= '</div>';
            $html .= '<div class="categories">';

            foreach ($categories as $category) {
                $html .= '<span>'.$category.'</span>';
            }

            $html .= '</div>';
            $html .= '<div class="treatment-info">';
            $html .= '<div class="treatment-item"><span>集学的治療体制</span><span '.(in_array('multi_treatment', $categoryTreatment) ? 'class="has-treatment">あり' : '>なし') .'</span></div>';
            $html .= '<div class="treatment-item"><span>名医の在籍あり</span><span '.(in_array('famous_doctor', $categoryTreatment) ? 'class="has-treatment">あり' : '>なし') .'</span></div>';
            $html .= '<div class="treatment-item"><span>先進医療</span><span '.(in_array('advanced_medical', $categoryTreatment) ? 'class="has-treatment">あり' : '>なし') .'</span></div>';
            $html .= '<div class="treatment-item"><span>特別室</span><span '.(in_array('special_clinic', $categoryTreatment) ? 'class="has-treatment">あり' : '>なし') .'</span></div>';
            $html .= '<div class="treatment-item"><span>特別な治療法</span><span '.($treatment ? 'class="has-treatment">あり' : '>なし') .'</span></div>';
            $html .= '</div>';
            $html .= '<div class="treatment-info">';
            $html .= '<div class="social-info '.($social_info ? '' : 'empty').'"><span>'.($social_info ? nl2br(e($social_info)) : '').'</span></div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="card-stats">';
            $html .= '<div class="card-outer-header">';
            $html .= '<div class="outer-header-hidden"></div>';
            $html .= '<div class="outer-header-show">都道府県</div>';
            $html .= '<div class="outer-header-show">地方</div>';
            $html .= '<div class="outer-header-show">全国</div>';
            $html .= '</div>';
            $html .= '<div class="hospital-rank">';
            $html .= '<div class="row-rank">';
            $html .= '<div class="row-rank-header">';
            $html .= '<div class="rank-header-top">入院患者数</div>';
            $html .= '<div class="rank-header-title">（直近3年平均）</div>';
            $html .= '<div class="rank-header-content">'.(is_numeric($avgData['avgDpc']) ? number_format($avgData['avgDpc']) . '人' : "-").'</div>';
            $html .= '</div>';
            $html .= '<div class="row-rank-content">';
            $html .= $this->renderRankStat('stat1', $avgData['avgPrefDpcRank'], "../img/icons/");
            $html .= '</div>';
            $html .= '<div class="row-rank-content">';
            $html .= $this->renderRankStat('stat2',  $avgData['avgAreaDpcRank'], "../img/icons/");
            $html .= '</div>';
            $html .= '<div class="row-rank-content">';
            $html .= $this->renderRankStat('stat3', $avgData['avgGlobalDpcRank'], "../img/icons/");
            $html .= '</div>';
            $html .= '</div>';

            $html .= '<div class="row-rank">';
            $html .= '<div class="row-rank-header">';
            $html .= '<div class="rank-header-top">新規がん患者数</div>';
            $html .= '<div class="rank-header-title">（直近3年平均）</div>';
            $html .= '<div class="rank-header-content">'.(is_numeric($avgData['avgNewNum']) ? number_format($avgData['avgNewNum']) . '人' : "-").'</div>';
            $html .= '</div>';
            $html .= '<div class="row-rank-content">';
            $html .= $this->renderRankStat('stat5', $avgData['avgPrefNewNumRank'], "../img/icons/");
            $html .= '</div>';
            $html .= '<div class="row-rank-content">';
            $html .= $this->renderRankStat('stat6', $avgData['avgLocalNewNumRank'], "../img/icons/");
            $html .= '</div>';
            $html .= '<div class="row-rank-content">';
            $html .= $this->renderRankStat('stat7', $avgData['avgGlobalNewNumRank'], "../img/icons/");
            $html .= '</div>';
            $html .= '</div>';

            $html .= '<div class="row-rank">';
            $html .= '<div class="row-rank-header">';
            $html .= '<div class="rank-header-top">5年生存率係数</div>';
            $html .= '<div class="rank-header-title">（直近3年平均）</div>';
            $html .= '<div class="rank-header-content">'.(is_numeric($avgData['avgSurvivalRate']) ? $avgData['avgSurvivalRate'] : "-").'</div>';
            $html .= '</div>';
            $html .= '<div class="row-rank-content">';
            $html .= $this->renderRankStat('stat9', $avgData['avgPrefRate'], "../img/icons/");
            $html .= '</div>';
            $html .= '<div class="row-rank-content">';
            $html .= $this->renderRankStat('stat10', $avgData['avgLocalRate'], "../img/icons/");
            $html .= '</div>';
            $html .= '<div class="row-rank-content">';
            $html .= $this->renderRankStat('stat11', $avgData['avgGlobalRate'], "../img/icons/");
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="footer-card">' .($permVDH ? ('<a target="_bank" href="detail/index.php?id='.$hospital->id.'&cancerId='.$cancers[0].'" class="detail-button">この医療機関の詳細を見る &#8594;</a>') : '') .'</div>';
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
        $permPrH = $this->auth_logic->check_permission('print.hospital.pdf');
        if (!$permPrH) {
            return [
                'status' => false,
                'data' => []
            ];
        }

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
        $roundedRank = is_numeric($rank) ? $rank : '-';
        if (in_array($roundedRank, [1, 2, 3])) {
            $html = '<div class="'.$class.' rank-icon">';
            $html .= '<div class="rank-icon"><img src="' . $imgPath . 'rank' . $roundedRank . '.png" alt="rank-img"></div>';
        } else {
            $html = '<div class="'.$class.'">';
            $html .= ($roundedRank === '-') ? '-' : $roundedRank . '位';
        }

        $html .= '</div>';
        return $html;
    }

    private function getPrintData ($hospitalId, $cancerId)
    {
        $hospital = Hospital::where('id', $hospitalId)
            ->whereHas('cancers', function ($query) use ($cancerId) {
                $query->where('m_cancer.id', $cancerId);
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
            ->get();

        $hospitalType = $categories->firstWhere('hard_name2', 'hospital_type');
        $hospitalGen = $categories->firstWhere('hard_name2', 'hospital_gen');
        $specialClinic = $categories->firstWhere('hard_name3', 'special_clinic');
        $lightCare = $categories->firstWhere('hard_name3', 'light_care');

        $advancedMedical = $categories->filter(function ($value) use ($cancerId) {
            return $value->pivot['cancer_id'] == $cancerId;
        })->firstWhere('hard_name3', 'advanced_medical');

        $famousDoctor = $categories->filter(function ($value) use ($cancerId) {
            return $value->pivot['cancer_id'] == $cancerId;
        })->firstWhere('hard_name3', 'famous_doctor');

        $multiTreatment = $categories->filter(function ($value) use ($cancerId) {
            return $value->pivot['cancer_id'] == $cancerId;
        })->firstWhere('hard_name3', 'multi_treatment');

        $hc = $hospital->cancers()->where('cancer_id', $cancerId)?->first();
        $treatment = $hc?->pivot?->sp_treatment;
        $cancerSocial = $hc?->pivot?->social_info;

        $yearSummaryDpc = $hospital->dpcs()
            ->select('year')
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->pluck('year')
            ->sort()
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
            ->sort()
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
            ->sort()
            ->map(function ($year) {
                return $year . '～' . ($year + 1) .'年';
            })
            ->implode('、');

        $avgSurvivalRate = $hospital->survivals()
            ->select('survival_rate')
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->avg('survival_rate');

        $dpcs = $hospital->dpcs()
            ->select(['year', 'n_dpc', 'rank_nation_dpc', 'rank_area_dpc', 'rank_pref_dpc'])
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->get();

        $stages = $hospital->stages()
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

        return [
            'baseUrl' => BASE_URL,
            'hospitalName' => $hospital->hospital_name,
            'cancerName' => $cancer->cancer_type,
            'yearSummaryDpc' => $yearSummaryDpc,
            'yearSummaryStage' => $yearSummaryStage,
            'yearSummarySurvival' => $yearSummarySurvival,
            'avgDpc' => is_numeric($avgDpc) ? round($avgDpc, 1) : null,
            'avgNewNum' => is_numeric($avgNewNum) ? round($avgNewNum, 1) : null,
            'avgSurvivalRate' => is_numeric($avgSurvivalRate) ? round($avgSurvivalRate, 2) : null,
            'hospitalTel' => $hospital->tel,
            'hospitalAddress' => $hospital->addr,
            'hospitalUrl' => $hospital->hp_url,
            'hospitalSpUrl' => $hospital->support_url,
            'hospitalScUrl' => $specialClinic?->pivot->content1,
            'lightCare' => $lightCare?->pivot->content1,
            'hospitalType' => $hospitalType?->level3,
            'hospitalGen' => $hospitalGen?->level3,
            'hasAdvancedMedical' => $advancedMedical ? 1 : 0,
            'advancedMedical' => $advancedMedical?->pivot->content1,
            'famousDoctor' => $famousDoctor ? 1 : 0,
            'multiTreatment' => $multiTreatment ? 1 : 0,
            'treatment' => $treatment,
            'dpcs' => $dpcs,
            'stages' => $stages,
            'survivals' => $survivals,
            'averageSurv' => $averageSurv,
            'cancerSocial' => $cancerSocial,
            'commonSocial' => $hospital->social_info,
        ];
    }

    public function createRemark($post)
    {
        $permAHN = $this->auth_logic->check_permission('add.hospital.note');
        if (!$permAHN) {
            return [
                'status' => false,
                'data' => []
            ];
        }

        $data = $post['data'] ?? [];
        $hospitalId = $data['hospitalId'] ?? null;
        $hospital = Hospital::find($hospitalId);

        if (!$hospital) {
            return [
                'status' => false,
                'data' => []
            ];
        }

        $hospital->users()->attach([ $_SESSION['authentication']['login_user']['id'] => [
            'remarks' => $data['remarks'] ?? '',
            'approved_time' => date('Y-m-d H:i:s'),
            ],
        ]);

        $remarks = $this->getRemarksList($hospital);

        return [
            'status' => true,
            'data' => $remarks
        ];
    }

    public function updateRemark($post)
    {
        $permAHN = $this->auth_logic->check_permission('add.hospital.note');
        if (!$permAHN) {
            return [
                'status' => false,
                'data' => []
            ];
        }

        $data = $post['data'] ?? [];
        $idPivot = $data['id'] ?? null;
        $hospitalId = $data['hospitalId'] ?? null;
        $updateType = $data['updateType'] ?? null;
        $hospital = Hospital::find($hospitalId);

        if (!$hospital || !$idPivot || !$updateType) {
            return [
                'status' => false,
                'data' => []
            ];
        }

        if ($updateType == 'update') {
            $updateData = [
                'remarks' => $data['remarks'],
                'updated_at' => date('Y-m-d H:i:s')
            ];
        } else {
            $updateData = [
                'del_flg' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        HospitalUser::where('id', $idPivot)->update($updateData);
        $remarks = $this->getRemarksList($hospital);

        return [
            'status' => true,
            'data' => $remarks
        ];
    }

    private function getRemarksList($hospital)
    {
        $hospital->refresh();
        $loginUser = $_SESSION['authentication']['login_user'];
        $remarks = $hospital->users()->where('t_user.id', $loginUser['id'] ?? null)->get();
        return !empty($remarks) ? $remarks->map(function ($item) use ($loginUser) {
            $pivotData = $item->pivot->toArray();
            if (isset($pivotData['remarks'])) {
                $pivotData['remarks'] =  nl2br(e($pivotData['remarks']));
            }

            return array_merge($pivotData, ['author' => $loginUser['name']]);
        })->sortByDesc('updated_at')->values()->toArray() : [];
    }
}
