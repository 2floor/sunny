<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends BaseModel
{
    protected $table = 't_user';
    protected $fillable = [
        'type',
        'role_id',
        'sno',
        'job',
        'name',
        'name_kana',
        'h_name',
        'tel',
        'zip',
        'pref',
        'addr',
        'email',
        'username',
        'password',
        'etc1',
        'etc2',
        'etc3',
        'etc4',
        'etc5',
        'del_flg',
        'public_flg'
    ];

    protected $hidden = [
        'password'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('deleted', fn(Builder $builder) => $builder->where('t_user.del_flg', '!=' , BaseModel::DELETED));
        static::addGlobalScope('unpublish', fn(Builder $builder) => $builder->where('t_user.public_flg', '!=' , BaseModel::UNPUBLISHED));
    }

    public function hospitals(): BelongsToMany
    {
        return $this->belongsToMany(Hospital::class, 't_hospital_user')
            ->withPivot('id', 'remarks', 'approved_time', 'updated_at')
            ->wherePivot('del_flg', BaseModel::NOT_DELETED);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
