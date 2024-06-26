<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    const DELETED = 1;
    const NOT_DELETED = 0;
    const PUBLISHED = 0;
    const UNPUBLISHED = 1;

    protected $table = 't_user';
    protected $fillable = [
        'type',
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

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
