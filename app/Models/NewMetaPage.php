<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewMetaPage extends BaseModel
{
    use HasFactory;

    protected $table = 'new_meta_pages';

    protected $fillable = [
        'company_id',
        'user_id',
        'page_id',
        'page_name',
        'page_access_token',
        'page_category',
        'page_picture_url',
        'page_about',
        'page_website',
        'page_verification_status',
        'page_followers_count',
        'page_likes_count',
        'sync_status',
        'last_synced_at',
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
        'page_followers_count' => 'integer',
        'page_likes_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
