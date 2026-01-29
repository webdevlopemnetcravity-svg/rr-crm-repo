<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewRelationshipsMaster extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_relationships_master';

    protected $fillable = [
        'company_id',
        'name'
    ];
}
