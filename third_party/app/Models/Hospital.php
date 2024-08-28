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

    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class, 'hospital_id');
    }

    public function survivals(): HasMany
    {
        return $this->hasMany(SurvHospital::class, 'hospital_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 't_hospital_user')
            ->withPivot('remarks', 'approved_time');
    }

    public function calculateAvgCommonData($cancerId): array
    {
        $dpcs = $this->dpcs()
            ->select(['n_dpc', 'rank_nation_dpc', 'rank_area_dpc', 'rank_pref_dpc'])
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->get();

        $avgDpc = $dpcs->avg('n_dpc');
        $avgGlobalDpcRank = $dpcs->avg('rank_nation_dpc');
        $avgAreaDpcRank = $dpcs->avg('rank_area_dpc');
        $avgPrefDpcRank = $dpcs->avg('rank_pref_dpc');

        $stages = $this->stages()
            ->select(['total_num_new', 'total_num_rank', 'local_num_rank', 'pref_num_rank'])
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->get();

        $avgNewNum = $stages->avg('total_num_new');
        $avgGlobalNewNumRank = $stages->avg('total_num_rank');
        $avgLocalNewNumRank = $stages->avg('local_num_rank');
        $avgPrefNewNumRank = $stages->avg('pref_num_rank');

        $survivals = $this->survivals()
            ->select([
                'survival_rate',
                'total_survival_rate',
                'local_survival_rate',
                'pref_survival_rate'
            ])
            ->where('cancer_id', $cancerId)
            ->orderBy('year', 'desc')
            ->take(3)
            ->get();

        $avgSurvivalRate = $survivals->avg('survival_rate');
        $avgGlobalRate = $survivals->avg('total_survival_rate');
        $avgLocalRate = $survivals->avg('local_survival_rate');
        $avgPrefRate = $survivals->avg('pref_survival_rate');

        return [
            'avgDpc' => $avgDpc ? round($avgDpc, 1) : null,
            'avgGlobalDpcRank' => $avgGlobalDpcRank ? round($avgGlobalDpcRank) : null,
            'avgAreaDpcRank' => $avgAreaDpcRank ? round($avgAreaDpcRank) : null,
            'avgPrefDpcRank' => $avgPrefDpcRank ? round($avgPrefDpcRank) : null,
            'avgSurvivalRate' => $avgSurvivalRate ? round($avgSurvivalRate, 2) : null,
            'avgGlobalRate' => $avgGlobalRate ? round($avgGlobalRate) : null,
            'avgLocalRate' => $avgLocalRate ? round($avgLocalRate) : null,
            'avgPrefRate' => $avgPrefRate ? round($avgPrefRate) : null,
            'avgNewNum' => $avgNewNum ? round($avgNewNum, 1) : null,
            'avgGlobalNewNumRank' => $avgGlobalNewNumRank ? round($avgGlobalNewNumRank) : null,
            'avgLocalNewNumRank' => $avgLocalNewNumRank ? round($avgLocalNewNumRank) : null,
            'avgPrefNewNumRank' => $avgPrefNewNumRank ? round($avgPrefNewNumRank) : null,
        ];
    }
}
