<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    const DELETED = 1;
    const NOT_DELETED = 0;
    const PUBLISHED = 0;
    const UNPUBLISHED = 1;
}
