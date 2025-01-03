<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Import extends BaseModel
{
    const DATA_TYPE_HOSPITAL = 1;
    const DATA_TYPE_HOSPITAL_CANCER = 2;
    const DATA_TYPE_DPC = 3;
    const DATA_TYPE_STAGE = 4;
    const DATA_TYPE_SURVIVAL = 5;
    const DATA_TYPE_CANCER = 6;
    const IMPORT_TYPE_MAIN = 1;
    const IMPORT_TYPE_REIMPORT = 2;
    const STATUS_PENDING = 0;
    const STATUS_IN_PROCESSING = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_ERROR_PROCESSING = 3;
    const STATUS_TIMEOUT = 4;
    const STATUS_REIMPORT = 5;


    protected $table = 't_import';
    protected $fillable = [
        'data_type',
        'import_type',
        'parent_id',
        'file_name',
        'success',
        'error',
        'error_message',
        'error_file',
        'completed_time',
        'status',
        'created_at'
    ];

    public function children() : HasMany
    {
        return $this->hasMany(Import::class, 'parent_id');
    }

    public $timestamps = false;
}
