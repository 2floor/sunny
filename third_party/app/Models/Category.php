<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends BaseModel
{
    const OTHER_TYPE = 0;
    const HOSPITAL_DETAIL_TYPE = 1;
    const HOSPITAL_TREATMENT_TYPE = 2;
    const HOSPITAL_POLICY_TYPE = 3;

    const HOSPITAL_GROUP = 1;
    const DOCTOR_GROUP = 2;

    const FOR_ALL_CANCER = 1;
    const NOT_FOR_ALL_CANCER = 0;

    const LIST_GROUP = [
        self::HOSPITAL_GROUP => '病院区分',
        self::DOCTOR_GROUP => '医師区分',
    ];
    const LIST_CANCER = [
        self::FOR_ALL_CANCER => '医療機関がん種',
        self::NOT_FOR_ALL_CANCER => '医療機関基本',
    ];
    const LIST_TYPE = [
        self::OTHER_TYPE => 'その他',
        self::HOSPITAL_DETAIL_TYPE => '病院詳細',
        self::HOSPITAL_TREATMENT_TYPE => '治療法',
        self::HOSPITAL_POLICY_TYPE => '治療方針',
    ];

    protected $table = 't_category';
    protected $primaryKey = 'id';
    protected $fillable = [
        'level1',
        'level2',
        'level3',
        'data_type',
        'order_num2',
        'order_num3',
        'category_group',
        'is_whole_cancer',
        'etc1',
        'etc2',
        'etc3',
        'etc4',
        'etc5',
        'del_flg',
        'public_flg'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('deleted', fn(Builder $builder) => $builder->where('t_category.del_flg', '!=', BaseModel::DELETED));
        static::addGlobalScope('unpublish', fn(Builder $builder) => $builder->where('t_category.public_flg', '!=', BaseModel::UNPUBLISHED));
    }

    public function hospitals(): BelongsToMany
    {
        return $this->belongsToMany(Hospital::class, 't_category_hospital')
            ->withPivot('cancer_id', 'content1', 'content2')
            ->withTimestamps();
    }
}
