<?php

namespace App\Models;

class HospitalUser extends BaseModel
{
    protected $table = 't_hospital_user';
    protected $primaryKey = 'id';
    protected $fillable = [
        'hospital_id',
        'user_id',
        'remarks',
        'approved_time',
        'del_flg',
        'created_at',
        'updated_at'
    ];

    public $timestamps = false;
}
