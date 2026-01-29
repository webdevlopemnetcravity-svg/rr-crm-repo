<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewOrganizationTypesMaster extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_organization_types_master';

    protected $fillable = [
        'company_id',
        'name'
    ];
}
