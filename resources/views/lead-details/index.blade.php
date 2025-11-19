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
                            <div class="tab-item"><img src="{{ asset('img/icon/Communication.svg') }}"></div>Communication
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
                        </div>
                    </div>
                    <!-- Tab Content Area -->
                    <div class="tab-section-content">
                        <!-- Personal Details Section -->
                        <div class="info-section mb-4">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Personal Details</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            <div class="info-grid-row row mb-3">
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
                        <div class="info-section mb-4">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Last Five Years Visa Status</h4>
                            </div>
                            <div class="info-grid-row row mb-3">
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
                        <div class="info-section mb-4">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Client Preference</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            <div class="info-grid-row row mb-3">
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
                        <div class="info-section mb-4">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Passport Details</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            <div class="info-grid-row row mb-3">
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
                        <div class="info-section mb-4">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Relative Contact Information</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Relative Contact 1</h5>
                            </div>
                            <div class="info-grid-row row mb-3">
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
                                <div class="info-field-item col-md-4 mb-3">
                                    <div class="info-field-label-text">Contact Address</div>
                                    <div class="info-field-value-text">27 Greenfield Avenue, Maplewood Heights, New Delhi, 110019, India</div>
                                </div>
                                <div class="info-field-item col-md-4 mb-3">
                                    <div class="info-field-label-text">Email</div>
                                    <div class="info-field-value-text">karan.shah@technova.com</div>
                                </div>
                                <div class="info-field-item col-md-4 mb-3">
                                    <div class="info-field-label-text">Phone Number</div>
                                    <div class="info-field-value-text">+91 98254 12345</div>
                                </div>
                            </div>
                        </div>

                        <!-- Family Information Section -->
                        <div class="info-section mb-4">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Family Information</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            
                            <!-- Father Details -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Father Details</h5>
                            </div>
                            <div class="info-grid-row row mb-3">
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
                            <div class="info-grid-row row mb-3">
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
                            <div class="info-grid-row row mb-3">
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
                            <div class="info-grid-row row mb-3">
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
                            <div class="info-grid-row row mb-3">
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

                        <!-- Professional Experience Section -->
                        <div class="info-section mb-4">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Professional Experience</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            
                            <!-- Experience 1 -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Experience 1</h5>
                            </div>
                            <div class="info-grid-row row mb-3">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Company Name</div>
                                    <div class="info-field-value-text">TechNova Solutions Pvt. Ltd.</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Designation</div>
                                    <div class="info-field-value-text">Senior UI/UX Designer</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Start Date</div>
                                    <div class="info-field-value-text">01-Jan-2020</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">End Date</div>
                                    <div class="info-field-value-text">Present</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-6 mb-3">
                                    <div class="info-field-label-text">Job Description</div>
                                    <div class="info-field-value-text">Responsible for designing user interfaces and user experiences for web and mobile applications. Collaborated with cross-functional teams to deliver high-quality design solutions.</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Location</div>
                                    <div class="info-field-value-text">Ahmedabad, India</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Employment Type</div>
                                    <div class="info-field-value-text">Full-time</div>
                                </div>
                            </div>

                            <!-- Experience 2 -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Experience 2</h5>
                            </div>
                            <div class="info-grid-row row mb-3">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Company Name</div>
                                    <div class="info-field-value-text">Digital Innovations Inc.</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Designation</div>
                                    <div class="info-field-value-text">UI/UX Designer</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Start Date</div>
                                    <div class="info-field-value-text">15-Jun-2018</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">End Date</div>
                                    <div class="info-field-value-text">31-Dec-2019</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-6 mb-3">
                                    <div class="info-field-label-text">Job Description</div>
                                    <div class="info-field-value-text">Designed user interfaces for various client projects. Worked on wireframing, prototyping, and visual design.</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Location</div>
                                    <div class="info-field-value-text">Mumbai, India</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Employment Type</div>
                                    <div class="info-field-value-text">Full-time</div>
                                </div>
                            </div>
                        </div>

                        <!-- Property Details Section -->
                        <div class="info-section mb-4">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Property Details</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            
                            <!-- Property 1 -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Property 1</h5>
                            </div>
                            <div class="info-grid-row row mb-3">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Property Type</div>
                                    <div class="info-field-value-text">Residential</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Ownership Type</div>
                                    <div class="info-field-value-text">Owned</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Property Value</div>
                                    <div class="info-field-value-text">₹ 50,00,000</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Area (Sq. Ft.)</div>
                                    <div class="info-field-value-text">1,200</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-6 mb-3">
                                    <div class="info-field-label-text">Property Address</div>
                                    <div class="info-field-value-text">27 Greenfield Avenue, Maplewood Heights, New Delhi, 110019, India</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Purchase Date</div>
                                    <div class="info-field-value-text">15-Mar-2019</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Mortgage/Loan</div>
                                    <div class="info-field-value-text">Yes</div>
                                </div>
                            </div>

                            <!-- Property 2 -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Property 2</h5>
                            </div>
                            <div class="info-grid-row row mb-3">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Property Type</div>
                                    <div class="info-field-value-text">Commercial</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Ownership Type</div>
                                    <div class="info-field-value-text">Owned</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Property Value</div>
                                    <div class="info-field-value-text">₹ 1,20,00,000</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Area (Sq. Ft.)</div>
                                    <div class="info-field-value-text">2,500</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-6 mb-3">
                                    <div class="info-field-label-text">Property Address</div>
                                    <div class="info-field-value-text">B-204, Navkar Residency, Vesu, Surat, Gujarat, 395007, India</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Purchase Date</div>
                                    <div class="info-field-value-text">10-Aug-2021</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Mortgage/Loan</div>
                                    <div class="info-field-value-text">No</div>
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
                            <div class="info-grid-row row mb-3">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Annual Income</div>
                                    <div class="info-field-value-text">₹ 12,00,000</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Monthly Income</div>
                                    <div class="info-field-value-text">₹ 1,00,000</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Income Source</div>
                                    <div class="info-field-value-text">Salary</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Additional Income</div>
                                    <div class="info-field-value-text">₹ 2,00,000</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Additional Income Source</div>
                                    <div class="info-field-value-text">Freelance Projects</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Total Annual Income</div>
                                    <div class="info-field-value-text">₹ 14,00,000</div>
                                </div>
                            </div>

                            <!-- Bank Accounts -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Bank Accounts</h5>
                            </div>
                            <div class="info-grid-row row mb-3">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Bank Name</div>
                                    <div class="info-field-value-text">HDFC Bank</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Account Type</div>
                                    <div class="info-field-value-text">Savings</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Account Number</div>
                                    <div class="info-field-value-text">****1234</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Current Balance</div>
                                    <div class="info-field-value-text">₹ 5,50,000</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Bank Name</div>
                                    <div class="info-field-value-text">ICICI Bank</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Account Type</div>
                                    <div class="info-field-value-text">Current</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Account Number</div>
                                    <div class="info-field-value-text">****5678</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Current Balance</div>
                                    <div class="info-field-value-text">₹ 2,30,000</div>
                                </div>
                            </div>

                            <!-- Assets & Liabilities -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Assets & Liabilities</h5>
                            </div>
                            <div class="info-grid-row row mb-3">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Total Assets Value</div>
                                    <div class="info-field-value-text">₹ 1,75,00,000</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Total Liabilities</div>
                                    <div class="info-field-value-text">₹ 30,00,000</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Net Worth</div>
                                    <div class="info-field-value-text">₹ 1,45,00,000</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Credit Score</div>
                                    <div class="info-field-value-text">750</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-4 mb-3">
                                    <div class="info-field-label-text">Investments (Stocks, Mutual Funds, etc.)</div>
                                    <div class="info-field-value-text">₹ 15,00,000</div>
                                </div>
                                <div class="info-field-item col-md-4 mb-3">
                                    <div class="info-field-label-text">Loans Outstanding</div>
                                    <div class="info-field-value-text">₹ 30,00,000</div>
                                </div>
                                <div class="info-field-item col-md-4 mb-3">
                                    <div class="info-field-label-text">Credit Cards</div>
                                    <div class="info-field-value-text">2 Active Cards</div>
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
                            <h3 class="tab-section-title">Process - <span class="tab-section-subtitle">Registered Date: 05-09-2025</span></h3>
                        </div>
                    </div>
                    <!-- Tab Content Area -->
                    <div class="tab-section-content">
                        <!-- Agent & Applicant Details Section -->
                        <div class="info-section mb-4">
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
                        <div class="info-section mb-4">
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
                        <div class="info-section mb-4">
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
                        <div class="info-section mb-4">
                            <div class="info-section-title mb-3">
                                <h4 class="f-16 font-weight-bold">Upload Documents</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <x-forms.label fieldId="contract_letter" fieldLabel="Contract Letter">
                                    </x-forms.label>
                                    <input type="file" class="form-control" name="contract_letter" id="contract_letter">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <x-forms.label fieldId="grant_letter" fieldLabel="Grant Letter">
                                    </x-forms.label>
                                    <input type="file" class="form-control" name="grant_letter" id="grant_letter">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <x-forms.label fieldId="offer_letter" fieldLabel="Offer Letter/Sponsor Letter">
                                    </x-forms.label>
                                    <input type="file" class="form-control" name="offer_letter" id="offer_letter">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <x-forms.label fieldId="medical_letter" fieldLabel="Medical Letter">
                                    </x-forms.label>
                                    <input type="file" class="form-control" name="medical_letter" id="medical_letter">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <x-forms.label fieldId="air_ticket" fieldLabel="Air Ticket">
                                    </x-forms.label>
                                    <input type="file" class="form-control" name="air_ticket" id="air_ticket">
                                </div>
                                <div class="col-md-4 mb-3">
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
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>DOCUMENT TYPE/NAME</th>
                                        <th>STATUS</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input">
                                                <label class="form-check-label">Assessment Letter</label>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-secondary">-</span></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" checked>
                                                <label class="form-check-label">Passport - Applicant</label>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-warning">Pending</span></td>
                                        <td><button class="btn btn-sm btn-primary">Upload</button></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" checked>
                                                <label class="form-check-label">Father Passport</label>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-warning">Pending</span></td>
                                        <td><button class="btn btn-sm btn-primary">Upload</button></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" checked>
                                                <label class="form-check-label">Child Document</label>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-success">Received</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-info mr-1">View</button>
                                            <a href="#" class="text-danger">Delete</a>
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
                    </div>
                </div>
                <!-- Communication Tab Content -->
                <div class="tab-content px-4 pb-4" id="communicationTab">
                    <!-- Tab Header -->
                    <div class="tab-section-header">
                        <div class="tab-section-header-content">
                            <h3 class="tab-section-title">Communication History</h3>
                            <button type="button" class="tab-section-add-btn" data-toggle="modal" data-target="#addCommunicationModal">
                                <i class="fa fa-plus"></i>
                            </button>
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
                        <textarea class="form-control" rows="5" placeholder="Enter your details."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Invoice Modal -->
    <div class="modal fade" id="addInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addInvoiceModalLabel">ADD INVOICE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <x-forms.label fieldId="client_name" fieldLabel="Client Name">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" value="Kishan Ghaghada">
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-forms.label fieldId="invoice_date" fieldLabel="Invoice Date">
                            </x-forms.label>
                            <input type="date" class="form-control height-35 f-14" value="17-07-2023">
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-forms.label fieldId="phone" fieldLabel="Phone">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" value="+91 123 4567 890">
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-forms.label fieldId="email" fieldLabel="Email">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" value="abc@gmail.com">
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-forms.label fieldId="address" fieldLabel="Address">
                            </x-forms.label>
                            <textarea class="form-control f-14" rows="2"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-forms.label fieldId="bill_to" fieldLabel="Bill To">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-forms.label fieldId="invoice_belongs" fieldLabel="Invoice Belongs To">
                            </x-forms.label>
                            <select class="form-control select-picker height-35 f-14">
                                <option value="">Select Agent</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <x-forms.label fieldId="service" fieldLabel="Service">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" value="Student Visa - Temporary Graduate Visa (Australia)(Subclass 485)">
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="price" fieldLabel="Price">
                            </x-forms.label>
                            <input type="number" class="form-control height-35 f-14" value="">
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="tax" fieldLabel="Tax">
                            </x-forms.label>
                            <select class="form-control select-picker height-35 f-14">
                                <option value="GST 18%" selected>GST 18%</option>
                                <option value="GST 0%">GST 0%</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="discount" fieldLabel="Discount">
                            </x-forms.label>
                            <input type="number" class="form-control height-35 f-14" value="">
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="net_amount" fieldLabel="Net Amount">
                            </x-forms.label>
                            <input type="number" class="form-control height-35 f-14" value="">
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-forms.label fieldId="service_description" fieldLabel="Service Description">
                            </x-forms.label>
                            <textarea class="form-control f-14" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save</button>
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
                            <div class="col-md-6 mb-3">
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
                <div class="modal-header">
                    <h5 class="modal-title" id="addTravelDetailsModalLabel">Add Travel Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <x-forms.label fieldId="purpose_of_trip" fieldLabel="Purpose of Trip">
                        </x-forms.label>
                        <input type="text" class="form-control height-35 f-14">
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="date_of_arrival" fieldLabel="Date of Arrival">
                            </x-forms.label>
                            <input type="date" class="form-control height-35 f-14">
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="arrival_flight" fieldLabel="Arrival Flight">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14">
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="arrival_city" fieldLabel="Arrival City">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14">
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="date_of_departure" fieldLabel="Date of Departure From">
                            </x-forms.label>
                            <input type="date" class="form-control height-35 f-14">
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-forms.label fieldId="departure_flight" fieldLabel="Departure Flight">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14">
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-forms.label fieldId="departure_city" fieldLabel="Departure City">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14">
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-forms.label fieldId="phone_number_other_country" fieldLabel="Phone Number (of other country)">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14">
                        </div>
                    </div>
                    <div class="form-group">
                        <x-forms.label fieldId="place_to_visit" fieldLabel="Place To Visit USA">
                        </x-forms.label>
                        <textarea class="form-control f-14" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <x-forms.label fieldId="address_stay" fieldLabel="Address Where You Will Stay">
                        </x-forms.label>
                        <textarea class="form-control f-14" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <x-forms.label fieldId="city" fieldLabel="City">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14">
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-forms.label fieldId="state" fieldLabel="State">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14">
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-forms.label fieldId="postal_code" fieldLabel="Postal/Zip Code">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14">
                        </div>
                    </div>
                    <div class="form-group">
                        <x-forms.label fieldId="person_paying" fieldLabel="Person Paying For Your Trip (Details)">
                        </x-forms.label>
                        <textarea class="form-control f-14" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <x-forms.label fieldId="mother_in_country" fieldLabel="Is Your Mother in that country?">
                            </x-forms.label>
                            <select class="form-control select-picker height-35 f-14">
                                <option value="" disabled selected>Select</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-forms.label fieldId="immediate_relatives" fieldLabel="Immediate Relatives In that country?">
                            </x-forms.label>
                            <select class="form-control select-picker height-35 f-14">
                                <option value="" disabled selected>Select</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-forms.label fieldId="other_relatives" fieldLabel="Other Relatives In that country?">
                            </x-forms.label>
                            <select class="form-control select-picker height-35 f-14">
                                <option value="" disabled selected>Select</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save</button>
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
        });
    </script>
@endpush

