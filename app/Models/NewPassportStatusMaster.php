<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewPassportStatusMaster extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_passport_status_master';

    protected $fillable = [
        'company_id',
        'name'
    ];
}
