<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DPCAvg extends BaseModel
{
    protected $table = 't_dpc_avg';
    protected $primaryKey = 'id';
    protected $fillable = [
        'hospital_id',
        'cancer_id',
        'area_id',
        'lasted_year',
        'avg_n_dpc',
        'avg_rank_nation_dpc',
        'avg_rank_area_dpc',
        'avg_rank_pref_dpc',
    ];

    public $timestamps = false;

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
