<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewHighestQualificationMaster extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_highest_qualification_master';

    protected $fillable = [
        'company_id',
        'name'
    ];
}
