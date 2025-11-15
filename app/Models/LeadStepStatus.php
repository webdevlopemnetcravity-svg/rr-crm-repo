<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadStepStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'step_1_completed',
        'step_2_completed',
        'step_3_completed',
        'step_4_completed',
        'step_5_completed',
        'step_6_completed',
        'step_7_completed',
        'step_8_completed',
        'step_9_completed',
        'final_status',
    ];

    protected $casts = [
        'step_1_completed' => 'boolean',
        'step_2_completed' => 'boolean',
        'step_3_completed' => 'boolean',
        'step_4_completed' => 'boolean',
        'step_5_completed' => 'boolean',
        'step_6_completed' => 'boolean',
        'step_7_completed' => 'boolean',
        'step_8_completed' => 'boolean',
        'step_9_completed' => 'boolean',
    ];

    /**
     * Get the lead that owns this step status.
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(NewLead::class, 'lead_id');
    }

    /**
     * Get the step logs for this lead.
     */
    public function stepLogs(): HasMany
    {
        return $this->hasMany(LeadStepLog::class, 'lead_id', 'lead_id');
    }

    /**
     * Check if all steps are completed.
     */
    public function areAllStepsCompleted(): bool
    {
        return $this->step_1_completed &&
               $this->step_2_completed &&
               $this->step_3_completed &&
               $this->step_4_completed &&
               $this->step_5_completed &&
               $this->step_6_completed &&
               $this->step_7_completed &&
               $this->step_8_completed &&
               $this->step_9_completed;
    }

    /**
     * Update final status based on step completion.
     */
    public function updateFinalStatus(): void
    {
        if ($this->areAllStepsCompleted()) {
            $this->final_status = 'complete';
        } else {
            $this->final_status = 'draft';
        }
        $this->save();
    }

    /**
     * Get or create step status for a lead.
     */
    public static function getOrCreateForLead($leadId): self
    {
        return static::firstOrCreate(
            ['lead_id' => $leadId],
            [
                'step_1_completed' => false,
                'step_2_completed' => false,
                'step_3_completed' => false,
                'step_4_completed' => false,
                'step_5_completed' => false,
                'step_6_completed' => false,
                'step_7_completed' => false,
                'step_8_completed' => false,
                'step_9_completed' => false,
                'final_status' => 'draft',
            ]
        );
    }
}
