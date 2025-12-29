<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadStatusChangeLog extends Model
{
    use HasFactory;

    protected $table = 'new_lead_status_change_logs';

    protected $fillable = [
        'lead_id',
        'change_type',
        'old_value',
        'new_value',
        'remark',
        'changed_by',
    ];

    /**
     * Get the lead that owns this status change log.
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(NewLead::class, 'lead_id');
    }

    /**
     * Get the user who made this change.
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}

