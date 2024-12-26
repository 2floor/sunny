<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StageAvg extends BaseModel
{
    protected $table = 't_stage_avg';
    protected $primaryKey = 'id';
    protected $fillable = [
        'area_id',
        'hospital_id',
        'cancer_id',
        'lasted_year',
        'avg_total_num_new',
        'avg_stage_new1',
        'avg_stage_new2',
        'avg_stage_new3',
        'avg_stage_new4',
        'avg_total_num_rank',
        'avg_local_num_rank',
        'avg_pref_num_rank',
        'avg_total_num_rank_stage1',
        'avg_local_num_rank_stage1',
        'avg_pref_num_rank_stage1',
        'avg_total_num_rank_stage2',
        'avg_local_num_rank_stage2',
        'avg_pref_num_rank_stage2',
        'avg_total_num_rank_stage3',
        'avg_local_num_rank_stage3',
        'avg_pref_num_rank_stage3',
        'avg_total_num_rank_stage4',
        'avg_local_num_rank_stage4',
        'avg_pref_num_rank_stage4',
    ];

    public $timestamps = false;

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
