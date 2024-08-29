<?php

use App\Models\Hospital;
use App\Models\Category;
use App\Models\Stage;
use App\Models\SurvHospital;

class f_hospital_logic
{
    public function getHospitalsFromFilter($keyword, $cancers, $areas, $categories, $stages, $sort, $page, $limit)
    {
        $query = Hospital::query();

        if ($keyword != '') {
            $query->where(function ($query2) use ($keyword, $cancers) {
                $query2->where('hospital_name', 'like', "%$keyword%");
                $query2->orWhereHas('categories', function ($query3) use ($keyword, $cancers) {
                    $query3->where('data_type' , Category::HOSPITAL_TREATMENT_TYPE);
                    $query3->where('level3', 'like', "%$keyword%");
                    $query3->where(function ($query4) use ($cancers) {
                        $query4->whereNull('t_category_hospital.cancer_id');
                        $query4->orWhereIn('t_category_hospital.cancer_id', $cancers);
                    });
                });
            });
        }

        $query->whereHas('cancers', function ($query) use ($cancers) {
            $query->whereIn('m_cancer.id', $cancers);
        });

        if (!empty($areas)) {
            $query->whereIn('area_id', $areas);
        }

        if (!empty($categories)) {
            $query->whereHas('categories', function ($query) use ($categories, $cancers) {
                $query->whereIn('t_category.id', $categories);
                $query->where(function ($query2) use ($cancers) {
                    $query2->whereNull('t_category_hospital.cancer_id');
                    foreach ($cancers as $cancer) {
                        $query2->orWhereRaw('FIND_IN_SET(?, t_category_hospital.cancer_id)', [$cancer]);
                    }
                });
            });
        }

        if (!$sort || $sort == 'dpcSort') {
            $query->orderByDesc(function ($query) use ($cancers) {
                $query->selectRaw('AVG(n_dpc) as average_n_dpc')
                    ->from('t_dpc')
                    ->whereColumn('t_dpc.hospital_id', 't_hospital.id')
                    ->whereIn('t_dpc.cancer_id', $cancers)
                    ->orderByDesc('year')
                    ->limit(3);

            });
        } else if ($sort == 'newNumSort') {
            $query->orderByDesc(function ($query) use ($cancers) {
                $query->selectRaw('AVG(total_num_new) as average_num_new')
                    ->from('t_stage')
                    ->whereColumn('t_stage.hospital_id', 't_hospital.id')
                    ->whereIn('t_stage.cancer_id', $cancers)
                    ->orderByDesc('year')
                    ->limit(3);
            });
        } else if ($sort == 'survRateSort') {
            $query->orderByDesc(function ($query) use ($cancers) {
                $query->selectRaw('AVG(survival_rate) as average_survival_rate')
                    ->from('t_surv_hospital')
                    ->whereColumn('t_surv_hospital.hospital_id', 't_hospital.id')
                    ->whereIn('t_surv_hospital.cancer_id', $cancers)
                    ->orderByDesc('year')
                    ->limit(3);
            });
        } else if ($sort == 'stageSort') {
            $listColumn = Stage::getListColumnStage();
            $columnSort = $listColumn[$stages[0] ?? 0] ?? null;

            if ($columnSort) {
                $query->orderByDesc(function ($query) use ($cancers, $columnSort) {
                    $query->selectRaw('AVG('.$columnSort.') as average_stage')
                        ->from('t_stage')
                        ->whereColumn('t_stage.hospital_id', 't_hospital.id')
                        ->whereIn('t_stage.cancer_id', $cancers)
                        ->orderByDesc('year')
                        ->limit(3);
                });
            }
        } else if ($sort == 'stageSurvSort') {
            $listColumn = SurvHospital::getListColumnStage();
            $columnSort = $listColumn[$stages[0] ?? 0] ?? null;

            if ($columnSort) {
                $query->orderByDesc(function ($query) use ($cancers, $columnSort) {
                    $query->selectRaw('AVG('.$columnSort.') as average_stage')
                        ->from('t_surv_hospital')
                        ->whereColumn('t_surv_hospital.hospital_id', 't_hospital.id')
                        ->whereIn('t_surv_hospital.cancer_id', $cancers)
                        ->orderByDesc('year')
                        ->limit(3);
                });
            }
        }

        return [
           'total' => $query->count(),
           'hospitals' => $query->paginate($limit, ['*'], 'page', $page)
        ];
    }
}