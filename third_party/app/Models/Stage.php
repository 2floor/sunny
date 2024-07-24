<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stage extends BaseModel
{
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

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
