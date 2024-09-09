<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hospital extends BaseModel
{
    protected $table = 't_hospital';
    protected $primaryKey = 'id';
    protected $fillable = [
        'area_id',
        'hospital_code',
        'hospital_name',
        'zip',
        'city',
        'addr',
        'tel',
        'hp_url',
        'social_info',
        'support_url',
        'introduction_url',
        'remarks',
        'etc1',
        'etc2',
        'etc3',
        'etc4',
        'etc5',
        'del_flg',
        'public_flg'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('deleted', fn(Builder $builder) => $builder->where('t_hospital.del_flg', '!=' , BaseModel::DELETED));
        static::addGlobalScope('unpublish', fn(Builder $builder) => $builder->where('t_hospital.public_flg', '!=' , BaseModel::UNPUBLISHED));
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 't_category_hospital')
            ->withPivot('cancer_id', 'content1', 'content2')
            ->withTimestamps();
    }

    public function cancers(): BelongsToMany
    {
        return $this->belongsToMany(Cancer::class, 't_hospital_cancer')
            ->withPivot('del_flg', 'public_flg', 'social_info', 'base_hospital', 'sp_treatment')
            ->withTimestamps()
            ->wherePivot('del_flg', BaseModel::NOT_DELETED)
            ->wherePivot('public_flg', BaseModel::PUBLISHED);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function dpcs(): HasMany
    {
        return $this->hasMany(DPC::class, 'hospital_id');
    }

    public function avgDpcs(): HasMany
    {
        return $this->hasMany(DPCAvg::class, 'hospital_id');
    }

    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class, 'hospital_id');
    }

    public function avgStages(): HasMany
    {
        return $this->hasMany(StageAvg::class, 'hospital_id');
    }

    public function survivals(): HasMany
    {
        return $this->hasMany(SurvHospital::class, 'hospital_id');
    }

    public function avgSurvivals(): HasMany
    {
        return $this->hasMany(SurvHospitalAvg::class, 'hospital_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 't_hospital_user')
            ->withPivot('id', 'remarks', 'approved_time', 'updated_at')
            ->wherePivot('del_flg', BaseModel::NOT_DELETED);
    }

    public function calculateAvgCommonData($cancerId): array
    {
        $dpcs = $this->dpcs()
            ->select(['n_dpc'])
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->get();

        $avgDpcRank = $this->avgDpcs()
            ->select(['avg_rank_nation_dpc', 'avg_rank_area_dpc', 'avg_rank_pref_dpc'])
            ->where('cancer_id', $cancerId)
            ->orderBy('lasted_year', 'desc')
            ->first();


        $avgDpc = $dpcs->avg('n_dpc');
        $avgGlobalDpcRank = $avgDpcRank->avg_rank_nation_dpc;
        $avgAreaDpcRank = $avgDpcRank->avg_rank_area_dpc;
        $avgPrefDpcRank = $avgDpcRank->avg_rank_pref_dpc;

        $stages = $this->stages()
            ->select(['total_num_new'])
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->get();

        $avgStageRank = $this->avgStages()
            ->select(['avg_total_num_rank', 'avg_local_num_rank', 'avg_pref_num_rank',
                'avg_total_num_rank_stage1', 'avg_local_num_rank_stage1', 'avg_pref_num_rank_stage1',
                'avg_total_num_rank_stage2', 'avg_local_num_rank_stage2', 'avg_pref_num_rank_stage2',
                'avg_total_num_rank_stage3', 'avg_local_num_rank_stage3', 'avg_pref_num_rank_stage3',
                'avg_total_num_rank_stage4', 'avg_local_num_rank_stage4', 'avg_pref_num_rank_stage4',
            ])
            ->where('cancer_id', $cancerId)
            ->orderBy('lasted_year', 'desc')
            ->first();

        $avgNewNum = $stages->avg('total_num_new');
        $avgGlobalNewNumRank = $avgStageRank->avg_total_num_rank;
        $avgLocalNewNumRank = $avgStageRank->avg_local_num_rank;
        $avgPrefNewNumRank = $avgStageRank->avg_pref_num_rank;
        $avgGlobalStage1 = $avgStageRank->avg_total_num_rank_stage1;
        $avgGlobalStage2 = $avgStageRank->avg_total_num_rank_stage2;
        $avgGlobalStage3 = $avgStageRank->avg_total_num_rank_stage3;
        $avgGlobalStage4 = $avgStageRank->avg_total_num_rank_stage4;
        $avgLocalStage1 = $avgStageRank->avg_local_num_rank_stage1;
        $avgLocalStage2 = $avgStageRank->avg_local_num_rank_stage2;
        $avgLocalStage3 = $avgStageRank->avg_local_num_rank_stage3;
        $avgLocalStage4 = $avgStageRank->avg_local_num_rank_stage4;
        $avgPrefStage1 = $avgStageRank->avg_pref_num_rank_stage1;
        $avgPrefStage2 = $avgStageRank->avg_pref_num_rank_stage2;
        $avgPrefStage3 = $avgStageRank->avg_pref_num_rank_stage3;
        $avgPrefStage4 = $avgStageRank->avg_pref_num_rank_stage4;

        $survivals = $this->survivals()
            ->select(['survival_rate', 'total_num'])
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->get();

        $avgSurvRank = $this->avgSurvivals()
            ->select(['avg_total_survival_rate', 'avg_local_survival_rate', 'avg_pref_survival_rate',
                'avg_total_survival_rate1', 'avg_local_survival_rate1', 'avg_pref_survival_rate1',
                'avg_total_survival_rate2', 'avg_local_survival_rate2', 'avg_pref_survival_rate2',
                'avg_total_survival_rate3', 'avg_local_survival_rate3', 'avg_pref_survival_rate3',
                'avg_total_survival_rate4', 'avg_local_survival_rate4', 'avg_pref_survival_rate4',
                'avg_total_stage_total_taget', 'avg_local_stage_total_taget', 'avg_pref_stage_total_taget',
                'avg_total_stage_taget1', 'avg_local_stage_taget1', 'avg_pref_stage_taget1',
                'avg_total_stage_taget2', 'avg_local_stage_taget2', 'avg_pref_stage_taget2',
                'avg_total_stage_taget3', 'avg_local_stage_taget3', 'avg_pref_stage_taget3',
                'avg_total_stage_taget4', 'avg_local_stage_taget4', 'avg_pref_stage_taget4'
            ])
            ->where('cancer_id', $cancerId)
            ->orderBy('lasted_year', 'desc')
            ->first();

        $avgSurvivalRate = $survivals->avg('survival_rate');
        $avgGlobalRate = $avgSurvRank->avg_total_survival_rate;
        $avgLocalRate = $avgSurvRank->avg_local_survival_rate;
        $avgPrefRate = $avgSurvRank->avg_pref_survival_rate;
        $avgGlobalRate1 = $avgSurvRank->avg_total_survival_rate1;
        $avgLocalRate1 = $avgSurvRank->avg_local_survival_rate1;
        $avgPrefRate1 = $avgSurvRank->avg_pref_survival_rate1;
        $avgGlobalRate2 = $avgSurvRank->avg_total_survival_rate2;
        $avgLocalRate2 = $avgSurvRank->avg_local_survival_rate2;
        $avgPrefRate2 = $avgSurvRank->avg_pref_survival_rate2;
        $avgGlobalRate3 = $avgSurvRank->avg_total_survival_rate3;
        $avgLocalRate3 = $avgSurvRank->avg_local_survival_rate3;
        $avgPrefRate3 = $avgSurvRank->avg_pref_survival_rate3;
        $avgGlobalRate4 = $avgSurvRank->avg_total_survival_rate4;
        $avgLocalRate4 = $avgSurvRank->avg_local_survival_rate4;
        $avgPrefRate4 = $avgSurvRank->avg_pref_survival_rate4;
        $avgNum = $survivals->avg('total_num');
        $avgGlobalNum = $avgSurvRank->avg_total_stage_total_taget;
        $avgLocalNum = $avgSurvRank->avg_local_stage_total_taget;
        $avgPrefNum = $avgSurvRank->avg_pref_stage_total_taget;
        $avgGlobalNum1 = $avgSurvRank->avg_total_stage_taget1;
        $avgLocalNum1 = $avgSurvRank->avg_local_stage_taget1;
        $avgPrefNum1 = $avgSurvRank->avg_pref_stage_taget1;
        $avgGlobalNum2 = $avgSurvRank->avg_total_stage_taget2;
        $avgLocalNum2 = $avgSurvRank->avg_local_stage_taget2;
        $avgPrefNum2 = $avgSurvRank->avg_pref_stage_taget2;
        $avgGlobalNum3 = $avgSurvRank->avg_total_stage_taget3;
        $avgLocalNum3 = $avgSurvRank->avg_local_stage_taget3;
        $avgPrefNum3 = $avgSurvRank->avg_pref_stage_taget3;
        $avgGlobalNum4 = $avgSurvRank->avg_total_stage_taget4;
        $avgLocalNum4 = $avgSurvRank->avg_local_stage_taget4;
        $avgPrefNum4 = $avgSurvRank->avg_pref_stage_taget4;

        return [
            'avgDpc' => is_numeric($avgDpc) ? round($avgDpc, 1) : null,
            'avgGlobalDpcRank' => $avgGlobalDpcRank,
            'avgAreaDpcRank' => $avgAreaDpcRank,
            'avgPrefDpcRank' => $avgPrefDpcRank,
            'avgSurvivalRate' => is_numeric($avgSurvivalRate) ? round($avgSurvivalRate, 2) : null,
            'avgGlobalRate' => $avgGlobalRate,
            'avgLocalRate' => $avgLocalRate,
            'avgPrefRate' => $avgPrefRate,
            'avgGlobalRate1' => $avgGlobalRate1,
            'avgLocalRate1' => $avgLocalRate1,
            'avgPrefRate1' => $avgPrefRate1,
            'avgGlobalRate2' => $avgGlobalRate2,
            'avgLocalRate2' => $avgLocalRate2,
            'avgPrefRate2' => $avgPrefRate2,
            'avgGlobalRate3' => $avgGlobalRate3,
            'avgLocalRate3' => $avgLocalRate3,
            'avgPrefRate3' => $avgPrefRate3,
            'avgGlobalRate4' => $avgGlobalRate4,
            'avgLocalRate4' => $avgLocalRate4,
            'avgPrefRate4' => $avgPrefRate4,
            'avgNum' => is_numeric($avgNum) ? round($avgNum, 1) : null,
            'avgGlobalNum' => $avgGlobalNum,
            'avgLocalNum' => $avgLocalNum,
            'avgPrefNum' => $avgPrefNum,
            'avgGlobalNum1' => $avgGlobalNum1,
            'avgLocalNum1' => $avgLocalNum1,
            'avgPrefNum1' => $avgPrefNum1,
            'avgGlobalNum2' => $avgGlobalNum2,
            'avgLocalNum2' => $avgLocalNum2,
            'avgPrefNum2' => $avgPrefNum2,
            'avgGlobalNum3' => $avgGlobalNum3,
            'avgLocalNum3' => $avgLocalNum3,
            'avgPrefNum3' => $avgPrefNum3,
            'avgGlobalNum4' => $avgGlobalNum4,
            'avgLocalNum4' => $avgLocalNum4,
            'avgPrefNum4' => $avgPrefNum4,
            'avgNewNum' => is_numeric($avgNewNum) ? round($avgNewNum, 1) : null,
            'avgGlobalNewNumRank' => $avgGlobalNewNumRank,
            'avgLocalNewNumRank' => $avgLocalNewNumRank,
            'avgPrefNewNumRank' => $avgPrefNewNumRank,
            'avgGlobalStage1' => $avgGlobalStage1,
            'avgGlobalStage2' => $avgGlobalStage2,
            'avgGlobalStage3' => $avgGlobalStage3,
            'avgGlobalStage4' => $avgGlobalStage4,
            'avgLocalStage1' => $avgLocalStage1,
            'avgLocalStage2' => $avgLocalStage2,
            'avgLocalStage3' => $avgLocalStage3,
            'avgLocalStage4' => $avgLocalStage4,
            'avgPrefStage1' => $avgPrefStage1,
            'avgPrefStage2' => $avgPrefStage2,
            'avgPrefStage3' => $avgPrefStage3,
            'avgPrefStage4' => $avgPrefStage4,
        ];
    }
}
