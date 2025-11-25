@php
    $processData = null;
    $hasProcessData = false;
    if (isset($lead) && $lead && $lead->process) {
        $processData = $lead->process;
        $hasProcessData = true;
    }
    
    // Helper function to format date
    $formatDate = function($date) {
        if (empty($date)) return '-';
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
    
    // Helper function to get value or default
    $getValue = function($value, $default = '-') {
        return !empty($value) ? $value : $default;
    };
    
    // Get registered date from lead
    $registeredDate = isset($lead) && $lead ? ($lead->created_at ? $formatDate($lead->created_at) : 'N/A') : 'N/A';
@endphp

<div class="tab-content px-4 pb-4" id="processTab">
    <!-- Tab Header -->
    <div class="tab-section-header">
        <div class="tab-section-header-content d-flex justify-content-between align-items-center">
            <h3 class="tab-section-title">Process - <span class="tab-section-subtitle"> Registered Date: {{ $registeredDate }}</span></h3>
            <div class="tab-header-actions">
                <!-- Edit Button (shown when data exists and not in edit mode) -->
                <button type="button" class="btn btn-primary btn-sm" id="editProcessBtn" style="{{ $hasProcessData ? '' : 'display:none;' }}">
                    <i class="fa fa-edit mr-1"></i> Edit
                </button>
                <!-- Save and Cancel Buttons (shown when in edit mode) -->
                <div id="processFormActions" style="display:none;">
                    <button type="button" class="btn btn-success btn-sm" id="saveProcessBtn">
                        <i class="fa fa-save mr-1"></i> Save
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm ml-2" id="cancelProcessBtn">
                        <i class="fa fa-times mr-1"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tab Content Area -->
    <div class="tab-section-content">
        <!-- Form Section (shown by default if no data, or when editing) -->
        <div id="processFormSection" style="{{ $hasProcessData ? 'display:none;' : '' }}">
            <form id="processForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="new_lead_id" id="process_lead_id" value="{{ isset($lead) && $lead ? $lead->id : '' }}">
                <input type="hidden" name="process_id" id="process_id" value="{{ $processData ? $processData->id : '' }}">
                
                <!-- Agent & Applicant Details Section -->
                <div class="info-section mb-1">
                    <div class="info-section-title mb-3">
                        <h4 class="f-16 font-weight-bold">Agent & Applicant Details</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="applicant_name" fieldLabel="Applicant Name *">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="applicant_name" id="applicant_name" value="{{ $processData ? $processData->applicant_name : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="visa_category" fieldLabel="Visa Category *">
                            </x-forms.label>
                            <select class="form-control select-picker height-35 f-14" name="visa_category" id="visa_category" required>
                                <option value="">Select</option>
                                <option value="PR" {{ $processData && $processData->visa_category == 'PR' ? 'selected' : '' }}>PR</option>
                                <option value="Student Visa" {{ $processData && $processData->visa_category == 'Student Visa' ? 'selected' : '' }}>Student Visa</option>
                                <option value="Visit Visa" {{ $processData && $processData->visa_category == 'Visit Visa' ? 'selected' : '' }}>Visit Visa</option>
                                <option value="Work Permit" {{ $processData && $processData->visa_category == 'Work Permit' ? 'selected' : '' }}>Work Permit</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-forms.label fieldId="subclass" fieldLabel="Subclass *">
                            </x-forms.label>
                            <select class="form-control select-picker height-35 f-14" name="subclass" id="subclass" required>
                                <option value="">Select</option>
                                <option value="Visitor Visa (Subclass 600)" {{ $processData && $processData->subclass == 'Visitor Visa (Subclass 600)' ? 'selected' : '' }}>Visitor Visa (Subclass 600)</option>
                                <option value="PR - Employer Nomination Scheme (ENS)(Subclass 186)" {{ $processData && $processData->subclass == 'PR - Employer Nomination Scheme (ENS)(Subclass 186)' ? 'selected' : '' }}>PR - Employer Nomination Scheme (ENS)(Subclass 186)</option>
                                <option value="PR - Skilled Nominated Visa (Subclass 190)" {{ $processData && $processData->subclass == 'PR - Skilled Nominated Visa (Subclass 190)' ? 'selected' : '' }}>PR - Skilled Nominated Visa (Subclass 190)</option>
                                <option value="PR - Skilled Independent Visa (Subclass 189)" {{ $processData && $processData->subclass == 'PR - Skilled Independent Visa (Subclass 189)' ? 'selected' : '' }}>PR - Skilled Independent Visa (Subclass 189)</option>
                                <option value="Work Visa - Temporary Skill Shortage Visa (Subclass 482)" {{ $processData && $processData->subclass == 'Work Visa - Temporary Skill Shortage Visa (Subclass 482)' ? 'selected' : '' }}>Work Visa - Temporary Skill Shortage Visa (Subclass 482)</option>
                                <option value="Work Visa - Skilled Work Regional Visa (Australia) (Subclass 491)" {{ $processData && $processData->subclass == 'Work Visa - Skilled Work Regional Visa (Australia) (Subclass 491)' ? 'selected' : '' }}>Work Visa - Skilled Work Regional Visa (Australia) (Subclass 491)</option>
                                <option value="Student Visa (Subclass 500)" {{ $processData && $processData->subclass == 'Student Visa (Subclass 500)' ? 'selected' : '' }}>Student Visa (Subclass 500)</option>
                                <option value="Student Visa - Temporary Graduate Visa (Australia)(Subclass 485)" {{ $processData && $processData->subclass == 'Student Visa - Temporary Graduate Visa (Australia)(Subclass 485)' ? 'selected' : '' }}>Student Visa - Temporary Graduate Visa (Australia)(Subclass 485)</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="passport_name" fieldLabel="Passport Name *">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="passport_name" id="passport_name" value="{{ $processData ? $processData->passport_name : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="passport_number" fieldLabel="Passport Number *">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="passport_number" id="passport_number" value="{{ $processData ? $processData->passport_number : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="agent_name" fieldLabel="Agent Name *">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="agent_name" id="agent_name" value="{{ $processData ? $processData->agent_name : '' }}" required>
                        </div>
                    </div>
                </div>

                <!-- All Fees Section -->
                <div class="info-section mb-1">
                    <div class="info-section-title mb-3">
                        <h4 class="f-16 font-weight-bold">All Fees</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="advance_fees" fieldLabel="Advance Fees *">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="advance_fees" id="advance_fees" value="{{ $processData ? $processData->advance_fees : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="advance_fees_due_date" fieldLabel="Advance Fees Due Date *">
                            </x-forms.label>
                            <input type="date" class="form-control height-35 f-14" name="advance_fees_due_date" id="advance_fees_due_date" value="{{ $processData && $processData->advance_fees_due_date ? $processData->advance_fees_due_date->format('Y-m-d') : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="remaining_fees" fieldLabel="Remaining Fees *">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="remaining_fees" id="remaining_fees" value="{{ $processData ? $processData->remaining_fees : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="remaining_fees_due_date" fieldLabel="Remaining Fees Due Date *">
                            </x-forms.label>
                            <input type="date" class="form-control height-35 f-14" name="remaining_fees_due_date" id="remaining_fees_due_date" value="{{ $processData && $processData->remaining_fees_due_date ? $processData->remaining_fees_due_date->format('Y-m-d') : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="agent_fees" fieldLabel="Agent Fees *">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="agent_fees" id="agent_fees" value="{{ $processData ? $processData->agent_fees : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="submission_fees" fieldLabel="Submission Fees *">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="submission_fees" id="submission_fees" value="{{ $processData ? $processData->submission_fees : '' }}" required>
                        </div>
                    </div>
                </div>

                <!-- Process & Status Section -->
                <div class="info-section mb-1">
                    <div class="info-section-title mb-3">
                        <h4 class="f-16 font-weight-bold">Process & Status</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="status" fieldLabel="Status Pending/Completed *">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="status" id="status" value="{{ $processData ? $processData->status : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="processing_time" fieldLabel="Processing Time *">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="processing_time" id="processing_time" value="{{ $processData ? $processData->processing_time : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="bank_cheque_handover_date" fieldLabel="Bank Cheque Document Handover Date *">
                            </x-forms.label>
                            <input type="date" class="form-control height-35 f-14" name="bank_cheque_handover_date" id="bank_cheque_handover_date" value="{{ $processData && $processData->bank_cheque_handover_date ? $processData->bank_cheque_handover_date->format('Y-m-d') : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="passport_handover_date" fieldLabel="Passport Handover Date *">
                            </x-forms.label>
                            <input type="date" class="form-control height-35 f-14" name="passport_handover_date" id="passport_handover_date" value="{{ $processData && $processData->passport_handover_date ? $processData->passport_handover_date->format('Y-m-d') : '' }}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-forms.label fieldId="process_note" fieldLabel="Note related to agent or process *">
                            </x-forms.label>
                            <textarea class="form-control f-14" name="process_note" id="process_note" rows="3" required>{{ $processData ? $processData->process_note : '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Upload Documents Section -->
                <div class="info-section mb-1">
                    <div class="info-section-title mb-3">
                        <h4 class="f-16 font-weight-bold">Upload Documents</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="contract_letter" fieldLabel="Contract Letter *">
                            </x-forms.label>
                            <input type="file" class="form-control height-35 f-14" name="contract_letter" id="contract_letter" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880" {{ !$processData || !$processData->contract_letter ? 'required' : '' }}>
                            @if($processData && $processData->contract_letter)
                                <small class="text-muted d-block mt-1">Current: <a href="{{ asset('storage/' . $processData->contract_letter) }}" target="_blank">View</a></small>
                            @endif
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="grant_letter" fieldLabel="Grant Letter *">
                            </x-forms.label>
                            <input type="file" class="form-control height-35 f-14" name="grant_letter" id="grant_letter" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880" {{ !$processData || !$processData->grant_letter ? 'required' : '' }}>
                            @if($processData && $processData->grant_letter)
                                <small class="text-muted d-block mt-1">Current: <a href="{{ asset('storage/' . $processData->grant_letter) }}" target="_blank">View</a></small>
                            @endif
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="offer_letter" fieldLabel="Offer Letter/Sponsor Letter *">
                            </x-forms.label>
                            <input type="file" class="form-control height-35 f-14" name="offer_letter" id="offer_letter" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880" {{ !$processData || !$processData->offer_letter ? 'required' : '' }}>
                            @if($processData && $processData->offer_letter)
                                <small class="text-muted d-block mt-1">Current: <a href="{{ asset('storage/' . $processData->offer_letter) }}" target="_blank">View</a></small>
                            @endif
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="medical_letter" fieldLabel="Medical Letter *">
                            </x-forms.label>
                            <input type="file" class="form-control height-35 f-14" name="medical_letter" id="medical_letter" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880" {{ !$processData || !$processData->medical_letter ? 'required' : '' }}>
                            @if($processData && $processData->medical_letter)
                                <small class="text-muted d-block mt-1">Current: <a href="{{ asset('storage/' . $processData->medical_letter) }}" target="_blank">View</a></small>
                            @endif
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="air_ticket" fieldLabel="Air Ticket *">
                            </x-forms.label>
                            <input type="file" class="form-control height-35 f-14" name="air_ticket" id="air_ticket" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880" {{ !$processData || !$processData->air_ticket ? 'required' : '' }}>
                            @if($processData && $processData->air_ticket)
                                <small class="text-muted d-block mt-1">Current: <a href="{{ asset('storage/' . $processData->air_ticket) }}" target="_blank">View</a></small>
                            @endif
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="accommodation_letter" fieldLabel="Accommodation Configuration Letter *">
                            </x-forms.label>
                            <input type="file" class="form-control height-35 f-14" name="accommodation_letter" id="accommodation_letter" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880" {{ !$processData || !$processData->accommodation_letter ? 'required' : '' }}>
                            @if($processData && $processData->accommodation_letter)
                                <small class="text-muted d-block mt-1">Current: <a href="{{ asset('storage/' . $processData->accommodation_letter) }}" target="_blank">View</a></small>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Save Button at bottom of form -->
                <div class="mt-4">
                    <button type="button" class="btn btn-primary" id="saveProcessFormBtn">
                        <i class="fa fa-save mr-1"></i> Save
                    </button>
                </div>
            </form>
        </div>

        <!-- Details Section (shown when data exists and not editing) -->
        <div id="processDetailsSection" style="{{ $hasProcessData ? '' : 'display:none;' }}">
            <!-- Agent & Applicant Details Section -->
            <div class="info-section mb-1">
                <div class="info-section-header mb-3">
                    <h4 class="info-section-title-text">Agent & Applicant Details</h4>
                    <div class="info-section-divider"></div>
                </div>
                <div class="info-grid-row row">
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Applicant Name</div>
                        <div class="info-field-value-text">{{ $getValue($processData ? $processData->applicant_name : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Passport Name</div>
                        <div class="info-field-value-text">{{ $getValue($processData ? $processData->passport_name : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Passport Number</div>
                        <div class="info-field-value-text">{{ $getValue($processData ? $processData->passport_number : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Agent Name</div>
                        <div class="info-field-value-text">{{ $getValue($processData ? $processData->agent_name : null) }}</div>
                    </div>
                </div>
                <div class="info-grid-row row mt-3">
                    <div class="info-field-item col-md-6 mb-3">
                        <div class="info-field-label-text">Visa Category</div>
                        <div class="info-field-value-text">{{ $getValue($processData ? $processData->visa_category : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-6 mb-3">
                        <div class="info-field-label-text">Subclass</div>
                        <div class="info-field-value-text">{{ $getValue($processData ? $processData->subclass : null) }}</div>
                    </div>
                </div>
            </div>

            <!-- All Fees Section -->
            <div class="info-section mb-1">
                <div class="info-section-header mb-3">
                    <h4 class="info-section-title-text">All Fees</h4>
                    <div class="info-section-divider"></div>
                </div>
                <div class="info-grid-row row">
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Advance Fees</div>
                        <div class="info-field-value-text">{{ $getValue($processData ? $processData->advance_fees : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Advance Fees Due Date</div>
                        <div class="info-field-value-text">{{ $formatDate($processData ? $processData->advance_fees_due_date : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Remaining Fees</div>
                        <div class="info-field-value-text">{{ $getValue($processData ? $processData->remaining_fees : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Remaining Fees Due Date</div>
                        <div class="info-field-value-text">{{ $formatDate($processData ? $processData->remaining_fees_due_date : null) }}</div>
                    </div>
                </div>
                <div class="info-grid-row row mt-3">
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Agent Fees</div>
                        <div class="info-field-value-text">{{ $getValue($processData ? $processData->agent_fees : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Submission Fees</div>
                        <div class="info-field-value-text">{{ $getValue($processData ? $processData->submission_fees : null) }}</div>
                    </div>
                </div>
            </div>

            <!-- Process & Status Section -->
            <div class="info-section mb-1">
                <div class="info-section-header mb-3">
                    <h4 class="info-section-title-text">Process & Status</h4>
                    <div class="info-section-divider"></div>
                </div>
                <div class="info-grid-row row">
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Status</div>
                        <div class="info-field-value-text">{{ $getValue($processData ? $processData->status : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Processing Time</div>
                        <div class="info-field-value-text">{{ $getValue($processData ? $processData->processing_time : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Bank Cheque Document Handover Date</div>
                        <div class="info-field-value-text">{{ $formatDate($processData ? $processData->bank_cheque_handover_date : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Passport Handover Date</div>
                        <div class="info-field-value-text">{{ $formatDate($processData ? $processData->passport_handover_date : null) }}</div>
                    </div>
                </div>
                <div class="info-grid-row row mt-3">
                    <div class="info-field-item col-md-12 mb-3">
                        <div class="info-field-label-text">Note related to agent or process</div>
                        <div class="info-field-value-text">{{ $getValue($processData ? $processData->process_note : null) }}</div>
                    </div>
                </div>
            </div>

            <!-- Upload Documents Section -->
            <div class="info-section mb-1">
                <div class="info-section-header mb-3">
                    <h4 class="info-section-title-text">Upload Documents</h4>
                    <div class="info-section-divider"></div>
                </div>
                <div class="upload-documents-list">
                    @if($processData && $processData->contract_letter)
                        <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <div class="upload-document-icon mr-3">
                                    <i class="fa fa-file-pdf text-danger"></i>
                                </div>
                                <span class="upload-document-name f-14 font-weight-500">Contract Letter</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="{{ asset('storage/' . $processData->contract_letter) }}" target="_blank" class="mr-3"><i class="fas fa-eye document-view-icon"></i></a>
                            </div>
                        </div>
                    @endif
                    @if($processData && $processData->grant_letter)
                        <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <div class="upload-document-icon mr-3">
                                    <i class="fa fa-file-word text-primary"></i>
                                </div>
                                <span class="upload-document-name f-14 font-weight-400">Grant Letter</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="{{ asset('storage/' . $processData->grant_letter) }}" target="_blank" class="mr-3"><i class="fas fa-eye document-view-icon"></i></a>
                            </div>
                        </div>
                    @endif
                    @if($processData && $processData->offer_letter)
                        <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <div class="upload-document-icon mr-3">
                                    <i class="fa fa-file-word text-primary"></i>
                                </div>
                                <span class="upload-document-name f-14 font-weight-400">Offer Letter/Sponsor Letter</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="{{ asset('storage/' . $processData->offer_letter) }}" target="_blank" class="mr-3"><i class="fas fa-eye document-view-icon"></i></a>
                            </div>
                        </div>
                    @endif
                    @if($processData && $processData->medical_letter)
                        <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <div class="upload-document-icon mr-3">
                                    <i class="fa fa-file-word text-primary"></i>
                                </div>
                                <span class="upload-document-name f-14 font-weight-400">Medical Letter</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="{{ asset('storage/' . $processData->medical_letter) }}" target="_blank" class="mr-3"><i class="fas fa-eye document-view-icon"></i></a>
                            </div>
                        </div>
                    @endif
                    @if($processData && $processData->air_ticket)
                        <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <div class="upload-document-icon mr-3">
                                    <i class="fa fa-file-word text-primary"></i>
                                </div>
                                <span class="upload-document-name f-14 font-weight-400">Air Ticket</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="{{ asset('storage/' . $processData->air_ticket) }}" target="_blank" class="mr-3"><i class="fas fa-eye document-view-icon"></i></a>
                            </div>
                        </div>
                    @endif
                    @if($processData && $processData->accommodation_letter)
                        <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <div class="upload-document-icon mr-3">
                                    <i class="fa fa-file-word text-primary"></i>
                                </div>
                                <span class="upload-document-name f-14 font-weight-400">Accommodation Configuration Letter</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="{{ asset('storage/' . $processData->accommodation_letter) }}" target="_blank" class="mr-3"><i class="fas fa-eye document-view-icon"></i></a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
