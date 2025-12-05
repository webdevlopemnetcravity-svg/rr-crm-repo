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
                                @php
                                    $visaTypes = $visaTypes ?? (isset($lead) && $lead ? \App\Models\NewLeadVisaType::where(function($query) {
                                        $query->where('company_id', company()->id)
                                              ->orWhereNull('company_id');
                                    })->orderBy('name')->get() : collect());
                                @endphp
                                @foreach($visaTypes as $visaType)
                                    @php
                                        $isSelected = false;
                                        if ($processData && $processData->visa_category) {
                                            // Check if stored value matches ID or name (for backward compatibility)
                                            $isSelected = ($processData->visa_category == $visaType->id || $processData->visa_category == $visaType->name);
                                        }
                                    @endphp
                                    <option value="{{ $visaType->id }}" data-visa-type-id="{{ $visaType->id }}" {{ $isSelected ? 'selected' : '' }}>{{ $visaType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-forms.label fieldId="subclass" fieldLabel="Subclass *">
                            </x-forms.label>
                            <select class="form-control select-picker height-35 f-14" name="subclass" id="subclass" required>
                                <option value="">Select</option>
                                @php
                                    $subclasses = $subclasses ?? (isset($lead) && $lead ? \App\Models\NewLeadSubclass::with('visaType')
                                        ->where(function($query) {
                                            $query->where('company_id', company()->id)
                                                  ->orWhereNull('company_id');
                                        })
                                        ->orderBy('name')
                                        ->get() : collect());
                                @endphp
                                @foreach($subclasses as $subclass)
                                    @php
                                        $isSelected = false;
                                        if ($processData && $processData->subclass) {
                                            // Check if stored value matches ID or name (for backward compatibility)
                                            $isSelected = ($processData->subclass == $subclass->id || $processData->subclass == $subclass->name);
                                        }
                                    @endphp
                                    <option value="{{ $subclass->id }}" 
                                            data-visa-type-id="{{ $subclass->visaType->id ?? '' }}" 
                                            data-visa-category="{{ $subclass->visaType->name ?? '' }}"
                                            {{ $isSelected ? 'selected' : '' }}>
                                        {{ $subclass->name }}
                                    </option>
                                @endforeach
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
                            <input type="number" step="0.01" min="0" class="form-control height-35 f-14" name="advance_fees" id="advance_fees" value="{{ $processData ? $processData->advance_fees : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="advance_fees_due_date" fieldLabel="Advance Fees Due Date *">
                            </x-forms.label>
                            <input type="date" class="form-control height-35 f-14" name="advance_fees_due_date" id="advance_fees_due_date" value="{{ $processData && $processData->advance_fees_due_date ? $processData->advance_fees_due_date->format('Y-m-d') : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="remaining_fees" fieldLabel="Remaining Fees *">
                            </x-forms.label>
                            <input type="number" step="0.01" min="0" class="form-control height-35 f-14" name="remaining_fees" id="remaining_fees" value="{{ $processData ? $processData->remaining_fees : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="remaining_fees_due_date" fieldLabel="Remaining Fees Due Date *">
                            </x-forms.label>
                            <input type="date" class="form-control height-35 f-14" name="remaining_fees_due_date" id="remaining_fees_due_date" value="{{ $processData && $processData->remaining_fees_due_date ? $processData->remaining_fees_due_date->format('Y-m-d') : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="agent_fees" fieldLabel="Agent Fees *">
                            </x-forms.label>
                            <input type="number" step="0.01" min="0" class="form-control height-35 f-14" name="agent_fees" id="agent_fees" value="{{ $processData ? $processData->agent_fees : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="submission_fees" fieldLabel="Submission Fees *">
                            </x-forms.label>
                            <input type="number" step="0.01" min="0" class="form-control height-35 f-14" name="submission_fees" id="submission_fees" value="{{ $processData ? $processData->submission_fees : '' }}" required>
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
                            <x-forms.label fieldId="status" fieldLabel="Status Pending/Completed">
                            </x-forms.label>
                            <select class="form-control select-picker height-35 f-14" name="status" id="status">
                                <option value="">Select</option>
                                <option value="Pending" {{ $processData && $processData->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Completed" {{ $processData && $processData->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="processing_time" fieldLabel="Processing Time">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="processing_time" id="processing_time" value="{{ $processData ? $processData->processing_time : '' }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="bank_cheque_handover_date" fieldLabel="Bank Cheque Document Handover Date">
                            </x-forms.label>
                            <input type="date" class="form-control height-35 f-14" name="bank_cheque_handover_date" id="bank_cheque_handover_date" value="{{ $processData && $processData->bank_cheque_handover_date ? $processData->bank_cheque_handover_date->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="passport_handover_date" fieldLabel="Passport Handover Date">
                            </x-forms.label>
                            <input type="date" class="form-control height-35 f-14" name="passport_handover_date" id="passport_handover_date" value="{{ $processData && $processData->passport_handover_date ? $processData->passport_handover_date->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-forms.label fieldId="process_note" fieldLabel="Note related to agent or process">
                            </x-forms.label>
                            <textarea class="form-control f-14" name="process_note" id="process_note" rows="3">{{ $processData ? $processData->process_note : '' }}</textarea>
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
                            <x-forms.label fieldId="contract_letter" fieldLabel="Contract Letter">
                            </x-forms.label>
                            <input type="file" class="form-control height-35 f-14" name="contract_letter" id="contract_letter" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            @if($processData && $processData->contract_letter)
                                <small class="text-muted d-block mt-1">Current: <a href="{{ route('lead-details.view-process-document', ['leadId' => $lead->id, 'documentField' => 'contract_letter']) }}" target="_blank">View</a></small>
                            @endif
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="grant_letter" fieldLabel="Grant Letter">
                            </x-forms.label>
                            <input type="file" class="form-control height-35 f-14" name="grant_letter" id="grant_letter" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            @if($processData && $processData->grant_letter)
                                <small class="text-muted d-block mt-1">Current: <a href="{{ route('lead-details.view-process-document', ['leadId' => $lead->id, 'documentField' => 'grant_letter']) }}" target="_blank">View</a></small>
                            @endif
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="offer_letter" fieldLabel="Offer Letter/Sponsor Letter">
                            </x-forms.label>
                            <input type="file" class="form-control height-35 f-14" name="offer_letter" id="offer_letter" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            @if($processData && $processData->offer_letter)
                                <small class="text-muted d-block mt-1">Current: <a href="{{ route('lead-details.view-process-document', ['leadId' => $lead->id, 'documentField' => 'offer_letter']) }}" target="_blank">View</a></small>
                            @endif
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="medical_letter" fieldLabel="Medical Letter">
                            </x-forms.label>
                            <input type="file" class="form-control height-35 f-14" name="medical_letter" id="medical_letter" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            @if($processData && $processData->medical_letter)
                                <small class="text-muted d-block mt-1">Current: <a href="{{ route('lead-details.view-process-document', ['leadId' => $lead->id, 'documentField' => 'medical_letter']) }}" target="_blank">View</a></small>
                            @endif
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="air_ticket" fieldLabel="Air Ticket">
                            </x-forms.label>
                            <input type="file" class="form-control height-35 f-14" name="air_ticket" id="air_ticket" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            @if($processData && $processData->air_ticket)
                                <small class="text-muted d-block mt-1">Current: <a href="{{ route('lead-details.view-process-document', ['leadId' => $lead->id, 'documentField' => 'air_ticket']) }}" target="_blank">View</a></small>
                            @endif
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="accommodation_letter" fieldLabel="Accommodation Configuration Letter">
                            </x-forms.label>
                            <input type="file" class="form-control height-35 f-14" name="accommodation_letter" id="accommodation_letter" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            @if($processData && $processData->accommodation_letter)
                                <small class="text-muted d-block mt-1">Current: <a href="{{ route('lead-details.view-process-document', ['leadId' => $lead->id, 'documentField' => 'accommodation_letter']) }}" target="_blank">View</a></small>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Additional Documents Section -->
                <div class="info-section mb-1">
                    <div class="info-section-title mb-3">
                        <h4 class="f-16 font-weight-bold">Additional Documents</h4>
                    </div>
                    <!-- Dynamic Additional Documents Rows Container -->
                    <div id="additional-documents-container"></div>
                    
                    <!-- Add More Document Button -->
                    <div class="mt-3 mb-3">
                        <button type="button" class="btn btn-secondary btn-sm" id="add-more-document">
                            <i class="fa fa-plus mr-1"></i> Add More Document
                        </button>
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
                        <div class="info-field-value-text">
                            @php
                                $visaCategoryName = '-';
                                if ($processData && $processData->visa_category) {
                                    // Check if it's numeric (ID) or string (name for backward compatibility)
                                    if (is_numeric($processData->visa_category)) {
                                        $visaType = \App\Models\NewLeadVisaType::find($processData->visa_category);
                                        $visaCategoryName = $visaType ? $visaType->name : $processData->visa_category;
                                    } else {
                                        // Backward compatibility: if it's a name, display it as is
                                        $visaCategoryName = $processData->visa_category;
                                    }
                                }
                            @endphp
                            {{ $visaCategoryName }}
                        </div>
                    </div>
                    <div class="info-field-item col-md-6 mb-3">
                        <div class="info-field-label-text">Subclass</div>
                        <div class="info-field-value-text">
                            @php
                                $subclassName = '-';
                                if ($processData && $processData->subclass) {
                                    // Check if it's numeric (ID) or string (name for backward compatibility)
                                    if (is_numeric($processData->subclass)) {
                                        $subclass = \App\Models\NewLeadSubclass::find($processData->subclass);
                                        $subclassName = $subclass ? $subclass->name : $processData->subclass;
                                    } else {
                                        // Backward compatibility: if it's a name, display it as is
                                        $subclassName = $processData->subclass;
                                    }
                                }
                            @endphp
                            {{ $subclassName }}
                        </div>
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
                    @php
                        // Helper function to get document URL
                        $getDocumentUrl = function($fileName) use ($lead) {
                            if (empty($fileName)) {
                                return null;
                            }
                            try {
                                // Files are stored in public/user-uploads/public/process_documents/{leadId}/{filename}
                                // The fileName stored in DB might be: process_documents/5/contract_letter_1234567890.pdf
                                // Or: public/process_documents/5/contract_letter_1234567890.pdf
                                
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
                                return asset_url_local_s3($filePath);
                            } catch (\Exception $e) {
                                // Fallback to direct asset if helper fails
                                try {
                                    // Try with user-uploads/public/ prefix
                                    if (strpos($fileName, 'public/') === 0) {
                                        return asset('user-uploads/' . $fileName);
                                    } else {
                                        return asset('user-uploads/public/' . $fileName);
                                    }
                                } catch (\Exception $e2) {
                                    return null;
                                }
                            }
                        };
                    @endphp
                    @if($processData && $processData->contract_letter)
                        @php
                            $contractLetterUrl = $getDocumentUrl($processData->contract_letter);
                        @endphp
                        <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <div class="upload-document-icon mr-3">
                                    <i class="fa fa-file-pdf text-danger"></i>
                                </div>
                                <span class="upload-document-name f-14 font-weight-500">Contract Letter</span>
                            </div>
                            <div class="d-flex align-items-center">
                                @if($contractLetterUrl)
                                    <a href="{{ $contractLetterUrl }}" target="_blank" class="mr-3 document-view-icon" title="View Document"><i class="fas fa-eye"></i></a>
                                @else
                                    <a href="{{ route('lead-details.view-process-document', ['leadId' => $lead->id, 'documentField' => 'contract_letter']) }}" target="_blank" class="mr-3 document-view-icon" title="View Document"><i class="fas fa-eye"></i></a>
                                @endif
                            </div>
                        </div>
                    @endif
                    @if($processData && $processData->grant_letter)
                        @php
                            $grantLetterUrl = $getDocumentUrl($processData->grant_letter);
                        @endphp
                        <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <div class="upload-document-icon mr-3">
                                    <i class="fa fa-file-word text-primary"></i>
                                </div>
                                <span class="upload-document-name f-14 font-weight-400">Grant Letter</span>
                            </div>
                            <div class="d-flex align-items-center">
                                @if($grantLetterUrl)
                                    <a href="{{ $grantLetterUrl }}" target="_blank" class="mr-3 document-view-icon" title="View Document"><i class="fas fa-eye"></i></a>
                                @else
                                    <a href="{{ route('lead-details.view-process-document', ['leadId' => $lead->id, 'documentField' => 'grant_letter']) }}" target="_blank" class="mr-3 document-view-icon" title="View Document"><i class="fas fa-eye"></i></a>
                                @endif
                            </div>
                        </div>
                    @endif
                    @if($processData && $processData->offer_letter)
                        @php
                            $offerLetterUrl = $getDocumentUrl($processData->offer_letter);
                        @endphp
                        <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <div class="upload-document-icon mr-3">
                                    <i class="fa fa-file-word text-primary"></i>
                                </div>
                                <span class="upload-document-name f-14 font-weight-400">Offer Letter/Sponsor Letter</span>
                            </div>
                            <div class="d-flex align-items-center">
                                @if($offerLetterUrl)
                                    <a href="{{ $offerLetterUrl }}" target="_blank" class="mr-3 document-view-icon" title="View Document"><i class="fas fa-eye"></i></a>
                                @else
                                    <a href="{{ route('lead-details.view-process-document', ['leadId' => $lead->id, 'documentField' => 'offer_letter']) }}" target="_blank" class="mr-3 document-view-icon" title="View Document"><i class="fas fa-eye"></i></a>
                                @endif
                            </div>
                        </div>
                    @endif
                    @if($processData && $processData->medical_letter)
                        @php
                            $medicalLetterUrl = $getDocumentUrl($processData->medical_letter);
                        @endphp
                        <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <div class="upload-document-icon mr-3">
                                    <i class="fa fa-file-word text-primary"></i>
                                </div>
                                <span class="upload-document-name f-14 font-weight-400">Medical Letter</span>
                            </div>
                            <div class="d-flex align-items-center">
                                @if($medicalLetterUrl)
                                    <a href="{{ $medicalLetterUrl }}" target="_blank" class="mr-3 document-view-icon" title="View Document"><i class="fas fa-eye"></i></a>
                                @else
                                    <a href="{{ route('lead-details.view-process-document', ['leadId' => $lead->id, 'documentField' => 'medical_letter']) }}" target="_blank" class="mr-3 document-view-icon" title="View Document"><i class="fas fa-eye"></i></a>
                                @endif
                            </div>
                        </div>
                    @endif
                    @if($processData && $processData->air_ticket)
                        @php
                            $airTicketUrl = $getDocumentUrl($processData->air_ticket);
                        @endphp
                        <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <div class="upload-document-icon mr-3">
                                    <i class="fa fa-file-word text-primary"></i>
                                </div>
                                <span class="upload-document-name f-14 font-weight-400">Air Ticket</span>
                            </div>
                            <div class="d-flex align-items-center">
                                @if($airTicketUrl)
                                    <a href="{{ $airTicketUrl }}" target="_blank" class="mr-3 document-view-icon" title="View Document"><i class="fas fa-eye"></i></a>
                                @else
                                    <a href="{{ route('lead-details.view-process-document', ['leadId' => $lead->id, 'documentField' => 'air_ticket']) }}" target="_blank" class="mr-3 document-view-icon" title="View Document"><i class="fas fa-eye"></i></a>
                                @endif
                            </div>
                        </div>
                    @endif
                    @if($processData && $processData->accommodation_letter)
                        @php
                            $accommodationLetterUrl = $getDocumentUrl($processData->accommodation_letter);
                        @endphp
                        <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <div class="upload-document-icon mr-3">
                                    <i class="fa fa-file-word text-primary"></i>
                                </div>
                                <span class="upload-document-name f-14 font-weight-400">Accommodation Configuration Letter</span>
                            </div>
                            <div class="d-flex align-items-center">
                                @if($accommodationLetterUrl)
                                    <a href="{{ $accommodationLetterUrl }}" target="_blank" class="mr-3 document-view-icon" title="View Document"><i class="fas fa-eye"></i></a>
                                @else
                                    <a href="{{ route('lead-details.view-process-document', ['leadId' => $lead->id, 'documentField' => 'accommodation_letter']) }}" target="_blank" class="mr-3 document-view-icon" title="View Document"><i class="fas fa-eye"></i></a>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <!-- Additional Documents -->
                    @if($processData && $processData->additional_documents)
                        @php
                            $additionalDocs = is_string($processData->additional_documents) 
                                ? json_decode($processData->additional_documents, true) 
                                : $processData->additional_documents;
                            $additionalDocs = is_array($additionalDocs) ? $additionalDocs : [];
                            
                            // Helper function to get document URL
                            $getAdditionalDocUrl = function($fileName) use ($lead) {
                                if (empty($fileName)) return null;
                                try {
                                    if (strpos($fileName, 'public/') === 0) {
                                        $filePath = $fileName;
                                    } else {
                                        $filePath = 'public/' . $fileName;
                                    }
                                    return asset_url_local_s3($filePath);
                                } catch (\Exception $e) {
                                    return null;
                                }
                            };
                        @endphp
                        @if(count($additionalDocs) > 0)
                            @foreach($additionalDocs as $doc)
                                @php
                                    $docName = $doc['document_name'] ?? 'N/A';
                                    $docFile = $doc['document_file'] ?? '';
                                    $docUrl = $getAdditionalDocUrl($docFile);
                                @endphp
                                @if($docFile)
                                    <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="upload-document-icon mr-3">
                                                <i class="fa fa-file-pdf text-danger"></i>
                                            </div>
                                            <span class="upload-document-name f-14 font-weight-400">{{ $docName }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            @if($docUrl)
                                                <a href="{{ $docUrl }}" target="_blank" class="mr-3 document-view-icon" title="View Document"><i class="fas fa-eye"></i></a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
