<?php

use App\Models\Hospital;
use App\Models\HospitalCategory;

class f_hospital_logic
{
    public function getHospitalsFromFilter($keyword, $cancers, $areas, $categories, $sort, $page, $limit)
    {
        $query = Hospital::query();

        if ($keyword != '') {
            $query->where('hospital_name', 'like', "%$keyword%");
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
                    $query2->orWhereIn('t_category_hospital.cancer_id', $cancers);
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
        }

        return [
           'total' => $query->count(),
           'hospitals' => $query->paginate($limit, ['*'], 'page', $page)
        ];
    }
}