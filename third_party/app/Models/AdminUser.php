<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdminUser extends BaseModel
{
    protected $table = 't_admin_user';
    protected $fillable = [
        'member_id',
        'login_id',
        'name',
        'mail',
        'pass',
        'authority',
        'ses_id',
        'del_flg',
    ];

    protected $hidden = [
        'pass'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('deleted', fn(Builder $builder) => $builder->where('t_user.del_flg', '!=' , BaseModel::DELETED));
    }
}
