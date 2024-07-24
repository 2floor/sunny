<?php

namespace App\Models;

class DPC extends BaseModel
{
    protected $table = 't_dpc';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cancer_id',
        'area_id',
        'cancer_name_dpc',
        'hospital_id',
        'hospital_name',
        'year',
        'n_dpc',
        'rank_nation_dpc',
        'rank_area_dpc',
        'rank_pref_dpc',
    ];
}
