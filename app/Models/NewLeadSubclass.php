<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewLeadSubclass extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_lead_subclass';

    protected $fillable = [
        'company_id',
        'visa_type_id',
        'name'
    ];

    public function visaType(): BelongsTo
    {
        return $this->belongsTo(NewLeadVisaType::class, 'visa_type_id');
    }
}

