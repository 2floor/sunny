<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cancer extends BaseModel
{
    protected $table = 'm_cancer';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cancer_type',
        'cancer_type_dpc',
        'cancer_type_stage',
        'cancer_type_surv',
        'order_num',
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

        static::addGlobalScope('deleted', fn(Builder $builder) => $builder->where('m_cancer.del_flg', '!=' , BaseModel::DELETED));
        static::addGlobalScope('unpublish', fn(Builder $builder) => $builder->where('m_cancer.public_flg', '!=' , BaseModel::UNPUBLISHED));
    }

    public function hospitals(): BelongsToMany
    {
        return $this->belongsToMany(Hospital::class, 't_hospital_cancer')
            ->withPivot('del_flg', 'public_flg', 'social_info', 'base_hospital', 'sp_treatment')
            ->withTimestamps()
            ->wherePivot('del_flg', BaseModel::NOT_DELETED)
            ->wherePivot('public_flg', BaseModel::PUBLISHED);
    }
}
