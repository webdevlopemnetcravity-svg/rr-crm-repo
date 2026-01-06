<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewGoogleToken extends BaseModel
{
    use HasFactory;

    protected $table = 'new_google_token';

    protected $fillable = [
        'company_id',
        'user_id',
        'access_token',
        'refresh_token',
        'calendar_id',
        'google_id',
        'name',
        'email',
        'verification_status',
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
     * Get the token array for Google Client
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

        if (!empty($this->refresh_token)) {
            $token['refresh_token'] = $this->refresh_token;
        }

        return $token;
    }
}
