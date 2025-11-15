<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadStepLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'step_number',
        'status',
        'completed_at',
        'completed_by',
        'notes',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * Get the lead that owns this step log.
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(NewLead::class, 'lead_id');
    }

    /**
     * Get the user who completed this step.
     */
    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}
