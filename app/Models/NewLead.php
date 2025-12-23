<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewLead extends BaseModel
{
    use HasFactory;

    protected $table = 'new_leads';

    protected $fillable = [
        'company_id',
        'client_name',
        'profile_image',
        'client_email',
        'mobile',
        'lead_source',
        'priority',
        'lead_status',
        'lead_quality',
        'lead_owner',
        'added_by',
        'last_updated_by',
        'step_1_data',
        'step_2_data',
        'step_3_data',
        'step_4_data',
        'step_5_data',
        'step_6_data',
        'step_7_data',
        'step_8_data',
        'step_9_data',
        'note',
        'hash',
    ];

    protected $casts = [
        'step_1_data' => 'array',
        'step_2_data' => 'array',
        'step_3_data' => 'array',
        'step_4_data' => 'array',
        'step_5_data' => 'array',
        'step_6_data' => 'array',
        'step_7_data' => 'array',
        'step_8_data' => 'array',
        'step_9_data' => 'array',
    ];

    /**
     * Get the company that owns this lead.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user who added this lead.
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Get the user who owns this lead.
     */
    public function leadOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lead_owner');
    }

    /**
     * Get the step status for this lead.
     */
    public function stepStatus(): HasOne
    {
        return $this->hasOne(LeadStepStatus::class, 'lead_id');
    }

    /**
     * Get the step logs for this lead.
     */
    public function stepLogs(): HasMany
    {
        return $this->hasMany(LeadStepLog::class, 'lead_id');
    }

    /**
     * Get the follow-ups for this lead.
     */
    public function followUps(): HasMany
    {
        return $this->hasMany(NewLeadFollowUp::class, 'new_lead_id');
    }

    /**
     * Get the file notes for this lead.
     */
    public function fileNotes(): HasMany
    {
        return $this->hasMany(NewLeadFileNote::class, 'new_lead_id');
    }

    /**
     * Get the process for this lead.
     */
    public function process(): HasOne
    {
        return $this->hasOne(NewLeadProcess::class, 'new_lead_id');
    }

    /**
     * Get the accounts for this lead.
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(NewLeadAccount::class, 'new_lead_id');
    }

    /**
     * Get the travel details for this lead.
     */
    public function travelDetails(): HasOne
    {
        return $this->hasOne(NewLeadTravelDetail::class, 'new_lead_id');
    }

    /**
     * Get the documents for this lead.
     */
    public function documents(): HasOne
    {
        return $this->hasOne(NewLeadDocument::class, 'lead_id');
    }
}
