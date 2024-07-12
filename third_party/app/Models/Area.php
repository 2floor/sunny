<?php

namespace App\Models;

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

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
