<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('deleted', fn(Builder $builder) => $builder->where('t_dpc.del_flg', '!=' , BaseModel::DELETED));
        static::addGlobalScope('unpublish', fn(Builder $builder) => $builder->where('t_dpc.public_flg', '!=' , BaseModel::UNPUBLISHED));
    }

    public function cancer(): BelongsTo
    {
        return $this->belongsTo(Cancer::class, 'cancer_id');
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }
}
