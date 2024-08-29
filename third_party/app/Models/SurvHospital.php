<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurvHospital extends BaseModel
{
    const STAGE_1 = 1;
    const STAGE_2 = 2;
    const STAGE_3 = 3;
    const STAGE_4 = 4;

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

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('deleted', fn(Builder $builder) => $builder->where('t_surv_hospital.del_flg', '!=' , BaseModel::DELETED));
        static::addGlobalScope('unpublish', fn(Builder $builder) => $builder->where('t_surv_hospital.public_flg', '!=' , BaseModel::UNPUBLISHED));
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function cancer(): BelongsTo
    {
        return $this->belongsTo(Cancer::class, 'cancer_id');
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }

    public static function getListColumnStage(): array
    {
        return [
            self::STAGE_1 => 'stage_target1',
            self::STAGE_2 => 'stage_target2',
            self::STAGE_3 => 'stage_target3',
            self::STAGE_4 => 'stage_target4',
        ];
    }
}
