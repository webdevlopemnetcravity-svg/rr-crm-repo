<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewCityMaster extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'new_city_master';

    protected $fillable = [
        'company_id',
        'state_id',
        'name'
    ];

    public function state()
    {
        return $this->belongsTo(NewStateMaster::class, 'state_id');
    }
}
