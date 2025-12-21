<?php

namespace App\DataTables;

use App\Models\NewLead;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;

class NewLeadDataTable extends BaseDataTable
{

    private $editLeadPermission;
    private $deleteLeadPermission;
    private $viewLeadPermission;

    public function __construct()
    {
        parent::__construct();
        $this->editLeadPermission = user()->permission('edit_lead');
        $this->deleteLeadPermission = user()->permission('delete_lead');
        $this->viewLeadPermission = user()->permission('view_lead');
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {

        $datatables = datatables()->eloquent($query);
        $datatables->addIndexColumn();
        // Keep old action column for backward compatibility (hidden)
        $datatables->addColumn('action', function ($row) {
            $action = '<div class="task_view">

                    <div class="dropdown">
                        <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
                            id="dropdownMenuLink-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-options-vertical icons"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->id . '" tabindex="0">';

            $action .= '<a href="' . route('add-lead.index', ['lead_id' => $row->id]) . '" class="dropdown-item"><i class="fa fa-eye mr-2"></i>' . __('app.view') . '</a>';

            if (
                $this->editLeadPermission == 'all'
                || $this->editLeadPermission == 'both' && (user()->id == $row->added_by || user()->id == $row->lead_owner)
                || ($this->editLeadPermission == 'owned' && user()->id == $row->lead_owner )
                || ($this->editLeadPermission == 'added' && user()->id == $row->added_by) )
            {
                $action .= '<a class="dropdown-item" href="' . route('add-lead.index', ['lead_id' => $row->id]) . '">
                                <i class="fa fa-edit mr-2"></i>
                                ' . trans('app.edit') . '
                            </a>';
            }

            if (
                $this->deleteLeadPermission == 'all'
                || ($this->deleteLeadPermission == 'added' && user()->id == $row->added_by)
                || ($this->deleteLeadPermission == 'owned' && user()->id == $row->lead_owner)
                || ($this->deleteLeadPermission == 'both' && (user()->id == $row->added_by || user()->id == $row->lead_owner ))
            ) {
                $action .= '<a class="dropdown-item delete-table-row" href="javascript:;" data-id="' . $row->id . '">
                        <i class="fa fa-trash mr-2"></i>
                        ' . trans('app.delete') . '
                    </a>';
            }

            $action .= '</div>
                    </div>
                </div>';

            return $action;
        });

        // LEAD Column: ID in format "LEAD-0001", date of creation, and Lead Source
        $datatables->addColumn('lead', function ($row) {
            $leadId = 'LEAD-' . str_pad($row->id, 4, '0', STR_PAD_LEFT);
            $createdDate = $row->created_at ? $row->created_at->translatedFormat($this->company->date_format) : '--';
            $leadSource = $row->lead_source ?? '--';
            
            return '<div class="lead-info">
                        <div class="lead-id f-14 f-w-500 text-darkest-grey">' . $leadId . '</div>
                        <div class="lead-date f-12 text-dark-grey">' . $createdDate . '</div>
                        <div class="lead-source f-12 text-dark-grey">' . $leadSource . '</div>
                    </div>';
        });

        // CLIENT Column: Priority dropdown, mobile number, and email
        $datatables->addColumn('client', function ($row) {
            $priorityOptions = ['Select Priority', '1st Priority', '2nd Priority', '3rd Priority', '4th Priority', '5th Priority'];
            $currentPriority = $row->priority ?? 'Select Priority';
            
            // Map priorities to icon files
            $priorityIconMap = [
                'Select Priority' => '',
                '1st Priority' => '1st_Priority.svg',
                '2nd Priority' => '2nd_Priority.svg',
                '3rd Priority' => '3rd_Priority.svg',
                '4th Priority' => '4th_Priority.svg',
                '5th Priority' => '5th_Priority.svg',
            ];
            
            $prioritySelect = '<select class="form-control select-picker priority-select f-14" data-lead-id="' . $row->id . '" data-size="8">';
            foreach ($priorityOptions as $option) {
                $selected = ($currentPriority == $option) ? 'selected' : '';
                $iconFile = $priorityIconMap[$option] ?? '';
                
                // Build data-content with icon if available
                if ($iconFile && $option != 'Select Priority') {
                    $iconPath = asset('img/icon/' . $iconFile);
                    $content = '<div class="d-flex align-items-center"><img src="' . $iconPath . '" style="width: 18px; height: 18px; margin-right: 6px;"><span>' . htmlspecialchars($option) . '</span></div>';
                    $prioritySelect .= '<option value="' . htmlspecialchars($option) . '" ' . $selected . ' data-content="' . htmlspecialchars($content, ENT_QUOTES) . '">' . htmlspecialchars($option) . '</option>';
                } else {
                    $prioritySelect .= '<option value="' . htmlspecialchars($option) . '" ' . $selected . '>' . htmlspecialchars($option) . '</option>';
                }
            }
            $prioritySelect .= '</select>';
            
            $mobile = $row->mobile ?? '--';
            $email = $row->client_email ?? '--';
            
            // Get email from step_1_data if not in main field
            if ($email == '--' && $row->step_1_data) {
                $step1Data = is_array($row->step_1_data) ? $row->step_1_data : json_decode($row->step_1_data, true);
                $email = $step1Data['email_address'] ?? '--';
            }
            
            // Get mobile from step_1_data if not in main field
            if ($mobile == '--' && $row->step_1_data) {
                $step1Data = is_array($row->step_1_data) ? $row->step_1_data : json_decode($row->step_1_data, true);
                $mobile = $step1Data['primary_phone'] ?? '--';
            }
            
            return '<div class="client-info">
                        <div class="priority-dropdown mb-2">' . $prioritySelect . '</div>
                        <div class="client-mobile f-12 text-dark-grey mb-1">' . $mobile . '</div>
                        <div class="client-email f-12 text-dark-grey">' . $email . '</div>
                    </div>';
        });

        // SERVICES Column: Subclass from step 2 data
        $datatables->addColumn('services', function ($row) {
            $subclass = '--';
            if ($row->step_2_data) {
                $step2Data = is_array($row->step_2_data) ? $row->step_2_data : json_decode($row->step_2_data, true);
                if ($step2Data) {
                    // Check for different visa type subclasses
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
                    
                    // If subclassId is numeric, get name from database, otherwise use as-is (backward compatibility)
                    if ($subclassId && is_numeric($subclassId)) {
                        $subclassModel = \App\Models\NewLeadSubclass::find($subclassId);
                        $subclass = $subclassModel ? $subclassModel->name : '--';
                    } elseif ($subclassId) {
                        // Backward compatibility: if it's a string (old format), use it directly
                        $subclass = $subclassId;
                    }
                }
            }
            return '<div class="services-info f-14 text-darkest-grey">' . $subclass . '</div>';
        });

        // STATUS Column: Show "draft" if final_status is draft, otherwise show dropdown
        $datatables->addColumn('status', function ($row) {
            $isDraft = false;
            if ($row->stepStatus && $row->stepStatus->final_status == 'draft') {
                $isDraft = true;
            }
            
            if ($isDraft) {
                return '<div class="status-draft f-14 text-darkest-grey">Draft</div>';
            }
            
            $statusOptions = [
                'Untouched', 'Introduction', 'Info Collected', 'Consultation Call 1', 
                'Consultation Call 2', 'Consultation Meet 1', 'Consultation Meet 2', 
                'Documentation', 'Final Discussion', 'Estimation', 'Payment', 'MOU', 
                'File in Process', 'File Submission', 'Visa Process', 
                'Flying Date Received', 'Join/Move/Admissions', 'Follow Up', 'Lead Close'
            ];
            
            $currentStatus = $row->lead_status ?? 'Untouched';
            
            $statusSelect = '<select class="form-control select-picker status-select f-14" data-lead-id="' . $row->id . '" data-size="8">';
            foreach ($statusOptions as $option) {
                $selected = ($currentStatus == $option) ? 'selected' : '';
                $statusSelect .= '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
            }
            $statusSelect .= '</select>';
            
            return '<div class="status-dropdown">' . $statusSelect . '</div>';
        });

        // LEAD QUALITY Column: Show "-" if draft, otherwise show dropdown
        $datatables->addColumn('lead_quality', function ($row) {
            $isDraft = false;
            if ($row->stepStatus && $row->stepStatus->final_status == 'draft') {
                $isDraft = true;
            }
            
            if ($isDraft) {
                return '<div class="lead-quality-draft f-14 text-darkest-grey">-</div>';
            }
            
            $qualityOptions = [
                'Assigned', 'In-Process', 'On Hold', 'Plan Dropped', 
                'Negotiation', 'Future Prospect', 'Ringing', 
                'Dead/Junk Lead', 'Not Interested', 'Rejected'
            ];
            
            $currentQuality = $row->lead_quality ?? 'Assigned';
            
            $qualitySelect = '<select class="form-control select-picker quality-select f-14" data-lead-id="' . $row->id . '" data-size="8">';
            foreach ($qualityOptions as $option) {
                $selected = ($currentQuality == $option) ? 'selected' : '';
                $qualitySelect .= '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
            }
            $qualitySelect .= '</select>';
            
            return '<div class="lead-quality-dropdown">' . $qualitySelect . '</div>';
        });

        // FOLLOW-UP Column: Show last follow-up info and add button
        $datatables->addColumn('follow_up', function ($row) {
            // Check if lead is draft
            $isDraft = false;
            if ($row->stepStatus && $row->stepStatus->final_status == 'draft') {
                $isDraft = true;
            }
            
            $followUpCount = $row->followUps ? $row->followUps->count() : 0;
            $html = '<div class="follow-up-column">';
            
            // Show last follow-up information
            if ($followUpCount > 0 && $row->followUps) {
                $lastFollowUp = $row->followUps->sortByDesc('created_at')->first();
                
                // Format date
                $followUpDate = $lastFollowUp->created_at ? $lastFollowUp->created_at->format(company()->date_format) : '';
                $followUpType = ucfirst($lastFollowUp->follow_up_type ?? '');
                $subject = $lastFollowUp->subject ?? '';
                
                // Build tooltip content with all follow-up details
                $tooltipContent = '<div class="text-left" style="max-width: 300px; line-height: 1.6;">';
                $tooltipContent .= '<strong>Subject:</strong><br>' . htmlspecialchars($subject ?: 'N/A', ENT_QUOTES) . '<br><br>';
                $tooltipContent .= '<strong>Outcome:</strong><br>' . htmlspecialchars($lastFollowUp->outcome ?: 'N/A', ENT_QUOTES) . '<br><br>';
                $tooltipContent .= '<strong>Note:</strong><br>' . htmlspecialchars($lastFollowUp->notes ?: 'N/A', ENT_QUOTES) . '<br><br>';
                $tooltipContent .= '<strong>Reminder:</strong><br>' . ($lastFollowUp->send_reminder == 'yes' ? 'Yes' : 'No') . '<br><br>';
                
                if ($lastFollowUp->send_reminder == 'yes' && $lastFollowUp->next_follow_up_date) {
                    $reminderDateTime = $lastFollowUp->next_follow_up_date->format(company()->date_format . ' ' . company()->time_format);
                    $tooltipContent .= '<strong>Reminder Date & Time:</strong><br>' . htmlspecialchars($reminderDateTime, ENT_QUOTES) . '<br><br>';
                } else {
                    $tooltipContent .= '<strong>Reminder Date & Time:</strong><br>N/A<br><br>';
                }
                
                // Show only Updated if available, otherwise show Created
                $hasUpdate = $lastFollowUp->lastUpdatedBy && $lastFollowUp->updated_at && $lastFollowUp->updated_at->ne($lastFollowUp->created_at);
                
                if ($hasUpdate) {
                    // Show Updated information
                    $updatedBy = $lastFollowUp->lastUpdatedBy ? htmlspecialchars($lastFollowUp->lastUpdatedBy->name, ENT_QUOTES) : 'N/A';
                    $updatedAt = $lastFollowUp->updated_at ? $lastFollowUp->updated_at->format(company()->date_format . ' ' . company()->time_format) : 'N/A';
                    $tooltipContent .= '<strong>Updated By:</strong><br>' . $updatedBy . ' on ' . htmlspecialchars($updatedAt, ENT_QUOTES);
                } else {
                    // Show Created information
                    $createdBy = $lastFollowUp->addedBy ? htmlspecialchars($lastFollowUp->addedBy->name, ENT_QUOTES) : 'N/A';
                    $createdAt = $lastFollowUp->created_at ? $lastFollowUp->created_at->format(company()->date_format . ' ' . company()->time_format) : 'N/A';
                    $tooltipContent .= '<strong>Created By:</strong><br>' . $createdBy . ' on ' . htmlspecialchars($createdAt, ENT_QUOTES);
                }
                $tooltipContent .= '</div>';
                
                // Feedback section - format: "Subject: [subject] on [date] by [type]..."
                // Limit subject to 2 lines
                $feedbackText = '';
                if ($subject) {
                    $feedbackLines = preg_split('/\r\n|\r|\n/', $subject);
                    $feedbackLines = array_filter($feedbackLines); // Remove empty lines
                    $feedbackLines = array_slice($feedbackLines, 0, 2); // Take only first 2 lines
                    $feedbackText = implode(' ', $feedbackLines);
                    if (strlen($subject) > strlen($feedbackText)) {
                        $feedbackText .= '...';
                    }
                }
                
                // Use a unique ID for each tooltip to avoid conflicts
                $tooltipId = 'follow-up-tooltip-' . $row->id . '-' . $lastFollowUp->id;
                $html .= '<div class="last-follow-up-info mb-2 follow-up-tooltip-trigger" data-toggle="tooltip" data-html="true" data-placement="auto" data-tooltip-id="' . $tooltipId . '" title="' . htmlspecialchars($tooltipContent, ENT_QUOTES) . '" style="cursor: help; display: inline-block;">';
                $html .= '<div class="feedback-section f-12 text-dark-grey mb-1">';
                $html .= '<strong>Subject:</strong> ' . htmlspecialchars($feedbackText) . ' on ' . $followUpDate . ' by ' . $followUpType;
                $html .= '</div>';
                
                // Reminder section - only if reminder is set
                if ($lastFollowUp->send_reminder == 'yes' && $lastFollowUp->follow_up_subject_line) {
                    $reminderText = $lastFollowUp->follow_up_subject_line;
                    $reminderLines = preg_split('/\r\n|\r|\n/', $reminderText);
                    $reminderLines = array_filter($reminderLines); // Remove empty lines
                    $reminderLines = array_slice($reminderLines, 0, 2); // Take only first 2 lines
                    $reminderDisplay = implode(' ', $reminderLines);
                    if (strlen($reminderText) > strlen($reminderDisplay)) {
                        $reminderDisplay .= '...';
                    }
                    
                    $html .= '<div class="reminder-section f-12 text-dark-grey">';
                    $html .= '<strong>Reminder:</strong> ' . htmlspecialchars($reminderDisplay);
                    $html .= '</div>';
                }
                
                $html .= '</div>';
                
                // Edit button - only show if there's a follow-up and not draft
                if (!$isDraft) {
                    $html .= '<button type="button" class="edit-follow-up-btn-simple edit-follow-up-btn" data-follow-up-id="' . $lastFollowUp->id . '" data-lead-id="' . $row->id . '" style="padding: 0; background: none; border: none; color: #007bff; text-decoration: underline; cursor: pointer; margin-right: 10px;">
                                <i class="fa fa-edit"></i> Edit
                            </button>';
                }
            }
            
            // Add button - only show if not draft, simple style without padding and background
            if (!$isDraft) {
                $html .= '<button type="button" class="add-follow-up-btn add-follow-up-btn-simple" data-lead-id="' . $row->id . '" style="padding: 0; background: none; border: none; color: #007bff; text-decoration: underline; cursor: pointer; position: relative;">
                            <i class="fa fa-plus"></i> Add
                        </button>';
            }
            $html .= '</div>';
            return $html;
        });

        // ACTION Column: Lead owner image/placeholder with 2 letters and view button
        $datatables->addColumn('action_new', function ($row) {
            $action = '<div class="action-info d-flex align-items-center justify-content-end">';
            
            if ($row->leadOwner) {
                $ownerImage = $row->leadOwner->image_url;
                $ownerName = $row->leadOwner->name;
                $action .= '<img src="' . $ownerImage . '" class="rounded-circle mr-2" style="width: 32px; height: 32px; object-fit: cover;" alt="' . $ownerName . '" title="' . $ownerName . '">';
            } else {
                // Get 2 letters from client name
                $clientName = $row->client_name ?? 'NA';
                $initials = strtoupper(substr($clientName, 0, 2));
                if (strlen($clientName) > 1) {
                    // Try to get first letter of first and last word
                    $nameParts = explode(' ', trim($clientName));
                    if (count($nameParts) > 1) {
                        $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts) - 1], 0, 1));
                    } else {
                        $initials = strtoupper(substr($clientName, 0, 2));
                    }
                }
                $action .= '<div class="rounded-circle bg-light-grey d-flex align-items-center justify-content-center mr-2" style="width: 32px; height: 32px;">
                                <span class="f-12 text-dark-grey f-w-500">' . $initials . '</span>
                            </div>';
            }
            
