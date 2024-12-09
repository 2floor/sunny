<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MissMatch extends BaseModel
{
    CONST STATUS_NOT_CONFIRM = 0;
    CONST STATUS_CONFIRMED = 1;
    CONST TYPE_DPC = 1;
    CONST TYPE_STAGE = 2;
    CONST TYPE_SURVIVAL = 3;

    protected $table = 't_miss_match';
    protected $fillable = [
        'hospital_id',
        'hospital_name',
        'type',
        'cancer_id',
        'area_id',
        'year',
        'status',
        'percent_match',
        'import_file',
        'import_value',
        'del_flg'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('deleted', fn(Builder $builder) => $builder->where('t_miss_match.del_flg', '!=' , BaseModel::DELETED));
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class, 'hospital_id', 'id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
}
