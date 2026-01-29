<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewPassportHistoryMaster extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_passport_history_master';

    protected $fillable = [
        'company_id',
        'name'
    ];
}
