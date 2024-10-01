<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends BaseModel
{
    const GROUP_USER = 1;
    const GROUP_ADMIN = 2;
    const IS_NOT_SUPER_ADMIN = 0;
    const IS_SUPER_ADMIN = 1;

    protected $table = 't_role';
    protected $fillable = [
        'role_name',
        'description',
        'group_role',
        'is_supper_role',
        'del_flg'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('deleted', fn(Builder $builder) => $builder->where('t_role.del_flg', '!=' , BaseModel::DELETED));
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 't_role_permission');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
