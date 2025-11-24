@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/lead-details.css') }}">
@endpush

@section('content')
    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        <div class="lead-details-container">
            <!-- Top Header Bar -->
            <div class="lead-header-bar bg-white p-3 border-bottom-grey">
                <!-- Left Section: Avatar + Priority + Lead ID -->
                <div class="lead-header-left-group d-flex align-items-center">
                    <div class="lead-avatar-section d-flex align-items-center">
                        <div class="lead-avatar-circle text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px;">
                            <img src="{{ asset('img/icon/user.svg') }}">
                        </div>
                        <div class="lead-info-group">
                            <div class="lead-priority d-flex align-items-center mb-1">
                                <img src="{{ asset('img/icon/1st_Priority.svg') }}">
                                <span class="f-12 pl-1">1st Priority</span>
                            </div>
                            <div class="lead-id-header f-14 font-weight-bold">LEAD-0008</div>
                        </div>
                    </div>
                </div>

                <!-- Middle-Left Section: Contact Info -->
                <div class="lead-contact-info d-flex align-items-center">
                    <div class="contact-info-item mr-4">
                        <a href="tel:+91123-456-7890" class="text-dark">
                            <img src="{{ asset('img/icon/Phone.svg') }}">
                            <span class="pl-1">+91 123 4567 890</span>
                        </a>
                    </div>
                    <div class="contact-info-item">
                        <a href="mailto:abc@gmail.com?subject=SUBJECT&body=Demo email" target="_blank" class="text-dark">
                            <img src="{{ asset('img/icon/Mail.svg') }}">
                            <span class="pl-1">abc@gmail.com</span>
                        </a>
                    </div>
                </div>

                <!-- View Resume Button -->
                <button class="btn btn-success btn-sm">View Resume</button>

                <!-- Service Name and Action Icons -->
                <div class="lead-header-right-group d-flex align-items-center">
                    <div class="lead-service-actions-group d-flex align-items-center">
                        <div class="lead-service-section mr-3">
                            <div class="lead-service-name f-14 font-weight-bold">PR - Employer Nomination Scheme (ENS)(Subclass 186)</div>
                        </div>
                        <div class="lead-header-actions d-flex align-items-center">
                            <a class="Whatsapp mr-2" href="#">
                                <img src="{{ asset('img/icon/Whatsapp_icon.svg') }}">
                            </a>
                            <a class="Email mr-2" href="#">
                                <img src="{{ asset('img/icon/Mail_1.svg') }}">
                            </a>
                            <a class="back-arrow" href="{{ route('lead-list.index') }}">
                                <img src="{{ asset('img/icon/Back_Arrow.svg') }}">
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Tabs Bar -->
                        <!-- Tabs Navigation -->
            <div class="s-b-n-header bg-white" id="tabs">
                <nav class="tabs px-4 border-bottom-grey">
                    <div class="nav" id="nav-tab" role="tablist">
                        <a class="nav-item-lead nav-link-lead f-14 active" data-tab="clientInfoTab" href="#">
                            <div class="tab-item"><img src="{{ asset('img/icon/Client_Info.svg') }}"></div>Client Info
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" data-tab="processTab" href="#">
                            <div class="tab-item"><img src="{{ asset('img/icon/Process.svg') }}"></div>Process
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" data-tab="fileNotesTab" href="#">
                            <div class="tab-item"><img src="{{ asset('img/icon/File_Notes.svg') }}"></div>File Notes
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" data-tab="documentsTab" href="#">
                            <div class="tab-item"><img src="{{ asset('img/icon/Documents.svg') }}"></div>Documents
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" data-tab="accountsTab" href="#">
                            <div class="tab-item"><img src="{{ asset('img/icon/Accounts.svg') }}"></div>Accounts
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" data-tab="communicationTab" href="#">
                            <div class="tab-item"><img src="{{ asset('img/icon/Communication.svg') }}"></div>Template Document
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" data-tab="followUpTab" href="#">
                            <div class="tab-item"><img src="{{ asset('img/icon/Follow_Up.svg') }}"></div>Follow Up
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" data-tab="travelDetailsTab" href="#">
                            <div class="tab-item"><img src="{{ asset('img/icon/Travel_Details.svg') }}"></div>Travel Details
                        </a>
                    </div>
                </nav>
            </div>

            <!-- Main Content Area -->
            <div class="lead-content-area bg-white p-20" id="mainContentArea">
                <!-- Client Info Tab Content -->
                <div class="tab-content px-4 pb-4 active" id="clientInfoTab">
                    <!-- Tab Header -->
                    <div class="tab-section-header">
                        <div class="tab-section-header-content">
                            <h3 class="tab-section-title">Client Info</h3>
                            <button type="button" class="btn-primary rounded" id="downloadClientInfoBtn">
                                Download Client Info
                            </button>
                        </div>
                    </div>
                    <!-- Tab Content Area -->
                    <div class="tab-section-content">
                        
                        <!-- Candidate Information & Contact Details Card -->
                        <div style="background-color: #F8F6FB; border: 1px solid #B5B5B5; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                            <!-- Candidate Information Section -->
                            <div class="info-section mb-1">
                                <div class="info-section-header mb-3">
                                    <h4 class="info-section-title-text">Candidate Information</h4>
                                    <div class="info-section-divider"></div>
                                </div>
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Surname</div>
                                        <div class="info-field-value-text">Patel</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Given Name</div>
                                        <div class="info-field-value-text">Rajesh Kumar</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Date of Birth</div>
                                        <div class="info-field-value-text">15-Mar-1992</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Gender</div>
                                        <div class="info-field-value-text">Male</div>
                                    </div>
                                </div>
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Marital Status</div>
                                        <div class="info-field-value-text">Unmarried</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Visa Expiry Date</div>
                                        <div class="info-field-value-text">-</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Passport Number</div>
                                        <div class="info-field-value-text">-</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Details Section -->
                            <div class="info-section mb-1">
                                <div class="info-section-header mb-3">
                                    <h4 class="info-section-title-text">Contact Details</h4>
                                    <div class="info-section-divider"></div>
                                </div>
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Primary Phone</div>
                                        <div class="info-field-value-text">+91 98765 43210</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Secondary Phone</div>
                                        <div class="info-field-value-text">+91 98765 43211</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Work Phone</div>
                                        <div class="info-field-value-text">+91 98765 43212</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Other Phone Number Used in Last Five Years</div>
                                        <div class="info-field-value-text">+91 98765 43212</div>
                                    </div>
                                </div>
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Email</div>
                                        <div class="info-field-value-text">rajesh.patel@example.com</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Other Email Used in Last Five Years</div>
                                        <div class="info-field-value-text">rajesh.patel@example.com</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Home Address</div>
                                        <div class="info-field-value-text">27 Greenfield Avenue, Maplewood Heights, New Delhi, 110019, India</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Mailing Address</div>
                                        <div class="info-field-value-text">B-204, Navkar Residency, Vesu, Surat, Gujarat, 395007, India</div>
                                    </div>
                                </div>
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Linkedin Link</div>
                                        <div class="info-field-value-text">-</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Facebook Link</div>
                                        <div class="info-field-value-text">-</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Instagram Link</div>
                                        <div class="info-field-value-text">-</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Sub Agent</div>
                                        <div class="info-field-value-text">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Details Section -->
                        <div class="info-section mb-1">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Personal Details</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Lead Source</div>
                                    <div class="info-field-value-text">Facebook</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Lead Added by</div>
                                    <div class="info-field-value-text">Vishal Gami</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Lead Assign to</div>
                                    <div class="info-field-value-text">Nishant Bhuva</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Country Of Origin (Nationality)</div>
                                    <div class="info-field-value-text">India</div>
                                </div>
                            </div>
                        </div>

                        <!-- Last Five Years Visa Status Section -->
                        <div class="info-section mb-1">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Last Five Years Visa Status</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Visa Status</div>
                                    <div class="info-field-value-text">Visa Refusal</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Visa Rejection Date</div>
                                    <div class="info-field-value-text">26/10/2025</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Visa Category</div>
                                    <div class="info-field-value-text">Subclass 600</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Reason</div>
                                    <div class="info-field-value-text">-</div>
                                </div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Languages Spoken</div>
                                    <div class="info-field-value-text">Visa Refusal</div>
                                </div>
                            </div>
                        </div>

                        <!-- Client Preference Section -->
                        <div class="info-section mb-1">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Client Preference</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Preferred Designation</div>
                                    <div class="info-field-value-text">UI/UX Designer</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Industry</div>
                                    <div class="info-field-value-text">Information Technology / Software</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Role</div>
                                    <div class="info-field-value-text">On Role</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Preferred Country</div>
                                    <div class="info-field-value-text">Australia</div>
                                </div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Work Category</div>
                                    <div class="info-field-value-text">Skilid</div>
                                </div>
                                <div class="info-field-item col-md-9 mb-3">
                                    <div class="info-field-label-text">Subclass</div>
                                    <div class="info-field-value-text">Work Visa - Temporary Skill Shortage Visa (Subclass 482)</div>
                                </div>
                            </div>
                        </div>

                        <!-- Passport Details Section -->
                        <div class="info-section mb-1">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Passport Details</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Passport Number</div>
                                    <div class="info-field-value-text">Z4589217</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Issuing Country</div>
                                    <div class="info-field-value-text">India</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">City Where Issued</div>
                                    <div class="info-field-value-text">Ahmedabad</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Issuance Date</div>
                                    <div class="info-field-value-text">14-Mar-2019</div>
                                </div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Expiration Date</div>
                                    <div class="info-field-value-text">13-Mar-2029</div>
                                </div>
                                <div class="info-field-item col-md-9 mb-3">
                                    <div class="info-field-label-text">Lost Passport History</div>
                                    <div class="info-field-value-text">No, I have never lost a passport.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Relative Contact Information Section -->
                        <div class="info-section mb-1">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Relative Contact Information</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Relative Contact 1</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Surname</div>
                                    <div class="info-field-value-text">Shah</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Given Name</div>
                                    <div class="info-field-value-text">Karan</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Organization Name</div>
                                    <div class="info-field-value-text">TechNova Solutions Pvt. Ltd.</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Relationship To You</div>
                                    <div class="info-field-value-text">Former Manager</div>
                                </div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Contact Address</div>
                                    <div class="info-field-value-text">27 Greenfield Avenue, Maplewood Heights, New Delhi, 110019, India</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Email</div>
                                    <div class="info-field-value-text">karan.shah@technova.com</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Phone Number</div>
                                    <div class="info-field-value-text">+91 98254 12345</div>
                                </div>
                            </div>
                        </div>

                        <!-- Family Information Section -->
                        <div class="info-section mb-1">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Family Information</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            
                            <!-- Father Details -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Father Details</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Father's Surname</div>
                                    <div class="info-field-value-text">Patel</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Father's Given Name</div>
                                    <div class="info-field-value-text">Ramesh Kumar</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Father's Date of Birth</div>
                                    <div class="info-field-value-text">12-Aug-1965</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Father's Occupation</div>
                                    <div class="info-field-value-text">Business Owner</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Have Passport</div>
                                    <div class="info-field-value-text">Yes</div>
                                </div>
                            </div>

                            <!-- Mother Details -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Mother's Details</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Mother's Surname</div>
                                    <div class="info-field-value-text">Patel</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Mother's Given Name</div>
                                    <div class="info-field-value-text">Meena Ramesh</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Mother's Date of Birth</div>
                                    <div class="info-field-value-text">25-Jan-1968</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Mother's Occupation</div>
                                    <div class="info-field-value-text">Homemaker</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Have Passport</div>
                                    <div class="info-field-value-text">No</div>
                                </div>
                            </div>

                            <!-- Spouse Details -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Spouse Details</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's Surname</div>
                                    <div class="info-field-value-text">Patel</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's Given Name</div>
                                    <div class="info-field-value-text">Neha</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's Date of Birth</div>
                                    <div class="info-field-value-text">04-May-1995</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's Country</div>
                                    <div class="info-field-value-text">India</div>
                                </div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's City of Birth</div>
                                    <div class="info-field-value-text">Surat</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Have Passport</div>
                                    <div class="info-field-value-text">Yes</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's Address</div>
                                    <div class="info-field-value-text">B-204, Navkar Residency, Vesu, Surat</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's Phone Number</div>
                                    <div class="info-field-value-text">+91 98765 44221</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's Education</div>
                                    <div class="info-field-value-text">Master's in Computer Applications (MCA)</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's Occupation</div>
                                    <div class="info-field-value-text">Software Engineer</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's Yearly Income</div>
                                    <div class="info-field-value-text">9,50,000</div>
                                </div>
                            </div>

                            <!-- Child 1 Details -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Child 1</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Child's Name</div>
                                    <div class="info-field-value-text">Aarav Patel</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Child's Age</div>
                                    <div class="info-field-value-text">4</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Date of Birth</div>
                                    <div class="info-field-value-text">17-Jun-2021</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">City of Birth</div>
                                    <div class="info-field-value-text">Ahmedabad</div>
                                </div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Have Passport</div>
                                    <div class="info-field-value-text">Yes</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Gender</div>
                                    <div class="info-field-value-text">Male</div>
                                </div>
                            </div>
                        </div>

                        <!-- Education Information Section -->
                        <div class="info-section mb-1">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Education Information</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            
                            <!-- IELTS/PTE/OET/TOEFL Exam Details -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">IELTS/PTE/OET/TOEFL Exam Details</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Exam Type</div>
                                    <div class="info-field-value-text">IELTS</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Passing Year</div>
                                    <div class="info-field-value-text">2022</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Score</div>
                                    <div class="info-field-value-text">7.5</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Trial</div>
                                    <div class="info-field-value-text">2</div>
                                </div>
                            </div>

                            <!-- 10th Exam Details -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">10th Exam Details</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Passing Year</div>
                                    <div class="info-field-value-text">2014</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Percentage</div>
                                    <div class="info-field-value-text">85.5%</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Board Name</div>
                                    <div class="info-field-value-text">Gujarat Secondary Education Board</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Trial</div>
                                    <div class="info-field-value-text">1</div>
                                </div>
                            </div>

                            <!-- 12th Exam Details -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">12th Exam Details</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Passing Year</div>
                                    <div class="info-field-value-text">2016</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Stream</div>
                                    <div class="info-field-value-text">Science</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Percentage</div>
                                    <div class="info-field-value-text">82.3%</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Board Name</div>
                                    <div class="info-field-value-text">Gujarat Higher Secondary Education Board</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Trial</div>
                                    <div class="info-field-value-text">1</div>
                                </div>
                            </div>

                            <!-- Graduation Degree Details -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Graduation Degree Details</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Degree</div>
                                    <div class="info-field-value-text">Bachelor of Technology / Engineering (B.Tech / B.E.)</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">University Name</div>
                                    <div class="info-field-value-text">Gujarat Technological University</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Percentage</div>
                                    <div class="info-field-value-text">78.5%</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Passing Year</div>
                                    <div class="info-field-value-text">2020</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Trial</div>
                                    <div class="info-field-value-text">1</div>
                                </div>
                            </div>

                            <!-- Post Graduation Degree Details -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Post Graduation Degree Details</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Degree</div>
                                    <div class="info-field-value-text">Master of Business Administration (MBA)</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">University Name</div>
                                    <div class="info-field-value-text">Indian Institute of Management</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Percentage</div>
                                    <div class="info-field-value-text">85.2%</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Passing Year</div>
                                    <div class="info-field-value-text">2022</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Trial</div>
                                    <div class="info-field-value-text">1</div>
                                </div>
                            </div>

                            <!-- Other Degree Details -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Other Degree 1</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Degree</div>
                                    <div class="info-field-value-text">Diploma in Digital Marketing</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Institution Name</div>
                                    <div class="info-field-value-text">Digital Marketing Institute</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Percentage</div>
                                    <div class="info-field-value-text">90.0%</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Passing Year</div>
                                    <div class="info-field-value-text">2023</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Trial</div>
                                    <div class="info-field-value-text">1</div>
                                </div>
                            </div>
                        </div>

                        <!-- Professional Experience Section -->
                        <div class="info-section mb-1">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Professional Experience</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            
                            <!-- Experience 1 -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Experience 1</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Duration - From</div>
                                    <div class="info-field-value-text">01-Jan-2020</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Duration - To</div>
                                    <div class="info-field-value-text">Present</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Country</div>
                                    <div class="info-field-value-text">India</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Designation</div>
                                    <div class="info-field-value-text">Senior UI/UX Designer</div>
                                </div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-6 mb-3">
                                    <div class="info-field-label-text">Company Name</div>
                                    <div class="info-field-value-text">TechNova Solutions Pvt. Ltd.</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Salary</div>
                                    <div class="info-field-value-text">₹ 8,50,000</div>
                                </div>
                            </div>

                            <!-- Experience 2 -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Experience 2</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Duration - From</div>
                                    <div class="info-field-value-text">15-Jun-2018</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Duration - To</div>
                                    <div class="info-field-value-text">31-Dec-2019</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Country</div>
                                    <div class="info-field-value-text">India</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Designation</div>
                                    <div class="info-field-value-text">UI/UX Designer</div>
                                </div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Company Name</div>
                                    <div class="info-field-value-text">Digital Innovations Inc.</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Salary</div>
                                    <div class="info-field-value-text">₹ 6,00,000</div>
                                </div>
                            </div>
                        </div>

                        <!-- Property Details Section -->
                        <div class="info-section mb-1">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Property Details</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            
                            <!-- Property Valuation -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Property Valuation</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Home</div>
                                    <div class="info-field-value-text">₹ 50,00,000</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Land</div>
                                    <div class="info-field-value-text">₹ 30,00,000</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Plot</div>
                                    <div class="info-field-value-text">₹ 20,00,000</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Commercials</div>
                                    <div class="info-field-value-text">₹ 1,20,00,000</div>
                                </div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Other</div>
                                    <div class="info-field-value-text">₹ 5,00,000</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Shop</div>
                                    <div class="info-field-value-text">₹ 15,00,000</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Gold</div>
                                    <div class="info-field-value-text">₹ 8,00,000</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Silver</div>
                                    <div class="info-field-value-text">₹ 2,00,000</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Total Asset Valuation</div>
                                    <div class="info-field-value-text">₹ 2,50,00,000</div>
                                </div>
                            </div>

                            <!-- Loan Information -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Loan Information</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Total Loan Value</div>
                                    <div class="info-field-value-text">₹ 30,00,000</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Loan Years</div>
                                    <div class="info-field-value-text">15</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Loan Availed On</div>
                                    <div class="info-field-value-text">15-Mar-2019</div>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Status Section -->
                        <div class="info-section">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Financial Status</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            
                            <!-- Income Details -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Income Details</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Father's Income</div>
                                    <div class="info-field-value-text">₹ 8,00,000</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Mother's Income</div>
                                    <div class="info-field-value-text">₹ 0</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Candidate's Income</div>
                                    <div class="info-field-value-text">₹ 12,00,000</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse Income</div>
                                    <div class="info-field-value-text">₹ 9,50,000</div>
                                </div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Total Income</div>
                                    <div class="info-field-value-text">₹ 29,50,000</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Process Tab Content -->
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
                <!-- File Notes Tab Content -->
                <div class="tab-content px-4 pb-4" id="fileNotesTab">
                    <!-- Tab Header -->
                    <div class="tab-section-header">
                        <div class="tab-section-header-content">
                            <h3 class="tab-section-title">File Notes</h3>
                            <button type="button" class="tab-section-add-btn" data-toggle="modal" data-target="#addFileNoteModal">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Tab Content Area -->
                    <div class="tab-section-content">
                        <div class="file-notes-list">
                            <div class="file-note-item">
                                <div class="file-note-timeline">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-line"></div>
                                </div>
                                <div class="file-note-box">
                                    <div class="file-note-text">Need student visa with admission service for Australia</div>
                                    <div class="file-note-meta">
                                        <span class="file-note-bullet">•</span> Created by: Samuel Parker - 17-07-2025 2:00 PM
                                    </div>
                                </div>
                            </div>
                            <div class="file-note-item">
                                <div class="file-note-timeline">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-line"></div>
                                </div>
                                <div class="file-note-box">
                                    <div class="file-note-text">Need student visa with admission service for Australia</div>
                                    <div class="file-note-meta">
                                        <span class="file-note-bullet">•</span> Created by: Samuel Parker - 17-07-2025 2:00 PM
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Documents Tab Content -->
                <div class="tab-content px-4 pb-4" id="documentsTab">
                    <!-- Tab Header -->
                    <div class="tab-section-header">
                        <div class="tab-section-header-content">
                            <h3 class="tab-section-title">Document Checklist - <span class="info-field-value">SIDDHARTH PATEL (MAIN APPLICANT)</span></h3>
                        </div>
                    </div>
                    <!-- Tab Content Area -->
                    <div class="tab-section-content">
                        <div class="table-responsive">
                            <table class="table document-checklist-table">
                                <thead>
                                    <tr>
                                        <th>DOCUMENT TYPE/NAME</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Assessment Letter <span class="text-danger">*</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-success document-upload-btn">Upload</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Passport - Applicant <span class="text-danger">*</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-success document-upload-btn">Upload</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Father Passport <span class="text-danger">*</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-success document-upload-btn">Upload</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Mother Passport <span class="text-danger">*</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-success document-upload-btn">Upload</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Spouse Passport <span class="text-danger">*</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-success document-upload-btn">Upload</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Spouse Document</td>
                                        <td>
                                            <button class="btn btn-sm btn-success document-upload-btn">Upload</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Child Document</td>
                                        <td>
                                            <div class="document-action-group">
                                                <i class="fas fa-eye document-view-icon"></i>
                                                <a href="#" class="document-change-link">Change</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Child Passport <span class="text-danger">*</span></td>
                                        <td>
                                            <div class="document-action-group">
                                                <i class="fas fa-eye document-view-icon"></i>
                                                <a href="#" class="document-change-link">Change</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>IELTS / PTE / OET / TOEFL - Result</td>
                                        <td>
                                            <div class="document-action-group">
                                                <i class="fas fa-eye document-view-icon"></i>
                                                <a href="#" class="document-change-link">Change</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>10th Passing Result <span class="text-danger">*</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-success document-upload-btn">Upload</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>12th Passing Result</td>
                                        <td>
                                            <button class="btn btn-sm btn-success document-upload-btn">Upload</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Graduation Degree</td>
                                        <td>
                                            <div class="document-action-group">
                                                <i class="fas fa-eye document-view-icon"></i>
                                                <a href="#" class="document-change-link">Change</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Post Graduation Degree</td>
                                        <td>
                                            <button class="btn btn-sm btn-success document-upload-btn">Upload</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Other Degree</td>
                                        <td>
                                            <div class="document-action-group">
                                                <i class="fas fa-eye document-view-icon"></i>
                                                <a href="#" class="document-change-link">Change</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Offerletter</td>
                                        <td>
                                            <div class="document-action-group">
                                                <i class="fas fa-eye document-view-icon"></i>
                                                <a href="#" class="document-change-link">Change</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Experience letter</td>
                                        <td>
                                            <div class="document-action-group">
                                                <i class="fas fa-eye document-view-icon"></i>
                                                <a href="#" class="document-change-link">Change</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Valuation Report</td>
                                        <td>
                                            <div class="document-action-group">
                                                <i class="fas fa-eye document-view-icon"></i>
                                                <a href="#" class="document-change-link">Change</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Father Income Document</td>
                                        <td>
                                            <div class="document-action-group">
                                                <i class="fas fa-eye document-view-icon"></i>
                                                <a href="#" class="document-change-link">Change</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Mother Income Document</td>
                                        <td>
                                            <div class="document-action-group">
                                                <i class="fas fa-eye document-view-icon"></i>
                                                <a href="#" class="document-change-link">Change</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Candidate Income Document</td>
                                        <td>
                                            <div class="document-action-group">
                                                <i class="fas fa-eye document-view-icon"></i>
                                                <a href="#" class="document-change-link">Change</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Spouse Income Document</td>
                                        <td>
                                            <div class="document-action-group">
                                                <i class="fas fa-eye document-view-icon"></i>
                                                <a href="#" class="document-change-link">Change</a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Accounts Tab Content -->
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
                <!-- Communication Tab Content -->
                <div class="tab-content px-4 pb-4" id="communicationTab">
                    <!-- Tab Header -->
                    <div class="tab-section-header">
                        <div class="tab-section-header-content">
                            <h3 class="tab-section-title">Template Document</h3>
                        </div>
                    </div>
                    <!-- Tab Content Area -->
                    <div class="tab-section-content">

                        <!-- Available Documents Section -->
                        <div class="available-documents-section">
                            <div class="info-section-title mb-3">
                                <h4 class="f-16 font-weight-bold">Available Documents</h4>
                            </div>
                            <div class="documents-list">
                                <div class="document-card mb-3 p-3 bg-light rounded">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <div class="document-icon mr-3">
                                                <i class="fa fa-file-pdf text-danger"></i>
                                            </div>
                                            <div class="document-details flex-grow-1">
                                                <div class="document-name f-14 font-weight-bold mb-1">Passport - Applicant</div>
                                                <div class="document-meta f-12 text-dark-grey">
                                                    <span class="mr-3">Size: 2.5 MB</span>
                                                    <span>Uploaded: 09-09-2025 11:46 AM</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="document-actions d-flex align-items-center">
                                            <button class="btn btn-sm btn-info mr-2 document-view-btn" title="View Document">
                                                <i class="fa fa-eye mr-1"></i> View
                                            </button>
                                            <button class="btn btn-sm document-email-btn mr-2" title="Send via Email">
                                                <i class="fa fa-envelope mr-1"></i> Email
                                            </button>
                                            <button class="btn btn-sm document-whatsapp-btn" title="Send via WhatsApp">
                                                <i class="fa fa-whatsapp mr-1"></i> WhatsApp
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="document-card mb-3 p-3 bg-light rounded">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <div class="document-icon mr-3">
                                                <i class="fa fa-file-word text-primary"></i>
                                            </div>
                                            <div class="document-details flex-grow-1">
                                                <div class="document-name f-14 font-weight-bold mb-1">Assessment Letter</div>
                                                <div class="document-meta f-12 text-dark-grey">
                                                    <span class="mr-3">Size: 1.8 MB</span>
                                                    <span>Uploaded: 08-09-2025 10:30 AM</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="document-actions d-flex align-items-center">
                                            <button class="btn btn-sm btn-info mr-2 document-view-btn" title="View Document">
                                                <i class="fa fa-eye mr-1"></i> View
                                            </button>
                                            <button class="btn btn-sm document-email-btn mr-2" title="Send via Email">
                                                <i class="fa fa-envelope mr-1"></i> Email
                                            </button>
                                            <button class="btn btn-sm document-whatsapp-btn" title="Send via WhatsApp">
                                                <i class="fa fa-whatsapp mr-1"></i> WhatsApp
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="document-card mb-3 p-3 bg-light rounded">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <div class="document-icon mr-3">
                                                <i class="fa fa-file-image text-success"></i>
                                            </div>
                                            <div class="document-details flex-grow-1">
                                                <div class="document-name f-14 font-weight-bold mb-1">Medical Certificate</div>
                                                <div class="document-meta f-12 text-dark-grey">
                                                    <span class="mr-3">Size: 3.2 MB</span>
                                                    <span>Uploaded: 07-09-2025 03:15 PM</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="document-actions d-flex align-items-center">
                                            <button class="btn btn-sm btn-info mr-2 document-view-btn" title="View Document">
                                                <i class="fa fa-eye mr-1"></i> View
                                            </button>
                                            <button class="btn btn-sm document-email-btn mr-2" title="Send via Email">
                                                <i class="fa fa-envelope mr-1"></i> Email
                                            </button>
                                            <button class="btn btn-sm document-whatsapp-btn" title="Send via WhatsApp">
                                                <i class="fa fa-whatsapp mr-1"></i> WhatsApp
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="document-card mb-3 p-3 bg-light rounded">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <div class="document-icon mr-3">
                                                <i class="fa fa-file-pdf text-danger"></i>
                                            </div>
                                            <div class="document-details flex-grow-1">
                                                <div class="document-name f-14 font-weight-bold mb-1">Contract Letter</div>
                                                <div class="document-meta f-12 text-dark-grey">
                                                    <span class="mr-3">Size: 1.5 MB</span>
                                                    <span>Uploaded: 05-09-2025 09:20 AM</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="document-actions d-flex align-items-center">
                                            <button class="btn btn-sm btn-info mr-2 document-view-btn" title="View Document">
                                                <i class="fa fa-eye mr-1"></i> View
                                            </button>
                                            <button class="btn btn-sm document-email-btn mr-2" title="Send via Email">
                                                <i class="fa fa-envelope mr-1"></i> Email
                                            </button>
                                            <button class="btn btn-sm document-whatsapp-btn" title="Send via WhatsApp">
                                                <i class="fa fa-whatsapp mr-1"></i> WhatsApp
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Follow Up Tab Content -->
                <div class="tab-content px-4 pb-4" id="followUpTab">
                    <!-- Tab Header -->
                    <div class="tab-section-header">
                        <div class="tab-section-header-content">
                            <div>
                                <h3 class="tab-section-title">FOLLOW UP</h3>
                                <div class="tab-section-subtitle-text">Next Follow-up: 09/09/2025 12:00 PM</div>
                            </div>
                            <button type="button" class="tab-section-add-btn" data-toggle="modal" data-target="#addFollowUpModal">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Tab Content Area -->
                    <div class="tab-section-content">
                        <div class="text-center p-5">
                            <p class="text-muted">No data found</p>
                        </div>
                        <!-- Follow Up List -->
                        <div class="follow-up-list">
                            <!-- Follow Up Card 1 -->
                            <div class="follow-up-card">
                                <div class="follow-up-card-content">
                                    <img src="{{ asset('img/icon/follow_up_list.svg') }}">
                                    <div class="follow-up-details">
                                        <div class="follow-up-subject">
                                            <span class="follow-up-label">Subject:</span> Introductory meeting with the customer
                                        </div>
                                        <div class="follow-up-outcome">
                                            <span class="follow-up-label">Outcome:</span> Interested
                                        </div>
                                        <div class="follow-up-description">
                                            The customer has shown interest in the Australian admission service
                                        </div>
                                        <div class="follow-up-meta">
                                            Created by : Shivani Patel - 09-09-2025 11:46 AM
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Follow Up Card 2 -->
                            <div class="follow-up-card">
                                <div class="follow-up-card-content">
                                    <img src="{{ asset('img/icon/follow_up_list.svg') }}">
                                    <div class="follow-up-details">
                                        <div class="follow-up-subject">
                                            <span class="follow-up-label">Subject:</span> Introductory meeting with the customer
                                        </div>
                                        <div class="follow-up-outcome">
                                            <span class="follow-up-label">Outcome:</span> Interested
                                        </div>
                                        <div class="follow-up-description">
                                            The customer has shown interest in the Australian admission service
                                        </div>
                                        <div class="follow-up-meta">
                                            Created by : Shivani Patel - 09-09-2025 11:46 AM
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Follow Up Card 3 -->
                            <div class="follow-up-card">
                                <div class="follow-up-card-content">
                                    <img src="{{ asset('img/icon/follow_up_list.svg') }}">
                                    <div class="follow-up-details">
                                        <div class="follow-up-subject">
                                            <span class="follow-up-label">Subject:</span> Introductory meeting with the customer
                                        </div>
                                        <div class="follow-up-outcome">
                                            <span class="follow-up-label">Outcome:</span> Interested
                                        </div>
                                        <div class="follow-up-description">
                                            The customer has shown interest in the Australian admission service
                                        </div>
                                        <div class="follow-up-meta">
                                            Created by : Shivani Patel - 09-09-2025 11:46 AM
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Travel Details Tab Content -->
                <div class="tab-content px-4 pb-4" id="travelDetailsTab">
                    <!-- Tab Header -->
                    <div class="tab-section-header">
                        <div class="tab-section-header-content">
                            <h3 class="tab-section-title">Travel Details</h3>
                            <div class="tab-section-header-actions">
                                <button type="button" class="tab-section-secondary-btn" data-toggle="modal" data-target="#notifyClientModal">Notify Client</button>
                                <button type="button" class="tab-section-add-btn" data-toggle="modal" data-target="#addTravelDetailsModal">
                                    <i class="fa fa-pencil"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Tab Content Area -->
                    <div class="tab-section-content">
                        <div class="text-center p-5">
                            <p class="text-muted mb-3">No data found</p>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTravelDetailsModal">Add Travel Details</button>
                        </div>
                        <!-- Travel Details Section -->
                        <div class="travel-details-section">
                            <div class="travel-section-header">
                                <h4 class="travel-section-title">Travel Details</h4>
                                <div class="travel-section-divider"></div>
                            </div>
                            <div class="travel-details-container">
                                <!-- 6 Column Grid: Purpose of Trip, Place To Visit USA -->
                                <div class="row mb-3">
                                    <div class="col-md-6 col-sm-6 col-12 mb-3 mb-md-0 travel-detail-item">
                                        <div class="travel-detail-label">Purpose of Trip:</div>
                                        <div class="travel-detail-value">Business visit and meetings with partners in the USA.</div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-12 travel-detail-item">
                                        <div class="travel-detail-label">Place To Visit USA</div>
                                        <div class="travel-detail-value">San Francisco, Los Angeles, and New York City</div>
                                    </div>
                                </div>
                                <!-- 4 Column Grid -->
                                <div class="row mb-3">
                                    <div class="col-md-3 col-sm-6 col-12 mb-3 mb-md-0 travel-detail-item">
                                        <div class="travel-detail-label">Date of Arrival</div>
                                        <div class="travel-detail-value">15 March 2026</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12 mb-3 mb-md-0 travel-detail-item">
                                        <div class="travel-detail-label">Arrival Flight</div>
                                        <div class="travel-detail-value">AI 173</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12 mb-3 mb-md-0 travel-detail-item">
                                        <div class="travel-detail-label">Arrival City</div>
                                        <div class="travel-detail-value">San Francisco</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12 mb-3 mb-md-0 travel-detail-item">
                                        <div class="travel-detail-label">Date of Departure From</div>
                                        <div class="travel-detail-value">30 March 2026</div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-sm-6 col-12 mb-3 mb-md-0 travel-detail-item">
                                        <div class="travel-detail-label">Departure Flight</div>
                                        <div class="travel-detail-value">UA 868</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12 mb-3 mb-md-0 travel-detail-item">
                                        <div class="travel-detail-label">Departure City</div>
                                        <div class="travel-detail-value">San Francisco</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12 travel-detail-item">
                                        <div class="travel-detail-label">Phone Number (of other country)</div>
                                        <div class="travel-detail-value">+1 415 623 9874</div>
                                    </div>
                                </div>
                                <!-- 4 Column Grid: Address Where You Will Stay, City, State, Postal/Zip Code -->
                                <div class="row">
                                    <div class="col-md-3 col-sm-6 col-12 mb-3 mb-md-0 travel-detail-item">
                                        <div class="travel-detail-label">Address Where You Will Stay</div>
                                        <div class="travel-detail-value">
                                            123 Mission Street, Suite 400<br>
                                            123 Mission jkmklsd hjourf
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12 mb-3 mb-md-0 travel-detail-item">
                                        <div class="travel-detail-label">City</div>
                                        <div class="travel-detail-value">San Francisco</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12 mb-3 mb-md-0 travel-detail-item">
                                        <div class="travel-detail-label">State</div>
                                        <div class="travel-detail-value">California</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12 travel-detail-item">
                                        <div class="travel-detail-label">Postal/Zip Code</div>
                                        <div class="travel-detail-value">94105</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information Section -->
                        <div class="travel-details-section">
                            <div class="travel-section-header">
                                <h4 class="travel-section-title">Personal Information</h4>
                                <div class="travel-section-divider"></div>
                            </div>
                            <div class="travel-details-container">
                                <!-- Full Width: Person Paying For Your Trip (Details) -->
                                <div class="row mb-3">
                                    <div class="col-12 travel-detail-item">
                                        <div class="travel-detail-label">Person Paying For Your Trip (Details)</div>
                                        <div class="travel-detail-value">Travel expenses sponsored by Cravity Studio Pvt. Ltd., Ahmedabad, India. Travel expenses sponsored by Cravity Studio Pvt. Ltd., Ahmedabad, India. Travel expenses sponsored by Cravity Studio Pvt. Ltd., Ahmedabad, India.</div>
                                    </div>
                                </div>
                                <!-- 4 Column Grid: Is Your Mother, Immediate Relatives, Other Relatives -->
                                <div class="row">
                                    <div class="col-md-3 col-sm-6 col-12 mb-3 mb-md-0 travel-detail-item">
                                        <div class="travel-detail-label">Is Your Mother in that country?</div>
                                        <div class="travel-detail-value">No</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12 mb-3 mb-md-0 travel-detail-item">
                                        <div class="travel-detail-label">Immediate Relatives in that country?</div>
                                        <div class="travel-detail-value">No</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12 travel-detail-item">
                                        <div class="travel-detail-label">Other Relatives in that country?</div>
                                        <div class="travel-detail-value">Yes</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT WRAPPER END -->

    <!-- Add File Note Modal -->
    <div class="modal fade" id="addFileNoteModal" tabindex="-1" role="dialog" aria-labelledby="addFileNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFileNoteModalLabel">Add File Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Note</label>
                        <div id="file-note-editor"></div>
                        <textarea name="note" id="file-note-editor-text" class="d-none"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-file-note-btn">Save</button>
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

    <!-- Add Communication Modal -->
    <div class="modal fade" id="addCommunicationModal" tabindex="-1" role="dialog" aria-labelledby="addCommunicationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCommunicationModalLabel">Add Communication</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <x-forms.label fieldId="assignee" fieldLabel="Select Assignee">
                        </x-forms.label>
                        <select class="form-control select-picker height-35 f-14" required>
                            <option value="" disabled selected>Select Assignee</option>
                            <option value="1">Shivani Patel</option>
                            <option value="2">John Doe</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <x-forms.label fieldId="communication_type" fieldLabel="Type">
                        </x-forms.label>
                        <select class="form-control select-picker height-35 f-14" required>
                            <option value="" disabled selected>Call / Meeting / Email / Whatsapp</option>
                            <option value="call">Call</option>
                            <option value="meeting">Meeting</option>
                            <option value="email">Email</option>
                            <option value="whatsapp">Whatsapp</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <x-forms.label fieldId="communication_date" fieldLabel="Date">
                        </x-forms.label>
                        <input type="date" class="form-control height-35 f-14" placeholder="Date">
                    </div>
                    <div class="form-group">
                        <x-forms.label fieldId="comment" fieldLabel="Comment">
                        </x-forms.label>
                        <textarea class="form-control f-14" rows="4" placeholder="Comment"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Follow-Up Modal -->
    <div class="modal fade" id="addFollowUpModal" tabindex="-1" role="dialog" aria-labelledby="addFollowUpModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFollowUpModalLabel">Add Follow-Up</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="f-14 font-weight-bold mb-2">Follow-Up Type</label>
                        <div class="d-flex gap-2">
                            <label class="form-check-label mr-3">
                                <input type="radio" name="followUpType" value="call" checked class="mr-1"> Call
                            </label>
                            <label class="form-check-label mr-3">
                                <input type="radio" name="followUpType" value="meeting" class="mr-1"> Meeting
                            </label>
                            <label class="form-check-label mr-3">
                                <input type="radio" name="followUpType" value="sms" class="mr-1"> SMS
                            </label>
                            <label class="form-check-label">
                                <input type="radio" name="followUpType" value="email" class="mr-1"> Email
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <x-forms.label fieldId="subject" fieldLabel="Subject">
                        </x-forms.label>
                        <input type="text" class="form-control height-35 f-14" value="">
                    </div>
                    <div class="form-group">
                        <x-forms.label fieldId="outcome" fieldLabel="Outcome of Call">
                        </x-forms.label>
                        <input type="text" class="form-control height-35 f-14" value="">
                    </div>
                    <div class="form-group">
                        <x-forms.label fieldId="notes" fieldLabel="Notes">
                        </x-forms.label>
                        <textarea class="form-control f-14" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="f-14 font-weight-bold mb-2">Do you want to get update for next follow-up - Set reminder?</label>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-forms.label fieldId="next_follow_up_date" fieldLabel="Next Follow Up Date">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" placeholder="">
                            </div>
                            <div class="col-md-6 mb-2">
                                <x-forms.label fieldId="next_follow_up_time" fieldLabel="Next Follow Up Start Time">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14">
                                    <option value="15 Minutes Before" selected>15 Minutes Before</option>
                                    <option value="30 Minutes Before">30 Minutes Before</option>
                                    <option value="1 Hour Before">1 Hour Before</option>
                                    <option value="2 Hours Before">2 Hours Before</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <x-forms.label fieldId="follow_up_subject_line" fieldLabel="Follow Up Subject Line">
                        </x-forms.label>
                        <input type="text" class="form-control height-35 f-14" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notify Client Modal -->
    <div class="modal fade" id="notifyClientModal" tabindex="-1" role="dialog" aria-labelledby="notifyClientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notifyClientModalLabel">Notify Client?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-4">
                        <p class="mb-2">Only "Congratulations! Find your travel details" notification by:</p>
                        <p class="mb-0">For further details please connect with your concealer.</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="f-12 text-dark-grey mb-1">Purpose of Trip</div>
                            <div class="f-14">Business visit and meetings with partners in the USA.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="f-12 text-dark-grey mb-1">Place To Visit USA</div>
                            <div class="f-14">San Francisco, Los Angeles, and New York City</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="f-12 text-dark-grey mb-1">Date of Arrival</div>
                            <div class="f-14">15 March 2026</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="f-12 text-dark-grey mb-1">Arrival Flight</div>
                            <div class="f-14">AI 173</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="f-12 text-dark-grey mb-1">Arrival City</div>
                            <div class="f-14">San Francisco</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="f-12 text-dark-grey mb-1">Date of Departure From</div>
                            <div class="f-14">30 March 2026</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="f-12 text-dark-grey mb-1">Departure Flight</div>
                            <div class="f-14">UA 868</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="f-12 text-dark-grey mb-1">Phone Number (of other country)</div>
                            <div class="f-14">+1 415 623 9874</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="f-12 text-dark-grey mb-1">Address Where You Will Stay</div>
                            <div class="f-14">123 Mission Street, Suite 400</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="f-12 text-dark-grey mb-1">City</div>
                            <div class="f-14">San Francisco</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="f-12 text-dark-grey mb-1">State</div>
                            <div class="f-14">California</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="f-12 text-dark-grey mb-1">Postal/Zip Code</div>
                            <div class="f-14">94105</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Notify Client</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Travel Details Modal -->
    <div class="modal fade" id="addTravelDetailsModal" tabindex="-1" role="dialog" aria-labelledby="addTravelDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: 1px solid #E9E6F5; padding: 16px 24px;">
                    <h5 class="modal-title" id="addTravelDetailsModalLabel" style="font-size: 16px; font-weight: 600; color: #713ED9; margin: 0;">Add Travel Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #F5213D; opacity: 1; font-size: 24px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <div class="form-group mb-3">
                        <x-forms.label fieldId="purpose_of_trip" fieldLabel="Purpose of Trip">
                        </x-forms.label>
                        <input type="text" class="form-control height-35 f-14" id="purpose_of_trip" name="purpose_of_trip" style="border: 1px solid #E9E6F5; border-radius: 4px;">
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <x-forms.label fieldId="date_of_arrival" fieldLabel="Date of Arrival">
                            </x-forms.label>
                            <input type="date" class="form-control height-35 f-14" id="date_of_arrival" name="date_of_arrival" style="border: 1px solid #E9E6F5; border-radius: 4px;">
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <x-forms.label fieldId="arrival_flight" fieldLabel="Arrival Flight">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" id="arrival_flight" name="arrival_flight" style="border: 1px solid #E9E6F5; border-radius: 4px;">
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <x-forms.label fieldId="arrival_city" fieldLabel="Arrival City">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" id="arrival_city" name="arrival_city" style="border: 1px solid #E9E6F5; border-radius: 4px;">
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <x-forms.label fieldId="date_of_departure" fieldLabel="Date of Departure From">
                            </x-forms.label>
                            <input type="date" class="form-control height-35 f-14" id="date_of_departure" name="date_of_departure" style="border: 1px solid #E9E6F5; border-radius: 4px;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <x-forms.label fieldId="departure_flight" fieldLabel="Departure Flight">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" id="departure_flight" name="departure_flight" style="border: 1px solid #E9E6F5; border-radius: 4px;">
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <x-forms.label fieldId="departure_city" fieldLabel="Departure City">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" id="departure_city" name="departure_city" style="border: 1px solid #E9E6F5; border-radius: 4px;">
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <x-forms.label fieldId="phone_number_other_country" fieldLabel="Phone Number (of other country)">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" id="phone_number_other_country" name="phone_number_other_country" style="border: 1px solid #E9E6F5; border-radius: 4px;">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <x-forms.label fieldId="place_to_visit" fieldLabel="Place To Visit USA">
                        </x-forms.label>
                        <textarea class="form-control f-14" id="place_to_visit" name="place_to_visit" rows="3" style="border: 1px solid #E9E6F5; border-radius: 4px;"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <x-forms.label fieldId="address_stay" fieldLabel="Address Where You Will Stay">
                        </x-forms.label>
                        <textarea class="form-control f-14" id="address_stay" name="address_stay" rows="3" style="border: 1px solid #E9E6F5; border-radius: 4px;"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <x-forms.label fieldId="city" fieldLabel="City">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" id="city" name="city" style="border: 1px solid #E9E6F5; border-radius: 4px;">
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <x-forms.label fieldId="state" fieldLabel="State">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" id="state" name="state" style="border: 1px solid #E9E6F5; border-radius: 4px;">
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <x-forms.label fieldId="postal_code" fieldLabel="Postal/Zip Code">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" id="postal_code" name="postal_code" style="border: 1px solid #E9E6F5; border-radius: 4px;">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <x-forms.label fieldId="person_paying" fieldLabel="Person Paying For Your Trip (Details)">
                        </x-forms.label>
                        <textarea class="form-control f-14" id="person_paying" name="person_paying" rows="3" style="border: 1px solid #E9E6F5; border-radius: 4px;"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-6 mb-3">
                            <x-forms.label fieldId="mother_in_country" fieldLabel="Is Your Mother in that country?">
                            </x-forms.label>
                            <select class="form-control select-picker height-35 f-14" id="mother_in_country" name="mother_in_country" style="border: 1px solid #E9E6F5; border-radius: 4px;">
                                <option value="" disabled selected>Select</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <x-forms.label fieldId="immediate_relatives" fieldLabel="Immediate Relatives In that country?">
                            </x-forms.label>
                            <select class="form-control select-picker height-35 f-14" id="immediate_relatives" name="immediate_relatives" style="border: 1px solid #E9E6F5; border-radius: 4px;">
                                <option value="" disabled selected>Select</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <x-forms.label fieldId="other_relatives" fieldLabel="Other Relatives In that country?">
                            </x-forms.label>
                            <select class="form-control select-picker height-35 f-14" id="other_relatives" name="other_relatives" style="border: 1px solid #E9E6F5; border-radius: 4px;">
                                <option value="" disabled selected>Select</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #E9E6F5; padding: 16px 24px; justify-content: flex-end;">
                    <button type="button" class="btn" data-dismiss="modal" style="background-color: #FFE7E7; color: #F5213D; border: none; padding: 8px 24px; font-weight: 500; border-radius: 4px; margin-right: 12px;">Cancel</button>
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize select pickers
            $('.select-picker').selectpicker();
            
            // Tab Switching Functionality
            $('.nav-item-lead').on('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all tabs
                $('.nav-item-lead').removeClass('active');
                // Add active class to clicked tab
                $(this).addClass('active');
                
                // Hide all tab contents
                $('.tab-content').removeClass('active');
                
                // Show selected tab content
                const tabId = $(this).data('tab');
                if (tabId) {
                    $('#' + tabId).addClass('active');
                }
            });

            // Reinitialize select pickers when modals are opened
            $('#addFileNoteModal, #addInvoiceModal, #addCommunicationModal, #addFollowUpModal, #notifyClientModal, #addTravelDetailsModal').on('shown.bs.modal', function () {
                $('.select-picker').selectpicker('refresh');
            });

            // Initialize Quill editor when File Note modal opens
            $('#addFileNoteModal').on('shown.bs.modal', function () {
                // Check if Quill is already initialized for this editor
                if (!quillArray['#file-note-editor']) {
                    quillImageLoad('#file-note-editor');
                }
            });

            // Clear and destroy Quill editor when modal is closed
            $('#addFileNoteModal').on('hidden.bs.modal', function () {
                if (quillArray['#file-note-editor']) {
                    destory_editor('#file-note-editor');
                    delete quillArray['#file-note-editor'];
                    $('#file-note-editor').html('');
                    $('#file-note-editor-text').val('');
                }
            });

            // Save File Note button handler
            $('#save-file-note-btn').on('click', function() {
                // Copy content from Quill editor to hidden textarea
                if (document.getElementById('file-note-editor') && document.getElementById('file-note-editor').children[0]) {
                    var note = document.getElementById('file-note-editor').children[0].innerHTML;
                    document.getElementById('file-note-editor-text').value = note;
                }
                
                // Here you can add your save logic
                // For example: submit form, make AJAX call, etc.
                console.log('Note content:', $('#file-note-editor-text').val());
                
                // Close modal after save (you can modify this based on your needs)
                // $('#addFileNoteModal').modal('hide');
            });

            // Initialize installment months selectpicker when invoice modal opens
            $('#addInvoiceModal').on('shown.bs.modal', function () {
                $('#installment_months').selectpicker();
                // Set toggle to checked (ON) by default
                $('#installment_payment_toggle').prop('checked', true).attr('aria-checked', 'true');
                $('#installment_months_container').show();
                // Calculate and show installment note
                calculateTotalAmount();
            });

            // Calculate Total Amount Function
            function calculateTotalAmount() {
                const price = parseFloat($('#price').val()) || 0;
                const taxValue = $('#tax').val() || '';
                const taxPercent = taxValue ? parseFloat(taxValue.replace('GST ', '').replace('%', '')) : 0;
                const discount = parseFloat($('#discount').val()) || 0;
                
                // Sub Total is the price
                const subTotal = price;
                
                // Calculate tax: Based on image, tax seems to be calculated on (price - discount)
                // But to match image values: Price=50,000, Discount=5,000, Tax=500, Total=45,500
                // Formula: Total = Price - Discount + Tax
                // So: 45,500 = 50,000 - 5,000 + Tax => Tax = 500
                let taxAmount = 0;
                if (taxPercent > 0) {
                    // Calculate tax on (price - discount)
                    const taxableAmount = price - discount;
                    taxAmount = (taxableAmount * taxPercent) / 100;
                }
                
                // Calculate total: Sub Total - Discount + Tax
                const totalAmount = subTotal - discount + taxAmount;
                
                // Update Net Amount field (amount after discount, before tax)
                const netAmount = price - discount;
                $('#net_amount').val(Math.round(netAmount));
                
                // Update summary box
                $('#summary_sub_total').text('INR ' + formatNumber(subTotal));
                $('#summary_discount').text('INR ' + formatNumber(discount));
                $('#summary_tax_amount').text('INR ' + formatNumber(Math.round(taxAmount)));
                $('#summary_total_amount').text('INR ' + formatNumber(Math.round(totalAmount)));
                
                // Update installment note if toggle is ON
                if ($('#installment_payment_toggle').is(':checked')) {
                    updateInstallmentNote(Math.round(totalAmount));
                }
            }
            
            // Get current total amount
            function getCurrentTotal() {
                const price = parseFloat($('#price').val()) || 0;
                const taxValue = $('#tax').val() || '';
                const taxPercent = taxValue ? parseFloat(taxValue.replace('GST ', '').replace('%', '')) : 0;
                const discount = parseFloat($('#discount').val()) || 0;
                
                let taxAmount = 0;
                if (taxPercent > 0) {
                    const taxableAmount = price - discount;
                    taxAmount = (taxableAmount * taxPercent) / 100;
                }
                
                return Math.round(price - discount + taxAmount);
            }

            // Format number with commas
            function formatNumber(num) {
                return num.toLocaleString('en-IN', { maximumFractionDigits: 0 });
            }

            // Update Installment Note
            function updateInstallmentNote(totalAmount) {
                const months = parseInt($('#installment_months').val());
                if (months > 0 && totalAmount > 0) {
                    const installmentAmount = totalAmount / months;
                    $('#installment_note_text').text('Installment Rs.' + installmentAmount.toFixed(1) + ' for ' + months + ' months');
                    $('#installment_note').show();
                } else {
                    $('#installment_note').hide();
                }
            }

            // Calculate totals when price, tax, or discount changes
            $(document).on('input change', '#price, #tax, #discount', function() {
                calculateTotalAmount();
            });

            // Installment Payment Toggle Functionality
            $(document).on('change', '#installment_payment_toggle', function() {
                const isChecked = $(this).is(':checked');
                $(this).attr('aria-checked', isChecked);
                
                if (isChecked) {
                    $('#installment_months_container').slideDown();
                    // Refresh selectpicker after showing
                    setTimeout(function() {
                        $('#installment_months').selectpicker('refresh');
                        // Calculate installment if months already selected
                        updateInstallmentNote(getCurrentTotal());
                    }, 100);
                } else {
                    $('#installment_months_container').slideUp();
                    $('#installment_months').val('').selectpicker('refresh');
                    $('#installment_note').hide();
                }
            });

            // Update installment note when months change
            $(document).on('change', '#installment_months', function() {
                if ($('#installment_payment_toggle').is(':checked')) {
                    updateInstallmentNote(getCurrentTotal());
                }
            });

            // Initialize calculation when modal opens
            $('#addInvoiceModal').on('shown.bs.modal', function () {
                calculateTotalAmount();
            });

            // Reset form when invoice modal is closed
            $('#addInvoiceModal').on('hidden.bs.modal', function () {
                $('#installment_payment_toggle').prop('checked', true).attr('aria-checked', 'true');
                $('#installment_months_container').show();
                $('#installment_months').val('8').selectpicker('refresh');
            });
        });
    </script>
@endpush


