<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hospital extends BaseModel
{
    protected $table = 't_hospital';
    protected $primaryKey = 'id';
    protected $fillable = [
        'area_id',
        'hospital_code',
        'hospital_name',
        'zip',
        'city',
        'addr',
        'tel',
        'hp_url',
        'social_info',
        'support_url',
        'introduction_url',
        'remarks',
        'etc1',
        'etc2',
        'etc3',
        'etc4',
        'etc5',
        'del_flg',
        'public_flg'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 't_category_hospital');
    }

    public function cancers(): BelongsToMany
    {
        return $this->belongsToMany(Cancer::class, 't_hospital_cancer');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function dpcs(): HasMany
    {
        return $this->hasMany(DPC::class, 'hospital_id');
    }

    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class, 'hospital_id');
    }

    public function survivals(): HasMany
    {
        return $this->hasMany(SurvHospital::class, 'hospital_id');
    }
}
