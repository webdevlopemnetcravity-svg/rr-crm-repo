<?php

namespace App\Http\Controllers;

use App\DataTables\DealsDataTable;
use App\DataTables\LeadContactDataTable;
use App\DataTables\LeadNotesDataTable;
use App\DataTables\NewLeadDataTable;
use App\Enums\Salutation;
use App\Helper\Reply;
use App\Http\Requests\Admin\Employee\ImportProcessRequest;
use App\Http\Requests\Admin\Employee\ImportRequest;
use App\Http\Requests\Lead\StoreRequest;
use App\Http\Requests\Lead\UpdateRequest;
use App\Imports\LeadImport;
use App\Jobs\ImportLeadJob;
use App\Models\Deal;
use App\Models\LeadAgent;
use App\Models\LeadCategory;
use App\Models\Lead;
use App\Models\LeadCustomForm;
use App\Models\LeadPipeline;
use App\Models\LeadProduct;
use App\Models\LeadSource;
use App\Models\LeadStepLog;
use App\Models\LeadStepStatus;
use App\Models\LeadStatusChangeLog;
use App\Models\NewLead;
use App\Models\NewLeadAccount;
use App\Models\NewLeadAppointment;
use App\Models\NewLeadFollowUp;
use App\Models\NewLeadProcess;
use App\Models\NewLeadVisaType;
use App\Models\NewVisaCategoryMaster;
use App\Models\NewLanguageMaster;
use App\Models\NewCountryMaster;
use App\Models\NewStateMaster;
use App\Models\NewCityMaster;
use App\Models\NewGoogleToken;
use App\Models\SocialAuthSetting;
use App\Services\Google;
use App\Mail\LeadAppointmentBookedToLead;
use App\Mail\LeadAppointmentBookedToUser;
use MacsiDigital\Zoom\Facades\Zoom;
use App\Models\PipelineStage;
use App\Models\LeadStatus;
use App\Models\Product;
use App\Models\User;
use App\Traits\ImportExcel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Mail\LeadConfirmation;
use App\Mail\LeadCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Exports\LeadListExport;
use Maatwebsite\Excel\Facades\Excel;

class LeadContactController extends AccountBaseController
{

