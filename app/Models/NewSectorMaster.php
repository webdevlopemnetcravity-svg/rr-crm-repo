<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewSectorMaster extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_sector_master';

    protected $fillable = [
        'company_id',
        'industry_id',
        'name'
    ];

    public function industry(): BelongsTo
    {
        return $this->belongsTo(NewIndustryMaster::class, 'industry_id');
    }
}
