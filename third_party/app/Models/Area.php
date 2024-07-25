<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Area extends BaseModel
{
    protected $table = 'm_area';
    protected $primaryKey = 'id';
    protected $fillable = [
        'area_name',
        'prec_cd',
        'pref_name',
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

        static::addGlobalScope('deleted', fn(Builder $builder) => $builder->where('m_area.del_flg', '!=' , BaseModel::DELETED));
        static::addGlobalScope('unpublish', fn(Builder $builder) => $builder->where('m_area.public_flg', '!=' , BaseModel::UNPUBLISHED));
    }
}
