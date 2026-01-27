<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewInstitutionTypeMaster extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_institution_type_master';

    protected $fillable = [
        'company_id',
        'name'
    ];
}
