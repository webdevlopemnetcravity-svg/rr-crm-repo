<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewLeadAppointment extends BaseModel
{
    use HasFactory;

    protected $table = 'new_lead_appointments';

    protected $fillable = [
        'company_id',
        'lead_id',
        'meeting_title',
        'description',
        'appointment_date',
        'start_time',
        'end_time',
        'google_meet_link',
        'google_event_id',
        'zoom_link',
        'zoom_meeting_id',
        'zoom_meeting_password',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(NewLead::class, 'lead_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

