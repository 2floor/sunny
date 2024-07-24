<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurvHospital extends BaseModel
{
    protected $table = 't_surv_hospital';
    protected $primaryKey = 'id';
    protected $fillable = [
        'hospital_id',
        'cancer_id',
        'area_id',
        'year',
        'total_num',
        'stage_target1',
        'stage_target2',
        'stage_target3',
        'stage_target4',
        'stage_survival_rate1',
        'stage_survival_rate2',
        'stage_survival_rate3',
        'stage_survival_rate4',
        'survival_rate',
        'adjustment_survival_rate',
        'total_stage_total_taget',
        'local_stage_total_taget',
        'pref_stage_total_taget',
        'total_stage_taget1',
        'local_stage_taget1',
        'pref_stage_taget1',
        'total_stage_taget2',
        'local_stage_taget2',
        'pref_stage_taget2',
        'total_stage_taget3',
        'local_stage_taget3',
        'pref_stage_taget3',
        'total_stage_taget4',
        'local_stage_taget4',
        'pref_stage_taget4',
        'total_survival_rate',
        'local_survival_rate',
        'pref_survival_rate',
        'total_survival_rate1',
        'local_survival_rate1',
        'pref_survival_rate1',
        'total_survival_rate2',
        'local_survival_rate2',
        'pref_survival_rate2',
        'total_survival_rate3',
        'local_survival_rate3',
        'pref_survival_rate3',
        'total_survival_rate4',
        'local_survival_rate4',
        'pref_survival_rate4',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
