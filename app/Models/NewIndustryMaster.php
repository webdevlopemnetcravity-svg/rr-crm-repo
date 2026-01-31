<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewIndustryMaster extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_industry_master';

    protected $fillable = [
        'company_id',
        'name'
    ];

    public function sectors(): HasMany
    {
        return $this->hasMany(NewSectorMaster::class, 'industry_id');
    }
}
