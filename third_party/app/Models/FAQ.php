<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class FAQ extends BaseModel
{
    protected $table = 't_faq';
    protected $primaryKey = 'id';
    protected $fillable = [
        'question',
        'answer',
        'group_answer',
        'del_flg',
        'public_flg',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('deleted', fn(Builder $builder) => $builder->where('t_faq.del_flg', '!=' , BaseModel::DELETED));
        static::addGlobalScope('unpublish', fn(Builder $builder) => $builder->where('t_faq.public_flg', '!=' , BaseModel::UNPUBLISHED));
    }

}
