<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stage extends BaseModel
{
    const STAGE_1 = 1;
    const STAGE_2 = 2;
    const STAGE_3 = 3;
    const STAGE_4 = 4;

    protected $table = 't_stage';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cancer_id',
        'hospital_id',
        'area_id',
        'hospital_name',
        'cancer_name_stage',
        'year',
        'total_num_new',
        'stage_new1',
        'stage_new2',
        'stage_new3',
        'stage_new4',
        'total_num_rank',
        'local_num_rank',
        'pref_num_rank',
        'total_num_rank_stage1',
        'local_num_rank_stage1',
        'pref_num_rank_stage1',
        'total_num_rank_stage2',
        'local_num_rank_stage2',
        'pref_num_rank_stage2',
        'total_num_rank_stage3',
        'local_num_rank_stage3',
        'pref_num_rank_stage3',
        'total_num_rank_stage4',
        'local_num_rank_stage4',
        'pref_num_rank_stage4',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('deleted', fn(Builder $builder) => $builder->where('t_stage.del_flg', '!=' , BaseModel::DELETED));
        static::addGlobalScope('unpublish', fn(Builder $builder) => $builder->where('t_stage.public_flg', '!=' , BaseModel::UNPUBLISHED));
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function cancer(): BelongsTo
    {
        return $this->belongsTo(Cancer::class, 'cancer_id');
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }

    public static function getListColumnStage(): array
    {
        return [
            self::STAGE_1 => 'stage_new1',
            self::STAGE_2 => 'stage_new2',
            self::STAGE_3 => 'stage_new3',
            self::STAGE_4 => 'stage_new4',
        ];
    }
}
