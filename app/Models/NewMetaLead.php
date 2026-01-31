<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewMetaLead extends BaseModel
{
    use HasFactory;

    protected $table = 'new_meta_leads';

    protected $fillable = [
        'company_id',
        'user_id',
        'page_id',
        'page_name',
        'form_id',
        'form_name',
        'meta_lead_id',
        'field_data',
        'full_name',
        'email',
        'phone',
        'lead_created_time',
        'status',
        'new_lead_id',
        'lead_number',
    ];

    protected $casts = [
        'lead_created_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Extract name, email, phone from Facebook field_data array.
     * Field names can vary (full_name, first_name, email, phone_number, etc.)
     */
    public static function extractFieldsFromFieldData(array $fieldData): array
    {
        $name = null;
        $email = null;
        $phone = null;

        $nameKeys = ['full_name', 'first_name', 'name', 'last_name'];
        $emailKeys = ['email'];
        $phoneKeys = ['phone_number', 'phone', 'mobile', 'telephone'];

        foreach ($fieldData as $field) {
            $fieldName = $field['name'] ?? '';
            $values = $field['values'] ?? [];
            $value = is_array($values) ? (reset($values) ?? '') : (string) $values;

            if (in_array(strtolower($fieldName), $nameKeys) && $value) {
                if ($name) {
                    $name = trim($name . ' ' . $value);
                } else {
                    $name = $value;
                }
            }
            if (in_array(strtolower($fieldName), $emailKeys) && $value) {
                $email = $value;
            }
            if (in_array(strtolower($fieldName), $phoneKeys) && $value) {
                $phone = $value;
            }
        }

        return ['full_name' => $name, 'email' => $email, 'phone' => $phone];
    }
}