            // Add edit button - always visible regardless of status
            $editUrl = route('add-lead.index', ['lead_id' => $row->id]);
            $action .= '<a href="' . $editUrl . '" class="btn btn-sm btn-secondary" title="' . __('app.edit') . '">
                            <i class="fa fa-edit"></i>
                        </a>';
            
            // Add view button - only visible if lead status is complete
            $isComplete = false;
            if ($row->stepStatus && $row->stepStatus->final_status == 'complete') {
                $isComplete = true;
            }
            
            if ($isComplete) {
                // Complete status - show view icon and redirect to lead-details
                $viewUrl = route('lead-details.index', ['id' => $row->id]);
                $action .= '<a href="' . $viewUrl . '" class="btn btn-sm btn-secondary ml-2" title="' . __('app.view') . '">
                                <i class="fa fa-eye"></i>
                            </a>';
            }
            
            // Add Reassign Lead button - visible to admins or if lead is unassigned
            $userRoles = user_roles();
            $isAdmin = in_array('admin', $userRoles);
            $isUnassigned = is_null($row->lead_owner);
            
            if ($isAdmin || $isUnassigned) {
                $leadNumber = 'LEAD-' . str_pad($row->id, 4, '0', STR_PAD_LEFT);
                $currentOwnerName = $row->leadOwner ? htmlspecialchars($row->leadOwner->name, ENT_QUOTES) : 'Not Assigned';
                $clientName = htmlspecialchars($row->client_name ?? 'N/A', ENT_QUOTES);
                
                $action .= '<button type="button" class="btn btn-sm btn-secondary ml-2 reassign-lead-btn" 
                                data-lead-id="' . $row->id . '" 
                                data-lead-number="' . htmlspecialchars($leadNumber, ENT_QUOTES) . '"
                                data-client-name="' . $clientName . '"
                                data-current-owner="' . $currentOwnerName . '"
                                title="' . __('app.reassignLead') . '">
                                <i class="fa fa-user-plus"></i>
                            </button>';
            }
            
