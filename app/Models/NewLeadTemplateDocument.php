<?php

namespace App\Models;

use App\Traits\HasCompany;
use App\Traits\IconTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewLeadTemplateDocument extends BaseModel
{
    use HasFactory, IconTrait, HasCompany;

    const FILE_PATH = 'new-lead-template-documents';

    protected $table = 'new_lead_template_documents';

    protected $fillable = [
        'company_id',
        'name',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'added_by'
    ];

    protected $appends = ['file_url', 'icon'];

    public function getFileUrlAttribute()
    {
        return asset_url_local_s3(NewLeadTemplateDocument::FILE_PATH . '/' . $this->file_path);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}

