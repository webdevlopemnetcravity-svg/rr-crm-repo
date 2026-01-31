<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewMetaForm extends BaseModel
{
    use HasFactory;

    protected $table = 'new_meta_forms';

    protected $fillable = [
        'company_id',
        'user_id',
        'page_id',
        'page_name',
        'form_id',
        'form_name',
        'sync_status',
        'last_synced_at',
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
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
