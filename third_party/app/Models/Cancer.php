<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cancer extends BaseModel
{
    protected $table = 'm_cancer';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cancer_type',
        'cancer_type_dpc',
        'cancer_type_stage',
        'cancer_type_surv',
        'order_num',
        'etc1',
        'etc2',
        'etc3',
        'etc4',
        'etc5',
        'del_flg',
        'public_flg'
    ];

    public function hospitals(): BelongsToMany
    {
        return $this->belongsToMany(Hospital::class, 't_hospital_cancer')
            ->withPivot('del_flg', 'public_flg')
            ->wherePivot('del_flg', BaseModel::NOT_DELETED)
            ->wherePivot('public_flg', BaseModel::PUBLISHED);
    }
}
