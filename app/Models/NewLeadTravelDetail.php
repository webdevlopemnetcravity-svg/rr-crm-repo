<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewLeadTravelDetail extends BaseModel
{
    protected $table = 'new_lead_travel_details';

    protected $fillable = [
        'new_lead_id',
        'purpose_of_trip',
        'place_to_visit',
        'date_of_arrival',
        'arrival_flight',
        'arrival_city',
        'date_of_departure',
        'departure_flight',
        'departure_city',
        'phone_number_other_country',
        'address_stay',
        'city',
        'state',
        'postal_code',
        'person_paying',
        'mother_in_country',
        'immediate_relatives',
        'other_relatives',
        'added_by',
        'last_updated_by',
    ];

    protected $casts = [
        'date_of_arrival' => 'date',
        'date_of_departure' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the new lead that owns this travel detail.
     */
    public function newLead(): BelongsTo
    {
        return $this->belongsTo(NewLead::class, 'new_lead_id');
    }

    /**
     * Get the user who added this travel detail.
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Get the user who last updated this travel detail.
     */
    public function lastUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }
}

