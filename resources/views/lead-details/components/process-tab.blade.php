                <div class="tab-content px-4 pb-4" id="processTab">
                    <!-- Tab Header -->
                    <div class="tab-section-header">
                        <div class="tab-section-header-content">
                            <h3 class="tab-section-title">Process - <span class="tab-section-subtitle"> Registered Date: 05-09-2025</span></h3>
                        </div>
                    </div>
                    <!-- Tab Content Area -->
                    <div class="tab-section-content">
                        <!-- Agent & Applicant Details Section -->
                        <div class="info-section mb-1">
                            <div class="info-section-title mb-3">
                                <h4 class="f-16 font-weight-bold">Agent & Applicant Details</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <x-forms.label fieldId="applicant_name" fieldLabel="Applicant Name">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="applicant_name" id="applicant_name" placeholder="">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <x-forms.label fieldId="visa_category" fieldLabel="Visa Category">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="visa_category" id="visa_category">
                                        <option value="">Select</option>
                                        <option value="PR">PR</option>
                                        <option value="Student Visa">Student Visa</option>
                                        <option value="Visit Visa">Visit Visa</option>
                                        <option value="Work Permit">Work Permit</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-forms.label fieldId="subclass" fieldLabel="Subclass">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="subclass" id="subclass">
                                        <option value="">Select</option>
                                        <option value="Visitor Visa (Subclass 600)">Visitor Visa (Subclass 600)</option>
                                        <option value="PR - Employer Nomination Scheme (ENS)(Subclass 186)">PR - Employer Nomination Scheme (ENS)(Subclass 186)</option>
                                        <option value="PR - Skilled Nominated Visa (Subclass 190)">PR - Skilled Nominated Visa (Subclass 190)</option>
                                        <option value="PR - Skilled Independent Visa (Subclass 189)">PR - Skilled Independent Visa (Subclass 189)</option>
                                        <option value="Work Visa - Temporary Skill Shortage Visa (Subclass 482)">Work Visa - Temporary Skill Shortage Visa (Subclass 482)</option>
                                        <option value="Work Visa - Skilled Work Regional Visa (Australia) (Subclass 491)">Work Visa - Skilled Work Regional Visa (Australia) (Subclass 491)</option>
                                        <option value="Student Visa (Subclass 500)">Student Visa (Subclass 500)</option>
                                        <option value="Student Visa - Temporary Graduate Visa (Australia)(Subclass 485)">Student Visa - Temporary Graduate Visa (Australia)(Subclass 485)</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <x-forms.label fieldId="passport_name" fieldLabel="Passport Name">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="passport_name" id="passport_name" placeholder="">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <x-forms.label fieldId="passport_number" fieldLabel="Passport Number">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="passport_number" id="passport_number" placeholder="">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <x-forms.label fieldId="agent_name" fieldLabel="Agent Name">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="agent_name" id="agent_name" placeholder="">
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
                                    <x-forms.label fieldId="advance_fees" fieldLabel="Advance Fees">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="advance_fees" id="advance_fees" placeholder="">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <x-forms.label fieldId="advance_fees_due_date" fieldLabel="Advance Fees Due Date">
                                    </x-forms.label>
                                    <input type="date" class="form-control height-35 f-14" name="advance_fees_due_date" id="advance_fees_due_date" placeholder="">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <x-forms.label fieldId="remaining_fees" fieldLabel="Remaining Fees">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="remaining_fees" id="remaining_fees" placeholder="">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <x-forms.label fieldId="remaining_fees_due_date" fieldLabel="Remaining Fees Due Date">
                                    </x-forms.label>
                                    <input type="date" class="form-control height-35 f-14" name="remaining_fees_due_date" id="remaining_fees_due_date" placeholder="">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <x-forms.label fieldId="agent_fees" fieldLabel="Agent Fees">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="agent_fees" id="agent_fees" placeholder="">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <x-forms.label fieldId="submission_fees" fieldLabel="Submission Fees">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="submission_fees" id="submission_fees" placeholder="">
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
                                    <input type="text" class="form-control height-35 f-14" name="status" id="status" placeholder="">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <x-forms.label fieldId="processing_time" fieldLabel="Processing Time">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="processing_time" id="processing_time" placeholder="">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <x-forms.label fieldId="bank_cheque_handover_date" fieldLabel="Bank Cheque Document Handover Date">
                                    </x-forms.label>
                                    <input type="date" class="form-control height-35 f-14" name="bank_cheque_handover_date" id="bank_cheque_handover_date" placeholder="">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <x-forms.label fieldId="passport_handover_date" fieldLabel="Passport Handover Date">
                                    </x-forms.label>
                                    <input type="date" class="form-control height-35 f-14" name="passport_handover_date" id="passport_handover_date" placeholder="">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <x-forms.label fieldId="process_note" fieldLabel="Note related to agent or process">
                                    </x-forms.label>
                                    <textarea class="form-control f-14" name="process_note" id="process_note" rows="3" placeholder=""></textarea>
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
                                    <input type="file" class="form-control" name="contract_letter" id="contract_letter">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <x-forms.label fieldId="grant_letter" fieldLabel="Grant Letter">
                                    </x-forms.label>
                                    <input type="file" class="form-control" name="grant_letter" id="grant_letter">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <x-forms.label fieldId="offer_letter" fieldLabel="Offer Letter/Sponsor Letter">
                                    </x-forms.label>
                                    <input type="file" class="form-control" name="offer_letter" id="offer_letter">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <x-forms.label fieldId="medical_letter" fieldLabel="Medical Letter">
                                    </x-forms.label>
                                    <input type="file" class="form-control" name="medical_letter" id="medical_letter">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <x-forms.label fieldId="air_ticket" fieldLabel="Air Ticket">
                                    </x-forms.label>
                                    <input type="file" class="form-control" name="air_ticket" id="air_ticket">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <x-forms.label fieldId="accommodation_letter" fieldLabel="Accommodation Configuration Letter">
                                    </x-forms.label>
                                    <input type="file" class="form-control" name="accommodation_letter" id="accommodation_letter">
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-secondary btn-sm">
                                    <i class="fa fa-plus mr-1"></i> Add More Document
                                </button>
                            </div>
                        </div>
                                                <!-- Agent & Applicant Details Section -->
                                                <div class="info-section mb-1">
                                                    <div class="info-section-header mb-3">
                                                        <h4 class="info-section-title-text">Agent & Applicant Details</h4>
                                                        <div class="info-section-divider"></div>
                                                    </div>
                                                    <div class="info-grid-row row">
                                                        <div class="info-field-item col-md-3 mb-3">
                                                            <div class="info-field-label-text">Applicant Name</div>
                                                            <div class="info-field-value-text">15 March 2026</div>
                                                        </div>
                                                        <div class="info-field-item col-md-3 mb-3">
                                                            <div class="info-field-label-text">Passport Name</div>
                                                            <div class="info-field-value-text">AI 173</div>
                                                        </div>
                                                        <div class="info-field-item col-md-3 mb-3">
                                                            <div class="info-field-label-text">Passport Number</div>
                                                            <div class="info-field-value-text">San Francisco</div>
                                                        </div>
                                                        <div class="info-field-item col-md-3 mb-3">
                                                            <div class="info-field-label-text">Agent Name</div>
                                                            <div class="info-field-value-text">30 March 2026</div>
                                                        </div>
                                                    </div>
                                                    <div class="info-grid-row row mt-3">
                                                        <div class="info-field-item col-md-6 mb-3">
                                                            <div class="info-field-label-text">Visa Category</div>
                                                            <div class="info-field-value-text">Business visit and meetings with partners in the USA.</div>
                                                        </div>
                                                        <div class="info-field-item col-md-6 mb-3">
                                                            <div class="info-field-label-text">Subclass</div>
                                                            <div class="info-field-value-text">San Francisco, Los Angeles, and New York City</div>
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
                                                            <div class="info-field-value-text">15 March 2026</div>
                                                        </div>
                                                        <div class="info-field-item col-md-3 mb-3">
                                                            <div class="info-field-label-text">Advance Fees Due Date</div>
                                                            <div class="info-field-value-text">AI 173</div>
                                                        </div>
                                                        <div class="info-field-item col-md-3 mb-3">
                                                            <div class="info-field-label-text">Remaining Fees</div>
                                                            <div class="info-field-value-text">San Francisco</div>
                                                        </div>
                                                        <div class="info-field-item col-md-3 mb-3">
                                                            <div class="info-field-label-text">Remaining Fees Due Date</div>
                                                            <div class="info-field-value-text">30 March 2026</div>
                                                        </div>
                                                    </div>
                                                    <div class="info-grid-row row mt-3">
                                                        <div class="info-field-item col-md-3 mb-3">
                                                            <div class="info-field-label-text">Agent Fees</div>
                                                            <div class="info-field-value-text">15 March 2026</div>
                                                        </div>
                                                        <div class="info-field-item col-md-3 mb-3">
                                                            <div class="info-field-label-text">Submission Fees</div>
                                                            <div class="info-field-value-text">AI 173</div>
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
                                                            <div class="info-field-value-text">Pending</div>
                                                        </div>
                                                        <div class="info-field-item col-md-3 mb-3">
                                                            <div class="info-field-label-text">Processing Time</div>
                                                            <div class="info-field-value-text">AI 173</div>
                                                        </div>
                                                        <div class="info-field-item col-md-3 mb-3">
                                                            <div class="info-field-label-text">Bank Cheque Document Handover Date</div>
                                                            <div class="info-field-value-text">San Francisco</div>
                                                        </div>
                                                        <div class="info-field-item col-md-3 mb-3">
                                                            <div class="info-field-label-text">Passport Handover Date</div>
                                                            <div class="info-field-value-text">30 March 2026</div>
                                                        </div>
                                                    </div>
                                                    <div class="info-grid-row row mt-3">
                                                        <div class="info-field-item col-md-6 mb-3">
                                                            <div class="info-field-label-text">Note related to agent or process</div>
                                                            <div class="info-field-value-text">15 March 2026</div>
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
                                                        <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                                                            <div class="d-flex align-items-center">
                                                                <div class="upload-document-icon mr-3">
                                                                    <i class="fa fa-file-pdf text-danger"></i>
                                                                </div>
                                                                <span class="upload-document-name f-14 font-weight-500">Contract Letter</span>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-eye document-view-icon mr-3"></i>
                                                                <button class="btn btn-sm btn-link text-danger p-0 upload-document-delete" title="Delete">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                                                            <div class="d-flex align-items-center">
                                                                <div class="upload-document-icon mr-3">
                                                                    <i class="fa fa-file-word text-primary"></i>
                                                                </div>
                                                                <span class="upload-document-name f-14 font-weight-400">Grant Letter</span>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-eye document-view-icon mr-3"></i>
                                                                <button class="btn btn-sm btn-link text-danger p-0 upload-document-delete" title="Delete">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                                                            <div class="d-flex align-items-center">
                                                                <div class="upload-document-icon mr-3">
                                                                    <i class="fa fa-file-word text-primary"></i>
                                                                </div>
                                                                <span class="upload-document-name f-14 font-weight-400">Offer Letter/Sponsor Letter</span>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-eye document-view-icon mr-3"></i>
                                                                <button class="btn btn-sm btn-link text-danger p-0 upload-document-delete" title="Delete">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                                                            <div class="d-flex align-items-center">
                                                                <div class="upload-document-icon mr-3">
                                                                    <i class="fa fa-file-word text-primary"></i>
                                                                </div>
                                                                <span class="upload-document-name f-14 font-weight-400">Medical Letter</span>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-eye document-view-icon mr-3"></i>
                                                                <button class="btn btn-sm btn-link text-danger p-0 upload-document-delete" title="Delete">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                                                            <div class="d-flex align-items-center">
                                                                <div class="upload-document-icon mr-3">
                                                                    <i class="fa fa-file-word text-primary"></i>
                                                                </div>
                                                                <span class="upload-document-name f-14 font-weight-400">Air Ticket</span>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-eye document-view-icon mr-3"></i>
                                                                <button class="btn btn-sm btn-link text-danger p-0 upload-document-delete" title="Delete">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="upload-document-item d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                                                            <div class="d-flex align-items-center">
                                                                <div class="upload-document-icon mr-3">
                                                                    <i class="fa fa-file-word text-primary"></i>
                                                                </div>
                                                                <span class="upload-document-name f-14 font-weight-400">Accommodation Configuration Letter</span>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-eye document-view-icon mr-3"></i>
                                                                <button class="btn btn-sm btn-link text-danger p-0 upload-document-delete" title="Delete">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                    </div>
                    <!-- Save Button -->
                    <div class="mt-4">
                        <button type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
