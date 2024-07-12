<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends BaseModel
{
    const OTHER_TYPE = 0;
    const HOSPITAL_DETAIL_TYPE = 1;
    const HOSPITAL_TREATMENT_TYPE = 2;
    const HOSPITAL_POLICY_TYPE = 2;

    const HOSPITAL_GROUP = 1;
    const DOCTOR_GROUP = 2;

    const FOR_ALL_CANCER = 1;
    const NOT_FOR_ALL_CANCER = 0;

    protected $table = 't_category';
    protected $primaryKey = 'id';
    protected $fillable = [
        'level1',
        'level2',
        'level3',
        'data_type',
        'order_num',
        'is_whole_cancer',
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

    public function hospitals(): BelongsToMany
    {
        return $this->belongsToMany(Hospital::class, 't_category_hospital');
    }
}
