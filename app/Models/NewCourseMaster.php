<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewCourseMaster extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_course_master';

    protected $fillable = [
        'company_id',
        'name'
    ];
}
