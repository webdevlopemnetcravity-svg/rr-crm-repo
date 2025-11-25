<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewLeadProcess extends BaseModel
{
    protected $table = 'new_lead_process';

    protected $fillable = [
        'new_lead_id',
        'applicant_name',
        'visa_category',
        'subclass',
        'passport_name',
        'passport_number',
        'agent_name',
        'advance_fees',
        'advance_fees_due_date',
        'remaining_fees',
        'remaining_fees_due_date',
        'agent_fees',
        'submission_fees',
        'status',
        'processing_time',
        'bank_cheque_handover_date',
        'passport_handover_date',
        'process_note',
        'contract_letter',
        'grant_letter',
        'offer_letter',
        'medical_letter',
        'air_ticket',
        'accommodation_letter',
        'added_by',
        'last_updated_by',
    ];

    protected $casts = [
        'advance_fees_due_date' => 'date',
        'remaining_fees_due_date' => 'date',
        'bank_cheque_handover_date' => 'date',
        'passport_handover_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the new lead that owns this process.
     */
    public function newLead(): BelongsTo
    {
        return $this->belongsTo(NewLead::class, 'new_lead_id');
    }

    /**
     * Get the user who added this process.
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Get the user who last updated this process.
     */
    public function lastUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }
}

