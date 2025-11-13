<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImmigrationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

public function rules(): array
{
    return [
        'applicant_name'   => 'required|string|max:191',

        // header
        'application_number' => 'nullable|string|max:191',
        'application_date'   => 'nullable|date',
        'lead_source'        => 'nullable|string|max:191',
        'lead_source_other'  => 'nullable|string|max:191',
        'resume'             => 'nullable|file|max:10240', // 10MB

        // two existing doc uploads (keep)
        'document_one' => 'nullable|file|max:10240',
        'document_two' => 'nullable|file|max:10240',

        // sections (arrays/strings)
        'personal.*'  => 'nullable',
        'passport.*'  => 'nullable',
        'relative.*'  => 'nullable',
        'family.*'    => 'nullable',
        'spouse.*'    => 'nullable',
        'education.*' => 'nullable',
        'jobs'        => 'nullable|array',
        'jobs.*.from'       => 'nullable|string|max:50',
        'jobs.*.to'         => 'nullable|string|max:50',
        'jobs.*.country'    => 'nullable|string|max:191',
        'jobs.*.designation'=> 'nullable|string|max:191',
        'jobs.*.company'    => 'nullable|string|max:191',
        'jobs.*.salary'     => 'nullable|string|max:191',
        'jobs.*.offer'      => 'nullable|file|max:10240',
        'jobs.*.experience' => 'nullable|file|max:10240',
        'property.*'  => 'nullable',
        'finance.*'   => 'nullable',
        'travel.*'    => 'nullable',
    ];
}

}