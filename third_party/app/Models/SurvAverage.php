<?php

namespace App\Models;

class SurvAverage extends BaseModel
{
    protected $table = 't_surv_average';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cancer_id',
        'year',
        'stage_survival1',
        'stage_survival2',
        'stage_survival3',
        'stage_survival4'
    ];
}
