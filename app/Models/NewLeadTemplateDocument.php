<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewLeadTemplateDocument extends BaseModel
{
    use HasFactory, HasCompany;

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

    protected $appends = ['file_url', 'icon', 'file_type_color'];

    public function getFileUrlAttribute()
    {
        return asset_url_local_s3(NewLeadTemplateDocument::FILE_PATH . '/' . $this->file_path);
    }

    public function getIconAttribute()
    {
        $filename = $this->file_name ?? $this->file_path;
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $mimeType = [
            'txt' => 'fa-file-alt',
            'pdf' => 'fa-file-pdf',
            'doc' => 'fa-file-word',
            'docx' => 'fa-file-word',
            'xls' => 'fa-file-excel',
            'xlsx' => 'fa-file-excel',
            'ppt' => 'fa-file-powerpoint',
            'pptx' => 'fa-file-powerpoint',
            'png' => 'fa-file-image',
            'jpe' => 'fa-file-image',
            'jpeg' => 'fa-file-image',
            'jpg' => 'fa-file-image',
            'gif' => 'fa-file-image',
            'bmp' => 'fa-file-image',
            'ico' => 'fa-file-image',
            'tiff' => 'fa-file-image',
            'tif' => 'fa-file-image',
            'svg' => 'fa-file-image',
        ];

        return $mimeType[$ext] ?? 'fa-file';
    }

    public function getFileTypeColorAttribute()
    {
        $filename = $this->file_name ?? $this->file_path;
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // PDF - Red
        if ($ext === 'pdf') {
            return 'text-danger';
        }

        // Images - Green
        $imageFormats = ['png', 'jpe', 'jpeg', 'jpg', 'gif', 'bmp', 'ico', 'tiff', 'tif', 'svg'];
        if (in_array($ext, $imageFormats)) {
            return 'text-success';
        }

        // Word Documents - Blue
        if (in_array($ext, ['doc', 'docx'])) {
            return 'text-primary';
        }

        // Excel - Green (darker)
        if (in_array($ext, ['xls', 'xlsx'])) {
            return 'text-success';
        }

        // PowerPoint - Orange
        if (in_array($ext, ['ppt', 'pptx'])) {
            return 'text-warning';
        }

        // Default - Grey
        return 'text-secondary';
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}

