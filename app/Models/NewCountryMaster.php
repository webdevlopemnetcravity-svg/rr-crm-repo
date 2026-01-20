<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewCountryMaster extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_countery_master';

    protected $fillable = [
        'company_id',
        'name'
    ];

    public function states()
    {
        return $this->hasMany(NewStateMaster::class, 'country_id');
    }
}
