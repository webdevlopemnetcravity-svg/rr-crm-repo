<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewLeadVisaType extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_lead_visa_type';

    protected $fillable = [
        'company_id',
        'name'
    ];

    public function subclasses(): HasMany
    {
        return $this->hasMany(NewLeadSubclass::class, 'visa_type_id');
    }
}

