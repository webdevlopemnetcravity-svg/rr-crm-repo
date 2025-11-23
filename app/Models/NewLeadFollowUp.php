<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewLeadFollowUp extends BaseModel
{
    protected $table = 'new_lead_follow_up';

    protected $fillable = [
        'new_lead_id',
        'follow_up_type',
        'subject',
        'outcome',
        'notes',
        'next_follow_up_date',
        'send_reminder',
        'remind_time',
        'follow_up_subject_line',
        'status',
        'added_by',
        'last_updated_by',
    ];

    protected $casts = [
        'next_follow_up_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the new lead that owns this follow-up.
     */
    public function newLead(): BelongsTo
    {
        return $this->belongsTo(NewLead::class, 'new_lead_id');
    }

    /**
     * Get the user who added this follow-up.
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Get the user who last updated this follow-up.
     */
    public function lastUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }
}

