                <div class="tab-content px-4 pb-4" id="accountsTab">
                    <!-- Tab Header -->
                    <div class="tab-section-header">
                        <div class="tab-section-header-content">
                            <h3 class="tab-section-title">ADD INVOICE</h3>
                            <button type="button" class="tab-section-add-btn" data-toggle="modal" data-target="#addInvoiceModal">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Tab Content Area -->
                    <div class="tab-section-content">
                        <div class="text-center p-5">
                            <p class="text-muted mb-3">No invoice has been created for this Lead.</p>
                            <p class="text-muted">To create an Invoice, click on <i class="fa fa-plus"></i> at the top right corner</p>
                        </div>
                                            <!-- Upload Documents Section -->
                    <div class="info-section mb-1">
                        <div class="upload-documents-list">
                            <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                                <div class="d-flex align-items-center">
                                    <div class="upload-document-icon mr-3">
                                        <i class="fa fa-file-pdf text-danger"></i>
                                    </div>
                                    <span class="upload-document-name f-14 font-weight-500">Revised Invoice LEAD-0008 | Kishan Ghaghada | 17-05-2025</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-eye document-view-icon mr-3" style="cursor: pointer;" data-toggle="modal" data-target="#viewInvoiceModal" title="View Invoice"></i>
                                    <button class="btn btn-sm btn-link text-danger p-0 upload-document-delete" title="Delete">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                            <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                                <div class="d-flex align-items-center">
                                    <div class="upload-document-icon mr-3">
                                        <i class="fa fa-file-pdf text-danger"></i>
                                    </div>
                                    <span class="upload-document-name f-14 font-weight-400">Invoice LEAD-0008 | Kishan Ghaghada | 17-05-2025</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-eye document-view-icon mr-3" style="cursor: pointer;" data-toggle="modal" data-target="#viewInvoiceModal" title="View Invoice"></i>
                                    <button class="btn btn-sm btn-link text-danger p-0 upload-document-delete" title="Delete">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>

                </div>

    <!-- Add Invoice Modal -->
    <div class="modal fade" id="addInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" style="max-width: 1200px;">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: 1px solid #E9E6F5; padding: 16px 24px;">
                    <h5 class="modal-title" id="addInvoiceModalLabel" style="font-size: 16px; font-weight: 600; color: #713ED9; margin: 0;">ADD INVOICE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #F5213D; opacity: 1; font-size: 24px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <div class="row">
                        <!-- Left Column: Form Fields -->
                        <div class="col-md-8">
                            <!-- Client Information Section -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <x-forms.label fieldId="client_name" fieldLabel="Client Name">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" id="client_name" name="client_name" value="Kishan Ghaghada">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-forms.label fieldId="invoice_date" fieldLabel="Invoice Date">
                                    </x-forms.label>
                                    <input type="date" class="form-control height-35 f-14" id="invoice_date" name="invoice_date" value="2023-07-17">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-forms.label fieldId="phone" fieldLabel="Phone">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" id="phone" name="phone" value="+91 123 4567 890">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-forms.label fieldId="email" fieldLabel="Email">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" id="email" name="email" value="abc@gmail.com">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <x-forms.label fieldId="address" fieldLabel="Address">
                                    </x-forms.label>
                                    <textarea class="form-control f-14" id="address" name="address" rows="2">906- A, Appartment, Sindhubhavan Road, Appartment, Sindhubhavan Road, Ahmedabad</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-forms.label fieldId="bill_to" fieldLabel="Bill To">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" id="bill_to" name="bill_to" value="Vrajesh Ghaghada">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-forms.label fieldId="invoice_belongs" fieldLabel="Invoice Belongs To">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" id="invoice_belongs" name="invoice_belongs">
                                        <option value="">Select Agent</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Services Section -->
                            <div class="services-section" style="margin-top: 32px;">
                                <div class="services-header" style="background-color: #713ED9; color: #FFFFFF; padding: 10px 16px; margin: 0 -24px 20px -24px; font-weight: 600; font-size: 14px; letter-spacing: 0.5px;">
                                    SERVICES
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <x-forms.label fieldId="service" fieldLabel="Service">
                                        </x-forms.label>
                                        <input type="text" class="form-control height-35 f-14" id="service" name="service" value="Student Visa - Temporary Graduate Visa (Australia)(Subclass 485)">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <x-forms.label fieldId="price" fieldLabel="Price">
                                        </x-forms.label>
                                        <input type="number" class="form-control height-35 f-14" id="price" name="price" value="50000">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <x-forms.label fieldId="tax" fieldLabel="Tax">
                                        </x-forms.label>
                                        <select class="form-control select-picker height-35 f-14" id="tax" name="tax">
                                            <option value="GST 18%" selected>GST 18%</option>
                                            <option value="GST 0%">0% GST</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <x-forms.label fieldId="discount" fieldLabel="Discount">
                                        </x-forms.label>
                                        <input type="number" class="form-control height-35 f-14" id="discount" name="discount" value="5000">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <x-forms.label fieldId="net_amount" fieldLabel="Net Amt">
                                        </x-forms.label>
                                        <input type="number" class="form-control height-35 f-14" id="net_amount" name="net_amount" value="45000" readonly style="background-color: #F5F5F5;">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <x-forms.label fieldId="service_description" fieldLabel="Service Description">
                                        </x-forms.label>
                                        <textarea class="form-control f-14" id="service_description" name="service_description" rows="2">Student Visa - Temporary Graduate Visa (Australia)(Subclass 485) Country Visa + admissions</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Installment Payment Option -->
                            <div class="row" style="margin-top: 24px;">
                                <div class="col-md-12 mb-3">
                                    <div class="form-check form-switch d-flex align-items-center">
                                        <input class="form-check-input" type="checkbox" role="switch" id="installment_payment_toggle" aria-checked="true" style="cursor: pointer; width: 48px; height: 24px;">
                                        <label class="form-check-label ml-3 f-14" for="installment_payment_toggle" style="cursor: pointer; margin-bottom: 0;">
                                            Do you want to avail installment payment option?
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3" id="installment_months_container">
                                    <x-forms.label fieldId="installment_months" fieldLabel="Installment Months">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" id="installment_months" name="installment_months">
                                        <option value="">Select month</option>
                                        <option value="3">3 Months</option>
                                        <option value="6">6 Months</option>
                                        <option value="8" selected>8 Months</option>
                                        <option value="12">12 Months</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Invoice Notes -->
                            <div class="row" style="margin-top: 24px;">
                                <div class="col-md-12 mb-3">
                                    <x-forms.label fieldId="invoice_notes" fieldLabel="Invoice Notes">
                                    </x-forms.label>
                                    <textarea class="form-control f-14" id="invoice_notes" name="invoice_notes" rows="2">Payment conditions - Payment will be 100% advace</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Total Amount Summary Box -->
                        <div class="col-md-4">
                            <div class="total-summary-box" style="background-color: #F1EBFF; border: 1px solid #E9E6F5; border-radius: 8px; padding: 20px; position: sticky; top: 20px;">
                                <h6 class="mb-3" style="font-weight: 600; font-size: 14px; color: #000; margin-bottom: 16px;">Summary</h6>
                                <div class="summary-item" style="display: flex; justify-content: space-between; padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid #E9E6F5;">
                                    <span class="f-14" style="color: #6C6C6C;">Sub Total</span>
                                    <span class="f-14" style="color: #000; font-weight: 500;" id="summary_sub_total">INR 50,000</span>
                                </div>
                                <div class="summary-item" style="display: flex; justify-content: space-between; padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid #E9E6F5;">
                                    <span class="f-14" style="color: #6C6C6C;">Discount</span>
                                    <span class="f-14" style="color: #000; font-weight: 500;" id="summary_discount">INR 5,000</span>
                                </div>
                                <div class="summary-item" style="display: flex; justify-content: space-between; padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid #E9E6F5;">
                                    <span class="f-14" style="color: #6C6C6C;">Tax Amount</span>
                                    <span class="f-14" style="color: #000; font-weight: 500;" id="summary_tax_amount">INR 500</span>
                                </div>
                                <div class="summary-item" style="display: flex; justify-content: space-between; padding-bottom: 16px; margin-bottom: 16px; border-bottom: 2px solid #713ED9;">
                                    <span class="f-14" style="color: #000; font-weight: 600;">Total Amount</span>
                                    <span class="f-14" style="color: #713ED9; font-weight: 600;" id="summary_total_amount">INR 45,500</span>
                                </div>
                                <div class="installment-note" id="installment_note" style="padding-top: 12px; border-top: 1px solid #E9E6F5;">
                                    <span class="f-12" style="color: #6C6C6C;" id="installment_note_text">Installment Rs.5687.5 for 8 months</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #E9E6F5; padding: 16px 24px; justify-content: flex-end;">
                    <button type="button" class="btn" style="background-color: #713ED9; color: #FFFFFF; border: none; padding: 8px 24px; font-weight: 500; border-radius: 4px;">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Invoice Modal -->
    <div class="modal fade" id="viewInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="viewInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
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
                            <p class="invoice-lead-number">LEAD-0008</p>
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
                                    <div class="invoice-detail-value">Kishan Ghaghada</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-label">Email</div>
                                    <div class="invoice-detail-value">abc@gmail.com</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-label">Phone</div>
                                    <div class="invoice-detail-value">+91 123 4567 890</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-label">Invoice Date</div>
                                    <div class="invoice-detail-value">17-07-2023</div>
                                </div>
                            </div>
                            <!-- Row 2: Bill to | Invoice Belongs To | Address -->
                            <div class="col-md-3 mb-3">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-label">Bill to</div>
                                    <div class="invoice-detail-value">Vrajesh Ghaghada</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-label">Invoice Belongs To</div>
                                    <div class="invoice-detail-value">Deepak Parmar</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-label">Address</div>
                                    <div class="invoice-detail-value">906- A, Appartment, Sindhubhavan Road, Appartment, Sindhubhavan Road, Ahmedabad</div>
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
                            <div class="invoice-detail-value">Student Visa - Temporary Graduate Visa (Australia)(Subclass 485)</div>
                        </div>
                        <div class="invoice-service-item">
                            <div class="invoice-detail-label">Note</div>
                            <div class="invoice-detail-value">Payment conditions - Payment will be 100% advance</div>
                        </div>
                    </div>

                    <hr class="invoice-divider">

                    <!-- Payable Amount Section -->
                    <div class="invoice-payable-section mb-4">
                        <h4 class="invoice-section-title">PAYABLE AMOUNT</h4>
                        <div class="invoice-amount-table">
                            <div class="invoice-amount-row">
                                <div class="invoice-amount-label">Sub Total :</div>
                                <div class="invoice-amount-value">INR 50,000</div>
                            </div>
                            <div class="invoice-amount-row">
                                <div class="invoice-amount-label">Discount :</div>
                                <div class="invoice-amount-value">INR 5,000</div>
                            </div>
                            <div class="invoice-amount-row">
                                <div class="invoice-amount-label">Tax Amount :</div>
                                <div class="invoice-amount-value">INR 500</div>
                            </div>
                            <div class="invoice-amount-row total">
                                <div class="invoice-amount-label total">Total Amount :</div>
                                <div class="invoice-amount-value total">INR 45,500</div>
                            </div>
                            <div class="invoice-installment-note">
                                Installment Rs.5,687.5 for 8 months
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
                                <div class="footer-toll-free">1800 571 2844</div>
                                <div class="footer-email">info.rrpei@gmail.com</div>
                            </div>
                            <div class="col-md-4 mb-3 text-right">
                                <div class="footer-address">3rd Floor, Aaron Spectra, 302, Rajpath Rangoli Rd, behind Rajpath Club, Bodakdev, Ahmedabad, Gujarat 380059</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>