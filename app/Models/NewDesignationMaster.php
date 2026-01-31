<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewDesignationMaster extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_designation_master';

    protected $fillable = [
        'company_id',
        'name'
    ];
}
