<?php

use App\Models\Area;
use App\Models\Cancer;
use App\Models\Category;
use App\Models\DPC;
use App\Models\Hospital;
use App\Models\HospitalCategory;

if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../../common/security_common_logic.php';
require_once __DIR__ . "/../../logic/front/auth_logic.php";
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
        $auth_logic = new auth_logic();
        $auth_logic->check_authentication();

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
        $auth_logic = new auth_logic();
        $auth_logic->check_authentication();
    }

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
                    return $subItems->first()->order_num;
                });
            });

        return [
            'cancer' => $cancer->toArray(),
            'area' => $area->toArray(),
            'category' => $category->toArray(),
        ];
    }

    public function searchHospitalList($get)
    {
        $cancers = $get['data']['cancer'] ?? [];
        $areaRaw = $get['data']['area'] ?? [];
        $categoryRaw = $get['data']['category'] ?? [];
        $keyword = $get['data']['keyword'] ?? '';
        $page = $get['data']['page'] ?? 1;
        $limit = $get['data']['limit'] ?? 5;

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

        $categories = [];
        array_walk_recursive($categoryRaw, function($value) use (&$categories) {
            if ($value !== "") {
                $categories[] = $value;
            }
        });

        $query = Hospital::query();

        if ($keyword != '') {
            $query->where('hospital_name', 'like', "%$keyword%");
        }

        $query->whereHas('cancers', function ($query) use ($cancers) {
            $query->whereIn('m_cancer.id', $cancers);
        });

        $areaSelectSql = !empty($areas) ? '(t_hospital.area_id IN ('.implode(',', $areas).'))' : '0';

        if (!empty($categories)) {
            $categoryMatchCountQuery = HospitalCategory::selectRaw('hospital_id, COUNT(*) as category_match_count')
                ->whereIn('category_id', $categories)
                ->where(function ($sub) use ($cancers) {
                    $sub->whereNull('cancer_id');
                    $sub->orWhereIn('cancer_id', $cancers);
                })
                ->groupBy('hospital_id');

            $query->leftJoinSub($categoryMatchCountQuery, 'category_matches', 't_hospital.id', '=', 'category_matches.hospital_id');

            $query->selectRaw('t_hospital.*, '.$areaSelectSql.' + COALESCE(category_match_count, 0) as total_match_count')
                ->orderByDesc('total_match_count');
        } else {
            $query->selectRaw('t_hospital.*, '.$areaSelectSql.' as total_match_count')
                ->orderByDesc('total_match_count');
        }

        $hospitals = $query->paginate($limit, ['*'], 'page', $page);
        $totalPages = $hospitals->lastPage();

        $html = '';
        $hospitals->each(function ($hospital) use ($cancers, &$html) {
            $areaName = $hospital->area->pref_name;
            $categories = $hospital->categories()->select('level3')
                ->where('data_type', Category::HOSPITAL_DETAIL_TYPE)
                ->whereIn('level2', ['病院区分', 'ゲノム拠点病院区分'])
                ->get()
                ->pluck('level3');

            $avgDpc = $hospital->dpcs()->select('n_dpc')->where('cancer_id', $cancers[0])
                ->orderBy('year', 'desc')
                ->take(3)
                ->pluck('n_dpc')
                ->avg();

            $stages = $hospital->stages()
                ->select(['total_num_new', 'total_num_rank', 'local_num_rank', 'pref_num_rank'])
                ->where('cancer_id', $cancers[0])
                ->orderBy('year', 'desc')
                ->take(3)
                ->get();

            $avgNewNum = $stages->avg('total_num_new');
            $avgGlobalNewNumRank = $stages->avg('total_num_rank');
            $avgLocalNewNumRank = $stages->avg('local_num_rank');
            $avgPrefNewNumRank = $stages->avg('pref_num_rank');

            $survivals = $hospital->survivals()
                ->select([
                    'total_num',
                    'survival_rate',
                    'total_stage_total_taget',
                    'local_stage_total_taget',
                    'pref_stage_total_taget',
                    'total_survival_rate',
                    'local_survival_rate',
                    'pref_survival_rate'
                ])
                ->where('cancer_id', $cancers[0])
                ->orderBy('year', 'desc')
                ->take(3)
                ->get();

            $avgTotalNum = $survivals->avg('total_num');
            $avgGlobalTotalNumRank = $survivals->avg('total_stage_total_taget');
            $avgLocalTotalNumRank = $survivals->avg('local_stage_total_taget');
            $avgPrefTotalNumRank = $survivals->avg('pref_stage_total_taget');
            $avgSurvivalRate = $survivals->avg('survival_rate');
            $avgGlobalRate = $survivals->avg('total_survival_rate');
            $avgLocalRate = $survivals->avg('local_survival_rate');
            $avgPrefRate = $survivals->avg('pref_survival_rate');

            $html .= '<div class="hospital-card">';
            $html .= '<div class="card-checkbox">';
            $html .= '<label><input type="checkbox" class="checkbox-print"></label>';
            $html .= '</div>';
            $html .= '<div class="card-info">';
            $html .= '<div class="tag">'.$areaName.'</div>';
            $html .= '<div class="hospital-info">';
            $html .= '<h2>'.$hospital->hospital_name.'</h2>';
            $html .= '<a href="'.$hospital->hp_url.'"><span class="info-icon"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> (ホームページが開設されます)</span></a>';
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
            $html .= $this->renderRankStat('stat1', $avgPrefTotalNumRank, "../img/icons/");
            $html .= $this->renderRankStat('stat2', $avgLocalTotalNumRank, "../img/icons/");
            $html .= $this->renderRankStat('stat3', $avgGlobalTotalNumRank, "../img/icons/");
            $html .= '<div class="stat4 stat m-b-25"><div class="stat-info stat-info-extended rank-row-1"><p>年間入院患者数:</p><p>'.($avgTotalNum ? number_format(round($avgTotalNum)) : "-").'人</p></div></div>';
            $html .= $this->renderRankStat('stat5', $avgPrefNewNumRank, "../img/icons/");
            $html .= $this->renderRankStat('stat6', $avgLocalNewNumRank, "../img/icons/");
            $html .= $this->renderRankStat('stat7', $avgGlobalNewNumRank, "../img/icons/");
            $html .= '<div class="stat8 stat m-b-25"><div class="stat-info stat-info-extended rank-row-2"><p>年閒新現患者数:</p><p>'.($avgNewNum ? number_format(round($avgNewNum)) : "-").'人</p></div></div>';
            $html .= $this->renderRankStat('stat9', $avgPrefRate, "../img/icons/");
            $html .= $this->renderRankStat('stat10', $avgLocalRate, "../img/icons/");
            $html .= $this->renderRankStat('stat11', $avgGlobalRate, "../img/icons/");
            $html .= '<div class="stat12 stat m-b-25"><div class="stat-info stat-info-extended rank-row-3"><p>5年後生存率係败:</p><p>'.($avgSurvivalRate ? round($avgSurvivalRate, 2) : "-").'</p></div></div>';
            $html .= '</div>';
            $html .= '<div class="dpc-info">';
            $html .= '<p>DPC治療指数: <span>'.($avgDpc ?? "-").'</span></p>';
            $html .= '<button class="confirm-button">この医療機関の詳細を見る</button>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        });

        return [
            'status' => true,
            'data' => [
                'html' => $html,
                'totalPages' => $totalPages
            ]
        ];
    }

    private function renderRankStat($class, $rank, $imgPath) {
        $roundedRank = $rank ? round($rank) : "-";
        if (in_array($roundedRank, [1, 2, 3])) {
            $html = '<div class="'.$class.' rank-icon m-b-25 h-27">';
            $html .= '<div class="rank-icon"><img src="' . $imgPath . 'rank' . $roundedRank . '.png" alt="rank-img"></div>';
        } else {
            $html = '<div class="'.$class.' m-b-25 h-27">';
            $html .= $roundedRank . '位';
        }

        $html .= '</div>';
        return $html;
    }
}
