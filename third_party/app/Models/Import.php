<?php

namespace App\Models;

class Import extends BaseModel
{
    const DATA_TYPE_HOSPITAL = 1;
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
        'error_file',
        'completed_time',
        'status'
    ];
}
