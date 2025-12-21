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
use App\Models\NewLead;
use App\Models\NewLeadProcess;
use App\Models\PipelineStage;
use App\Models\LeadStatus;
use App\Models\Product;
use App\Models\User;
use App\Traits\ImportExcel;
use Illuminate\Support\Facades\Schema;
use App\Mail\LeadConfirmation;
use App\Mail\LeadCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

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
                'Untouched',
                'Introduction',
                'Info Collected',
                'Consultation Call 1',
                'Consultation Call 2',
                'Consultation Meet 1',
                'Consultation Meet 2',
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
            
            // Apply view permission filters for "All Leads"
            if ($viewPermission == 'owned') {
                $allLeadsQuery->where('lead_owner', user()->id);
            } elseif ($viewPermission == 'added') {
                $allLeadsQuery->where('added_by', user()->id);
            } elseif ($viewPermission == 'both') {
                $allLeadsQuery->where(function ($query) {
                    $query->where('lead_owner', user()->id)
                          ->orWhere('added_by', user()->id);
                });
            }
            // If 'all', no filter needed
            
            // "My Leads" - only leads assigned to current user (lead_owner)
            $myLeadsQuery->where('lead_owner', user()->id);
            
            $this->allLeadsCount = $allLeadsQuery->count();
            $this->myLeadsCount = $myLeadsQuery->count();
        }

        return $dataTable->render('lead-list.index', $this->data);

    }

    public function addLead()
    {
        $this->addLeadPermission = user()->permission('add_lead');
        abort_403(!in_array($this->addLeadPermission, ['all', 'added']));

        $this->pageTitle = 'app.addLead';

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
        // Get employees from the same organization
        $this->employees = User::allEmployees(null, 'active', null, company()->id);

        // Load visa types from master datatable
        $this->visaTypes = \App\Models\NewLeadVisaType::where(function($query) {
            $query->where('company_id', company()->id)
                  ->orWhereNull('company_id');
        })->orderBy('name')->get();

        // Check if editing existing new lead
        $leadId = request('lead_id');
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

    public function leadDetails($id = null)
    {
        $this->viewLeadPermission = $viewPermission = user()->permission('view_lead');
        abort_403(!in_array($viewPermission, ['all','added','owned','both']));

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
        $this->leadDocuments = [];
        $this->allExpectedDocuments = [];
        
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
        
        if ($id) {
            $this->lead = NewLead::with(['addedBy', 'leadOwner', 'followUps.addedBy', 'followUps.lastUpdatedBy', 'fileNotes.addedBy', 'process', 'accounts.agentUser', 'accounts.addedBy', 'travelDetails'])->find($id);
            if (!$this->lead) {
                abort(404, 'Lead not found');
            }
            
            // Extract all documents from step data
            try {
                $this->leadDocuments = $this->extractAllDocuments($this->lead);
                // Get all expected documents (including missing ones)
                $this->allExpectedDocuments = $this->getAllExpectedDocuments($this->lead);
            } catch (\Exception $e) {
                \Log::error('Error extracting documents for lead ' . $id . ': ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                $this->leadDocuments = [];
                $this->allExpectedDocuments = [];
            }
        }

        if (!request()->ajax()) {
            $this->categories = LeadCategory::get();
            $this->sources = LeadSource::get();
            $this->employees = User::allEmployees(null, 'active');
            $this->templateDocuments = \App\Models\NewLeadTemplateDocument::all();
        }

        return view('lead-details.index', $this->data);
    }
    
    /**
     * Extract all documents from step 1-9 data
     */
    private function extractAllDocuments($lead)
    {
        $documents = [];
        
        if (!$lead) {
            return $documents;
        }
        
        // Helper function to safely get step data
        $getStepData = function($stepData) {
            if (is_string($stepData)) {
                $decoded = json_decode($stepData, true);
                return is_array($decoded) ? $decoded : [];
            }
            return is_array($stepData) ? $stepData : [];
        };
        
        // Step 1 - Resume
        $step1Data = $getStepData($lead->step_1_data ?? null);
        if (!empty($step1Data['upload_resume'])) {
            $documents[] = [
                'key' => 'upload_resume',
                'name' => 'Resume',
                'file' => $step1Data['upload_resume'],
                'step' => 1,
                'folder' => 'lead-resume-files',
                'required' => true,
                'no_lead_id' => true
            ];
        }
        
        // Step 2 - Assessment Letter
        $step2Data = $getStepData($lead->step_2_data ?? null);
        if (!empty($step2Data['pr_assessment_letter_file'])) {
            $documents[] = [
                'key' => 'pr_assessment_letter_file',
                'name' => 'Assessment Letter',
                'file' => $step2Data['pr_assessment_letter_file'],
                'step' => 2,
                'folder' => 'lead-assessment-letters',
                'required' => true
            ];
        }
        
        // Step 3 - Passport
        $step3Data = $getStepData($lead->step_3_data ?? null);
        if (!empty($step3Data['passport_file_upload'])) {
            $documents[] = [
                'key' => 'passport_file_upload',
                'name' => 'Passport - Applicant',
                'file' => $step3Data['passport_file_upload'],
                'step' => 3,
                'folder' => 'lead-passport-files',
                'required' => true
            ];
        }
        
        // Step 5 - Family Documents
        $step5Data = $getStepData($lead->step_5_data ?? null);
        
        // Father Passport
        if (!empty($step5Data['father_passport_file'])) {
            $documents[] = [
                'key' => 'father_passport_file',
                'name' => 'Father Passport',
                'file' => $step5Data['father_passport_file'],
                'step' => 5,
                'folder' => 'lead-family-passports',
                'required' => true
            ];
        }
        
        // Mother Passport
        if (!empty($step5Data['mother_passport_file'])) {
            $documents[] = [
                'key' => 'mother_passport_file',
                'name' => 'Mother Passport',
                'file' => $step5Data['mother_passport_file'],
                'step' => 5,
                'folder' => 'lead-family-passports',
                'required' => true
            ];
        }
        
        // Spouse Passport
        if (!empty($step5Data['spouse_passport_file'])) {
            $documents[] = [
                'key' => 'spouse_passport_file',
                'name' => 'Spouse Passport',
                'file' => $step5Data['spouse_passport_file'],
                'step' => 5,
                'folder' => 'lead-family-passports',
                'required' => true
            ];
        }
        
        // Spouse Document
        if (!empty($step5Data['spouse_document_file'])) {
            $documents[] = [
                'key' => 'spouse_document_file',
                'name' => 'Spouse Document',
                'file' => $step5Data['spouse_document_file'],
                'step' => 5,
                'folder' => 'lead-family-documents',
                'required' => false
            ];
        }
        
        // Children Documents (dynamic)
        if (!empty($step5Data['children']) && is_array($step5Data['children'])) {
            foreach ($step5Data['children'] as $index => $child) {
                $childName = $child['child_name'] ?? 'Child ' . ($index + 1);
                
                if (!empty($child['child_passport_file'])) {
                    $documents[] = [
                        'key' => 'child_passport_file_' . ($index + 1),
                        'name' => 'Child Passport - ' . $childName,
                        'file' => $child['child_passport_file'],
                        'step' => 5,
                        'folder' => 'lead-family-passports',
                        'required' => true,
                        'child_index' => $index
                    ];
                }
                
                if (!empty($child['child_document_file'])) {
                    $documents[] = [
                        'key' => 'child_document_file_' . ($index + 1),
                        'name' => 'Child Document - ' . $childName,
                        'file' => $child['child_document_file'],
                        'step' => 5,
                        'folder' => 'lead-family-documents',
                        'required' => false,
                        'child_index' => $index
                    ];
                }
            }
        }
        
        // Step 6 - Education Documents
        $step6Data = $getStepData($lead->step_6_data ?? null);
        
        // IELTS Result
        if (!empty($step6Data['ielts_result_file'])) {
            $documents[] = [
                'key' => 'ielts_result_file',
                'name' => 'IELTS / PTE / OET / TOEFL - Result',
                'file' => $step6Data['ielts_result_file'],
                'step' => 6,
                'folder' => 'lead-education-files',
                'required' => false
            ];
        }
        
        // 10th Result
        if (!empty($step6Data['tenth_result_file'])) {
            $documents[] = [
                'key' => 'tenth_result_file',
                'name' => '10th Passing Result',
                'file' => $step6Data['tenth_result_file'],
                'step' => 6,
                'folder' => 'lead-education-files',
                'required' => true
            ];
        }
        
        // 12th Result
        if (!empty($step6Data['twelfth_result_file'])) {
            $documents[] = [
                'key' => 'twelfth_result_file',
                'name' => '12th Passing Result',
                'file' => $step6Data['twelfth_result_file'],
                'step' => 6,
                'folder' => 'lead-education-files',
                'required' => false
            ];
        }
        
        // Graduation Result
        if (!empty($step6Data['graduation_result_file'])) {
            $documents[] = [
                'key' => 'graduation_result_file',
                'name' => 'Graduation Degree',
                'file' => $step6Data['graduation_result_file'],
                'step' => 6,
                'folder' => 'lead-education-files',
                'required' => false
            ];
        }
        
        // Post Graduation Result
        if (!empty($step6Data['post_graduation_result_file'])) {
            $documents[] = [
                'key' => 'post_graduation_result_file',
                'name' => 'Post Graduation Degree',
                'file' => $step6Data['post_graduation_result_file'],
                'step' => 6,
                'folder' => 'lead-education-files',
                'required' => false
            ];
        }
        
        // Other Degrees (dynamic)
        if (!empty($step6Data['other_degrees']) && is_array($step6Data['other_degrees'])) {
            foreach ($step6Data['other_degrees'] as $index => $degree) {
                $degreeName = $degree['other_degree'] ?? 'Other Degree ' . ($index + 1);
                
                if (!empty($degree['other_degree_result_file'])) {
                    $documents[] = [
                        'key' => 'other_degree_result_file_' . ($index + 1),
                        'name' => 'Other Degree - ' . $degreeName,
                        'file' => $degree['other_degree_result_file'],
                        'step' => 6,
                        'folder' => 'lead-education-files',
                        'required' => false,
                        'degree_index' => $index
                    ];
                }
            }
        }
        
        // Step 7 - Job Documents (dynamic)
        $step7Data = $getStepData($lead->step_7_data ?? null);
        if (!empty($step7Data['jobs']) && is_array($step7Data['jobs'])) {
            foreach ($step7Data['jobs'] as $index => $job) {
                $jobTitle = $job['job_designation'] ?? $job['job_title'] ?? 'Job ' . ($index + 1);
                
                if (!empty($job['job_offer_letter_file'])) {
                    $documents[] = [
                        'key' => 'job_offer_letter_file_' . ($index + 1),
                        'name' => 'Job Offer Letter - ' . $jobTitle,
                        'file' => $job['job_offer_letter_file'],
                        'step' => 7,
                        'folder' => 'lead-job-files',
                        'required' => false,
                        'job_index' => $index
                    ];
                }
                
                if (!empty($job['job_experience_letter_file'])) {
                    $documents[] = [
                        'key' => 'job_experience_letter_file_' . ($index + 1),
                        'name' => 'Job Experience Letter - ' . $jobTitle,
                        'file' => $job['job_experience_letter_file'],
                        'step' => 7,
                        'folder' => 'lead-job-files',
                        'required' => false,
                        'job_index' => $index
                    ];
                }
            }
        }
        
        // Step 8 - Valuation Report
        $step8Data = $getStepData($lead->step_8_data ?? null);
        if (!empty($step8Data['valuation_report_file'])) {
            $documents[] = [
                'key' => 'valuation_report_file',
                'name' => 'Valuation Report',
                'file' => $step8Data['valuation_report_file'],
                'step' => 8,
                'folder' => 'lead-property-files',
                'required' => false
            ];
        }
        
        // Step 9 - Income Documents
        $step9Data = $getStepData($lead->step_9_data ?? null);
        
        if (!empty($step9Data['father_income_document_file'])) {
            $documents[] = [
                'key' => 'father_income_document_file',
                'name' => 'Father Income Document',
                'file' => $step9Data['father_income_document_file'],
                'step' => 9,
                'folder' => 'lead-income-documents',
                'required' => false
            ];
        }
        
        if (!empty($step9Data['mother_income_document_file'])) {
            $documents[] = [
                'key' => 'mother_income_document_file',
                'name' => 'Mother Income Document',
                'file' => $step9Data['mother_income_document_file'],
                'step' => 9,
                'folder' => 'lead-income-documents',
                'required' => false
            ];
        }
        
        if (!empty($step9Data['candidate_income_document_file'])) {
            $documents[] = [
                'key' => 'candidate_income_document_file',
                'name' => 'Candidate Income Document',
                'file' => $step9Data['candidate_income_document_file'],
                'step' => 9,
                'folder' => 'lead-income-documents',
                'required' => false
            ];
        }
        
        if (!empty($step9Data['spouse_income_document_file'])) {
            $documents[] = [
                'key' => 'spouse_income_document_file',
                'name' => 'Spouse Income Document',
                'file' => $step9Data['spouse_income_document_file'],
                'step' => 9,
                'folder' => 'lead-income-documents',
                'required' => false
            ];
        }
        
        return $documents;
    }
    
    /**
     * Get all expected documents (including missing ones) for display
     */
    private function getAllExpectedDocuments($lead)
    {
        $expectedDocs = [];
        
        if (!$lead) {
            return $expectedDocs;
        }
        
        $existingDocs = $this->extractAllDocuments($lead);
        $existingDocKeys = array_column($existingDocs, 'key');
        
        // Helper function to safely get step data
        $getStepData = function($stepData) {
            if (is_string($stepData)) {
                $decoded = json_decode($stepData, true);
                return is_array($decoded) ? $decoded : [];
            }
            return is_array($stepData) ? $stepData : [];
        };
        
        // Define all expected documents
        $allDocs = [
            // Step 1
            ['key' => 'upload_resume', 'name' => 'Resume', 'step' => 1, 'folder' => 'lead-resume-files', 'required' => true, 'no_lead_id' => true],
            // Step 2
            ['key' => 'pr_assessment_letter_file', 'name' => 'Assessment Letter', 'step' => 2, 'folder' => 'lead-assessment-letters', 'required' => true],
            // Step 3
            ['key' => 'passport_file_upload', 'name' => 'Passport - Applicant', 'step' => 3, 'folder' => 'lead-passport-files', 'required' => true],
            // Step 5
            ['key' => 'father_passport_file', 'name' => 'Father Passport', 'step' => 5, 'folder' => 'lead-family-passports', 'required' => true],
            ['key' => 'mother_passport_file', 'name' => 'Mother Passport', 'step' => 5, 'folder' => 'lead-family-passports', 'required' => true],
            ['key' => 'spouse_passport_file', 'name' => 'Spouse Passport', 'step' => 5, 'folder' => 'lead-family-passports', 'required' => true],
            ['key' => 'spouse_document_file', 'name' => 'Spouse Document', 'step' => 5, 'folder' => 'lead-family-documents', 'required' => false],
            // Step 6
            ['key' => 'ielts_result_file', 'name' => 'IELTS / PTE / OET / TOEFL - Result', 'step' => 6, 'folder' => 'lead-education-files', 'required' => false],
            ['key' => 'tenth_result_file', 'name' => '10th Passing Result', 'step' => 6, 'folder' => 'lead-education-files', 'required' => true],
            ['key' => 'twelfth_result_file', 'name' => '12th Passing Result', 'step' => 6, 'folder' => 'lead-education-files', 'required' => false],
            ['key' => 'graduation_result_file', 'name' => 'Graduation Degree', 'step' => 6, 'folder' => 'lead-education-files', 'required' => false],
            ['key' => 'post_graduation_result_file', 'name' => 'Post Graduation Degree', 'step' => 6, 'folder' => 'lead-education-files', 'required' => false],
            // Step 8
            ['key' => 'valuation_report_file', 'name' => 'Valuation Report', 'step' => 8, 'folder' => 'lead-property-files', 'required' => false],
            // Step 9
            ['key' => 'father_income_document_file', 'name' => 'Father Income Document', 'step' => 9, 'folder' => 'lead-income-documents', 'required' => false],
            ['key' => 'mother_income_document_file', 'name' => 'Mother Income Document', 'step' => 9, 'folder' => 'lead-income-documents', 'required' => false],
            ['key' => 'candidate_income_document_file', 'name' => 'Candidate Income Document', 'step' => 9, 'folder' => 'lead-income-documents', 'required' => false],
            ['key' => 'spouse_income_document_file', 'name' => 'Spouse Income Document', 'step' => 9, 'folder' => 'lead-income-documents', 'required' => false],
        ];
        
        // Create a map of existing documents by key
        $existingDocsMap = [];
        foreach ($existingDocs as $ed) {
            $existingDocsMap[$ed['key']] = $ed;
        }
        
        // Add static documents
        foreach ($allDocs as $doc) {
            $docKey = $doc['key'];
            if (isset($existingDocsMap[$docKey])) {
                $expectedDocs[] = $existingDocsMap[$docKey];
            } else {
                $expectedDocs[] = $doc;
            }
        }
        
        // Add dynamic documents (children, other_degrees, jobs)
        $step5Data = $getStepData($lead->step_5_data ?? null);
        if (!empty($step5Data['children']) && is_array($step5Data['children'])) {
            foreach ($step5Data['children'] as $index => $child) {
                $childName = $child['child_name'] ?? 'Child ' . ($index + 1);
                
                // Check if child passport exists - use index + 1 to match extractAllDocuments format
                $childPassportKey = 'child_passport_file_' . ($index + 1);
                if (isset($existingDocsMap[$childPassportKey])) {
                    $expectedDocs[] = $existingDocsMap[$childPassportKey];
                } else {
                    $expectedDocs[] = [
                        'key' => $childPassportKey,
                        'name' => 'Child Passport - ' . $childName,
                        'step' => 5,
                        'folder' => 'lead-family-passports',
                        'required' => true,
                        'child_index' => $index,
                        'file' => !empty($child['child_passport_file']) ? $child['child_passport_file'] : null
                    ];
                }
                
                // Check if child document exists - use index + 1 to match extractAllDocuments format
                $childDocKey = 'child_document_file_' . ($index + 1);
                if (isset($existingDocsMap[$childDocKey])) {
                    $expectedDocs[] = $existingDocsMap[$childDocKey];
                } else {
                    $expectedDocs[] = [
                        'key' => $childDocKey,
                        'name' => 'Child Document - ' . $childName,
                        'step' => 5,
                        'folder' => 'lead-family-documents',
                        'required' => false,
                        'child_index' => $index,
                        'file' => !empty($child['child_document_file']) ? $child['child_document_file'] : null
                    ];
                }
            }
        }
        
        // Add other degrees
        $step6Data = $getStepData($lead->step_6_data ?? null);
        if (!empty($step6Data['other_degrees']) && is_array($step6Data['other_degrees'])) {
            foreach ($step6Data['other_degrees'] as $index => $degree) {
                $degreeName = $degree['other_degree'] ?? 'Other Degree ' . ($index + 1);
                // Use index + 1 to match extractAllDocuments format
                $degreeKey = 'other_degree_result_file_' . ($index + 1);
                
                if (isset($existingDocsMap[$degreeKey])) {
                    $expectedDocs[] = $existingDocsMap[$degreeKey];
                } else {
                    $expectedDocs[] = [
                        'key' => $degreeKey,
                        'name' => 'Other Degree - ' . $degreeName,
                        'step' => 6,
                        'folder' => 'lead-education-files',
                        'required' => false,
                        'degree_index' => $index,
                        'file' => !empty($degree['other_degree_result_file']) ? $degree['other_degree_result_file'] : null
                    ];
                }
            }
        }
        
        // Add job documents
        $step7Data = $getStepData($lead->step_7_data ?? null);
        if (!empty($step7Data['jobs']) && is_array($step7Data['jobs'])) {
            foreach ($step7Data['jobs'] as $index => $job) {
                $jobTitle = $job['job_designation'] ?? $job['job_title'] ?? 'Job ' . ($index + 1);
                
                // Job Offer Letter
                $offerKey = 'job_offer_letter_file_' . ($index + 1);
                if (isset($existingDocsMap[$offerKey])) {
                    $expectedDocs[] = $existingDocsMap[$offerKey];
                } else {
                    $expectedDocs[] = [
                        'key' => $offerKey,
                        'name' => 'Job Offer Letter - ' . $jobTitle,
                        'step' => 7,
                        'folder' => 'lead-job-files',
                        'required' => false,
                        'job_index' => $index,
                        'file' => !empty($job['job_offer_letter_file']) ? $job['job_offer_letter_file'] : null
                    ];
                }
                
                // Job Experience Letter
                $experienceKey = 'job_experience_letter_file_' . ($index + 1);
                if (isset($existingDocsMap[$experienceKey])) {
                    $expectedDocs[] = $existingDocsMap[$experienceKey];
                } else {
                    $expectedDocs[] = [
                        'key' => $experienceKey,
                        'name' => 'Job Experience Letter - ' . $jobTitle,
                        'step' => 7,
                        'folder' => 'lead-job-files',
                        'required' => false,
                        'job_index' => $index,
                        'file' => !empty($job['job_experience_letter_file']) ? $job['job_experience_letter_file'] : null
                    ];
                }
            }
        }
        
        return $expectedDocs;
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

            // Remove any non-numeric characters and ensure it has country code
            $leadPhone = preg_replace('/[^0-9]/', '', $leadPhone);
            // Remove leading 0 if present
            $leadPhone = ltrim($leadPhone, '0');
            // Add 91 (India country code) if not present
            if (!str_starts_with($leadPhone, '91')) {
                $leadPhone = '91' . $leadPhone;
            }

            // Get user name
            $userName = $lead->client_name ?? 'Client';

            // Get document URL and filename
            $documentUrl = $document->file_url;
            $documentFilename = $document->file_name ?? $document->name ?? 'Document';

            // Fixed values as per API requirements - read from environment variables
            $apiKey = env('AISENSY_API_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjY4YzdmM2RjNmZhOGUxMDEzYzdlMDgzZSIsIm5hbWUiOiJSLlIgcGF0ZWwgIG5ldyIsImFwcE5hbWUiOiJBaVNlbnN5IiwiY2xpZW50SWQiOiI2ODcyMzU5ZGRlNjFiYjMxOTgzMzc2NDMiLCJhY3RpdmVQbGFuIjoiQkFTSUNfTU9OVEhMWSIsImlhdCI6MTc2MDM1NTc4OX0.6H8mv7r3R0ucc7APyDM1q0xew4-oBUVKqUHA38klVG4');
            $campaignName = env('AISENSY_CAMPAIGN_NAME', 'testing102');
            $source = env('AISENSY_SOURCE', 'new-landing-page form');

            // Prepare API request payload
            $payload = [
                'apiKey' => $apiKey,
                'campaignName' => $campaignName,
                'destination' => $leadPhone,
                'userName' => $userName,
                'templateParams' => [],
                'source' => $source,
                'media' => [
                    'url' => $documentUrl,
                    'filename' => $documentFilename
                ],
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
        $this->deletePermission = user()->permission('delete_lead');

        abort_403(!($this->deletePermission == 'all'
            || ($this->deletePermission == 'added' && $newLead->added_by == user()->id)
            || ($this->deletePermission == 'owned' && $newLead->lead_owner == user()->id)
            || ($this->deletePermission == 'both' && ($newLead->added_by == user()->id || $newLead->lead_owner == user()->id))
        ));

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

        $lead->lead_status = $request->status;
        $lead->save();

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

        $lead->lead_quality = $request->quality;
        $lead->save();

        return Reply::success(__('messages.updateSuccess'));
    }

    /**
     * Get employees for reassign dropdown
     */
    public function getEmployeesForReassign()
    {
        $employees = User::allEmployees(null, 'active');
        
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
        
        // Check permissions: only admins can reassign leads
        $userRoles = user_roles();
        $isAdmin = in_array('admin', $userRoles);
        
        abort_403(!$isAdmin);
        
        // Validate employee
        $newEmployee = User::findOrFail($request->employee_id);
        
        // Store old owner for email notification
        $oldOwnerId = $lead->lead_owner;
        
        // Update lead owner
        $lead->lead_owner = $request->employee_id;
        $lead->last_updated_by = user()->id;
        $lead->save();
        
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

            // Create or get new lead
            if ($leadId) {
                $lead = NewLead::findOrFail($leadId);
            } else {
                // Create new lead
                $lead = new NewLead();
                $lead->company_id = company()->id;
                $lead->client_name = ($request->surname ?? '') . ' ' . ($request->given_name ?? '');
                $lead->client_email = $request->email_address ?? $request->email ?? null;
                $lead->mobile = $request->mobile ?? $request->primary_phone ?? null;
                $lead->added_by = user()->id;
                $lead->hash = md5(microtime());
                $lead->save();
                $leadId = $lead->id;
            }

            // Get or create step status
            $stepStatus = LeadStepStatus::getOrCreateForLead($leadId);

            // Save step data using unified method for all steps
            $this->saveStepData($lead, $request, $stepNumber);

            // Mark step as completed
            $stepField = 'step_' . $stepNumber . '_completed';
            $stepStatus->$stepField = true;
            $stepStatus->save();

            // Update final status
            $stepStatus->updateFinalStatus();

            // Log step completion
            $this->logStepCompletion($leadId, $stepNumber);

            // Send emails when step 9 is completed
            // Use loose comparison to handle both string "9" and integer 9
            if ($stepNumber == 9 || $stepNumber === 9 || (string)$stepNumber === '9') {
                \Log::info('Step 9 completed, triggering email sending for lead ID: ' . $lead->id);
                $this->sendLeadEmails($lead);
            }

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
            $lead->lead_owner = $this->getRequestValue($request, 'lead_assign_to') ?: user()->id;
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
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send lead emails: ' . $e->getMessage());
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

            // Update lead owner
            $lead->lead_owner = $leadAssignTo;
            $lead->save();

            // Get or create step status
            $stepStatus = LeadStepStatus::getOrCreateForLead($leadId);

            // Set final status to complete
            $stepStatus->final_status = 'complete';
            $stepStatus->save();

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
    public function downloadLeadDocument($leadId, $documentKey)
    {
        try {
            $lead = NewLead::findOrFail($leadId);
            
            // Get document configuration
            $documentConfig = $this->getDocumentConfig($documentKey, $lead);
            
            if (!$documentConfig) {
                abort(404, 'Invalid document key: ' . $documentKey);
            }
            
            // Get step data
            $stepData = $this->getStepDataArray($lead, $documentConfig['step']);
            
            // Get file name
            $fileName = $this->getDocumentFileName($stepData, $documentKey, $documentConfig);
            
            if (!$fileName) {
                abort(404, 'Document file not found in step data for key: ' . $documentKey);
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
            $lead = NewLead::findOrFail($leadId);
            
            $this->lead = $lead;
            
            // Get all expected documents
            $allExpectedDocuments = $this->getAllExpectedDocuments($lead);
            $this->data['allExpectedDocuments'] = $allExpectedDocuments;
            $this->data['lead'] = $lead;
            
            $html = view('lead-details.components.documents-list', $this->data)->render();
            
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
            'document_key' => 'required|string',
            'document_file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);
        
        $documentKey = $request->document_key;
        $file = $request->file('document_file');
        
        // Determine step and folder based on document key
        $documentConfig = $this->getDocumentConfig($documentKey, $lead);
        
        if (!$documentConfig) {
            return Reply::error('Invalid document key.');
        }
        
        try {
            // Delete old file if exists
            $stepData = $this->getStepDataArray($lead, $documentConfig['step']);
            $oldFileName = $this->getDocumentFileName($stepData, $documentKey, $documentConfig);
            
            // Step 1 resume files don't have lead ID subfolder
            $folderPath = !empty($documentConfig['no_lead_id']) 
                ? $documentConfig['folder'] 
                : $documentConfig['folder'] . '/' . $lead->id;
            
            if ($oldFileName) {
                \App\Helper\Files::deleteFile($oldFileName, $folderPath);
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
            
            // Update step data
            $this->updateDocumentInStepData($lead, $documentConfig['step'], $documentKey, $customFileName, $documentConfig);
            
            return Reply::success(__('messages.recordSaved'));
        } catch (\Exception $e) {
            return Reply::error('Failed to upload document: ' . $e->getMessage());
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

        $account->save();

        return Reply::success(__('messages.recordSaved'));
    }

    /**
     * Get account data for editing
     */
    public function getNewLeadAccount($id)
    {
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
        $account = \App\Models\NewLeadAccount::findOrFail($id);
        $account->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }

    /**
     * Get accounts list for a lead via AJAX
     */
    public function getNewLeadAccounts($id)
    {
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

}

