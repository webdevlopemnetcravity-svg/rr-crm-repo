@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/add-lead.css') }}">
@endpush

@section('content')
    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        <!-- Add Task Export Buttons End -->

        <div class="d-flex flex-column w-100 rounded mt-3 bg-white">
            <!-- Tabs Navigation -->
            <div class="s-b-n-header" id="tabs">
                <nav class="tabs px-4 border-bottom-grey">
                    <div class="nav" id="nav-tab" role="tablist">
                        <a class="nav-item-lead nav-link-lead f-14 active" id="nav-personal-tab" data-toggle="tab" href="#nav-personal" role="tab" aria-controls="nav-personal" aria-selected="true">
                            <div class="tab-item"><img src="{{ asset('img/icon/Personal_Details.svg') }}"></div>@lang('app.personalDetails')
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" id="nav-preference-tab" data-toggle="tab" href="#nav-preference" role="tab" aria-controls="nav-preference" aria-selected="false">
                            <div class="tab-item"><img src="{{ asset('img/icon/Client_Preference.svg') }}"></div>@lang('app.clientPreference')
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" id="nav-passport-tab" data-toggle="tab" href="#nav-passport" role="tab" aria-controls="nav-passport" aria-selected="false">
                            <div class="tab-item"><img src="{{ asset('img/icon/Passport_Details.svg') }}"></div>@lang('app.passportDetails')
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" id="nav-relative-tab" data-toggle="tab" href="#nav-relative" role="tab" aria-controls="nav-relative" aria-selected="false">
                            <div class="tab-item"><img src="{{ asset('img/icon/Relative_Contact_Information.svg') }}"></div>@lang('app.relativeContactInformation')
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" id="nav-family-tab" data-toggle="tab" href="#nav-family" role="tab" aria-controls="nav-family" aria-selected="false">
                            <div class="tab-item"><img src="{{ asset('img/icon/Financial_Status.svg') }}"></div>@lang('app.familyInformation')
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" id="nav-education-tab" data-toggle="tab" href="#nav-education" role="tab" aria-controls="nav-education" aria-selected="false">
                            <div class="tab-item"><img src="{{ asset('img/icon/Education.svg') }}"></div>@lang('app.education')
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" id="nav-experience-tab" data-toggle="tab" href="#nav-experience" role="tab" aria-controls="nav-experience" aria-selected="false">
                            <div class="tab-item"><img src="{{ asset('img/icon/Professional_Experience.svg') }}"></div>@lang('app.professionalExperience')
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" id="nav-property-tab" data-toggle="tab" href="#nav-property" role="tab" aria-controls="nav-property" aria-selected="false">
                            <div class="tab-item"><img src="{{ asset('img/icon/Property_Details.svg') }}"></div>@lang('app.propertyDetails')
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" id="nav-financial-tab" data-toggle="tab" href="#nav-financial" role="tab" aria-controls="nav-financial" aria-selected="false">
                            <div class="tab-item"><img src="{{ asset('img/icon/Financial_Status.svg') }}"></div>@lang('app.financialStatus')
                        </a>
                    </div>
                </nav>
            </div>

            <!-- Form Card -->
            <x-form id="addLeadForm" class="ajax-form">
                <input type="hidden" name="lead_id" id="lead_id" value="{{ $newLead->id ?? '' }}">
                <div class="tab-content p-20" id="nav-tabContent">
                    <!-- Personal Details Tab -->
                    <div class="tab-pane fade show active" id="nav-personal" role="tabpanel" aria-labelledby="nav-personal-tab">
                        <!-- Personal Details Section -->
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="lead_source" :fieldLabel="__('modules.lead.leadSource')" fieldRequired="true">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="lead_source" id="lead_source">
                                    <option value="">@lang('app.select') @lang('modules.lead.leadSource')</option>
                                    <option value="Facebook">Facebook</option>
                                    <option value="Google Ads">Google Ads</option>
                                    <option value="Walk-in">Walk-in</option>
                                    <option value="WhatsApp Inquiry">WhatsApp Inquiry</option>
                                    <option value="Reference">Reference</option>
                                    <option value="Website">Website</option>
                                    <option value="Email Marketing">Email Marketing</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="lead_added_by" :fieldLabel="__('app.leadAddedBy')">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" id="lead_added_by" name="lead_added_by" value="{{ user()->name }}" readonly style="background-color: #e9ecef;">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="lead_assign_to" :fieldLabel="__('app.leadAssignTo')" fieldRequired="true">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="lead_assign_to" id="lead_assign_to">
                                    <option value="">@lang('app.select') @lang('app.leadAssignTo')</option>
                                    <option value="{{ user()->id }}">{{ user()->name }}</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="upload_resume" fieldLabel="Upload Resume" fieldRequired="true">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" id="upload_resume" name="upload_resume" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="surname" :fieldLabel="__('app.surname')" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="surname" id="surname">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="given_name" :fieldLabel="__('app.givenName')" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="given_name" id="given_name">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="gender" :fieldLabel="__('app.gender')" fieldRequired="true">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="gender" id="gender">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Male">@lang('app.male')</option>
                                    <option value="Female">@lang('app.female')</option>
                                    <option value="Other">@lang('app.other')</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="marital_status" :fieldLabel="__('app.maritalStatus')" fieldRequired="true">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="marital_status" id="marital_status">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Single">@lang('app.single')</option>
                                    <option value="Married">@lang('app.maritalStatus.married')</option>
                                    <option value="Divorced">@lang('app.maritalStatus.divorced')</option>
                                    <option value="Widowed">@lang('app.maritalStatus.widow')</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="date_of_birth" :fieldLabel="__('app.dateOfBirth')" fieldRequired="true">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="date_of_birth" id="date_of_birth" max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="country_of_origin" :fieldLabel="__('app.countryOfOrigin')" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="country_of_origin" id="country_of_origin">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Home Address Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.homeAddress')</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="home_address" :fieldLabel="__('modules.lead.address')" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="home_address" id="home_address">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="home_city" :fieldLabel="__('app.city')" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="home_city" id="home_city">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="home_state" :fieldLabel="__('app.state')" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="home_state" id="home_state">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="home_pin_code" :fieldLabel="__('app.pinCode')" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="home_pin_code" id="home_pin_code">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Mailing Address Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.mailingAddress')</h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mailing_same_as_home" id="mailing_same_as_home" value="1">
                                    <label class="form-check-label" for="mailing_same_as_home">
                                        @lang('app.mailingAddressAsAbove')
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mailing_address" :fieldLabel="__('modules.lead.address')" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mailing_address" id="mailing_address">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mailing_city" :fieldLabel="__('app.city')" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mailing_city" id="mailing_city">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mailing_state" :fieldLabel="__('app.state')" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mailing_state" id="mailing_state">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mailing_pin_code" :fieldLabel="__('app.pinCode')" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mailing_pin_code" id="mailing_pin_code">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Contact Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.contactDetails')</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="primary_phone" :fieldLabel="__('app.primaryPhoneNo')" fieldRequired="true">
                                </x-forms.label>
                                <input type="number" max="9999999999" min="0" class="form-control height-35 f-14" name="primary_phone" id="primary_phone" oninput="this.value = this.value.slice(0, 10)">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="secondary_phone" :fieldLabel="__('app.secondaryPhoneNo')">
                                </x-forms.label>
                                <input type="number" max="9999999999" min="0" class="form-control height-35 f-14" name="secondary_phone" id="secondary_phone" oninput="this.value = this.value.slice(0, 10)">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="work_phone" :fieldLabel="__('app.workPhoneNo')">
                                </x-forms.label>
                                <input type="number" max="9999999999" min="0" class="form-control height-35 f-14" name="work_phone" id="work_phone" oninput="this.value = this.value.slice(0, 10)">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_phone" :fieldLabel="__('app.otherPhoneNo')">
                                </x-forms.label>
                                <input type="number" max="9999999999" min="0" class="form-control height-35 f-14" name="other_phone" id="other_phone" oninput="this.value = this.value.slice(0, 10)">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="email_address" :fieldLabel="__('modules.lead.email')" fieldRequired="true">
                                </x-forms.label>
                                <input type="email" class="form-control height-35 f-14" name="email_address" id="email_address">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_email" :fieldLabel="__('app.otherEmail')">
                                </x-forms.label>
                                <input type="email" class="form-control height-35 f-14" name="other_email" id="other_email">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Social Media Profile URLs Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.socialMediaPreference')</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.label class="mt-3" fieldId="facebook_profile_url" fieldLabel="Facebook Profile URL">
                                </x-forms.label>
                                <input type="url" class="form-control height-35 f-14" name="facebook_profile_url" id="facebook_profile_url" placeholder="https://www.facebook.com/yourprofile">
                            </div>
                            <div class="col-md-4">
                                <x-forms.label class="mt-3" fieldId="instagram_profile_url" fieldLabel="Instagram Profile URL">
                                </x-forms.label>
                                <input type="url" class="form-control height-35 f-14" name="instagram_profile_url" id="instagram_profile_url" placeholder="https://www.instagram.com/yourprofile">
                            </div>
                            <div class="col-md-4">
                                <x-forms.label class="mt-3" fieldId="linkedin_profile_url" fieldLabel="LinkedIn Profile URL">
                                </x-forms.label>
                                <input type="url" class="form-control height-35 f-14" name="linkedin_profile_url" id="linkedin_profile_url" placeholder="https://www.linkedin.com/in/yourprofile">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Visa Status Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.lastFiveYearsVisaStatus') <span class="text-danger">*</span></h6>
                        
                        <!-- Visa Status Radio Buttons -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-check form-check-inline mr-4">
                                    <input class="form-check-input" type="radio" name="visa_status" id="visa_granted" value="granted">
                                    <label class="form-check-label f-14" for="visa_granted">
                                        @lang('app.visaGranted')
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="visa_status" id="visa_refusal" value="refusal">
                                    <label class="form-check-label f-14" for="visa_refusal">
                                        @lang('app.visaRefusal')
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Visa Granted Fields -->
                        <div class="row" id="visa_granted_fields" style="display: none;">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="visa_issue_date" :fieldLabel="__('app.visaIssueDate')" fieldRequired="true">
                                </x-forms.label>
                                <input type="month" class="form-control height-35 f-14" name="visa_issue_date" id="visa_issue_date" max="{{ date('Y-m') }}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="visa_expire_date" :fieldLabel="__('app.visaExpireDate')" fieldRequired="true">
                                </x-forms.label>
                                <input type="month" class="form-control height-35 f-14" name="visa_expire_date" id="visa_expire_date">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="visa_category" :fieldLabel="__('app.visaCategory')" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="visa_category" id="visa_category">
                            </div>
                        </div>
                        
                        <!-- Visa Refusal Fields -->
                        <div id="visa_refusal_fields" style="display: none;">
                            <!-- Initial Visa Refusal Entry -->
                            <div class="row">
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="visa_rejection_date" :fieldLabel="__('app.visaRejectionDate')" fieldRequired="true">
                                    </x-forms.label>
                                    <input type="month" class="form-control height-35 f-14" name="visa_rejection_date" id="visa_rejection_date" max="{{ date('Y-m') }}">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="visa_refusal_category" :fieldLabel="__('app.visaCategory')" fieldRequired="true">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="visa_refusal_category" id="visa_refusal_category">
                                </div>
                                <div class="col-md-6">
                                    <x-forms.label class="mt-3" fieldId="visa_refusal_reason" :fieldLabel="__('app.reason')" fieldRequired="true">
                                    </x-forms.label>
                                    <textarea class="form-control f-14" rows="2" name="visa_refusal_reason" id="visa_refusal_reason"></textarea>
                                </div>
                            </div>
                            
                            <!-- Add More Visa Refusal Button -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary btn-sm" id="add-more-visa-refusal">
                                        <i class="fa fa-plus mr-1"></i>@lang('app.addMoreVisaRefusal')
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Dynamic Visa Refusal Rows Container -->
                            <div id="visa-refusal-rows-container"></div>
                        </div>

                        <hr class="my-4">

                        <!-- Languages Spoken Section -->
                <div class="row">
                            <div class="col-md-12">
                                <x-forms.label class="mt-3" fieldId="languages_spoken" :fieldLabel="__('app.languagesSpoken')" fieldRequired="true">
                                </x-forms.label>
                                <textarea class="form-control f-14" rows="2" name="languages_spoken" id="languages_spoken"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Client Preference Tab -->
                    <div class="tab-pane fade" id="nav-preference" role="tabpanel" aria-labelledby="nav-preference-tab">
                        <!-- Visa Type Selection -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <x-forms.label class="mt-3 mb-3" fieldId="visa_type" :fieldLabel="__('app.selectVisaType')" fieldRequired="true">
                                </x-forms.label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input visa-type" type="radio" name="visa_type" id="visa_pr" value="pr">
                                    <label class="form-check-label" for="visa_pr">@lang('app.pr')</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input visa-type" type="radio" name="visa_type" id="visa_visit" value="visit">
                                    <label class="form-check-label" for="visa_visit">@lang('app.visitVisa')</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input visa-type" type="radio" name="visa_type" id="visa_work" value="work">
                                    <label class="form-check-label" for="visa_work">@lang('app.workPermit')</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input visa-type" type="radio" name="visa_type" id="visa_student" value="student">
                                    <label class="form-check-label" for="visa_student">@lang('app.studentVisa')</label>
                                </div>
                    </div>
                </div>

                        <!-- PR Section -->
                        <div id="prSection" class="form-section d-none">
                <div class="row">
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="skill_assessment_letter" :fieldLabel="__('app.skillAssessmentLetter')" fieldRequired="true">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="skill_assessment_letter" id="skill_assessment_letter">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Positive">@lang('app.positive')</option>
                                        <option value="Negative">@lang('app.negative')</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="pr_assessment_letter_file" :fieldLabel="__('app.addAssessmentLetter')" fieldRequired="true">
                                    </x-forms.label>
                                    <input class="form-control height-35 f-14" type="file" id="pr_assessment_letter_file" name="pr_assessment_letter_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="pr_preferred_country" :fieldLabel="__('app.preferredCountry')">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="pr_preferred_country" id="pr_preferred_country">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Australia">@lang('app.countryAustralia')</option>
                                        <option value="New Zealand">@lang('app.countryNewZealand')</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="pr_preferred_state" :fieldLabel="__('app.preferredState')">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="pr_preferred_state" id="pr_preferred_state">
                                        <option value="">@lang('app.select')</option>
                                        <!-- Australian States -->
                                        <option value="Western Australia (WA)" data-country="Australia">@lang('app.stateWesternAustralia')</option>
                                        <option value="South Australia (SA)" data-country="Australia">@lang('app.stateSouthAustralia')</option>
                                        <option value="Australian Capital Territory (ACT)" data-country="Australia">@lang('app.stateAustralianCapitalTerritory')</option>
                                        <option value="Queensland (QLD)" data-country="Australia">@lang('app.stateQueensland')</option>
                                        <option value="New South Wales (NSW)" data-country="Australia">@lang('app.stateNewSouthWales')</option>
                                        <option value="Victoria (VIC)" data-country="Australia">@lang('app.stateVictoria')</option>
                                        <option value="Tasmania (TAS)" data-country="Australia">@lang('app.stateTasmania')</option>
                                        <option value="Northern Territory (NT)" data-country="Australia">@lang('app.stateNorthernTerritory')</option>
                                        <!-- New Zealand Regions -->
                                        <option value="Waikato" data-country="New Zealand">@lang('app.stateWaikato')</option>
                                        <option value="Bay of Plenty" data-country="New Zealand">@lang('app.stateBayOfPlenty')</option>
                                        <option value="Hawke's Bay" data-country="New Zealand">@lang('app.stateHawkesBay')</option>
                                        <option value="Manawatu-Whanganui" data-country="New Zealand">@lang('app.stateManawatuWhanganui')</option>
                                        <option value="Taranaki" data-country="New Zealand">@lang('app.stateTaranaki')</option>
                                        <option value="Canterbury" data-country="New Zealand">@lang('app.stateCanterbury')</option>
                                        <option value="Otago" data-country="New Zealand">@lang('app.stateOtago')</option>
                                        <option value="Southland" data-country="New Zealand">@lang('app.stateSouthland')</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="pr_family" :fieldLabel="__('app.family')" fieldRequired="true">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="pr_family" id="pr_family">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Single">@lang('app.single')</option>
                                        <option value="Couple Visa">@lang('app.coupleVisa')</option>
                                        <option value="Couple + Children Visa">@lang('app.coupleChildrenVisa')</option>
                                        <option value="Family Visa">@lang('app.familyVisa')</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="pr_subclass" :fieldLabel="__('app.subclass')">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="pr_subclass" id="pr_subclass">
                                        <option value="">@lang('app.select')</option>
                                        <option value="PR - Employer Nomination Scheme (ENS)(Subclass 186)">PR - Employer Nomination Scheme (ENS)(Subclass 186)</option>
                                        <option value="PR - Skilled Nominated Visa (Subclass 190)">PR - Skilled Nominated Visa (Subclass 190)</option>
                                        <option value="PR - Skilled Independent Visa (Subclass 189)">PR - Skilled Independent Visa (Subclass 189)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Visit Visa Section -->
                        <div id="visitSection" class="form-section d-none">
                            <div class="row">
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="purpose_of_visit" :fieldLabel="__('app.purposeOfVisit')" fieldRequired="true">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="purpose_of_visit" id="purpose_of_visit">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="visit_family" :fieldLabel="__('app.family')" fieldRequired="true">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="visit_family" id="visit_family">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Single">@lang('app.single')</option>
                                        <option value="Couple Visa">@lang('app.coupleVisa')</option>
                                        <option value="Couple + Children Visa">@lang('app.coupleChildrenVisa')</option>
                                        <option value="Family Visa">@lang('app.familyVisa')</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="visit_preferred_country" :fieldLabel="__('app.preferredCountry')">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="visit_preferred_country" id="visit_preferred_country">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Australia">@lang('app.countryAustralia')</option>
                                        <option value="New Zealand">@lang('app.countryNewZealand')</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="visit_preferred_state" :fieldLabel="__('app.preferredState')">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="visit_preferred_state" id="visit_preferred_state">
                                        <option value="">@lang('app.select')</option>
                                        <!-- Australian States -->
                                        <option value="Western Australia (WA)" data-country="Australia">@lang('app.stateWesternAustralia')</option>
                                        <option value="South Australia (SA)" data-country="Australia">@lang('app.stateSouthAustralia')</option>
                                        <option value="Australian Capital Territory (ACT)" data-country="Australia">@lang('app.stateAustralianCapitalTerritory')</option>
                                        <option value="Queensland (QLD)" data-country="Australia">@lang('app.stateQueensland')</option>
                                        <option value="New South Wales (NSW)" data-country="Australia">@lang('app.stateNewSouthWales')</option>
                                        <option value="Victoria (VIC)" data-country="Australia">@lang('app.stateVictoria')</option>
                                        <option value="Tasmania (TAS)" data-country="Australia">@lang('app.stateTasmania')</option>
                                        <option value="Northern Territory (NT)" data-country="Australia">@lang('app.stateNorthernTerritory')</option>
                                        <!-- New Zealand Regions -->
                                        <option value="Waikato" data-country="New Zealand">@lang('app.stateWaikato')</option>
                                        <option value="Bay of Plenty" data-country="New Zealand">@lang('app.stateBayOfPlenty')</option>
                                        <option value="Hawke's Bay" data-country="New Zealand">@lang('app.stateHawkesBay')</option>
                                        <option value="Manawatu-Whanganui" data-country="New Zealand">@lang('app.stateManawatuWhanganui')</option>
                                        <option value="Taranaki" data-country="New Zealand">@lang('app.stateTaranaki')</option>
                                        <option value="Canterbury" data-country="New Zealand">@lang('app.stateCanterbury')</option>
                                        <option value="Otago" data-country="New Zealand">@lang('app.stateOtago')</option>
                                        <option value="Southland" data-country="New Zealand">@lang('app.stateSouthland')</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="visit_subclass" :fieldLabel="__('app.subclass')">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="visit_subclass" id="visit_subclass">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Visitor Visa (Subclass 600)">Visitor Visa (Subclass 600)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Work Permit Section -->
                        <div id="workSection" class="form-section d-none">
                            <div class="row">
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="preferred_designation" :fieldLabel="__('app.preferredDesignation')" fieldRequired="true">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="preferred_designation" id="preferred_designation" placeholder="@lang('app.enterDesignation')">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="industry" :fieldLabel="__('app.industry')">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="industry" id="industry">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Agriculture">@lang('app.industryAgriculture')</option>
                                        <option value="Aviation Ground Staff">@lang('app.industryAviationGroundStaff')</option>
                                        <option value="Call Centres / BPO">@lang('app.industryCallCentresBPO')</option>
                                        <option value="Catering Services">@lang('app.industryCateringServices')</option>
                                        <option value="Chemical Manufacturing">@lang('app.industryChemicalManufacturing')</option>
                                        <option value="Childcare">@lang('app.industryChildcare')</option>
                                        <option value="Cleaning & Facility Management">@lang('app.industryCleaningFacilityManagement')</option>
                                        <option value="Construction">@lang('app.industryConstruction')</option>
                                        <option value="Courier & Delivery Services">@lang('app.industryCourierDeliveryServices')</option>
                                        <option value="Dairy & Livestock">@lang('app.industryDairyLivestock')</option>
                                        <option value="Data Entry Services">@lang('app.industryDataEntryServices')</option>
                                        <option value="Education">@lang('app.industryEducation')</option>
                                        <option value="Electronics Manufacturing">@lang('app.industryElectronicsManufacturing')</option>
                                        <option value="Events & Entertainment">@lang('app.industryEventsEntertainment')</option>
                                        <option value="Food Chains">@lang('app.industryFoodChains')</option>
                                        <option value="Food Processing">@lang('app.industryFoodProcessing')</option>
                                        <option value="Healthcare Services">@lang('app.industryHealthcareServices')</option>
                                        <option value="Hospitals & Clinics">@lang('app.industryHospitalsClinics')</option>
                                        <option value="Hotels & Hospitality">@lang('app.industryHotelsHospitality')</option>
                                        <option value="Industrial Production">@lang('app.industryIndustrialProduction')</option>
                                        <option value="Infrastructure">@lang('app.industryInfrastructure')</option>
                                        <option value="IT Services">@lang('app.industryITServices')</option>
                                        <option value="Logistics & Transport">@lang('app.industryLogisticsTransport')</option>
                                        <option value="Manufacturing">@lang('app.industryManufacturing')</option>
                                        <option value="Nursing & Aged Care">@lang('app.industryNursingAgedCare')</option>
                                        <option value="Pharma">@lang('app.industryPharma')</option>
                                        <option value="Real Estate">@lang('app.industryRealEstate')</option>
                                        <option value="Repair & Maintenance Services (Electrical/Plumbing/AC)">@lang('app.industryRepairMaintenanceServices')</option>
                                        <option value="Restaurants & Cafes">@lang('app.industryRestaurantsCafes')</option>
                                        <option value="Retail (Apparel)">@lang('app.industryRetailApparel')</option>
                                        <option value="Retail (Electronics)">@lang('app.industryRetailElectronics')</option>
                                        <option value="Retail (Showrooms & Specialty Stores)">@lang('app.industryRetailShowroomsSpecialtyStores')</option>
                                        <option value="Schools & Colleges">@lang('app.industrySchoolsColleges')</option>
                                        <option value="Security Services">@lang('app.industrySecurityServices')</option>
                                        <option value="Software Development">@lang('app.industrySoftwareDevelopment')</option>
                                        <option value="Supermarket / Grocery">@lang('app.industrySupermarketGrocery')</option>
                                        <option value="Technical Support">@lang('app.industryTechnicalSupport')</option>
                                        <option value="Textiles & Garments">@lang('app.industryTextilesGarments')</option>
                                        <option value="Training Institutes">@lang('app.industryTrainingInstitutes')</option>
                                        <option value="Transport (Drivers)">@lang('app.industryTransportDrivers')</option>
                                        <option value="Warehousing">@lang('app.industryWarehousing')</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="on_role_off_role" :fieldLabel="__('app.onRoleOffRole')">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="on_role_off_role" id="on_role_off_role">
                                        <option value="">@lang('app.select')</option>
                                        <option value="On Role">@lang('app.onRole')</option>
                                        <option value="Off Role">@lang('app.offRole')</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="work_preferred_country" :fieldLabel="__('app.preferredCountry')">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="work_preferred_country" id="work_preferred_country">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Australia">@lang('app.countryAustralia')</option>
                                        <option value="New Zealand">@lang('app.countryNewZealand')</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="work_category" :fieldLabel="__('app.workCategory')">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="work_category" id="work_category">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Skilled">@lang('app.skilled')</option>
                                        <option value="Semi-Skilled">@lang('app.semiSkilled')</option>
                                        <option value="Unskilled">@lang('app.unskilled')</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="work_subclass" :fieldLabel="__('app.subclass')">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="work_subclass" id="work_subclass">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Work Visa - Temporary Skill Shortage Visa (Subclass 482)">Work Visa - Temporary Skill Shortage Visa (Subclass 482)</option>
                                        <option value="Work Visa - Skilled Work Regional Visa (Australia) (Subclass 491)">Work Visa - Skilled Work Regional Visa (Australia) (Subclass 491)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Student Visa Section -->
                        <div id="studentSection" class="form-section d-none">
                            <div class="row">
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="preferred_course" :fieldLabel="__('app.preferredCourse')">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="preferred_course" id="preferred_course">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="student_country" :fieldLabel="__('app.preferredCountry')">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="student_country" id="student_country">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Australia">@lang('app.countryAustralia')</option>
                                        <option value="New Zealand">@lang('app.countryNewZealand')</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="university" :fieldLabel="__('app.university')">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="university" id="university">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="term_intake" :fieldLabel="__('app.termIntake')" fieldRequired="true">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="term_intake" id="term_intake">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="student_subclass" :fieldLabel="__('app.subclass')">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="student_subclass" id="student_subclass">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Student Visa (Subclass 500)">Student Visa (Subclass 500)</option>
                                        <option value="Student Visa - Temporary Graduate Visa (Australia)(Subclass 485)">Student Visa - Temporary Graduate Visa (Australia)(Subclass 485)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Passport Details Tab -->
                    <div class="tab-pane fade" id="nav-passport" role="tabpanel" aria-labelledby="nav-passport-tab">
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="passport_number" fieldLabel="Passport Number" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="passport_number" id="passport_number">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="issuing_country" fieldLabel="Issuing Country" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="issuing_country" id="issuing_country">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="city_where_issued" fieldLabel="City Where Issued" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="city_where_issued" id="city_where_issued">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="issuance_date" fieldLabel="Issue Date" fieldRequired="true">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="issuance_date" id="issuance_date" max="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="expiration_date" fieldLabel="Expiration Date" fieldRequired="true">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="expiration_date" id="expiration_date">
                                <script>
                                    $(document).ready(function() {
                                        $('#issuance_date').on('change', function() {
                                            const issuanceDate = $(this).val();
                                            if (issuanceDate) {
                                                $('#expiration_date').attr('min', issuanceDate);
                                            }
                                        });
                                    });
                                </script>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="passport_file_upload" fieldLabel="Add Passport" fieldRequired="true">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" id="passport_file_upload" name="passport_file_upload" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                            <div class="col-md-12">
                                <x-forms.label class="mt-3" fieldId="lost_passport_history" fieldLabel="Lost Passport History">
                                </x-forms.label>
                                <textarea class="form-control f-14" rows="3" name="lost_passport_history" id="lost_passport_history"></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- Relative Contact Information Tab -->
                    <div class="tab-pane fade" id="nav-relative" role="tabpanel" aria-labelledby="nav-relative-tab">
                        <!-- Initial Relative Contact Section -->
                        <div class="relative-contact-section">
                            <div class="relative-contact-section-header">
                                <div class="relative-contact-section-title">Relative Contact 1</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="relative_surname" fieldLabel="Surname">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="relative_surname" id="relative_surname">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="relative_given_name" fieldLabel="Given Name">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="relative_given_name" id="relative_given_name">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="relative_organization_name" fieldLabel="Organization Name">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="relative_organization_name" id="relative_organization_name">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="relative_relationship" fieldLabel="Relationship To You">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="relative_relationship" id="relative_relationship">
                                </div>
                                <div class="col-md-12">
                                    <x-forms.label class="mt-3" fieldId="relative_contact_address" fieldLabel="Contact Address">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="relative_contact_address" id="relative_contact_address">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="relative_city" fieldLabel="City">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="relative_city" id="relative_city">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="relative_state" fieldLabel="State">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="relative_state" id="relative_state">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="relative_zip_code" fieldLabel="Zip Code">
                                    </x-forms.label>
                                    <input type="number" class="form-control height-35 f-14" name="relative_zip_code" id="relative_zip_code">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="relative_email_address" fieldLabel="Email Address">
                                    </x-forms.label>
                                    <input type="email" class="form-control height-35 f-14" name="relative_email_address" id="relative_email_address" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="relative_phone_number" fieldLabel="Phone Number">
                                    </x-forms.label>
                                    <input type="number" max="9999999999" class="form-control height-35 f-14" name="relative_phone_number" id="relative_phone_number" oninput="if(this.value.length > 10) this.value = this.value.slice(0,10);">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dynamic Relative Contact Rows Container -->
                        <div id="relative-contact-rows-container"></div>
                        
                        <!-- Add More Button -->
                        <div class="mt-4 mb-3">
                            <button type="button" class="btn btn-secondary btn-sm" id="add-more-relative">
                                <i class="fa fa-plus mr-1"></i>@lang('app.addMore')
                            </button>
                        </div>
                    </div>
                    <!-- Family Information Tab -->
                    <div class="tab-pane fade" id="nav-family" role="tabpanel" aria-labelledby="nav-family-tab">
                        <!-- Father Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.fatherDetails')</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="father_surname" fieldLabel="Father's Surname" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="father_surname" id="father_surname">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="father_given_name" fieldLabel="Father's Given Name" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="father_given_name" id="father_given_name">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="father_date_of_birth" fieldLabel="Father's Date of Birth" fieldRequired="true">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="father_date_of_birth" id="father_date_of_birth" max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="father_occupation" fieldLabel="Father's Occupation" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="father_occupation" id="father_occupation">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="father_have_passport" fieldLabel="Have Passport" fieldRequired="true">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="father_have_passport" id="father_have_passport">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="father_passport_file_container" style="display: none;">
                                <x-forms.label class="mt-3" fieldId="father_passport_file" fieldLabel="Add Father Passport">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" id="father_passport_file" name="father_passport_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Mother Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.motherDetails')</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mother_surname" fieldLabel="Mother's Surname" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mother_surname" id="mother_surname">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mother_given_name" fieldLabel="Mother's Given Name" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mother_given_name" id="mother_given_name">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mother_date_of_birth" fieldLabel="Mother's Date of Birth" fieldRequired="true">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="mother_date_of_birth" id="mother_date_of_birth" max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mother_occupation" fieldLabel="Mother's Occupation" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mother_occupation" id="mother_occupation">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mother_have_passport" fieldLabel="Have Passport" fieldRequired="true">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="mother_have_passport" id="mother_have_passport">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="mother_passport_file_container" style="display: none;">
                                <x-forms.label class="mt-3" fieldId="mother_passport_file" fieldLabel="Add Mother Passport" fieldRequired="true">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" id="mother_passport_file" name="mother_passport_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Spouse Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.spouseDetails')</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_surname" fieldLabel="Spouse's Surname">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="spouse_surname" id="spouse_surname">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_given_name" fieldLabel="Spouse's Given Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="spouse_given_name" id="spouse_given_name">
                            </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="spouse_date_of_birth" fieldLabel="Spouse's Date of Birth">
                                    </x-forms.label>
                                    <input type="date" class="form-control height-35 f-14" name="spouse_date_of_birth" id="spouse_date_of_birth" max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                                </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_country" fieldLabel="Spouse's Country">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="spouse_country" id="spouse_country">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_city_of_birth" fieldLabel="Spouse's City of Birth">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="spouse_city_of_birth" id="spouse_city_of_birth">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_have_passport" fieldLabel="Have Passport">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="spouse_have_passport" id="spouse_have_passport">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="spouse_passport_file_container" style="display: none;">
                                <x-forms.label class="mt-3" fieldId="spouse_passport_file" fieldLabel="Add Spouse Passport" fieldRequired="true">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" id="spouse_passport_file" name="spouse_passport_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                            <div class="col-md-12">
                                <x-forms.label class="mt-3" fieldId="spouse_address" fieldLabel="Spouse's Address">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="spouse_address" id="spouse_address">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_city" fieldLabel="City">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="spouse_city" id="spouse_city">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_state" fieldLabel="State">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="spouse_state" id="spouse_state">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_postal_code" fieldLabel="Postal Code">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="spouse_postal_code" id="spouse_postal_code">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_phone_number" fieldLabel="Spouse's Phone Number">
                                </x-forms.label>
                                <input type="number" maxlength="10" class="form-control height-35 f-14" name="spouse_phone_number" id="spouse_phone_number">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_education" fieldLabel="Spouse's Education">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="spouse_education" id="spouse_education">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_occupation" fieldLabel="Spouse's Occupation">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="spouse_occupation" id="spouse_occupation">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_yearly_income" fieldLabel="Spouse's Yearly Income">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="spouse_yearly_income" id="spouse_yearly_income">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_document_file" fieldLabel="Add Spouse Document">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" id="spouse_document_file" name="spouse_document_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Child Details Section -->
                        <div class="child-section">
                            <div class="child-section-header">
                                <div class="child-section-title">@lang('app.child') 1</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="child_name" fieldLabel="Child's Name">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="child_name" id="child_name">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="child_age" fieldLabel="Child's Age">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="child_age" id="child_age">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="child_date_of_birth" fieldLabel="Date of Birth">
                                    </x-forms.label>
                                    <input type="date" class="form-control height-35 f-14" name="child_date_of_birth" id="child_date_of_birth" max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="child_city_of_birth" fieldLabel="City of Birth">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="child_city_of_birth" id="child_city_of_birth">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="child_gender" fieldLabel="Gender">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="child_gender" id="child_gender">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Prefer not to say">Prefer not to say</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="child_document_file" fieldLabel="Add Child Document">
                                    </x-forms.label>
                                    <input class="form-control height-35 f-14" type="file" id="child_document_file" name="child_document_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="child_have_passport" fieldLabel="Have Passport">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="child_have_passport" id="child_have_passport">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="col-md-3" id="child_passport_file_container" style="display: none;">
                                    <x-forms.label class="mt-3" fieldId="child_passport_file" fieldLabel="Add Child Passport" fieldRequired="true">
                                    </x-forms.label>
                                    <input class="form-control height-35 f-14" type="file" id="child_passport_file" name="child_passport_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dynamic Child Rows Container -->
                        <div id="child-rows-container"></div>
                        
                        <!-- Add More Button -->
                        <div class="mt-4 mb-3">
                            <button type="button" class="btn btn-secondary btn-sm" id="add-more-child">
                                <i class="fa fa-plus mr-1"></i>@lang('app.addMoreChild')
                            </button>
                        </div>
                    </div>
                    <!-- Education Tab -->
                    <div class="tab-pane fade" id="nav-education" role="tabpanel" aria-labelledby="nav-education-tab">
                        <!-- IELTS/PTC/OET/TOEFL Exam Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.ieltsPtcOetToeflExamDetails')</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="ielts_clear_or_not" :fieldLabel="__('app.ieltsPtcOetToeflExamDetails')">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="ielts_clear_or_not" id="ielts_clear_or_not">
                                    <option value="">@lang('app.select')</option>
                                    <option value="IELTS">@lang('app.ielts')</option>
                                    <option value="PTE">@lang('app.pte')</option>
                                    <option value="OET">@lang('app.oet')</option>
                                    <option value="TOEFL">@lang('app.toefl')</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="ielts_passing_year" fieldLabel="Passing Year">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="ielts_passing_year" id="ielts_passing_year">
                                    <option value="">@lang('app.select')</option>
                                    @for($year = date('Y'); $year >= 1950; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="ielts_score" fieldLabel="Score">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="ielts_score" id="ielts_score">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="ielts_trial" fieldLabel="Trial">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="ielts_trial" id="ielts_trial">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="ielts_result_file" fieldLabel="Add IELTS/PTC/OET/TOEFL Result">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" id="ielts_result_file" name="ielts_result_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- 10th Exam Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.tenthExamDetails')</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="tenth_passing_year" fieldLabel="10th Passing Year" fieldRequired="true">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="tenth_passing_year" id="tenth_passing_year">
                                    <option value="">@lang('app.select')</option>
                                    @for($year = date('Y'); $year >= 1950; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="tenth_percentage" fieldLabel="Percentage" fieldRequired="true">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="tenth_percentage" id="tenth_percentage" min="0" max="100" step="0.01" maxlength="5">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="tenth_board_name" fieldLabel="Board Name" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="tenth_board_name" id="tenth_board_name">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="tenth_trial" fieldLabel="Trial" fieldRequired="true">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="tenth_trial" id="tenth_trial">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="tenth_result_file" fieldLabel="Add 10th Result" fieldRequired="true">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" id="tenth_result_file" name="tenth_result_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- 12th Exam Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.twelfthExamDetails')</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="twelfth_passing_year" fieldLabel="12th Passing Year">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="twelfth_passing_year" id="twelfth_passing_year">
                                    <option value="">@lang('app.select')</option>
                                    @for($year = date('Y'); $year >= 1950; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="twelfth_stream" fieldLabel="Stream">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="twelfth_stream" id="twelfth_stream">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Arts">Arts</option>
                                    <option value="Commerce">Commerce</option>
                                    <option value="Science">Science</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="twelfth_percentage" fieldLabel="Percentage">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="twelfth_percentage" id="twelfth_percentage" min="0" max="100" step="0.01" maxlength="5">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="twelfth_board_name" fieldLabel="Board Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="twelfth_board_name" id="twelfth_board_name">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="twelfth_trial" fieldLabel="Trial">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="twelfth_trial" id="twelfth_trial">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="twelfth_result_file" fieldLabel="Add 12th Result">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" id="twelfth_result_file" name="twelfth_result_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Graduation Degree Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.graduationDegreeDetails')</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="graduation_degree" :fieldLabel="__('app.graduationDegree')">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="graduation_degree" id="graduation_degree" data-live-search="true" data-live-search-placeholder="@lang('app.search')">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Bachelor of Arts (B.A. / B.A. Hons.)">@lang('app.gradDegreeBachelorOfArts')</option>
                                    <option value="Bachelor of Journalism & Mass Communication (BJMC)">@lang('app.gradDegreeBachelorOfJournalismMassCommunication')</option>
                                    <option value="Bachelor of Fine Arts (BFA)">@lang('app.gradDegreeBachelorOfFineArts')</option>
                                    <option value="Bachelor of Design (B.Des)">@lang('app.gradDegreeBachelorOfDesign')</option>
                                    <option value="Bachelor of Social Work (BSW)">@lang('app.gradDegreeBachelorOfSocialWork')</option>
                                    <option value="B.A. LL.B. (5 Years Integrated)">@lang('app.gradDegreeBALLB')</option>
                                    <option value="Bachelor of Science (B.Sc. / B.Sc. Hons.)">@lang('app.gradDegreeBachelorOfScience')</option>
                                    <option value="Bachelor of Technology / Engineering (B.Tech / B.E.)">@lang('app.gradDegreeBachelorOfTechnologyEngineering')</option>
                                    <option value="MBBS (Bachelor of Medicine & Surgery)">@lang('app.gradDegreeMBBS')</option>
                                    <option value="Bachelor of Dental Surgery (BDS)">@lang('app.gradDegreeBachelorOfDentalSurgery')</option>
                                    <option value="Bachelor of Pharmacy (B.Pharm)">@lang('app.gradDegreeBachelorOfPharmacy')</option>
                                    <option value="Bachelor of Computer Applications (BCA)">@lang('app.gradDegreeBachelorOfComputerApplications')</option>
                                    <option value="Bachelor of Physiotherapy (BPT)">@lang('app.gradDegreeBachelorOfPhysiotherapy')</option>
                                    <option value="B.Sc Nursing">@lang('app.gradDegreeBScNursing')</option>
                                    <option value="Bachelor of Computer Science / B.Sc IT / BCS">@lang('app.gradDegreeBachelorOfComputerScience')</option>
                                    <option value="Bachelor of Commerce (B.Com / B.Com Hons.)">@lang('app.gradDegreeBachelorOfCommerce')</option>
                                    <option value="Bachelor of Business Administration (BBA)">@lang('app.gradDegreeBachelorOfBusinessAdministration')</option>
                                    <option value="Bachelor of Management Studies (BMS)">@lang('app.gradDegreeBachelorOfManagementStudies')</option>
                                    <option value="Bachelor of Business Economics (BBE)">@lang('app.gradDegreeBachelorOfBusinessEconomics')</option>
                                    <option value="Integrated B.Com-LL.B.">@lang('app.gradDegreeIntegratedBComLLB')</option>
                                    <option value="Bachelor of Law (LL.B.) – 3-Year Degree">@lang('app.gradDegreeBachelorOfLaw')</option>
                                    <option value="Bachelor of Hotel Management (BHM) / B.Sc Hospitality">@lang('app.gradDegreeBachelorOfHotelManagement')</option>
                                    <option value="Bachelor of Architecture (B.Arch)">@lang('app.gradDegreeBachelorOfArchitecture')</option>
                                    <option value="Bachelor of Elementary Education (B.El.Ed.)">@lang('app.gradDegreeBachelorOfElementaryEducation')</option>
                                    <option value="Chartered Accountancy (CA)">@lang('app.gradDegreeCharteredAccountancy')</option>
                                    <option value="Company Secretary (CS)">@lang('app.gradDegreeCompanySecretary')</option>
                                    <option value="Cost & Management Accountant (CMA)">@lang('app.gradDegreeCostManagementAccountant')</option>
                                    <option value="Bachelor of Vocational Studies (B.Voc)">@lang('app.gradDegreeBachelorOfVocationalStudies')</option>
                                    <option value="Integrated BBA-LL.B. / B.A.-LL.B. / B.Sc.-LL.B.">@lang('app.gradDegreeIntegratedBBALLB')</option>
                                    <option value="Degree Name (Custom)">@lang('app.gradDegreeDegreeNameCustom')</option>
                                    <option value="Other">@lang('app.gradDegreeOther')</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="graduation_university_name" fieldLabel="University Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="graduation_university_name" id="graduation_university_name">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="graduation_percentage" fieldLabel="Percentage">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="graduation_percentage" id="graduation_percentage" min="0" max="100" step="0.01" maxlength="5">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="graduation_passing_year" fieldLabel="Passing Year">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="graduation_passing_year" id="graduation_passing_year">
                                    <option value="">@lang('app.select')</option>
                                    @for($year = date('Y'); $year >= 1950; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="graduation_trial" fieldLabel="Trial">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="graduation_trial" id="graduation_trial">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="graduation_result_file" fieldLabel="Add Graduation Result">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" id="graduation_result_file" name="graduation_result_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Post Graduation Degree Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.postGraduationDegreeDetails')</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="post_graduation_degree" :fieldLabel="__('app.postGraduationDegree')">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="post_graduation_degree" id="post_graduation_degree" data-live-search="true" data-live-search-placeholder="@lang('app.search')">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Master of Business Administration (MBA)">@lang('app.postGradDegreeMBA')</option>
                                    <option value="Post Graduate Diploma in Management (PGDM)">@lang('app.postGradDegreePGDM')</option>
                                    <option value="Master of Commerce (M.Com)">@lang('app.postGradDegreeMCom')</option>
                                    <option value="Master of Business Economics (MBE)">@lang('app.postGradDegreeMBE')</option>
                                    <option value="Master of Science (M.Sc.)">@lang('app.postGradDegreeMSc')</option>
                                    <option value="Master of Technology (M.Tech)">@lang('app.postGradDegreeMTech')</option>
                                    <option value="Master of Computer Applications (MCA)">@lang('app.postGradDegreeMCA')</option>
                                    <option value="Master of Arts (M.A.)">@lang('app.postGradDegreeMA')</option>
                                    <option value="Master in Social Work (MSW)">@lang('app.postGradDegreeMSW')</option>
                                    <option value="Master of Design (M.Des)">@lang('app.postGradDegreeMDes')</option>
                                    <option value="Master of Laws (LL.M.)">@lang('app.postGradDegreeLLM')</option>
                                    <option value="Master of Education (M.Ed.)">@lang('app.postGradDegreeMEd')</option>
                                    <option value="Master of Pharmacy (M.Pharm)">@lang('app.postGradDegreeMPharm')</option>
                                    <option value="Master of Architecture (M.Arch)">@lang('app.postGradDegreeMArch')</option>
                                    <option value="Doctor of Medicine (M.D.) / Master of Surgery (M.S.)">@lang('app.postGradDegreeMDMS')</option>
                                    <option value="Other">@lang('app.postGradDegreeOther')</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="post_graduation_university_name" fieldLabel="University Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="post_graduation_university_name" id="post_graduation_university_name">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="post_graduation_percentage" fieldLabel="Percentage">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="post_graduation_percentage" id="post_graduation_percentage" min="0" max="100" step="0.01" maxlength="5">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="post_graduation_passing_year" fieldLabel="Passing Year">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="post_graduation_passing_year" id="post_graduation_passing_year">
                                    <option value="">@lang('app.select')</option>
                                    @for($year = date('Y'); $year >= 1950; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="post_graduation_trial" fieldLabel="Trial">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="post_graduation_trial" id="post_graduation_trial">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="post_graduation_result_file" fieldLabel="Add Post Graduation Result">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" id="post_graduation_result_file" name="post_graduation_result_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Other Degree Details Section -->
                        <div class="other-degree-section">
                            <div class="other-degree-section-header">
                                <div class="other-degree-section-title">@lang('app.otherDegreeDetails') 1</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="other_degree" fieldLabel="Other Degree">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="other_degree" id="other_degree">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="other_degree_university_name" fieldLabel="University Name">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="other_degree_university_name" id="other_degree_university_name">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="other_degree_percentage" fieldLabel="Percentage">
                                    </x-forms.label>
                                    <input type="number" class="form-control height-35 f-14" name="other_degree_percentage" id="other_degree_percentage" min="0" max="100" step="0.01" maxlength="5">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="other_degree_passing_year" fieldLabel="Passing Year">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="other_degree_passing_year" id="other_degree_passing_year">
                                        <option value="">@lang('app.select')</option>
                                        @for($year = date('Y'); $year >= 1950; $year--)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="other_degree_trial" fieldLabel="Trial">
                                    </x-forms.label>
                                    <input type="number" class="form-control height-35 f-14" name="other_degree_trial" id="other_degree_trial">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="other_degree_result_file" fieldLabel="Add Other Degree Result">
                                    </x-forms.label>
                                    <input class="form-control height-35 f-14" type="file" id="other_degree_result_file" name="other_degree_result_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dynamic Other Degree Rows Container -->
                        <div id="other-degree-rows-container"></div>
                        
                        <!-- Add More Button -->
                        <div class="mt-4 mb-3">
                            <button type="button" class="btn btn-secondary btn-sm" id="add-more-education">
                                <i class="fa fa-plus mr-1"></i>@lang('app.addMore')
                            </button>
                        </div>
                    </div>
                    <!-- Professional Experience Tab -->
                    <div class="tab-pane fade" id="nav-experience" role="tabpanel" aria-labelledby="nav-experience-tab">
                        <!-- Job 1 Section -->
                        <div class="job-section">
                            <div class="job-section-header">
                                <div class="job-section-title">@lang('app.job') 1</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="job_duration_from" fieldLabel="Duration - From">
                                    </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="job_duration_from" id="job_duration_from" max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_duration_to" fieldLabel="Duration - To">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="job_duration_to" id="job_duration_to" max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="job_country" fieldLabel="Country">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="job_country" id="job_country">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="job_designation" fieldLabel="Designation">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="job_designation" id="job_designation">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="job_company_name" fieldLabel="Company Name">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="job_company_name" id="job_company_name">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="job_salary" fieldLabel="Salary">
                                    </x-forms.label>
                                    <input type="number" class="form-control height-35 f-14" name="job_salary" id="job_salary">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="job_offer_letter_file" fieldLabel="Add Offerletter">
                                    </x-forms.label>
                                    <input class="form-control height-35 f-14" type="file" id="job_offer_letter_file" name="job_offer_letter_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="job_experience_letter_file" fieldLabel="Add Experience letter">
                                    </x-forms.label>
                                    <input class="form-control height-35 f-14" type="file" id="job_experience_letter_file" name="job_experience_letter_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dynamic Job Rows Container -->
                        <div id="job-rows-container"></div>
                        
                        <!-- Add More Button -->
                        <div class="mt-4 mb-3">
                            <button type="button" class="btn btn-secondary btn-sm" id="add-more-job">
                                <i class="fa fa-plus mr-1"></i>@lang('app.addMoreJob')
                            </button>
                        </div>
                    </div>
                    <!-- Property Details Tab -->
                    <div class="tab-pane fade" id="nav-property" role="tabpanel" aria-labelledby="nav-property-tab">
                        <p class="small-text mt-2 mb-3">Enter the valuation for each property type. The total valuation will be calculated automatically. If you do not have a property, enter a value of 0.</p>
                        
                        <!-- Property Valuation Inputs -->
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_home" fieldLabel="Home" fieldRequired="true">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 valuation-input" name="property_home" id="property_home">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_land" fieldLabel="Land" fieldRequired="true">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 valuation-input" name="property_land" id="property_land">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_plot" fieldLabel="Plot" fieldRequired="true">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 valuation-input" name="property_plot" id="property_plot">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_commercials" fieldLabel="Commercials" fieldRequired="true">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 valuation-input" name="property_commercials" id="property_commercials">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_other" fieldLabel="Other" fieldRequired="true">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 valuation-input" name="property_other" id="property_other">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_shop" fieldLabel="Shop" fieldRequired="true">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 valuation-input" name="property_shop" id="property_shop">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_gold" fieldLabel="Gold" fieldRequired="true">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 valuation-input" name="property_gold" id="property_gold">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_silver" fieldLabel="Silver" fieldRequired="true">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 valuation-input" name="property_silver" id="property_silver">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="total_valuation" fieldLabel="Total Valuation">
                                </x-forms.label>
                                <input type="number" id="total_valuation" class="form-control height-35 f-14" readonly placeholder="@lang('app.autoCalculated')" name="total_valuation">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Loan Info Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.loanInformation')</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="total_loan_value" fieldLabel="Total Loan Value">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="total_loan_value" id="total_loan_value">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="loan_years" fieldLabel="Loan Years">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="loan_years" id="loan_years">
                                    <option value="">@lang('app.select')</option>
                                    @for($year = date('Y'); $year >= 1950; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="loan_availed_on" fieldLabel="Loan Availed On">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="loan_availed_on" id="loan_availed_on">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="valuation_report_file" fieldLabel="Add Valuation Report">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" id="valuation_report_file" name="valuation_report_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                        </div>
                    </div>
                    <!-- Financial Status Tab -->
                    <div class="tab-pane fade" id="nav-financial" role="tabpanel" aria-labelledby="nav-financial-tab">
                        <p class="small-text mt-2 mb-3">@lang('app.enterIncomeForEachUsers')</p>
                        
                        <!-- Income Inputs -->
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="father_income" fieldLabel="Father's Income">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 income-input" name="father_income" id="father_income">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mother_income" fieldLabel="Mother's Income">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 income-input" name="mother_income" id="mother_income">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="candidate_income" fieldLabel="Candidate's Income">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 income-input" name="candidate_income" id="candidate_income">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_income" fieldLabel="Spouse Income">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 income-input" name="spouse_income" id="spouse_income">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="father_income_document_file" fieldLabel="Add Father's Income Document">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" id="father_income_document_file" name="father_income_document_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mother_income_document_file" fieldLabel="Add Mother's Income Document">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" id="mother_income_document_file" name="mother_income_document_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="candidate_income_document_file" fieldLabel="Add Candidate's Income Document">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" id="candidate_income_document_file" name="candidate_income_document_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_income_document_file" fieldLabel="Add Spouse Income Document">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" id="spouse_income_document_file" name="spouse_income_document_file" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="total_income" fieldLabel="Total Income">
                                </x-forms.label>
                                <input type="number" id="total_income" class="form-control height-35 f-14" readonly placeholder="@lang('app.autoCalculated')" name="total_income">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-travel" role="tabpanel" aria-labelledby="nav-travel-tab">
                        <div class="p-20">
                            <p class="text-muted">Travel Details content will be added here.</p>
                        </div>
                    </div>
                </div>

                <!-- Bottom Buttons -->
                <div class="row align-items-center justify-content-between g-2 p-20 border-top-grey">
                    <div class="col-12 col-md-auto d-flex flex-column flex-md-row">
                        <button type="button" class="btn btn-secondary mr-2" id="btn-previous">
                            <i class="fa fa-arrow-left mr-1"></i>@lang('app.previous')
                        </button>
                    </div>
                    <div class="col-12 col-md-auto d-flex flex-column flex-md-row gap-2 justify-content-md-end">
                        <x-forms.button-primary id="save-lead-form" icon="check">
                            @lang('app.saveAndNext')
                        </x-forms.button-primary>
                        <x-forms.button-cancel :link="route('lead-list.index')" class="border-0">
                            @lang('app.cancel')
                        </x-forms.button-cancel>
                    </div>
                </div>
            </x-form>
        </div>
    </div>
    <!-- CONTENT WRAPPER END -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize select picker
            function initializeSelectPickers() {
                $('.select-picker').each(function() {
                    if (!$(this).data('selectpicker')) {
                        $(this).selectpicker();
                    }
                });
            }
            
            // Initial initialization
            initializeSelectPickers();
            
            // Re-initialize after a short delay to catch any dynamically loaded content
            setTimeout(function() {
                initializeSelectPickers();
            }, 300);
            
            // Re-initialize after a longer delay for any late-loading content
            setTimeout(function() {
                initializeSelectPickers();
            }, 1000);
            
            // Use MutationObserver to detect when new select fields are added
            if (typeof MutationObserver !== 'undefined') {
                const observer = new MutationObserver(function(mutations) {
                    let shouldReinit = false;
                    mutations.forEach(function(mutation) {
                        if (mutation.addedNodes.length > 0) {
                            mutation.addedNodes.forEach(function(node) {
                                if (node.nodeType === 1) { // Element node
                                    if ($(node).hasClass('select-picker') || $(node).find('.select-picker').length > 0) {
                                        shouldReinit = true;
                                    }
                                }
                            });
                        }
                    });
                    if (shouldReinit) {
                        setTimeout(function() {
                            initializeSelectPickers();
                        }, 100);
                    }
                });
                
                // Observe the document body for changes
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            }

            // Handle mailing address same as home address checkbox
            $('#mailing_same_as_home').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#mailing_address').val($('#home_address').val());
                    $('#mailing_city').val($('#home_city').val());
                    $('#mailing_state').val($('#home_state').val());
                    $('#mailing_pin_code').val($('#home_pin_code').val());
                    
                    // Disable mailing address fields
                    $('#mailing_address, #mailing_city, #mailing_state, #mailing_pin_code').prop('disabled', true);
                } else {
                    // Enable mailing address fields
                    $('#mailing_address, #mailing_city, #mailing_state, #mailing_pin_code').prop('disabled', false);
                }
            });

            // Auto-fill mailing address when home address changes (if checkbox is checked)
            $('#home_address, #home_city, #home_state, #home_pin_code').on('input', function() {
                if ($('#mailing_same_as_home').is(':checked')) {
                    $('#mailing_address').val($('#home_address').val());
                    $('#mailing_city').val($('#home_city').val());
                    $('#mailing_state').val($('#home_state').val());
                    $('#mailing_pin_code').val($('#home_pin_code').val());
                }
            });

            // Handle visa status radio buttons
            $('input[name="visa_status"]').on('change', function() {
                const selectedValue = $(this).val();
                
                if (selectedValue === 'granted') {
                    // Show Visa Granted fields, hide Visa Refusal fields
                    $('#visa_granted_fields').show();
                    $('#visa_refusal_fields').hide();
                } else if (selectedValue === 'refusal') {
                    // Show Visa Refusal fields, hide Visa Granted fields
                    $('#visa_granted_fields').hide();
                    $('#visa_refusal_fields').show();
                } else {
                    // If neither is selected, hide all fields
                    $('#visa_granted_fields').hide();
                    $('#visa_refusal_fields').hide();
                }
            });
            
            // Initially hide all dependent fields
            $('#visa_granted_fields').hide();
            $('#visa_refusal_fields').hide();

            // Add more visa refusal functionality
            let visaRefusalCount = 0;
            
            // Use event delegation to handle dynamically added buttons
            $(document).on('click', '#add-more-visa-refusal', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                visaRefusalCount++;
                const newRow = `
                    <div class="row mt-3 visa-refusal-row" id="visa-refusal-row-${visaRefusalCount}">
                        <div class="col-md-3">
                            <x-forms.label class="mt-3" fieldId="visa_rejection_date_${visaRefusalCount}" :fieldLabel="__('app.visaRejectionDate')" fieldRequired="true">
                            </x-forms.label>
                            <input type="month" class="form-control height-35 f-14" name="visa_rejection_date[]" id="visa_rejection_date_${visaRefusalCount}" max="{{ date('Y-m') }}">
                        </div>
                        <div class="col-md-3">
                            <x-forms.label class="mt-3" fieldId="visa_refusal_category_${visaRefusalCount}" :fieldLabel="__('app.visaCategory')" fieldRequired="true">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="visa_refusal_category[]" id="visa_refusal_category_${visaRefusalCount}">
                        </div>
                        <div class="col-md-5">
                            <x-forms.label class="mt-3" fieldId="visa_refusal_reason_${visaRefusalCount}" :fieldLabel="__('app.reason')" fieldRequired="true">
                            </x-forms.label>
                            <textarea class="form-control f-14" rows="2" name="visa_refusal_reason[]" id="visa_refusal_reason_${visaRefusalCount}"></textarea>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-visa-refusal" data-row-id="${visaRefusalCount}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                
                // Append to the visa-refusal-rows-container
                $('#visa-refusal-rows-container').append(newRow);
                
                // Reinitialize select picker for the new row (if any)
                setTimeout(function() {
                    $('.select-picker').each(function() {
                        if (!$(this).data('selectpicker')) {
                            $(this).selectpicker();
                        } else {
                            $(this).selectpicker('refresh');
                        }
                    });
                }, 100);
            });

            // Remove visa refusal row
            $(document).on('click', '.remove-visa-refusal', function() {
                const rowId = $(this).data('row-id');
                $(`#visa-refusal-row-${rowId}`).remove();
            });

            // Form submission
            $('#save-lead-form').on('click', function() {
                // Form validation and submission logic will be added here
                // This should be handled by the ajax-form class
            });

            // Tab navigation - Previous button (old handler - will be replaced by the one below)
            // This is kept for backward compatibility but the main handler is below

            // Handle visa type radio buttons for Client Preference tab
            $('input[name="visa_type"]').on('change', function() {
                const selectedValue = $(this).val();
                const previousValue = $(this).data('previous-value');
                
                // Clear form data for other visa types when switching
                if (previousValue && previousValue !== selectedValue) {
                    if (previousValue === 'pr') {
                        // Clear PR fields
                        $('#skill_assessment_letter, #pr_preferred_country, #pr_preferred_state, #pr_family, #pr_subclass').val('').selectpicker('refresh');
                        $('#pr_assessment_letter_file').val('');
                    } else if (previousValue === 'visit') {
                        // Clear Visit Visa fields
                        $('#purpose_of_visit, #visit_family, #visit_preferred_country, #visit_preferred_state, #visit_subclass').val('').selectpicker('refresh');
                    } else if (previousValue === 'work') {
                        // Clear Work Permit fields
                        $('#preferred_designation, #work_industry, #work_preferred_country, #work_preferred_state, #work_subclass').val('').selectpicker('refresh');
                    } else if (previousValue === 'student') {
                        // Clear Student Visa fields
                        $('#preferred_course, #student_country, #university, #term_intake, #student_subclass').val('').selectpicker('refresh');
                    }
                }
                
                // Store current value as previous
                $(this).data('previous-value', selectedValue);
                
                // Hide all sections
                $('#prSection, #visitSection, #workSection, #studentSection').addClass('d-none');
                
                // Show selected section
                if (selectedValue === 'pr') {
                    $('#prSection').removeClass('d-none');
                } else if (selectedValue === 'visit') {
                    $('#visitSection').removeClass('d-none');
                } else if (selectedValue === 'work') {
                    $('#workSection').removeClass('d-none');
                } else if (selectedValue === 'student') {
                    $('#studentSection').removeClass('d-none');
                }
                
                // Reinitialize select picker for the visible section
                setTimeout(function() {
                    $('.select-picker').each(function() {
                        if (!$(this).data('selectpicker')) {
                            $(this).selectpicker();
                        } else {
                            $(this).selectpicker('refresh');
                        }
                    });
                }, 100);
            });
            
            // Handle PR Assessment Letter file input change
            $('#pr_assessment_letter_file').on('change', function() {
                const file = this.files[0];
                if (file) {
                    // Remove hidden input when new file is selected
                    $('#pr_assessment_letter_file_hidden').remove();
                }
            });

            // Add More Relative Contact functionality
            let relativeContactCount = 0;
            
            // Function to get next relative contact number
            function getNextRelativeContactNumber() {
                const existingRows = $('.relative-contact-row').length;
                return existingRows + 2; // +2 because we have initial section (1) and existing rows
            }
            
            $('#add-more-relative').on('click', function() {
                relativeContactCount++;
                const nextNumber = getNextRelativeContactNumber();
                const newRow = `
                    <div class="relative-contact-row" id="relative-contact-row-${relativeContactCount}">
                        <div class="relative-contact-row-header">
                            <div class="relative-contact-row-number">Relative Contact ${nextNumber}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_surname_${relativeContactCount}" fieldLabel="Surname">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_surname[]" id="relative_surname_${relativeContactCount}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_given_name_${relativeContactCount}" fieldLabel="Given Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_given_name[]" id="relative_given_name_${relativeContactCount}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_organization_name_${relativeContactCount}" fieldLabel="Organization Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_organization_name[]" id="relative_organization_name_${relativeContactCount}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_relationship_${relativeContactCount}" fieldLabel="Relationship To You">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_relationship[]" id="relative_relationship_${relativeContactCount}">
                            </div>
                            <div class="col-md-12">
                                <x-forms.label class="mt-3" fieldId="relative_contact_address_${relativeContactCount}" fieldLabel="Contact Address">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_contact_address[]" id="relative_contact_address_${relativeContactCount}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_city_${relativeContactCount}" fieldLabel="City">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_city[]" id="relative_city_${relativeContactCount}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_state_${relativeContactCount}" fieldLabel="State">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_state[]" id="relative_state_${relativeContactCount}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_zip_code_${relativeContactCount}" fieldLabel="Zip Code">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="relative_zip_code[]" id="relative_zip_code_${relativeContactCount}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_email_address_${relativeContactCount}" fieldLabel="Email Address">
                                </x-forms.label>
                                <input type="email" class="form-control height-35 f-14" name="relative_email_address[]" id="relative_email_address_${relativeContactCount}" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_phone_number_${relativeContactCount}" fieldLabel="Phone Number">
                                </x-forms.label>
                                <input type="number" max="9999999999" class="form-control height-35 f-14" name="relative_phone_number[]" id="relative_phone_number_${relativeContactCount}" oninput="if(this.value.length > 10) this.value = this.value.slice(0,10);">
                            </div>
                            <div class="col-md-12 mt-3">
                                <button type="button" class="btn btn-danger btn-sm remove-relative-contact" data-row-id="${relativeContactCount}">
                                    <i class="fa fa-trash mr-1"></i>Remove
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                // Append to the relative-contact-rows-container
                $('#relative-contact-rows-container').append(newRow);
                
                // Reinitialize select picker for the new row
                setTimeout(function() {
                    $('.select-picker').each(function() {
                        if (!$(this).data('selectpicker')) {
                            $(this).selectpicker();
                        } else {
                            $(this).selectpicker('refresh');
                        }
                    });
                }, 100);
            });

            // Remove relative contact row
            $(document).on('click', '.remove-relative-contact', function() {
                const rowId = $(this).data('row-id');
                $(`#relative-contact-row-${rowId}`).remove();
            });

            // Add More Child functionality
            function getNextChildNumber() {
                const existingRows = $('.child-row').length;
                return existingRows + 2; // +2 because we have initial section (1) and existing rows
            }
            
            $('#add-more-child').on('click', function() {
                const nextChildNum = getNextChildNumber();
                const newRow = `
                    <div class="child-row" id="child-row-${nextChildNum}">
                        <div class="child-row-header">
                            <div class="child-row-number">Child ${nextChildNum}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="child_name_${nextChildNum}" fieldLabel="Child's Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="child_name[]" id="child_name_${nextChildNum}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="child_age_${nextChildNum}" fieldLabel="Child's Age">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="child_age[]" id="child_age_${nextChildNum}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="child_date_of_birth_${nextChildNum}" fieldLabel="Date of Birth">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="child_date_of_birth[]" id="child_date_of_birth_${nextChildNum}" max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="child_city_of_birth_${nextChildNum}" fieldLabel="City of Birth">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="child_city_of_birth[]" id="child_city_of_birth_${nextChildNum}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="child_have_passport_${nextChildNum}" fieldLabel="Have Passport">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="child_have_passport[]" id="child_have_passport_${nextChildNum}">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="child_gender_${nextChildNum}" fieldLabel="Gender">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="child_gender[]" id="child_gender_${nextChildNum}">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Prefer not to say">Prefer not to say</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="child_document_file_${nextChildNum}" fieldLabel="Add Child Document">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" name="child_document_file[]" id="child_document_file_${nextChildNum}" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                            <div class="col-md-3" id="child_passport_file_container_${nextChildNum}" style="display: none;">
                                <x-forms.label class="mt-3" fieldId="child_passport_file_${nextChildNum}" fieldLabel="Add Child Passport" fieldRequired="true">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" name="child_passport_file[]" id="child_passport_file_${nextChildNum}" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                            <div class="col-md-12 mt-3">
                                <button type="button" class="btn btn-danger btn-sm remove-child" data-row-id="${nextChildNum}">
                                    <i class="fa fa-trash mr-1"></i>Remove
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                // Append to the child-rows-container
                $('#child-rows-container').append(newRow);
                
                // Reinitialize select picker for the new row
                setTimeout(function() {
                    $('.select-picker').each(function() {
                        if (!$(this).data('selectpicker')) {
                            $(this).selectpicker();
                        } else {
                            $(this).selectpicker('refresh');
                        }
                    });
                }, 100);
            });

            // Remove child row
            $(document).on('click', '.remove-child', function() {
                const rowId = $(this).data('row-id');
                $(`#child-row-${rowId}`).remove();
            });

            // Add More Education (Other Degree) functionality
            function getNextOtherDegreeNumber() {
                const existingRows = $('.other-degree-row').length;
                return existingRows + 2; // +2 because we have initial section (1) and existing rows
            }
            
            $('#add-more-education').on('click', function() {
                const nextDegreeNum = getNextOtherDegreeNumber();
                const newRow = `
                    <div class="other-degree-row" id="other-degree-row-${nextDegreeNum}">
                        <div class="other-degree-row-header">
                            <div class="other-degree-row-number">Other Degree ${nextDegreeNum}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_degree_${nextDegreeNum}" fieldLabel="Other Degree">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="other_degree[]" id="other_degree_${nextDegreeNum}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_degree_university_name_${nextDegreeNum}" fieldLabel="University Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="other_degree_university_name[]" id="other_degree_university_name_${nextDegreeNum}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_degree_percentage_${nextDegreeNum}" fieldLabel="Percentage">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="other_degree_percentage[]" id="other_degree_percentage_${nextDegreeNum}" min="0" max="100" step="0.01" maxlength="5">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_degree_passing_year_${nextDegreeNum}" fieldLabel="Passing Year">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="other_degree_passing_year[]" id="other_degree_passing_year_${nextDegreeNum}">
                                    <option value="">Select</option>
                                    ${(() => {
                                        let yearOptions = '';
                                        const currentYear = new Date().getFullYear();
                                        for (let year = currentYear; year >= 1950; year--) {
                                            yearOptions += `<option value="${year}">${year}</option>`;
                                        }
                                        return yearOptions;
                                    })()}
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_degree_trial_${nextDegreeNum}" fieldLabel="Trial">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="other_degree_trial[]" id="other_degree_trial_${nextDegreeNum}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_degree_result_file_${nextDegreeNum}" fieldLabel="Add Other Degree Result">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" name="other_degree_result_file[]" id="other_degree_result_file_${nextDegreeNum}" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                            <div class="col-md-12 mt-3">
                                <button type="button" class="btn btn-danger btn-sm remove-other-degree" data-row-id="${nextDegreeNum}">
                                    <i class="fa fa-trash mr-1"></i>Remove
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                // Append to the other-degree-rows-container
                $('#other-degree-rows-container').append(newRow);
                
                // Reinitialize select picker for the new row
                setTimeout(function() {
                    $('.select-picker').each(function() {
                        if (!$(this).data('selectpicker')) {
                            $(this).selectpicker();
                        } else {
                            $(this).selectpicker('refresh');
                        }
                    });
                }, 100);
            });

            // Remove other degree row
            $(document).on('click', '.remove-other-degree', function() {
                const rowId = $(this).data('row-id');
                $(`#other-degree-row-${rowId}`).remove();
            });

            // Add More Job functionality
            function getNextJobNumber() {
                const existingRows = $('.job-row').length;
                return existingRows + 2; // +2 because we have initial section (1) and existing rows
            }
            
            $('#add-more-job').on('click', function() {
                const nextJobNum = getNextJobNumber();
                const newRow = `
                    <div class="job-row" id="job-row-${nextJobNum}">
                        <div class="job-row-header">
                            <div class="job-row-number">Job ${nextJobNum}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_duration_from_${nextJobNum}" fieldLabel="Duration - From">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="job_duration_from[]" id="job_duration_from_${nextJobNum}" max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_duration_to_${nextJobNum}" fieldLabel="Duration - To">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="job_duration_to[]" id="job_duration_to_${nextJobNum}" max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_country_${nextJobNum}" fieldLabel="Country">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="job_country[]" id="job_country_${nextJobNum}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_designation_${nextJobNum}" fieldLabel="Designation">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="job_designation[]" id="job_designation_${nextJobNum}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_company_name_${nextJobNum}" fieldLabel="Company Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="job_company_name[]" id="job_company_name_${nextJobNum}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_salary_${nextJobNum}" fieldLabel="Salary">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="job_salary[]" id="job_salary_${nextJobNum}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_offer_letter_file_${nextJobNum}" fieldLabel="Add Offerletter">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" name="job_offer_letter_file[]" id="job_offer_letter_file_${nextJobNum}" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_experience_letter_file_${nextJobNum}" fieldLabel="Add Experience letter">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" name="job_experience_letter_file[]" id="job_experience_letter_file_${nextJobNum}" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
                            </div>
                            <div class="col-md-12 mt-3">
                                <button type="button" class="btn btn-danger btn-sm remove-job" data-row-id="${nextJobNum}">
                                    <i class="fa fa-trash mr-1"></i>Remove
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                // Append to the job-rows-container
                $('#job-rows-container').append(newRow);
            });

            // Remove job row
            $(document).on('click', '.remove-job', function() {
                const rowId = $(this).data('row-id');
                $(`#job-row-${rowId}`).remove();
            });

            // Auto-calculate total valuation for Property Details
            $('.valuation-input').on('input', function() {
                let total = 0;
                $('.valuation-input').each(function() {
                    const val = parseFloat($(this).val()) || 0;
                    total += val;
                });
                $('#total_valuation').val(total);
            });

            // Auto-calculate total income for Financial Status
            $('.income-input').on('input', function() {
                let total = 0;
                $('.income-input').each(function() {
                    const val = parseFloat($(this).val()) || 0;
                    total += val;
                });
                $('#total_income').val(total);
            });
            
            // Handle Father Have Passport - show/hide passport file field
            $('#father_have_passport').on('changed.bs.select', function() {
                const havePassport = $(this).val();
                if (havePassport === 'Yes') {
                    $('#father_passport_file_container').show();
                } else {
                    $('#father_passport_file_container').hide();
                    $('#father_passport_file').val('');
                }
            });
            
            // Handle Mother Have Passport - show/hide passport file field
            $('#mother_have_passport').on('changed.bs.select', function() {
                const havePassport = $(this).val();
                if (havePassport === 'Yes') {
                    $('#mother_passport_file_container').show();
                } else {
                    $('#mother_passport_file_container').hide();
                    $('#mother_passport_file').val('');
                }
            });
            
            // Handle Spouse Have Passport - show/hide passport file field
            $('#spouse_have_passport').on('changed.bs.select', function() {
                const havePassport = $(this).val();
                if (havePassport === 'Yes') {
                    $('#spouse_passport_file_container').show();
                } else {
                    $('#spouse_passport_file_container').hide();
                    $('#spouse_passport_file').val('');
                }
            });
            
            // Handle Child Have Passport - show/hide passport file field
            $('#child_have_passport').on('changed.bs.select', function() {
                const havePassport = $(this).val();
                if (havePassport === 'Yes') {
                    $('#child_passport_file_container').show();
                } else {
                    $('#child_passport_file_container').hide();
                    $('#child_passport_file').val('');
                }
            });
            
            // Handle dynamic child passport fields
            $(document).on('changed.bs.select', '[id^="child_have_passport"]', function() {
                const havePassport = $(this).val();
                const childNum = $(this).attr('id').replace('child_have_passport_', '').replace('child_have_passport', '');
                const containerId = childNum ? `#child_passport_file_container_${childNum}` : '#child_passport_file_container';
                const fileId = childNum ? `#child_passport_file_${childNum}` : '#child_passport_file';
                
                if (havePassport === 'Yes') {
                    $(containerId).show();
                } else {
                    $(containerId).hide();
                    $(fileId).val('');
                }
            });
            
            // Function to check and show passport fields based on "Have Passport" values
            function checkAndShowPassportFields() {
                // Check Father passport
                const fatherHavePassport = getSelectValue('#father_have_passport');
                if (fatherHavePassport === 'Yes') {
                    $('#father_passport_file_container').show();
                } else {
                    $('#father_passport_file_container').hide();
                }
                
                // Check Mother passport
                const motherHavePassport = getSelectValue('#mother_have_passport');
                if (motherHavePassport === 'Yes') {
                    $('#mother_passport_file_container').show();
                } else {
                    $('#mother_passport_file_container').hide();
                }
                
                // Check Spouse passport
                const spouseHavePassport = getSelectValue('#spouse_have_passport');
                if (spouseHavePassport === 'Yes') {
                    $('#spouse_passport_file_container').show();
                } else {
                    $('#spouse_passport_file_container').hide();
                }
                
                // Check Child passport
                const childHavePassport = getSelectValue('#child_have_passport');
                if (childHavePassport === 'Yes') {
                    $('#child_passport_file_container').show();
                } else {
                    $('#child_passport_file_container').hide();
                }
                
                // Check dynamic child passport fields
                $('[id^="child_have_passport"]').each(function() {
                    const childHavePassportVal = getSelectValue('#' + $(this).attr('id'));
                    const childNum = $(this).attr('id').replace('child_have_passport_', '').replace('child_have_passport', '');
                    const containerId = childNum ? `#child_passport_file_container_${childNum}` : '#child_passport_file_container';
                    
                    if (childHavePassportVal === 'Yes') {
                        $(containerId).show();
                    } else {
                        $(containerId).hide();
                    }
                });
            }
            
            // Trigger on page load if values are already set
            setTimeout(function() {
                checkAndShowPassportFields();
            }, 500);
            
            // Also trigger after selectpicker is initialized/refreshed
            setTimeout(function() {
                checkAndShowPassportFields();
            }, 1000);
            
            // Also trigger on selectpicker refresh (for when data is loaded)
            $(document).on('refreshed.bs.select', '.select-picker', function() {
                setTimeout(function() {
                    checkAndShowPassportFields();
                }, 100);
            });
            
            // Also trigger when tab is shown (in case user navigates to Step 5)
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                // Reinitialize selectpickers when tab is shown
                setTimeout(function() {
                    initializeSelectPickers();
                }, 200);
                
                if ($(e.target).attr('id') === 'nav-family-tab') {
                    setTimeout(function() {
                        checkAndShowPassportFields();
                    }, 300);
                }
            });
            
            // File size validation for all file inputs
            $(document).on('change', 'input[type="file"][data-max-size]', function() {
                const file = this.files[0];
                const maxSize = $(this).data('max-size'); // 5242880 = 5MB
                if (file && file.size > maxSize) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'File too large',
                            text: 'File size must be less than 5MB',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    } else {
                        alert('File size must be less than 5MB');
                    }
                    $(this).val('');
                }
            });

            // Function to filter states based on selected country
            function filterStatesByCountry(countrySelectId, stateSelectId) {
                const countrySelect = $('#' + countrySelectId);
                const stateSelect = $('#' + stateSelectId);
                
                countrySelect.on('changed.bs.select', function() {
                    const selectedCountry = $(this).val();
                    
                    if (selectedCountry) {
                        // Hide all state options except the "Select" option
                        stateSelect.find('option[value!=""]').each(function() {
                            const $option = $(this);
                            if ($option.data('country') === selectedCountry) {
                                $option.prop('disabled', false);
                            } else {
                                $option.prop('disabled', true);
                            }
                        });
                    } else {
                        // If no country selected, enable all states
                        stateSelect.find('option').prop('disabled', false);
                    }
                    
                    // Reset state selection and refresh selectpicker
                    stateSelect.val('').selectpicker('refresh');
                });
                
                // Also handle regular change event for compatibility
                countrySelect.on('change', function() {
                    const selectedCountry = $(this).val();
                    
                    if (selectedCountry) {
                        // Show only matching states, hide others
                        stateSelect.find('option[value!=""]').each(function() {
                            const $option = $(this);
                            if ($option.data('country') === selectedCountry) {
                                $option.prop('disabled', false).show();
                            } else {
                                $option.prop('disabled', true).hide();
                            }
                        });
                    } else {
                        // If no country selected, show all states
                        stateSelect.find('option').prop('disabled', false).show();
                    }
                    
                    // Reset state selection and refresh selectpicker
                    stateSelect.val('').selectpicker('refresh');
                });
                
                // Trigger on page load if country is already selected
                if (countrySelect.val()) {
                    countrySelect.trigger('change');
                }
            }

            // Initialize state filtering for PR section
            filterStatesByCountry('pr_preferred_country', 'pr_preferred_state');
            
            // Initialize state filtering for Visit Visa section
            filterStatesByCountry('visit_preferred_country', 'visit_preferred_state');

            // ==================== STEP-BY-STEP LEAD SAVING ====================
            
            // Get lead_id from URL parameter or hidden input
            function getLeadIdFromUrl() {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get('lead_id');
            }
            
            // Update URL with lead_id without page reload
            function updateUrlWithLeadId(leadId) {
                if (leadId) {
                    const url = new URL(window.location);
                    url.searchParams.set('lead_id', leadId);
                    window.history.replaceState({}, '', url);
                }
            }
            
            let currentLeadId = $('#lead_id').val() || getLeadIdFromUrl() || null;
            
            // If lead_id is in URL but not in hidden input, update hidden input
            if (currentLeadId && !$('#lead_id').val()) {
                $('#lead_id').val(currentLeadId);
            }
            let currentStep = 1;
            let stepStatus = {
                step_1_completed: false,
                step_2_completed: false,
                step_3_completed: false,
                step_4_completed: false,
                step_5_completed: false,
                step_6_completed: false,
                step_7_completed: false,
                step_8_completed: false,
                step_9_completed: false,
                final_status: 'draft'
            };
            
            // Load existing lead data if lead_id exists
            function loadExistingLeadData(leadId) {
                if (!leadId || leadId === '' || leadId === 'null' || leadId === 'undefined') {
                    return;
                }
                
                $.ajax({
                    url: '{{ route("add-lead.step-status", ":id") }}'.replace(':id', leadId),
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Check if response has error (fail status)
                        if (response.status === 'fail' || response.status === 'error') {
                            console.error('Error loading lead:', response.message || 'Unknown error');
                            return;
                        }
                        
                        // Check if response has step_status
                        if (response && response.step_status) {
                            // Update step status
                            stepStatus = {
                                step_1_completed: response.step_status.step_1_completed || false,
                                step_2_completed: response.step_status.step_2_completed || false,
                                step_3_completed: response.step_status.step_3_completed || false,
                                step_4_completed: response.step_status.step_4_completed || false,
                                step_5_completed: response.step_status.step_5_completed || false,
                                step_6_completed: response.step_status.step_6_completed || false,
                                step_7_completed: response.step_status.step_7_completed || false,
                                step_8_completed: response.step_status.step_8_completed || false,
                                step_9_completed: response.step_status.step_9_completed || false,
                                final_status: response.step_status.final_status || 'draft'
                            };
                            
                            // Populate form fields with existing data
                            if (response.step_data) {
                                populateFormFields(response.step_data);
                            }
                            
                            // Update UI
                            updateTabNavigation();
                            updateFooterButtons();
                            
                            // Navigate to the appropriate step
                            navigateToAppropriateStep();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading lead status:', error);
                        console.error('Response:', xhr.responseText);
                        // Don't show error to user, just log it
                    }
                });
            }
            
            // Populate form fields with existing step data
            function populateFormFields(stepData) {
                // Populate Step 1 data
                if (stepData.step_1_data) {
                    const data = stepData.step_1_data;
                    $('#surname').val(data.surname || '');
                    $('#given_name').val(data.given_name || '');
                    $('#gender').val(data.gender || '').selectpicker('refresh');
                    // Format date for HTML date input (YYYY-MM-DD)
                    if (data.date_of_birth) {
                        let dob = data.date_of_birth;
                        // If date is in different format, convert it
                        if (dob.includes('/')) {
                            // Convert from DD/MM/YYYY or MM/DD/YYYY to YYYY-MM-DD
                            const parts = dob.split('/');
                            if (parts.length === 3) {
                                // Assume DD/MM/YYYY format
                                dob = parts[2] + '-' + parts[1] + '-' + parts[0];
                            }
                        } else if (dob.includes('-') && dob.split('-')[0].length === 2) {
                            // Convert from DD-MM-YYYY to YYYY-MM-DD
                            const parts = dob.split('-');
                            dob = parts[2] + '-' + parts[1] + '-' + parts[0];
                        }
                        $('#date_of_birth').val(dob);
                    } else {
                        $('#date_of_birth').val('');
                    }
                    $('#marital_status').val(data.marital_status || '').selectpicker('refresh');
                    $('#country_of_origin').val(data.country_of_origin || '');
                    $('#email_address').val(data.email_address || data.email || '');
                    $('#mobile').val(data.mobile || '');
                    $('#primary_phone').val(data.primary_phone || '');
                    $('#secondary_phone').val(data.secondary_phone || '');
                    $('#work_phone').val(data.work_phone || '');
                    $('#other_phone').val(data.other_phone || '');
                    // Other Email (Used in Last 5 Years)
                    if (data.other_email !== undefined && data.other_email !== null) {
                        $('#other_email').val(data.other_email);
                    } else {
                        $('#other_email').val('');
                    }
                    $('#lead_source').val(data.lead_source || '').selectpicker('refresh');
                    $('#lead_assign_to').val(data.lead_assign_to || '').selectpicker('refresh');
                    
                    // Home address
                    $('#home_address').val(data.home_address || '');
                    $('#home_city').val(data.home_city || '');
                    $('#home_state').val(data.home_state || '');
                    $('#home_country').val(data.home_country || '').selectpicker('refresh');
                    $('#home_pin_code').val(data.home_pin_code || '');
                    
                    // Mailing address
                    $('#mailing_address').val(data.mailing_address || '');
                    $('#mailing_city').val(data.mailing_city || '');
                    $('#mailing_state').val(data.mailing_state || '');
                    $('#mailing_country').val(data.mailing_country || '').selectpicker('refresh');
                    $('#mailing_pin_code').val(data.mailing_pin_code || '');
                    
                    if (data.mailing_same_as_home) {
                        $('#mailing_same_as_home').prop('checked', true).trigger('change');
                    }
                    
                    // Visa status
                    if (data.visa_status) {
                        $('input[name="visa_status"][value="' + data.visa_status + '"]').prop('checked', true).trigger('change');
                        
                        // Populate visa granted fields if status is granted
                        if (data.visa_status === 'granted') {
                            $('#visa_issue_date').val(data.visa_issue_date || '');
                            $('#visa_expire_date').val(data.visa_expire_date || '');
                            $('#visa_category').val(data.visa_category || '');
                        }
                    }
                    
                    // Visa refusals
                    if (data.visa_refusals) {
                        // Handle if visa_refusals is a JSON string
                        let visaRefusals = data.visa_refusals;
                        if (typeof visaRefusals === 'string') {
                            try {
                                visaRefusals = JSON.parse(visaRefusals);
                            } catch (e) {
                                console.error('Error parsing visa_refusals:', e);
                                visaRefusals = [];
                            }
                        }
                        
                        // Ensure it's an array
                        if (Array.isArray(visaRefusals) && visaRefusals.length > 0) {
                            visaRefusals.forEach(function(refusal, index) {
                                if (index === 0) {
                                    // Use existing fields
                                    $('#visa_rejection_date').val(refusal.date || '');
                                    $('#visa_refusal_category').val(refusal.category || '');
                                    $('#visa_refusal_reason').val(refusal.reason || '');
                                } else {
                                    // Add more refusal rows
                                    $('#add-more-visa-refusal').trigger('click');
                                    setTimeout(function() {
                                        const row = $('.visa-refusal-row').last();
                                        row.find('input[name="visa_rejection_date[]"]').val(refusal.date || '');
                                        row.find('input[name="visa_refusal_category[]"]').val(refusal.category || '');
                                        row.find('textarea[name="visa_refusal_reason[]"]').val(refusal.reason || '');
                                    }, 100);
                                }
                            });
                        }
                    }
                    
                    // Languages Spoken
                    $('#languages_spoken').val(data.languages_spoken || '');
                    
                    // Social Media Profile URLs
                    $('#facebook_profile_url').val(data.facebook_profile_url || '');
                    $('#instagram_profile_url').val(data.instagram_profile_url || '');
                    $('#linkedin_profile_url').val(data.linkedin_profile_url || '');
                }
                
                // Populate Step 2 data
                if (stepData.step_2_data) {
                    const data = stepData.step_2_data;
                    
                    // Set visa type first to show the correct section
                    if (data.visa_type) {
                        $('input[name="visa_type"][value="' + data.visa_type + '"]').prop('checked', true).trigger('change');
                        
                        // Wait a bit for the section to show, then populate fields
                        setTimeout(function() {
                            // PR Section fields
                            if (data.skill_assessment_letter) {
                                $('#skill_assessment_letter').val(data.skill_assessment_letter).selectpicker('refresh');
                            }
                            // Store the file name in a hidden input for reference if exists
                            if (data.pr_assessment_letter_file) {
                                const fileName = data.pr_assessment_letter_file;
                                
                                // Store the file name in a hidden input for reference
                                if ($('#pr_assessment_letter_file_hidden').length === 0) {
                                    $('<input>').attr({
                                        type: 'hidden',
                                        id: 'pr_assessment_letter_file_hidden',
                                        name: 'pr_assessment_letter_file_existing',
                                        value: fileName
                                    }).insertAfter('#pr_assessment_letter_file');
                                } else {
                                    $('#pr_assessment_letter_file_hidden').val(fileName);
                                }
                            }
                            if (data.pr_preferred_country) {
                                $('#pr_preferred_country').val(data.pr_preferred_country).selectpicker('refresh');
                                // Filter states based on country
                                const selectedCountry = data.pr_preferred_country;
                                $('#pr_preferred_state').find('option[value!=""]').each(function() {
                                    const $option = $(this);
                                    if ($option.data('country') === selectedCountry) {
                                        $option.prop('disabled', false);
                                    } else {
                                        $option.prop('disabled', true);
                                    }
                                });
                            }
                            if (data.pr_preferred_state) {
                                $('#pr_preferred_state').val(data.pr_preferred_state).selectpicker('refresh');
                            }
                            if (data.pr_family) {
                                $('#pr_family').val(data.pr_family).selectpicker('refresh');
                            }
                            if (data.pr_subclass) {
                                $('#pr_subclass').val(data.pr_subclass).selectpicker('refresh');
                            }
                            
                            // Visit Section fields
                            if (data.purpose_of_visit) {
                                $('#purpose_of_visit').val(data.purpose_of_visit);
                            }
                            if (data.visit_family) {
                                $('#visit_family').val(data.visit_family).selectpicker('refresh');
                            }
                            if (data.visit_preferred_country) {
                                $('#visit_preferred_country').val(data.visit_preferred_country).selectpicker('refresh');
                                // Filter states based on country
                                const selectedCountry = data.visit_preferred_country;
                                $('#visit_preferred_state').find('option[value!=""]').each(function() {
                                    const $option = $(this);
                                    if ($option.data('country') === selectedCountry) {
                                        $option.prop('disabled', false);
                                    } else {
                                        $option.prop('disabled', true);
                                    }
                                });
                            }
                            if (data.visit_preferred_state) {
                                $('#visit_preferred_state').val(data.visit_preferred_state).selectpicker('refresh');
                            }
                            if (data.visit_subclass) {
                                $('#visit_subclass').val(data.visit_subclass).selectpicker('refresh');
                            }
                            
                            // Work Section fields
                            if (data.preferred_designation) {
                                $('#preferred_designation').val(data.preferred_designation);
                            }
                            if (data.industry) {
                                $('#industry').val(data.industry).selectpicker('refresh');
                            }
                            if (data.on_role_off_role) {
                                $('#on_role_off_role').val(data.on_role_off_role).selectpicker('refresh');
                            }
                            if (data.work_preferred_country) {
                                $('#work_preferred_country').val(data.work_preferred_country).selectpicker('refresh');
                                // Filter states based on country
                                const selectedCountry = data.work_preferred_country;
                                $('#work_preferred_state').find('option[value!=""]').each(function() {
                                    const $option = $(this);
                                    if ($option.data('country') === selectedCountry) {
                                        $option.prop('disabled', false);
                                    } else {
                                        $option.prop('disabled', true);
                                    }
                                });
                            }
                            if (data.work_preferred_state) {
                                $('#work_preferred_state').val(data.work_preferred_state).selectpicker('refresh');
                            }
                            if (data.work_category) {
                                $('#work_category').val(data.work_category).selectpicker('refresh');
                            }
                            if (data.work_subclass) {
                                $('#work_subclass').val(data.work_subclass).selectpicker('refresh');
                            }
                            
                            // Student Section fields
                            if (data.preferred_course) {
                                $('#preferred_course').val(data.preferred_course);
                            }
                            if (data.student_country) {
                                $('#student_country').val(data.student_country).selectpicker('refresh');
                            }
                            if (data.university) {
                                $('#university').val(data.university);
                            }
                            if (data.student_subclass) {
                                $('#student_subclass').val(data.student_subclass).selectpicker('refresh');
                            }
                        }, 300);
                    }
                }
                
                // Populate Steps 3-9 data
                for (let stepNum = 3; stepNum <= 9; stepNum++) {
                    const stepKey = 'step_' + stepNum + '_data';
                    if (stepData[stepKey] && typeof stepData[stepKey] === 'object') {
                        const stepDataObj = stepData[stepKey];
                        // Populate all fields for this step
                        Object.keys(stepDataObj).forEach(function(fieldName) {
                            const $field = $('#' + fieldName + ', [name="' + fieldName + '"]').first();
                            if ($field.length) {
                                const value = stepDataObj[fieldName];
                                if ($field.is('select')) {
                                    $field.val(value).selectpicker('refresh');
                                } else if ($field.is(':checkbox') || $field.is(':radio')) {
                                    if ($field.is(':checkbox')) {
                                        $field.prop('checked', value === true || value === '1' || value === 1);
                                    } else {
                                        $field.filter('[value="' + value + '"]').prop('checked', true);
                                    }
                                } else {
                                    $field.val(value || '');
                                }
                            }
                        });
                    }
                }
            }

            // Map tab IDs to step numbers
            const tabStepMap = {
                'nav-personal-tab': 1,
                'nav-preference-tab': 2,
                'nav-passport-tab': 3,
                'nav-relative-tab': 4,
                'nav-family-tab': 5,
                'nav-education-tab': 6,
                'nav-experience-tab': 7,
                'nav-property-tab': 8,
                'nav-financial-tab': 9 // Financial Status is step 9
            };

            // Get current step from active tab
            function getCurrentStep() {
                const activeTab = $('.nav-link-lead.active');
                const tabId = activeTab.attr('id');
                return tabStepMap[tabId] || 1;
            }
            
            // Navigate to the appropriate step based on completion status
            function navigateToAppropriateStep() {
                // If all steps are completed and final status is 'complete', redirect to fresh form
                const allCompleted = stepStatus.step_1_completed && 
                                     stepStatus.step_2_completed && 
                                     stepStatus.step_3_completed && 
                                     stepStatus.step_4_completed && 
                                     stepStatus.step_5_completed && 
                                     stepStatus.step_6_completed && 
                                     stepStatus.step_7_completed && 
                                     stepStatus.step_8_completed &&
                                     stepStatus.step_9_completed;
                
                if (allCompleted && stepStatus.final_status === 'complete') {
                    // Redirect to fresh form
                    window.location.href = '{{ route("add-lead.index") }}';
                    return;
                }
                
                // Find the first incomplete step
                let targetStep = 1;
                
                for (let i = 1; i <= 9; i++) {
                    const stepKey = 'step_' + i + '_completed';
                    if (!stepStatus[stepKey]) {
                        targetStep = i;
                        break;
                    }
                }
                
                // If all steps are completed but status is still draft, go to step 9
                if (allCompleted) {
                    targetStep = 9;
                }
                
                // Find the tab ID for the target step
                let targetTabId = Object.keys(tabStepMap).find(key => tabStepMap[key] === targetStep);
                
                if (targetTabId && $('#' + targetTabId).length) {
                    // Use Bootstrap's tab API to switch tabs
                    $('#' + targetTabId).tab('show');
                    
                    // Update current step after tab is shown
                    setTimeout(function() {
                        currentStep = targetStep;
                        updateFooterButtons();
                    }, 100);
                }
            }

            // Update tab navigation based on step completion
            function updateTabNavigation() {
                $('.nav-link-lead').each(function() {
                    const tabId = $(this).attr('id');
                    const stepNum = tabStepMap[tabId];
                    
                    if (!stepNum) return;
                    
                    // Step 1 is always enabled
                    if (stepNum === 1) {
                        $(this).removeClass('disabled').css('pointer-events', 'auto').css('opacity', '1');
                        return;
                    }
                    
                    // Check if previous step is completed
                    const previousStep = 'step_' + (stepNum - 1) + '_completed';
                    if (stepStatus[previousStep]) {
                        $(this).removeClass('disabled').css('pointer-events', 'auto').css('opacity', '1');
                    } else {
                        $(this).addClass('disabled').css('pointer-events', 'none').css('opacity', '0.5');
                    }
                });
            }

            // Prevent navigation to disabled tabs
            $('.nav-link-lead').on('click', function(e) {
                if ($(this).hasClass('disabled')) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        text: '@lang('app.pleaseCompletePreviousSteps')',
                        toast: true,
                        position: "top-end",
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                        showClass: {
                            popup: "swal2-noanimation",
                            backdrop: "swal2-noanimation",
                        },
                    });
                    return false;
                }
                currentStep = getCurrentStep();
            });

            // Update footer buttons based on current step
            function updateFooterButtons() {
                const $prevBtn = $('#btn-previous');
                const $saveBtn = $('#save-lead-form');
                
                // Disable previous button on step 1
                if (currentStep === 1) {
                    $prevBtn.prop('disabled', true).addClass('disabled');
                } else {
                    $prevBtn.prop('disabled', false).removeClass('disabled');
                }
                
                // Show "Save and Submit" on last step (step 9), "Save and Next" for others
                if (currentStep === 9) {
                    $saveBtn.html('<i class="fa fa-check mr-1"></i>Save and Submit');
                } else {
                    $saveBtn.html('<i class="fa fa-arrow-right mr-1"></i>@lang('app.saveAndNext')');
                }
            }

            // Helper function to get select value (handles Bootstrap Selectpicker)
            function getSelectValue(selector) {
                const $select = $(selector);
                if ($select.length === 0) return null;
                
                // Try selectpicker method first
                try {
                    if ($select.data('selectpicker')) {
                        const val = $select.selectpicker('val');
                        return val;
                    }
                } catch(e) {
                    // If selectpicker method fails, fall back to regular val()
                }
                // Fallback to regular val()
                return $select.val();
            }
            
            // Helper function to show error message below a field
            function showFieldError(fieldId, errorMessage) {
                const $field = $(fieldId);
                
                // Find the parent column container
                const $parentColumn = $field.closest('.col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-12');
                
                // Check if it's a bootstrap-select field first
                const $bootstrapSelect = $field.closest('.bootstrap-select');
                
                // Remove ALL existing error messages comprehensively
                if ($parentColumn.length) {
                    // Remove from entire parent column
                    $parentColumn.find('.invalid-feedback').remove();
                }
                // Remove from field itself and siblings
                $field.next('.invalid-feedback').remove();
                $field.siblings('.invalid-feedback').remove();
                
                if ($bootstrapSelect.length) {
                    // For bootstrap-select, add is-invalid to the wrapper
                    // CSS will handle hiding the error styling on the select element
                    $bootstrapSelect.addClass('is-invalid');
                    
                    // Remove errors from wrapper and its parent
                    $bootstrapSelect.next('.invalid-feedback').remove();
                    $bootstrapSelect.siblings('.invalid-feedback').remove();
                    $bootstrapSelect.parent().find('.invalid-feedback').remove();
                    
                    // Add error after the wrapper (only once)
                    $bootstrapSelect.after('<div class="invalid-feedback d-block">' + errorMessage + '</div>');
                } else if ($field.attr('type') === 'file') {
                    // For file inputs, add is-invalid to the field
                    $field.addClass('is-invalid');
                    // For file inputs, add error after the parent container
                    if ($parentColumn.length) {
                        $parentColumn.append('<div class="invalid-feedback d-block">' + errorMessage + '</div>');
                    } else {
                        $field.after('<div class="invalid-feedback d-block">' + errorMessage + '</div>');
                    }
                } else {
                    // For regular inputs, add is-invalid to the field
                    $field.addClass('is-invalid');
                    // Add error message below the field
                    $field.after('<div class="invalid-feedback d-block">' + errorMessage + '</div>');
                }
            }
            
            // Validate current step before saving
            function validateCurrentStep() {
                currentStep = getCurrentStep();
                let isValid = true;
                
                // Remove previous error styling and messages
                $('.form-control').removeClass('is-invalid');
                $('.bootstrap-select').removeClass('is-invalid');
                $('.form-check').removeClass('is-invalid');
                $('.form-check-input').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                
                switch (currentStep) {
                    case 1:
                        // Step 1 - Personal Details
                        const surnameVal = $('#surname').val() || '';
                        if (!surnameVal.trim()) {
                            isValid = false;
                            showFieldError('#surname', '@lang('app.surname') is required');
                        }
                        const givenNameVal = $('#given_name').val() || '';
                        if (!givenNameVal.trim()) {
                            isValid = false;
                            showFieldError('#given_name', '@lang('app.givenName') is required');
                        }
                        // Gender validation (select field)
                        const genderVal = getSelectValue('#gender');
                        if (!genderVal || genderVal === '' || genderVal === null || (Array.isArray(genderVal) && genderVal.length === 0)) {
                            isValid = false;
                            showFieldError('#gender', '@lang('app.gender') is required');
                        }
                        // Marital Status validation (select field)
                        const maritalStatusVal = getSelectValue('#marital_status');
                        if (!maritalStatusVal || maritalStatusVal === '' || maritalStatusVal === null || (Array.isArray(maritalStatusVal) && maritalStatusVal.length === 0)) {
                            isValid = false;
                            showFieldError('#marital_status', '@lang('app.maritalStatus') is required');
                        }
                        if (!$('#date_of_birth').val()) {
                            isValid = false;
                            showFieldError('#date_of_birth', '@lang('app.dateOfBirth') is required');
                        }
                        const countryOfOriginVal = $('#country_of_origin').val() || '';
                        if (!countryOfOriginVal.trim()) {
                            isValid = false;
                            showFieldError('#country_of_origin', '@lang('app.countryOfOrigin') is required');
                        }
                        // Lead Source validation (select field)
                        const leadSourceVal = getSelectValue('#lead_source');
                        if (!leadSourceVal || leadSourceVal === '' || leadSourceVal === null || (Array.isArray(leadSourceVal) && leadSourceVal.length === 0)) {
                            isValid = false;
                            showFieldError('#lead_source', '@lang('modules.lead.leadSource') is required');
                        }
                        // Lead Assign To validation (select field)
                        const leadAssignToVal = getSelectValue('#lead_assign_to');
                        if (!leadAssignToVal || leadAssignToVal === '' || leadAssignToVal === null || (Array.isArray(leadAssignToVal) && leadAssignToVal.length === 0)) {
                            isValid = false;
                            showFieldError('#lead_assign_to', '@lang('app.leadAssignTo') is required');
                        }
                        const homeAddressVal = $('#home_address').val() || '';
                        if (!homeAddressVal.trim()) {
                            isValid = false;
                            showFieldError('#home_address', '@lang('modules.lead.address') is required');
                        }
                        const homeCityVal = $('#home_city').val() || '';
                        if (!homeCityVal.trim()) {
                            isValid = false;
                            showFieldError('#home_city', '@lang('app.city') is required');
                        }
                        const homeStateVal = $('#home_state').val() || '';
                        if (!homeStateVal.trim()) {
                            isValid = false;
                            showFieldError('#home_state', '@lang('app.state') is required');
                        }
                        const homePinCodeVal = $('#home_pin_code').val() || '';
                        if (!homePinCodeVal.trim()) {
                            isValid = false;
                            showFieldError('#home_pin_code', '@lang('app.pinCode') is required');
                        }
                        // Mailing address validation (only if not same as home)
                        if (!$('#mailing_same_as_home').is(':checked')) {
                            const mailingAddressVal = $('#mailing_address').val() || '';
                            if (!mailingAddressVal.trim()) {
                                isValid = false;
                                showFieldError('#mailing_address', '@lang('modules.lead.address') is required');
                            }
                            const mailingCityVal = $('#mailing_city').val() || '';
                            if (!mailingCityVal.trim()) {
                                isValid = false;
                                showFieldError('#mailing_city', '@lang('app.city') is required');
                            }
                            const mailingStateVal = $('#mailing_state').val() || '';
                            if (!mailingStateVal.trim()) {
                                isValid = false;
                                showFieldError('#mailing_state', '@lang('app.state') is required');
                            }
                            const mailingPinCodeVal = $('#mailing_pin_code').val() || '';
                            if (!mailingPinCodeVal.trim()) {
                                isValid = false;
                                showFieldError('#mailing_pin_code', '@lang('app.pinCode') is required');
                            }
                        }
                        // Primary phone validation
                        const primaryPhone = ($('#primary_phone').val() || '').trim();
                        if (!primaryPhone) {
                            isValid = false;
                            showFieldError('#primary_phone', '@lang('app.primaryPhoneNo') is required');
                        } else if (!/^[0-9]{10}$/.test(primaryPhone)) {
                            isValid = false;
                            showFieldError('#primary_phone', '@lang('app.primaryPhoneNo') must be 10 digits');
                        }
                        // Email validation
                        const emailAddress = ($('#email_address').val() || '').trim();
                        if (!emailAddress) {
                            isValid = false;
                            showFieldError('#email_address', '@lang('modules.lead.email') is required');
                        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailAddress)) {
                            isValid = false;
                            showFieldError('#email_address', '@lang('modules.lead.email') must be a valid email address');
                        }
                        // Optional phone fields validation
                        const secondaryPhone = ($('#secondary_phone').val() || '').trim();
                        if (secondaryPhone && !/^[0-9]{10}$/.test(secondaryPhone)) {
                            isValid = false;
                            showFieldError('#secondary_phone', '@lang('app.secondaryPhoneNo') must be 10 digits');
                        }
                        const workPhone = ($('#work_phone').val() || '').trim();
                        if (workPhone && !/^[0-9]{10}$/.test(workPhone)) {
                            isValid = false;
                            showFieldError('#work_phone', '@lang('app.workPhoneNo') must be 10 digits');
                        }
                        const otherPhone = ($('#other_phone').val() || '').trim();
                        if (otherPhone && !/^[0-9]{10}$/.test(otherPhone)) {
                            isValid = false;
                            showFieldError('#other_phone', '@lang('app.otherPhoneNo') must be 10 digits');
                        }
                        const mobile = ($('#mobile').val() || '').trim();
                        if (mobile && !/^[0-9]{10}$/.test(mobile)) {
                            isValid = false;
                            showFieldError('#mobile', '@lang('app.mobile') must be 10 digits');
                        }
                        // Optional email validation
                        const otherEmail = ($('#other_email').val() || '').trim();
                        if (otherEmail && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(otherEmail)) {
                            isValid = false;
                            showFieldError('#other_email', '@lang('app.otherEmail') must be a valid email address');
                        }
                        // Upload Resume validation
                        if (!$('#upload_resume').val() && !$('#upload_resume_hidden').length) {
                            isValid = false;
                            showFieldError('#upload_resume', 'Upload Resume is required');
                        }
                        // Visa Status validation
                        if (!$('input[name="visa_status"]:checked').val()) {
                            isValid = false;
                            const $visaStatusContainer = $('input[name="visa_status"]').closest('.row').first();
                            $visaStatusContainer.find('.invalid-feedback').remove();
                            $visaStatusContainer.append('<div class="invalid-feedback d-block col-12">@lang('app.lastFiveYearsVisaStatus') is required</div>');
                            $('input[name="visa_status"]').closest('.form-check').addClass('is-invalid');
                        } else {
                            const visaStatus = $('input[name="visa_status"]:checked').val();
                            // Visa Granted fields validation
                            if (visaStatus === 'granted') {
                                if (!$('#visa_issue_date').val()) {
                                    isValid = false;
                                    showFieldError('#visa_issue_date', '@lang('app.visaIssueDate') is required');
                                }
                                if (!$('#visa_expire_date').val()) {
                                    isValid = false;
                                    showFieldError('#visa_expire_date', '@lang('app.visaExpireDate') is required');
                                }
                                const visaCategoryVal = ($('#visa_category').val() || '').trim();
                                if (!visaCategoryVal) {
                                    isValid = false;
                                    showFieldError('#visa_category', '@lang('app.visaCategory') is required');
                                }
                            }
                            // Visa Refusal fields validation
                            if (visaStatus === 'refusal') {
                                if (!$('#visa_rejection_date').val()) {
                                    isValid = false;
                                    showFieldError('#visa_rejection_date', '@lang('app.visaRejectionDate') is required');
                                }
                                const visaRefusalCategoryVal = ($('#visa_refusal_category').val() || '').trim();
                                if (!visaRefusalCategoryVal) {
                                    isValid = false;
                                    showFieldError('#visa_refusal_category', '@lang('app.visaCategory') is required');
                                }
                                const visaRefusalReasonVal = ($('#visa_refusal_reason').val() || '').trim();
                                if (!visaRefusalReasonVal) {
                                    isValid = false;
                                    showFieldError('#visa_refusal_reason', '@lang('app.reason') is required');
                                }
                                // Validate dynamic visa refusal rows
                                $('[id^="visa_rejection_date_"]').each(function() {
                                    if (!$(this).val()) {
                                        isValid = false;
                                        showFieldError('#' + $(this).attr('id'), '@lang('app.visaRejectionDate') is required');
                                    }
                                });
                                $('[id^="visa_refusal_category_"]').each(function() {
                                    const val = ($(this).val() || '').trim();
                                    if (!val) {
                                        isValid = false;
                                        showFieldError('#' + $(this).attr('id'), '@lang('app.visaCategory') is required');
                                    }
                                });
                                $('[id^="visa_refusal_reason_"]').each(function() {
                                    const val = ($(this).val() || '').trim();
                                    if (!val) {
                                        isValid = false;
                                        showFieldError('#' + $(this).attr('id'), '@lang('app.reason') is required');
                                    }
                                });
                            }
                        }
                        // Languages Spoken validation
                        const languagesSpokenVal = ($('#languages_spoken').val() || '').trim();
                        if (!languagesSpokenVal) {
                            isValid = false;
                            showFieldError('#languages_spoken', '@lang('app.languagesSpoken') is required');
                        }
                        break;
                        
                    case 2:
                        // Step 2 - Client Preference
                        if (!$('input[name="visa_type"]:checked').val()) {
                            isValid = false;
                            // Show error on visa type radio buttons container
                            const $visaTypeContainer = $('input[name="visa_type"]').closest('.row').first();
                            $visaTypeContainer.find('.invalid-feedback').remove();
                            $visaTypeContainer.append('<div class="invalid-feedback d-block col-12">@lang('app.selectVisaType') is required</div>');
                            $('input[name="visa_type"]').closest('.form-check').addClass('is-invalid');
                        } else {
                            const visaType = $('input[name="visa_type"]:checked').val();
                            
                            // PR Visa validation (only fields with *)
                            if (visaType === 'PR' || visaType === 'pr') {
                                const skillAssessmentLetterVal = getSelectValue('#skill_assessment_letter');
                                if (!skillAssessmentLetterVal || skillAssessmentLetterVal === '' || skillAssessmentLetterVal === null || (Array.isArray(skillAssessmentLetterVal) && skillAssessmentLetterVal.length === 0)) {
                                    isValid = false;
                                    showFieldError('#skill_assessment_letter', '@lang('app.skillAssessmentLetter') is required');
                                }
                                // pr_assessment_letter_file is required if not already uploaded
                                if (!$('#pr_assessment_letter_file').val() && !$('#pr_assessment_letter_file_hidden').length) {
                                    isValid = false;
                                    showFieldError('#pr_assessment_letter_file', '@lang('app.addAssessmentLetter') is required');
                                }
                                const prFamilyVal = getSelectValue('#pr_family');
                                if (!prFamilyVal || prFamilyVal === '' || prFamilyVal === null || (Array.isArray(prFamilyVal) && prFamilyVal.length === 0)) {
                                    isValid = false;
                                    showFieldError('#pr_family', '@lang('app.family') is required');
                                }
                            }
                            
                            // Visit Visa validation (only fields with *)
                            if (visaType === 'Visit' || visaType === 'visit') {
                                const purposeOfVisitVal = $('#purpose_of_visit').val() || '';
                                if (!purposeOfVisitVal.trim()) {
                                    isValid = false;
                                    showFieldError('#purpose_of_visit', '@lang('app.purposeOfVisit') is required');
                                }
                                const visitFamilyVal = getSelectValue('#visit_family');
                                if (!visitFamilyVal || visitFamilyVal === '' || visitFamilyVal === null || (Array.isArray(visitFamilyVal) && visitFamilyVal.length === 0)) {
                                    isValid = false;
                                    showFieldError('#visit_family', '@lang('app.family') is required');
                                }
                            }
                            
                            // Work Visa validation (only fields with *)
                            if (visaType === 'Work' || visaType === 'work') {
                                const preferredDesignationVal = $('#preferred_designation').val() || '';
                                if (!preferredDesignationVal.trim()) {
                                    isValid = false;
                                    showFieldError('#preferred_designation', '@lang('app.preferredDesignation') is required');
                                }
                            }
                            
                            // Student Visa validation (only fields with *)
                            if (visaType === 'Student' || visaType === 'student') {
                                const termIntakeVal = ($('#term_intake').val() || '').trim();
                                if (!termIntakeVal) {
                                    isValid = false;
                                    showFieldError('#term_intake', '@lang('app.termIntake') is required');
                                }
                            }
                        }
                        break;
                        
                    case 3:
                        // Step 3 - Passport Details
                        const issuingCountryVal = $('#issuing_country').val() || '';
                        if (!issuingCountryVal.trim()) {
                            isValid = false;
                            showFieldError('#issuing_country', 'Issuing Country is required');
                        }
                        const cityWhereIssuedVal = $('#city_where_issued').val() || '';
                        if (!cityWhereIssuedVal.trim()) {
                            isValid = false;
                            showFieldError('#city_where_issued', 'City Where Issued is required');
                        }
                        if (!$('#issuance_date').val()) {
                            isValid = false;
                            showFieldError('#issuance_date', 'Issue Date is required');
                        }
                        if (!$('#expiration_date').val()) {
                            isValid = false;
                            showFieldError('#expiration_date', 'Expiration Date is required');
                        }
                        // Passport Number validation
                        const passportNumberVal = ($('#passport_number').val() || '').trim();
                        if (!passportNumberVal) {
                            isValid = false;
                            showFieldError('#passport_number', 'Passport Number is required');
                        }
                        // Passport file validation
                        if (!$('#passport_file_upload').val() && !$('#passport_file_upload_hidden').length) {
                            isValid = false;
                            showFieldError('#passport_file_upload', 'Passport file is required');
                        }
                        // Expiration Date should be greater than Issuance Date
                        const issuanceDate = $('#issuance_date').val();
                        const expirationDate = $('#expiration_date').val();
                        if (issuanceDate && expirationDate && expirationDate <= issuanceDate) {
                            isValid = false;
                            showFieldError('#expiration_date', 'Expiration Date must be greater than Issuance Date');
                        }
                        break;
                        
                    case 4:
                        // Step 4 - Relative Contact Information
                        // Validate email format if provided
                        const relativeEmail = ($('#relative_email_address').val() || '').trim();
                        if (relativeEmail && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(relativeEmail)) {
                            isValid = false;
                            showFieldError('#relative_email_address', 'Email Address must be a valid email address');
                        }
                        // Validate phone number max length
                        const relativePhone = ($('#relative_phone_number').val() || '').trim();
                        if (relativePhone && relativePhone.length > 10) {
                            isValid = false;
                            showFieldError('#relative_phone_number', 'Phone Number must be maximum 10 digits');
                        }
                        // Validate dynamic relative contact fields
                        $('[id^="relative_email_address_"]').each(function() {
                            const email = ($(this).val() || '').trim();
                            if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                                isValid = false;
                                showFieldError('#' + $(this).attr('id'), 'Email Address must be a valid email address');
                            }
                        });
                        $('[id^="relative_phone_number_"]').each(function() {
                            const phone = ($(this).val() || '').trim();
                            if (phone && phone.length > 10) {
                                isValid = false;
                                showFieldError('#' + $(this).attr('id'), 'Phone Number must be maximum 10 digits');
                            }
                        });
                        break;
                        
                    case 6:
                        // Step 6 - Education
                        if (!$('#tenth_passing_year').val()) {
                            isValid = false;
                            showFieldError('#tenth_passing_year', '10th Passing Year is required');
                        }
                        if (!$('#tenth_percentage').val()) {
                            isValid = false;
                            showFieldError('#tenth_percentage', '10th Percentage is required');
                        }
                        const tenthBoardNameVal = ($('#tenth_board_name').val() || '').trim();
                        if (!tenthBoardNameVal) {
                            isValid = false;
                            showFieldError('#tenth_board_name', '10th Board Name is required');
                        }
                        const tenthTrialVal = ($('#tenth_trial').val() || '').trim();
                        if (!tenthTrialVal) {
                            isValid = false;
                            showFieldError('#tenth_trial', '10th Trial is required');
                        }
                        // 10th Result file is required
                        if (!$('#tenth_result_file').val() && !$('#tenth_result_file_hidden').length) {
                            isValid = false;
                            showFieldError('#tenth_result_file', '10th Result file is required');
                        }
                        break;
                        
                    case 5:
                        // Step 5 - Family Information
                        const step5FatherSurnameVal = $('#father_surname').val() || '';
                        if (!step5FatherSurnameVal.trim()) {
                            isValid = false;
                            showFieldError('#father_surname', 'Father\'s Surname is required');
                        }
                        const step5FatherGivenNameVal = $('#father_given_name').val() || '';
                        if (!step5FatherGivenNameVal.trim()) {
                            isValid = false;
                            showFieldError('#father_given_name', 'Father\'s Given Name is required');
                        }
                        if (!$('#father_date_of_birth').val()) {
                            isValid = false;
                            showFieldError('#father_date_of_birth', 'Father\'s Date of Birth is required');
                        }
                        const step5FatherOccupationVal = $('#father_occupation').val() || '';
                        if (!step5FatherOccupationVal.trim()) {
                            isValid = false;
                            showFieldError('#father_occupation', 'Father\'s Occupation is required');
                        }
                        const step5FatherHavePassportVal = getSelectValue('#father_have_passport');
                        if (!step5FatherHavePassportVal || step5FatherHavePassportVal === '' || step5FatherHavePassportVal === null || (Array.isArray(step5FatherHavePassportVal) && step5FatherHavePassportVal.length === 0)) {
                            isValid = false;
                            showFieldError('#father_have_passport', 'Father\'s Have Passport is required');
                        }
                        const step5MotherSurnameVal = $('#mother_surname').val() || '';
                        if (!step5MotherSurnameVal.trim()) {
                            isValid = false;
                            showFieldError('#mother_surname', 'Mother\'s Surname is required');
                        }
                        const step5MotherGivenNameVal = $('#mother_given_name').val() || '';
                        if (!step5MotherGivenNameVal.trim()) {
                            isValid = false;
                            showFieldError('#mother_given_name', 'Mother\'s Given Name is required');
                        }
                        if (!$('#mother_date_of_birth').val()) {
                            isValid = false;
                            showFieldError('#mother_date_of_birth', 'Mother\'s Date of Birth is required');
                        }
                        const step5MotherOccupationVal = $('#mother_occupation').val() || '';
                        if (!step5MotherOccupationVal.trim()) {
                            isValid = false;
                            showFieldError('#mother_occupation', 'Mother\'s Occupation is required');
                        }
                        const step5MotherHavePassportVal = getSelectValue('#mother_have_passport');
                        if (!step5MotherHavePassportVal || step5MotherHavePassportVal === '' || step5MotherHavePassportVal === null || (Array.isArray(step5MotherHavePassportVal) && step5MotherHavePassportVal.length === 0)) {
                            isValid = false;
                            showFieldError('#mother_have_passport', 'Mother\'s Have Passport is required');
                        }
                        // Mother passport file is required if mother has passport = Yes
                        if (step5MotherHavePassportVal === 'Yes') {
                            if (!$('#mother_passport_file').val() && !$('#mother_passport_file_hidden').length) {
                                isValid = false;
                                showFieldError('#mother_passport_file', 'Mother\'s Passport file is required');
                            }
                        }
                        // Father passport file is required if father has passport = Yes
                        if (step5FatherHavePassportVal === 'Yes') {
                            if (!$('#father_passport_file').val() && !$('#father_passport_file_hidden').length) {
                                isValid = false;
                                showFieldError('#father_passport_file', 'Father\'s Passport file is required');
                            }
                        }
                        // Spouse passport file is required if spouse has passport = Yes
                        const step5SpouseHavePassportVal = getSelectValue('#spouse_have_passport');
                        if (step5SpouseHavePassportVal === 'Yes') {
                            if (!$('#spouse_passport_file').val() && !$('#spouse_passport_file_hidden').length) {
                                isValid = false;
                                showFieldError('#spouse_passport_file', 'Spouse\'s Passport file is required');
                            }
                        }
                        // Child passport file is required if child has passport = Yes
                        const step5ChildHavePassportVal = getSelectValue('#child_have_passport');
                        if (step5ChildHavePassportVal === 'Yes') {
                            if (!$('#child_passport_file').val() && !$('#child_passport_file_hidden').length) {
                                isValid = false;
                                showFieldError('#child_passport_file', 'Child\'s Passport file is required');
                            }
                        }
                        // Validate dynamic child passport fields
                        $('[id^="child_have_passport"]').each(function() {
                            const childHavePassport = getSelectValue('#' + $(this).attr('id'));
                            if (childHavePassport === 'Yes') {
                                const childNum = $(this).attr('id').replace('child_have_passport_', '').replace('child_have_passport', '');
                                const fileId = childNum ? `#child_passport_file_${childNum}` : '#child_passport_file';
                                if (!$(fileId).val() && !$(fileId + '_hidden').length) {
                                    isValid = false;
                                    showFieldError(fileId, 'Child\'s Passport file is required');
                                }
                            }
                        });
                        break;
                        
                    case 8:
                        // Step 8 - Property Details
                        const propertyHomeVal = $('#property_home').val();
                        if (propertyHomeVal === '' || propertyHomeVal === null || propertyHomeVal === undefined) {
                            isValid = false;
                            showFieldError('#property_home', 'Property Home is required');
                        }
                        const propertyLandVal = $('#property_land').val();
                        if (propertyLandVal === '' || propertyLandVal === null || propertyLandVal === undefined) {
                            isValid = false;
                            showFieldError('#property_land', 'Property Land is required');
                        }
                        const propertyPlotVal = $('#property_plot').val();
                        if (propertyPlotVal === '' || propertyPlotVal === null || propertyPlotVal === undefined) {
                            isValid = false;
                            showFieldError('#property_plot', 'Property Plot is required');
                        }
                        const propertyCommercialsVal = $('#property_commercials').val();
                        if (propertyCommercialsVal === '' || propertyCommercialsVal === null || propertyCommercialsVal === undefined) {
                            isValid = false;
                            showFieldError('#property_commercials', 'Property Commercials is required');
                        }
                        const propertyOtherVal = $('#property_other').val();
                        if (propertyOtherVal === '' || propertyOtherVal === null || propertyOtherVal === undefined) {
                            isValid = false;
                            showFieldError('#property_other', 'Property Other is required');
                        }
                        const propertyShopVal = $('#property_shop').val();
                        if (propertyShopVal === '' || propertyShopVal === null || propertyShopVal === undefined) {
                            isValid = false;
                            showFieldError('#property_shop', 'Property Shop is required');
                        }
                        const propertyGoldVal = $('#property_gold').val();
                        if (propertyGoldVal === '' || propertyGoldVal === null || propertyGoldVal === undefined) {
                            isValid = false;
                            showFieldError('#property_gold', 'Property Gold is required');
                        }
                        const propertySilverVal = $('#property_silver').val();
                        if (propertySilverVal === '' || propertySilverVal === null || propertySilverVal === undefined) {
                            isValid = false;
                            showFieldError('#property_silver', 'Property Silver is required');
                        }
                        break;
                }
                
                if (!isValid) {
                    // Scroll to first invalid field
                    setTimeout(function() {
                        const firstInvalid = $('.is-invalid').first();
                        if (firstInvalid.length && firstInvalid.offset()) {
                            $('html, body').animate({
                                scrollTop: firstInvalid.offset().top - 100
                            }, 500);
                        }
                    }, 100);
                }
                
                return isValid;
            }

            // Save current step
            function saveCurrentStep() {
                currentStep = getCurrentStep();
                
                // Validate before saving
                if (!validateCurrentStep()) {
                    return;
                }
                
                const formData = new FormData($('#addLeadForm')[0]);
                
                // Add lead_id if exists
                if (currentLeadId) {
                    formData.append('lead_id', currentLeadId);
                }
                
                // Collect visa refusals if step 1
                if (currentStep === 1) {
                    const visaRefusals = [];
                    
                    // Check if visa status is "refusal" and collect the first refusal entry
                    if ($('input[name="visa_status"]:checked').val() === 'refusal') {
                        const firstDate = $('#visa_rejection_date').val();
                        const firstCategory = $('#visa_refusal_category').val();
                        const firstReason = $('#visa_refusal_reason').val();
                        
                        // Only add first entry if at least one field has a value
                        if (firstDate || firstCategory || firstReason) {
                            visaRefusals.push({
                                date: firstDate || '',
                                category: firstCategory || '',
                                reason: firstReason || ''
                            });
                        }
                    }
                    
                    // Collect additional refusal rows
                    $('.visa-refusal-row').each(function() {
                        const date = $(this).find('input[name="visa_rejection_date[]"]').val();
                        const category = $(this).find('input[name="visa_refusal_category[]"]').val();
                        const reason = $(this).find('textarea[name="visa_refusal_reason[]"]').val();
                        
                        // Only add if at least one field has a value
                        if (date || category || reason) {
                            visaRefusals.push({
                                date: date || '',
                                category: category || '',
                                reason: reason || ''
                            });
                        }
                    });
                    
                    formData.append('visa_refusals', JSON.stringify(visaRefusals));
                }
                
                // Show loading
                const $saveBtn = $('#save-lead-form');
                const originalHtml = $saveBtn.html();
                $saveBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i>@lang('app.saving')...');
                
                $.ajax({
                    url: '{{ route("add-lead.save-step", ":step") }}'.replace(':step', currentStep),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            // Update lead_id if it's a new lead
                            if (response.lead_id) {
                                currentLeadId = response.lead_id;
                                $('#lead_id').val(currentLeadId);
                                // Update URL with lead_id so it persists on refresh
                                updateUrlWithLeadId(currentLeadId);
                            }
                            
                            // Update step status
                            stepStatus['step_' + currentStep + '_completed'] = true;
                            stepStatus.final_status = response.final_status || 'draft';
                            
                            // Update UI
                            updateTabNavigation();
                            updateFooterButtons();
                            
                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                text: response.message || '@lang('messages.recordSaved')',
                                toast: true,
                                position: "top-end",
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                },
                                showClass: {
                                    popup: "swal2-noanimation",
                                    backdrop: "swal2-noanimation",
                                },
                            });
                            
                            // If not last step, move to next step
                            if (currentStep < 9) {
                                setTimeout(function() {
                                    // Find the first incomplete step after current step
                                    let nextStep = currentStep + 1;
                                    for (let i = currentStep + 1; i <= 9; i++) {
                                        const stepKey = 'step_' + i + '_completed';
                                        if (!stepStatus[stepKey]) {
                                            nextStep = i;
                                            break;
                                        }
                                    }
                                    
                                    // If all steps after current are completed, go to step 9
                                    const allAfterCompleted = stepStatus.step_1_completed && 
                                                              stepStatus.step_2_completed && 
                                                              stepStatus.step_3_completed && 
                                                              stepStatus.step_4_completed && 
                                                              stepStatus.step_5_completed && 
                                                              stepStatus.step_6_completed && 
                                                              stepStatus.step_7_completed && 
                                                              stepStatus.step_8_completed &&
                                                              stepStatus.step_9_completed;
                                    if (allAfterCompleted) {
                                        nextStep = 9;
                                    }
                                    
                                    const nextTabId = Object.keys(tabStepMap).find(key => tabStepMap[key] === nextStep);
                                    if (nextTabId) {
                                        $('#' + nextTabId).tab('show');
                                        currentStep = nextStep;
                                        updateFooterButtons();
                                    }
                                }, 500);
                            } else {
                                // Only show completion message on step 9 (last step)
                                // Check if all steps are actually completed
                                const allStepsCompleted = stepStatus.step_1_completed && 
                                                          stepStatus.step_2_completed && 
                                                          stepStatus.step_3_completed && 
                                                          stepStatus.step_4_completed && 
                                                          stepStatus.step_5_completed && 
                                                          stepStatus.step_6_completed && 
                                                          stepStatus.step_7_completed && 
                                                          stepStatus.step_8_completed &&
                                                          stepStatus.step_9_completed;
                                
                                if (allStepsCompleted && (response.final_status === 'complete' || stepStatus.final_status === 'complete')) {
                                    // Show success message
                                    Swal.fire({
                                        icon: 'success',
                                        title: '@lang('app.allStepsCompleted')',
                                        text: '@lang('messages.recordSaved')',
                                        showConfirmButton: true,
                                        confirmButtonText: 'OK',
                                        customClass: {
                                            confirmButton: "btn btn-primary",
                                        },
                                    }).then(function() {
                                        // Redirect to new lead form (without lead_id)
                                        window.location.href = '{{ route("add-lead.index") }}';
                                    });
                                } else {
                                    // Step 9 saved but not all steps complete yet, just show regular success
                                    // This shouldn't happen, but just in case
                                }
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: response.message || '@lang('messages.errorOccurred')',
                                toast: true,
                                position: "top-end",
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                },
                                showClass: {
                                    popup: "swal2-noanimation",
                                    backdrop: "swal2-noanimation",
                                },
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = '@lang('messages.errorOccurred')';
                        
                        // Handle validation errors from backend
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON.errors) {
                                // Laravel validation errors - collect all errors
                                const errors = xhr.responseJSON.errors;
                                const errorList = [];
                                for (let field in errors) {
                                    if (errors.hasOwnProperty(field)) {
                                        errorList.push(errors[field][0]);
                                    }
                                }
                                errorMessage = errorList.length === 1 
                                    ? errorList[0] 
                                    : errorList.slice(0, 3).join('<br>') + (errorList.length > 3 ? '<br>... and ' + (errorList.length - 3) + ' more' : '');
                            }
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            html: errorMessage,
                            toast: true,
                            position: "top-end",
                            timer: errorMessage.includes('<br>') ? 5000 : 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                            showClass: {
                                popup: "swal2-noanimation",
                                backdrop: "swal2-noanimation",
                            },
                        });
                    },
                    complete: function() {
                        $saveBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            }

            // Handle save button click
            $('#save-lead-form').on('click', function(e) {
                e.preventDefault();
                saveCurrentStep();
            });

            // Handle previous button click
            $('#btn-previous').on('click', function(e) {
                e.preventDefault();
                if (currentStep > 1) {
                    const prevStep = currentStep - 1;
                    const prevTabId = Object.keys(tabStepMap).find(key => tabStepMap[key] === prevStep);
                    if (prevTabId) {
                        // Use Bootstrap tab API to switch tabs properly
                        $('#' + prevTabId).tab('show');
                        // Update current step will be handled by the shown.bs.tab event
                    }
                }
            });

            // Track tab changes - ensure tab and pane are synchronized
            $('.nav-link-lead').on('shown.bs.tab', function(e) {
                const tabId = $(e.target).attr('id');
                currentStep = tabStepMap[tabId] || 1;
                updateFooterButtons();
                
                // Ensure the correct tab pane is active
                const targetPane = $(e.target).attr('href') || $(e.target).data('target');
                if (targetPane) {
                    // Remove active class from all panes
                    $('.tab-pane').removeClass('show active');
                    // Add active class to target pane
                    $(targetPane).addClass('show active');
                }
                
                // Reload form data for the current step if lead_id exists
                if (currentLeadId && stepStatus['step_' + currentStep + '_completed']) {
                    // Reload data for this step to ensure all fields are populated
                    loadStepData(currentStep);
                }
            });
            
            // Function to load data for a specific step
            function loadStepData(stepNumber) {
                if (!currentLeadId) return;
                
                $.ajax({
                    url: '{{ route("add-lead.step-status", ":id") }}'.replace(':id', currentLeadId),
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response && response.step_data) {
                            // Only populate the current step's data
                            const stepKey = 'step_' + stepNumber + '_data';
                            if (response.step_data[stepKey]) {
                                const stepData = {};
                                stepData[stepKey] = response.step_data[stepKey];
                                populateFormFields(stepData);
                            }
                        }
                    },
                    error: function() {
                        // Silently fail - data might already be loaded
                    }
                });
            }
            
            // Handle tab click to prevent navigation to disabled tabs and ensure sync
            $('.nav-link-lead').on('click', function(e) {
                // Only proceed if tab is not disabled
                if ($(this).hasClass('disabled')) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        text: '@lang('app.pleaseCompletePreviousSteps')',
                        toast: true,
                        position: "top-end",
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                        showClass: {
                            popup: "swal2-noanimation",
                            backdrop: "swal2-noanimation",
                        },
                    });
                    return false;
                }
            });

            // Initialize on page load
            currentStep = getCurrentStep();
            
            // Load existing lead data if lead_id exists (with a small delay to ensure DOM is ready)
            if (currentLeadId) {
                setTimeout(function() {
                    loadExistingLeadData(currentLeadId);
                }, 500);
            }
            
            updateTabNavigation();
            updateFooterButtons();

        });
    </script>
@endpush

