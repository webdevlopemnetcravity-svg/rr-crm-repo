<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewFacebookToken extends BaseModel
{
    use HasFactory;

    protected $table = 'new_facebook_token';

    protected $fillable = [
        'company_id',
        'user_id',
        'access_token',
        'refresh_token', // Kept for database compatibility, but Meta does not provide refresh tokens
        'expires_at',
        'facebook_id',
        'name',
        'email',
        'verification_status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the token array for Facebook Client
     * Note: Meta does NOT provide refresh tokens - only long-lived access tokens (~60 days)
     */
    public function getTokenArray()
    {
        if (empty($this->access_token)) {
            return null;
        }

        $token = [
            'access_token' => $this->access_token,
            'token_type' => 'Bearer',
            'created' => $this->created_at ? $this->created_at->timestamp : time(),
        ];

        // Meta does not provide refresh tokens, so we don't include it
        // The refresh_token field exists in the database for compatibility but is always null

        return $token;
    }

    /**
     * Check if the token is expired
     */
    public function isExpired()
    {
        if (!$this->expires_at) {
            return false; // If no expiration date, assume not expired
        }
        return $this->expires_at->isPast();
    }
}
