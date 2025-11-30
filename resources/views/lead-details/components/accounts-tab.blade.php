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
                                                $address = $step1Data['address'] ?? '--';
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
                                            <input type="text" class="form-control height-35 f-14" id="service" name="service" value="">
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
