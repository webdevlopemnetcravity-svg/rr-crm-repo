<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewLeadAccount extends BaseModel
{
    protected $table = 'new_lead_account';

    protected $fillable = [
        'new_lead_id',
        'client_name',
        'invoice_date',
        'phone',
        'email',
        'address',
        'bill_to',
        'agent',
        'service',
        'price',
        'tax',
        'discount',
        'net_amount',
        'sub_total',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'service_description',
        'installment_payment',
        'installment_months',
        'invoice_notes',
        'invoice_file',
        'status',
        'added_by',
        'last_updated_by',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'installment_payment' => 'boolean',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the new lead that owns this account.
     */
    public function newLead(): BelongsTo
    {
        return $this->belongsTo(NewLead::class, 'new_lead_id');
    }

    /**
     * Get the agent user.
     */
    public function agentUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent');
    }

    /**
     * Get the user who added this account.
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Get the user who last updated this account.
     */
    public function lastUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }
}

