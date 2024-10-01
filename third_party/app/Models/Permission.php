<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permission extends BaseModel
{
    const GROUP_USER = 1;
    const GROUP_ADMIN = 2;

    protected $table = 't_permission';
    protected $fillable = [
        'permission_name',
        'parent_id',
        'description',
        'group_permission',
        'parse',
        'del_flg'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('deleted', fn(Builder $builder) => $builder->where('t_permission.del_flg', '!=' , BaseModel::DELETED));
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 't_role_permission');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'parent_id');
    }

    public function children() : HasMany
    {
        return $this->hasMany(Permission::class, 'parent_id');
    }
}
