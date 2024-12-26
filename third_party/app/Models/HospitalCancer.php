<?php

namespace App\Models;

class HospitalCancer extends BaseModel
{
    protected $table = 't_hospital_cancer';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cancer_id',
        'base_hospital',
        'order_num',
        'cancer_name',
        'hospital_id',
        'hospital_name',
        'social_info',
        'average_rank',
        'rank',
        'stage_1_average_rank',
        'stage_1_rank',
        'stage_2_average_rank',
        'stage_2_rank',
        'stage_3_average_rank',
        'stage_3_rank',
        'stage_4_average_rank',
        'stage_4_rank',
        'review',
        'surgery_url',
        'internal_url',
        'sp_cancer',
        'sp_treatment',
        'treatment_price',
        'remarks',
        'etc1',
        'etc2',
        'etc3',
        'etc4',
        'etc5',
        'public_flg',
        'del_flg',
    ];
}
