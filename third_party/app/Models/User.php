<?php

namespace App\Models;

class User extends BaseModel
{
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
}