            $action .= '</div>';
            
            return $action;
        });

        // Keep old columns for export compatibility
        $datatables->addColumn('export_email', fn($row) => $row->client_email);
        $datatables->addColumn('name', fn($row) => $row->client_name);
        $datatables->editColumn('added_by', fn($row) => $row->added_by ? view('components.employee', ['user' => $row->addedBy]) : '--');
        $datatables->editColumn('lead_owner', fn($row) => $row->lead_owner ? view('components.employee', ['user' => $row->leadOwner]) : '--');
        $datatables->addColumn('email', fn($row) => $row->client_email);
        $datatables->addColumn('export_mobile', fn($row) => $row->mobile ?? '--');
        $datatables->addColumn('lead_source', fn($row) => $row->lead_source ?? '--');

        $datatables->editColumn('created_at', fn($row) => $row->created_at?->translatedFormat($this->company->date_format));
        $datatables->smart(false);
        $datatables->setRowId(fn($row) => 'row-' . $row->id);

        $datatables->rawColumns(['action', 'action_new', 'lead', 'client', 'services', 'status', 'lead_quality', 'follow_up']);

        return $datatables;
    }

    /**
     * @param NewLead $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(NewLead $model)
    {
        $newLead = $model->with(['addedBy', 'leadOwner', 'stepStatus', 'followUps.addedBy', 'followUps.lastUpdatedBy'])
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

        if ($this->request()->startDate !== null && $this->request()->startDate != 'null' && $this->request()->startDate != '' && request()->date_filter_on == 'created_at') {
            $startDate = companyToDateString($this->request()->startDate);
            $newLead = $newLead->having(DB::raw('DATE(new_leads.`created_at`)'), '>=', $startDate);
        }

        if ($this->request()->endDate !== null && $this->request()->endDate != 'null' && $this->request()->endDate != '' && request()->date_filter_on == 'created_at') {
            $endDate = companyToDateString($this->request()->endDate);
            $newLead = $newLead->having(DB::raw('DATE(new_leads.`created_at`)'), '<=', $endDate);
        }

        if ($this->request()->startDate !== null && $this->request()->startDate != 'null' && $this->request()->startDate != '' && request()->date_filter_on == 'updated_at') {
            $startDate = companyToDateString($this->request()->startDate);
            $newLead = $newLead->having(DB::raw('DATE(new_leads.`updated_at`)'), '>=', $startDate);
        }

        if ($this->request()->endDate !== null && $this->request()->endDate != 'null' && $this->request()->endDate != '' && request()->date_filter_on == 'updated_at') {
            $endDate = companyToDateString($this->request()->endDate);
            $newLead = $newLead->having(DB::raw('DATE(new_leads.`updated_at`)'), '<=', $endDate);
        }

        if ($this->request()->source_id != 'all' && $this->request()->source_id != '') {
            // Filter by lead source directly (now using hardcoded values)
            $newLead = $newLead->where('new_leads.lead_source', $this->request()->source_id);
        }

        if ($this->viewLeadPermission == 'all' && $this->request()->filter_addedBy != 'all' && $this->request()->filter_addedBy != '') {
            $newLead = $newLead->where('new_leads.added_by', $this->request()->filter_addedBy);
        }

        if ($this->viewLeadPermission == 'owned') {
            $newLead = $newLead->where('new_leads.lead_owner', user()->id);
        }

        if ($this->viewLeadPermission == 'added') {
            $newLead = $newLead->where('new_leads.added_by', user()->id);
        }

        if ($this->viewLeadPermission == 'both') {
            $newLead = $newLead->where(function ($query) {
                $query->where('new_leads.lead_owner', user()->id)
                      ->orWhere('new_leads.added_by', user()->id);
            });
        }

        if ($this->request()->searchText != '') {
            $searchText = request('searchText');
            $newLead = $newLead->where(function ($query) use ($searchText) {
                // Search by name
                $query->where('new_leads.client_name', 'like', '%' . $searchText . '%')
                    ->orWhere('new_leads.client_email', 'like', '%' . $searchText . '%')
                    ->orWhere('new_leads.mobile', 'like', '%' . $searchText . '%');
                
                // Search by lead number (extract number from "LEAD-0001" format)
                if (preg_match('/LEAD-?(\d+)/i', $searchText, $matches)) {
                    $leadId = (int)$matches[1];
                    $query->orWhere('new_leads.id', $leadId);
                } elseif (is_numeric($searchText)) {
                    // If just a number, search by ID
                    $query->orWhere('new_leads.id', (int)$searchText);
                }
                
                // Search by subclass in step_2_data JSON
                $query->orWhere(function ($q) use ($searchText) {
                    $q->whereRaw("JSON_EXTRACT(new_leads.step_2_data, '$.pr_subclass') LIKE ?", ['%' . $searchText . '%'])
                      ->orWhereRaw("JSON_EXTRACT(new_leads.step_2_data, '$.visit_subclass') LIKE ?", ['%' . $searchText . '%'])
                      ->orWhereRaw("JSON_EXTRACT(new_leads.step_2_data, '$.work_subclass') LIKE ?", ['%' . $searchText . '%'])
                      ->orWhereRaw("JSON_EXTRACT(new_leads.step_2_data, '$.student_subclass') LIKE ?", ['%' . $searchText . '%']);
                });
            });
        }
        
        // Filter by Lead Status
        if ($this->request()->filter_lead_status != 'all' && $this->request()->filter_lead_status != '') {
            $newLead = $newLead->where('new_leads.lead_status', $this->request()->filter_lead_status);
        }
        
        // Filter by Subclass
        if ($this->request()->filter_subclass != 'all' && $this->request()->filter_subclass != '') {
            $subclass = $this->request()->filter_subclass;
            $newLead = $newLead->where(function ($query) use ($subclass) {
                $query->whereRaw("JSON_EXTRACT(new_leads.step_2_data, '$.pr_subclass') = ?", [$subclass])
                      ->orWhereRaw("JSON_EXTRACT(new_leads.step_2_data, '$.visit_subclass') = ?", [$subclass])
                      ->orWhereRaw("JSON_EXTRACT(new_leads.step_2_data, '$.work_subclass') = ?", [$subclass])
                      ->orWhereRaw("JSON_EXTRACT(new_leads.step_2_data, '$.student_subclass') = ?", [$subclass]);
            });
        }
        
        // Filter by Priority
        if ($this->request()->filter_priority != 'all' && $this->request()->filter_priority != '') {
            $newLead = $newLead->where('new_leads.priority', $this->request()->filter_priority);
        }

        // Order by ID in descending order (newest first)
        $newLead = $newLead->orderBy('new_leads.id', 'desc');

        return $newLead;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
            $dataTable = $this->setBuilder('new-leads-table', 2)
            ->parameters([
                'initComplete' => 'function () {
                   $(".priority-select, .status-select, .quality-select").selectpicker();
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    // Destroy existing tooltips to prevent conflicts
                    $(".follow-up-tooltip-trigger").tooltip("dispose");
                    // Initialize tooltips for follow-up info
                    $(".follow-up-tooltip-trigger").tooltip({
                        html: true,
                        placement: "top",
                        trigger: "hover",
                        container: "body"
                    });
                    // Initialize other tooltips
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]:not(.follow-up-tooltip-trigger)\'
                    });
                    $(".priority-select, .status-select, .quality-select").selectpicker();
                }',
            ]);

        // Export button removed

        return $dataTable;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        // New column structure
        $newColumns = [
            __('modules.lead.lead') => [
                'data' => 'lead',
                'name' => 'lead',
                'title' => __('modules.lead.lead'),
                'exportable' => false,
                'orderable' => false,
                'searchable' => false
            ],
            __('app.client') => [
                'data' => 'client',
                'name' => 'client',
                'title' => __('app.client'),
                'exportable' => false,
                'orderable' => false,
                'searchable' => false
            ],
            __('app.services') => [
                'data' => 'services',
                'name' => 'services',
                'title' => __('app.services'),
                'exportable' => false,
                'orderable' => false,
                'searchable' => false
            ],
            __('app.status') => [
                'data' => 'status',
                'name' => 'status',
                'title' => __('app.status'),
                'exportable' => false,
                'orderable' => false,
                'searchable' => false
            ],
            __('modules.lead.leadQuality') => [
                'data' => 'lead_quality',
                'name' => 'lead_quality',
                'title' => __('modules.lead.leadQuality'),
                'exportable' => false,
                'orderable' => false,
                'searchable' => false
            ],
            __('modules.lead.followUp') => [
                'data' => 'follow_up',
                'name' => 'follow_up',
                'title' => __('modules.lead.followUp'),
                'exportable' => false,
                'orderable' => false,
                'searchable' => false
            ],
            Column::computed('action_new', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->addClass('text-right pr-20')
        ];

        // Keep old columns hidden for export compatibility
        $hiddenColumns = [
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'visible' => false, 'title' => '#'],
            __('app.name') => ['data' => 'client_name', 'name' => 'name', 'exportable' => true, 'visible' => false, 'title' => __('app.name')],
            __('app.email') . ' ' . __('modules.lead.email') => ['data' => 'export_email', 'name' => 'email', 'title' => __('app.lead') . ' ' . __('modules.lead.email'), 'exportable' => true, 'visible' => false],
            __('app.lead') . ' ' . __('modules.lead.mobile') => ['data' => 'export_mobile', 'name' => 'mobile', 'title' => __('app.lead') . ' ' . __('modules.lead.mobile'), 'exportable' => true, 'visible' => false],
            __('modules.lead.leadSource') => ['data' => 'lead_source', 'name' => 'new_leads.lead_source', 'exportable' => true, 'visible' => false],
            __('app.owner') => ['data' => 'lead_owner', 'name' => 'lead_owner', 'exportable' => true, 'visible' => false],
            __('app.addedBy') => ['data' => 'added_by', 'name' => 'added_by', 'exportable' => true, 'visible' => false],
            __('app.createdOn') => ['data' => 'created_at', 'name' => 'new_leads.created_at', 'title' => __('app.createdOn'), 'visible' => false],
        ];

        return array_merge($newColumns, $hiddenColumns);

    }

}

