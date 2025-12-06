                <div class="tab-content px-4 pb-4" id="accountsTab">
                    <!-- Tab Header -->
                    <div class="tab-section-header">
                        <div class="tab-section-header-content">
                            <h3 class="tab-section-title">ACCOUNTS</h3>
                            @php
                                $userRoles = user_roles();
                                $isAdmin = in_array('admin', $userRoles);
                                $isEmployee = in_array('employee', $userRoles);
                                $isDraft = false;
                                if (isset($lead) && $lead && $lead->stepStatus && $lead->stepStatus->final_status == 'draft') {
                                    $isDraft = true;
                                }
                            @endphp
                            @if(($isAdmin || $isEmployee) && !$isDraft)
                                <button type="button" class="tab-section-add-btn" id="addAccountBtn">
                                    <i class="fa fa-plus"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    <!-- Tab Content Area -->
                    <div class="tab-section-content" id="accountsContent">
                        @include('lead-details.components.accounts-list')
                    </div>
                </div>

    <!-- Add/Edit Account Modal -->
    <div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog" aria-labelledby="addAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" style="max-width: 1200px;">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: 1px solid #dcecff; padding: 16px 24px;">
                    <h5 class="modal-title" id="addAccountModalLabel" style="font-size: 16px; font-weight: 500; color: #1D82F5; margin: 0;">ADD INVOICE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #F5213D; opacity: 1; font-size: 24px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <x-form id="accountFormDetails" method="POST" class="ajax-form">
                    <div class="modal-body" style="padding: 24px;">
                        <input type="hidden" name="new_lead_id" id="account_lead_id_details" value="{{ isset($lead) && $lead ? $lead->id : '' }}">
                        <input type="hidden" name="id" id="account_id_details">
                        <div class="row">
                            <!-- Left Column: Form Fields -->
                            <div class="col-md-8">
                                <!-- Client Information Section (Predefined - Display Only) -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-forms.label fieldId="client_name" fieldLabel="Client Name *">
                                        </x-forms.label>
                                        @php
                                            $clientName = '--';
                                            if(isset($lead) && $lead) {
                                                $step1Data = is_string($lead->step_1_data) ? json_decode($lead->step_1_data, true) : ($lead->step_1_data ?? []);
                                                $clientName = trim($lead->client_name ?? ($step1Data['first_name'] ?? '') . ' ' . ($step1Data['last_name'] ?? ''));
                                                $clientName = $clientName ?: '--';
                                            }
                                        @endphp
                                        <input type="text" class="form-control height-35 f-14" id="client_name" name="client_name" value="{{ $clientName }}" disabled style="background-color: #F5F5F5; cursor: not-allowed;">
                                    </div>
                                    <div class="col-md-6" style="margin-top: -16px;">
                                        <x-forms.datepicker fieldId="invoice_date" fieldLabel="Invoice Date *" fieldName="invoice_date"
                                            :fieldValue="now(company()->timezone)->format(company()->date_format)"
                                            :fieldPlaceholder="__('placeholders.date')" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-forms.label fieldId="phone" fieldLabel="Phone *">
                                        </x-forms.label>
                                        @php
                                            $phone = '--';
                                            if(isset($lead) && $lead) {
                                                $step1Data = is_string($lead->step_1_data) ? json_decode($lead->step_1_data, true) : ($lead->step_1_data ?? []);
                                                $phone = $step1Data['primary_phone'] ?? $lead->mobile ?? '--';
                                            }
                                        @endphp
                                        <input type="text" class="form-control height-35 f-14" id="phone" name="phone" value="{{ $phone }}" disabled style="background-color: #F5F5F5; cursor: not-allowed;">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-forms.label fieldId="email" fieldLabel="Email *">
                                        </x-forms.label>
                                        @php
                                            $email = '--';
                                            if(isset($lead) && $lead) {
                                                $step1Data = is_string($lead->step_1_data) ? json_decode($lead->step_1_data, true) : ($lead->step_1_data ?? []);
                                                $email = $step1Data['email_address'] ?? $lead->client_email ?? '--';
                                            }
                                        @endphp
                                        <input type="email" class="form-control height-35 f-14" id="email" name="email" value="{{ $email }}" disabled style="background-color: #F5F5F5; cursor: not-allowed;">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <x-forms.label fieldId="address" fieldLabel="Address *">
                                        </x-forms.label>
                                        @php
                                            $address = '--';
                                            if(isset($lead) && $lead) {
                                                $step1Data = is_string($lead->step_1_data) ? json_decode($lead->step_1_data, true) : ($lead->step_1_data ?? []);
                                                
                                                // Check if mailing address same as home address
                                                $mailingSameAsHome = !empty($step1Data['mailing_same_as_home']) && $step1Data['mailing_same_as_home'] == '1';
                                                
                                                if ($mailingSameAsHome) {
                                                    // Use home address if mailing same as home
                                                    $addressParts = [];
                                                    if (!empty($step1Data['home_address'])) {
                                                        $addressParts[] = $step1Data['home_address'];
                                                    }
                                                    if (!empty($step1Data['home_city'])) {
                                                        $addressParts[] = $step1Data['home_city'];
                                                    }
                                                    if (!empty($step1Data['home_state'])) {
                                                        $addressParts[] = $step1Data['home_state'];
                                                    }
                                                    if (!empty($step1Data['home_pin_code'])) {
                                                        $addressParts[] = $step1Data['home_pin_code'];
                                                    }
                                                    $address = !empty($addressParts) ? implode(', ', $addressParts) : '--';
                                                } else {
                                                    // Use mailing address
                                                    $addressParts = [];
                                                    if (!empty($step1Data['mailing_address'])) {
                                                        $addressParts[] = $step1Data['mailing_address'];
                                                    }
                                                    if (!empty($step1Data['mailing_city'])) {
                                                        $addressParts[] = $step1Data['mailing_city'];
                                                    }
                                                    if (!empty($step1Data['mailing_state'])) {
                                                        $addressParts[] = $step1Data['mailing_state'];
                                                    }
                                                    if (!empty($step1Data['mailing_pin_code'])) {
                                                        $addressParts[] = $step1Data['mailing_pin_code'];
                                                    }
                                                    $address = !empty($addressParts) ? implode(', ', $addressParts) : '--';
                                                }
                                            }
                                        @endphp
                                        <textarea class="form-control f-14" id="address" name="address" rows="3" disabled style="background-color: #F5F5F5; cursor: not-allowed;">{{ $address }}</textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-forms.label fieldId="bill_to" fieldLabel="Bill To *">
                                        </x-forms.label>
                                        <input type="text" class="form-control height-35 f-14" id="bill_to" name="bill_to" value="">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-forms.label fieldId="agent" fieldLabel="Invoice Belongs To (Agent)">
                                        </x-forms.label>
                                        <select class="form-control select-picker height-35 f-14" id="agent" name="agent" data-live-search="true">
                                            <option value="">Select Agent</option>
                                            @if(isset($employees))
                                                @foreach ($employees as $employee)
                                                    <x-user-option :user="$employee" />
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <!-- Services Section -->
                                <div class="services-section" style="margin-top: 32px;">
                                    <div class="services-header" style="background-color: #1D82F5; color: #FFFFFF; padding: 10px 16px; margin: 0 -24px 20px -24px; font-weight: 600; font-size: 14px; letter-spacing: 0.5px;">
                                        SERVICES
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <x-forms.label fieldId="service" fieldLabel="Service">
                                            </x-forms.label>
                                            @php
                                                $serviceValue = '';
                                                if (isset($lead) && $lead && $lead->step_2_data) {
                                                    $step2Data = is_array($lead->step_2_data) ? $lead->step_2_data : json_decode($lead->step_2_data, true);
                                                    if ($step2Data) {
                                                        $subclassId = null;
                                                        // Get subclass based on visa type
                                                        if (isset($step2Data['visa_type'])) {
                                                            $visaType = $step2Data['visa_type'];
                                                            // Check if visa_type is numeric (ID) or string (old format)
                                                            if (is_numeric($visaType)) {
                                                                // New format: get section from visa type name mapping
                                                                $visaTypeModel = \App\Models\NewLeadVisaType::find($visaType);
                                                                if ($visaTypeModel) {
                                                                    $visaTypeName = strtolower($visaTypeModel->name);
                                                                    if (stripos($visaTypeName, 'pr') !== false || stripos($visaTypeName, 'permanent') !== false) {
                                                                        $subclassId = $step2Data['pr_subclass'] ?? null;
                                                                    } elseif (stripos($visaTypeName, 'visit') !== false) {
                                                                        $subclassId = $step2Data['visit_subclass'] ?? null;
                                                                    } elseif (stripos($visaTypeName, 'work') !== false) {
                                                                        $subclassId = $step2Data['work_subclass'] ?? null;
                                                                    } elseif (stripos($visaTypeName, 'student') !== false) {
                                                                        $subclassId = $step2Data['student_subclass'] ?? null;
                                                                    }
                                                                }
                                                            } else {
                                                                // Old format: direct mapping
                                                                $visaTypeLower = strtolower($visaType);
                                                                if ($visaTypeLower === 'pr') {
                                                                    $subclassId = $step2Data['pr_subclass'] ?? null;
                                                                } elseif ($visaTypeLower === 'visit') {
                                                                    $subclassId = $step2Data['visit_subclass'] ?? null;
                                                                } elseif ($visaTypeLower === 'work') {
                                                                    $subclassId = $step2Data['work_subclass'] ?? null;
                                                                } elseif ($visaTypeLower === 'student') {
                                                                    $subclassId = $step2Data['student_subclass'] ?? null;
                                                                }
                                                            }
                                                        }
                                                        
                                                        // Convert subclass ID to name
                                                        if ($subclassId) {
                                                            if (is_numeric($subclassId)) {
                                                                $subclassModel = \App\Models\NewLeadSubclass::find($subclassId);
                                                                $serviceValue = $subclassModel ? $subclassModel->name : '';
                                                            } else {
                                                                // Backward compatibility: if it's a string (old format), use it directly
                                                                $serviceValue = $subclassId;
                                                            }
                                                        }
                                                    }
                                                }
                                            @endphp
                                            <input type="text" class="form-control height-35 f-14" id="service" name="service" value="{{ $serviceValue }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <x-forms.label fieldId="price" fieldLabel="Price *">
                                            </x-forms.label>
                                            <input type="number" class="form-control height-35 f-14" id="price" name="price" value="0" step="0.01">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <x-forms.label fieldId="tax" fieldLabel="Tax *">
                                            </x-forms.label>
                                            <select class="form-control select-picker height-35 f-14" id="tax" name="tax">
                                                <option value="GST 18%">GST 18%</option>
                                                <option value="GST 0%">0% GST</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <x-forms.label fieldId="discount" fieldLabel="Discount">
                                            </x-forms.label>
                                            <input type="number" class="form-control height-35 f-14" id="discount" name="discount" value="0" step="0.01">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <x-forms.label fieldId="net_amount" fieldLabel="Net Amt">
                                            </x-forms.label>
                                            <input type="number" class="form-control height-35 f-14" id="net_amount" name="net_amount" value="0" readonly style="background-color: #F5F5F5;" step="0.01">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <x-forms.label fieldId="service_description" fieldLabel="Service Description">
                                            </x-forms.label>
                                            <textarea class="form-control f-14" id="service_description" name="service_description" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Installment Payment Option -->
                                <div class="row" style="margin-top: 5px;">
                                    <div class="col-md-12 mb-1">
                                        <div class="form-group">
                                            <div class="form-check form-switch d-flex align-items-center" style="padding: 8px 0;">
                                                <input class="form-check-input" type="checkbox" role="switch" id="installment_payment_toggle" name="installment_payment" value="1" style="margin-left: 0;">
                                                <label class="form-check-label" for="installment_payment_toggle" style="margin-left: 30px; margin-top: 5px;">
                                                    Do you want to avail installment payment option?
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3" id="installment_months_container" style="display: none;">
                                        <x-forms.label fieldId="installment_months" fieldLabel="Installment Months *">
                                        </x-forms.label>
                                        <select class="form-control select-picker height-35 f-14" id="installment_months" name="installment_months">
                                            <option value="">Select month</option>
                                            <option value="3">3 Months</option>
                                            <option value="6">6 Months</option>
                                            <option value="8">8 Months</option>
                                            <option value="12">12 Months</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Invoice Notes -->
                                <div class="row" style="margin-top: 5px;">
                                    <div class="col-md-12 mb-3">
                                        <x-forms.label fieldId="invoice_notes" fieldLabel="Invoice Notes">
                                        </x-forms.label>
                                        <textarea class="form-control f-14" id="invoice_notes" name="invoice_notes" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Total Amount Summary Box -->
                            <div class="col-md-4">
                                <div class="total-summary-box" style="background-color: #e1efff; border: 1px solid #dcecff; border-radius: 8px; padding: 20px; position: sticky; top: 20px;">
                                    <h6 class="mb-3" style="font-weight: 600; font-size: 14px; color: #000; margin-bottom: 16px;">Summary</h6>
                                    <div class="summary-item" style="display: flex; justify-content: space-between; padding-bottom: 12px;">
                                        <span class="f-14" style="color: #6C6C6C;">Sub Total</span>
                                        <span class="f-14" style="color: #000; font-weight: 500;" id="summary_sub_total">
                                            @php
                                                try {
                                                    $currencySymbol = company()->currency ? company()->currency->currency_symbol : '₹';
                                                } catch (\Exception $e) {
                                                    $currencySymbol = '₹';
                                                }
                                            @endphp
                                            {{ $currencySymbol }} 0.00
                                        </span>
                                    </div>
                                    <div class="summary-item" style="display: flex; justify-content: space-between; padding-bottom: 12px;">
                                        <span class="f-14" style="color: #6C6C6C;">Discount</span>
                                        <span class="f-14" style="color: #000; font-weight: 500;" id="summary_discount">{{ $currencySymbol }} 0.00</span>
                                    </div>
                                    <div class="summary-item" style="display: flex; justify-content: space-between; padding-bottom: 12px; margin-bottom: 12px; border-bottom: 2px solid #1D82F5;">
                                        <span class="f-14" style="color: #6C6C6C;">Tax Amount</span>
                                        <span class="f-14" style="color: #000; font-weight: 500;" id="summary_tax_amount">{{ $currencySymbol }} 0.00</span>
                                    </div>
                                    <div class="summary-item" style="display: flex; justify-content: space-between; padding-bottom: 6px;">
                                        <span class="f-14" style="color: #000; font-weight: 600;">Total Amount</span>
                                        <span class="f-14" style="color: #1D82F5; font-weight: 600;" id="summary_total_amount">{{ $currencySymbol }} 0.00</span>
                                    </div>
                                    <div class="installment-note" id="installment_note" style="display: none;">
                                        <span class="f-12" style="color: #6C6C6C;" id="installment_note_text"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #dcecff; padding: 16px 24px; justify-content: flex-end;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <x-forms.button-primary id="save-account-btn" icon="check">Save</x-forms.button-primary>
                    </div>
                </x-form>
            </div>
        </div>
    </div>

    <!-- View Invoice Modal -->
    <div class="modal fade" id="viewInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="viewInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header invoice-view-header">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title invoice-view-title">VIEW INVOICE</h5>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn invoice-download-btn">
                                <i class="fa fa-download mr-1"></i> Download Invoice
                            </button>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Modal Body -->
                <div class="modal-body invoice-view-body">
                    <!-- Invoice Title and Company Info Section -->
                    <div class="invoice-header-row mb-4 d-flex justify-content-between align-items-start">
                        <!-- Invoice Title Section (Left) -->
                        <div class="invoice-title-section">
                            <h2 class="invoice-main-title">INVOICE</h2>
                            <p class="invoice-lead-number" id="view_invoice_lead_number">--</p>
                        </div>
                        <!-- Company Logo Section (Right) -->
                        <div class="invoice-company-info">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ company()->light_logo_url ?? global_setting()->light_logo_url }}" alt="Company Logo" class="company-logo-image">
                            </div>
                        </div>
                    </div>
                    <hr class="invoice-divider">
                    <!-- Client and Invoice Details Section -->
                    <div class="invoice-details-grid mb-4">
                        <div class="row">
                            <!-- Row 1: Client Name | Email | Phone | Invoice Date -->
                            <div class="col-md-3 mb-3">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-label">Client Name</div>
                                    <div class="invoice-detail-value" id="view_client_name">--</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-label">Email</div>
                                    <div class="invoice-detail-value" id="view_email">--</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-label">Phone</div>
                                    <div class="invoice-detail-value" id="view_phone">--</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-label">Invoice Date</div>
                                    <div class="invoice-detail-value" id="view_invoice_date">--</div>
                                </div>
                            </div>
                            <!-- Row 2: Bill to | Invoice Belongs To | Address -->
                            <div class="col-md-3 mb-3">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-label">Bill to</div>
                                    <div class="invoice-detail-value" id="view_bill_to">--</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-label">Invoice Belongs To</div>
                                    <div class="invoice-detail-value" id="view_agent_name">--</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-label">Address</div>
                                    <div class="invoice-detail-value" id="view_address">--</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="invoice-divider">
                    <!-- Details Section -->
                    <div class="invoice-service-section mb-4">
                        <h4 class="invoice-section-title">DETAILS</h4>
                        <div class="invoice-service-item mb-3">
                            <div class="invoice-detail-label">Service</div>
                            <div class="invoice-detail-value" id="view_service">--</div>
                        </div>
                        <div class="invoice-service-item">
                            <div class="invoice-detail-label">Note</div>
                            <div class="invoice-detail-value" id="view_service_description">--</div>
                        </div>
                    </div>
                    <hr class="invoice-divider">
                    <!-- Payable Amount Section -->
                    <div class="invoice-payable-section mb-4">
                        <h4 class="invoice-section-title">PAYABLE AMOUNT</h4>
                        <div class="invoice-amount-table">
                            <div class="invoice-amount-row">
                                <div class="invoice-amount-label">Sub Total :</div>
                                <div class="invoice-amount-value" id="view_sub_total">--</div>
                            </div>
                            <div class="invoice-amount-row">
                                <div class="invoice-amount-label">Discount :</div>
                                <div class="invoice-amount-value" id="view_discount">--</div>
                            </div>
                            <div class="invoice-amount-row">
                                <div class="invoice-amount-label">Tax Amount :</div>
                                <div class="invoice-amount-value" id="view_tax_amount">--</div>
                            </div>
                            <div class="invoice-amount-row total">
                                <div class="invoice-amount-label total">Total Amount :</div>
                                <div class="invoice-amount-value total" id="view_total_amount">--</div>
                            </div>
                            <div class="invoice-installment-note" id="view_installment_note" style="display: none;">
                                <span id="view_installment_note_text"></span>
                            </div>
                        </div>
                    </div>
                    <hr class="invoice-divider">
                    <!-- Footer Section -->
                    <div class="invoice-footer-section">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ company()->light_logo_url ?? global_setting()->light_logo_url }}" alt="Company Logo" class="company-logo-image-footer">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 text-center">
                                <div class="footer-label">Toll-Free Number</div>
                                <div class="footer-toll-free">{{ company()->phone ?? '1800 571 2844' }}</div>
                                <div class="footer-email">{{ company()->company_email ?? 'info.rrpei@gmail.com' }}</div>
                            </div>
                            <div class="col-md-4 mb-3 text-right">
                                <div class="footer-address">3rd Floor, Aaron Spectra, 302, Rajpath Rangoli Rd, behind Rajpath Club, Bodakdev, Ahmedabad, Gujarat 380059 </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
