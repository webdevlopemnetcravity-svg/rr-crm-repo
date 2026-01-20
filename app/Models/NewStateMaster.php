<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewStateMaster extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_state_master';

    protected $fillable = [
        'company_id',
        'country_id',
        'name'
    ];

    public function country()
    {
        return $this->belongsTo(NewCountryMaster::class, 'country_id');
    }

    public function cities()
    {
        return $this->hasMany(NewCityMaster::class, 'state_id');
    }
}
