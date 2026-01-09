<?php

namespace App\Exports;

use App\Models\NewLead;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LeadListExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $newLead = NewLead::with(['addedBy', 'leadOwner', 'stepStatus'])
            ->select(
                'new_leads.id',
                'new_leads.added_by',
                'new_leads.lead_owner',
                'new_leads.client_name',
                'new_leads.client_email',
                'new_leads.mobile',
                'new_leads.lead_source',
                'new_leads.priority',
                'new_leads.lead_status',
                'new_leads.lead_quality',
                'new_leads.step_1_data',
                'new_leads.step_2_data',
                'new_leads.created_at',
                'new_leads.updated_at',
            );

        // Apply same filters as DataTable
        if ($this->request->startDate !== null && $this->request->startDate != 'null' && $this->request->startDate != '' && $this->request->date_filter_on == 'created_at') {
            $startDate = companyToDateString($this->request->startDate);
            $newLead = $newLead->having(DB::raw('DATE(new_leads.`created_at`)'), '>=', $startDate);
        }

        if ($this->request->endDate !== null && $this->request->endDate != 'null' && $this->request->endDate != '' && $this->request->date_filter_on == 'created_at') {
            $endDate = companyToDateString($this->request->endDate);
            $newLead = $newLead->having(DB::raw('DATE(new_leads.`created_at`)'), '<=', $endDate);
        }

        if ($this->request->startDate !== null && $this->request->startDate != 'null' && $this->request->startDate != '' && $this->request->date_filter_on == 'updated_at') {
            $startDate = companyToDateString($this->request->startDate);
            $newLead = $newLead->having(DB::raw('DATE(new_leads.`updated_at`)'), '>=', $startDate);
        }

        if ($this->request->endDate !== null && $this->request->endDate != 'null' && $this->request->endDate != '' && $this->request->date_filter_on == 'updated_at') {
            $endDate = companyToDateString($this->request->endDate);
            $newLead = $newLead->having(DB::raw('DATE(new_leads.`updated_at`)'), '<=', $endDate);
        }

        if ($this->request->source_id != 'all' && $this->request->source_id != '') {
            $newLead = $newLead->where('new_leads.lead_source', $this->request->source_id);
        }

        if ($this->request->filter_addedBy != 'all' && $this->request->filter_addedBy != '') {
            $newLead = $newLead->where('new_leads.added_by', $this->request->filter_addedBy);
        }

        if ($this->request->filter_assignedTo != 'all' && $this->request->filter_assignedTo != '') {
            if ($this->request->filter_assignedTo == 'unassigned') {
                $newLead = $newLead->whereNull('new_leads.lead_owner');
            } else {
                $newLead = $newLead->where('new_leads.lead_owner', $this->request->filter_assignedTo);
            }
        }

        if ($this->request->searchText != '') {
            $searchText = $this->request->searchText;
            $newLead = $newLead->where(function ($query) use ($searchText) {
                $query->where('new_leads.client_name', 'like', '%' . $searchText . '%')
                    ->orWhere('new_leads.client_email', 'like', '%' . $searchText . '%')
                    ->orWhere('new_leads.mobile', 'like', '%' . $searchText . '%');
                
                if (preg_match('/LEAD-?(\d+)/i', $searchText, $matches)) {
                    $leadId = (int)$matches[1];
                    $query->orWhere('new_leads.id', $leadId);
                } elseif (is_numeric($searchText)) {
                    $query->orWhere('new_leads.id', (int)$searchText);
                }
                
                $query->orWhere(function ($q) use ($searchText) {
                    $q->whereRaw("JSON_EXTRACT(new_leads.step_2_data, '$.pr_subclass') LIKE ?", ['%' . $searchText . '%'])
                      ->orWhereRaw("JSON_EXTRACT(new_leads.step_2_data, '$.visit_subclass') LIKE ?", ['%' . $searchText . '%'])
                      ->orWhereRaw("JSON_EXTRACT(new_leads.step_2_data, '$.work_subclass') LIKE ?", ['%' . $searchText . '%'])
                      ->orWhereRaw("JSON_EXTRACT(new_leads.step_2_data, '$.student_subclass') LIKE ?", ['%' . $searchText . '%']);
                });
            });
        }
        
        if ($this->request->filter_lead_status != 'all' && $this->request->filter_lead_status != '') {
            if ($this->request->filter_lead_status == 'draft') {
                // Filter by draft status (check stepStatus final_status)
                $newLead = $newLead->whereHas('stepStatus', function ($query) {
                    $query->where('final_status', 'draft');
                });
            } else {
                // Filter by regular lead status
                $newLead = $newLead->where('new_leads.lead_status', $this->request->filter_lead_status);
            }
        }
        
        if ($this->request->filter_lead_quality != 'all' && $this->request->filter_lead_quality != '') {
            $newLead = $newLead->where('new_leads.lead_quality', $this->request->filter_lead_quality);
        }
        
        if ($this->request->filter_subclass != 'all' && $this->request->filter_subclass != '') {
            $subclass = $this->request->filter_subclass;
            $newLead = $newLead->where(function ($query) use ($subclass) {
                $query->whereRaw("JSON_EXTRACT(new_leads.step_2_data, '$.pr_subclass') = ?", [$subclass])
                      ->orWhereRaw("JSON_EXTRACT(new_leads.step_2_data, '$.visit_subclass') = ?", [$subclass])
                      ->orWhereRaw("JSON_EXTRACT(new_leads.step_2_data, '$.work_subclass') = ?", [$subclass])
                      ->orWhereRaw("JSON_EXTRACT(new_leads.step_2_data, '$.student_subclass') = ?", [$subclass]);
            });
        }
        
        if ($this->request->filter_priority != 'all' && $this->request->filter_priority != '') {
            $newLead = $newLead->where('new_leads.priority', $this->request->filter_priority);
        }

        $newLead = $newLead->orderBy('new_leads.id', 'desc');

        return $newLead->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Lead Number',
            'Created Date',
            'Lead Source',
            'Client Name',
            'Mobile',
            'Email',
            'Priority',
            'Services',
            'Status',
            'Lead Quality',
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        // Lead Number
        $leadId = 'LEAD-' . str_pad($row->id, 4, '0', STR_PAD_LEFT);
        $createdDate = $row->created_at ? $row->created_at->format(company()->date_format) : '--';
        $leadSource = $row->lead_source ?? '--';
        
        // Client Name (Surname + Given Name)
        $personName = '--';
        if ($row->step_1_data) {
            $step1Data = is_array($row->step_1_data) ? $row->step_1_data : json_decode($row->step_1_data, true);
            if ($step1Data) {
                $surname = $step1Data['surname'] ?? '';
                $givenName = $step1Data['given_name'] ?? '';
                $fullName = trim($surname . ' ' . $givenName);
                $personName = $fullName ?: '--';
            }
        }
        if ($personName == '--' && $row->client_name) {
            $personName = $row->client_name;
        }
        
        $mobile = $row->mobile ?? '--';
        if ($mobile == '--' && $row->step_1_data) {
            $step1Data = is_array($row->step_1_data) ? $row->step_1_data : json_decode($row->step_1_data, true);
            $mobile = $step1Data['primary_phone'] ?? '--';
        }
        
        $email = $row->client_email ?? '--';
        if ($email == '--' && $row->step_1_data) {
            $step1Data = is_array($row->step_1_data) ? $row->step_1_data : json_decode($row->step_1_data, true);
            $email = $step1Data['email_address'] ?? '--';
        }
        
        $priority = $row->priority ?? 'Select Priority';
        
        // Services (Subclass)
        $subclass = '--';
        if ($row->step_2_data) {
            $step2Data = is_array($row->step_2_data) ? $row->step_2_data : json_decode($row->step_2_data, true);
            if ($step2Data) {
                $subclassId = null;
                if (isset($step2Data['pr_subclass']) && !empty($step2Data['pr_subclass'])) {
                    $subclassId = $step2Data['pr_subclass'];
                } elseif (isset($step2Data['visit_subclass']) && !empty($step2Data['visit_subclass'])) {
                    $subclassId = $step2Data['visit_subclass'];
                } elseif (isset($step2Data['work_subclass']) && !empty($step2Data['work_subclass'])) {
                    $subclassId = $step2Data['work_subclass'];
                } elseif (isset($step2Data['student_subclass']) && !empty($step2Data['student_subclass'])) {
                    $subclassId = $step2Data['student_subclass'];
                }
                
                if ($subclassId && is_numeric($subclassId)) {
                    $subclassModel = \App\Models\NewLeadSubclass::find($subclassId);
                    $subclass = $subclassModel ? $subclassModel->name : '--';
                } elseif ($subclassId) {
                    $subclass = $subclassId;
                }
            }
        }
        
        $status = $row->lead_status ?? 'Open Lead';
        $quality = $row->lead_quality ?? 'Open';
        
        return [
            $leadId,
            $createdDate,
            $leadSource,
            $personName,
            $mobile,
            $email,
            $priority,
            $subclass,
            $status,
            $quality,
        ];
    }
}

