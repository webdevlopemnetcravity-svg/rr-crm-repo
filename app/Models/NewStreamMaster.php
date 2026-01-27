<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewStreamMaster extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_stream_master';

    protected $fillable = [
        'company_id',
        'name'
    ];
}
