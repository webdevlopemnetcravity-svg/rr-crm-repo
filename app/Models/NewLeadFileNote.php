<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewLeadFileNote extends BaseModel
{
    protected $table = 'new_lead_file_notes';

    protected $fillable = [
        'new_lead_id',
        'note',
        'added_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the new lead that owns this file note.
     */
    public function newLead(): BelongsTo
    {
        return $this->belongsTo(NewLead::class, 'new_lead_id');
    }

    /**
     * Get the user who added this file note.
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}

