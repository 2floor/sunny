<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutoRank extends BaseModel
{
    const DATA_TYPE_DPC = 1;
    const DATA_TYPE_STAGE = 2;
    const DATA_TYPE_SURVIVAL = 3;
    const AUTO_TYPE_RANK = 1;
    const AUTO_TYPE_AVG = 2;
    const STATUS_PENDING = 0;
    const STATUS_IN_PROCESSING = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_TIMEOUT = 3;


    protected $table = 't_auto_rank';
    protected $fillable = [
        'data_type',
        'auto_type',
        'cancer_id',
        'year',
        'total_affect',
        'status',
        'message',
        'completed_time',
        'created_at',
        'updated_at'
    ];

    public $timestamps = false;

    public function cancer(): BelongsTo
    {
        return $this->belongsTo(Cancer::class);
    }
}