    use ImportExcel;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'modules.leadContact.leadContacts';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('leads', $this->user->modules));

            return $next($request);
        });
    }

    public function index(LeadContactDataTable $dataTable)
    {
        $this->destroySession();
        $this->viewLeadPermission = $viewPermission = user()->permission('view_lead');

        abort_403(!in_array($viewPermission, ['all','added','owned','both']));

        if (!request()->ajax()) {
            $this->categories = LeadCategory::get();
            $this->sources = LeadSource::get();
            $this->employees = User::allEmployees(null, 'active');
        }

        return $dataTable->render('lead-contact.index', $this->data);

    }

    public function leadList(NewLeadDataTable $dataTable)
    {
        $this->destroySession();
        $this->viewLeadPermission = $viewPermission = user()->permission('view_lead');

        abort_403(!in_array($viewPermission, ['all','added','owned','both']));

        $this->pageTitle = 'app.leadList';

        if (!request()->ajax()) {
            $this->categories = LeadCategory::get();
            $this->employees = User::allEmployees(null, 'active');
            
            // Get users with admin, consultant, and receptionist roles for filters
            $this->filterUsers = User::join('employee_details', 'employee_details.user_id', '=', 'users.id')
                ->leftJoin('designations', 'employee_details.designation_id', '=', 'designations.id')
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('users.id', 'users.company_id', 'users.name', 'users.email', 'users.created_at', 'users.image', 'designations.name as designation_name', 'users.email_notifications', 'users.mobile', 'users.country_id', 'users.status')
                ->where('users.company_id', company()->id)
                ->where('users.status', 'active')
                ->whereIn('roles.name', ['admin', 'consultant', 'receptionist'])
                ->orderBy('users.name')
                ->groupBy('users.id')
                ->get();
            
            // Hardcoded lead source values for filter
            $this->sources = collect([
                'Facebook',
                'Google Ads',
                'Walk-in',
                'WhatsApp Inquiry',
                'Reference',
                'Website',
                'Email Marketing'
            ])->map(function ($source) {
                return (object)['id' => $source, 'type' => $source];
            });
            
            // Hardcoded lead status values for filter
            $this->leadStatuses = collect([
                'Open Lead',
                'Consultation in Progress',
                'Meeting in Progress',
                'Documentation',
                'Final Discussion',
                'Estimation',
                'Payment',
                'MOU',
                'File in Process',
                'File Submission',
                'Visa Process',
                'Flying Date Received',
                'Join/Move/Admissions',
                'Follow Up',
                'Lead Close'
            ])->map(function ($status) {
                return (object)['id' => $status, 'type' => $status];
            });
            
            // Hardcoded lead quality values for filter (must match DataTable options)
            $this->leadQualityOptions = [
                'Open',
                'In-Process',
                'On Hold',
                'Plan Dropped',
                'Negotiation',
                'Future Prospect',
                'Ringing',
                'Dead/Junk Lead',
                'Not Interested',
                'Rejected'
            ];
            
            // Hardcoded service/subclass values for filter
            $this->subclasses = collect([
                'Visitor Visa (Subclass 600)',
                'PR - Employer Nomination Scheme (ENS)(Subclass 186)',
                'PR - Skilled Nominated Visa (Subclass 190)',
                'PR - Skilled Independent Visa (Subclass 189)',
                'Work Visa - Temporary Skill Shortage Visa (Subclass 482)',
                'Work Visa - Skilled Work Regional Visa (Australia) (Subclass 491)',
                'Student Visa (Subclass 500)',
                'Student Visa - Temporary Graduate Visa (Australia)(Subclass 485)'
            ])->sort()->values();
            
            // Calculate lead counts for statistics
            $allLeadsQuery = NewLead::query();
            $myLeadsQuery = NewLead::query();
            
            // "All Leads" - count all leads regardless of permissions
            // No filter needed for all leads count
            
            // "My Leads" - leads owned OR added by current user
            $myLeadsQuery->where(function ($query) {
                $query->where('lead_owner', user()->id)
                      ->orWhere('added_by', user()->id);
            });
            
            $this->allLeadsCount = $allLeadsQuery->count();
            $this->myLeadsCount = $myLeadsQuery->count();
        }

        return $dataTable->render('lead-list.index', $this->data);

    }

    public function addLead()
    {
        $this->addLeadPermission = user()->permission('add_lead');
        abort_403(!in_array($this->addLeadPermission, ['all', 'added']));

        // Check user roles for access control
        $userRoles = user_roles();
        $isReceptionist = in_array('receptionist', $userRoles);
        $isConsultant = in_array('consultant', $userRoles);
        $isAdmin = in_array('admin', $userRoles);

        // Check if editing existing new lead
        $leadId = request('lead_id');
        
        // Set page title based on whether we're adding or editing
        if ($leadId) {
            $this->pageTitle = 'app.editLead';
        } else {
            $this->pageTitle = 'app.addLead';
        }

        // Set custom breadcrumb
        $this->customBreadcrumb = [
            [
                'text' => __('app.menu.home'),
                'url' => route('dashboard')
            ],
            [
                'text' => __('app.leadList'),
                'url' => route('lead-list.index')
            ],
            [
                'text' => $leadId ? __('app.editLead') : __('app.addLead')
            ]
        ];

        $defaultStatus = LeadStatus::where('default', '1')->first();
        $this->columnId = request('column_id') ?: $defaultStatus->id;

        $this->leadAgents = LeadAgent::whereHas('user', function ($q) {
            $q->where('status', 'active');
        })->with('user')->get();

        $this->leadAgentArray = $this->leadAgents->pluck('user_id')->toArray();

        if ((in_array(user()->id, $this->leadAgentArray))) {
            $this->myAgentId = $this->leadAgents->filter(function ($value, $key) {
                return $value->user_id == user()->id;
            })->first()->id;
        }

        $leadContact = new Lead();
        $getCustomField = $leadContact->getCustomFieldGroupsWithFields();

        if ($getCustomField) {
            $this->fields = $getCustomField->fields;
        }

        $this->sources = LeadSource::all();
        $this->categories = LeadCategory::all();
        $this->countries = countries();
        $this->salutations = Salutation::cases();
        $this->leadPipelines = LeadPipeline::orderBy('default', 'DESC')->get();
        $this->leadStages = PipelineStage::all();
        $this->leadAgentArray = $this->leadAgents->pluck('user_id')->toArray();
        $this->products = Product::all();
        // Get employees from the same organization with Consultant role only
        $this->employees = User::withRole('consultant')
            ->join('employee_details', 'employee_details.user_id', '=', 'users.id')
            ->leftJoin('designations', 'employee_details.designation_id', '=', 'designations.id')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.company_id', 'users.name', 'users.email', 'users.created_at', 'users.image', 'designations.name as designation_name', 'users.email_notifications', 'users.mobile', 'users.country_id', 'users.status')
            ->where('users.company_id', company()->id)
            ->where('users.status', 'active')
            ->where('roles.name', 'consultant')
            ->orderBy('users.name')
            ->groupBy('users.id')
            ->get();

        // Load visa types from master datatable
        $this->visaTypes = \App\Models\NewLeadVisaType::where(function($query) {
            $query->where('company_id', company()->id)
                  ->orWhereNull('company_id');
        })->orderBy('name')->get();

        // Load visa categories from master (for Last Five Years Visa Status - Visa Granted)
        $this->visaCategories = \App\Models\NewVisaCategoryMaster::where(function($query) {
            $query->where('company_id', company()->id)
                  ->orWhereNull('company_id');
        })->orderBy('name')->get();

        // Load languages from master (for Languages Spoken)
        $this->languages = NewLanguageMaster::where(function($query) {
            $query->where('company_id', company()->id)
                  ->orWhereNull('company_id');
        })->orderBy('name')->get();

        // Load countries from master (for Country of Origin / address state/city)
        $this->countryMasters = NewCountryMaster::where(function($query) {
            $query->where('company_id', company()->id)
                  ->orWhereNull('company_id');
        })->orderBy('name')->get();

        // Load states/cities masters (for other tabs dropdowns)
        $this->stateMasters = NewStateMaster::where(function ($query) {
            $query->where('company_id', company()->id)
                ->orWhereNull('company_id');
        })->orderBy('name')->get();

        $this->cityMasters = NewCityMaster::where(function ($query) {
            $query->where('company_id', company()->id)
                ->orWhereNull('company_id');
        })->orderBy('name')->get();

        // Load lead data if editing
        $this->newLead = null;
        $this->newLeadStepStatus = null;
        
        if ($leadId) {
            try {
                $this->newLead = NewLead::with(['stepStatus', 'stepLogs'])->find($leadId);
                if (!$this->newLead) {
                    // If lead_id is provided but doesn't exist, redirect to add-lead without lead_id
                    return redirect()->route('add-lead.index');
                }
                $this->newLeadStepStatus = $this->newLead->stepStatus;
                if (!$this->newLeadStepStatus) {
                    $this->newLeadStepStatus = LeadStepStatus::getOrCreateForLead($leadId);
                }
            } catch (\Exception $e) {
                // If there's an error loading the lead, redirect to add-lead without lead_id
                return redirect()->route('add-lead.index');
            }
            
            // Removed role-based restrictions - all users can now edit all leads
        }

        return view('add-lead.index', $this->data);
    }

    /**
     * Get subclasses by visa type ID
     */
    public function getSubclassesByVisaType($visaTypeId)
    {
        $subclasses = \App\Models\NewLeadSubclass::where('visa_type_id', $visaTypeId)
            ->where(function($query) {
                $query->where('company_id', company()->id)
                      ->orWhereNull('company_id');
            })
            ->orderBy('name')
            ->get();

        $options = '<option value="">' . __('app.select') . '</option>';
        foreach ($subclasses as $subclass) {
            $options .= '<option value="' . $subclass->id . '">' . htmlspecialchars($subclass->name, ENT_QUOTES, 'UTF-8') . '</option>';
        }

        return Reply::dataOnly(['status' => 'success', 'options' => $options, 'subclasses' => $subclasses]);
    }

    /**
     * Get states by country (for address dropdowns)
     */
    public function getStatesByCountry($countryId)
    {
        $states = NewStateMaster::where('country_id', $countryId)
            ->where(function ($query) {
                $query->where('company_id', company()->id)
                    ->orWhereNull('company_id');
            })
            ->orderBy('name')
            ->get();

        $options = '<option value="">' . __('app.select') . '</option>';
        foreach ($states as $state) {
            $options .= '<option value="' . $state->id . '">' . htmlspecialchars($state->name, ENT_QUOTES, 'UTF-8') . '</option>';
        }

        return Reply::dataOnly(['status' => 'success', 'options' => $options, 'states' => $states]);
    }

    /**
     * Get cities by state (for address dropdowns)
     */
    public function getCitiesByState($stateId)
    {
        $cities = NewCityMaster::where('state_id', $stateId)
            ->where(function ($query) {
                $query->where('company_id', company()->id)
                    ->orWhereNull('company_id');
            })
            ->orderBy('name')
            ->get();

        $options = '<option value="">' . __('app.select') . '</option>';
        foreach ($cities as $city) {
            $options .= '<option value="' . $city->id . '">' . htmlspecialchars($city->name, ENT_QUOTES, 'UTF-8') . '</option>';
        }

        return Reply::dataOnly(['status' => 'success', 'options' => $options, 'cities' => $cities]);
    }

    public function leadDetails($id = null)
    {
        // Redirect to lead list if ID is not provided
        if (!$id) {
            return redirect()->route('lead-list.index');
        }
        
        // Removed role and permission restrictions - all users can now view lead details

        $this->pageTitle = 'app.leadDetails';

        // Set custom breadcrumb
        $this->customBreadcrumb = [
            [
                'text' => __('app.menu.home'),
                'url' => route('dashboard')
            ],
            [
                'text' => __('app.leadList'),
                'url' => route('lead-list.index')
            ],
            [
                'text' => __('app.leadDetails')
            ]
        ];

        // Fetch the lead data if ID is provided
        $this->lead = null;
        
        // Load visa types and subclasses for process tab
        $this->visaTypes = \App\Models\NewLeadVisaType::where(function($query) {
            $query->where('company_id', company()->id)
                  ->orWhereNull('company_id');
        })->orderBy('name')->get();
        
        $this->subclasses = \App\Models\NewLeadSubclass::with('visaType')
            ->where(function($query) {
                $query->where('company_id', company()->id)
                      ->orWhereNull('company_id');
            })
            ->orderBy('name')
            ->get();
        
        // Add to data array for view access
        $this->data['visaTypes'] = $this->visaTypes;
        $this->data['subclasses'] = $this->subclasses;
        
        try {
            $this->lead = NewLead::with(['addedBy', 'leadOwner', 'followUps.addedBy', 'followUps.lastUpdatedBy', 'fileNotes.addedBy', 'process', 'accounts.agentUser', 'accounts.addedBy', 'travelDetails', 'stepStatus'])->find($id);
            if (!$this->lead) {
                return redirect()->route('lead-list.index');
            }
            
            // Check if lead is in draft status - redirect to lead list if it is
            if ($this->lead->stepStatus && $this->lead->stepStatus->final_status == 'draft') {
                return redirect()->route('lead-list.index');
            }
            
            // Explicitly add lead to data array
            $this->data['lead'] = $this->lead;
        } catch (\Exception $e) {
            \Log::error('Error loading lead details for ID ' . $id . ': ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('lead-list.index');
        }
        
        // Removed role-based restrictions - all users can now view all lead details

        if (!request()->ajax()) {
            try {
                $this->categories = LeadCategory::get();
                $this->sources = LeadSource::get();
                $this->employees = User::allEmployees(null, 'active');
                $this->templateDocuments = \App\Models\NewLeadTemplateDocument::all();
                
                // Explicitly add to data array
                $this->data['categories'] = $this->categories;
                $this->data['sources'] = $this->sources;
                $this->data['employees'] = $this->employees;
                $this->data['templateDocuments'] = $this->templateDocuments;
            } catch (\Exception $e) {
                \Log::error('Error loading lead details data: ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                // Set defaults to prevent view errors
                $this->data['categories'] = collect([]);
                $this->data['sources'] = collect([]);
                $this->data['employees'] = collect([]);
                $this->data['templateDocuments'] = collect([]);
            }
        }

        // Load document checklists for new documents tab
        if ($id && $this->lead) {
            try {
                $this->data['documentChecklists'] = $this->getDocumentChecklists($this->lead);
                $this->data['familyDetails'] = $this->getFamilyDetails($this->lead);
                
                // Get the last future appointment for this lead
                $lastFutureAppointment = NewLeadAppointment::where('lead_id', $this->lead->id)
                    ->where('appointment_date', '>=', now())
                    ->orderBy('appointment_date', 'asc')
                    ->orderBy('start_time', 'asc')
                    ->first();
                
                $this->data['lastFutureAppointment'] = $lastFutureAppointment;
            } catch (\Exception $e) {
                \Log::error('Error loading document checklists: ' . $e->getMessage());
                $this->data['documentChecklists'] = [];
                $this->data['familyDetails'] = [];
                $this->data['lastFutureAppointment'] = null;
            }
        } else {
            $this->data['documentChecklists'] = [];
            $this->data['familyDetails'] = [];
            $this->data['lastFutureAppointment'] = null;
        }

        return view('lead-details.index', $this->data);
    }
    
    /**
     * Get family details from step data
     */
    private function getFamilyDetails($lead)
    {
        $details = [
            'main_applicant' => null,
            'father' => null,
            'mother' => null,
            'spouse' => null,
            'children' => []
        ];
        
        if (!$lead) {
            return $details;
        }
        
        // Helper to get step data
        $getStepData = function($stepData) {
            if (is_string($stepData)) {
                $decoded = json_decode($stepData, true);
                return is_array($decoded) ? $decoded : [];
            }
            return is_array($stepData) ? $stepData : [];
        };
        
        // Main Applicant from Step 1
        $step1Data = $getStepData($lead->step_1_data ?? null);
        if (!empty($step1Data)) {
            $surname = $step1Data['surname'] ?? '';
            $givenName = $step1Data['given_name'] ?? '';
            $details['main_applicant'] = [
                'name' => trim($surname . ' ' . $givenName) ?: 'Main Applicant',
                'surname' => $surname,
                'given_name' => $givenName
            ];
        }
        
        // Family details from Step 5
        $step5Data = $getStepData($lead->step_5_data ?? null);
        
        // Father
        if (!empty($step5Data['father_surname']) || !empty($step5Data['father_given_name'])) {
            $details['father'] = [
                'name' => trim(($step5Data['father_surname'] ?? '') . ' ' . ($step5Data['father_given_name'] ?? '')) ?: 'Father',
                'surname' => $step5Data['father_surname'] ?? '',
                'given_name' => $step5Data['father_given_name'] ?? ''
            ];
        }
        
        // Mother
        if (!empty($step5Data['mother_surname']) || !empty($step5Data['mother_given_name'])) {
            $details['mother'] = [
                'name' => trim(($step5Data['mother_surname'] ?? '') . ' ' . ($step5Data['mother_given_name'] ?? '')) ?: 'Mother',
                'surname' => $step5Data['mother_surname'] ?? '',
                'given_name' => $step5Data['mother_given_name'] ?? ''
            ];
        }
        
        // Spouse
        if (!empty($step5Data['spouse_surname']) || !empty($step5Data['spouse_given_name'])) {
            $details['spouse'] = [
                'name' => trim(($step5Data['spouse_surname'] ?? '') . ' ' . ($step5Data['spouse_given_name'] ?? '')) ?: 'Spouse',
                'surname' => $step5Data['spouse_surname'] ?? '',
                'given_name' => $step5Data['spouse_given_name'] ?? ''
            ];
        }
        
        // Children
        if (!empty($step5Data['children']) && is_array($step5Data['children'])) {
            foreach ($step5Data['children'] as $index => $child) {
                $childName = $child['child_name'] ?? 'Child ' . ($index + 1);
                $details['children'][] = [
                    'index' => $index + 1,
                    'name' => $childName,
                    'data' => $child
                ];
            }
        }
        
        return $details;
    }
    
    /**
     * Get document checklists merged with uploaded documents
     */
    private function getDocumentChecklists($lead)
    {
        $checklists = [
            'main_applicant' => [],
            'father' => [],
            'mother' => [],
            'spouse' => [],
            'children' => []
        ];
        
        if (!$lead) {
            return $checklists;
        }
        
        // Get master document lists (keyed by ID for lookup)
        $mainDocuments = \App\Models\NewLeadMainDocument::where(function($query) {
            $query->where('company_id', company()->id)
                  ->orWhereNull('company_id');
        })->get()->keyBy('id');
        
        $dependsDocuments = \App\Models\NewLeadDependsDocument::where(function($query) {
            $query->where('company_id', company()->id)
                  ->orWhereNull('company_id');
        })->get()->keyBy('id');
        
        // Get uploaded documents
        $leadDocument = \App\Models\NewLeadDocument::where('lead_id', $lead->id)->first();
        
        // Main Applicant Checklist - Use document_master_id as key
        if ($leadDocument && $leadDocument->main_applicant_documents) {
            // Convert old format if needed
            $mainDocs = $this->convertDocumentKeysToIds($leadDocument->main_applicant_documents, true);
            
            foreach ($mainDocs as $docId => $docData) {
                $docIdInt = (int)$docId;
                $masterDoc = $mainDocuments->get($docIdInt);
                if ($masterDoc) {
                    $checklists['main_applicant'][] = [
                        'document_master_id' => $docIdInt,
                        'name' => $docData['document_name'] ?? $masterDoc->name,
                        'key' => (string)$docIdInt,
                        'file_url' => $docData['file_url'] ?? null,
                        'status' => $docData['status'] ?? 'pending',
                        'uploaded_at' => $docData['uploaded_at'] ?? null
                    ];
                }
            }
        }
        
        // Dependents Checklists (Father, Mother, Spouse) - Use document_master_id as key
        $dependentTypes = ['father', 'mother', 'spouse'];
        foreach ($dependentTypes as $type) {
            $columnName = $type . '_documents';
            $uploadedDocs = $leadDocument && $leadDocument->$columnName 
                ? $leadDocument->$columnName 
                : [];
            
            // Convert old format if needed
            $uploadedDocs = $this->convertDocumentKeysToIds($uploadedDocs, false);
            
            foreach ($uploadedDocs as $docId => $docData) {
                $docIdInt = (int)$docId;
                $masterDoc = $dependsDocuments->get($docIdInt);
                if ($masterDoc) {
                    $checklists[$type][] = [
                        'document_master_id' => $docIdInt,
                        'name' => $docData['document_name'] ?? $masterDoc->name,
                        'key' => (string)$docIdInt,
                        'file_url' => $docData['file_url'] ?? null,
                        'status' => $docData['status'] ?? 'pending',
                        'uploaded_at' => $docData['uploaded_at'] ?? null
                    ];
                }
            }
        }
        
        // Children Checklist - Use document_master_id as key
        $childrenDocs = $leadDocument && $leadDocument->children_documents 
            ? $leadDocument->children_documents 
            : [];
        
        // Get children from family details
        $familyDetails = $this->getFamilyDetails($lead);
        foreach ($familyDetails['children'] as $child) {
            $childIndex = $child['index'];
            
            // Find child document entry
            $childDocEntry = null;
            foreach ($childrenDocs as $entry) {
                if (isset($entry['child_index']) && $entry['child_index'] == $childIndex) {
                    $childDocEntry = $entry;
                    break;
                }
            }
            
            $childDocuments = $childDocEntry && isset($childDocEntry['documents']) 
                ? $childDocEntry['documents'] 
                : [];
            
            // Convert old format if needed
            $childDocuments = $this->convertDocumentKeysToIds($childDocuments, false);
            
            $childChecklist = [];
            foreach ($childDocuments as $docId => $docData) {
                $docIdInt = (int)$docId;
                $masterDoc = $dependsDocuments->get($docIdInt);
                if ($masterDoc) {
                    $childChecklist[] = [
                        'document_master_id' => $docIdInt,
                        'name' => $docData['document_name'] ?? $masterDoc->name,
                        'key' => (string)$docIdInt,
                        'file_url' => $docData['file_url'] ?? null,
                        'status' => $docData['status'] ?? 'pending',
                        'uploaded_at' => $docData['uploaded_at'] ?? null
                    ];
                }
            }
            
            $checklists['children'][] = [
                'child_index' => $childIndex,
                'child_name' => $child['name'],
                'documents' => $childChecklist
            ];
        }
        
        return $checklists;
    }
    

    /**
     * Send template document via email to lead
     */
    public function sendTemplateDocumentEmail(Request $request, $leadId, $documentId)
    {
        try {
            $lead = NewLead::findOrFail($leadId);
            $document = \App\Models\NewLeadTemplateDocument::findOrFail($documentId);

            // Get lead email from step_1_data or client_email
            $leadEmail = null;
            if ($lead->step_1_data) {
                $step1Data = is_string($lead->step_1_data) ? json_decode($lead->step_1_data, true) : $lead->step_1_data;
                if (is_array($step1Data)) {
                    $leadEmail = $step1Data['email_address'] ?? null;
                }
            }
            
            // Fallback to client_email
            if (empty($leadEmail)) {
                $leadEmail = $lead->client_email;
            }

            if (empty($leadEmail) || !filter_var($leadEmail, FILTER_VALIDATE_EMAIL)) {
                return Reply::error(__('Lead email address is not available or invalid.'));
            }

            // Send email with attachment
            \Mail::to($leadEmail)->send(new \App\Mail\SendTemplateDocument($lead, $document));

            return Reply::success(__('Template document sent successfully to ') . $leadEmail);
        } catch (\Exception $e) {
            \Log::error('Failed to send template document email: ' . $e->getMessage());
            return Reply::error(__('Failed to send email. Please try again.'));
        }
    }

    /**
     * Send template document via WhatsApp to lead
     */
    public function sendTemplateDocumentWhatsApp(Request $request, $leadId, $documentId)
    {
        try {
            $lead = NewLead::findOrFail($leadId);
            $document = \App\Models\NewLeadTemplateDocument::findOrFail($documentId);

            // Get lead phone number from step_1_data or mobile
            $leadPhone = null;
            if ($lead->step_1_data) {
                $step1Data = is_string($lead->step_1_data) ? json_decode($lead->step_1_data, true) : $lead->step_1_data;
                if (is_array($step1Data)) {
                    $leadPhone = $step1Data['primary_phone'] ?? null;
                }
            }
            
            // Fallback to mobile
            if (empty($leadPhone)) {
                $leadPhone = $lead->mobile;
            }

            if (empty($leadPhone)) {
                return Reply::error(__('Lead phone number is not available.'));
            }

            // Remove any non-numeric characters
            $leadPhone = preg_replace('/[^0-9]/', '', $leadPhone);
            // Remove leading 0 if present
            $leadPhone = ltrim($leadPhone, '0');
            // Remove country code if present (keep only last 10 digits)
            if (strlen($leadPhone) > 10) {
                $leadPhone = substr($leadPhone, -10);
            }

            // Get lead name
            $leadName = $lead->client_name ?? 'Client';
            if ($lead->step_1_data) {
                $step1Data = is_string($lead->step_1_data) ? json_decode($lead->step_1_data, true) : $lead->step_1_data;
                if (is_array($step1Data)) {
                    $givenName = $step1Data['given_name'] ?? '';
                    $surname = $step1Data['surname'] ?? '';
                    if ($givenName || $surname) {
                        $leadName = trim($givenName . ' ' . $surname) ?: $leadName;
                    }
                }
            }

            // Get consultant details
            $consultantName = 'Our Team';
            $consultantNumber = '';
            
            if ($lead->lead_owner) {
                $assignedUser = User::find($lead->lead_owner);
                if ($assignedUser) {
                    $consultantName = $assignedUser->name ?? 'Our Team';
                    // Get consultant phone number
                    $consultantNumber = $assignedUser->mobile ?? $assignedUser->phone ?? '';
                    // Format consultant number - keep only 10 digits (no country code)
                    if ($consultantNumber) {
                        $consultantNumber = preg_replace('/[^0-9]/', '', $consultantNumber);
                        $consultantNumber = ltrim($consultantNumber, '0');
                        // Remove country code if present (keep only last 10 digits)
                        if (strlen($consultantNumber) > 10) {
                            $consultantNumber = substr($consultantNumber, -10);
                        }
                    }
                }
            }

            // Get document URL and filename
            $documentUrl = $document->file_url;
            $documentFilename = $document->file_name ?? $document->name ?? 'sample_media';

            // Fixed values as per API requirements - read from environment variables
            $apiKey = env('AISENSY_API_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjY4YzdmM2RjNmZhOGUxMDEzYzdlMDgzZSIsIm5hbWUiOiJSLlIgcGF0ZWwgIG5ldyIsImFwcE5hbWUiOiJBaVNlbnN5IiwiY2xpZW50SWQiOiI2ODcyMzU5ZGRlNjFiYjMxOTgzMzc2NDMiLCJhY3RpdmVQbGFuIjoiQkFTSUNfTU9OVEhMWSIsImlhdCI6MTc2MDM1NTc4OX0.6H8mv7r3R0ucc7APyDM1q0xew4-oBUVKqUHA38klVG4');
            $campaignName = env('AISENSY_TEMPLATE_DOCUMENT_CAMPAIGN', 'additional_details_requested1');
            $userName = env('AISENSY_USER_NAME', 'R.R patel  new');
            $source = env('AISENSY_SOURCE', 'new-landing-page form');

            // Prepare template parameters
            // Template variables: {{name}}, {{consultant_name}}, {{consultant_number}}
            $templateParams = [
                $leadName,
                $consultantName,
                $consultantNumber ?: 'N/A'
            ];

            // Prepare API request payload - matching exact Postman working format
            $payload = [
                'apiKey' => $apiKey,
                'campaignName' => $campaignName,
                'destination' => $leadPhone,
                'userName' => $userName,
                'templateParams' => $templateParams,
                'source' => $source,
                'media' => [
                    'url' => $documentUrl,
                    'filename' => $documentFilename
                ],
                'buttons' => [],
                'carouselCards' => [],
                'location' => (object)[],
                'attributes' => (object)[],
                'paramsFallbackValue' => [
                    'FirstName' => $leadName
                ]
            ];

            // Make API call to AISensy
            $client = new Client();
            $response = $client->post('https://backend.aisensy.com/campaign/t1/api/v2', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
                'timeout' => 30
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);

            if ($response->getStatusCode() === 200) {
                return Reply::success(__('Template document sent successfully via WhatsApp to ') . $leadPhone);
            } else {
                \Log::error('AISensy API error: ' . json_encode($responseBody));
                return Reply::error(__('Failed to send WhatsApp message. Please try again.'));
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errorMessage = $e->getMessage();
            try {
                $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);
                if (is_array($errorResponse) && isset($errorResponse['message'])) {
                    $errorMessage = $errorResponse['message'];
                }
            } catch (\Exception $ex) {
                // If we can't parse the error response, use the original message
            }
            \Log::error('AISensy API client error: ' . $e->getMessage());
            return Reply::error(__('Failed to send WhatsApp message: ') . $errorMessage);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            \Log::error('AISensy API request error: ' . $e->getMessage());
            return Reply::error(__('Failed to send WhatsApp message. Please check your connection and try again.'));
        } catch (\Exception $e) {
            \Log::error('Failed to send template document via WhatsApp: ' . $e->getMessage());
            return Reply::error(__('Failed to send WhatsApp message. Please try again.'));
        }
    }

    public function leadDashboard()
    {
        $this->viewLeadPermission = $viewPermission = user()->permission('view_lead');
        abort_403(!in_array($viewPermission, ['all','added','owned','both']));

        $this->pageTitle = 'app.leadDashboard';

        $now = now($this->company->timezone);
        $today = $now->copy()->startOfDay();
        $todayEnd = $now->copy()->endOfDay();
        $thisWeekStart = $now->copy()->startOfWeek();
        $thisWeekEnd = $now->copy()->endOfWeek();
        
        // Convert to date strings for database queries
        $todayDate = $today->format('Y-m-d');
        $thisWeekStartDate = $thisWeekStart->format('Y-m-d');
        $thisWeekEndDate = $thisWeekEnd->format('Y-m-d');

        // Base query for leads based on permissions
        $baseQuery = NewLead::query();
        
        if ($viewPermission == 'owned') {
            $baseQuery->where('lead_owner', user()->id);
        } elseif ($viewPermission == 'added') {
            $baseQuery->where('added_by', user()->id);
        } elseif ($viewPermission == 'both') {
            $baseQuery->where(function($q) {
                $q->where('lead_owner', user()->id)
                  ->orWhere('added_by', user()->id);
            });
        }

        // TOP SUMMARY CARDS
        $this->totalLeads = (clone $baseQuery)->count();
        $this->newLeadsToday = (clone $baseQuery)->whereBetween('created_at', [$today, $todayEnd])->count();
        $this->newLeadsThisWeek = (clone $baseQuery)->whereBetween('created_at', [$thisWeekStart, $thisWeekEnd])->count();
        $this->closedLeads = (clone $baseQuery)->where('lead_status', 'Lead Close')->count();

        // Follow-ups Today (for Consultant role)
        $userRoles = user_roles();
        $this->isConsultant = in_array('consultant', $userRoles);
        $this->isAdmin = in_array('admin', $userRoles);
        
        if ($this->isConsultant) {
            $followUpQuery = NewLeadFollowUp::where('status', 'pending')
                ->whereDate('next_follow_up_date', $todayDate);
            
            if ($viewPermission == 'owned') {
                $followUpQuery->whereHas('newLead', function($q) {
                    $q->where('lead_owner', user()->id);
                });
            } elseif ($viewPermission == 'added') {
                $followUpQuery->whereHas('newLead', function($q) {
                    $q->where('added_by', user()->id);
                });
            } elseif ($viewPermission == 'both') {
                $followUpQuery->whereHas('newLead', function($q) {
                    $q->where(function($q2) {
                        $q2->where('lead_owner', user()->id)
                           ->orWhere('added_by', user()->id);
                    });
                });
            }
            
            $this->followUpsToday = $followUpQuery->count();
        } else {
            $this->followUpsToday = 0;
        }

        // Revenue Generated (for Admin role)
        if ($this->isAdmin) {
            $revenueGenerated = NewLeadAccount::where('status', 'received')
                ->sum('total_amount');
            $this->revenueGenerated = $revenueGenerated ?? 0;
        } else {
            $this->revenueGenerated = 0;
        }

        // LEAD STATUS FUNNEL
        $funnelStages = [
            'Consultation in Progress',
            'Meeting in Progress',
            'Documentation',
            'Estimation',
            'Payment',
            'MOU',
            'File in Process',
            'File Submission',
            'Visa Process',
            'Flying Date Received',
            'Join / Move / Admissions',
            'Follow up',
            'Lead Close'
        ];

        $funnelData = [];
        $openLeadsQuery = (clone $baseQuery)->where('lead_status', 'Open Lead');
        $this->openLeadsCount = $openLeadsQuery->count();
        
        foreach ($funnelStages as $stage) {
            $count = (clone $baseQuery)->where('lead_status', $stage)->count();
            $funnelData[$stage] = $count;
        }
        
        // Ensure all stages are present even if count is 0
        foreach ($funnelStages as $stage) {
            if (!isset($funnelData[$stage])) {
                $funnelData[$stage] = 0;
            }
        }
        
        $this->funnelData = $funnelData;

        // COUNTRY / VISA TYPE ANALYTICS
        $allLeads = (clone $baseQuery)->whereNotNull('step_2_data')->get();
        
        $visaTypes = [
            'PR' => 0,
            'Student Visa' => 0,
            'Visit Visa' => 0,
            'Work Permit' => 0
        ];
        
        $countries = [
            'Australia' => 0,
            'New Zealand' => 0
        ];

        foreach ($allLeads as $lead) {
            // Handle both array (from cast) and JSON string (backward compatibility)
            $step2Data = is_array($lead->step_2_data) ? $lead->step_2_data : (json_decode($lead->step_2_data, true) ?? []);
            
            // Extract visa type - handle both numeric ID (new format) and string (old format)
            $visaTypeName = null;
            if (isset($step2Data['visa_type'])) {
                $visaTypeValue = $step2Data['visa_type'];
                
                if (is_numeric($visaTypeValue)) {
                    // New format: Look up visa type by ID
                    $visaTypeModel = NewLeadVisaType::find($visaTypeValue);
                    if ($visaTypeModel) {
                        $visaTypeName = $visaTypeModel->name;
                    }
                } else {
                    // Old format: string values - map to proper names
                    $visaTypeLower = strtolower($visaTypeValue);
                    $visaTypeMap = [
                        'pr' => 'PR',
                        'permanent residence' => 'PR',
                        'student' => 'Student Visa',
                        'student visa' => 'Student Visa',
                        'visit' => 'Visit Visa',
                        'visit visa' => 'Visit Visa',
                        'work' => 'Work Permit',
                        'work permit' => 'Work Permit',
                    ];
                    $visaTypeName = $visaTypeMap[$visaTypeLower] ?? null;
                }
                
                // Count visa type
                if ($visaTypeName && isset($visaTypes[$visaTypeName])) {
                    $visaTypes[$visaTypeName]++;
                }
            }
            
            // Extract country based on visa type
            $country = null;
            if (isset($step2Data['visa_type'])) {
                $visaTypeValue = $step2Data['visa_type'];
                $sectionId = null;
                
                // Determine section ID
                if (is_numeric($visaTypeValue)) {
                    $visaTypeModel = NewLeadVisaType::find($visaTypeValue);
                    if ($visaTypeModel) {
                        $visaTypeNameForSection = $visaTypeModel->name;
                        $sectionMap = [
                            'PR' => 'pr',
                            'Permanent Residence' => 'pr',
                            'Visit Visa' => 'visit',
                            'Work Permit' => 'work',
                            'Student Visa' => 'student',
                        ];
                        foreach($sectionMap as $key => $value) {
                            if(stripos($visaTypeNameForSection, $key) !== false) {
                                $sectionId = $value;
                                break;
                            }
                        }
                    }
                } else {
                    $sectionId = strtolower($visaTypeValue);
                }
                
                // Get country based on section
                if ($sectionId == 'pr' && isset($step2Data['pr_preferred_country'])) {
                    $country = $step2Data['pr_preferred_country'];
                } elseif ($sectionId == 'visit' && isset($step2Data['visit_preferred_country'])) {
                    $country = $step2Data['visit_preferred_country'];
                } elseif ($sectionId == 'work' && isset($step2Data['work_preferred_country'])) {
                    $country = $step2Data['work_preferred_country'];
                } elseif ($sectionId == 'student' && isset($step2Data['student_country'])) {
                    $country = $step2Data['student_country'];
                }
            }
            
            // Count country (case-insensitive matching)
            if ($country) {
                $countryNormalized = ucwords(strtolower(trim($country)));
                // Check for Australia variations
                if (stripos($country, 'australia') !== false || $countryNormalized === 'Australia') {
                    $countries['Australia']++;
                }
                // Check for New Zealand variations
                elseif (stripos($country, 'new zealand') !== false || $countryNormalized === 'New Zealand') {
                    $countries['New Zealand']++;
                }
            }
        }
        
        $this->visaTypes = $visaTypes;
        $this->countries = $countries;

        // SOURCE-WISE LEADS
        $leadSources = [
            'Facebook' => 0,
            'Google Ads' => 0,
            'Walk-in' => 0,
            'WhatsApp Inquiry' => 0,
            'Reference' => 0,
            'Website' => 0,
            'Email Marketing' => 0
        ];

        $sourceLeads = (clone $baseQuery)->select('lead_source', DB::raw('count(*) as count'))
            ->whereNotNull('lead_source')
            ->groupBy('lead_source')
            ->get();

        foreach ($sourceLeads as $sourceLead) {
            $source = $sourceLead->lead_source;
            if (isset($leadSources[$source])) {
                $leadSources[$source] = $sourceLead->count;
            }
        }
        
        $this->leadSources = $leadSources;

        // FOLLOW-UP & TASK TRACKER
        $followUpBaseQuery = NewLeadFollowUp::where('status', 'pending');
        
        if ($viewPermission == 'owned') {
            $followUpBaseQuery->whereHas('newLead', function($q) {
                $q->where('lead_owner', user()->id);
            });
        } elseif ($viewPermission == 'added') {
            $followUpBaseQuery->whereHas('newLead', function($q) {
                $q->where('added_by', user()->id);
            });
        } elseif ($viewPermission == 'both') {
            $followUpBaseQuery->whereHas('newLead', function($q) {
                $q->where(function($q2) {
                    $q2->where('lead_owner', user()->id)
                       ->orWhere('added_by', user()->id);
                });
            });
        }

        $this->todayFollowups = (clone $followUpBaseQuery)->whereDate('next_follow_up_date', $todayDate)->count();
        $this->overdueFollowups = (clone $followUpBaseQuery)->where('next_follow_up_date', '<', $now)->count();
        
        // Upcoming Meetings (next 7 days)
        $upcomingEnd = $now->copy()->addDays(7);
        $this->upcomingMeetings = (clone $followUpBaseQuery)
            ->where('follow_up_type', 'meeting')
            ->whereBetween('next_follow_up_date', [$now, $upcomingEnd])
            ->count();
        
        // Pending Calls
        $this->pendingCalls = (clone $followUpBaseQuery)
            ->where('follow_up_type', 'call')
            ->where('next_follow_up_date', '>=', $now)
            ->count();

        // REVENUE & PAYMENT ANALYTICS (Admin only)
        if ($this->isAdmin) {
            $this->totalExpectedRevenue = NewLeadAccount::sum('total_amount') ?? 0;
            $this->collectedPayments = NewLeadAccount::where('status', 'received')->sum('total_amount') ?? 0;
            $this->pendingPayments = NewLeadAccount::where('status', 'pending')->sum('total_amount') ?? 0;
            
            // Month-wise Revenue Trend (last 12 months)
            $monthlyRevenue = [];
            for ($i = 11; $i >= 0; $i--) {
                $monthStart = $now->copy()->subMonths($i)->startOfMonth();
                $monthEnd = $now->copy()->subMonths($i)->endOfMonth();
                $monthName = $monthStart->format('M Y');
                
                $monthRevenueAmount = NewLeadAccount::where('status', 'received')
                    ->whereBetween('invoice_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
                    ->sum('total_amount');
                
                $monthlyRevenue[$monthName] = round($monthRevenueAmount ?? 0, 2);
            }
            $this->monthlyRevenue = $monthlyRevenue;
        } else {
            $this->totalExpectedRevenue = 0;
            $this->collectedPayments = 0;
            $this->pendingPayments = 0;
            $this->monthlyRevenue = [];
        }

        // Ensure all variables are set for the view
        $this->data['totalLeads'] = $this->totalLeads;
        $this->data['newLeadsToday'] = $this->newLeadsToday;
        $this->data['newLeadsThisWeek'] = $this->newLeadsThisWeek;
        $this->data['closedLeads'] = $this->closedLeads;
        $this->data['followUpsToday'] = $this->followUpsToday;
        $this->data['revenueGenerated'] = $this->revenueGenerated;
        $this->data['funnelData'] = $this->funnelData;
        $this->data['openLeadsCount'] = $this->openLeadsCount;
        $this->data['visaTypes'] = $this->visaTypes;
        $this->data['countries'] = $this->countries;
        $this->data['leadSources'] = $this->leadSources;
        $this->data['todayFollowups'] = $this->todayFollowups;
        $this->data['overdueFollowups'] = $this->overdueFollowups;
        $this->data['upcomingMeetings'] = $this->upcomingMeetings;
        $this->data['pendingCalls'] = $this->pendingCalls;
        $this->data['isAdmin'] = $this->isAdmin;
        $this->data['isConsultant'] = $this->isConsultant;
        $this->data['totalExpectedRevenue'] = $this->totalExpectedRevenue ?? 0;
        $this->data['collectedPayments'] = $this->collectedPayments ?? 0;
        $this->data['pendingPayments'] = $this->pendingPayments ?? 0;
        $this->data['monthlyRevenue'] = $this->monthlyRevenue ?? [];

        return view('lead-dashboard.index', $this->data);
    }

    public function show($id)
    {
        $this->leadContact = Lead::findOrFail($id)->withCustomFields();

        $this->viewPermission = user()->permission('view_lead');

        abort_403(!in_array($this->viewPermission, ['all','added','owned','both']));

        $this->pageTitle = $this->leadContact->client_name_salutation;

        $this->categories = LeadCategory::all();

        $this->leadFormFields = LeadCustomForm::with('customField')->where('status', 'active')->where('custom_fields_id', '!=', 'null')->get();

        $this->leadId = $id;

        $getCustomFieldGroupsWithFields = $this->leadContact->getCustomFieldGroupsWithFields();
        if ($getCustomFieldGroupsWithFields) {
            $this->fields = $getCustomFieldGroupsWithFields->fields;
        }

        $this->editLeadPermission = user()->permission('edit_lead');
        $this->deleteLeadPermission = user()->permission('delete_lead');

        $tab = request('tab');

        switch ($tab) {
        case 'deal':
            return $this->deals();
        case 'notes':
            return $this->notes();
        default:
            $this->view = 'lead-contact.ajax.profile';
            break;
        }

        if (request()->ajax()) {
            return $this->returnAjax($this->view);
        }

        $this->activeTab = $tab ?: 'profile';

        return view('lead-contact.show', $this->data);

    }

    public function notes()
    {
        $dataTable = new LeadNotesDataTable();
        $viewPermission = user()->permission('view_deals');

        abort_403(!($viewPermission == 'all' || $viewPermission == 'added' || $viewPermission == 'both'));

        $tab = request('tab');
        $this->activeTab = $tab ?: 'profile';

        $this->view = 'lead-contact.ajax.notes';

        return $dataTable->render('lead-contact.show', $this->data);
    }

    public function deals()
    {
        $viewPermission = user()->permission('view_deals');

        abort_403(!in_array($viewPermission, ['all', 'added', 'both', 'owned']));

        $tab = request('tab');
        $this->pipelines = LeadPipeline::all();

        $defaultPipeline = $this->pipelines->filter(function ($value, $key) {
            return $value->default == 1;
        })->first();

        $this->stages = PipelineStage::where('lead_pipeline_id', $defaultPipeline->id)->get();

        $this->activeTab = $tab ?: 'profile';
        $this->view = 'lead-contact.ajax.deal';
        $dataTable = new DealsDataTable();

        return $dataTable->render('lead-contact.show', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->pageTitle = __('modules.leadContact.createTitle');

        $this->addPermission = user()->permission('add_lead');
        abort_403(!in_array($this->addPermission, ['all', 'added']));

        $this->employees = User::allEmployees(null, true);

        $defaultStatus = LeadStatus::where('default', '1')->first();
        $this->columnId = request('column_id') ?: $defaultStatus->id;

        $this->leadAgents = LeadAgent::whereHas('user', function ($q) {
            $q->where('status', 'active');
        })->with('user')->get();

        $this->leadAgentArray = $this->leadAgents->pluck('user_id')->toArray();


        if ((in_array(user()->id, $this->leadAgentArray))) {
            $this->myAgentId = $this->leadAgents->filter(function ($value, $key) {
                return $value->user_id == user()->id;
            })->first()->id;
        }

        $leadContact = new Lead();

        $getCustomField = $leadContact->getCustomFieldGroupsWithFields();

        if ($getCustomField) {
            $this->fields = $getCustomField->fields;
        }

        $this->sources = LeadSource::all();
        $this->categories = LeadCategory::all();
        $this->countries = countries();
        $this->salutations = Salutation::cases();

        // To create deal from lead

        $this->leadPipelines = LeadPipeline::orderBy('default', 'DESC')->get();
        $this->leadStages = PipelineStage::all();
        $this->leadAgentArray = $this->leadAgents->pluck('user_id')->toArray();
        $this->products = Product::all();


        $this->view = 'lead-contact.ajax.create';

        if (request()->ajax()) {
            return $this->returnAjax($this->view);
        }

        return view('lead-contact.create', $this->data);

    }

    /**
     * @param StoreRequest $request
     * @return array|void
     * @throws \Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException
     */
    public function store(StoreRequest $request)
    {
        $this->addPermission = user()->permission('add_lead');

        abort_403(!in_array($this->addPermission, ['all', 'added']));

        $existingUser = User::select('id')
            ->whereHas('roles', function ($q) {
                $q->where('name', 'client');
            })->where('company_id', company()->id)
            ->where('email', $request->client_email)
            ->whereNotNull('email')
            ->first();

        $leadContact = new Lead();
        $leadContact->company_id = company()->id;
        $leadContact->salutation = $request->salutation;
        $leadContact->client_name = $request->client_name;
        $leadContact->client_email = $request->client_email;
        $leadContact->note = trim_editor($request->note);
        $leadContact->source_id = $request->source_id;
        $leadContact->client_id = $existingUser?->id;
        $leadContact->lead_owner = $request->lead_owner;
        $leadContact->company_name = $request->company_name;
        $leadContact->website = $request->website;
        $leadContact->address = $request->address;
        $leadContact->cell = $request->cell;
        $leadContact->office = $request->office;
        $leadContact->city = $request->city;
        $leadContact->state = $request->state;
        $leadContact->country = $request->country;
        $leadContact->postal_code = $request->postal_code;
        $leadContact->mobile = $request->mobile;

        if ($request->has('create_deal') && $request->create_deal == 'on') {
            Session::put('create_deal_with_lead', true);
            Session::put('deal_name', $request->name);
        }

        $leadContact->save();

        if ($request->has('create_deal') && $request->create_deal == 'on') {
            $this->storeDeal($request, $leadContact);
        }

        // To add custom fields data
        if ($request->custom_fields_data) {
            $leadContact->updateCustomFieldData($request->custom_fields_data);
        }

        // Log search
        $this->logSearchEntry($leadContact->id, $leadContact->client_name, 'lead-contact.show', 'lead');

        if ($leadContact->client_email) {
            $this->logSearchEntry($leadContact->id, $leadContact->client_name, 'lead-contact.show', 'lead');
        }

        $redirectUrl = urldecode($request->redirect_url);

        if ($request->add_more == 'true') {
            $html = $this->create();

            return Reply::successWithData(__('messages.recordSaved'), ['html' => $html, 'add_more' => true]);
        }

        if ($redirectUrl == '') {
            $redirectUrl = route('lead-contact.index');
        }

        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->leadContact = Lead::with('leadSource', 'category')->findOrFail($id)->withCustomFields();

        $this->editPermission = user()->permission('edit_lead');

        abort_403(!($this->editPermission == 'all'
            || ($this->editPermission == 'added' && $this->leadContact->added_by == user()->id)
            || ($this->editPermission == 'owned' && $this->leadContact->lead_owner == user()->id)
            || ($this->editPermission == 'both' && $this->leadContact->added_by == user()->id) || user()->id == $this->leadContact->lead_owner)
        );

        $this->leadAgents = LeadAgent::with('user')->whereHas('user', function ($q) {
            $q->where('status', 'active');
        })->get();

        $getCustomFieldGroupsWithFields = $this->leadContact->getCustomFieldGroupsWithFields();
        $this->employees = User::allEmployees();

        $activeEmployees = $this->employees->filter(function ($employee) {
            return $employee->status !== 'deactive';
        });

        $selectedEmployee = $this->employees->firstWhere('id', $this->leadContact->lead_owner);

        if ($selectedEmployee && $selectedEmployee->status === 'deactive') {
            $this->employees = $activeEmployees->push($selectedEmployee);
        } else {
            $this->employees = $activeEmployees;
        }

        if ($getCustomFieldGroupsWithFields) {
            $this->fields = $getCustomFieldGroupsWithFields->fields;
        }

        $this->sources = LeadSource::all();
        $this->categories = LeadCategory::all();
        $this->countries = countries();

        $this->pageTitle = __('modules.leadContact.updateTitle');
        $this->salutations = Salutation::cases();

        if (request()->ajax()) {
            $html = view('lead-contact.ajax.edit', $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'lead-contact.ajax.edit';

        return view('lead-contact.create', $this->data);

    }

    /**
     * @param UpdateRequest $request
     * @param int $id
     * @return array|void
     * @throws \Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException
     */
    public function update(UpdateRequest $request, $id)
    {
        $leadContact = Lead::findOrFail($id);
        $this->editPermission = user()->permission('edit_lead');

        abort_403(!($this->editPermission == 'all'
            || ($this->editPermission == 'added' && $leadContact->added_by == user()->id)
            || ($this->editPermission == 'owned' && $leadContact->lead_owner == user()->id)
            || ($this->editPermission == 'both' && $leadContact->added_by == user()->id) || user()->id == $leadContact->lead_owner)
        );

        $leadContact->salutation = $request->salutation;
        $leadContact->client_name = $request->client_name;
        $leadContact->client_email = $request->client_email;
        $leadContact->note = trim_editor($request->note);
        $leadContact->source_id = $request->source_id;
        $leadContact->lead_owner = $request->lead_owner;
        $leadContact->category_id = $request->category_id;
        $leadContact->company_name = $request->company_name;
        $leadContact->website = $request->website;
        $leadContact->address = $request->address;
        $leadContact->cell = $request->cell;
        $leadContact->office = $request->office;
        $leadContact->city = $request->city;
        $leadContact->state = $request->state;
        $leadContact->country = $request->country;
        $leadContact->postal_code = $request->postal_code;
        $leadContact->mobile = $request->mobile;
        $leadContact->save();

        // To add custom fields data
        if ($request->custom_fields_data) {
            $leadContact->updateCustomFieldData($request->custom_fields_data);
        }

        return Reply::successWithData(__('messages.updateSuccess'), ['redirectUrl' => route('lead-contact.index')]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $leadContact = Lead::findOrFail($id);
        $this->deletePermission = user()->permission('delete_lead');

        abort_403(!($this->deletePermission == 'all'
            || ($this->deletePermission == 'added' && $leadContact->added_by == user()->id)
            || ($this->deletePermission == 'owned' && $leadContact->lead_owner == user()->id)
            || ($this->deletePermission == 'both' && $leadContact->added_by == user()->id) || user()->id == $leadContact->lead_owner)
        );

        Lead::destroy($id);

        return Reply::success(__('messages.deleteSuccess'));

    }

    public function applyQuickAction(Request $request)
    {
        Lead::whereIn('id', explode(',', $request->row_ids))->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }

    /**
     * Remove the specified new lead from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroyNewLead($id)
    {
        $newLead = NewLead::findOrFail($id);
        
        // Delete action available for all roles
        NewLead::destroy($id);

        return Reply::success(__('messages.deleteSuccess'));
    }

    /**
     * Apply quick action on new leads
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function applyQuickActionNewLeads(Request $request)
    {
        NewLead::whereIn('id', explode(',', $request->row_ids))->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }

    /**
     * Get updated lead counts for statistics
     *
     * @return \Illuminate\Http\Response
     */
    public function getLeadCounts()
    {
        // Calculate lead counts for statistics
        $allLeadsQuery = NewLead::query();
        $myLeadsQuery = NewLead::query();
        
        // "All Leads" - count all leads regardless of permissions
        // No filter needed for all leads count
        
        // "My Leads" - leads owned OR added by current user
        $myLeadsQuery->where(function ($query) {
            $query->where('lead_owner', user()->id)
                  ->orWhere('added_by', user()->id);
        });
        
        $allLeadsCount = $allLeadsQuery->count();
        $myLeadsCount = $myLeadsQuery->count();

        return Reply::dataOnly([
            'status' => 'success',
            'allLeadsCount' => $allLeadsCount,
            'myLeadsCount' => $myLeadsCount
        ]);
    }

    /**
     * Update lead priority
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateLeadPriority(Request $request)
    {
        $lead = NewLead::findOrFail($request->lead_id);
        $this->editPermission = user()->permission('edit_lead');

        abort_403(!($this->editPermission == 'all'
            || ($this->editPermission == 'added' && $lead->added_by == user()->id)
            || ($this->editPermission == 'owned' && $lead->lead_owner == user()->id)
            || ($this->editPermission == 'both' && ($lead->added_by == user()->id || $lead->lead_owner == user()->id))
        ));

        $lead->priority = $request->priority;
        $lead->save();

        return Reply::success(__('messages.updateSuccess'));
    }

    /**
     * Update lead status
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateLeadStatus(Request $request)
    {
        $lead = NewLead::findOrFail($request->lead_id);
        $this->editPermission = user()->permission('edit_lead');

        abort_403(!($this->editPermission == 'all'
            || ($this->editPermission == 'added' && $lead->added_by == user()->id)
            || ($this->editPermission == 'owned' && $lead->lead_owner == user()->id)
            || ($this->editPermission == 'both' && ($lead->added_by == user()->id || $lead->lead_owner == user()->id))
        ));

        $oldStatus = $lead->lead_status;
        $newStatus = $request->new_value ?? $request->status;
        $remark = $request->remark ?? null;

        // Update lead status
        $lead->lead_status = $newStatus;
        $lead->save();

        // Log the status change
        if ($oldStatus != $newStatus) {
            \App\Models\LeadStatusChangeLog::create([
                'lead_id' => $lead->id,
                'change_type' => 'status',
                'old_value' => $oldStatus,
                'new_value' => $newStatus,
                'remark' => $remark,
                'changed_by' => user()->id,
            ]);
        }

        return Reply::success(__('messages.updateSuccess'));
    }

    /**
     * Update lead quality
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateLeadQuality(Request $request)
    {
        $lead = NewLead::findOrFail($request->lead_id);
        $this->editPermission = user()->permission('edit_lead');

        abort_403(!($this->editPermission == 'all'
            || ($this->editPermission == 'added' && $lead->added_by == user()->id)
            || ($this->editPermission == 'owned' && $lead->lead_owner == user()->id)
            || ($this->editPermission == 'both' && ($lead->added_by == user()->id || $lead->lead_owner == user()->id))
        ));

        $oldQuality = $lead->lead_quality;
        $newQuality = $request->new_value ?? $request->quality;
        $remark = $request->remark ?? null;

        // Update lead quality
        $lead->lead_quality = $newQuality;
        $lead->save();

        // Log the quality change
        if ($oldQuality != $newQuality) {
            \App\Models\LeadStatusChangeLog::create([
                'lead_id' => $lead->id,
                'change_type' => 'quality',
                'old_value' => $oldQuality,
                'new_value' => $newQuality,
                'remark' => $remark,
                'changed_by' => user()->id,
            ]);
        }

        return Reply::success(__('messages.updateSuccess'));
    }

    /**
     * Get status activity for a lead
     *
     * @param int $leadId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatusActivity($leadId)
    {
        $lead = NewLead::findOrFail($leadId);
        
        // Check permission
        $this->viewLeadPermission = user()->permission('view_lead');
        abort_403(!in_array($this->viewLeadPermission, ['all', 'added', 'owned', 'both']));
        
        $statusLogs = LeadStatusChangeLog::where('lead_id', $leadId)
            ->where('change_type', 'status')
            ->with('changedBy')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'old_value' => $log->old_value,
                    'new_value' => $log->new_value,
                    'remark' => $log->remark,
                    'created_at' => $log->created_at->format(company()->date_format . ' ' . company()->time_format),
                    'created_at_iso' => $log->created_at->toIso8601String(),
                    'changed_by_user' => $log->changedBy ? [
                        'id' => $log->changedBy->id,
                        'name' => $log->changedBy->name,
                        'image_url' => $log->changedBy->image_url
                    ] : null
                ];
            });
        
        return Reply::dataOnly(['status' => 'success', 'data' => $statusLogs]);
    }

    /**
     * Get quality activity for a lead
     *
     * @param int $leadId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQualityActivity($leadId)
    {
        $lead = NewLead::findOrFail($leadId);
        
        // Check permission
        $this->viewLeadPermission = user()->permission('view_lead');
        abort_403(!in_array($this->viewLeadPermission, ['all', 'added', 'owned', 'both']));
        
        $qualityLogs = LeadStatusChangeLog::where('lead_id', $leadId)
            ->where('change_type', 'quality')
            ->with('changedBy')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'old_value' => $log->old_value,
                    'new_value' => $log->new_value,
                    'remark' => $log->remark,
                    'created_at' => $log->created_at->format(company()->date_format . ' ' . company()->time_format),
                    'created_at_iso' => $log->created_at->toIso8601String(),
                    'changed_by_user' => $log->changedBy ? [
                        'id' => $log->changedBy->id,
                        'name' => $log->changedBy->name,
                        'image_url' => $log->changedBy->image_url
                    ] : null
                ];
            });
        
        return Reply::dataOnly(['status' => 'success', 'data' => $qualityLogs]);
    }

    /**
     * Get employees for reassign dropdown - only Consultant role
     */
    public function getEmployeesForReassign()
    {
        // Get only Consultant role employees
        $employees = User::withRole('consultant')
            ->join('employee_details', 'employee_details.user_id', '=', 'users.id')
            ->leftJoin('designations', 'employee_details.designation_id', '=', 'designations.id')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.company_id', 'users.name', 'users.email', 'users.created_at', 'users.image', 'designations.name as designation_name', 'users.email_notifications', 'users.mobile', 'users.country_id', 'users.status')
            ->where('users.company_id', company()->id)
            ->where('users.status', 'active')
            ->where('roles.name', 'consultant')
            ->orderBy('users.name')
            ->groupBy('users.id')
            ->get();
        
        $employeeData = [];
        foreach ($employees as $employee) {
            $employeeData[] = [
                'id' => $employee->id,
                'name' => $employee->name,
                'image_url' => $employee->image_url,
            ];
        }
        
        return Reply::dataOnly(['status' => 'success', 'employees' => $employeeData]);
    }

    /**
     * Reassign lead to another employee
     */
    public function reassignLead(Request $request)
    {
        $lead = NewLead::findOrFail($request->lead_id);
        
        // Check permissions: admins can always reassign, or users with edit permission can assign unassigned leads
        $userRoles = user_roles();
        $isAdmin = in_array('admin', $userRoles);
        $isUnassigned = is_null($lead->lead_owner);
        
        if (!$isAdmin) {
            // For unassigned leads, check edit permission similar to moveToLead
            if ($isUnassigned) {
                $this->editPermission = user()->permission('edit_lead');
                abort_403(!($this->editPermission == 'all'
                    || ($this->editPermission == 'added' && $lead->added_by == user()->id)
                    || ($this->editPermission == 'owned' && $lead->lead_owner == user()->id)
                    || ($this->editPermission == 'both' && ($lead->added_by == user()->id || $lead->lead_owner == user()->id))
                ));
            } else {
                // For assigned leads, only admins can reassign
                abort_403(true);
            }
        }
        
        // Validate employee
        $newEmployee = User::findOrFail($request->employee_id);
        
        // Store old owner for email notification
        $oldOwnerId = $lead->lead_owner;
        
        // Update lead owner
        $lead->lead_owner = $request->employee_id;
        $lead->last_updated_by = user()->id;
        $lead->save();
        
        // Get or create step status
        $stepStatus = LeadStepStatus::getOrCreateForLead($lead->id);
        
        // Set final status to complete
        $stepStatus->final_status = 'complete';
        $stepStatus->save();
        
        // Send email notification to new assigned employee
        try {
            if ($newEmployee->email) {
                // Reload lead with company relationship for email template
                $lead->refresh();
                $lead->load('company');
                Mail::to($newEmployee->email)->send(new \App\Mail\LeadReassignedNotification($lead, $newEmployee));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send lead reassigned notification email: ' . $e->getMessage());
        }
        
        return Reply::success(__('messages.updateSuccess'));
    }

    public function importLead()
    {
        $this->pageTitle = __('app.importExcel') . ' ' . __('app.menu.lead');

        $this->addPermission = user()->permission('add_lead');
        abort_403(!in_array($this->addPermission, ['all', 'added']));

        if (request()->ajax()) {
            $html = view('leads.ajax.import', $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'leads.ajax.import';

        return view('leads.create', $this->data);
    }

    public function importStore(ImportRequest $request)
    {
        $rvalue = $this->importFileProcess($request, LeadImport::class);

        if($rvalue == 'abort'){
            return Reply::error(__('messages.abortAction'));
        }
        
        $view = view('leads.ajax.import_progress', $this->data)->render();

        return Reply::successWithData(__('messages.importUploadSuccess'), ['view' => $view]);
    }

    public function importProcess(ImportProcessRequest $request)
    {
        $batch = $this->importJobProcess($request, LeadImport::class, ImportLeadJob::class);

        return Reply::successWithData(__('messages.importProcessStart'), ['batch' => $batch]);
    }

    public function destroySession(){

        if (session()->has('is_imported')) {
            session()->forget('is_imported');
        }

        if (session()->has('leads')) {
            session()->forget('leads');
        }

        if (session()->has('leads_count')) {
            session()->forget('leads_count');
        }

        if(session()->has('total_leads')) {
            session()->forget('total_leads');
        }

        if(session()->has('create_deal_with_lead')) {
            session()->forget('create_deal_with_lead');
        }

        if(session()->has('deal_name')) {
            session()->forget('deal_name');
        }

        if(session()->has('duplicate_leads')) {
            session()->forget('duplicate_leads');
        }
    }

    public function storeDeal($request, $leadContact)
    {
        $this->addPermission = user()->permission('add_deals');
        abort_403(!in_array($this->addPermission, ['all', 'added']));
        $agentId = null;

        if (!is_null($request->agent_id)) {
            $leadAgent = LeadAgent::where('user_id', $request->agent_id)->where('lead_category_id', $request->category_id)->first();
            $agentId = isset($leadAgent) ? $leadAgent->id : null;
        }

        $deal = new Deal();
        $deal->name = $request->name;
        $deal->lead_id = $leadContact->id;
        $deal->next_follow_up = 'yes';
        $deal->category_id = $request->category_id;
        $deal->deal_watcher = $request->deal_watcher;
        $deal->lead_pipeline_id = $request->pipeline;
        $deal->pipeline_stage_id = $request->stage_id;
        $deal->agent_id = $agentId;
        $deal->close_date = companyToYmd($request->close_date);
        $deal->value = ($request->value) ?: 0;
        $deal->currency_id = $this->company->currency_id;
        $deal->save();

        if (!is_null($request->product_id)) {

            $products = $request->product_id;

            foreach ($products as $product) {
                $leadProduct = new LeadProduct();
                $leadProduct->deal_id = $deal->id;
                $leadProduct->product_id = $product;
                $leadProduct->save();
            }
        }
        }

    /**
     * Validate step data based on step number
     */
    private function validateStepData(Request $request, $stepNumber)
    {
        $rules = [];
        $messages = [];

        switch ($stepNumber) {
            case 1:
                // Step 1 - Personal Details
                // Only mandatory fields: Surname, Given Name, Primary Phone No, and Email
                $rules = [
                    'surname' => 'required|string|max:255',
                    'given_name' => 'required|string|max:255',
                    'primary_phone' => 'required|string|regex:/^[0-9]{10}$/',
                    'email_address' => 'required|email|max:255',
                ];
                
                // All other fields are optional - validate format only if provided
                $rules['gender'] = 'nullable|string|in:Male,Female,Other';
                $rules['marital_status'] = 'nullable|string';
                $rules['date_of_birth'] = 'nullable|date';
                $rules['country_of_origin'] = 'nullable|string|max:255';
                $rules['lead_source'] = 'nullable|string';
                $rules['lead_assign_to'] = 'nullable|integer|exists:users,id';
                $rules['home_address'] = 'nullable|string|max:500';
                $rules['home_city'] = 'nullable|string|max:255';
                $rules['home_state'] = 'nullable|string|max:255';
                $rules['home_pin_code'] = 'nullable|string|max:20';
                $rules['mailing_address'] = 'nullable|string|max:500';
                $rules['mailing_city'] = 'nullable|string|max:255';
                $rules['mailing_state'] = 'nullable|string|max:255';
                $rules['mailing_pin_code'] = 'nullable|string|max:20';
                
                // Optional phone fields validation (format only, not required)
                if ($request->has('secondary_phone') && $request->secondary_phone) {
                    $rules['secondary_phone'] = 'nullable|string|regex:/^[0-9]{10}$/';
                }
                if ($request->has('work_phone') && $request->work_phone) {
                    $rules['work_phone'] = 'nullable|string|regex:/^[0-9]{10}$/';
                }
                if ($request->has('other_phone') && $request->other_phone) {
                    $rules['other_phone'] = 'nullable|string|regex:/^[0-9]{10}$/';
                }
                if ($request->has('mobile') && $request->mobile) {
                    $rules['mobile'] = 'nullable|string|regex:/^[0-9]{10}$/';
                }
                
                // Optional email validation (format only, not required)
                if ($request->has('other_email') && $request->other_email) {
                    $rules['other_email'] = 'nullable|email|max:255';
                }
                
                // Upload resume is optional
                $rules['upload_resume'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
                
                $messages = [
                    'surname.required' => __('validation.required', ['attribute' => __('app.surname')]),
                    'given_name.required' => __('validation.required', ['attribute' => __('app.givenName')]),
                    'primary_phone.required' => __('validation.required', ['attribute' => __('app.primaryPhoneNo')]),
                    'primary_phone.regex' => __('validation.regex', ['attribute' => __('app.primaryPhoneNo')]),
                    'email_address.required' => __('validation.required', ['attribute' => __('modules.lead.email')]),
                    'email_address.email' => __('validation.email', ['attribute' => __('modules.lead.email')]),
                    'upload_resume.file' => 'Upload Resume must be a valid file.',
                    'upload_resume.mimes' => 'Upload Resume must be a file of type: pdf, jpg, jpeg, png.',
                    'upload_resume.max' => 'Upload Resume may not be greater than 5MB.',
                ];
                break;

            case 2:
                // Step 2 - Client Preference
                // Accept visa type ID (numeric) or old string values (for backward compatibility)
                $visaTypeId = $request->visa_type;
                $visaType = null;
                $sectionId = null;
                
                // Check if it's a numeric ID (new format) or string (old format)
                if (is_numeric($visaTypeId)) {
                    // New format: Look up visa type by ID
                    $visaTypeModel = \App\Models\NewLeadVisaType::find($visaTypeId);
                    if ($visaTypeModel) {
                        $visaType = $visaTypeModel->name;
                        // Map visa type name to section identifier
                        $sectionMap = [
                            'PR' => 'pr',
                            'Permanent Residence' => 'pr',
                            'Visit Visa' => 'visit',
                            'Work Permit' => 'work',
                            'Student Visa' => 'student',
                        ];
                        $sectionId = strtolower(str_replace(' ', '_', $visaType));
                        // Try to find a match in the map
                        foreach($sectionMap as $key => $value) {
                            if(stripos($visaType, $key) !== false) {
                                $sectionId = $value;
                                break;
                            }
                        }
                    }
                } else {
                    // Old format: string values like 'pr', 'visit', etc.
                    $visaType = strtolower($visaTypeId);
                    $sectionId = $visaType;
                }
                
                // All fields are optional - no validation required
                // Validation rules (optional)
                if (is_numeric($visaTypeId)) {
                    // New format: validate visa type ID exists if provided
                    $rules = [
                        'visa_type' => 'nullable|numeric|exists:new_lead_visa_type,id',
                    ];
                } else {
                    // Old format: validate string values if provided
                    $rules = [
                        'visa_type' => 'nullable|string|in:pr,visit,work,student,PR,Visit,Work,Student',
                    ];
                }
                
                // All visa-specific fields are optional
                $rules['skill_assessment_letter'] = 'nullable|string';
                $rules['pr_assessment_letter_file'] = 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240';
                $rules['pr_family'] = 'nullable|string';
                $rules['purpose_of_visit'] = 'nullable|string|max:500';
                $rules['visit_family'] = 'nullable|string';
                $rules['preferred_designation'] = 'nullable|string|max:255';
                $rules['term_intake'] = 'nullable|string';
                
                $messages = [];
                break;

            case 3:
                // Step 3 - Passport Details
                // All passport fields are optional
                $rules = [
                    'passport_number' => 'nullable|string|max:255',
                    'issuing_country' => 'nullable|string|max:255',
                    'city_where_issued' => 'nullable|string|max:255',
                    'issuance_date' => 'nullable|date',
                    'expiration_date' => 'nullable|date',
                    'passport_file_upload' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ];
                
                // Validate expiration_date is after issuance_date only if both are provided
                if ($request->issuance_date && $request->expiration_date) {
                    $rules['expiration_date'] = 'nullable|date|after:issuance_date';
                }
                break;

            case 4:
                // Step 4 - Relative Contact Information
                // No required fields - all fields are optional
                break;

            case 5:
                // Step 5 - Family Information
                // All fields are optional - no validation required
                $rules = [
                    'father_surname' => 'nullable|string|max:255',
                    'father_given_name' => 'nullable|string|max:255',
                    'father_date_of_birth' => 'nullable|date',
                    'father_occupation' => 'nullable|string|max:255',
                    'father_have_passport' => 'nullable|string|in:Yes,No',
                    'mother_surname' => 'nullable|string|max:255',
                    'mother_given_name' => 'nullable|string|max:255',
                    'mother_date_of_birth' => 'nullable|date',
                    'mother_occupation' => 'nullable|string|max:255',
                    'mother_have_passport' => 'nullable|string|in:Yes,No',
                    'father_passport_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'mother_passport_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'spouse_passport_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ];
                break;

            case 6:
                // Step 6 - Education
                // All fields are optional - no validation required
                $rules = [
                    'tenth_passing_year' => 'nullable|integer|min:1950|max:' . date('Y'),
                    'tenth_percentage' => 'nullable|numeric|min:0|max:100',
                    'tenth_result_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'tenth_board_name' => 'nullable|string|max:255',
                    'tenth_trial' => 'nullable|string|max:255',
                ];
                break;

            case 7:
                // Step 7 - Professional Experience
                // No required fields based on form structure - all fields are optional
                break;

            case 8:
                // Step 8 - Property Details
                // All fields are optional - no validation required
                $rules = [
                    'property_home' => 'nullable|numeric|min:0',
                    'property_land' => 'nullable|numeric|min:0',
                    'property_plot' => 'nullable|numeric|min:0',
                    'property_commercials' => 'nullable|numeric|min:0',
                    'property_other' => 'nullable|numeric|min:0',
                    'property_shop' => 'nullable|numeric|min:0',
                    'property_gold' => 'nullable|numeric|min:0',
                    'property_silver' => 'nullable|numeric|min:0',
                ];
                break;

            case 9:
                // Step 9 - Financial Status
                // No required fields based on form structure - all income fields are optional
                // But validate file uploads if provided
                if ($request->hasFile('father_income_document_file')) {
                    $rules['father_income_document_file'] = 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240';
                }
                if ($request->hasFile('mother_income_document_file')) {
                    $rules['mother_income_document_file'] = 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240';
                }
                if ($request->hasFile('candidate_income_document_file')) {
                    $rules['candidate_income_document_file'] = 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240';
                }
                if ($request->hasFile('spouse_income_document_file')) {
                    $rules['spouse_income_document_file'] = 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240';
                }
                break;
        }

        // Validate phone numbers if provided (only if not already in rules)
        $phoneFields = ['secondary_phone', 'work_phone', 'other_phone', 'mobile'];
        foreach ($phoneFields as $field) {
            if ($request->has($field) && $request->$field && !isset($rules[$field])) {
                $rules[$field] = 'nullable|string|regex:/^[0-9]{10}$/';
            }
        }

        // Validate email addresses if provided (only if not already in rules)
        $emailFields = ['other_email'];
        foreach ($emailFields as $field) {
            if ($request->has($field) && $request->$field && !isset($rules[$field])) {
                $rules[$field] = 'nullable|email|max:255';
            }
        }

        if (empty($rules)) {
            return true; // No validation needed for this step
        }

        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Reply::error($validator->errors()->first());
        }

        return true;
    }

    /**
     * Save step data for a lead
     */
    public function saveStep(Request $request, $stepNumber)
    {
        try {
            $this->addPermission = user()->permission('add_lead');
            abort_403(!in_array($this->addPermission, ['all', 'added']));

            if ($stepNumber < 1 || $stepNumber > 9) {
                return Reply::error(__('app.invalidStepNumber'));
            }

            // Validate step data
            $validationResult = $this->validateStepData($request, $stepNumber);
            if ($validationResult !== true) {
                return $validationResult;
            }

            $leadId = $request->lead_id;
            $isNewLead = false;

            // Check for duplicate leads (only for step 1 and new leads)
            if ($stepNumber == 1 && !$leadId) {
                $primaryPhone = $request->primary_phone ?? $request->mobile ?? null;
                $emailAddress = $request->email_address ?? $request->email ?? null;
                
                $duplicateLead = null;
                $duplicateReason = '';
                
                // Check for duplicate by primary phone
                if ($primaryPhone) {
                    $duplicateByPhone = NewLead::where('company_id', company()->id)
                        ->where(function($query) use ($primaryPhone) {
                            $query->where('mobile', $primaryPhone)
                                  ->orWhere('step_1_data->primary_phone', $primaryPhone);
                        })
                        ->first();
                    
                    if ($duplicateByPhone) {
                        $duplicateLead = $duplicateByPhone;
                        $duplicateReason = 'Primary Phone No';
                    }
                }
                
                // Check for duplicate by email (if not already found by phone)
                if (!$duplicateLead && $emailAddress) {
                    $duplicateByEmail = NewLead::where('company_id', company()->id)
                        ->where(function($query) use ($emailAddress) {
                            $query->where('client_email', $emailAddress)
                                  ->orWhere('step_1_data->email_address', $emailAddress);
                        })
                        ->first();
                    
                    if ($duplicateByEmail) {
                        $duplicateLead = $duplicateByEmail;
                        $duplicateReason = 'Email';
                    }
                }
                
                // If duplicate found, return error with duplicate lead information
                if ($duplicateLead) {
                    $leadNumber = 'LEAD-' . str_pad($duplicateLead->id, 4, '0', STR_PAD_LEFT);
                    $viewLeadUrl = route('lead-details.index', $duplicateLead->id);
                    
                    return Reply::error(__('messages.duplicateLead'), null, [
                        'duplicate' => true,
                        'duplicate_reason' => $duplicateReason,
                        'duplicate_lead_id' => $duplicateLead->id,
                        'duplicate_lead_number' => $leadNumber,
                        'view_lead_url' => $viewLeadUrl,
                    ]);
                }
            }

            // Create or get new lead
            if ($leadId) {
                $lead = NewLead::findOrFail($leadId);
            } else {
                // Create new lead
                $isNewLead = true;
                $lead = new NewLead();
                $lead->company_id = company()->id;
                $lead->client_name = ($request->surname ?? '') . ' ' . ($request->given_name ?? '');
                $lead->client_email = $request->email_address ?? $request->email ?? null;
                $lead->mobile = $request->mobile ?? $request->primary_phone ?? null;
                $lead->added_by = user()->id;
                $lead->lead_status = 'Open Lead';
                $lead->lead_quality = 'Open';
                $lead->hash = md5(microtime());
                $lead->save();
                $leadId = $lead->id;
            }

            // Get or create step status
            $stepStatus = LeadStepStatus::getOrCreateForLead($leadId);

            // Save step data using unified method for all steps
            $this->saveStepData($lead, $request, $stepNumber);

            // Check if this step was already completed before (to determine if this is first time saving)
            $stepField = 'step_' . $stepNumber . '_completed';
            $wasStepAlreadyCompleted = $stepStatus->$stepField;

            // Mark step as completed
            $stepStatus->$stepField = true;
            $stepStatus->save();

            // Update final status (skip for step 9 as it's managed differently)
            // Use loose comparison to handle both string "9" and integer 9
            // Only set status to 'draft' if this is the first time saving this step
            // For step 2, only set to 'draft' on first save, don't overwrite on subsequent saves
            if ($stepNumber != 9 && $stepNumber !== 9 && (string)$stepNumber !== '9') {
                $isFirstTimeSavingStep = !$wasStepAlreadyCompleted;
                $stepStatus->updateFinalStatus($isFirstTimeSavingStep);
            }

            // Log step completion
            $this->logStepCompletion($leadId, $stepNumber);

            // Send emails when step 9 is completed
            // DISABLED: Email notification on step 9 completion
            // Use loose comparison to handle both string "9" and integer 9
            // if ($stepNumber == 9 || $stepNumber === 9 || (string)$stepNumber === '9') {
            //     \Log::info('Step 9 completed, triggering email sending for lead ID: ' . $lead->id);
            //     $this->sendLeadEmails($lead);
            // }

            return Reply::successWithData(__('messages.recordSaved'), [
                'lead_id' => $leadId,
                'step_' . $stepNumber . '_completed' => true,
                'final_status' => $stepStatus->final_status,
            ]);
        } catch (\Exception $e) {
            return Reply::error(__('messages.errorOccurred') . ': ' . $e->getMessage());
        }
    }

    /**
     * Helper method to get request value, converting empty strings to null
     */
    private function getRequestValue($request, $key, $default = null)
    {
        $value = $request->input($key, $default);
        return ($value === '' || $value === null) ? $default : $value;
    }

    /**
     * Get fields for a specific step (1-9)
     */
    private function getStepFields($stepNumber)
    {
        $stepFields = [
            1 => [
                // Step 1 - Personal Details
                'lead_source',
                'lead_added_by',
                'lead_assign_to',
                'surname',
                'given_name',
                'gender',
                'date_of_birth',
                'marital_status',
                'country_of_origin',
                'email_address',
                'primary_phone',
                'secondary_phone',
                'work_phone',
                'other_phone',
                'home_address',
                'home_city',
                'home_state',
                'home_pin_code',
                'mailing_address',
                'mailing_city',
                'mailing_state',
                'mailing_pin_code',
                'mailing_same_as_home',
                'visa_status',
                'visa_issue_date',
                'visa_expire_date',
                'visa_category',
                'visa_rejection_date',
                'visa_refusal_category',
                'visa_refusal_reason',
                'visa_refusals',
                'languages_spoken',
                'facebook_profile_url',
                'instagram_profile_url',
                'linkedin_profile_url',
                'other_email',
                'upload_resume',
            ],
            2 => [
                // Step 2 - Client Preference
                'visa_type',
                'skill_assessment_letter',
                'pr_assessment_letter_file',
                'pr_preferred_country',
                'pr_preferred_state',
                'pr_family',
                'pr_subclass',
                'purpose_of_visit',
                'visit_family',
                'visit_preferred_country',
                'visit_preferred_state',
                'visit_subclass',
                'preferred_designation',
                'industry',
                'on_role_off_role',
                'work_preferred_country',
                'work_preferred_state',
                'work_category',
                'work_subclass',
                'preferred_course',
                'student_country',
                'university',
                'term_intake',
                'student_subclass',
            ],
            3 => [
                // Step 3 - Passport Details
                'passport_number',
                'issuing_country',
                'city_where_issued',
                'issuance_date',
                'expiration_date',
                'lost_passport_history',
                'passport_file_upload',
            ],
            4 => [
                // Step 4 - Relative Contact Information
                'relative_surname',
                'relative_given_name',
                'relative_organization_name',
                'relative_relationship',
                'relative_contact_address',
                'relative_city',
                'relative_state',
                'relative_zip_code',
                'relative_email_address',
                'relative_phone_number',
            ],
            5 => [
                // Step 5 - Family Information
                'father_surname',
                'father_given_name',
                'father_date_of_birth',
                'father_occupation',
                'father_have_passport',
                'father_passport_file',
                'mother_surname',
                'mother_given_name',
                'mother_date_of_birth',
                'mother_occupation',
                'mother_have_passport',
                'mother_passport_file',
                'spouse_surname',
                'spouse_given_name',
                'spouse_date_of_birth',
                'spouse_country',
                'spouse_city_of_birth',
                'spouse_have_passport',
                'spouse_passport_file',
                'spouse_address',
                'spouse_city',
                'spouse_state',
                'spouse_postal_code',
                'spouse_phone_number',
                'spouse_education',
                'spouse_occupation',
                'spouse_yearly_income',
                'child_name',
                'child_age',
                'child_date_of_birth',
                'child_city_of_birth',
                'child_gender',
                'child_have_passport',
                'child_passport_file',
                'child_document_file',
                'spouse_document_file',
            ],
            6 => [
                // Step 6 - Education
                'ielts_clear_or_not',
                'ielts_passing_year',
                'ielts_score',
                'ielts_trial',
                'ielts_result_file',
                'tenth_passing_year',
                'tenth_percentage',
                'tenth_board_name',
                'tenth_trial',
                'tenth_result_file',
                'twelfth_passing_year',
                'twelfth_stream',
                'twelfth_percentage',
                'twelfth_board_name',
                'twelfth_trial',
                'twelfth_result_file',
                'graduation_degree',
                'graduation_university_name',
                'graduation_percentage',
                'graduation_passing_year',
                'graduation_trial',
                'graduation_result_file',
                'post_graduation_degree',
                'post_graduation_university_name',
                'post_graduation_percentage',
                'post_graduation_passing_year',
                'post_graduation_trial',
                'post_graduation_result_file',
                'other_degrees',
            ],
            7 => [
                // Step 7 - Professional Experience
                'jobs',
            ],
            8 => [
                // Step 8 - Property Details
                'property_home',
                'property_land',
                'property_plot',
                'property_commercials',
                'property_other',
                'property_shop',
                'property_gold',
                'property_silver',
                'total_valuation',
                'total_loan_value',
                'loan_years',
                'loan_availed_on',
                'valuation_report_file',
            ],
            5 => [
                // Step 5 - Family Information
                'father_surname',
                'father_given_name',
                'father_date_of_birth',
                'father_occupation',
                'father_have_passport',
                'father_passport_file',
                'mother_surname',
                'mother_given_name',
                'mother_date_of_birth',
                'mother_occupation',
                'mother_have_passport',
                'mother_passport_file',
                'spouse_surname',
                'spouse_given_name',
                'spouse_date_of_birth',
                'spouse_country',
                'spouse_city_of_birth',
                'spouse_have_passport',
                'spouse_passport_file',
                'spouse_document_file',
                'spouse_address',
                'spouse_city',
                'spouse_state',
                'spouse_postal_code',
                'spouse_phone_number',
                'spouse_education',
                'spouse_occupation',
                'spouse_yearly_income',
                'child_name',
                'child_age',
                'child_date_of_birth',
                'child_city_of_birth',
                'child_gender',
                'child_have_passport',
                'child_passport_file',
                'child_document_file',
            ],
            9 => [
                // Step 9 - Financial Status
                'father_income',
                'mother_income',
                'candidate_income',
                'spouse_income',
                'total_income',
                'father_income_document_file',
                'mother_income_document_file',
                'candidate_income_document_file',
                'spouse_income_document_file',
            ],
        ];

        return $stepFields[$stepNumber] ?? [];
    }

    /**
     * Save Steps 1-9 data (unified method)
     */
    private function saveStepData(NewLead $lead, Request $request, $stepNumber)
    {
        
        // Get only the fields that belong to this step
        $stepFields = $this->getStepFields($stepNumber);
        
        // Extract only the relevant fields for this step
        $stepData = [];
        foreach ($stepFields as $field) {
            // Skip file fields - they will be handled separately in special cases
            // BUT: Don't skip upload_resume for step 1 - it's handled in special cases
            if ($request->hasFile($field) && !($stepNumber == 1 && $field === 'upload_resume')) {
                // Initialize file field as null, will be set in special handling
                $stepData[$field] = null;
                continue;
            }
            
            // Skip pr_assessment_letter_file for step 2 - it's handled in special cases
            if ($field === 'pr_assessment_letter_file' && $stepNumber == 2) {
                $stepData[$field] = null;
                continue;
            }
            
            // Skip passport_file_upload for step 3 - it's handled in special cases
            if ($field === 'passport_file_upload' && $stepNumber == 3) {
                $stepData[$field] = null;
                continue;
            }
            
            // Skip step 5 file fields - they're handled in special cases
            if ($stepNumber == 5 && in_array($field, ['father_passport_file', 'mother_passport_file', 'spouse_passport_file', 'spouse_document_file', 'child_passport_file', 'child_document_file'])) {
                $stepData[$field] = null;
                continue;
            }
            
            // Skip step 6 file fields - they're handled in special cases
            if ($stepNumber == 6 && in_array($field, ['ielts_result_file', 'tenth_result_file', 'twelfth_result_file', 'graduation_result_file', 'post_graduation_result_file', 'other_degree_result_file'])) {
                $stepData[$field] = null;
                continue;
            }
            
            // Skip step 6 other_degree fields - they're handled in special cases (as arrays)
            if ($stepNumber == 6 && in_array($field, ['other_degree', 'other_degree_university_name', 'other_degree_percentage', 'other_degree_passing_year', 'other_degree_trial'])) {
                $stepData[$field] = null;
                continue;
            }
            
            // Get the value using our helper method
            $value = $this->getRequestValue($request, $field);
            // Always include the field, even if null, to maintain structure
            $stepData[$field] = $value;
        }
        
        // Handle special cases for Step 1
        // Use loose comparison to handle both string "1" and integer 1
        if ($stepNumber == 1) {
            // Update lead basic info
            $lead->client_name = ($this->getRequestValue($request, 'surname', '') . ' ' . $this->getRequestValue($request, 'given_name', '')) ?: null;
            $lead->client_email = $this->getRequestValue($request, 'email_address') ?: $this->getRequestValue($request, 'email');
            $lead->mobile = $this->getRequestValue($request, 'primary_phone');
            // Only set lead_owner if explicitly provided in request, don't auto-assign
            if ($request->has('lead_assign_to') && $request->lead_assign_to) {
                $lead->lead_owner = $this->getRequestValue($request, 'lead_assign_to');
            }
            $lead->lead_source = $this->getRequestValue($request, 'lead_source');
            $lead->last_updated_by = user()->id;
            $lead->save();
            
            // Handle mailing address same as home address
            if ($request->mailing_same_as_home) {
                $stepData['mailing_address'] = $this->getRequestValue($request, 'home_address');
                $stepData['mailing_city'] = $this->getRequestValue($request, 'home_city');
                $stepData['mailing_state'] = $this->getRequestValue($request, 'home_state');
                $stepData['mailing_pin_code'] = $this->getRequestValue($request, 'home_pin_code');
            }
            
            // Handle email field
            $emailValue = $this->getRequestValue($request, 'email_address');
            $stepData['email_address'] = $emailValue;

            // Handle visa_category: resolve id to name, or use visa_category_other when "Other" is selected
            $visaCat = $this->getRequestValue($request, 'visa_category');
            if ($visaCat === 'other' || $visaCat === 'Other') {
                $stepData['visa_category'] = $this->getRequestValue($request, 'visa_category_other');
            } elseif (is_numeric($visaCat)) {
                $cat = NewVisaCategoryMaster::find($visaCat);
                $stepData['visa_category'] = $cat ? $cat->name : $visaCat;
            }
            // else: stepData['visa_category'] stays as from the loop (e.g. empty string)

            // Handle languages_spoken: convert array of IDs to comma-separated names
            $languagesSpoken = $request->input('languages_spoken', []);
            if (is_array($languagesSpoken) && count($languagesSpoken) > 0) {
                $languageNames = [];
                foreach ($languagesSpoken as $langId) {
                    if (is_numeric($langId)) {
                        $lang = NewLanguageMaster::find($langId);
                        if ($lang) {
                            $languageNames[] = $lang->name;
                        }
                    } else {
                        // Backward compatibility: if it's already a string, use it
                        $languageNames[] = $langId;
                    }
                }
                $stepData['languages_spoken'] = implode(', ', $languageNames);
            } else {
                // If empty or not array, keep the value from the loop (which might be empty string)
                $stepData['languages_spoken'] = $this->getRequestValue($request, 'languages_spoken', '');
            }

            // Bind Country/State/City masters (store names in step data)
            $countryOfOrigin = $this->getRequestValue($request, 'country_of_origin');
            if (is_numeric($countryOfOrigin)) {
                $country = NewCountryMaster::find($countryOfOrigin);
                $stepData['country_of_origin'] = $country ? $country->name : $countryOfOrigin;
            }

            $homeState = $this->getRequestValue($request, 'home_state');
            if (is_numeric($homeState)) {
                $state = NewStateMaster::find($homeState);
                $stepData['home_state'] = $state ? $state->name : $homeState;
            }

            $homeCity = $this->getRequestValue($request, 'home_city');
            if (is_numeric($homeCity)) {
                $city = NewCityMaster::find($homeCity);
                $stepData['home_city'] = $city ? $city->name : $homeCity;
            }

            $mailingState = $stepData['mailing_state'] ?? $this->getRequestValue($request, 'mailing_state');
            if (is_numeric($mailingState)) {
                $state = NewStateMaster::find($mailingState);
                $stepData['mailing_state'] = $state ? $state->name : $mailingState;
            }

            $mailingCity = $stepData['mailing_city'] ?? $this->getRequestValue($request, 'mailing_city');
            if (is_numeric($mailingCity)) {
                $city = NewCityMaster::find($mailingCity);
                $stepData['mailing_city'] = $city ? $city->name : $mailingCity;
            }

            // Parse visa_refusals if it's a JSON string
            $visaRefusals = [];
            if ($request->has('visa_refusals')) {
                $visaRefusalsValue = $request->visa_refusals;
                if (!empty($visaRefusalsValue) && $visaRefusalsValue !== '[]' && $visaRefusalsValue !== 'null') {
                    if (is_string($visaRefusalsValue)) {
                        $decoded = json_decode($visaRefusalsValue, true);
                        $visaRefusals = is_array($decoded) ? $decoded : [];
                    } elseif (is_array($visaRefusalsValue)) {
                        $visaRefusals = $visaRefusalsValue;
                    }
                }
            }
            $stepData['visa_refusals'] = $visaRefusals;
            
            // Remove individual visa refusal fields from stepData (they're now in visa_refusals array)
            $visaRefusalFields = ['visa_rejection_date', 'visa_refusal_category', 'visa_refusal_reason'];
            foreach ($visaRefusalFields as $field) {
                unset($stepData[$field]);
            }
            
            // Handle mailing_same_as_home as boolean
            $stepData['mailing_same_as_home'] = $request->has('mailing_same_as_home') ? (bool)$request->mailing_same_as_home : false;
            
            // Handle upload_resume file upload for step 1
            $existingStep1Data = is_array($lead->step_1_data) ? $lead->step_1_data : [];
            
            // Try multiple ways to detect the file
            $fileDetected = false;
            $uploadedFile = null;
            
            if ($request->hasFile('upload_resume')) {
                $uploadedFile = $request->file('upload_resume');
                $fileDetected = true;
            } elseif ($request->allFiles() && isset($request->allFiles()['upload_resume'])) {
                $uploadedFile = $request->allFiles()['upload_resume'];
                $fileDetected = true;
            } elseif ($request->file('upload_resume')) {
                $uploadedFile = $request->file('upload_resume');
                $fileDetected = true;
            }
            
            if ($fileDetected && $uploadedFile) {
                // Delete old file if exists
                if (isset($existingStep1Data['upload_resume']) && $existingStep1Data['upload_resume']) {
                    $oldFileName = $existingStep1Data['upload_resume'];
                    try {
                        \App\Helper\Files::deleteFile($oldFileName, 'lead-resume-files');
                    } catch (\Exception $e) {
                        // Silently fail if delete fails
                    }
                }
                
                // Upload new file directly to lead-resume-files folder with original name + unique ID
                try {
                    $fileToUpload = $uploadedFile;
                    if (!$fileToUpload) {
                        $fileToUpload = $request->upload_resume;
                    }
                    
                    // Generate filename with original name + unique ID
                    $customFileName = \App\Helper\Files::generateFileNameWithOriginal($fileToUpload->getClientOriginalName());
                    \App\Helper\Files::fileStore($fileToUpload, 'lead-resume-files', $customFileName);
                    
                    $fileVisibility = [];
                    if (config('filesystems.default') == 'local') {
                        $fileVisibility = ['directory_visibility' => 'public', 'visibility' => 'public'];
                    }
                    
                    Storage::disk(config('filesystems.default'))->putFileAs('lead-resume-files', $fileToUpload, $customFileName, $fileVisibility);
                    
                    $stepData['upload_resume'] = $customFileName;
                } catch (\Exception $e) {
                    // Preserve existing file if upload fails
                    if (isset($existingStep1Data['upload_resume']) && $existingStep1Data['upload_resume']) {
                        $stepData['upload_resume'] = $existingStep1Data['upload_resume'];
                    }
                }
            } elseif ($request->has('upload_resume_existing')) {
                // Keep existing file if no new file is uploaded
                $stepData['upload_resume'] = $request->input('upload_resume_existing');
            } elseif (isset($existingStep1Data['upload_resume']) && $existingStep1Data['upload_resume']) {
                // Keep existing file if no new file is uploaded and no removal signal
                $stepData['upload_resume'] = $existingStep1Data['upload_resume'];
            } else {
                // No file uploaded and no existing file
                $stepData['upload_resume'] = null;
            }
        }
        
        // Handle special cases for Step 2
        if ($stepNumber == 2) {
            // Handle PR Assessment Letter file upload
            $prAssessmentLetterFile = null;
            $existingStep2Data = is_array($lead->step_2_data) ? $lead->step_2_data : [];
            
            if ($request->hasFile('pr_assessment_letter_file')) {
                // Delete old file if exists
                if (isset($existingStep2Data['pr_assessment_letter_file'])) {
                    $oldFileName = $existingStep2Data['pr_assessment_letter_file'];
                    \App\Helper\Files::deleteFile($oldFileName, 'lead-assessment-letters/' . $lead->id);
                }
                // Upload new file with original name + unique ID
                try {
                    // Generate filename with original name + unique ID
                    $customFileName = \App\Helper\Files::generateFileNameWithOriginal($request->pr_assessment_letter_file->getClientOriginalName());
                    \App\Helper\Files::fileStore($request->pr_assessment_letter_file, 'lead-assessment-letters/' . $lead->id, $customFileName);
                    
                    $fileVisibility = [];
                    if (config('filesystems.default') == 'local') {
                        $fileVisibility = ['directory_visibility' => 'public', 'visibility' => 'public'];
                    }
                    
                    Storage::disk(config('filesystems.default'))->putFileAs('lead-assessment-letters/' . $lead->id, $request->pr_assessment_letter_file, $customFileName, $fileVisibility);
                    $prAssessmentLetterFile = $customFileName;
                } catch (\Exception $e) {
                    // Silently fail
                }
            } elseif ($request->has('pr_assessment_letter_file_existing')) {
                $prAssessmentLetterFile = $request->pr_assessment_letter_file_existing;
            } elseif (isset($existingStep2Data['pr_assessment_letter_file'])) {
                // User removed file
                $oldFileName = $existingStep2Data['pr_assessment_letter_file'];
                try {
                    \App\Helper\Files::deleteFile($oldFileName, 'lead-assessment-letters/' . $lead->id);
                } catch (\Exception $e) {
                    // Silently fail
                }
                $prAssessmentLetterFile = null;
            }
            $stepData['pr_assessment_letter_file'] = $prAssessmentLetterFile;
        }
        
        // Handle file uploads for specific steps
        if ($stepNumber == 3) {
            // Handle passport file upload for step 3
            if ($request->hasFile('passport_file_upload')) {
                // Delete old file if exists
                if (isset($lead->step_3_data['passport_file_upload']) && $lead->step_3_data['passport_file_upload']) {
                    $oldFileName = $lead->step_3_data['passport_file_upload'];
                    \App\Helper\Files::deleteFile($oldFileName, 'lead-passport-files/' . $lead->id);
                }
                // Upload new file with original name + unique ID
                try {
                    // Generate filename with original name + unique ID
                    $customFileName = \App\Helper\Files::generateFileNameWithOriginal($request->passport_file_upload->getClientOriginalName());
                    \App\Helper\Files::fileStore($request->passport_file_upload, 'lead-passport-files/' . $lead->id, $customFileName);
                    
                    $fileVisibility = [];
                    if (config('filesystems.default') == 'local') {
                        $fileVisibility = ['directory_visibility' => 'public', 'visibility' => 'public'];
                    }
                    
                    Storage::disk(config('filesystems.default'))->putFileAs('lead-passport-files/' . $lead->id, $request->passport_file_upload, $customFileName, $fileVisibility);
                    $stepData['passport_file_upload'] = $customFileName;
                } catch (\Exception $e) {
                    // Silently fail
                }
            } elseif ($request->has('passport_file_upload_existing')) {
                // Keep existing file if no new file is uploaded
                $stepData['passport_file_upload'] = $request->input('passport_file_upload_existing');
            } elseif (isset($lead->step_3_data['passport_file_upload']) && $lead->step_3_data['passport_file_upload']) {
                // If existing file input is not present, it means user removed it, so delete file
                $oldFileName = $lead->step_3_data['passport_file_upload'];
                try {
                    \App\Helper\Files::deleteFile($oldFileName, 'lead-passport-files/' . $lead->id);
                } catch (\Exception $e) {
                    // Silently fail
                }
                $stepData['passport_file_upload'] = null;
            }

            // Bind issuing country / city masters (store names)
            $issuingCountry = $this->getRequestValue($request, 'issuing_country');
            if (is_numeric($issuingCountry)) {
                $country = NewCountryMaster::find($issuingCountry);
                $stepData['issuing_country'] = $country ? $country->name : $issuingCountry;
            }

            $cityWhereIssued = $this->getRequestValue($request, 'city_where_issued');
            if (is_numeric($cityWhereIssued)) {
                $city = NewCityMaster::find($cityWhereIssued);
                $stepData['city_where_issued'] = $city ? $city->name : $cityWhereIssued;
            }
        }
        
        if ($stepNumber == 4) {
            // Handle relative contacts data - check if sent as JSON string
            $relativeContactData = [];
            
            if ($request->has('relative_contacts')) {
                $relativeContactsJson = $request->input('relative_contacts');
                if (is_string($relativeContactsJson)) {
                    $decoded = json_decode($relativeContactsJson, true);
                    if (is_array($decoded)) {
                        $relativeContactData = $decoded;
                    }
                } elseif (is_array($relativeContactsJson)) {
                    $relativeContactData = $relativeContactsJson;
                }
            }
            
            // If no relative contacts from JSON, try old format (backward compatibility)
            if (empty($relativeContactData)) {
                $relativeFields = ['relative_surname', 'relative_given_name', 'relative_organization_name', 'relative_relationship', 'relative_contact_address', 'relative_city', 'relative_state', 'relative_zip_code', 'relative_email_address', 'relative_phone_number'];
                
                // Get Relative Contact 1 data (single values)
                $relative1Data = [];
                foreach ($relativeFields as $field) {
                    $value = $this->getRequestValue($request, $field);
                    if ($value !== null && $value !== '') {
                        $relative1Data[$field] = $value;
                    }
                }
                
                // If Relative Contact 1 has any data, add it to the array
                if (!empty($relative1Data)) {
                    $relativeContactData[] = $relative1Data;
                }
                
                // Get Relative Contact 2+ data (arrays)
                $relativeArrays = [];
                foreach ($relativeFields as $field) {
                    $arrayKey = $field . '[]';
                    $arrayValues = $request->input($arrayKey, []);
                    if (is_array($arrayValues)) {
                        $relativeArrays[$field] = $arrayValues;
                    }
                }
                
                // Combine Relative Contact 2+ data into proper structure
                if (!empty($relativeArrays)) {
                    $maxCount = 0;
                    foreach ($relativeArrays as $field => $values) {
                        if (count($values) > $maxCount) {
                            $maxCount = count($values);
                        }
                    }
                    
                    for ($i = 0; $i < $maxCount; $i++) {
                        $relativeRow = [];
                        foreach ($relativeFields as $field) {
                            if (isset($relativeArrays[$field][$i]) && $relativeArrays[$field][$i] !== null && $relativeArrays[$field][$i] !== '') {
                                $relativeRow[$field] = $relativeArrays[$field][$i];
                            }
                        }
                        if (!empty($relativeRow)) {
                            $relativeContactData[] = $relativeRow;
                        }
                    }
                }
            }
            
            // Store relative contacts data as JSON array
            $stepData['relative_contacts'] = $relativeContactData;
            
            // Remove individual relative contact fields from stepData (they're now in relative_contacts array)
            $relativeFields = ['relative_surname', 'relative_given_name', 'relative_organization_name', 'relative_relationship', 'relative_contact_address', 'relative_city', 'relative_state', 'relative_zip_code', 'relative_email_address', 'relative_phone_number'];
            foreach ($relativeFields as $field) {
                unset($stepData[$field]);
            }
        }
        
        if ($stepNumber == 5) {
            // Bind spouse city/state masters (store names)
            $spouseCountry = $this->getRequestValue($request, 'spouse_country');
            if (is_numeric($spouseCountry)) {
                $country = NewCountryMaster::find($spouseCountry);
                $stepData['spouse_country'] = $country ? $country->name : $spouseCountry;
            }

            $spouseState = $this->getRequestValue($request, 'spouse_state');
            if (is_numeric($spouseState)) {
                $state = NewStateMaster::find($spouseState);
                $stepData['spouse_state'] = $state ? $state->name : $spouseState;
            }

            $spouseCity = $this->getRequestValue($request, 'spouse_city');
            if (is_numeric($spouseCity)) {
                $city = NewCityMaster::find($spouseCity);
                $stepData['spouse_city'] = $city ? $city->name : $spouseCity;
            }

            // Handle children data - check if sent as JSON string
            $childData = [];
            
            if ($request->has('children')) {
                $childrenJson = $request->input('children');
                if (is_string($childrenJson)) {
                    $decoded = json_decode($childrenJson, true);
                    if (is_array($decoded)) {
                        $childData = $decoded;
                    }
                } elseif (is_array($childrenJson)) {
                    $childData = $childrenJson;
                }
            }
            
            // If no children from JSON, try old format (backward compatibility)
            if (empty($childData)) {
                $childFields = ['child_name', 'child_age', 'child_date_of_birth', 'child_city_of_birth', 'child_gender', 'child_have_passport'];
                
                // Get Child 1 data (single values)
                $child1Data = [];
                foreach ($childFields as $field) {
                    $value = $this->getRequestValue($request, $field);
                    if ($value !== null && $value !== '') {
                        $child1Data[$field] = $value;
                    }
                }
                
                // If Child 1 has any data, add it to the array
                if (!empty($child1Data)) {
                    $childData[] = $child1Data;
                }
                
                // Get Child 2+ data (arrays)
                $childArrays = [];
                foreach ($childFields as $field) {
                    $arrayKey = $field . '[]';
                    $arrayValues = $request->input($arrayKey, []);
                    if (is_array($arrayValues)) {
                        $childArrays[$field] = $arrayValues;
                    }
                }
                
                // Combine Child 2+ data into proper structure
                if (!empty($childArrays)) {
                    $maxCount = 0;
                    foreach ($childArrays as $field => $values) {
                        if (count($values) > $maxCount) {
                            $maxCount = count($values);
                        }
                    }
                    
                    for ($i = 0; $i < $maxCount; $i++) {
                        $childRow = [];
                        foreach ($childFields as $field) {
                            if (isset($childArrays[$field][$i]) && $childArrays[$field][$i] !== null && $childArrays[$field][$i] !== '') {
                                $childRow[$field] = $childArrays[$field][$i];
                            }
                        }
                        if (!empty($childRow)) {
                            $childData[] = $childRow;
                        }
                    }
                }
            }
            
            // Store child data as JSON array
            $stepData['children'] = $childData;
            
            // Remove individual child fields from stepData (they're now in children array)
            $childFields = ['child_name', 'child_age', 'child_date_of_birth', 'child_city_of_birth', 'child_gender', 'child_have_passport'];
            foreach ($childFields as $field) {
                unset($stepData[$field]);
            }
            
            // Safely get existing step 5 data first
            $existingStep5Data = [];
            if ($lead->step_5_data) {
                if (is_string($lead->step_5_data)) {
                    $decoded = json_decode($lead->step_5_data, true);
                    $existingStep5Data = is_array($decoded) ? $decoded : [];
                } elseif (is_array($lead->step_5_data)) {
                    $existingStep5Data = $lead->step_5_data;
                }
            }
            
            // Handle child file uploads first (with new naming convention: child_passport_file_1, child_document_file_1, etc.)
            $passportFolder = 'lead-family-passports';
            $documentFolder = 'lead-family-documents';
            
            // Get existing children data for file reference
            $existingChildren = [];
            if (isset($existingStep5Data['children']) && is_array($existingStep5Data['children'])) {
                $existingChildren = $existingStep5Data['children'];
            }
            
            // Process child files and update children array
            foreach ($childData as $index => $child) {
                $childIndex = $index + 1; // Child index starts from 1
                
                // Handle child passport file
                $passportFileKey = 'child_passport_file_' . $childIndex;
                $passportFileExistingKey = $passportFileKey . '_existing';
                
                if ($request->hasFile($passportFileKey)) {
                    try {
                        $file = $request->file($passportFileKey);
                        if ($file && $file->isValid()) {
                            // Delete old file if exists
                            if (isset($child['child_passport_file']) && $child['child_passport_file']) {
                                \App\Helper\Files::deleteFile($child['child_passport_file'], $passportFolder . '/' . $lead->id);
                            }
                            
                            $customFileName = \App\Helper\Files::generateFileNameWithOriginal($file->getClientOriginalName());
                            \App\Helper\Files::fileStore($file, $passportFolder . '/' . $lead->id, $customFileName);
                            
                            $fileVisibility = [];
                            if (config('filesystems.default') == 'local') {
                                $fileVisibility = ['directory_visibility' => 'public', 'visibility' => 'public'];
                            }
                            
                            Storage::disk(config('filesystems.default'))->putFileAs($passportFolder . '/' . $lead->id, $file, $customFileName, $fileVisibility);
                            $childData[$index]['child_passport_file'] = $customFileName;
                        }
                    } catch (\Exception $e) {
                        // Preserve existing file if upload fails
                        if (isset($child['child_passport_file']) && $child['child_passport_file']) {
                            $childData[$index]['child_passport_file'] = $child['child_passport_file'];
                        }
                    }
                } elseif ($request->has($passportFileExistingKey)) {
                    // Keep existing file
                    $childData[$index]['child_passport_file'] = $request->input($passportFileExistingKey);
                } elseif (isset($child['child_passport_file']) && $child['child_passport_file'] && 
                          (!isset($existingChildren[$index]) || !isset($existingChildren[$index]['child_passport_file']) || 
                           $existingChildren[$index]['child_passport_file'] !== $child['child_passport_file'])) {
                    // File was removed, delete it
                    try {
                        \App\Helper\Files::deleteFile($child['child_passport_file'], $passportFolder . '/' . $lead->id);
                    } catch (\Exception $e) {
                        // Silently fail
                    }
                    $childData[$index]['child_passport_file'] = null;
                }
                
                // Handle child document file
                $documentFileKey = 'child_document_file_' . $childIndex;
                $documentFileExistingKey = $documentFileKey . '_existing';
                
                if ($request->hasFile($documentFileKey)) {
                    try {
                        $file = $request->file($documentFileKey);
                        if ($file && $file->isValid()) {
                            // Delete old file if exists
                            if (isset($child['child_document_file']) && $child['child_document_file']) {
                                \App\Helper\Files::deleteFile($child['child_document_file'], $documentFolder . '/' . $lead->id);
                            }
                            
                            $customFileName = \App\Helper\Files::generateFileNameWithOriginal($file->getClientOriginalName());
                            \App\Helper\Files::fileStore($file, $documentFolder . '/' . $lead->id, $customFileName);
                            
                            $fileVisibility = [];
                            if (config('filesystems.default') == 'local') {
                                $fileVisibility = ['directory_visibility' => 'public', 'visibility' => 'public'];
                            }
                            
                            Storage::disk(config('filesystems.default'))->putFileAs($documentFolder . '/' . $lead->id, $file, $customFileName, $fileVisibility);
                            $childData[$index]['child_document_file'] = $customFileName;
                        }
                    } catch (\Exception $e) {
                        // Preserve existing file if upload fails
                        if (isset($child['child_document_file']) && $child['child_document_file']) {
                            $childData[$index]['child_document_file'] = $child['child_document_file'];
                        }
                    }
                } elseif ($request->has($documentFileExistingKey)) {
                    // Keep existing file
                    $childData[$index]['child_document_file'] = $request->input($documentFileExistingKey);
                } elseif (isset($child['child_document_file']) && $child['child_document_file'] && 
                          (!isset($existingChildren[$index]) || !isset($existingChildren[$index]['child_document_file']) || 
                           $existingChildren[$index]['child_document_file'] !== $child['child_document_file'])) {
                    // File was removed, delete it
                    try {
                        \App\Helper\Files::deleteFile($child['child_document_file'], $documentFolder . '/' . $lead->id);
                    } catch (\Exception $e) {
                        // Silently fail
                    }
                    $childData[$index]['child_document_file'] = null;
                }
            }
            
            // Update stepData with updated children array
            $stepData['children'] = $childData;
            
            // Handle file uploads for other family members in step 5 - use same pattern as upload_resume
            $familyFileFields = [
                'father_passport_file',
                'mother_passport_file',
                'spouse_passport_file',
                'spouse_document_file',
            ];
            
            foreach ($familyFileFields as $fileField) {
                $folder = strpos($fileField, 'passport') !== false ? 'lead-family-passports' : 'lead-family-documents';
                
                // Safely get existing step 5 data
                $existingStep5Data = [];
                if ($lead->step_5_data) {
                    if (is_string($lead->step_5_data)) {
                        $decoded = json_decode($lead->step_5_data, true);
                        $existingStep5Data = is_array($decoded) ? $decoded : [];
                    } elseif (is_array($lead->step_5_data)) {
                        $existingStep5Data = $lead->step_5_data;
                    }
                }
                
                if ($request->hasFile($fileField)) {
                    // Delete old file if exists
                    if (isset($existingStep5Data[$fileField]) && $existingStep5Data[$fileField]) {
                        $oldFileName = $existingStep5Data[$fileField];
                        \App\Helper\Files::deleteFile($oldFileName, $folder . '/' . $lead->id);
                    }
                    // Upload new file with original name + unique ID
                    try {
                        $file = $request->file($fileField);
                        // Handle both single file and array of files
                        if (is_array($file)) {
                            // If it's an array, take the first file (for child files, we handle them separately)
                            $file = !empty($file) ? $file[0] : null;
                        }
                        if ($file && $file->isValid()) {
                            $customFileName = \App\Helper\Files::generateFileNameWithOriginal($file->getClientOriginalName());
                            \App\Helper\Files::fileStore($file, $folder . '/' . $lead->id, $customFileName);
                            
                            $fileVisibility = [];
                            if (config('filesystems.default') == 'local') {
                                $fileVisibility = ['directory_visibility' => 'public', 'visibility' => 'public'];
                            }
                            
                            Storage::disk(config('filesystems.default'))->putFileAs($folder . '/' . $lead->id, $file, $customFileName, $fileVisibility);
                            $stepData[$fileField] = $customFileName;
                        }
                    } catch (\Exception $e) {
                        // Preserve existing file if upload fails
                        if (isset($existingStep5Data[$fileField]) && $existingStep5Data[$fileField]) {
                            $stepData[$fileField] = $existingStep5Data[$fileField];
                        }
                    }
                } elseif ($request->has($fileField . '_existing')) {
                    // Keep existing file if no new file is uploaded
                    $stepData[$fileField] = $request->input($fileField . '_existing');
                } elseif (isset($existingStep5Data[$fileField]) && $existingStep5Data[$fileField]) {
                    // If existing file input is not present, it means user removed it, so delete file
                    $oldFileName = $existingStep5Data[$fileField];
                    try {
                        \App\Helper\Files::deleteFile($oldFileName, $folder . '/' . $lead->id);
                    } catch (\Exception $e) {
                        // Silently fail
                    }
                    $stepData[$fileField] = null;
                }
            }
        }
        
        // Handle file uploads for step 6 (Education) - use same pattern as upload_resume
        if ($stepNumber == 6) {
            // Handle other_degrees data - check if sent as JSON string
            $otherDegreeData = [];
            
            if ($request->has('other_degrees')) {
                $otherDegreesJson = $request->input('other_degrees');
                if (is_string($otherDegreesJson)) {
                    $decoded = json_decode($otherDegreesJson, true);
                    if (is_array($decoded)) {
                        $otherDegreeData = $decoded;
                    }
                } elseif (is_array($otherDegreesJson)) {
                    $otherDegreeData = $otherDegreesJson;
                }
            }
            
            // If no other_degrees from JSON, try old format (backward compatibility)
            if (empty($otherDegreeData)) {
                $otherDegreeFields = ['other_degree', 'other_degree_university_name', 'other_degree_percentage', 'other_degree_passing_year', 'other_degree_trial'];
                
                // Get Other Degree 1 data (single values)
                $otherDegree1Data = [];
                foreach ($otherDegreeFields as $field) {
                    $value = $this->getRequestValue($request, $field);
                    if ($value !== null && $value !== '') {
                        $otherDegree1Data[$field] = $value;
                    }
                }
                
                // If Other Degree 1 has any data, add it to the array
                if (!empty($otherDegree1Data)) {
                    $otherDegreeData[] = $otherDegree1Data;
                }
                
                // Get Other Degree 2+ data (arrays)
                $otherDegreeArrays = [];
                foreach ($otherDegreeFields as $field) {
                    $arrayKey = $field . '[]';
                    $arrayValues = $request->input($arrayKey, []);
                    if (is_array($arrayValues)) {
                        $otherDegreeArrays[$field] = $arrayValues;
                    }
                }
                
                // Combine Other Degree 2+ data into proper structure
                if (!empty($otherDegreeArrays)) {
                    $maxCount = 0;
                    foreach ($otherDegreeArrays as $field => $values) {
                        if (count($values) > $maxCount) {
                            $maxCount = count($values);
                        }
                    }
                    
                    for ($i = 0; $i < $maxCount; $i++) {
                        $otherDegreeRow = [];
                        foreach ($otherDegreeFields as $field) {
                            if (isset($otherDegreeArrays[$field][$i]) && $otherDegreeArrays[$field][$i] !== null && $otherDegreeArrays[$field][$i] !== '') {
                                $otherDegreeRow[$field] = $otherDegreeArrays[$field][$i];
                            }
                        }
                        if (!empty($otherDegreeRow)) {
                            $otherDegreeData[] = $otherDegreeRow;
                        }
                    }
                }
            }
            
            // Store other_degree data as JSON array
            $stepData['other_degrees'] = $otherDegreeData;
            
            // Remove individual other_degree fields from stepData (they're now in other_degrees array)
            $otherDegreeFields = ['other_degree', 'other_degree_university_name', 'other_degree_percentage', 'other_degree_passing_year', 'other_degree_trial'];
            foreach ($otherDegreeFields as $field) {
                unset($stepData[$field]);
            }
            
            // Safely get existing step 6 data first
            $existingStep6Data = [];
            if ($lead->step_6_data) {
                if (is_string($lead->step_6_data)) {
                    $decoded = json_decode($lead->step_6_data, true);
                    $existingStep6Data = is_array($decoded) ? $decoded : [];
                } elseif (is_array($lead->step_6_data)) {
                    $existingStep6Data = $lead->step_6_data;
                }
            }
            
            // Handle other degree file uploads first (with new naming convention: other_degree_result_file_1, etc.)
            $folder = 'lead-education-files';
            
            // Get existing other degrees data for file reference
            $existingOtherDegrees = [];
            if (isset($existingStep6Data['other_degrees']) && is_array($existingStep6Data['other_degrees'])) {
                $existingOtherDegrees = $existingStep6Data['other_degrees'];
            }
            
            // Process other degree files and update other_degrees array
            foreach ($otherDegreeData as $index => $degree) {
                $degreeIndex = $index + 1; // Degree index starts from 1
                
                // Handle other degree result file
                $resultFileKey = 'other_degree_result_file_' . $degreeIndex;
                $resultFileExistingKey = $resultFileKey . '_existing';
                
                if ($request->hasFile($resultFileKey)) {
                    try {
                        $file = $request->file($resultFileKey);
                        if ($file && $file->isValid()) {
                            // Delete old file if exists
                            if (isset($degree['other_degree_result_file']) && $degree['other_degree_result_file']) {
                                \App\Helper\Files::deleteFile($degree['other_degree_result_file'], $folder . '/' . $lead->id);
                            }
                            
                            $customFileName = \App\Helper\Files::generateFileNameWithOriginal($file->getClientOriginalName());
                            \App\Helper\Files::fileStore($file, $folder . '/' . $lead->id, $customFileName);
                            
                            $fileVisibility = [];
                            if (config('filesystems.default') == 'local') {
                                $fileVisibility = ['directory_visibility' => 'public', 'visibility' => 'public'];
                            }
                            
                            Storage::disk(config('filesystems.default'))->putFileAs($folder . '/' . $lead->id, $file, $customFileName, $fileVisibility);
                            $otherDegreeData[$index]['other_degree_result_file'] = $customFileName;
                        }
                    } catch (\Exception $e) {
                        // Preserve existing file if upload fails
                        if (isset($degree['other_degree_result_file']) && $degree['other_degree_result_file']) {
                            $otherDegreeData[$index]['other_degree_result_file'] = $degree['other_degree_result_file'];
                        }
                    }
                } elseif ($request->has($resultFileExistingKey)) {
                    // Keep existing file
                    $otherDegreeData[$index]['other_degree_result_file'] = $request->input($resultFileExistingKey);
                } elseif (isset($degree['other_degree_result_file']) && $degree['other_degree_result_file'] && 
                          (!isset($existingOtherDegrees[$index]) || !isset($existingOtherDegrees[$index]['other_degree_result_file']) || 
                           $existingOtherDegrees[$index]['other_degree_result_file'] !== $degree['other_degree_result_file'])) {
                    // File was removed, delete it
                    try {
                        \App\Helper\Files::deleteFile($degree['other_degree_result_file'], $folder . '/' . $lead->id);
                    } catch (\Exception $e) {
                        // Silently fail
                    }
                    $otherDegreeData[$index]['other_degree_result_file'] = null;
                }
            }
            
            // Update stepData with processed other_degrees array
            $stepData['other_degrees'] = $otherDegreeData;
            
            // Handle other step 6 file fields
            $educationFileFields = [
                'ielts_result_file',
                'tenth_result_file',
                'twelfth_result_file',
                'graduation_result_file',
                'post_graduation_result_file',
            ];
            
            foreach ($educationFileFields as $fileField) {
                if ($request->hasFile($fileField)) {
                    // Delete old file if exists
                    if (isset($existingStep6Data[$fileField]) && $existingStep6Data[$fileField]) {
                        $oldFileName = $existingStep6Data[$fileField];
                        \App\Helper\Files::deleteFile($oldFileName, $folder . '/' . $lead->id);
                    }
                    // Upload new file with original name + unique ID
                    try {
                        $file = $request->file($fileField);
                        if (is_array($file)) {
                            $file = !empty($file) ? $file[0] : null;
                        }
                        if ($file && $file->isValid()) {
                            $customFileName = \App\Helper\Files::generateFileNameWithOriginal($file->getClientOriginalName());
                            \App\Helper\Files::fileStore($file, $folder . '/' . $lead->id, $customFileName);
                            
                            $fileVisibility = [];
                            if (config('filesystems.default') == 'local') {
                                $fileVisibility = ['directory_visibility' => 'public', 'visibility' => 'public'];
                            }
                            
                            Storage::disk(config('filesystems.default'))->putFileAs($folder . '/' . $lead->id, $file, $customFileName, $fileVisibility);
                            $stepData[$fileField] = $customFileName;
                        }
                    } catch (\Exception $e) {
                        // Preserve existing file if upload fails
                        if (isset($existingStep6Data[$fileField]) && $existingStep6Data[$fileField]) {
                            $stepData[$fileField] = $existingStep6Data[$fileField];
                        }
                    }
                } elseif ($request->has($fileField . '_existing')) {
                    // Keep existing file if no new file is uploaded
                    $stepData[$fileField] = $request->input($fileField . '_existing');
                } elseif (isset($existingStep6Data[$fileField]) && $existingStep6Data[$fileField]) {
                    // If existing file input is not present, it means user removed it, so delete file
                    $oldFileName = $existingStep6Data[$fileField];
                    try {
                        \App\Helper\Files::deleteFile($oldFileName, $folder . '/' . $lead->id);
                    } catch (\Exception $e) {
                        // Silently fail
                    }
                    $stepData[$fileField] = null;
                }
            }
        }
        
        // Handle file uploads for step 7 (Professional Experience)
        if ($stepNumber == 7) {
            // Handle jobs data - check if sent as JSON string
            $jobData = [];
            
            if ($request->has('jobs')) {
                $jobsJson = $request->input('jobs');
                if (is_string($jobsJson)) {
                    $decoded = json_decode($jobsJson, true);
                    if (is_array($decoded)) {
                        $jobData = $decoded;
                    }
                } elseif (is_array($jobsJson)) {
                    $jobData = $jobsJson;
                }
            }
            
            // If no jobs from JSON, try old format (backward compatibility)
            if (empty($jobData)) {
                $jobFields = ['job_duration_from', 'job_duration_to', 'job_country', 'job_designation', 'job_company_name', 'job_salary'];
                
                // Get Job 1 data (single values)
                $job1Data = [];
                foreach ($jobFields as $field) {
                    $value = $this->getRequestValue($request, $field);
                    if ($value !== null && $value !== '') {
                        $job1Data[$field] = $value;
                    }
                }
                
                // If Job 1 has any data, add it to the array
                if (!empty($job1Data)) {
                    $jobData[] = $job1Data;
                }
                
                // Get Job 2+ data (arrays)
                $jobArrays = [];
                foreach ($jobFields as $field) {
                    $arrayKey = $field . '[]';
                    $arrayValues = $request->input($arrayKey, []);
                    if (is_array($arrayValues)) {
                        $jobArrays[$field] = $arrayValues;
                    }
                }
                
                // Combine Job 2+ data into proper structure
                if (!empty($jobArrays)) {
                    $maxCount = 0;
                    foreach ($jobArrays as $field => $values) {
                        if (count($values) > $maxCount) {
                            $maxCount = count($values);
                        }
                    }
                    
                    for ($i = 0; $i < $maxCount; $i++) {
                        $jobRow = [];
                        foreach ($jobFields as $field) {
                            if (isset($jobArrays[$field][$i]) && $jobArrays[$field][$i] !== null && $jobArrays[$field][$i] !== '') {
                                $jobRow[$field] = $jobArrays[$field][$i];
                            }
                        }
                        if (!empty($jobRow)) {
                            $jobData[] = $jobRow;
                        }
                    }
                }
            }
            
            // Store job data as JSON array
            $stepData['jobs'] = $jobData;
            
            // Remove individual job fields from stepData (they're now in jobs array)
            $jobFields = ['job_duration_from', 'job_duration_to', 'job_country', 'job_designation', 'job_company_name', 'job_salary'];
            foreach ($jobFields as $field) {
                unset($stepData[$field]);
            }
            
            // Safely get existing step 7 data first
            $existingStep7Data = [];
            if ($lead->step_7_data) {
                if (is_string($lead->step_7_data)) {
                    $decoded = json_decode($lead->step_7_data, true);
                    $existingStep7Data = is_array($decoded) ? $decoded : [];
                } elseif (is_array($lead->step_7_data)) {
                    $existingStep7Data = $lead->step_7_data;
                }
            }
            
            // Handle job file uploads first (with new naming convention: job_offer_letter_file_1, job_experience_letter_file_1, etc.)
            $folder = 'lead-job-files';
            
            // Get existing jobs data for file reference
            $existingJobs = [];
            if (isset($existingStep7Data['jobs']) && is_array($existingStep7Data['jobs'])) {
                $existingJobs = $existingStep7Data['jobs'];
            }
            
            // Process job files and update jobs array
            foreach ($jobData as $index => $job) {
                $jobIndex = $index + 1; // Job index starts from 1
                
                // Handle job offer letter file
                $offerFileKey = 'job_offer_letter_file_' . $jobIndex;
                $offerFileExistingKey = $offerFileKey . '_existing';
                
                if ($request->hasFile($offerFileKey)) {
                    try {
                        $file = $request->file($offerFileKey);
                        if ($file && $file->isValid()) {
                            // Delete old file if exists
                            if (isset($job['job_offer_letter_file']) && $job['job_offer_letter_file']) {
                                \App\Helper\Files::deleteFile($job['job_offer_letter_file'], $folder . '/' . $lead->id);
                            }
                            
                            $customFileName = \App\Helper\Files::generateFileNameWithOriginal($file->getClientOriginalName());
                            \App\Helper\Files::fileStore($file, $folder . '/' . $lead->id, $customFileName);
                            
                            $fileVisibility = [];
                            if (config('filesystems.default') == 'local') {
                                $fileVisibility = ['directory_visibility' => 'public', 'visibility' => 'public'];
                            }
                            
                            Storage::disk(config('filesystems.default'))->putFileAs($folder . '/' . $lead->id, $file, $customFileName, $fileVisibility);
                            $jobData[$index]['job_offer_letter_file'] = $customFileName;
                        }
                    } catch (\Exception $e) {
                        // Preserve existing file if upload fails
                        if (isset($job['job_offer_letter_file']) && $job['job_offer_letter_file']) {
                            $jobData[$index]['job_offer_letter_file'] = $job['job_offer_letter_file'];
                        }
                    }
                } elseif ($request->has($offerFileExistingKey)) {
                    // Keep existing file
                    $jobData[$index]['job_offer_letter_file'] = $request->input($offerFileExistingKey);
                } elseif (isset($job['job_offer_letter_file']) && $job['job_offer_letter_file'] && 
                          (!isset($existingJobs[$index]) || !isset($existingJobs[$index]['job_offer_letter_file']) || 
                           $existingJobs[$index]['job_offer_letter_file'] !== $job['job_offer_letter_file'])) {
                    // File was removed, delete it
                    try {
                        \App\Helper\Files::deleteFile($job['job_offer_letter_file'], $folder . '/' . $lead->id);
                    } catch (\Exception $e) {
                        // Silently fail
                    }
                    $jobData[$index]['job_offer_letter_file'] = null;
                }
                
                // Handle job experience letter file
                $experienceFileKey = 'job_experience_letter_file_' . $jobIndex;
                $experienceFileExistingKey = $experienceFileKey . '_existing';
                
                if ($request->hasFile($experienceFileKey)) {
                    try {
                        $file = $request->file($experienceFileKey);
                        if ($file && $file->isValid()) {
                            // Delete old file if exists
                            if (isset($job['job_experience_letter_file']) && $job['job_experience_letter_file']) {
                                \App\Helper\Files::deleteFile($job['job_experience_letter_file'], $folder . '/' . $lead->id);
                            }
                            
                            $customFileName = \App\Helper\Files::generateFileNameWithOriginal($file->getClientOriginalName());
                            \App\Helper\Files::fileStore($file, $folder . '/' . $lead->id, $customFileName);
                            
                            $fileVisibility = [];
                            if (config('filesystems.default') == 'local') {
                                $fileVisibility = ['directory_visibility' => 'public', 'visibility' => 'public'];
                            }
                            
                            Storage::disk(config('filesystems.default'))->putFileAs($folder . '/' . $lead->id, $file, $customFileName, $fileVisibility);
                            $jobData[$index]['job_experience_letter_file'] = $customFileName;
                        }
                    } catch (\Exception $e) {
                        // Preserve existing file if upload fails
                        if (isset($job['job_experience_letter_file']) && $job['job_experience_letter_file']) {
                            $jobData[$index]['job_experience_letter_file'] = $job['job_experience_letter_file'];
                        }
                    }
                } elseif ($request->has($experienceFileExistingKey)) {
                    // Keep existing file
                    $jobData[$index]['job_experience_letter_file'] = $request->input($experienceFileExistingKey);
                } elseif (isset($job['job_experience_letter_file']) && $job['job_experience_letter_file'] && 
                          (!isset($existingJobs[$index]) || !isset($existingJobs[$index]['job_experience_letter_file']) || 
                           $existingJobs[$index]['job_experience_letter_file'] !== $job['job_experience_letter_file'])) {
                    // File was removed, delete it
                    try {
                        \App\Helper\Files::deleteFile($job['job_experience_letter_file'], $folder . '/' . $lead->id);
                    } catch (\Exception $e) {
                        // Silently fail
                    }
                    $jobData[$index]['job_experience_letter_file'] = null;
                }
            }
            
            // Update stepData with processed jobs array
            $stepData['jobs'] = $jobData;
        }
        
        // Handle file uploads for step 8 (Property Details)
        if ($stepNumber == 8) {
            // Safely get existing step 8 data
            $existingStep8Data = [];
            if ($lead->step_8_data) {
                if (is_string($lead->step_8_data)) {
                    $decoded = json_decode($lead->step_8_data, true);
                    $existingStep8Data = is_array($decoded) ? $decoded : [];
                } elseif (is_array($lead->step_8_data)) {
                    $existingStep8Data = $lead->step_8_data;
                }
            }
            
            // Handle valuation_report_file similar to upload_resume
            $uploadedFile = null;
            $fileDetected = false;
            
            if ($request->hasFile('valuation_report_file')) {
                $uploadedFile = $request->file('valuation_report_file');
                $fileDetected = true;
            } elseif ($request->allFiles() && isset($request->allFiles()['valuation_report_file'])) {
                $uploadedFile = $request->allFiles()['valuation_report_file'];
                $fileDetected = true;
            } elseif ($request->file('valuation_report_file')) {
                $uploadedFile = $request->file('valuation_report_file');
                $fileDetected = true;
            }
            
            if ($fileDetected && $uploadedFile) {
                // Delete old file if exists
                if (isset($existingStep8Data['valuation_report_file']) && $existingStep8Data['valuation_report_file']) {
                    $oldFileName = $existingStep8Data['valuation_report_file'];
                    try {
                        \App\Helper\Files::deleteFile($oldFileName, 'lead-property-files/' . $lead->id);
                    } catch (\Exception $e) {
                        // Silently fail if delete fails
                    }
                }
                
                // Upload new file with original name + unique ID
                try {
                    $fileToUpload = $uploadedFile;
                    if (!$fileToUpload) {
                        $fileToUpload = $request->valuation_report_file;
                    }
                    
                    // Generate filename with original name + unique ID
                    $customFileName = \App\Helper\Files::generateFileNameWithOriginal($fileToUpload->getClientOriginalName());
                    \App\Helper\Files::fileStore($fileToUpload, 'lead-property-files/' . $lead->id, $customFileName);
                    
                    $fileVisibility = [];
                    if (config('filesystems.default') == 'local') {
                        $fileVisibility = ['directory_visibility' => 'public', 'visibility' => 'public'];
                    }
                    
                    Storage::disk(config('filesystems.default'))->putFileAs('lead-property-files/' . $lead->id, $fileToUpload, $customFileName, $fileVisibility);
                    
                    $stepData['valuation_report_file'] = $customFileName;
                } catch (\Exception $e) {
                    // Preserve existing file if upload fails
                    if (isset($existingStep8Data['valuation_report_file']) && $existingStep8Data['valuation_report_file']) {
                        $stepData['valuation_report_file'] = $existingStep8Data['valuation_report_file'];
                    }
                }
            } elseif ($request->has('valuation_report_file_existing')) {
                // Keep existing file if no new file is uploaded
                $stepData['valuation_report_file'] = $request->input('valuation_report_file_existing');
            } elseif (isset($existingStep8Data['valuation_report_file']) && $existingStep8Data['valuation_report_file']) {
                // Keep existing file if no new file is uploaded and no removal signal
                $stepData['valuation_report_file'] = $existingStep8Data['valuation_report_file'];
            } else {
                // No file uploaded and no existing file
                $stepData['valuation_report_file'] = null;
            }
        }
        
        // Handle file uploads for step 9 (Financial Status)
        if ($stepNumber == 9) {
            // Safely get existing step 9 data
            $existingStep9Data = [];
            if ($lead->step_9_data) {
                if (is_string($lead->step_9_data)) {
                    $decoded = json_decode($lead->step_9_data, true);
                    $existingStep9Data = is_array($decoded) ? $decoded : [];
                } elseif (is_array($lead->step_9_data)) {
                    $existingStep9Data = $lead->step_9_data;
                }
            }
            
            // Handle income document file uploads
            $fileFields = [
                'father_income_document_file',
                'mother_income_document_file',
                'candidate_income_document_file',
                'spouse_income_document_file',
            ];
            
            foreach ($fileFields as $fileField) {
                $uploadedFile = null;
                $fileDetected = false;
                
                if ($request->hasFile($fileField)) {
                    $uploadedFile = $request->file($fileField);
                    $fileDetected = true;
                } elseif ($request->allFiles() && isset($request->allFiles()[$fileField])) {
                    $uploadedFile = $request->allFiles()[$fileField];
                    $fileDetected = true;
                } elseif ($request->file($fileField)) {
                    $uploadedFile = $request->file($fileField);
                    $fileDetected = true;
                }
                
                if ($fileDetected && $uploadedFile) {
                    // Delete old file if exists
                    if (isset($existingStep9Data[$fileField]) && $existingStep9Data[$fileField]) {
                        $oldFileName = $existingStep9Data[$fileField];
                        try {
                            \App\Helper\Files::deleteFile($oldFileName, 'lead-income-documents/' . $lead->id);
                        } catch (\Exception $e) {
                            // Silently fail if delete fails
                        }
                    }
                    
                    // Upload new file with original name + unique ID
                    try {
                        $fileToUpload = $uploadedFile;
                        if (!$fileToUpload) {
                            $fileToUpload = $request->$fileField;
                        }
                        
                        // Generate filename with original name + unique ID
                        $customFileName = \App\Helper\Files::generateFileNameWithOriginal($fileToUpload->getClientOriginalName());
                        \App\Helper\Files::fileStore($fileToUpload, 'lead-income-documents/' . $lead->id, $customFileName);
                        
                        $fileVisibility = [];
                        if (config('filesystems.default') == 'local') {
                            $fileVisibility = ['directory_visibility' => 'public', 'visibility' => 'public'];
                        }
                        
                        Storage::disk(config('filesystems.default'))->putFileAs('lead-income-documents/' . $lead->id, $fileToUpload, $customFileName, $fileVisibility);
                        
                        $stepData[$fileField] = $customFileName;
                    } catch (\Exception $e) {
                        // Preserve existing file if upload fails
                        if (isset($existingStep9Data[$fileField]) && $existingStep9Data[$fileField]) {
                            $stepData[$fileField] = $existingStep9Data[$fileField];
                        }
                    }
                } elseif ($request->has($fileField . '_existing')) {
                    // Keep existing file if no new file is uploaded
                    $stepData[$fileField] = $request->input($fileField . '_existing');
                } elseif (isset($existingStep9Data[$fileField]) && $existingStep9Data[$fileField]) {
                    // Keep existing file if no new file is uploaded and no removal signal
                    $stepData[$fileField] = $existingStep9Data[$fileField];
                } else {
                    // No file uploaded and no existing file
                    $stepData[$fileField] = null;
                }
            }
        }
        
        // Ensure upload_resume is always in stepData for step 1 (even if null)
        // Use loose comparison since $stepNumber is a string "1" from route parameter
        if ($stepNumber == 1 && !array_key_exists('upload_resume', $stepData)) {
            $stepData['upload_resume'] = null;
        }
        
        // Store step data in separate column based on step number
        $stepField = 'step_' . $stepNumber . '_data';
        $lead->$stepField = $stepData;
        $lead->save();
    }

    /**
     * Log step completion
     */
    private function logStepCompletion($leadId, $stepNumber)
    {
        // Check if log already exists for this step
        $existingLog = LeadStepLog::where('lead_id', $leadId)
            ->where('step_number', $stepNumber)
            ->first();

        if ($existingLog) {
            $existingLog->status = 'completed';
            $existingLog->completed_at = now();
            $existingLog->completed_by = user()->id;
            $existingLog->save();
        } else {
            LeadStepLog::create([
                'lead_id' => $leadId,
                'step_number' => $stepNumber,
                'status' => 'completed',
                'completed_at' => now(),
                'completed_by' => user()->id,
            ]);
        }
    }

    /**
     * Send confirmation and notification emails when lead is completed
     */
    private function sendLeadEmails(NewLead $lead)
    {
        try {
            // Reload lead with relationships
            $lead->refresh();
            $lead->load(['addedBy', 'leadOwner', 'company']);

            \Log::info('Sending lead emails for lead ID: ' . $lead->id);
            \Log::info('Step 1 data: ' . json_encode($lead->step_1_data));
            \Log::info('Lead owner: ' . $lead->lead_owner);
            \Log::info('Client email: ' . $lead->client_email);

            // Get lead email from step 1 data (contact details)
            $leadEmail = null;
            if ($lead->step_1_data) {
                if (is_string($lead->step_1_data)) {
                    $step1Data = json_decode($lead->step_1_data, true);
                } else {
                    $step1Data = $lead->step_1_data;
                }
                
                if (is_array($step1Data)) {
                    $leadEmail = $step1Data['email_address'] ?? null;
                }
            }
            
            // Fallback to client_email if step_1_data email is not available
            if (empty($leadEmail)) {
                $leadEmail = $lead->client_email;
            }

            \Log::info('Lead email to send confirmation: ' . ($leadEmail ?? 'NOT FOUND'));

            // Get assigned user (lead owner) from lead_assign_to field
            $assignedUser = null;
            if ($lead->lead_owner) {
                $assignedUser = User::find($lead->lead_owner);
                \Log::info('Assigned user found: ' . ($assignedUser ? $assignedUser->email : 'NOT FOUND'));
            } else {
                \Log::warning('Lead owner is not set for lead ID: ' . $lead->id);
            }

            // Send confirmation email to lead's email address (from step 1)
            if ($leadEmail && filter_var($leadEmail, FILTER_VALIDATE_EMAIL)) {
                try {
                    \Log::info('Attempting to send confirmation email to: ' . $leadEmail);
                    Mail::to($leadEmail)->send(new LeadConfirmation($lead));
                    \Log::info('Confirmation email sent successfully to: ' . $leadEmail);
                } catch (\Exception $e) {
                    // Log error but don't fail the request
                    \Log::error('Failed to send lead confirmation email to ' . $leadEmail . ': ' . $e->getMessage());
                    \Log::error('Exception trace: ' . $e->getTraceAsString());
                }
            } else {
                \Log::warning('Lead email is invalid or not found: ' . ($leadEmail ?? 'NULL'));
            }

            // Send notification email to assigned user (lead owner/employee/admin)
            if ($assignedUser && $assignedUser->email) {
                try {
                    \Log::info('Attempting to send notification email to: ' . $assignedUser->email);
                    Mail::to($assignedUser->email)->send(new LeadCreatedNotification($lead));
                    \Log::info('Notification email sent successfully to: ' . $assignedUser->email);
                } catch (\Exception $e) {
                    // Log error but don't fail the request
                    \Log::error('Failed to send lead created notification email to ' . $assignedUser->email . ': ' . $e->getMessage());
                    \Log::error('Exception trace: ' . $e->getTraceAsString());
                }
            } else {
                \Log::warning('Assigned user email is invalid or not found');
            }
            
            // Send WhatsApp notification to lead
            $this->sendLeadAssignmentWhatsApp($lead, $assignedUser);
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send lead emails: ' . $e->getMessage());
            \Log::error('Exception trace: ' . $e->getTraceAsString());
        }
    }
    
    /**
     * Send WhatsApp notification when lead is assigned
     */
    private function sendLeadAssignmentWhatsApp(NewLead $lead, $assignedUser = null)
    {
        try {
            // Ensure company relationship is loaded
            if (!$lead->relationLoaded('company')) {
                $lead->load('company');
            }
            
            // Get lead phone number from step_1_data or mobile
            $leadPhone = null;
            if ($lead->step_1_data) {
                $step1Data = is_string($lead->step_1_data) ? json_decode($lead->step_1_data, true) : $lead->step_1_data;
                if (is_array($step1Data)) {
                    $leadPhone = $step1Data['primary_phone'] ?? $step1Data['mobile'] ?? null;
                }
            }
            
            // Fallback to mobile
            if (empty($leadPhone)) {
                $leadPhone = $lead->mobile;
            }

            if (empty($leadPhone)) {
                \Log::warning('Lead phone number is not available for WhatsApp notification. Lead ID: ' . $lead->id);
                return;
            }

            // Remove any non-numeric characters
            $leadPhone = preg_replace('/[^0-9]/', '', $leadPhone);
            // Remove leading 0 if present
            $leadPhone = ltrim($leadPhone, '0');
            // Don't add country code - send as is (10 digits)

            // Get lead name
            $leadName = $lead->client_name ?? 'Client';
            if ($lead->step_1_data) {
                $step1Data = is_string($lead->step_1_data) ? json_decode($lead->step_1_data, true) : $lead->step_1_data;
                if (is_array($step1Data)) {
                    $givenName = $step1Data['given_name'] ?? '';
                    $surname = $step1Data['surname'] ?? '';
                    if ($givenName || $surname) {
                        $leadName = trim($givenName . ' ' . $surname) ?: $leadName;
                    }
                }
            }

            // Get consultant details
            $consultantName = 'Our Team';
            $consultantNumber = '';
            
            if ($assignedUser) {
                $consultantName = $assignedUser->name ?? 'Our Team';
                // Get consultant phone number - check if user has mobile field
                $consultantNumber = $assignedUser->mobile ?? $assignedUser->phone ?? '';
                // Format consultant number - keep only 10 digits (no country code)
                if ($consultantNumber) {
                    $consultantNumber = preg_replace('/[^0-9]/', '', $consultantNumber);
                    $consultantNumber = ltrim($consultantNumber, '0');
                    // Remove country code if present (keep only last 10 digits)
                    if (strlen($consultantNumber) > 10) {
                        $consultantNumber = substr($consultantNumber, -10);
                    }
                }
            }

            // API configuration
            $apiKey = env('AISENSY_API_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjY4YzdmM2RjNmZhOGUxMDEzYzdlMDgzZSIsIm5hbWUiOiJSLlIgcGF0ZWwgIG5ldyIsImFwcE5hbWUiOiJBaVNlbnN5IiwiY2xpZW50SWQiOiI2ODcyMzU5ZGRlNjFiYjMxOTgzMzc2NDMiLCJhY3RpdmVQbGFuIjoiQkFTSUNfTU9OVEhMWSIsImlhdCI6MTc2MDM1NTc4OX0.6H8mv7r3R0ucc7APyDM1q0xew4-oBUVKqUHA38klVG4');
            $campaignName = env('AISENSY_REGISTRATION_CAMPAIGN', 'order_confirm101');
            $userName = env('AISENSY_USER_NAME', 'R.R patel  new');
            $source = env('AISENSY_SOURCE', 'new-landing-page form');

            // Prepare template parameters
            // Template variables: {{name}}, {{consultant_name}}, {{consultant_number}}
            $templateParams = [
                $leadName,
                $consultantName,
                $consultantNumber ?: 'N/A'
            ];


            // Prepare API request payload - matching exact Postman working format
            $payload = [
                'apiKey' => $apiKey,
                'campaignName' => $campaignName,
                'destination' => $leadPhone,
                'userName' => $userName,
                'templateParams' => $templateParams,
                'source' => $source,
                'media' => [],
                'buttons' => [],
                'carouselCards' => [],
                'location' => (object)[],
                'attributes' => (object)[],
                'paramsFallbackValue' => (object)[]
            ];

            // Make API call to AISensy
            $client = new Client();
            $response = $client->post('https://backend.aisensy.com/campaign/t1/api/v2', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
                'timeout' => 30
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);

            // Check both status code and response body for success
            if ($response->getStatusCode() === 200) {
                // Check if response indicates success (some APIs return 200 with error in body)
                if (isset($responseBody['status']) && $responseBody['status'] === 'success') {
                    \Log::info('WhatsApp notification sent successfully to lead. Lead ID: ' . $lead->id . ', Phone: ' . $leadPhone);
                } elseif (isset($responseBody['message']) && strpos(strtolower($responseBody['message']), 'error') !== false) {
                    \Log::error('AISensy API error in response body for lead assignment WhatsApp: ' . json_encode($responseBody));
                } else {
                    // Log full response for debugging
                    \Log::info('WhatsApp notification response for lead assignment: ' . json_encode($responseBody));
                    \Log::info('WhatsApp notification sent successfully to lead. Lead ID: ' . $lead->id . ', Phone: ' . $leadPhone);
                }
            } else {
                \Log::error('AISensy API error for lead assignment WhatsApp: Status ' . $response->getStatusCode() . ', Response: ' . json_encode($responseBody));
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errorMessage = $e->getMessage();
            $errorResponse = null;
            try {
                $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);
                if (is_array($errorResponse) && isset($errorResponse['message'])) {
                    $errorMessage = $errorResponse['message'];
                }
            } catch (\Exception $ex) {
                // If we can't parse the error response, use the original message
            }
            \Log::error('AISensy API client error for lead assignment WhatsApp: ' . $e->getMessage());
            if ($errorResponse) {
                \Log::error('AISensy API error response body: ' . json_encode($errorResponse));
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            \Log::error('AISensy API request error for lead assignment WhatsApp: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send lead assignment WhatsApp notification: ' . $e->getMessage());
            \Log::error('Exception trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * Get lead step status
     */
    public function getLeadStepStatus($id)
    {
        try {
            $lead = NewLead::with(['stepStatus', 'stepLogs'])->find($id);
            
            if (!$lead) {
                return Reply::error(__('messages.recordNotFound'));
            }
            
            $stepStatus = $lead->stepStatus;
            
            // If step status doesn't exist, create it
            if (!$stepStatus) {
                $stepStatus = LeadStepStatus::getOrCreateForLead($id);
            }
            
            return Reply::dataOnly([
                'lead_id' => $id,
                'lead' => [
                    'client_name' => $lead->client_name,
                    'client_email' => $lead->client_email,
                    'mobile' => $lead->mobile,
                    'lead_source' => $lead->lead_source,
                    'lead_owner' => $lead->lead_owner,
                ],
                'step_data' => [
                    'step_1_data' => $lead->step_1_data ?? null,
                    'step_2_data' => $lead->step_2_data ?? null,
                    'step_3_data' => $lead->step_3_data ?? null,
                    'step_4_data' => $lead->step_4_data ?? null,
                    'step_5_data' => $lead->step_5_data ?? null,
                    'step_6_data' => $lead->step_6_data ?? null,
                    'step_7_data' => $lead->step_7_data ?? null,
                    'step_8_data' => $lead->step_8_data ?? null,
                    'step_9_data' => $lead->step_9_data ?? null,
                ],
                'step_status' => [
                    'step_1_completed' => $stepStatus->step_1_completed ?? false,
                    'step_2_completed' => $stepStatus->step_2_completed ?? false,
                    'step_3_completed' => $stepStatus->step_3_completed ?? false,
                    'step_4_completed' => $stepStatus->step_4_completed ?? false,
                    'step_5_completed' => $stepStatus->step_5_completed ?? false,
                    'step_6_completed' => $stepStatus->step_6_completed ?? false,
                    'step_7_completed' => $stepStatus->step_7_completed ?? false,
                    'step_8_completed' => $stepStatus->step_8_completed ?? false,
                    'step_9_completed' => $stepStatus->step_9_completed ?? false,
                    'final_status' => $stepStatus->final_status ?? 'draft',
                ],
                'step_logs' => $lead->stepLogs ?? [],
            ]);
        } catch (\Exception $e) {
            return Reply::error($e->getMessage());
        }
    }

    /**
     * Move lead to assigned user and mark as complete
     */
    public function moveToLead(Request $request)
    {
        try {
            $leadId = $request->lead_id;
            $leadAssignTo = $request->lead_assign_to;

            if (!$leadId) {
                return Reply::error('Lead ID is required.');
            }

            if (!$leadAssignTo) {
                return Reply::error('Please select a user to assign the lead.');
            }

            $lead = NewLead::findOrFail($leadId);
            $this->editPermission = user()->permission('edit_lead');

            abort_403(!($this->editPermission == 'all'
                || ($this->editPermission == 'added' && $lead->added_by == user()->id)
                || ($this->editPermission == 'owned' && $lead->lead_owner == user()->id)
                || ($this->editPermission == 'both' && ($lead->added_by == user()->id || $lead->lead_owner == user()->id))
            ));

            // Get or create step status before updating
            $stepStatus = LeadStepStatus::getOrCreateForLead($leadId);
            
            // Check if status was already complete (to avoid sending duplicate lead emails)
            $wasAlreadyComplete = $stepStatus->final_status === 'complete';
            
            // Check if lead owner is being changed
            $oldLeadOwner = $lead->lead_owner;
            $isNewAssignment = ($oldLeadOwner != $leadAssignTo);

            // Update lead owner
            $lead->lead_owner = $leadAssignTo;
            $lead->save();

            // Set final status to complete
            $stepStatus->final_status = 'complete';
            $stepStatus->save();

            // Send emails only if status was not already complete (to avoid duplicate lead emails)
            // If status was already complete but consultant is being assigned/changed, only send consultant email
            if (!$wasAlreadyComplete) {
                // First time completing - send both emails
                $this->sendLeadEmails($lead);
            } elseif ($isNewAssignment) {
                // Status already complete, but consultant is being assigned/changed - only send consultant email
                $assignedUser = User::find($leadAssignTo);
                if ($assignedUser && $assignedUser->email) {
                    try {
                        Mail::to($assignedUser->email)->send(new LeadCreatedNotification($lead));
                    } catch (\Exception $e) {
                        \Log::error('Failed to send consultant notification email: ' . $e->getMessage());
                    }
                }
                // Send WhatsApp notification when consultant is assigned
                $this->sendLeadAssignmentWhatsApp($lead, $assignedUser);
            }

            // Redirect to lead list or lead details
            $redirectUrl = route('lead-list.index');

            return Reply::successWithData('Lead moved successfully!', [
                'redirect_url' => $redirectUrl,
            ]);
        } catch (\Exception $e) {
            return Reply::error($e->getMessage());
        }
    }

    /**
     * Download assessment letter file
     */
    public function downloadAssessmentLetter($leadId, $fileName)
    {
        $lead = NewLead::findOrFail($leadId);
        
        // Verify the file belongs to this lead
        if (!isset($lead->step_2_data['pr_assessment_letter_file']) || 
            $lead->step_2_data['pr_assessment_letter_file'] !== $fileName) {
            abort(404);
        }
        
        $filePath = 'lead-assessment-letters/' . $leadId . '/' . $fileName;
        
        // Use the download_local_s3 helper function
        $file = (object) [
            'filename' => $fileName,
            'name' => $fileName,
        ];
        
        return download_local_s3($file, $filePath);
    }
    
    /**
     * Download lead document
     */
    public function downloadLeadDocument($leadId, $documentKey, $applicantType = null, $childIndex = null)
    {
        try {
            $lead = NewLead::findOrFail($leadId);
            
            // Try new JSON structure first
            $leadDocument = \App\Models\NewLeadDocument::where('lead_id', $leadId)->first();
            
            if ($leadDocument) {
                // Determine applicant type if not provided (for backward compatibility)
                if (!$applicantType) {
                    // Try to determine from document key or default to main_applicant
                    $applicantType = 'main_applicant';
                }
                
                $fileUrl = null;
                
                // documentKey can be either document_master_id (new format) or old name-based key (backward compatibility)
                $isNumericKey = is_numeric($documentKey);
                $docIdStr = $isNumericKey ? (string)$documentKey : null;
                
                if ($applicantType === 'child' && $childIndex) {
                    // Get from children_documents
                    $childrenDocs = $leadDocument->children_documents ?? [];
                    foreach ($childrenDocs as $entry) {
                        if (isset($entry['child_index']) && $entry['child_index'] == $childIndex) {
                            $childDocuments = $entry['documents'] ?? [];
                            // Convert old format if needed
                            $childDocuments = $this->convertDocumentKeysToIds($childDocuments, false);
                            
                            if ($isNumericKey && isset($childDocuments[$docIdStr]['file_url'])) {
                                $fileUrl = $childDocuments[$docIdStr]['file_url'];
                            } elseif (!$isNumericKey && isset($childDocuments[$documentKey]['file_url'])) {
                                // Old format fallback
                                $fileUrl = $childDocuments[$documentKey]['file_url'];
                            }
                            break;
                        }
                    }
                } else {
                    // Get from main_applicant, father, mother, or spouse documents
                    $columnName = $this->getDocumentColumnName($applicantType);
                    $documents = $leadDocument->$columnName ?? [];
                    // Convert old format if needed
                    $documents = $this->convertDocumentKeysToIds($documents, $applicantType === 'main_applicant');
                    
                    if ($isNumericKey && isset($documents[$docIdStr]['file_url'])) {
                        $fileUrl = $documents[$docIdStr]['file_url'];
                    } elseif (!$isNumericKey && isset($documents[$documentKey]['file_url'])) {
                        // Old format fallback
                        $fileUrl = $documents[$documentKey]['file_url'];
                    }
                }
                
                if ($fileUrl) {
                    return redirect($fileUrl);
                }
            }
            
            // Fallback to old step data structure for backward compatibility
            $documentConfig = $this->getDocumentConfig($documentKey, $lead);
            
            if (!$documentConfig) {
                abort(404, 'Invalid document key: ' . $documentKey);
            }
            
            // Get step data
            $stepData = $this->getStepDataArray($lead, $documentConfig['step']);
            
            // Get file name
            $fileName = $this->getDocumentFileName($stepData, $documentKey, $documentConfig);
            
            if (!$fileName) {
                abort(404, 'Document file not found for key: ' . $documentKey);
            }
            
            // Verify the file belongs to this lead by checking step data
            // Step 1 resume files don't have lead ID subfolder
            if (!empty($documentConfig['no_lead_id'])) {
                $filePath = $documentConfig['folder'] . '/' . $fileName;
            } else {
                $filePath = $documentConfig['folder'] . '/' . $leadId . '/' . $fileName;
            }
            
            // Check if file exists (for local storage)
            if (config('filesystems.default') == 'local') {
                $fullPath = public_path(\App\Helper\Files::UPLOAD_FOLDER . '/' . $filePath);
                if (!file_exists($fullPath)) {
                    \Log::error('File not found at path: ' . $fullPath);
                    abort(404, 'File not found: ' . $fileName);
                }
            }
            
            // Return redirect to file URL for viewing in browser
            $fileUrl = asset_url_local_s3($filePath);
            return redirect($fileUrl);
        } catch (\Exception $e) {
            \Log::error('Error downloading lead document: ' . $e->getMessage());
            \Log::error('Lead ID: ' . $leadId . ', Document Key: ' . $documentKey);
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            abort(404, 'Document not found: ' . $e->getMessage());
        }
    }
    
    /**
     * View process document
     */
    public function viewProcessDocument($leadId, $documentField)
    {
        try {
            $lead = NewLead::findOrFail($leadId);
            $process = $lead->process;
            
            if (!$process) {
                abort(404, 'Process data not found');
            }
            
            // Valid document fields
            $validFields = ['contract_letter', 'grant_letter', 'offer_letter', 'medical_letter', 'air_ticket', 'accommodation_letter'];
            
            if (!in_array($documentField, $validFields)) {
                abort(404, 'Invalid document field');
            }
            
            $fileName = $process->$documentField;
            
            if (!$fileName) {
                abort(404, 'Document not found');
            }
            
            \Log::info('Process document request - Lead ID: ' . $leadId . ', Field: ' . $documentField . ', FileName: ' . $fileName);
            
            // Files are stored using storeAs('public', $fileName), so they're in storage/app/public/
            // The fileName stored in DB is like: process_documents/6/contract_letter_1234567890.pdf
            // Check if file exists in public storage
            if (!\Storage::disk('public')->exists($fileName)) {
                \Log::error('Process document not found in storage: ' . $fileName);
                \Log::error('Storage path: ' . storage_path('app/public/' . $fileName));
                \Log::error('Full path: ' . \Storage::disk('public')->path($fileName));
                abort(404, 'File not found: ' . $fileName);
            }
            
            // Files are stored in public/user-uploads/public/process_documents/{leadId}/{filename}
            // The fileName stored in DB might be: process_documents/5/contract_letter_1234567890.pdf
            // Or: public/process_documents/5/contract_letter_1234567890.pdf
            
            // Ensure fileName is properly formatted
            $fileName = ltrim($fileName, '/');
            
            // Check if fileName already includes 'public/'
            if (strpos($fileName, 'public/') === 0) {
                // Already has public/ prefix, use as is
                $filePath = $fileName;
            } else {
                // Add public/ prefix
                $filePath = 'public/' . $fileName;
            }
            
            // Use asset_url_local_s3 which handles user-uploads path correctly
            // asset_url_local_s3 adds 'user-uploads/' prefix automatically
            $fileUrl = asset_url_local_s3($filePath);
            
            \Log::info('Generated file URL: ' . $fileUrl);
            \Log::info('FileName from DB: ' . $fileName);
            \Log::info('File path used: ' . $filePath);
            
            // If using S3 or other storage, use the configured default disk
            if (in_array(config('filesystems.default'), \App\Models\StorageSetting::S3_COMPATIBLE_STORAGE)) {
                // For S3, use the default disk which should be configured
                $fileUrl = \Storage::disk(config('filesystems.default'))->url($filePath);
            }
            
            return redirect($fileUrl);
        } catch (\Exception $e) {
            \Log::error('Error viewing process document: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Lead ID: ' . $leadId . ', Document Field: ' . $documentField);
            abort(404, 'Document not found: ' . $e->getMessage());
        }
    }
    
    /**
     * Get documents tab content for AJAX refresh
     */
    public function getNewLeadDocumentsTab($leadId)
    {
        try {
            $lead = NewLead::with(['addedBy', 'leadOwner', 'followUps.addedBy', 'followUps.lastUpdatedBy', 'fileNotes.addedBy', 'process', 'accounts.agentUser', 'accounts.addedBy', 'travelDetails'])->findOrFail($leadId);
            
            $this->lead = $lead;
            $this->data['lead'] = $lead;
            
            // Load document checklists and family details for new documents tab
            $this->data['documentChecklists'] = $this->getDocumentChecklists($lead);
            $this->data['familyDetails'] = $this->getFamilyDetails($lead);
            
            // Get only the documents content (without the tab wrapper)
            $html = view('lead-details.components.documents-content', $this->data)->render();
            
            return Reply::dataOnly(['status' => 'success', 'data' => ['html' => $html]]);
        } catch (\Exception $e) {
            \Log::error('Error in getNewLeadDocumentsTab: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return Reply::error('Failed to load documents: ' . $e->getMessage());
        }
    }
    
    /**
     * Get process tab content for AJAX refresh
     */
    public function getNewLeadProcessTab($leadId)
    {
        try {
            $lead = NewLead::with(['process'])->findOrFail($leadId);
            
            $this->lead = $lead;
            $this->data['lead'] = $lead;
            
            // Load visa types and subclasses for process tab
            $this->data['visaTypes'] = \App\Models\NewLeadVisaType::where(function($query) {
                $query->where('company_id', company()->id)
                      ->orWhereNull('company_id');
            })->orderBy('name')->get();
            
            $this->data['subclasses'] = \App\Models\NewLeadSubclass::with('visaType')
                ->where(function($query) {
                    $query->where('company_id', company()->id)
                          ->orWhereNull('company_id');
                })
                ->orderBy('name')
                ->get();
            
            $html = view('lead-details.components.process-tab', $this->data)->render();
            
            return Reply::dataOnly(['status' => 'success', 'data' => ['html' => $html]]);
        } catch (\Exception $e) {
            \Log::error('Error in getNewLeadProcessTab: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return Reply::error('Failed to load process tab: ' . $e->getMessage());
        }
    }

    /**
     * Store new lead follow-up
     */
    public function storeNewLeadFollowUp(Request $request)
    {
        $newLead = NewLead::findOrFail($request->new_lead_id);
        
        // Check if lead is draft - don't allow follow-up for draft leads
        $isDraft = false;
        if ($newLead->stepStatus && $newLead->stepStatus->final_status == 'draft') {
            $isDraft = true;
        }
        
        if ($isDraft) {
            return Reply::error('Cannot add follow-up for draft leads. Please complete the lead first.');
        }
        
        $rules = [
            'new_lead_id' => 'required|exists:new_leads,id',
            'follow_up_type' => 'required|in:call,meeting,sms,email',
            'subject' => 'nullable|string|max:255',
            'outcome' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'next_follow_up_date' => 'nullable|date|after_or_equal:' . now(company()->timezone)->format('Y-m-d'),
            'next_follow_up_time' => 'nullable|string',
            'send_reminder' => 'nullable|in:yes,no',
            'remind_time' => 'nullable|string',
        ];
        
        // Make follow_up_subject_line required if send_reminder is checked
        if ($request->send_reminder == 'yes') {
            $rules['follow_up_subject_line'] = 'required|string|max:255';
        } else {
            $rules['follow_up_subject_line'] = 'nullable|string|max:255';
        }
        
        $request->validate($rules);

        // Combine date and time if both are provided
        $nextFollowUpDate = null;
        $now = now(company()->timezone);
        
        if ($request->next_follow_up_date && $request->next_follow_up_time) {
            try {
                // Parse date first
                $dateObj = \Carbon\Carbon::createFromFormat(
                    company()->date_format,
                    $request->next_follow_up_date
                )->setTimezone(company()->timezone);
                
                // Parse time - try multiple formats
                $timeStr = trim($request->next_follow_up_time);
                $timeFormats = [
                    company()->time_format, // Try company format first
                    'h:i A', // 12-hour with uppercase AM/PM
                    'h:i a', // 12-hour with lowercase am/pm
                    'H:i',   // 24-hour format
                    'g:i A', // 12-hour without leading zero
                    'g:i a', // 12-hour without leading zero lowercase
                ];
                
                $timeParsed = false;
                $timeObj = null;
                
                foreach ($timeFormats as $format) {
                    try {
                        $timeObj = \Carbon\Carbon::createFromFormat($format, $timeStr);
                        $timeParsed = true;
                        break;
                    } catch (\Exception $e) {
                        continue;
                    }
                }
                
                if (!$timeParsed) {
                    // If all formats fail, try to parse as standard time
                    try {
                        $timeObj = \Carbon\Carbon::parse($timeStr);
                        $timeParsed = true;
                    } catch (\Exception $e) {
                        // If still fails, use start of day
                        $timeObj = \Carbon\Carbon::now()->startOfDay();
                    }
                }
                
                // Combine date and time
                $dateTime = $dateObj->setTime($timeObj->hour, $timeObj->minute, 0);
                
                // Validate that the datetime is in the future
                if ($dateTime->lte($now)) {
                    return Reply::error('Next Follow Up Date and Time must be in the future.');
                }
                
                $nextFollowUpDate = $dateTime->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                // If parsing fails, try with just date
                if ($request->next_follow_up_date) {
                    try {
                        $dateTime = \Carbon\Carbon::createFromFormat(
                            company()->date_format,
                            $request->next_follow_up_date
                        )->setTimezone(company()->timezone)->startOfDay();
                        
                        // If only date is provided and it's today, it's valid (time can be set later)
                        // But if it's in the past, reject it
                        if ($dateTime->lt($now->startOfDay())) {
                            return Reply::error('Next Follow Up Date must be today or in the future.');
                        }
                        
                        $nextFollowUpDate = $dateTime->format('Y-m-d H:i:s');
                    } catch (\Exception $e2) {
                        return Reply::error('Invalid date format: ' . $e2->getMessage());
                    }
                } else {
                    return Reply::error('Invalid date or time format: ' . $e->getMessage());
                }
            }
        } elseif ($request->next_follow_up_date) {
            // Only date provided - must be today or future
            try {
                $dateTime = \Carbon\Carbon::createFromFormat(
                    company()->date_format,
                    $request->next_follow_up_date
                )->setTimezone(company()->timezone)->startOfDay();
                
                // Validate that the date is today or in the future
                if ($dateTime->lt($now->startOfDay())) {
                    return Reply::error('Next Follow Up Date must be today or in the future.');
                }
                
                $nextFollowUpDate = $dateTime->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                return Reply::error('Invalid date format.');
            }
        }

        $followUp = new \App\Models\NewLeadFollowUp();
        $followUp->new_lead_id = $request->new_lead_id;
        $followUp->follow_up_type = $request->follow_up_type;
        $followUp->subject = $request->subject;
        $followUp->outcome = $request->outcome;
        $followUp->notes = $request->notes;
        $followUp->next_follow_up_date = $nextFollowUpDate;
        $followUp->send_reminder = $request->send_reminder ?? 'no';
        $followUp->remind_time = $request->remind_time;
        $followUp->follow_up_subject_line = $request->follow_up_subject_line;
        $followUp->status = 'pending';
        $followUp->added_by = user()->id;
        $followUp->save();

        // Fire notification event if reminder is enabled
        if ($followUp->send_reminder == 'yes') {
            event(new \App\Events\NewLeadFollowUpReminderEvent($followUp, true));
        }

        return Reply::success(__('messages.recordSaved'));
    }

    /**
     * Get follow-ups for a new lead
     */
    public function getNewLeadFollowUps($leadId)
    {
        $dataTable = new \App\DataTables\NewLeadFollowUpDataTable();
        $dataTable->getAjaxUrl = route('new-leads.follow-ups', $leadId);
        return $dataTable->render('lead-list.follow-ups', ['leadId' => $leadId]);
    }

    /**
     * Update follow-up status
     */
    public function updateNewLeadFollowUpStatus(Request $request)
    {
        $request->validate([
            'followup_id' => 'required|exists:new_lead_follow_up,id',
            'status' => 'required|in:pending,completed,canceled',
        ]);

        $followUp = \App\Models\NewLeadFollowUp::findOrFail($request->followup_id);
        $followUp->status = $request->status;
        $followUp->last_updated_by = user()->id;
        $followUp->save();

        return Reply::success(__('messages.updateSuccess'));
    }

    /**
     * Get follow-up for editing
     */
    public function editNewLeadFollowUp($id)
    {
        $followUp = \App\Models\NewLeadFollowUp::findOrFail($id);
        
        // Check permissions
        $editPermission = user()->permission('edit_lead_follow_up');
        abort_403(!($editPermission == 'all' || ($editPermission == 'added' && $followUp->added_by == user()->id)));
        
        return Reply::dataOnly([
            'status' => 'success',
            'follow_up' => [
                'id' => $followUp->id,
                'new_lead_id' => $followUp->new_lead_id,
                'follow_up_type' => $followUp->follow_up_type,
                'subject' => $followUp->subject,
                'outcome' => $followUp->outcome,
                'notes' => $followUp->notes,
                'next_follow_up_date' => $followUp->next_follow_up_date ? $followUp->next_follow_up_date->format(company()->date_format) : '',
                'next_follow_up_time' => $followUp->next_follow_up_date ? $followUp->next_follow_up_date->format(company()->time_format) : '',
                'send_reminder' => $followUp->send_reminder,
                'remind_time' => $followUp->remind_time,
                'follow_up_subject_line' => $followUp->follow_up_subject_line,
            ]
        ]);
    }

    /**
     * Update follow-up
     */
    public function updateNewLeadFollowUp(Request $request)
    {
        $followUp = \App\Models\NewLeadFollowUp::findOrFail($request->id);
        $newLead = NewLead::findOrFail($request->new_lead_id);
        
        // Check if lead is draft - don't allow follow-up update for draft leads
        $isDraft = false;
        if ($newLead->stepStatus && $newLead->stepStatus->final_status == 'draft') {
            $isDraft = true;
        }
        
        if ($isDraft) {
            return Reply::error('Cannot update follow-up for draft leads. Please complete the lead first.');
        }
        
        // Check permissions
        $editPermission = user()->permission('edit_lead_follow_up');
        abort_403(!($editPermission == 'all' || ($editPermission == 'added' && $followUp->added_by == user()->id)));
        
        $rules = [
            'id' => 'required|exists:new_lead_follow_up,id',
            'new_lead_id' => 'required|exists:new_leads,id',
            'follow_up_type' => 'required|in:call,meeting,sms,email',
            'subject' => 'nullable|string|max:255',
            'outcome' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'next_follow_up_date' => 'nullable|date|after_or_equal:' . now(company()->timezone)->format('Y-m-d'),
            'next_follow_up_time' => 'nullable|string',
            'send_reminder' => 'nullable|in:yes,no',
            'remind_time' => 'nullable|string',
        ];
        
        // Make follow_up_subject_line required if send_reminder is checked
        if ($request->send_reminder == 'yes') {
            $rules['follow_up_subject_line'] = 'required|string|max:255';
        } else {
            $rules['follow_up_subject_line'] = 'nullable|string|max:255';
        }
        
        $request->validate($rules);

        $now = now(company()->timezone);
        
        // Combine date and time if both are provided
        $nextFollowUpDate = null;
        if ($request->next_follow_up_date && $request->next_follow_up_time) {
            try {
                // Parse date first
                $dateObj = \Carbon\Carbon::createFromFormat(
                    company()->date_format,
                    $request->next_follow_up_date
                )->setTimezone(company()->timezone);
                
                // Parse time - try multiple formats
                $timeStr = trim($request->next_follow_up_time);
                $timeFormats = [
                    company()->time_format, // Try company format first
                    'h:i A', // 12-hour with uppercase AM/PM
                    'h:i a', // 12-hour with lowercase am/pm
                    'H:i',   // 24-hour format
                    'g:i A', // 12-hour without leading zero
                    'g:i a', // 12-hour without leading zero lowercase
                ];
                
                $timeParsed = false;
                $timeObj = null;
                
                foreach ($timeFormats as $format) {
                    try {
                        $timeObj = \Carbon\Carbon::createFromFormat($format, $timeStr);
                        $timeParsed = true;
                        break;
                    } catch (\Exception $e) {
                        continue;
                    }
                }
                
                if (!$timeParsed) {
                    // If all formats fail, try to parse as standard time
                    try {
                        $timeObj = \Carbon\Carbon::parse($timeStr);
                        $timeParsed = true;
                    } catch (\Exception $e) {
                        // If still fails, use start of day
                        $timeObj = \Carbon\Carbon::now()->startOfDay();
                    }
                }
                
                // Combine date and time
                $dateTime = $dateObj->setTime($timeObj->hour, $timeObj->minute, 0);
                
                // Validate that the datetime is in the future
                if ($dateTime->lte($now)) {
                    return Reply::error('Next Follow Up Date and Time must be in the future.');
                }
                
                $nextFollowUpDate = $dateTime->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                if ($request->next_follow_up_date) {
                    try {
                        $dateTime = \Carbon\Carbon::createFromFormat(
                            company()->date_format,
                            $request->next_follow_up_date
                        )->setTimezone(company()->timezone)->startOfDay();
                        
                        if ($dateTime->lt($now->startOfDay())) {
                            return Reply::error('Next Follow Up Date must be today or in the future.');
                        }
                        
                        $nextFollowUpDate = $dateTime->format('Y-m-d H:i:s');
                    } catch (\Exception $e2) {
                        return Reply::error('Invalid date format: ' . $e2->getMessage());
                    }
                } else {
                    return Reply::error('Invalid date or time format: ' . $e->getMessage());
                }
            }
        } elseif ($request->next_follow_up_date) {
            try {
                $dateTime = \Carbon\Carbon::createFromFormat(
                    company()->date_format,
                    $request->next_follow_up_date
                )->setTimezone(company()->timezone)->startOfDay();
                
                if ($dateTime->lt($now->startOfDay())) {
                    return Reply::error('Next Follow Up Date must be today or in the future.');
                }
                
                $nextFollowUpDate = $dateTime->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                return Reply::error('Invalid date format.');
            }
        }

        $followUp->follow_up_type = $request->follow_up_type;
        $followUp->subject = $request->subject;
        $followUp->outcome = $request->outcome;
        $followUp->notes = $request->notes;
        $followUp->next_follow_up_date = $nextFollowUpDate;
        $followUp->send_reminder = $request->send_reminder ?? 'no';
        $followUp->remind_time = $request->remind_time;
        $followUp->follow_up_subject_line = $request->follow_up_subject_line;
        $followUp->last_updated_by = user()->id;
        $followUp->save();

        // Fire notification event if reminder is enabled
        if ($followUp->send_reminder == 'yes') {
            event(new \App\Events\NewLeadFollowUpReminderEvent($followUp, true));
        }

        return Reply::success(__('messages.updateSuccess'));
    }

    /**
     * Get follow-up list for a lead via AJAX
     */
    public function getNewLeadFollowUpList($id)
    {
        $lead = NewLead::with(['followUps.addedBy', 'followUps.lastUpdatedBy'])->findOrFail($id);
        
        $this->lead = $lead;
        
        $html = view('lead-details.components.follow-up-list', $this->data)->render();
        
        return Reply::dataOnly(['status' => 'success', 'data' => ['html' => $html]]);
    }

    /**
     * Delete follow-up
     */
    public function deleteNewLeadFollowUp($id)
    {
        $followUp = \App\Models\NewLeadFollowUp::findOrFail($id);
        $followUp->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }

    /**
     * Store new lead file note
     */
    public function storeNewLeadFileNote(Request $request)
    {
        $newLead = NewLead::findOrFail($request->new_lead_id);
        
        $rules = [
            'new_lead_id' => 'required|exists:new_leads,id',
            'note' => 'required|string',
        ];
        
        $request->validate($rules);

        $fileNote = new \App\Models\NewLeadFileNote();
        $fileNote->new_lead_id = $request->new_lead_id;
        $fileNote->note = trim_editor($request->note);
        $fileNote->added_by = user()->id;
        $fileNote->save();

        return Reply::success(__('messages.recordSaved'));
    }

    /**
     * Upload or update document for a lead
     */
    public function uploadLeadDocument(Request $request, $leadId)
    {
        $lead = NewLead::findOrFail($leadId);
        
        $request->validate([
            'document_master_id' => 'required|integer',
            'applicant_type' => 'required|in:main_applicant,father,mother,spouse,child',
            'document_file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'child_index' => 'nullable|integer|min:1',
        ]);
        
        $documentMasterId = $request->document_master_id;
        $applicantType = $request->applicant_type;
        $childIndex = $request->child_index;
        $file = $request->file('document_file');
        
        // Validate child_index for child type
        if ($applicantType === 'child' && !$childIndex) {
            return Reply::error('Child index is required for child documents.');
        }
        
        try {
            // Get document master to get name
            if ($applicantType === 'main_applicant') {
                $documentMaster = \App\Models\NewLeadMainDocument::where(function($query) {
                    $query->where('company_id', company()->id)
                          ->orWhereNull('company_id');
                })->find($documentMasterId);
            } else {
                $documentMaster = \App\Models\NewLeadDependsDocument::where(function($query) {
                    $query->where('company_id', company()->id)
                          ->orWhereNull('company_id');
                })->find($documentMasterId);
            }
            
            if (!$documentMaster) {
                return Reply::error('Document master not found.');
            }
            
            // Get or create document record
            $leadDocument = \App\Models\NewLeadDocument::firstOrNew(['lead_id' => $leadId]);
            
            // Determine folder path
            $folderPath = 'lead-documents/' . $leadId . '/' . $applicantType;
            if ($applicantType === 'child') {
                $folderPath .= '/child_' . $childIndex;
            }
            
            // Delete old file if exists
            $oldFileUrl = null;
            $columnName = $this->getDocumentColumnName($applicantType);
            $documents = $leadDocument->$columnName ?? [];
            $docIdStr = (string)$documentMasterId;
            
            if ($applicantType === 'child') {
                // Convert old format if needed
                $childrenDocs = $this->convertDocumentKeysToIds($documents, false);
                
                // Find child entry
                $childEntry = null;
                $childEntryIndex = null;
                foreach ($childrenDocs as $index => $entry) {
                    if (isset($entry['child_index']) && $entry['child_index'] == $childIndex) {
                        $childEntry = $entry;
                        $childEntryIndex = $index;
                        break;
                    }
                }
                
                if ($childEntry && isset($childEntry['documents'][$docIdStr]['file_url'])) {
                    $oldFileUrl = $childEntry['documents'][$docIdStr]['file_url'];
                }
            } else {
                // Convert old format if needed
                $documents = $this->convertDocumentKeysToIds($documents, $applicantType === 'main_applicant');
                
                if (isset($documents[$docIdStr]['file_url'])) {
                    $oldFileUrl = $documents[$docIdStr]['file_url'];
                }
            }
            
            // Delete old file
            if ($oldFileUrl) {
                try {
                    // Extract file path from URL
                    $oldFilePath = str_replace(asset_url_local_s3(''), '', $oldFileUrl);
                    if ($oldFilePath && \Storage::disk(config('filesystems.default'))->exists($oldFilePath)) {
                        \Storage::disk(config('filesystems.default'))->delete($oldFilePath);
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete old file: ' . $e->getMessage());
                }
            }
            
            // Upload new file
            $customFileName = \App\Helper\Files::generateFileNameWithOriginal($file->getClientOriginalName());
            \App\Helper\Files::fileStore($file, $folderPath, $customFileName);
            
            $fileVisibility = [];
            if (config('filesystems.default') == 'local') {
                $fileVisibility = ['directory_visibility' => 'public', 'visibility' => 'public'];
            }
            
            \Storage::disk(config('filesystems.default'))->putFileAs(
                $folderPath,
                $file,
                $customFileName,
                $fileVisibility
            );
            
            // Generate file URL
            $fileUrl = asset_url_local_s3($folderPath . '/' . $customFileName);
            
            // Update JSON structure using document_master_id as key
            if ($applicantType === 'child') {
                // Update children_documents
                $childrenDocs = $leadDocument->children_documents ?? [];
                
                // Find or create child entry
                $childEntryIndex = null;
                foreach ($childrenDocs as $index => $entry) {
                    if (isset($entry['child_index']) && $entry['child_index'] == $childIndex) {
                        $childEntryIndex = $index;
                        break;
                    }
                }
                
                if ($childEntryIndex === null) {
                    // Create new child entry
                    $childrenDocs[] = [
                        'child_index' => $childIndex,
                        'documents' => []
                    ];
                    $childEntryIndex = count($childrenDocs) - 1;
                }
                
                // Convert old format if needed
                if (!isset($childrenDocs[$childEntryIndex]['documents'])) {
                    $childrenDocs[$childEntryIndex]['documents'] = [];
                }
                $childDocuments = $this->convertDocumentKeysToIds($childrenDocs[$childEntryIndex]['documents'], false);
                
                // Update document using document_master_id as key
                $childDocuments[$docIdStr] = [
                    'document_master_id' => $documentMasterId,
                    'document_name' => $documentMaster->name,
                    'file_url' => $fileUrl,
                    'status' => 'uploaded',
                    'uploaded_at' => now()->toDateString()
                ];
                
                $childrenDocs[$childEntryIndex]['documents'] = $childDocuments;
                $leadDocument->children_documents = $childrenDocs;
            } else {
                // Update main applicant, father, mother, or spouse documents
                $documents = $leadDocument->$columnName ?? [];
                // Convert old format if needed
                $documents = $this->convertDocumentKeysToIds($documents, $applicantType === 'main_applicant');
                
                // Update document using document_master_id as key
                $documents[$docIdStr] = [
                    'document_master_id' => $documentMasterId,
                    'document_name' => $documentMaster->name,
                    'file_url' => $fileUrl,
                    'status' => 'uploaded',
                    'uploaded_at' => now()->toDateString()
                ];
                $leadDocument->$columnName = $documents;
            }
            
            $leadDocument->save();
            
            return Reply::success(__('messages.recordSaved'));
        } catch (\Exception $e) {
            \Log::error('Error uploading document: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return Reply::error('Failed to upload document: ' . $e->getMessage());
        }
    }
    
    /**
     * Get document column name based on applicant type
     */
    private function getDocumentColumnName($applicantType)
    {
        $columnMap = [
            'main_applicant' => 'main_applicant_documents',
            'father' => 'father_documents',
            'mother' => 'mother_documents',
            'spouse' => 'spouse_documents',
            'child' => 'children_documents',
        ];
        
        return $columnMap[$applicantType] ?? 'main_applicant_documents';
    }
    
    /**
     * Convert old name-based JSON keys to ID-based keys (backward compatibility)
     */
    private function convertDocumentKeysToIds($documents, $isMainApplicant = false)
    {
        if (empty($documents) || !is_array($documents)) {
            return [];
        }
        
        $converted = [];
        $needsConversion = false;
        
        // Check if any key is non-numeric (old format)
        foreach ($documents as $key => $value) {
            if (!is_numeric($key)) {
                $needsConversion = true;
                break;
            }
        }
        
        if (!$needsConversion) {
            // Already using ID-based keys, but ensure document_name exists
            foreach ($documents as $key => $docData) {
                if (!isset($docData['document_name'])) {
                    // Get document name from master table
                    $docId = (int)$key;
                    if ($isMainApplicant) {
                        $masterDoc = \App\Models\NewLeadMainDocument::find($docId);
                    } else {
                        $masterDoc = \App\Models\NewLeadDependsDocument::find($docId);
                    }
                    if ($masterDoc) {
                        $docData['document_name'] = $masterDoc->name;
                    }
                }
                $converted[$key] = $docData;
            }
            return $converted;
        }
        
        // Get master documents for mapping
        if ($isMainApplicant) {
            $masterDocuments = \App\Models\NewLeadMainDocument::where(function($query) {
                $query->where('company_id', company()->id)
                      ->orWhereNull('company_id');
            })->get()->keyBy(function($doc) {
                return strtolower(str_replace(' ', '_', $doc->name));
            });
        } else {
            $masterDocuments = \App\Models\NewLeadDependsDocument::where(function($query) {
                $query->where('company_id', company()->id)
                      ->orWhereNull('company_id');
            })->get()->keyBy(function($doc) {
                return strtolower(str_replace(' ', '_', $doc->name));
            });
        }
        
        // Convert old keys to new ID-based keys
        foreach ($documents as $oldKey => $docData) {
            if (is_numeric($oldKey)) {
                // Already ID-based, keep as is but ensure document_name exists
                $docId = (int)$oldKey;
                if (!isset($docData['document_name'])) {
                    $masterDoc = $isMainApplicant 
                        ? \App\Models\NewLeadMainDocument::find($docId)
                        : \App\Models\NewLeadDependsDocument::find($docId);
                    if ($masterDoc) {
                        $docData['document_name'] = $masterDoc->name;
                    }
                }
                $converted[(string)$oldKey] = $docData;
            } else {
                // Old name-based key, convert to ID
                $masterDoc = $masterDocuments->get($oldKey);
                if ($masterDoc) {
                    $docId = (string)$masterDoc->id;
                    $converted[$docId] = [
                        'document_master_id' => $masterDoc->id,
                        'document_name' => $masterDoc->name,
                        'file_url' => $docData['file_url'] ?? null,
                        'status' => $docData['status'] ?? 'pending',
                        'uploaded_at' => $docData['uploaded_at'] ?? null
                    ];
                }
            }
        }
        
        return $converted;
    }
    
    /**
     * Get master documents for add required document dialog
     */
    public function getMasterDocumentsForSection(Request $request, $leadId)
    {
        try {
            $lead = NewLead::findOrFail($leadId);
            $applicantType = $request->get('applicant_type');
            $childIndex = $request->get('child_index');
            
            if (!in_array($applicantType, ['main_applicant', 'father', 'mother', 'spouse', 'child'])) {
                return Reply::error('Invalid applicant type.');
            }
            
            // Get master documents based on applicant type
            if ($applicantType === 'main_applicant') {
                $masterDocuments = \App\Models\NewLeadMainDocument::where(function($query) {
                    $query->where('company_id', company()->id)
                          ->orWhereNull('company_id');
                })->orderBy('name')->get();
            } else {
                $masterDocuments = \App\Models\NewLeadDependsDocument::where(function($query) {
                    $query->where('company_id', company()->id)
                          ->orWhereNull('company_id');
                })->orderBy('name')->get();
            }
            
            // Get existing documents from JSON
            $leadDocument = \App\Models\NewLeadDocument::where('lead_id', $leadId)->first();
            $existingDocuments = [];
            
            if ($applicantType === 'child') {
                if (!$childIndex) {
                    return Reply::error('Child index is required for child documents.');
                }
                
                $childrenDocs = $leadDocument && $leadDocument->children_documents 
                    ? $leadDocument->children_documents 
                    : [];
                
                // Find child entry
                foreach ($childrenDocs as $entry) {
                    if (isset($entry['child_index']) && $entry['child_index'] == $childIndex) {
                        $existingDocuments = $entry['documents'] ?? [];
                        break;
                    }
                }
                
                // Convert old format if needed
                $existingDocuments = $this->convertDocumentKeysToIds($existingDocuments, false);
            } else {
                $columnName = $this->getDocumentColumnName($applicantType);
                $existingDocuments = $leadDocument && $leadDocument->$columnName 
                    ? $leadDocument->$columnName 
                    : [];
                
                // Convert old format if needed
                $existingDocuments = $this->convertDocumentKeysToIds($existingDocuments, $applicantType === 'main_applicant');
            }
            
            // Build response with checkbox state (using document_master_id)
            $documents = [];
            foreach ($masterDocuments as $doc) {
                $docIdStr = (string)$doc->id;
                $isChecked = isset($existingDocuments[$docIdStr]);
                
                $documents[] = [
                    'id' => $doc->id,
                    'name' => $doc->name,
                    'checked' => $isChecked
                ];
            }
            
            return Reply::dataOnly(['status' => 'success', 'data' => ['documents' => $documents]]);
        } catch (\Exception $e) {
            \Log::error('Error getting master documents: ' . $e->getMessage());
            return Reply::error('Failed to load documents: ' . $e->getMessage());
        }
    }
    
    /**
     * Add or remove required documents from section JSON
     */
    public function updateRequiredDocuments(Request $request, $leadId)
    {
        try {
            $lead = NewLead::findOrFail($leadId);
            
            $request->validate([
                'applicant_type' => 'required|in:main_applicant,father,mother,spouse,child',
                'child_index' => 'nullable|integer|min:1',
                'document_ids' => 'required|array',
                'document_ids.*' => 'integer',
            ]);
            
            $applicantType = $request->applicant_type;
            $childIndex = $request->child_index;
            $selectedDocumentIds = $request->document_ids; // Array of checked document master IDs
            
            // Validate child_index for child type
            if ($applicantType === 'child' && !$childIndex) {
                return Reply::error('Child index is required for child documents.');
            }
            
            // Get or create document record
            $leadDocument = \App\Models\NewLeadDocument::firstOrNew(['lead_id' => $leadId]);
            
            // Get master documents to get document names
            if ($applicantType === 'main_applicant') {
                $masterDocuments = \App\Models\NewLeadMainDocument::where(function($query) {
                    $query->where('company_id', company()->id)
                          ->orWhereNull('company_id');
                })->get()->keyBy('id');
            } else {
                $masterDocuments = \App\Models\NewLeadDependsDocument::where(function($query) {
                    $query->where('company_id', company()->id)
                          ->orWhereNull('company_id');
                })->get()->keyBy('id');
            }
            
            if ($applicantType === 'child') {
                // Handle children_documents
                $childrenDocs = $leadDocument->children_documents ?? [];
                
                // Find or create child entry
                $childEntryIndex = null;
                foreach ($childrenDocs as $index => $entry) {
                    if (isset($entry['child_index']) && $entry['child_index'] == $childIndex) {
                        $childEntryIndex = $index;
                        break;
                    }
                }
                
                if ($childEntryIndex === null) {
                    // Create new child entry
                    $childrenDocs[] = [
                        'child_index' => $childIndex,
                        'documents' => []
                    ];
                    $childEntryIndex = count($childrenDocs) - 1;
                }
                
                if (!isset($childrenDocs[$childEntryIndex]['documents'])) {
                    $childrenDocs[$childEntryIndex]['documents'] = [];
                }
                
                // Convert old format if needed
                $childDocuments = $this->convertDocumentKeysToIds($childrenDocs[$childEntryIndex]['documents'], false);
                
                // Get all master document IDs
                $allMasterIds = $masterDocuments->keys()->toArray();
                
                // Update documents: add checked, remove unchecked
                foreach ($allMasterIds as $docId) {
                    $docIdStr = (string)$docId;
                    $masterDoc = $masterDocuments->get($docId);
                    
                    if (in_array($docId, $selectedDocumentIds)) {
                        // Document should be present - add if not exists
                        if (!isset($childDocuments[$docIdStr])) {
                            $childDocuments[$docIdStr] = [
                                'document_master_id' => $docId,
                                'document_name' => $masterDoc->name,
                                'file_url' => null,
                                'status' => 'pending',
                                'uploaded_at' => null
                            ];
                        }
                    } else {
                        // Document should be removed - only remove if no file uploaded
                        if (isset($childDocuments[$docIdStr]) && empty($childDocuments[$docIdStr]['file_url'])) {
                            unset($childDocuments[$docIdStr]);
                        }
                    }
                }
                
                $childrenDocs[$childEntryIndex]['documents'] = $childDocuments;
                $leadDocument->children_documents = $childrenDocs;
            } else {
                // Handle main_applicant, father, mother, spouse
                $columnName = $this->getDocumentColumnName($applicantType);
                $documents = $leadDocument->$columnName ?? [];
                
                // Convert old format if needed
                $documents = $this->convertDocumentKeysToIds($documents, $applicantType === 'main_applicant');
                
                // Get all master document IDs
                $allMasterIds = $masterDocuments->keys()->toArray();
                
                // Update documents: add checked, remove unchecked
                foreach ($allMasterIds as $docId) {
                    $docIdStr = (string)$docId;
                    $masterDoc = $masterDocuments->get($docId);
                    
                    if (in_array($docId, $selectedDocumentIds)) {
                        // Document should be present - add if not exists
                        if (!isset($documents[$docIdStr])) {
                            $documents[$docIdStr] = [
                                'document_master_id' => $docId,
                                'document_name' => $masterDoc->name,
                                'file_url' => null,
                                'status' => 'pending',
                                'uploaded_at' => null
                            ];
                        }
                    } else {
                        // Document should be removed - only remove if no file uploaded
                        if (isset($documents[$docIdStr]) && empty($documents[$docIdStr]['file_url'])) {
                            unset($documents[$docIdStr]);
                        }
                    }
                }
                
                $leadDocument->$columnName = $documents;
            }
            
            $leadDocument->save();
            
            return Reply::success(__('messages.recordSaved'));
        } catch (\Exception $e) {
            \Log::error('Error updating required documents: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return Reply::error('Failed to update documents: ' . $e->getMessage());
        }
    }
    
    /**
     * Get document configuration based on key
     */
    private function getDocumentConfig($documentKey, $lead)
    {
        $configs = [
            'upload_resume' => ['step' => 1, 'folder' => 'lead-resume-files', 'field' => 'upload_resume', 'no_lead_id' => true],
            'pr_assessment_letter_file' => ['step' => 2, 'folder' => 'lead-assessment-letters', 'field' => 'pr_assessment_letter_file'],
            'passport_file_upload' => ['step' => 3, 'folder' => 'lead-passport-files', 'field' => 'passport_file_upload'],
            'father_passport_file' => ['step' => 5, 'folder' => 'lead-family-passports', 'field' => 'father_passport_file'],
            'mother_passport_file' => ['step' => 5, 'folder' => 'lead-family-passports', 'field' => 'mother_passport_file'],
            'spouse_passport_file' => ['step' => 5, 'folder' => 'lead-family-passports', 'field' => 'spouse_passport_file'],
            'spouse_document_file' => ['step' => 5, 'folder' => 'lead-family-documents', 'field' => 'spouse_document_file'],
            'ielts_result_file' => ['step' => 6, 'folder' => 'lead-education-files', 'field' => 'ielts_result_file'],
            'tenth_result_file' => ['step' => 6, 'folder' => 'lead-education-files', 'field' => 'tenth_result_file'],
            'twelfth_result_file' => ['step' => 6, 'folder' => 'lead-education-files', 'field' => 'twelfth_result_file'],
            'graduation_result_file' => ['step' => 6, 'folder' => 'lead-education-files', 'field' => 'graduation_result_file'],
            'post_graduation_result_file' => ['step' => 6, 'folder' => 'lead-education-files', 'field' => 'post_graduation_result_file'],
            'job_offer_letter_file' => ['step' => 7, 'folder' => 'lead-job-files', 'field' => 'job_offer_letter_file'],
            'job_experience_letter_file' => ['step' => 7, 'folder' => 'lead-job-files', 'field' => 'job_experience_letter_file'],
            'valuation_report_file' => ['step' => 8, 'folder' => 'lead-property-files', 'field' => 'valuation_report_file'],
            'father_income_document_file' => ['step' => 9, 'folder' => 'lead-income-documents', 'field' => 'father_income_document_file'],
            'mother_income_document_file' => ['step' => 9, 'folder' => 'lead-income-documents', 'field' => 'mother_income_document_file'],
            'candidate_income_document_file' => ['step' => 9, 'folder' => 'lead-income-documents', 'field' => 'candidate_income_document_file'],
            'spouse_income_document_file' => ['step' => 9, 'folder' => 'lead-income-documents', 'field' => 'spouse_income_document_file'],
        ];
        
        // Handle dynamic documents
        if (strpos($documentKey, 'child_passport_file_') === 0) {
            $index = (int) str_replace('child_passport_file_', '', $documentKey);
            return ['step' => 5, 'folder' => 'lead-family-passports', 'field' => 'child_passport_file', 'is_array' => true, 'array_key' => 'children', 'array_index' => $index - 1, 'array_field' => 'child_passport_file'];
        }
        
        if (strpos($documentKey, 'child_document_file_') === 0) {
            $index = (int) str_replace('child_document_file_', '', $documentKey);
            return ['step' => 5, 'folder' => 'lead-family-documents', 'field' => 'child_document_file', 'is_array' => true, 'array_key' => 'children', 'array_index' => $index - 1, 'array_field' => 'child_document_file'];
        }
        
        if (strpos($documentKey, 'other_degree_result_file_') === 0) {
            $index = (int) str_replace('other_degree_result_file_', '', $documentKey);
            return ['step' => 6, 'folder' => 'lead-education-files', 'field' => 'other_degree_result_file', 'is_array' => true, 'array_key' => 'other_degrees', 'array_index' => $index - 1, 'array_field' => 'other_degree_result_file'];
        }
        
        if (strpos($documentKey, 'job_offer_letter_file_') === 0) {
            $index = (int) str_replace('job_offer_letter_file_', '', $documentKey);
            return ['step' => 7, 'folder' => 'lead-job-files', 'field' => 'job_offer_letter_file', 'is_array' => true, 'array_key' => 'jobs', 'array_index' => $index - 1, 'array_field' => 'job_offer_letter_file'];
        }
        
        if (strpos($documentKey, 'job_experience_letter_file_') === 0) {
            $index = (int) str_replace('job_experience_letter_file_', '', $documentKey);
            return ['step' => 7, 'folder' => 'lead-job-files', 'field' => 'job_experience_letter_file', 'is_array' => true, 'array_key' => 'jobs', 'array_index' => $index - 1, 'array_field' => 'job_experience_letter_file'];
        }
        
        return $configs[$documentKey] ?? null;
    }
    
    /**
     * Get step data as array
     */
    private function getStepDataArray($lead, $stepNumber)
    {
        $stepField = 'step_' . $stepNumber . '_data';
        $stepData = $lead->$stepField;
        
        if (is_string($stepData)) {
            $decoded = json_decode($stepData, true);
            return is_array($decoded) ? $decoded : [];
        }
        return is_array($stepData) ? $stepData : [];
    }
    
    /**
     * Get document file name from step data
     */
    private function getDocumentFileName($stepData, $documentKey, $config)
    {
        if (!empty($config['is_array'])) {
            $arrayKey = $config['array_key'];
            $arrayIndex = $config['array_index'];
            $arrayField = $config['array_field'];
            
            if (!empty($stepData[$arrayKey][$arrayIndex][$arrayField])) {
                return $stepData[$arrayKey][$arrayIndex][$arrayField];
            }
        } else {
            $field = $config['field'];
            if (!empty($stepData[$field])) {
                return $stepData[$field];
            }
        }
        
        return null;
    }
    
    /**
     * Update document in step data
     */
    private function updateDocumentInStepData($lead, $stepNumber, $documentKey, $fileName, $config)
    {
        $stepField = 'step_' . $stepNumber . '_data';
        $stepData = $this->getStepDataArray($lead, $stepNumber);
        
        if (!empty($config['is_array'])) {
            $arrayKey = $config['array_key'];
            $arrayIndex = $config['array_index'];
            $arrayField = $config['array_field'];
            
            if (!isset($stepData[$arrayKey])) {
                $stepData[$arrayKey] = [];
            }
            if (!isset($stepData[$arrayKey][$arrayIndex])) {
                $stepData[$arrayKey][$arrayIndex] = [];
            }
            $stepData[$arrayKey][$arrayIndex][$arrayField] = $fileName;
        } else {
            $field = $config['field'];
            $stepData[$field] = $fileName;
        }
        
        $lead->$stepField = $stepData;
        $lead->save();
    }
    
    /**
     * Get file notes for a lead via AJAX
     */
    public function getNewLeadFileNotes($id)
    {
        $lead = NewLead::with(['fileNotes.addedBy'])->findOrFail($id);
        
        $this->lead = $lead;
        
        $html = view('lead-details.components.file-notes-list', $this->data)->render();
        
        return Reply::dataOnly(['status' => 'success', 'data' => ['html' => $html]]);
    }

    /**
     * Store or update new lead process
     */
    public function storeNewLeadProcess(Request $request)
    {
        $newLead = NewLead::findOrFail($request->new_lead_id);
        
        $rules = [
            'new_lead_id' => 'required|exists:new_leads,id',
            'applicant_name' => 'required|string|max:255',
            'visa_category' => 'required|exists:new_lead_visa_type,id',
            'subclass' => 'required|exists:new_lead_subclass,id',
            'passport_name' => 'required|string|max:255',
            'passport_number' => 'required|string|max:255',
            'agent_name' => 'required|string|max:255',
            'advance_fees' => 'required|string|max:255',
            'advance_fees_due_date' => 'required|date',
            'remaining_fees' => 'required|string|max:255',
            'remaining_fees_due_date' => 'required|date',
            'agent_fees' => 'required|string|max:255',
            'submission_fees' => 'required|string|max:255',
            'status' => 'nullable|string|max:255',
            'processing_time' => 'nullable|string|max:255',
            'bank_cheque_handover_date' => 'nullable|date',
            'passport_handover_date' => 'nullable|date',
            'process_note' => 'nullable|string',
            'contract_letter' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'grant_letter' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'offer_letter' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'medical_letter' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'air_ticket' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'accommodation_letter' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ];
        
        $request->validate($rules);

        // Check if process exists
        $process = NewLeadProcess::where('new_lead_id', $request->new_lead_id)->first();
        
        if (!$process) {
            $process = new NewLeadProcess();
            $process->new_lead_id = $request->new_lead_id;
            $process->added_by = user()->id;
        } else {
            $process->last_updated_by = user()->id;
        }

        // Fill basic fields
        $process->applicant_name = $request->applicant_name;
        $process->visa_category = $request->visa_category;
        $process->subclass = $request->subclass;
        $process->passport_name = $request->passport_name;
        $process->passport_number = $request->passport_number;
        $process->agent_name = $request->agent_name;
        $process->advance_fees = $request->advance_fees;
        $process->advance_fees_due_date = $request->advance_fees_due_date;
        $process->remaining_fees = $request->remaining_fees;
        $process->remaining_fees_due_date = $request->remaining_fees_due_date;
        $process->agent_fees = $request->agent_fees;
        $process->submission_fees = $request->submission_fees;
        $process->status = $request->status ?? null;
        $process->processing_time = $request->processing_time ?? null;
        $process->bank_cheque_handover_date = $request->bank_cheque_handover_date ?? null;
        $process->passport_handover_date = $request->passport_handover_date ?? null;
        $process->process_note = $request->process_note ?? null;

        // Handle file uploads
        $fileFields = ['contract_letter', 'grant_letter', 'offer_letter', 'medical_letter', 'air_ticket', 'accommodation_letter'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                // Use Files::fileStore to match other document uploads (stores in user-uploads)
                $customFileName = \App\Helper\Files::generateFileNameWithOriginal($file->getClientOriginalName());
                $folderPath = 'public/process_documents/' . $newLead->id;
                \App\Helper\Files::fileStore($file, $folderPath, $customFileName);
                
                // Also store using Storage for consistency
                $fileVisibility = [];
                if (config('filesystems.default') == 'local') {
                    $fileVisibility = ['directory_visibility' => 'public', 'visibility' => 'public'];
                }
                \Storage::disk(config('filesystems.default'))->putFileAs($folderPath, $file, $customFileName, $fileVisibility);
                
                // Store relative path: public/process_documents/{leadId}/{filename}
                $process->$field = $folderPath . '/' . $customFileName;
            }
        }

        // Handle additional documents
        $additionalDocuments = [];
        if ($request->has('additional_documents_json')) {
            $documentsData = json_decode($request->additional_documents_json, true);
            if (is_array($documentsData)) {
                foreach ($documentsData as $docData) {
                    $docName = $docData['document_name'] ?? '';
                    $docFileIndicator = $docData['document_file'] ?? '';
                    
                    if (empty($docName)) {
                        continue;
                    }
                    
                    $documentFile = null;
                    
                    // Check if it's a new file indicator (NEW_FILE_{index})
                    if (strpos($docFileIndicator, 'NEW_FILE_') === 0) {
                        // Extract the index from NEW_FILE_{index}
                        $fileIndex = str_replace('NEW_FILE_', '', $docFileIndicator);
                        $fileFieldName = 'additional_document_file_' . $fileIndex;
                        
                        // Check if new file is uploaded
                        if ($request->hasFile($fileFieldName)) {
                            $file = $request->file($fileFieldName);
                            $customFileName = \App\Helper\Files::generateFileNameWithOriginal($file->getClientOriginalName());
                            $folderPath = 'public/process_documents/' . $newLead->id;
                            \App\Helper\Files::fileStore($file, $folderPath, $customFileName);
                            
                            // Also store using Storage for consistency
                            $fileVisibility = [];
                            if (config('filesystems.default') == 'local') {
                                $fileVisibility = ['directory_visibility' => 'public', 'visibility' => 'public'];
                            }
                            \Storage::disk(config('filesystems.default'))->putFileAs($folderPath, $file, $customFileName, $fileVisibility);
                            
                            $documentFile = $folderPath . '/' . $customFileName;
                        }
                    } elseif (!empty($docFileIndicator)) {
                        // Keep existing file
                        $documentFile = $docFileIndicator;
                    }
                    
                    // Only add if we have a file (either new or existing)
                    if ($documentFile) {
                        $additionalDocuments[] = [
                            'document_name' => $docName,
                            'document_file' => $documentFile
                        ];
                    }
                }
            }
        }
        
        // Store additional documents as JSON
        $process->additional_documents = !empty($additionalDocuments) ? json_encode($additionalDocuments) : null;

        $process->save();

        return Reply::success(__('messages.recordSaved'));
    }

    /**
     * Store or update new lead account
     */
    public function storeNewLeadAccount(Request $request)
    {
        // Restrict access to Admin role only
        abort_403(!in_array('admin', user_roles()));
        
        $lead = NewLead::findOrFail($request->new_lead_id);
        
        $rules = [
            'new_lead_id' => 'required|exists:new_leads,id',
            'invoice_date' => 'required|date_format:"' . company()->date_format . '"',
            'bill_to' => 'required|string|max:255',
            'service' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'tax' => 'required|string|max:255',
            'discount' => 'nullable|numeric|min:0',
            'service_description' => 'nullable|string',
            'installment_payment' => 'nullable|boolean',
            'installment_months' => 'nullable|integer|min:1',
            'invoice_notes' => 'nullable|string',
        ];

        $request->validate($rules);

        // Parse invoice date from company format to Y-m-d
        $invoiceDate = null;
        if ($request->invoice_date) {
            try {
                $invoiceDate = \Carbon\Carbon::createFromFormat(
                    company()->date_format,
                    $request->invoice_date
                )->format('Y-m-d');
            } catch (\Exception $e) {
                // If parsing fails, try standard date parsing
                try {
                    $invoiceDate = \Carbon\Carbon::parse($request->invoice_date)->format('Y-m-d');
                } catch (\Exception $e2) {
                    return Reply::error('Invalid invoice date format.');
                }
            }
        }

        // Calculate net amount
        $price = floatval($request->price ?? 0);
        $discount = floatval($request->discount ?? 0);
        $tax = $request->tax ?? 'GST 0%';
        
        // Extract tax percentage from tax field (e.g., "GST 18%" -> 18)
        $taxPercent = 0;
        if (preg_match('/(\d+(?:\.\d+)?)/', $tax, $matches)) {
            $taxPercent = floatval($matches[1]);
        }
        
        // Calculate: Tax Amount = Price * (Tax Percentage / 100)
        $taxAmount = ($price * $taxPercent) / 100;
        
        // Calculate: Sub Total = Price
        $subTotal = $price;
        
        // Calculate: Net Amount = Price + Tax Amount - Discount
        $netAmount = $price + $taxAmount - $discount;
        $netAmount = max(0, $netAmount); // Ensure non-negative
        
        // Calculate: Total Amount = Net Amount
        $totalAmount = $netAmount;

        // Check if account exists for update
        if ($request->id) {
            $account = \App\Models\NewLeadAccount::findOrFail($request->id);
            $account->last_updated_by = user()->id;
        } else {
            $account = new \App\Models\NewLeadAccount();
            $account->added_by = user()->id;
        }

        // Fill account data
        $account->new_lead_id = $request->new_lead_id;
        $account->client_name = $request->client_name ?? $lead->client_name;
        $account->invoice_date = $invoiceDate;
        $account->phone = $request->phone ?? $lead->mobile;
        $account->email = $request->email ?? $lead->client_email;
        $account->address = $request->address;
        $account->bill_to = $request->bill_to;
        $account->agent = $request->agent ?: null;
        $account->service = $request->service;
        $account->price = $price;
        $account->tax = $tax;
        $account->discount = $discount;
        $account->net_amount = $netAmount;
        $account->sub_total = $subTotal;
        $account->tax_amount = $taxAmount;
        $account->discount_amount = $discount;
        $account->total_amount = $totalAmount;
        $account->service_description = $request->service_description;
        $account->installment_payment = $request->has('installment_payment') ? true : false;
        $account->installment_months = $request->installment_months;
        $account->invoice_notes = $request->invoice_notes;
        $account->status = $request->status ?? 'pending';

        $account->save();

        return Reply::success(__('messages.recordSaved'));
    }

    /**
     * Get account data for editing
     */
    public function getNewLeadAccount($id)
    {
        // Restrict access to Admin role only
        abort_403(!in_array('admin', user_roles()));
        
        $account = \App\Models\NewLeadAccount::with(['agentUser', 'newLead'])->findOrFail($id);
        
        return Reply::dataOnly([
            'status' => 'success',
            'account' => $account
        ]);
    }

    /**
     * Delete account
     */
    public function deleteNewLeadAccount($id)
    {
        // Restrict access to Admin role only
        abort_403(!in_array('admin', user_roles()));
        
        $account = \App\Models\NewLeadAccount::findOrFail($id);
        $account->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }

    /**
     * Update account status
     */
    public function updateAccountStatus(Request $request, $id)
    {
        // Restrict access to Admin role only
        abort_403(!in_array('admin', user_roles()));
        
        $request->validate([
            'status' => 'required|in:pending,received',
        ]);

        $account = \App\Models\NewLeadAccount::findOrFail($id);
        $account->status = $request->status;
        $account->last_updated_by = user()->id;
        $account->save();

        return Reply::success(__('messages.updateSuccess'));
    }

    /**
     * Get accounts list for a lead via AJAX
     */
    public function getNewLeadAccounts($id)
    {
        // Restrict access to Admin role only
        abort_403(!in_array('admin', user_roles()));
        
        $lead = NewLead::with(['accounts.agentUser', 'accounts.addedBy'])->findOrFail($id);
        
        $this->lead = $lead;
        
        $html = view('lead-details.components.accounts-list', $this->data)->render();
        
        return Reply::dataOnly(['status' => 'success', 'data' => ['html' => $html]]);
    }

    /**
     * Download account invoice as PDF
     */
    public function downloadAccountInvoice($id)
    {
        // Restrict access to Admin role only
        abort_403(!in_array('admin', user_roles()));
        
        try {
            $account = \App\Models\NewLeadAccount::with(['agentUser', 'newLead'])->findOrFail($id);
            
            // Get lead ID formatted
            $leadId = $account->newLead ? 'LEAD-' . str_pad($account->newLead->id, 4, '0', STR_PAD_LEFT) : '--';
            
            // Get currency symbol
            try {
                $currencySymbol = company()->currency ? company()->currency->currency_symbol : '₹';
            } catch (\Exception $e) {
                $currencySymbol = '₹';
            }
            
            // Get invoice setting for logo
            $invoiceSetting = invoice_setting();
            $companyLogo = $invoiceSetting ? $invoiceSetting->logo_url : (company()->light_logo_url ?? global_setting()->light_logo_url ?? '');
            
            // Get company data for template
            $company = company();
            $companyPhone = $company->phone ?? '1800 571 2844';
            $companyEmail = $company->company_email ?? 'info.rrpei@gmail.com';
            $companyAddress = $company->address ?? '3rd Floor, Aaron Spectra, 302, Rajpath Rangoli Rd, behind Rajpath Club, Bodakdev, Ahmedabad, Gujarat 380059';
            $dateFormat = company()->date_format ?? 'd-m-Y';
            
            // Generate PDF
            $pdf = app('dompdf.wrapper');
            $pdf->setOption('enable_php', true);
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', true);
            
            $customCss = '<style>
                * { text-transform: none !important; }
            </style>';
            
            $html = view('lead-details.pdf.account-invoice', [
                'account' => $account,
                'leadId' => $leadId,
                'currencySymbol' => $currencySymbol,
                'companyLogo' => $companyLogo,
                'companyPhone' => $companyPhone,
                'companyEmail' => $companyEmail,
                'companyAddress' => $companyAddress,
                'dateFormat' => $dateFormat,
            ])->render();
            
            $pdf->loadHTML($customCss . $html);
            
            $filename = 'Invoice-' . $leadId . '-' . date('Y-m-d');
            
            return $pdf->download($filename . '.pdf');
        } catch (\Exception $e) {
            \Log::error('Error downloading account invoice: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Account ID: ' . $id);
            return response()->json(['error' => 'Failed to generate PDF: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store or update new lead travel details
     */
    public function storeNewLeadTravelDetails(Request $request)
    {
        $newLead = NewLead::findOrFail($request->new_lead_id);
        
        $rules = [
            'new_lead_id' => 'required|exists:new_leads,id',
            'purpose_of_trip' => 'required|string',
            'place_to_visit' => 'required|string',
            'date_of_arrival' => 'required|date',
            'arrival_flight' => 'required|string|max:255',
            'arrival_city' => 'required|string|max:255',
            'date_of_departure' => 'required|date',
            'departure_flight' => 'required|string|max:255',
            'departure_city' => 'required|string|max:255',
            'phone_number_other_country' => 'nullable|numeric|digits:10',
            'address_stay' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|numeric|digits:6',
            'person_paying' => 'required|string',
            'mother_in_country' => 'nullable|string|in:yes,no',
            'immediate_relatives' => 'nullable|string|in:yes,no',
            'other_relatives' => 'nullable|string|in:yes,no',
        ];
        
        $request->validate($rules);

        // Check if travel details exist
        $travelDetails = \App\Models\NewLeadTravelDetail::where('new_lead_id', $request->new_lead_id)->first();
        
        if (!$travelDetails) {
            $travelDetails = new \App\Models\NewLeadTravelDetail();
            $travelDetails->new_lead_id = $request->new_lead_id;
            $travelDetails->added_by = user()->id;
        } else {
            $travelDetails->last_updated_by = user()->id;
        }

        // Fill fields
        $travelDetails->purpose_of_trip = $request->purpose_of_trip;
        $travelDetails->place_to_visit = $request->place_to_visit;
        $travelDetails->date_of_arrival = $request->date_of_arrival;
        $travelDetails->arrival_flight = $request->arrival_flight;
        $travelDetails->arrival_city = $request->arrival_city;
        $travelDetails->date_of_departure = $request->date_of_departure;
        $travelDetails->departure_flight = $request->departure_flight;
        $travelDetails->departure_city = $request->departure_city;
        $travelDetails->phone_number_other_country = $request->phone_number_other_country;
        $travelDetails->address_stay = $request->address_stay;
        $travelDetails->city = $request->city;
        $travelDetails->state = $request->state;
        $travelDetails->postal_code = $request->postal_code;
        $travelDetails->person_paying = $request->person_paying;
        $travelDetails->mother_in_country = $request->mother_in_country;
        $travelDetails->immediate_relatives = $request->immediate_relatives;
        $travelDetails->other_relatives = $request->other_relatives;

        $travelDetails->save();

        return Reply::success(__('messages.recordSaved'));
    }

    /**
     * Notify client with travel details via email and WhatsApp
     */
    public function notifyTravelDetails(Request $request, $leadId)
    {
        // Increase execution time limit for PDF generation
        set_time_limit(120);
        
        try {
            $lead = NewLead::with(['travelDetails', 'leadOwner', 'company', 'addedBy'])->findOrFail($leadId);
            
            if (!$lead->travelDetails) {
                return Reply::error('Travel details not found for this lead.');
            }

            $travelDetails = $lead->travelDetails;

            // Get lead email from step_1_data or client_email
            $leadEmail = null;
            if ($lead->step_1_data) {
                $step1Data = is_string($lead->step_1_data) ? json_decode($lead->step_1_data, true) : $lead->step_1_data;
                if (is_array($step1Data)) {
                    $leadEmail = $step1Data['email_address'] ?? null;
                }
            }
            
            if (empty($leadEmail)) {
                $leadEmail = $lead->client_email;
            }

            // Get lead name
            $leadName = $lead->client_name ?? 'Client';
            if ($lead->step_1_data) {
                $step1Data = is_string($lead->step_1_data) ? json_decode($lead->step_1_data, true) : $lead->step_1_data;
                if (is_array($step1Data)) {
                    $givenName = $step1Data['given_name'] ?? '';
                    $surname = $step1Data['surname'] ?? '';
                    if ($givenName || $surname) {
                        $leadName = trim($givenName . ' ' . $surname) ?: $leadName;
                    }
                }
            }

            // Get consultant details
            $consultantName = 'Our Team';
            $consultantNumber = '';
            
            if ($lead->lead_owner) {
                $assignedUser = User::find($lead->lead_owner);
                if ($assignedUser) {
                    $consultantName = $assignedUser->name ?? 'Our Team';
                    $consultantNumber = $assignedUser->mobile ?? $assignedUser->phone ?? '';
                    // Format consultant number - keep only 10 digits (no country code)
                    if ($consultantNumber) {
                        $consultantNumber = preg_replace('/[^0-9]/', '', $consultantNumber);
                        $consultantNumber = ltrim($consultantNumber, '0');
                        if (strlen($consultantNumber) > 10) {
                            $consultantNumber = substr($consultantNumber, -10);
                        }
                    }
                }
            }

            // Generate PDF with travel details
            $pdf = $this->generateTravelDetailsPDF($lead, $travelDetails);
            $pdfContent = $pdf->output();
            $pdfFilename = 'Travel_Details_' . $lead->id . '_' . date('Y-m-d_His') . '.pdf';

            // Send email with PDF attachment
            if ($leadEmail && filter_var($leadEmail, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($leadEmail)->send(new \App\Mail\TravelDetailsNotification($lead, $travelDetails, $pdfContent, $pdfFilename, $leadName, $consultantName, $consultantNumber));
                    \Log::info('Travel details email sent successfully to: ' . $leadEmail);
                } catch (\Exception $e) {
                    \Log::error('Failed to send travel details email: ' . $e->getMessage());
                }
            }

            // Send WhatsApp notification
            $this->sendTravelDetailsWhatsApp($lead, $travelDetails, $leadName, $consultantName, $consultantNumber, $pdfContent, $pdfFilename);

            return Reply::success('Travel details notification sent successfully via email and WhatsApp.');
        } catch (\Exception $e) {
            \Log::error('Failed to notify travel details: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return Reply::error('Failed to send notification: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF for travel details
     */
    private function generateTravelDetailsPDF(NewLead $lead, $travelDetails)
    {
        // Use same logo URL as emails (hardcoded Google Drive URL)
        $logoUrl = 'https://lh3.googleusercontent.com/d/1o50KgJxSNFJCYEUOTEx33wBYK5LLD2Wc';

        // Convert logo to base64 to avoid remote loading timeout
        $logoBase64 = '';
        try {
            $ch = curl_init($logoUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $logoContent = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($logoContent !== false && $httpCode == 200) {
                $imageInfo = @getimagesizefromstring($logoContent);
                if ($imageInfo !== false) {
                    $mimeType = $imageInfo['mime'];
                    $logoBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($logoContent);
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to load logo for PDF: ' . $e->getMessage());
        }
        
        // Fallback to URL if base64 conversion failed
        if (empty($logoBase64)) {
            $logoBase64 = $logoUrl;
        }

        // Format dates
        $formatDate = function($date) {
            if (empty($date)) return 'N/A';
            try {
                if (is_string($date)) {
                    $dateObj = \Carbon\Carbon::parse($date);
                    return $dateObj->format('d-M-Y');
                }
                return $date->format('d-M-Y');
            } catch (\Exception $e) {
                return $date;
            }
        };

        $data = [
            'lead' => $lead,
            'travelDetails' => $travelDetails,
            'logoUrl' => $logoBase64,
            'formatDate' => $formatDate,
            'getValue' => function($value, $default = 'N/A') {
                return !empty($value) ? $value : $default;
            }
        ];

        $pdf = app('dompdf.wrapper');
        $pdf->setOption('enable_php', true);
        // Disable remote loading to avoid timeout, use base64 logo instead
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true, 
            'isRemoteEnabled' => false,
            'defaultFont' => 'DejaVu Sans'
        ]);
        $pdf->loadView('lead-details.pdf.travel-details', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf;
    }

    /**
     * Send WhatsApp notification for travel details
     */
    private function sendTravelDetailsWhatsApp(NewLead $lead, $travelDetails, $leadName, $consultantName, $consultantNumber, $pdfContent, $pdfFilename)
    {
        try {
            // Get lead phone number
            $leadPhone = null;
            if ($lead->step_1_data) {
                $step1Data = is_string($lead->step_1_data) ? json_decode($lead->step_1_data, true) : $lead->step_1_data;
                if (is_array($step1Data)) {
                    $leadPhone = $step1Data['primary_phone'] ?? $step1Data['mobile'] ?? null;
                }
            }
            
            if (empty($leadPhone)) {
                $leadPhone = $lead->mobile;
            }

            if (empty($leadPhone)) {
                \Log::warning('Lead phone number is not available for WhatsApp notification. Lead ID: ' . $lead->id);
                return;
            }

            // Format phone number (10 digits)
            $leadPhone = preg_replace('/[^0-9]/', '', $leadPhone);
            $leadPhone = ltrim($leadPhone, '0');
            if (strlen($leadPhone) > 10) {
                $leadPhone = substr($leadPhone, -10);
            }

            // Upload PDF to get URL for WhatsApp
            $folderPath = 'travel-details-pdfs';
            $fileVisibility = [];
            if (config('filesystems.default') == 'local') {
                $fileVisibility = ['directory_visibility' => 'public', 'visibility' => 'public'];
            }
            
            // Save PDF to public folder first (required for saveFileInfo)
            $publicFolderPath = \App\Helper\Files::UPLOAD_FOLDER . '/' . $folderPath;
            $publicFilePath = public_path($publicFolderPath);
            
            // Create directory if it doesn't exist
            if (!\File::exists($publicFilePath)) {
                \File::makeDirectory($publicFilePath, 0755, true);
            }
            
            // Save to public folder
            $publicFileFullPath = $publicFilePath . '/' . $pdfFilename;
            \File::put($publicFileFullPath, $pdfContent);
            
            // Store file information in database (requires file to exist in public folder)
            try {
                \App\Helper\Files::saveFileInfo($pdfFilename, $folderPath, $lead->company_id ?? company()->id ?? null);
            } catch (\Exception $e) {
                \Log::warning('Failed to save file info: ' . $e->getMessage());
            }
            
            // Upload PDF content to storage (cloud storage if configured)
            $documentUrl = '';
            try {
                \Storage::disk(config('filesystems.default'))->put($folderPath . '/' . $pdfFilename, $pdfContent, $fileVisibility);
                $documentUrl = asset_url_local_s3($folderPath . '/' . $pdfFilename);
                
                // Ensure URL is absolute and publicly accessible
                if (!empty($documentUrl) && !\Str::startsWith($documentUrl, 'http')) {
                    $documentUrl = url($documentUrl);
                }
                
                \Log::info('Travel details PDF URL generated: ' . $documentUrl);
            } catch (\Exception $e) {
                \Log::error('Failed to upload travel details PDF to storage: ' . $e->getMessage());
                // Fallback to public URL - ensure it's absolute
                $documentUrl = url($publicFolderPath . '/' . $pdfFilename);
                \Log::info('Using fallback PDF URL: ' . $documentUrl);
            }
            
            // Final check - ensure we have a valid URL
            if (empty($documentUrl)) {
                \Log::error('Travel details PDF URL is empty. File path: ' . $folderPath . '/' . $pdfFilename);
                $documentUrl = url($publicFolderPath . '/' . $pdfFilename);
            }
            
            // Log the final URL being sent to WhatsApp API
            \Log::info('Sending travel details PDF to WhatsApp. URL: ' . $documentUrl . ', Filename: ' . $pdfFilename);
            
            // Verify PDF file exists and is accessible
            if (\File::exists($publicFileFullPath)) {
                $fileSize = \File::size($publicFileFullPath);
                \Log::info('PDF file verified. Size: ' . $fileSize . ' bytes');
            } else {
                \Log::warning('PDF file not found at: ' . $publicFileFullPath);
            }

            // API configuration - use journey_details campaign for travel details
            $apiKey = env('AISENSY_API_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjY4YzdmM2RjNmZhOGUxMDEzYzdlMDgzZSIsIm5hbWUiOiJSLlIgcGF0ZWwgIG5ldyIsImFwcE5hbWUiOiJBaVNlbnN5IiwiY2xpZW50SWQiOiI2ODcyMzU5ZGRlNjFiYjMxOTgzMzc2NDMiLCJhY3RpdmVQbGFuIjoiQkFTSUNfTU9OVEhMWSIsImlhdCI6MTc2MDM1NTc4OX0.6H8mv7r3R0ucc7APyDM1q0xew4-oBUVKqUHA38klVG4');
            $campaignName = env('AISENSY_TRAVEL_DETAILS_CAMPAIGN', 'journey_details');
            $userName = env('AISENSY_USER_NAME', 'R.R patel  new');
            $source = env('AISENSY_SOURCE', 'new-landing-page form');

            // Prepare template parameters - journey_details template requires 15 parameters
            // Map travel details to template parameters
            $formatDate = function($date) {
                if (empty($date)) return 'N/A';
                try {
                    if (is_string($date)) {
                        $dateObj = \Carbon\Carbon::parse($date);
                        return $dateObj->format('d-M-Y');
                    }
                    return $date->format('d-M-Y');
                } catch (\Exception $e) {
                    return $date;
                }
            };
            
            $getValue = function($value, $default = 'N/A') {
                return !empty($value) ? $value : $default;
            };
            
            // Prepare 15 template parameters based on travel details
            $templateParams = [
                $leadName, // 1. Lead name
                $getValue($travelDetails->purpose_of_trip), // 2. Purpose of trip
                $getValue($travelDetails->place_to_visit), // 3. Place to visit
                $formatDate($travelDetails->date_of_arrival), // 4. Arrival date
                $getValue($travelDetails->arrival_flight), // 5. Arrival flight
                $getValue($travelDetails->arrival_city), // 6. Arrival city
                $formatDate($travelDetails->date_of_departure), // 7. Departure date
                $getValue($travelDetails->departure_flight), // 8. Departure flight
                $getValue($travelDetails->departure_city), // 9. Departure city
                $getValue($travelDetails->address_stay), // 10. Address
                $getValue($travelDetails->city), // 11. City
                $getValue($travelDetails->state), // 12. State
                $getValue($travelDetails->postal_code), // 13. Postal code
                $consultantName, // 14. Consultant name
                $consultantNumber ?: 'N/A' // 15. Consultant number
            ];

            // Prepare API request payload
            $payload = [
                'apiKey' => $apiKey,
                'campaignName' => $campaignName,
                'destination' => $leadPhone,
                'userName' => $userName,
                'templateParams' => $templateParams,
                'source' => $source,
                'media' => [
                    'url' => $documentUrl ?: '',
                    'filename' => $pdfFilename
                ],
                'buttons' => [],
                'carouselCards' => [],
                'location' => (object)[],
                'attributes' => (object)[],
                'paramsFallbackValue' => [
                    'FirstName' => $leadName
                ]
            ];
            
            // Log payload for debugging (without sensitive data)
            \Log::info('Travel details WhatsApp payload: ' . json_encode([
                'campaignName' => $campaignName,
                'destination' => $leadPhone,
                'media_url' => $documentUrl,
                'media_filename' => $pdfFilename,
                'templateParams_count' => count($templateParams)
            ]));

            // Make API call to AISensy
            $client = new Client();
            $response = $client->post('https://backend.aisensy.com/campaign/t1/api/v2', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
                'timeout' => 30
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);

            // Check both status code and response body for success
            if ($response->getStatusCode() === 200) {
                // Check if response indicates success (some APIs return 200 with error in body)
                if (isset($responseBody['status']) && $responseBody['status'] === 'success') {
                    \Log::info('Travel details WhatsApp notification sent successfully. Lead ID: ' . $lead->id . ', Phone: ' . $leadPhone);
                } elseif (isset($responseBody['message']) && strpos(strtolower($responseBody['message']), 'error') !== false) {
                    \Log::error('AISensy API error in response body for travel details WhatsApp: ' . json_encode($responseBody));
                } else {
                    // Log full response for debugging
                    \Log::info('Travel details WhatsApp notification response: ' . json_encode($responseBody));
                    \Log::info('Travel details WhatsApp notification sent successfully. Lead ID: ' . $lead->id . ', Phone: ' . $leadPhone);
                }
            } else {
                \Log::error('AISensy API error for travel details WhatsApp: Status ' . $response->getStatusCode() . ', Response: ' . json_encode($responseBody));
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errorMessage = $e->getMessage();
            $errorResponse = null;
            try {
                $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);
                if (is_array($errorResponse) && isset($errorResponse['message'])) {
                    $errorMessage = $errorResponse['message'];
                }
            } catch (\Exception $ex) {
                // If we can't parse the error response, use the original message
            }
            \Log::error('AISensy API client error for travel details WhatsApp: ' . $e->getMessage());
            if ($errorResponse) {
                \Log::error('AISensy API error response body for travel details: ' . json_encode($errorResponse));
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            \Log::error('AISensy API request error for travel details WhatsApp: ' . $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Failed to send travel details WhatsApp notification: ' . $e->getMessage());
            \Log::error('Exception trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * Upload profile image for a lead
     *
     * @param \Illuminate\Http\Request $request
     * @param int $leadId
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadProfileImage(Request $request, $leadId)
    {
        $lead = NewLead::findOrFail($leadId);
        
        // Check permissions
        $this->editPermission = user()->permission('edit_lead');
        abort_403(!($this->editPermission == 'all'
            || ($this->editPermission == 'added' && $lead->added_by == user()->id)
            || ($this->editPermission == 'owned' && $lead->lead_owner == user()->id)
            || ($this->editPermission == 'both' && ($lead->added_by == user()->id || $lead->lead_owner == user()->id))
        ));

        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Delete old profile image if exists
            if ($lead->profile_image) {
                \App\Helper\Files::deleteFile($lead->profile_image, 'lead-profile-images');
            }

            // Upload new profile image
            $imageName = \App\Helper\Files::uploadLocalOrS3($request->file('profile_image'), 'lead-profile-images', 200, 200);
            
            // Update lead with new profile image
            $lead->profile_image = $imageName;
            $lead->last_updated_by = user()->id;
            $lead->save();

            $imageUrl = asset_url_local_s3('lead-profile-images/' . $imageName);

            return Reply::successWithData(__('messages.updateSuccess'), [
                'image_url' => $imageUrl,
                'image_name' => $imageName
            ]);
        } catch (\Exception $e) {
            return Reply::error(__('messages.errorOccured') . ': ' . $e->getMessage());
        }
    }

    /**
     * Download import template file
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadImportTemplate()
    {
        abort_403(!in_array('admin', user_roles()));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = ['Surname', 'Given Name', 'Primary Phone No', 'Email'];
        $sheet->fromArray($headers, null, 'A1');

        // Style header row
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E0E0E0']
            ]
        ];
        $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(30);

        // Add sample data row
        $sampleData = ['Doe', 'John', '+1234567890', 'john.doe@example.com'];
        $sheet->fromArray($sampleData, null, 'A2');

        $writer = new Xlsx($spreadsheet);
        $filename = 'lead_import_template.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Import new leads from Excel file
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importNewLeads(Request $request)
    {
        $userRoles = user_roles();
        abort_403(!in_array('admin', $userRoles) && !in_array('receptionist', $userRoles));

        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls|max:10240',
            'added_by' => 'required|exists:users,id',
        ]);

        try {
            $file = $request->file('import_file');
            $addedBy = $request->added_by;

            $data = \Maatwebsite\Excel\Facades\Excel::toArray([], $file);
            
            if (empty($data) || empty($data[0])) {
                return Reply::error('The file is empty or invalid.');
            }

            $rows = $data[0];
            $headerRow = array_shift($rows); // Remove header row

            // Validate headers
            $expectedHeaders = ['Surname', 'Given Name', 'Primary Phone No', 'Email'];
            $headerMap = [];
            foreach ($expectedHeaders as $expected) {
                $index = array_search($expected, $headerRow);
                if ($index === false) {
                    return Reply::error("Missing required column: {$expected}");
                }
                $headerMap[$expected] = $index;
            }

            $imported = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                $rowNum = $index + 2; // +2 because we removed header and arrays are 0-indexed

                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                $surname = trim($row[$headerMap['Surname']] ?? '');
                $givenName = trim($row[$headerMap['Given Name']] ?? '');
                $primaryPhone = trim($row[$headerMap['Primary Phone No']] ?? '');
                $email = trim($row[$headerMap['Email']] ?? '');

                // Validate required fields
                if (empty($surname)) {
                    $errors[] = "Row {$rowNum}: Surname is required";
                    continue;
                }
                if (empty($givenName)) {
                    $errors[] = "Row {$rowNum}: Given Name is required";
                    continue;
                }
                if (empty($primaryPhone)) {
                    $errors[] = "Row {$rowNum}: Primary Phone No is required";
                    continue;
                }
                if (empty($email)) {
                    $errors[] = "Row {$rowNum}: Email is required";
                    continue;
                }

                // Validate email format
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Row {$rowNum}: Invalid email format: {$email}";
                    continue;
                }

                // Check for duplicate email
                $existingLead = NewLead::where('client_email', $email)
                    ->where('company_id', company()->id)
                    ->first();

                if ($existingLead) {
                    $errors[] = "Row {$rowNum}: Lead with email {$email} already exists";
                    continue;
                }

                // Create new lead
                $lead = new NewLead();
                $lead->company_id = company()->id;
                $lead->client_name = trim($surname . ' ' . $givenName);
                $lead->client_email = $email;
                $lead->mobile = $primaryPhone;
                $lead->added_by = $addedBy;
                $lead->last_updated_by = user()->id;
                $lead->lead_status = 'Open Lead';
                $lead->lead_quality = 'Open';

                // Store in step_1_data
                $lead->step_1_data = [
                    'surname' => $surname,
                    'given_name' => $givenName,
                    'primary_phone' => $primaryPhone,
                    'email_address' => $email,
                ];

                $lead->save();

                // Create lead step status entry with all steps set to 0 (false) and final_status as 'draft'
                LeadStepStatus::create([
                    'lead_id' => $lead->id,
                    'step_1_completed' => false,
                    'step_2_completed' => false,
                    'step_3_completed' => false,
                    'step_4_completed' => false,
                    'step_5_completed' => false,
                    'step_6_completed' => false,
                    'step_7_completed' => false,
                    'step_8_completed' => false,
                    'step_9_completed' => false,
                    'final_status' => 'draft',
                ]);

                $imported++;
            }

            $message = "Successfully imported {$imported} lead(s).";
            if (!empty($errors)) {
                $message .= " " . count($errors) . " error(s) occurred.";
                \Log::warning('Lead import errors', ['errors' => $errors]);
            }

            return Reply::successWithData($message, [
                'imported' => $imported,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            \Log::error('Lead import error: ' . $e->getMessage());
            return Reply::error('Failed to import leads: ' . $e->getMessage());
        }
    }

    /**
     * Export lead list to Excel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportLeadList(Request $request)
    {
        // Only admin can export
        abort_403(!in_array('admin', user_roles()));

        $filename = 'lead_list_' . date('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new LeadListExport($request), $filename);
    }

    /**
     * Book appointment for a lead
     *
     * @param Request $request
     * @param int $leadId
     * @return \Illuminate\Http\Response
     */
    public function bookAppointment(Request $request, $leadId)
    {
        try {
            $lead = NewLead::findOrFail($leadId);
            
            // Validate required fields
            $request->validate([
                'meeting_title' => 'required|string|max:255',
                'appointment_date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'required',
            ]);

            // Get company timezone and date/time format
            $company = company();
            $companyTimezone = $company->timezone ?? 'Asia/Kolkata';
            $dateFormat = $company->date_format ?? 'Y-m-d';
            $timeFormat = $company->time_format ?? 'H:i';

            // Try to get lead's timezone from step_1_data (country_of_origin)
            // If not available, use company timezone
            $leadTimezone = $companyTimezone;
            if ($lead->step_1_data && is_array($lead->step_1_data)) {
                $countryOfOrigin = $lead->step_1_data['country_of_origin'] ?? null;
                if (!empty($countryOfOrigin)) {
                    // Try to get timezone from country
                    $leadTimezone = $this->getTimezoneFromCountry($countryOfOrigin, $companyTimezone);
                    \Log::info('Book Appointment: Lead timezone detected', [
                        'country_of_origin' => $countryOfOrigin,
                        'lead_timezone' => $leadTimezone,
                        'company_timezone' => $companyTimezone
                    ]);
                }
            }

            // Parse date and time using lead's timezone (or company timezone as fallback)
            $appointmentDate = Carbon::createFromFormat($dateFormat, $request->appointment_date, $leadTimezone);
            $startTime = Carbon::createFromFormat($timeFormat, $request->start_time, $leadTimezone);
            $endTime = Carbon::createFromFormat($timeFormat, $request->end_time, $leadTimezone);

            // Combine date and time
            $startDateTime = $appointmentDate->copy()->setTime($startTime->hour, $startTime->minute, 0);
            $endDateTime = $appointmentDate->copy()->setTime($endTime->hour, $endTime->minute, 0);

            // Validate that appointment is in the future
            $now = Carbon::now($leadTimezone);
            if ($startDateTime->lte($now)) {
                return Reply::error('Appointment date and time must be in the future.');
            }

            // Validate that end time is after start time
            if ($endDateTime->lte($startDateTime)) {
                return Reply::error('End time must be after start time.');
            }

            // Try to use logged-in user's Google token first (so they can join directly)
            // If not available, fall back to admin's token
            $currentUser = user(); // Keep for logging who created the appointment
            
            \Log::info('Book Appointment: Current user', [
                'user_id' => $currentUser->id,
                'user_name' => $currentUser->name,
                'auth_user_id' => auth()->id()
            ]);
            
            // First, try to get logged-in user's Google token
            /* 
            $googleToken = NewGoogleToken::where('user_id', $currentUser->id)
                ->where('company_id', $company->id)
                ->where('verification_status', 'verified')
                ->first();
            
            $tokenOwner = $currentUser;
            $usingAdminToken = false;
            
            // If logged-in user doesn't have a token, fall back to admin's token
            if (!$googleToken || empty($googleToken->access_token)) {
                \Log::info('Book Appointment: Logged-in user has no token, trying admin token');
                $adminUsers = User::allAdmins($company->id);
                
                if ($adminUsers->isEmpty()) {
                    \Log::error('Book Appointment: No admin user found', [
                        'company_id' => $company->id
                    ]);
                    return Reply::error('No admin user found. Please ensure an admin user has connected their Google Calendar.');
                }
                
                // Get the first admin user's Google token
                $adminUser = $adminUsers->first();
                $googleToken = NewGoogleToken::where('user_id', $adminUser->id)
                    ->where('company_id', $company->id)
                    ->where('verification_status', 'verified')
                    ->first();
                
                if (!$googleToken || empty($googleToken->access_token)) {
                    \Log::error('Book Appointment: Admin Google token not found', [
                        'admin_user_id' => $adminUser->id,
                        'admin_user_email' => $adminUser->email,
                        'company_id' => $company->id
                    ]);
                    return Reply::error('Google Calendar is not authenticated. Please connect your Google Calendar or ensure an admin has connected theirs.');
                }
                
                $tokenOwner = $adminUser;
                $usingAdminToken = true;
            }
            
            \Log::info('Book Appointment: Using Google token', [
                'token_owner_id' => $tokenOwner->id,
                'token_owner_name' => $tokenOwner->name,
                'token_owner_email' => $tokenOwner->email,
                'using_admin_token' => $usingAdminToken,
                'created_by_user_id' => $currentUser->id,
                'created_by_user_name' => $currentUser->name
            ]);

            // Create Google Meet link first - MUST succeed before saving appointment
            $googleMeetLink = null;
            $googleEventId = null;

            try {
                // Get token array from model first
                $tokenArray = $googleToken->getTokenArray();
                if (!$tokenArray) {
                    throw new \Exception('Invalid token format in database');
                }

                // Create Google service instance
                $google = new Google();
                $client = $google->getClient();
                
                // Check if token needs refresh (Google access tokens expire in ~1 hour)
                $tokenCreated = $tokenArray['created'] ?? ($googleToken->created_at ? $googleToken->created_at->timestamp : time());
                $tokenAge = time() - $tokenCreated;
                
                // If token is older than 50 minutes (3000 seconds), refresh it before using
                if ($tokenAge > 3000 && !empty($googleToken->refresh_token)) {
                    \Log::info('Google Calendar: Token is expired/expiring, refreshing before API call');
                    try {
                        // Refresh the token using refresh_token
                        $client->refreshToken($googleToken->refresh_token);
                        
                        // Get the new token from the client
                        // Note: After refreshToken(), the client has the new token internally
                        // We try to get it, but if it fails (MAC error), we continue anyway
                        try {
                            $newToken = $client->getAccessToken();
                            if ($newToken) {
                                $newTokenArray = is_string($newToken) ? json_decode($newToken, true) : $newToken;
                                if (is_array($newTokenArray) && isset($newTokenArray['access_token'])) {
                                    // Update token array for current use
                                    $tokenArray = $newTokenArray;
                                    $tokenArray['created'] = time();
                                    
                                    // Update stored token in database
                                    $googleToken->access_token = $newTokenArray['access_token'];
                                    // Keep refresh_token if provided, otherwise keep existing
                                    if (isset($newTokenArray['refresh_token']) && !empty($newTokenArray['refresh_token'])) {
                                        $googleToken->refresh_token = $newTokenArray['refresh_token'];
                                    }
                                    $googleToken->save();
                                    \Log::info('Google Calendar: Token refreshed and saved to database');
                                }
                            }
                        } catch (\Exception $tokenError) {
                            // If getAccessToken() fails (e.g., MAC error), log but continue
                            // The refresh already happened, the client has the new token internally
                            \Log::warning('Google Calendar: Token refreshed but could not retrieve/save: ' . $tokenError->getMessage());
                        }
                    } catch (\Exception $refreshError) {
                        // If refresh fails, log but continue - might still work with existing token
                        \Log::warning('Google Calendar: Token refresh failed, will try with existing token: ' . $refreshError->getMessage());
                    }
                }
                
                // Set access token on client (either original or newly refreshed)
                $google->connectUsing($tokenArray);

                // Get lead email
                $leadEmail = null;
                if ($lead->step_1_data && is_array($lead->step_1_data)) {
                    $leadEmail = $lead->step_1_data['email_address'] ?? null;
                }
                if (empty($leadEmail)) {
                    $leadEmail = $lead->client_email;
                }

                // Get owner/creator email
                $ownerEmail = $currentUser->email;

                // Prepare attendees with proper Google Calendar attendee objects
                // This ensures proper permissions and response status
                $attendeesList = [];
                
                // Add lead as attendee
                if (!empty($leadEmail)) {
                    $leadAttendee = new \Google_Service_Calendar_EventAttendee();
                    $leadAttendee->setEmail($leadEmail);
                    $attendeesList[] = $leadAttendee;
                }
                
                // Add logged-in user as attendee with accepted status
                // Note: When using admin's token, Google Meet will still see admin as host
                // The only way to avoid "waiting" is to use logged-in user's own token
                if (!empty($ownerEmail)) {
                    $userAttendee = new \Google_Service_Calendar_EventAttendee();
                    $userAttendee->setEmail($ownerEmail);
                    $userAttendee->setResponseStatus('accepted');
                    $attendeesList[] = $userAttendee;
                }

                // Create conference data for Google Meet
                $conferenceData = new \Google_Service_Calendar_ConferenceData();
                $conferenceRequest = new \Google_Service_Calendar_CreateConferenceRequest();
                $conferenceRequest->setRequestId(uniqid());
                $conferenceData->setCreateRequest($conferenceRequest);

                // Create Google Calendar event with Meet
                // Use lead's timezone so it displays correctly in Google Calendar
                $eventData = new \Google_Service_Calendar_Event([
                    'summary' => $request->meeting_title,
                    'description' => $request->description ?? '',
                    'location' => 'Google Meet',
                    'start' => [
                        'dateTime' => $startDateTime->format('Y-m-d\TH:i:s'),
                        'timeZone' => $leadTimezone,
                    ],
                    'end' => [
                        'dateTime' => $endDateTime->format('Y-m-d\TH:i:s'),
                        'timeZone' => $leadTimezone,
                    ],
                    'attendees' => $attendeesList,
                    'conferenceData' => $conferenceData,
                    'reminders' => [
                        'useDefault' => false,
                        'overrides' => [
                            ['method' => 'email', 'minutes' => 24 * 60],
                            ['method' => 'popup', 'minutes' => 10],
                        ],
                    ],
                ]);

                // Set logged-in user as the organizer/owner of the meeting
                if (!empty($ownerEmail)) {
                    $organizer = new \Google_Service_Calendar_EventOrganizer();
                    $organizer->setEmail($ownerEmail);
                    $organizer->setDisplayName($currentUser->name ?? $ownerEmail);
                    $eventData->setOrganizer($organizer);
                    
                    \Log::info('Book Appointment: Set organizer', [
                        'organizer_email' => $ownerEmail,
                        'organizer_name' => $currentUser->name,
                        'user_id' => $currentUser->id,
                        'using_admin_token' => $usingAdminToken,
                        'token_owner_email' => $tokenOwner->email ?? 'unknown'
                    ]);
                    
                    if ($usingAdminToken) {
                        \Log::warning('Book Appointment: Using admin token - logged-in user may see "Please wait" message. To avoid this, user should connect their own Google Calendar.');
                    }
                }

                // Insert event with conference data
                $calendarId = $googleToken->calendar_id ?? 'primary';
                $results = $google->service('Calendar')->events->insert($calendarId, $eventData, [
                    'conferenceDataVersion' => 1
                ]);

                $googleEventId = $results->id;

                // Extract Google Meet link from conference data
                if ($results->getConferenceData() && $results->getConferenceData()->getEntryPoints()) {
                    foreach ($results->getConferenceData()->getEntryPoints() as $entryPoint) {
                        if ($entryPoint->getEntryPointType() == 'video') {
                            $googleMeetLink = $entryPoint->getUri();
                            break;
                        }
                    }
                }

                if (empty($googleMeetLink)) {
                    throw new \Exception('Google Meet link was not created. Conference data may be missing.');
                }

            } catch (\Exception $e) {
                \Log::error('Google Calendar API error: ' . $e->getMessage());
                \Log::error('Google Calendar API error class: ' . get_class($e));
                \Log::error('Google Calendar API error trace: ' . $e->getTraceAsString());
                
                // Check if this is a MAC error (session encryption issue)
                if (strpos($e->getMessage(), 'MAC is invalid') !== false || 
                    strpos($e->getMessage(), 'The MAC is invalid') !== false) {
                    return Reply::error('Google Calendar authentication error. Please logout and login again with Google to refresh your tokens.');
                }
                
                return Reply::error('Failed to create Google Meet link: ' . $e->getMessage());
            } */

            // Create Zoom Meeting
            $zoomLink = null;
            $zoomMeetingId = null;
            $zoomMeetingPassword = null;

            $zoomSettings = SocialAuthSetting::first();
            
            if ($zoomSettings && $zoomSettings->zoom_status == 'enable' && !empty($zoomSettings->zoom_client_id)) {
                try {
                    // Get Zoom access token using account_credentials grant type as suggested by user
                    $response = Http::withBasicAuth(
                        $zoomSettings->zoom_client_id,
                        $zoomSettings->zoom_client_secret
                    )->asForm()->post('https://zoom.us/oauth/token', [
                        'grant_type' => 'account_credentials',
                        'account_id' => $zoomSettings->zoom_account_id,
                    ]);

                    if ($response->failed()) {
                        throw new \Exception('Failed to get Zoom access token: ' . $response->body());
                    }

                    $accessToken = $response->json()['access_token'];
                    $duration = $startDateTime->diffInMinutes($endDateTime);

                    // Create Zoom meeting using the API directly
                    $meetingResponse = Http::withToken($accessToken)
                        ->post("https://api.zoom.us/v2/users/me/meetings", [
                            'topic' => $request->meeting_title,
                            'type' => 2, // Scheduled meeting
                            'start_time' => $startDateTime->format('Y-m-d\TH:i:s'),
                            'duration' => $duration,
                            'timezone' => $leadTimezone,
                            'agenda' => $request->description ?? null,
                            'settings' => [
                                'host_video' => true,
                                'participant_video' => true,
                                'join_before_host' => true,
                                'mute_upon_entry' => true,
                                'waiting_room' => false,
                            ],
                        ]);

                    if ($meetingResponse->failed()) {
                        throw new \Exception('Zoom meeting creation failed: ' . $meetingResponse->body());
                    }

                    $responseData = $meetingResponse->json();
                    $zoomLink = $responseData['join_url'];
                    $zoomMeetingId = $responseData['id'];
                    $zoomMeetingPassword = $responseData['password'] ?? null;

                } catch (\Exception $e) {
                    \Log::error('Zoom API error: ' . $e->getMessage());
                    return Reply::error('Failed to create Zoom link: ' . $e->getMessage());
                }
            } else {
                return Reply::error('Zoom integration is not enabled or configured.');
            }

            // Convert to IST for storage
            $startDateTimeIST = $startDateTime->setTimezone('Asia/Kolkata');
            $endDateTimeIST = $endDateTime->setTimezone('Asia/Kolkata');

            // Create appointment entry in database ONLY if Zoom link was successfully created
            $appointment = new NewLeadAppointment();
            $appointment->company_id = $company->id;
            $appointment->lead_id = $lead->id;
            $appointment->meeting_title = $request->meeting_title;
            $appointment->description = $request->description ?? null;
            $appointment->appointment_date = $startDateTimeIST;
            $appointment->start_time = $startDateTimeIST;
            $appointment->end_time = $endDateTimeIST;
            $appointment->zoom_link = $zoomLink;
            $appointment->zoom_meeting_id = $zoomMeetingId;
            $appointment->zoom_meeting_password = $zoomMeetingPassword;
            $appointment->created_by = $currentUser->id;
            $appointment->updated_by = $currentUser->id;
            $appointment->save();
            
            \Log::info('Book Appointment: Appointment saved', [
                'appointment_id' => $appointment->id,
                'created_by' => $appointment->created_by,
                'current_user_id' => $currentUser->id
            ]);

            // Send email to lead
            try {
                $leadEmail = $lead->routeNotificationForMail();
                \Log::info('Sending appointment email to lead', [
                    'lead_id' => $lead->id,
                    'lead_email' => $leadEmail
                ]);
                
                if ($leadEmail) {
                    \Mail::to($leadEmail)->send(new LeadAppointmentBookedToLead($appointment));
                    \Log::info('Appointment email sent to lead');
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send appointment email to lead: ' . $e->getMessage());
            }

            // Send email to login user
            try {
                \Log::info('Sending appointment email to user', [
                    'user_id' => $currentUser->id,
                    'user_email' => $currentUser->email
                ]);
                
                if ($currentUser->email) {
                    \Mail::to($currentUser->email)->send(new LeadAppointmentBookedToUser($appointment, $currentUser));
                    \Log::info('Appointment email sent to user');
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send appointment email to user: ' . $e->getMessage());
            }

            return Reply::success(__('messages.recordSaved'), [
                'message' => 'Appointment booked successfully!',
                'meet_link' => $zoomLink
            ]);

        } catch (\Exception $e) {
            \Log::error('Book appointment error: ' . $e->getMessage());
            return Reply::error('Failed to book appointment: ' . $e->getMessage());
        }
    }

    /**
     * Show appointment details
     */
    public function showAppointment($id)
    {
        $appointment = NewLeadAppointment::with(['lead', 'creator', 'updater'])->findOrFail($id);
        
        $this->appointment = $appointment;
        $this->pageTitle = 'Appointment Details';
        $this->view = 'lead-details.appointment-show';
        
        // Add to data array
        $this->data['appointment'] = $appointment;

        if (request()->ajax()) {
            return $this->returnAjax($this->view);
        }

        return view('lead-details.appointment-show', $this->data);
    }

    /**
     * Update appointment
     */
    public function updateAppointment(Request $request, $id)
    {
        $appointment = NewLeadAppointment::findOrFail($id);
        
        $request->validate([
            'meeting_title' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        try {
            $company = company();
            $timezone = $company->timezone ?? 'Asia/Kolkata';
            $dateFormat = $company->date_format ?? 'Y-m-d';
            $timeFormat = $company->time_format ?? 'H:i';

            // Parse date and time
            $appointmentDate = Carbon::createFromFormat($dateFormat, $request->appointment_date, $timezone);
            $startTime = Carbon::createFromFormat($timeFormat, $request->start_time, $timezone);
            $endTime = Carbon::createFromFormat($timeFormat, $request->end_time, $timezone);

            // Combine date and time
            $startDateTime = $appointmentDate->copy()->setTime($startTime->hour, $startTime->minute, 0);
            $endDateTime = $appointmentDate->copy()->setTime($endTime->hour, $endTime->minute, 0);

            // Validate that appointment is in the future
            $now = Carbon::now($timezone);
            if ($startDateTime->lte($now)) {
                return Reply::error('Appointment date and time must be in the future.');
            }

            // Validate that end time is after start time
            if ($endDateTime->lte($startDateTime)) {
                return Reply::error('End time must be after start time.');
            }

            // Convert to IST for storage
            $startDateTimeIST = $startDateTime->setTimezone('Asia/Kolkata');
            $endDateTimeIST = $endDateTime->setTimezone('Asia/Kolkata');

            // Get user's Google token for updating calendar event
            $currentUser = user();
            $googleToken = NewGoogleToken::where('user_id', $currentUser->id)
                ->where('company_id', $company->id)
                ->where('verification_status', 'verified')
                ->first();

            // Update Google Calendar event if it exists and user has token
            /* 
            if ($appointment->google_event_id && $googleToken && !empty($googleToken->access_token)) {
                try {
                    // Get token array from model
                    $tokenArray = $googleToken->getTokenArray();
                    if (!$tokenArray) {
                        throw new \Exception('Invalid token format in database');
                    }

                    // Create Google service instance
                    $google = new Google();
                    $client = $google->getClient();
                    
                    // Check if token needs refresh (Google access tokens expire in ~1 hour)
                    $tokenCreated = $tokenArray['created'] ?? ($googleToken->created_at ? $googleToken->created_at->timestamp : time());
                    $tokenAge = time() - $tokenCreated;
                    
                    // If token is older than 50 minutes (3000 seconds), refresh it before using
                    if ($tokenAge > 3000 && !empty($googleToken->refresh_token)) {
                        \Log::info('Update Appointment: Token is expired/expiring, refreshing before API call');
                        try {
                            // Refresh the token using refresh_token
                            $client->refreshToken($googleToken->refresh_token);
                            
                            // Get the new token from the client
                            try {
                                $newToken = $client->getAccessToken();
                                if ($newToken) {
                                    $newTokenArray = is_string($newToken) ? json_decode($newToken, true) : $newToken;
                                    if (is_array($newTokenArray) && isset($newTokenArray['access_token'])) {
                                        // Update token array for current use
                                        $tokenArray = $newTokenArray;
                                        $tokenArray['created'] = time();
                                        
                                        // Update stored token in database
                                        $googleToken->access_token = $newTokenArray['access_token'];
                                        if (isset($newTokenArray['refresh_token']) && !empty($newTokenArray['refresh_token'])) {
                                            $googleToken->refresh_token = $newTokenArray['refresh_token'];
                                        }
                                        $googleToken->save();
                                        \Log::info('Update Appointment: Token refreshed and saved to database');
                                    }
                                }
                            } catch (\Exception $tokenError) {
                                \Log::warning('Update Appointment: Token refreshed but could not retrieve/save: ' . $tokenError->getMessage());
                            }
                        } catch (\Exception $refreshError) {
                            \Log::warning('Update Appointment: Token refresh failed, will try with existing token: ' . $refreshError->getMessage());
                        }
                    }
                    
                    // Set access token on client (either original or newly refreshed)
                    $google->connectUsing($tokenArray);

                    // Get calendar service
                    $calendarId = $googleToken->calendar_id ?? 'primary';

                    // Get the existing event
                    $event = $google->service('Calendar')->events->get($calendarId, $appointment->google_event_id);

                    // Update event details
                    $event->setSummary($request->meeting_title);
                    $event->setDescription($request->description ?? '');

                    // Update start time
                    $startDateTimeGoogle = new \Google_Service_Calendar_EventDateTime();
                    $startDateTimeGoogle->setDateTime($startDateTime->format('Y-m-d\TH:i:s'));
                    $startDateTimeGoogle->setTimeZone($timezone);
                    $event->setStart($startDateTimeGoogle);

                    // Update end time
                    $endDateTimeGoogle = new \Google_Service_Calendar_EventDateTime();
                    $endDateTimeGoogle->setDateTime($endDateTime->format('Y-m-d\TH:i:s'));
                    $endDateTimeGoogle->setTimeZone($timezone);
                    $event->setEnd($endDateTimeGoogle);

                    // Update attendees (lead and creator)
                    $attendees = [];
                    if ($appointment->lead) {
                        // Get lead email from step_1_data or client_email
                        $leadEmail = null;
                        if ($appointment->lead->step_1_data && is_array($appointment->lead->step_1_data)) {
                            $leadEmail = $appointment->lead->step_1_data['email_address'] ?? null;
                        }
                        if (empty($leadEmail)) {
                            $leadEmail = $appointment->lead->client_email;
                        }
                        if (!empty($leadEmail)) {
                            $attendee = new \Google_Service_Calendar_EventAttendee();
                            $attendee->setEmail($leadEmail);
                            $attendees[] = $attendee;
                        }
                    }
                    if ($currentUser->email) {
                        $attendee = new \Google_Service_Calendar_EventAttendee();
                        $attendee->setEmail($currentUser->email);
                        $attendees[] = $attendee;
                    }
                    if (!empty($attendees)) {
                        $event->setAttendees($attendees);
                    }

                    // Update the event in Google Calendar
                    $updatedEvent = $google->service('Calendar')->events->update($calendarId, $appointment->google_event_id, $event);

                    \Log::info('Update Appointment: Google Calendar event updated', [
                        'event_id' => $appointment->google_event_id,
                        'calendar_id' => $calendarId
                    ]);

                } catch (\Exception $e) {
                    \Log::error('Update Appointment: Google Calendar update error', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Continue with database update even if Google update fails
                    // But log the error for debugging
                }
            } */

            // Update Zoom Meeting if exists
            if ($appointment->zoom_meeting_id) {
                $zoomSettings = SocialAuthSetting::first();
                if ($zoomSettings && $zoomSettings->zoom_status == 'enable' && !empty($zoomSettings->zoom_client_id)) {
                    try {
                        // Get Zoom access token
                        $response = Http::withBasicAuth(
                            $zoomSettings->zoom_client_id,
                            $zoomSettings->zoom_client_secret
                        )->asForm()->post('https://zoom.us/oauth/token', [
                            'grant_type' => 'account_credentials',
                            'account_id' => $zoomSettings->zoom_account_id,
                        ]);

                        if ($response->successful()) {
                            $accessToken = $response->json()['access_token'];
                            $duration = $startDateTime->diffInMinutes($endDateTime);

                            // Update Zoom meeting using the API directly
                            Http::withToken($accessToken)
                                ->patch("https://api.zoom.us/v2/meetings/{$appointment->zoom_meeting_id}", [
                                    'topic' => $request->meeting_title,
                                    'type' => 2,
                                    'start_time' => $startDateTime->format('Y-m-d\TH:i:s'),
                                    'duration' => $duration,
                                    'timezone' => $timezone,
                                    'agenda' => $request->description ?? null,
                                ]);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to update Zoom meeting: ' . $e->getMessage());
                    }
                }
            }

            // Update appointment in database
            $appointment->meeting_title = $request->meeting_title;
            $appointment->description = $request->description ?? null;
            $appointment->appointment_date = $startDateTimeIST;
            $appointment->start_time = $startDateTimeIST;
            $appointment->end_time = $endDateTimeIST;
            $appointment->updated_by = $currentUser->id;
            $appointment->save();

            // Refresh appointment with relationships
            $appointment->refresh();
            $appointment->load(['lead', 'creator', 'updater', 'company']);

            // Send email to lead
            try {
                $lead = $appointment->lead;
                if ($lead) {
                    $leadEmail = $lead->routeNotificationForMail();
                    \Log::info('Sending appointment update email to lead', [
                        'lead_id' => $lead->id,
                        'lead_email' => $leadEmail
                    ]);
                    
                    if ($leadEmail) {
                        \Mail::to($leadEmail)->send(new \App\Mail\LeadAppointmentUpdatedToLead($appointment));
                        \Log::info('Appointment update email sent to lead');
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send appointment update email to lead: ' . $e->getMessage());
            }

            // Send email to current user
            try {
                \Log::info('Sending appointment update email to user', [
                    'user_id' => $currentUser->id,
                    'user_email' => $currentUser->email
                ]);
                
                if ($currentUser->email) {
                    \Mail::to($currentUser->email)->send(new \App\Mail\LeadAppointmentUpdatedToUser($appointment, $currentUser));
                    \Log::info('Appointment update email sent to user');
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send appointment update email to user: ' . $e->getMessage());
            }

            return Reply::success(__('messages.updateSuccess'), [
                'redirectUrl' => route('events.index')
            ]);

        } catch (\Exception $e) {
            \Log::error('Update appointment error: ' . $e->getMessage());
            return Reply::error('Failed to update appointment: ' . $e->getMessage());
        }
    }

    /**
     * Cancel appointment
     */
    public function cancelAppointment(Request $request, $id)
    {
        $appointment = NewLeadAppointment::findOrFail($id);
        
        try {
            // Cancel Zoom meeting if it exists
            if ($appointment->zoom_meeting_id) {
                $zoomSettings = SocialAuthSetting::first();
                if ($zoomSettings && $zoomSettings->zoom_status == 'enable' && !empty($zoomSettings->zoom_client_id)) {
                    try {
                        // Get Zoom access token
                        $response = Http::withBasicAuth(
                            $zoomSettings->zoom_client_id,
                            $zoomSettings->zoom_client_secret
                        )->asForm()->post('https://zoom.us/oauth/token', [
                            'grant_type' => 'account_credentials',
                            'account_id' => $zoomSettings->zoom_account_id,
                        ]);

                        if ($response->successful()) {
                            $accessToken = $response->json()['access_token'];

                            // Delete Zoom meeting using the API directly
                            Http::withToken($accessToken)
                                ->delete("https://api.zoom.us/v2/meetings/{$appointment->zoom_meeting_id}");
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to delete Zoom meeting: ' . $e->getMessage());
                    }
                }
            }
            
            // Load relationships before deleting
            $appointment->load(['lead', 'creator', 'updater', 'company']);
            $lead = $appointment->lead;
            $currentUser = user();

            // Send email to lead before deleting
            try {
                if ($lead) {
                    $leadEmail = $lead->routeNotificationForMail();
                    \Log::info('Sending appointment cancellation email to lead', [
                        'lead_id' => $lead->id,
                        'lead_email' => $leadEmail
                    ]);
                    
                    if ($leadEmail) {
                        \Mail::to($leadEmail)->send(new \App\Mail\LeadAppointmentCancelledToLead($appointment));
                        \Log::info('Appointment cancellation email sent to lead');
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send appointment cancellation email to lead: ' . $e->getMessage());
            }

            // Send email to current user before deleting
            try {
                \Log::info('Sending appointment cancellation email to user', [
                    'user_id' => $currentUser->id,
                    'user_email' => $currentUser->email
                ]);
                
                if ($currentUser->email) {
                    \Mail::to($currentUser->email)->send(new \App\Mail\LeadAppointmentCancelledToUser($appointment, $currentUser));
                    \Log::info('Appointment cancellation email sent to user');
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send appointment cancellation email to user: ' . $e->getMessage());
            }

            // Delete appointment
            $appointment->delete();

            return Reply::success(__('messages.deleteSuccess'), [
                'redirectUrl' => route('events.index')
            ]);

        } catch (\Exception $e) {
            \Log::error('Cancel appointment error: ' . $e->getMessage());
            return Reply::error('Failed to cancel appointment: ' . $e->getMessage());
        }
    }

    /**
     * Get timezone from country name
     * Maps common country names to their primary timezone
     */
    private function getTimezoneFromCountry($countryName, $defaultTimezone = 'Asia/Kolkata')
    {
        if (empty($countryName)) {
            return $defaultTimezone;
        }

        // Common country to timezone mapping
        $countryTimezoneMap = [
            'India' => 'Asia/Kolkata',
            'United States' => 'America/New_York',
            'USA' => 'America/New_York',
            'United Kingdom' => 'Europe/London',
            'UK' => 'Europe/London',
            'Canada' => 'America/Toronto',
            'Australia' => 'Australia/Sydney',
            'Germany' => 'Europe/Berlin',
            'France' => 'Europe/Paris',
            'Japan' => 'Asia/Tokyo',
            'China' => 'Asia/Shanghai',
            'Brazil' => 'America/Sao_Paulo',
            'Mexico' => 'America/Mexico_City',
            'Russia' => 'Europe/Moscow',
            'South Korea' => 'Asia/Seoul',
            'Italy' => 'Europe/Rome',
            'Spain' => 'Europe/Madrid',
            'Netherlands' => 'Europe/Amsterdam',
            'Belgium' => 'Europe/Brussels',
            'Switzerland' => 'Europe/Zurich',
            'Sweden' => 'Europe/Stockholm',
            'Norway' => 'Europe/Oslo',
            'Denmark' => 'Europe/Copenhagen',
            'Poland' => 'Europe/Warsaw',
            'Portugal' => 'Europe/Lisbon',
            'Greece' => 'Europe/Athens',
            'Turkey' => 'Europe/Istanbul',
            'Saudi Arabia' => 'Asia/Riyadh',
            'UAE' => 'Asia/Dubai',
            'United Arab Emirates' => 'Asia/Dubai',
            'Singapore' => 'Asia/Singapore',
            'Malaysia' => 'Asia/Kuala_Lumpur',
            'Thailand' => 'Asia/Bangkok',
            'Indonesia' => 'Asia/Jakarta',
            'Philippines' => 'Asia/Manila',
            'Vietnam' => 'Asia/Ho_Chi_Minh',
            'Hong Kong' => 'Asia/Hong_Kong',
            'Taiwan' => 'Asia/Taipei',
            'New Zealand' => 'Pacific/Auckland',
            'South Africa' => 'Africa/Johannesburg',
            'Egypt' => 'Africa/Cairo',
            'Nigeria' => 'Africa/Lagos',
            'Kenya' => 'Africa/Nairobi',
            'Argentina' => 'America/Argentina/Buenos_Aires',
            'Chile' => 'America/Santiago',
            'Colombia' => 'America/Bogota',
            'Peru' => 'America/Lima',
            'Venezuela' => 'America/Caracas',
        ];

        // Try exact match first (case-insensitive)
        $countryNameLower = strtolower(trim($countryName));
        foreach ($countryTimezoneMap as $country => $timezone) {
            if (strtolower($country) === $countryNameLower) {
                \Log::info('Book Appointment: Timezone mapped from country', [
                    'country' => $countryName,
                    'timezone' => $timezone
                ]);
                return $timezone;
            }
        }

        // Try partial match (in case country name has extra text)
        foreach ($countryTimezoneMap as $country => $timezone) {
            if (stripos($countryNameLower, strtolower($country)) !== false || 
                stripos(strtolower($country), $countryNameLower) !== false) {
                \Log::info('Book Appointment: Timezone mapped from country (partial match)', [
                    'country' => $countryName,
                    'matched_country' => $country,
                    'timezone' => $timezone
                ]);
                return $timezone;
            }
        }

        // If no match found, return default
        \Log::info('Book Appointment: Timezone not found for country, using default', [
            'country' => $countryName,
            'default_timezone' => $defaultTimezone
        ]);
        return $defaultTimezone;
    }

}

