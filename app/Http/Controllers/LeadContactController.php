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
use App\Models\PipelineStage;
use App\Models\LeadStatus;
use App\Models\Product;
use App\Models\User;
use App\Traits\ImportExcel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
            $this->sources = LeadSource::get();
            $this->employees = User::allEmployees(null, 'active');
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

    public function leadDetails()
    {
        $this->viewLeadPermission = $viewPermission = user()->permission('view_lead');
        abort_403(!in_array($viewPermission, ['all','added','owned','both']));

        $this->pageTitle = 'app.leadDetails';

        if (!request()->ajax()) {
            $this->categories = LeadCategory::get();
            $this->sources = LeadSource::get();
            $this->employees = User::allEmployees(null, 'active');
        }

        return view('lead-details.index', $this->data);
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
                $rules = [
                    'surname' => 'required|string|max:255',
                    'given_name' => 'required|string|max:255',
                    'gender' => 'required|string|in:Male,Female,Other',
                    'marital_status' => 'required|string',
                    'date_of_birth' => 'required|date',
                    'country_of_origin' => 'required|string|max:255',
                    'lead_source' => 'required|string',
                    'lead_assign_to' => 'required|integer|exists:users,id',
                    'home_address' => 'required|string|max:500',
                    'home_city' => 'required|string|max:255',
                    'home_state' => 'required|string|max:255',
                    'home_pin_code' => 'required|string|max:20',
                    'primary_phone' => 'required|string|regex:/^[0-9]{10}$/',
                    'email_address' => 'required|email|max:255',
                ];
                
                // Mailing address is required only if "same as home" is not checked
                if (!$request->mailing_same_as_home) {
                    $rules['mailing_address'] = 'required|string|max:500';
                    $rules['mailing_city'] = 'required|string|max:255';
                    $rules['mailing_state'] = 'required|string|max:255';
                    $rules['mailing_pin_code'] = 'required|string|max:20';
                }
                
                // Optional phone fields validation
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
                
                // Optional email validation
                if ($request->has('other_email') && $request->other_email) {
                    $rules['other_email'] = 'nullable|email|max:255';
                }
                
                $messages = [
                    'surname.required' => __('validation.required', ['attribute' => __('app.surname')]),
                    'given_name.required' => __('validation.required', ['attribute' => __('app.givenName')]),
                    'gender.required' => __('validation.required', ['attribute' => __('app.gender')]),
                    'marital_status.required' => __('validation.required', ['attribute' => __('app.maritalStatus')]),
                    'date_of_birth.required' => __('validation.required', ['attribute' => __('app.dateOfBirth')]),
                    'country_of_origin.required' => __('validation.required', ['attribute' => __('app.countryOfOrigin')]),
                    'lead_source.required' => __('validation.required', ['attribute' => __('modules.lead.leadSource')]),
                    'lead_assign_to.required' => __('validation.required', ['attribute' => __('app.leadAssignTo')]),
                    'home_address.required' => __('validation.required', ['attribute' => __('modules.lead.address')]),
                    'home_city.required' => __('validation.required', ['attribute' => __('app.city')]),
                    'home_state.required' => __('validation.required', ['attribute' => __('app.state')]),
                    'home_pin_code.required' => __('validation.required', ['attribute' => __('app.pinCode')]),
                    'mailing_address.required' => __('validation.required', ['attribute' => __('modules.lead.address')]),
                    'mailing_city.required' => __('validation.required', ['attribute' => __('app.city')]),
                    'mailing_state.required' => __('validation.required', ['attribute' => __('app.state')]),
                    'mailing_pin_code.required' => __('validation.required', ['attribute' => __('app.pinCode')]),
                    'primary_phone.required' => __('validation.required', ['attribute' => __('app.primaryPhoneNo')]),
                    'primary_phone.regex' => __('validation.regex', ['attribute' => __('app.primaryPhoneNo')]),
                    'email_address.required' => __('validation.required', ['attribute' => __('modules.lead.email')]),
                    'email_address.email' => __('validation.email', ['attribute' => __('modules.lead.email')]),
                ];
                break;

            case 2:
                // Step 2 - Client Preference
                $rules = [
                    'visa_type' => 'required|string|in:pr,visit,work,student,PR,Visit,Work,Student',
                ];
                
                // Normalize visa_type to handle both lowercase and uppercase
                $visaType = strtolower($request->visa_type);
                
                // PR Visa specific fields (only fields with *)
                if ($visaType === 'pr') {
                    $rules['skill_assessment_letter'] = 'required|string';
                    // pr_assessment_letter_file is required if not already uploaded
                    if (!$request->hasFile('pr_assessment_letter_file') && !$request->pr_assessment_letter_file_existing) {
                        $rules['pr_assessment_letter_file'] = 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240';
                    } else {
                        $rules['pr_assessment_letter_file'] = 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240';
                    }
                    $rules['pr_family'] = 'required|string';
                }
                
                // Visit Visa specific fields (only fields with *)
                if ($visaType === 'visit') {
                    $rules['purpose_of_visit'] = 'required|string|max:500';
                    $rules['visit_family'] = 'required|string';
                }
                
                // Work Visa specific fields (only fields with *)
                if ($visaType === 'work') {
                    $rules['preferred_designation'] = 'required|string|max:255';
                }
                
                // Student Visa specific fields (only fields with *)
                if ($visaType === 'student') {
                    $rules['term_intake'] = 'required|string';
                }
                
                $messages = [
                    'visa_type.required' => __('validation.required', ['attribute' => __('app.selectVisaType')]),
                ];
                break;

            case 3:
                // Step 3 - Passport Details
                $rules = [
                    'passport_number' => 'required|string|max:255',
                    'issuing_country' => 'required|string|max:255',
                    'city_where_issued' => 'required|string|max:255',
                    'issuance_date' => 'required|date',
                    'expiration_date' => 'required|date|after:issuance_date',
                ];
                
                // Passport file is required if not already uploaded
                if (!$request->hasFile('passport_file_upload') && !$request->passport_file_upload_existing) {
                    $rules['passport_file_upload'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
                } else {
                    $rules['passport_file_upload'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
                }
                break;

            case 4:
                // Step 4 - Relative Contact Information
                // No required fields - all fields are optional
                break;

            case 5:
                // Step 5 - Family Information (same fields as Step 4, but may have additional spouse/child fields)
                // For now, validate the same required fields as Step 4
                $rules = [
                    'father_surname' => 'required|string|max:255',
                    'father_given_name' => 'required|string|max:255',
                    'father_date_of_birth' => 'required|date',
                    'father_occupation' => 'required|string|max:255',
                    'father_have_passport' => 'required|string|in:Yes,No',
                    'mother_surname' => 'required|string|max:255',
                    'mother_given_name' => 'required|string|max:255',
                    'mother_date_of_birth' => 'required|date',
                    'mother_occupation' => 'required|string|max:255',
                    'mother_have_passport' => 'required|string|in:Yes,No',
                ];
                
                // Father passport file is required if father has passport = Yes
                if ($request->father_have_passport === 'Yes') {
                    if (!$request->hasFile('father_passport_file') && !$request->father_passport_file_existing) {
                        $rules['father_passport_file'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
                    } else {
                        $rules['father_passport_file'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
                    }
                }
                
                // Mother passport file is required if mother has passport = Yes
                if ($request->mother_have_passport === 'Yes') {
                    if (!$request->hasFile('mother_passport_file') && !$request->mother_passport_file_existing) {
                        $rules['mother_passport_file'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
                    } else {
                        $rules['mother_passport_file'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
                    }
                }
                break;

            case 6:
                // Step 6 - Education
                $rules = [
                    'tenth_passing_year' => 'required|integer|min:1950|max:' . date('Y'),
                    'tenth_percentage' => 'required|numeric|min:0|max:100',
                    'tenth_result_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'tenth_board_name' => 'required|string|max:255',
                    'tenth_trial' => 'required|string|max:255',
                ];
                break;

            case 7:
                // Step 7 - Professional Experience
                // No required fields based on form structure - all fields are optional
                break;

            case 8:
                // Step 8 - Property Details
                $rules = [
                    'property_home' => 'required|numeric|min:0',
                    'property_land' => 'required|numeric|min:0',
                    'property_plot' => 'required|numeric|min:0',
                    'property_commercials' => 'required|numeric|min:0',
                    'property_other' => 'required|numeric|min:0',
                    'property_shop' => 'required|numeric|min:0',
                    'property_gold' => 'required|numeric|min:0',
                    'property_silver' => 'required|numeric|min:0',
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

            // Check if previous step is completed (except for step 1)
            if ($stepNumber > 1) {
                $previousStepField = 'step_' . ($stepNumber - 1) . '_completed';
                if (!$stepStatus->$previousStepField) {
                    return Reply::error(__('app.pleaseCompletePreviousStepsFirst'));
                }
            }

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

            return Reply::successWithData(__('messages.recordSaved'), [
                'lead_id' => $leadId,
                'step_' . $stepNumber . '_completed' => true,
                'final_status' => $stepStatus->final_status,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saving step ' . $stepNumber . ': ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
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
            ],
            6 => [
                // Step 6 - Education
                'ielts_clear_or_not',
                'ielts_passing_year',
                'ielts_score',
                'ielts_trial',
                'tenth_passing_year',
                'tenth_percentage',
                'tenth_board_name',
                'tenth_trial',
                'twelfth_passing_year',
                'twelfth_stream',
                'twelfth_percentage',
                'twelfth_board_name',
                'twelfth_trial',
                'graduation_degree',
                'graduation_university_name',
                'graduation_percentage',
                'graduation_passing_year',
                'graduation_trial',
                'post_graduation_degree',
                'post_graduation_university_name',
                'post_graduation_percentage',
                'post_graduation_passing_year',
                'post_graduation_trial',
                'other_degree',
                'other_degree_university_name',
                'other_degree_percentage',
                'other_degree_passing_year',
                'other_degree_trial',
            ],
            7 => [
                // Step 7 - Professional Experience
                'job_duration_from',
                'job_duration_to',
                'job_country',
                'job_designation',
                'job_company_name',
                'job_salary',
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
            if ($request->hasFile($field)) {
                // Initialize file field as null, will be set in special handling
                $stepData[$field] = null;
                continue;
            }
            
            // Get the value using our helper method
            $value = $this->getRequestValue($request, $field);
            // Always include the field, even if null, to maintain structure
            $stepData[$field] = $value;
        }
        
        // Handle special cases for Step 1
        if ($stepNumber === 1) {
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
            
            // Handle mailing_same_as_home as boolean
            $stepData['mailing_same_as_home'] = $request->has('mailing_same_as_home') ? (bool)$request->mailing_same_as_home : false;
            
            // Handle upload_resume file upload for step 1
            $existingStep1Data = is_array($lead->step_1_data) ? $lead->step_1_data : [];
            
            // Debug: Check if file is in request - log all file-related info
            \Log::info('=== UPLOAD RESUME DEBUG START ===');
            \Log::info('Lead ID: ' . $lead->id);
            \Log::info('hasFile(upload_resume): ' . ($request->hasFile('upload_resume') ? 'true' : 'false'));
            \Log::info('has(upload_resume): ' . ($request->has('upload_resume') ? 'true' : 'false'));
            \Log::info('All files in request: ' . implode(', ', array_keys($request->allFiles())));
            
            if ($request->hasFile('upload_resume')) {
                $file = $request->file('upload_resume');
                \Log::info('File detected - Name: ' . $file->getClientOriginalName() . ', Size: ' . $file->getSize() . ', Mime: ' . $file->getMimeType());
            } else {
                \Log::info('No file detected in request');
            }
            
            // Always ensure upload_resume is in stepData
            // Initialize it first
            $stepData['upload_resume'] = $existingStep1Data['upload_resume'] ?? null;
            
            if ($request->hasFile('upload_resume')) {
                $uploadedFile = $request->file('upload_resume');
                
                \Log::info('Attempting to upload resume file for lead ' . $lead->id);
                \Log::info('File details - Name: ' . $uploadedFile->getClientOriginalName() . ', Size: ' . $uploadedFile->getSize() . ', Valid: ' . ($uploadedFile->isValid() ? 'YES' : 'NO'));
                
                // Delete old file if exists
                if (isset($existingStep1Data['upload_resume']) && $existingStep1Data['upload_resume']) {
                    $oldFileName = $existingStep1Data['upload_resume'];
                    try {
                        \App\Helper\Files::deleteFile($oldFileName, 'lead-resume-files/' . $lead->id);
                        \Log::info('Deleted old resume file: ' . $oldFileName);
                    } catch (\Exception $e) {
                        \Log::error('Error deleting old resume file: ' . $e->getMessage());
                    }
                }
                
                // Upload new file
                try {
                    // Check if storage setting exists before uploading
                    $storageSetting = \App\Models\StorageSetting::where('status', 'enabled')->first();
                    if (!$storageSetting) {
                        throw new \Exception('No storage setting found with status enabled. Please configure storage settings first.');
                    }
                    \Log::info('Storage setting found: ' . $storageSetting->filesystem);
                    \Log::info('Filesystem default: ' . config('filesystems.default'));
                    
                    // Ensure directory exists for local storage
                    // Note: For 'local' disk, root is public/user-uploads (see config/filesystems.php)
                    if (config('filesystems.default') == 'local') {
                        $directoryPath = public_path('user-uploads/lead-resume-files/' . $lead->id);
                        if (!\Illuminate\Support\Facades\File::exists($directoryPath)) {
                            \Illuminate\Support\Facades\File::makeDirectory($directoryPath, 0755, true);
                            \Log::info('Created directory: ' . $directoryPath);
                        } else {
                            \Log::info('Directory already exists: ' . $directoryPath);
                        }
                    } else {
                        // For cloud storage, ensure directory exists in storage/app
                        $directoryPath = storage_path('app/lead-resume-files/' . $lead->id);
                        if (!\Illuminate\Support\Facades\File::exists($directoryPath)) {
                            \Illuminate\Support\Facades\File::makeDirectory($directoryPath, 0755, true);
                            \Log::info('Created directory: ' . $directoryPath);
                        }
                    }
                    
                    \Log::info('Calling uploadLocalOrS3 with file: ' . $uploadedFile->getClientOriginalName());
                    $resumeFile = \App\Helper\Files::uploadLocalOrS3(
                        $uploadedFile,
                        'lead-resume-files/' . $lead->id
                    );
                    \Log::info('uploadLocalOrS3 returned: ' . ($resumeFile ? $resumeFile : 'NULL'));
                    
                    // Verify file was actually saved
                    if ($resumeFile) {
                        $filePath = 'lead-resume-files/' . $lead->id . '/' . $resumeFile;
                        $fileExists = \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->exists($filePath);
                        
                        // Also check physical path for local storage
                        $physicalPath = null;
                        if (config('filesystems.default') == 'local') {
                            $physicalPath = public_path('user-uploads/' . $filePath);
                            $physicalExists = \Illuminate\Support\Facades\File::exists($physicalPath);
                            $fileExists = $fileExists || $physicalExists;
                            \Log::info('Physical file check: ' . $physicalPath . ' - ' . ($physicalExists ? 'EXISTS' : 'NOT FOUND'));
                        }
                        
                        \Log::info('File exists check for ' . $filePath . ': ' . ($fileExists ? 'YES' : 'NO'));
                        
                        if ($fileExists) {
                            $stepData['upload_resume'] = $resumeFile;
                            \Log::info('Resume file uploaded successfully for lead ' . $lead->id . ': ' . $resumeFile);
                            \Log::info('File path in database: ' . $resumeFile);
                            \Log::info('Full file path: ' . ($physicalPath ?: $filePath));
                        } else {
                            \Log::error('File was not saved to disk even though uploadLocalOrS3 returned filename: ' . $resumeFile);
                            \Log::error('Expected path: ' . ($physicalPath ?: $filePath));
                            // Don't update stepData, keep existing or null
                        }
                    } else {
                        \Log::error('Resume file upload returned null for lead ' . $lead->id);
                        // Don't update stepData, keep existing or null
                    }
                } catch (\Exception $e) {
                    \Log::error('Exception uploading resume file for lead ' . $lead->id . ': ' . $e->getMessage());
                    \Log::error('Exception class: ' . get_class($e));
                    \Log::error('Stack trace: ' . $e->getTraceAsString());
                    // Don't update stepData, keep existing or null
                }
            } elseif ($request->has('upload_resume_existing') && $request->upload_resume_existing) {
                // Keep existing file if no new file is uploaded and existing file is specified
                $stepData['upload_resume'] = $request->upload_resume_existing;
                \Log::info('Using existing resume file: ' . $request->upload_resume_existing);
            } elseif (isset($existingStep1Data['upload_resume']) && $existingStep1Data['upload_resume']) {
                // Keep existing file if no new file is uploaded and no removal signal
                $stepData['upload_resume'] = $existingStep1Data['upload_resume'];
                \Log::info('Preserving existing resume file: ' . $existingStep1Data['upload_resume']);
            } else {
                // No file uploaded and no existing file - set to null but ensure field exists
                $stepData['upload_resume'] = null;
                \Log::info('No resume file - setting to null');
            }
            
            // Debug: Log the upload_resume value being saved
            \Log::info('Step 1 upload_resume FINAL value for lead ' . $lead->id . ': ' . ($stepData['upload_resume'] ?? 'null'));
            \Log::info('=== UPLOAD RESUME DEBUG END ===');
        }
        
        // Handle special cases for Step 2
        if ($stepNumber === 2) {
            // Handle PR Assessment Letter file upload
            $prAssessmentLetterFile = null;
            $existingStep2Data = is_array($lead->step_2_data) ? $lead->step_2_data : [];
            
            if ($request->hasFile('pr_assessment_letter_file')) {
                // Delete old file if exists
                if (isset($existingStep2Data['pr_assessment_letter_file'])) {
                    $oldFileName = $existingStep2Data['pr_assessment_letter_file'];
                    \App\Helper\Files::deleteFile($oldFileName, 'lead-assessment-letters/' . $lead->id);
                }
                // Upload new file
                try {
                    $prAssessmentLetterFile = \App\Helper\Files::uploadLocalOrS3(
                        $request->pr_assessment_letter_file,
                        'lead-assessment-letters/' . $lead->id
                    );
                } catch (\Exception $e) {
                    \Log::error('Error uploading PR assessment letter file: ' . $e->getMessage());
                }
            } elseif ($request->has('pr_assessment_letter_file_existing')) {
                $prAssessmentLetterFile = $request->pr_assessment_letter_file_existing;
            } elseif (isset($existingStep2Data['pr_assessment_letter_file'])) {
                // User removed file
                $oldFileName = $existingStep2Data['pr_assessment_letter_file'];
                try {
                    \App\Helper\Files::deleteFile($oldFileName, 'lead-assessment-letters/' . $lead->id);
                } catch (\Exception $e) {
                    \Log::error('Error deleting PR assessment letter file: ' . $e->getMessage());
                }
                $prAssessmentLetterFile = null;
            }
            $stepData['pr_assessment_letter_file'] = $prAssessmentLetterFile;
        }
        
        // Handle file uploads for specific steps
        if ($stepNumber === 3) {
            // Handle passport file upload for step 3
            if ($request->hasFile('passport_file_upload')) {
                // Delete old file if exists
                if (isset($lead->step_3_data['passport_file_upload']) && $lead->step_3_data['passport_file_upload']) {
                    $oldFileName = $lead->step_3_data['passport_file_upload'];
                    \App\Helper\Files::deleteFile($oldFileName, 'lead-passport-files/' . $lead->id);
                }
                // Upload new file
                try {
                    $fileName = \App\Helper\Files::uploadLocalOrS3(
                        $request->passport_file_upload,
                        'lead-passport-files/' . $lead->id
                    );
                    $stepData['passport_file_upload'] = $fileName;
                } catch (\Exception $e) {
                    \Log::error('Error uploading passport file: ' . $e->getMessage());
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
                    \Log::error('Error deleting passport file: ' . $e->getMessage());
                }
                $stepData['passport_file_upload'] = null;
            }
        }
        
        if ($stepNumber === 5) {
            // Handle passport file uploads for family members in step 5
            $familyPassportFields = [
                'father_passport_file',
                'mother_passport_file',
                'spouse_passport_file',
                'child_passport_file',
            ];
            
            foreach ($familyPassportFields as $fileField) {
                if ($request->hasFile($fileField)) {
                    // Delete old file if exists
                    if (isset($lead->step_5_data[$fileField]) && $lead->step_5_data[$fileField]) {
                        $oldFileName = $lead->step_5_data[$fileField];
                        \App\Helper\Files::deleteFile($oldFileName, 'lead-family-passports/' . $lead->id);
                    }
                    // Upload new file
                    try {
                        $fileName = \App\Helper\Files::uploadLocalOrS3(
                            $request->$fileField,
                            'lead-family-passports/' . $lead->id
                        );
                        $stepData[$fileField] = $fileName;
                    } catch (\Exception $e) {
                        \Log::error('Error uploading ' . $fileField . ': ' . $e->getMessage());
                    }
                } elseif ($request->has($fileField . '_existing')) {
                    // Keep existing file if no new file is uploaded
                    $stepData[$fileField] = $request->input($fileField . '_existing');
                } elseif (isset($lead->step_5_data[$fileField]) && $lead->step_5_data[$fileField]) {
                    // If existing file input is not present, it means user removed it, so delete file
                    $oldFileName = $lead->step_5_data[$fileField];
                    try {
                        \App\Helper\Files::deleteFile($oldFileName, 'lead-family-passports/' . $lead->id);
                    } catch (\Exception $e) {
                        \Log::error('Error deleting ' . $fileField . ': ' . $e->getMessage());
                    }
                    $stepData[$fileField] = null;
                }
            }
        }
        
        // Handle file uploads for step 9 (Financial Status)
        if ($stepNumber === 9) {
            // Handle income document file uploads
            $fileFields = [
                'father_income_document_file',
                'mother_income_document_file',
                'candidate_income_document_file',
                'spouse_income_document_file',
            ];
            
            foreach ($fileFields as $fileField) {
                if ($request->hasFile($fileField)) {
                    // Delete old file if exists
                    if (isset($lead->step_9_data[$fileField]) && $lead->step_9_data[$fileField]) {
                        $oldFileName = $lead->step_9_data[$fileField];
                        \App\Helper\Files::deleteFile($oldFileName, 'lead-income-documents/' . $lead->id);
                    }
                    // Upload new file
                    try {
                        $fileName = \App\Helper\Files::uploadLocalOrS3(
                            $request->$fileField,
                            'lead-income-documents/' . $lead->id
                        );
                        $stepData[$fileField] = $fileName;
                    } catch (\Exception $e) {
                        \Log::error('Error uploading ' . $fileField . ': ' . $e->getMessage());
                    }
                } elseif ($request->has($fileField . '_existing')) {
                    // Keep existing file if no new file is uploaded
                    $stepData[$fileField] = $request->input($fileField . '_existing');
                } elseif (isset($lead->step_9_data[$fileField]) && $lead->step_9_data[$fileField]) {
                    // If existing file input is not present, it means user removed it, so delete file
                    $oldFileName = $lead->step_9_data[$fileField];
                    try {
                        \App\Helper\Files::deleteFile($oldFileName, 'lead-income-documents/' . $lead->id);
                    } catch (\Exception $e) {
                        \Log::error('Error deleting ' . $fileField . ': ' . $e->getMessage());
                    }
                    $stepData[$fileField] = null;
                }
            }
        }
        
        // Ensure upload_resume is always in stepData for step 1 (even if null)
        if ($stepNumber === 1 && !array_key_exists('upload_resume', $stepData)) {
            $stepData['upload_resume'] = null;
        }
        
        // Store step data in separate column based on step number
        $stepField = 'step_' . $stepNumber . '_data';
        $lead->$stepField = $stepData;
        $lead->save();
        
        // Debug: Log what was actually saved
        if ($stepNumber === 1) {
            \Log::info('Step 1 data saved for lead ' . $lead->id . ', upload_resume: ' . ($lead->step_1_data['upload_resume'] ?? 'NOT SET'));
        }
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

}
