<?php

namespace App\Models;

class HospitalCategory extends BaseModel
{
    protected $table = 't_category_hospital';
    protected $primaryKey = 'id';
    protected $fillable = [
        'hospital_id',
        'category_id',
        'cancer_id',
        'content1',
        'content2',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
