<?php

use App\Models\Area;
use App\Models\Cancer;
use App\Models\Category;
use App\Models\Hospital;

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

if (isset($_REQUEST['method'])) {
    $ct = new f_hospital_ct();
    $data = $ct->mainAjaxGet($_REQUEST);
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
    {}

    public function searchPageIndex() {
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
                'data_type' => Category::HOSPITAL_DETAIL_TYPE,
                'public_flg' => Category::PUBLISHED,
                'del_flg' => Category::NOT_DELETED
            ])
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
        $hospital = Hospital::where('id', $id)->whereHas('cancers', function ($query) use ($cancerId) {
            $query->where('m_cancer.id', $cancerId);
        })->first();

        if (!$hospital) {
            header("Location: " . BASE_URL . "error/404_page.php");
            exit();
        }

        $cancer = Cancer::select('cancer_type')->where('id', $cancerId)->first();

        $categories = $hospital->categories()
            ->where('data_type', Category::HOSPITAL_DETAIL_TYPE)
            ->orWhere('data_type', Category::HOSPITAL_TREATMENT_TYPE)
            ->get();

        $hospitalType = $categories->firstWhere('hard_name2', 'hospital_type');
        $hospitalGen = $categories->firstWhere('hard_name2', 'hospital_gen');
        $specialClinic = $categories->firstWhere('hard_name3', 'special_clinic');
        $advancedMedical = $categories->firstWhere('hard_name3', 'advanced_medical');

        $treatment = $hospital->categories()->where('data_type', Category::HOSPITAL_TREATMENT_TYPE)
            ->pluck('level3')
            ->implode(' ');

        $avgData = $hospital->calculateAvgCommonData($cancerId);

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
            'treatment' => $treatment
        ];

        return [
            'cancerName' => $cancer->cancer_type,
            'avgData' => $avgData,
            'infoHospital' => $infoHospital,
            'infoTreatment' => $infoTreatment,
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
            $html .= '<div class="hospital-info">';
            $html .= '<h2>'.$hospital->hospital_name.'</h2>';
            $html .= '<a href="'.$hospital->hp_url.'"><span class="info-icon"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> ホームページはこちら（外部リンク)</span></a>';
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

    private function renderRankStat($class, $rank, $imgPath) {
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
}
