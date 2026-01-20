<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewVisaCategoryMaster extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_visa_category_master';

    protected $fillable = [
        'company_id',
        'name'
    ];
}
