<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurvHospitalAvg extends BaseModel
{
    protected $table = 't_surv_hospital_avg';
    protected $primaryKey = 'id';
    protected $fillable = [
        'hospital_id',
        'cancer_id',
        'area_id',
        'lasted_year',
        'avg_total_num',
        'avg_stage_target1',
        'avg_stage_target2',
        'avg_stage_target3',
        'avg_stage_target4',
        'avg_survival_rate',
        'avg_stage_survival_rate1',
        'avg_stage_survival_rate2',
        'avg_stage_survival_rate3',
        'avg_stage_survival_rate4',
        'avg_total_stage_total_taget',
        'avg_local_stage_total_taget',
        'avg_pref_stage_total_taget',
        'avg_total_stage_taget1',
        'avg_local_stage_taget1',
        'avg_pref_stage_taget1',
        'avg_total_stage_taget2',
        'avg_local_stage_taget2',
        'avg_pref_stage_taget2',
        'avg_total_stage_taget3',
        'avg_local_stage_taget3',
        'avg_pref_stage_taget3',
        'avg_total_stage_taget4',
        'avg_local_stage_taget4',
        'avg_pref_stage_taget4',
        'avg_total_survival_rate',
        'avg_local_survival_rate',
        'avg_pref_survival_rate',
        'avg_total_survival_rate1',
        'avg_local_survival_rate1',
        'avg_pref_survival_rate1',
        'avg_total_survival_rate2',
        'avg_local_survival_rate2',
        'avg_pref_survival_rate2',
        'avg_total_survival_rate3',
        'avg_local_survival_rate3',
        'avg_pref_survival_rate3',
        'avg_total_survival_rate4',
        'avg_local_survival_rate4',
        'avg_pref_survival_rate4',
    ];

    public $timestamps = false;

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
