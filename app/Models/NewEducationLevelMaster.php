<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewEducationLevelMaster extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_education_level_master';

    protected $fillable = [
        'company_id',
        'name'
    ];
}
