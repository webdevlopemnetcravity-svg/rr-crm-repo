<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewLeadDocument extends BaseModel
{
    use HasFactory;

    protected $table = 'new_lead_documents';

    protected $fillable = [
        'lead_id',
        'main_applicant_documents',
        'father_documents',
        'mother_documents',
        'spouse_documents',
        'children_documents',
    ];

    protected $casts = [
        'main_applicant_documents' => 'array',
        'father_documents' => 'array',
        'mother_documents' => 'array',
        'spouse_documents' => 'array',
        'children_documents' => 'array',
    ];

    /**
     * Get the lead that owns this document record.
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(NewLead::class, 'lead_id');
    }
}
