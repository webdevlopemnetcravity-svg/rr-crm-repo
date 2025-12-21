<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewLeadDependsDocument extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_lead_depends_documents';

    protected $fillable = [
        'company_id',
        'name'
    ];
}
