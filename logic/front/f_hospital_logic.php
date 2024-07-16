<?php

use App\Models\Hospital;
use App\Models\HospitalCategory;

class f_hospital_logic
{
    public function getHospitalsFromFilter($keyword, $cancers, $areas, $categories, $page, $limit)
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
            $query->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('t_category.id', $categories);
            });
        }

        $query->orderByDesc(function ($query) use ($cancers) {
            $query->selectRaw('AVG(n_dpc) as average_n_dpc')
                ->from('t_dpc')
                ->whereColumn('t_dpc.hospital_id', 't_hospital.id')
                ->whereIn('t_dpc.cancer_id', $cancers)
                ->orderByDesc('year')
                ->limit(3);

        });

        return [
           'total' => $query->count(),
           'hospitals' => $query->paginate($limit, ['*'], 'page', $page)
        ];
    }
}