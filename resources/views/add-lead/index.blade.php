@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/add-lead.css') }}">
@endpush

@section('content')
    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        <!-- Add Task Export Buttons End -->

        <div class="d-flex flex-column w-100 rounded bg-white">
            <!-- Move to Lead Button -->
            <div class="px-4 py-3 border-bottom-grey justify-content-between align-items-center" id="moveToLeadButtonContainer" style="display: none !important;">
                <h3 id="leadNumberDisplay">
                    @if(isset($newLead) && $newLead->id)
                        LEAD-{{ str_pad($newLead->id, 4, '0', STR_PAD_LEFT) }}
                    @else
                        --
                    @endif
                </h3>
                <button type="button" class="btn btn-primary" id="moveToLeadBtn" data-toggle="modal" data-target="#moveToLeadModal">
                    <i class="fa fa-user-plus mr-1"></i>Move to Lead
                </button>
            </div>
            <!-- Tabs Navigation -->
            <div class="s-b-n-header" id="tabs">
                <nav class="tabs px-4 border-bottom-grey">
                    <div class="nav" id="nav-tab" role="tablist">
                        <a class="nav-item-lead nav-link-lead f-14 active" id="nav-personal-tab" data-toggle="tab" href="#nav-personal" role="tab" aria-controls="nav-personal" aria-selected="true">
                            <div class="tab-item"><img src="{{ asset('img/icon/Personal_Details.svg') }}"></div>@lang('app.personalDetails')
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" id="nav-education-tab" data-toggle="tab" href="#nav-education" role="tab" aria-controls="nav-education" aria-selected="false">
                            <div class="tab-item"><img src="{{ asset('img/icon/Education.svg') }}"></div>@lang('app.education')
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" id="nav-experience-tab" data-toggle="tab" href="#nav-experience" role="tab" aria-controls="nav-experience" aria-selected="false">
                            <div class="tab-item"><img src="{{ asset('img/icon/Professional_Experience.svg') }}"></div>@lang('app.professionalExperience')
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
            <x-form id="addLeadForm" class="ajax-form" enctype="multipart/form-data">
                <input type="hidden" name="lead_id" id="lead_id" value="{{ $newLead->id ?? '' }}">
                <div class="tab-content p-20" id="nav-tabContent">
                    <!-- Personal Details Tab -->
                    <div class="tab-pane fade show active" id="nav-personal" role="tabpanel" aria-labelledby="nav-personal-tab">
                        <!-- Personal Details Section -->
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="lead_source" :fieldLabel="__('modules.lead.leadSource')">
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
                                <x-forms.label class="mt-3" fieldId="upload_resume" fieldLabel="Upload Resume">
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
                                <x-forms.label class="mt-3" fieldId="gender" :fieldLabel="__('app.gender')">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="gender" id="gender">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Male">@lang('app.male')</option>
                                    <option value="Female">@lang('app.female')</option>
                                    <option value="Other">@lang('app.other')</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="marital_status" :fieldLabel="__('app.maritalStatus')">
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
                                <x-forms.label class="mt-3" fieldId="date_of_birth" :fieldLabel="__('app.dateOfBirth')">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="date_of_birth" id="date_of_birth" max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="country_of_origin" :fieldLabel="__('app.countryOfOrigin')">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="country_of_origin" id="country_of_origin">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Home Address Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.homeAddress')</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="home_address" :fieldLabel="__('modules.lead.address')">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="home_address" id="home_address">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="home_city" :fieldLabel="__('app.city')">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="home_city" id="home_city">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="home_state" :fieldLabel="__('app.state')">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="home_state" id="home_state">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="home_pin_code" :fieldLabel="__('app.pinCode')">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="home_pin_code" id="home_pin_code" maxlength="6" pattern="[0-9]{6}" title="Please enter exactly 6 digits">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Mailing Address Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.mailingAddress')</h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mailing_same_as_home" id="mailing_same_as_home" value="1">
                                    <label class="form-check-label pl-3" for="mailing_same_as_home">
                                        @lang('app.mailingAddressAsAbove')
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mailing_address" :fieldLabel="__('modules.lead.address')">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mailing_address" id="mailing_address">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mailing_city" :fieldLabel="__('app.city')">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mailing_city" id="mailing_city">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mailing_state" :fieldLabel="__('app.state')">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mailing_state" id="mailing_state">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mailing_pin_code" :fieldLabel="__('app.pinCode')">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mailing_pin_code" id="mailing_pin_code" maxlength="6" pattern="[0-9]{6}" title="Please enter exactly 6 digits">
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
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.lastFiveYearsVisaStatus')</h6>
                        
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
                                <x-forms.label class="mt-3" fieldId="visa_issue_date" :fieldLabel="__('app.visaIssueDate')">
                                </x-forms.label>
                                <input type="month" class="form-control height-35 f-14" name="visa_issue_date" id="visa_issue_date" max="{{ date('Y-m') }}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="visa_expire_date" :fieldLabel="__('app.visaExpireDate')">
                                </x-forms.label>
                                <input type="month" class="form-control height-35 f-14" name="visa_expire_date" id="visa_expire_date">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="visa_category" :fieldLabel="__('app.visaCategory')">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="visa_category" id="visa_category">
                            </div>
                        </div>
                        
                        <!-- Visa Refusal Fields -->
                        <div id="visa_refusal_fields" style="display: none;">
                            <!-- Dynamic Visa Refusal Rows Container -->
                            <div id="visa-refusal-rows-container"></div>

                            <!-- Add More Visa Refusal Button -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary btn-sm" id="add-more-visa-refusal">
                                        <i class="fa fa-plus mr-1"></i>@lang('app.addMoreVisaRefusal')
                                    </button>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Languages Spoken Section -->
                <div class="row">
                            <div class="col-md-12">
                                <x-forms.label class="mt-3" fieldId="languages_spoken" :fieldLabel="__('app.languagesSpoken')">
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
                                <x-forms.label class="mt-3 mb-3" fieldId="visa_type" :fieldLabel="__('app.selectVisaType')">
                                </x-forms.label>
                                <div id="visa-type-radio-container">
                                    @if(isset($visaTypes) && $visaTypes->count() > 0)
                                        @foreach($visaTypes as $visaType)
                                            @php
                                                // Map visa type name to section identifier for backward compatibility
                                                $sectionMap = [
                                                    'PR' => 'pr',
                                                    'Permanent Residence' => 'pr',
                                                    'Visit Visa' => 'visit',
                                                    'Work Permit' => 'work',
                                                    'Student Visa' => 'student',
                                                ];
                                                $sectionId = strtolower(str_replace(' ', '_', $visaType->name));
                                                // Try to find a match in the map
                                                foreach($sectionMap as $key => $value) {
                                                    if(stripos($visaType->name, $key) !== false) {
                                                        $sectionId = $value;
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input visa-type" type="radio" name="visa_type" id="visa_{{ $visaType->id }}" value="{{ $visaType->id }}" data-section="{{ $sectionId }}">
                                                <label class="form-check-label" for="visa_{{ $visaType->id }}">{{ $visaType->name }}</label>
                                            </div>
                                        @endforeach
                                    @else
                                        {{-- Fallback to hardcoded options if no visa types in database --}}
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input visa-type" type="radio" name="visa_type" id="visa_pr" value="pr" data-section="pr">
                                            <label class="form-check-label" for="visa_pr">@lang('app.pr')</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input visa-type" type="radio" name="visa_type" id="visa_visit" value="visit" data-section="visit">
                                            <label class="form-check-label" for="visa_visit">@lang('app.visitVisa')</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input visa-type" type="radio" name="visa_type" id="visa_work" value="work" data-section="work">
                                            <label class="form-check-label" for="visa_work">@lang('app.workPermit')</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input visa-type" type="radio" name="visa_type" id="visa_student" value="student" data-section="student">
                                            <label class="form-check-label" for="visa_student">@lang('app.studentVisa')</label>
                                        </div>
                                    @endif
                                </div>
                    </div>
                </div>

                        <!-- PR Section -->
                        <div id="prSection" class="form-section d-none">
                <div class="row">
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="skill_assessment_letter" :fieldLabel="__('app.skillAssessmentLetter')">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="skill_assessment_letter" id="skill_assessment_letter">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Positive">@lang('app.positive')</option>
                                        <option value="Negative">@lang('app.negative')</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="pr_assessment_letter_file" :fieldLabel="__('app.addAssessmentLetter')">
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
                                    <x-forms.label class="mt-3" fieldId="pr_family" :fieldLabel="__('app.family')">
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
                                    <select class="form-control select-picker height-35 f-14 subclass-select" name="pr_subclass" id="pr_subclass" data-section="pr">
                                        <option value="">@lang('app.select')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Visit Visa Section -->
                        <div id="visitSection" class="form-section d-none">
                            <div class="row">
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="purpose_of_visit" :fieldLabel="__('app.purposeOfVisit')">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="purpose_of_visit" id="purpose_of_visit">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="visit_family" :fieldLabel="__('app.family')">
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
                                    <select class="form-control select-picker height-35 f-14 subclass-select" name="visit_subclass" id="visit_subclass" data-section="visit">
                                        <option value="">@lang('app.select')</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Work Permit Section -->
                        <div id="workSection" class="form-section d-none">
                            <div class="row">
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="preferred_designation" :fieldLabel="__('app.preferredDesignation')">
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
                                    <select class="form-control select-picker height-35 f-14 subclass-select" name="work_subclass" id="work_subclass" data-section="work">
                                        <option value="">@lang('app.select')</option>
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
                                    <x-forms.label class="mt-3" fieldId="term_intake" :fieldLabel="__('app.termIntake')">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="term_intake" id="term_intake">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="student_subclass" :fieldLabel="__('app.subclass')">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14 subclass-select" name="student_subclass" id="student_subclass" data-section="student">
                                        <option value="">@lang('app.select')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Passport Details Tab -->
                    <div class="tab-pane fade" id="nav-passport" role="tabpanel" aria-labelledby="nav-passport-tab">
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="passport_number" fieldLabel="Passport Number">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="passport_number" id="passport_number">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="issuing_country" fieldLabel="Issuing Country">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="issuing_country" id="issuing_country">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="city_where_issued" fieldLabel="City Where Issued">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="city_where_issued" id="city_where_issued">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="issuance_date" fieldLabel="Issue Date">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="issuance_date" id="issuance_date" max="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="expiration_date" fieldLabel="Expiration Date">
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
                                <x-forms.label class="mt-3" fieldId="passport_file_upload" fieldLabel="Add Passport">
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
                        <!-- Relative Contact Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.relativeContactInformation')</h6>
                        
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
                                <x-forms.label class="mt-3" fieldId="father_surname" fieldLabel="Father's Surname">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="father_surname" id="father_surname">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="father_given_name" fieldLabel="Father's Given Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="father_given_name" id="father_given_name">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="father_date_of_birth" fieldLabel="Father's Date of Birth">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="father_date_of_birth" id="father_date_of_birth" max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="father_occupation" fieldLabel="Father's Occupation">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="father_occupation" id="father_occupation">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="father_have_passport" fieldLabel="Have Passport">
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
                                <x-forms.label class="mt-3" fieldId="mother_surname" fieldLabel="Mother's Surname">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mother_surname" id="mother_surname">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mother_given_name" fieldLabel="Mother's Given Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mother_given_name" id="mother_given_name">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mother_date_of_birth" fieldLabel="Mother's Date of Birth">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="mother_date_of_birth" id="mother_date_of_birth" max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mother_occupation" fieldLabel="Mother's Occupation">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mother_occupation" id="mother_occupation">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mother_have_passport" fieldLabel="Have Passport">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="mother_have_passport" id="mother_have_passport">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="mother_passport_file_container" style="display: none;">
                                <x-forms.label class="mt-3" fieldId="mother_passport_file" fieldLabel="Add Mother Passport">
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
                                <x-forms.label class="mt-3" fieldId="spouse_passport_file" fieldLabel="Add Spouse Passport">
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
                                <input type="text" class="form-control height-35 f-14" name="spouse_postal_code" id="spouse_postal_code" maxlength="6" pattern="[0-9]{6}" title="Please enter exactly 6 digits">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_phone_number" fieldLabel="Spouse's Phone Number">
                                </x-forms.label>
                                <input type="text" maxlength="10" pattern="[0-9]{10}" title="Please enter exactly 10 digits" class="form-control height-35 f-14" name="spouse_phone_number" id="spouse_phone_number">
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
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.child') Details</h6>
                        
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
                                <x-forms.label class="mt-3" fieldId="tenth_passing_year" fieldLabel="10th Passing Year">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="tenth_passing_year" id="tenth_passing_year">
                                    <option value="">@lang('app.select')</option>
                                    @for($year = date('Y'); $year >= 1950; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="tenth_percentage" fieldLabel="Percentage">
                                </x-forms.label>
                                <div class="input-group">
                                    <input type="number" class="form-control height-35 f-14" name="tenth_percentage" id="tenth_percentage" min="0" max="100" step="0.01" maxlength="5">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="tenth_board_name" fieldLabel="Board Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="tenth_board_name" id="tenth_board_name">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="tenth_trial" fieldLabel="Trial">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="tenth_trial" id="tenth_trial">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="tenth_result_file" fieldLabel="Add 10th Result">
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
                                <div class="input-group">
                                    <input type="number" class="form-control height-35 f-14" name="twelfth_percentage" id="twelfth_percentage" min="0" max="100" step="0.01" maxlength="5">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
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
                                <div class="input-group">
                                    <input type="number" class="form-control height-35 f-14" name="graduation_percentage" id="graduation_percentage" min="0" max="100" step="0.01" maxlength="5">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
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
                                <div class="input-group">
                                    <input type="number" class="form-control height-35 f-14" name="post_graduation_percentage" id="post_graduation_percentage" min="0" max="100" step="0.01" maxlength="5">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
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
                                <x-forms.label class="mt-3" fieldId="property_home" fieldLabel="Home">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 valuation-input" name="property_home" id="property_home">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_land" fieldLabel="Land">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 valuation-input" name="property_land" id="property_land">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_plot" fieldLabel="Plot">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 valuation-input" name="property_plot" id="property_plot">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_commercials" fieldLabel="Commercials">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 valuation-input" name="property_commercials" id="property_commercials">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_other" fieldLabel="Other">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 valuation-input" name="property_other" id="property_other">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_shop" fieldLabel="Shop">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 valuation-input" name="property_shop" id="property_shop">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_gold" fieldLabel="Gold">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 valuation-input" name="property_gold" id="property_gold">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_silver" fieldLabel="Silver">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14 valuation-input" name="property_silver" id="property_silver">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="total_valuation" fieldLabel="Total Asset Valuation">
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
                                <input type="number" class="form-control height-35 f-14" name="loan_years" id="loan_years" min="0" step="1">
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
                            @lang('app.previous')
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

    <!-- Move to Lead Modal -->
    <div class="modal fade" id="moveToLeadModal" tabindex="-1" role="dialog" aria-labelledby="moveToLeadModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="moveToLeadModalLabel">Move to Lead</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="moveToLeadForm">
                        <input type="hidden" name="lead_id" id="move_lead_id" value="{{ $newLead->id ?? '' }}">
                        <div class="form-group">
                            <x-forms.label fieldId="move_lead_assign_to" :fieldLabel="__('app.leadAssignTo')" fieldRequired="true">
                            </x-forms.label>
                            <select class="form-control select-picker height-35 f-14" name="lead_assign_to" id="move_lead_assign_to" required>
                                <option value="">@lang('app.select') @lang('app.leadAssignTo')</option>
                                @if(isset($employees) && $employees)
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ (isset($newLead) && $newLead->lead_owner == $employee->id) || (!isset($newLead) && user()->id == $employee->id) ? 'selected' : '' }}>{{ $employee->name }}</option>
                                    @endforeach
                                @else
                                    <option value="{{ user()->id }}">{{ user()->name }}</option>
                                @endif
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('app.cancel')</button>
                    <button type="button" class="btn btn-primary" id="confirmMoveToLeadBtn">
                        <i class="fa fa-check mr-1"></i>Move to Lead
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Hide Move to Lead button immediately if no lead_id in URL
        (function() {
            const urlParams = new URLSearchParams(window.location.search);
            const leadId = urlParams.get('lead_id');
            if (!leadId) {
                // Hide button if no lead_id in URL - run immediately
                setTimeout(function() {
                    const btnContainer = document.getElementById('moveToLeadButtonContainer');
                    if (btnContainer) {
                        btnContainer.classList.remove('d-flex');
                        btnContainer.style.display = 'none';
                    }
                }, 0);
            }
        })();
        
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
                    
                    // Clear all Visa Refusal fields
                    $('#visa-refusal-rows-container').empty();
                    $('#visa_rejection_date').val('');
                    $('#visa_refusal_category').val('');
                    $('#visa_refusal_reason').val('');
                    visaRefusalCounter = 0;
                } else if (selectedValue === 'refusal') {
                    // Show Visa Refusal fields, hide Visa Granted fields
                    $('#visa_granted_fields').hide();
                    $('#visa_refusal_fields').show();
                    
                    // Clear all Visa Granted fields
                    $('#visa_issue_date').val('');
                    $('#visa_expire_date').val('');
                    $('#visa_category').val('');
                    
                    // Initialize with one blank visa refusal if none exist
                    if ($('#visa-refusal-rows-container .visa-refusal-row').length === 0) {
                        addVisaRefusalRow();
                    }
                } else {
                    // If neither is selected, hide all fields
                    $('#visa_granted_fields').hide();
                    $('#visa_refusal_fields').hide();
                    
                    // Clear all fields
                    $('#visa_issue_date').val('');
                    $('#visa_expire_date').val('');
                    $('#visa_category').val('');
                    $('#visa-refusal-rows-container').empty();
                    $('#visa_rejection_date').val('');
                    $('#visa_refusal_category').val('');
                    $('#visa_refusal_reason').val('');
                    visaRefusalCounter = 0;
                }
            });
            
            // Initially hide all dependent fields
            $('#visa_granted_fields').hide();
            $('#visa_refusal_fields').hide();

            // Visa Refusal functionality - Dynamic visa refusals management
            let visaRefusalCounter = 0;
            
            // Function to get next visa refusal number
            function getNextVisaRefusalNumber() {
                visaRefusalCounter++;
                return visaRefusalCounter;
            }
            
            // Function to update visa refusal row numbers based on their index
            function updateVisaRefusalRowNumbers() {
                const visaRefusalRows = $('.visa-refusal-row');
                visaRefusalRows.each(function(index) {
                    const $row = $(this);
                    const refusalNumber = index + 1; // Start from 1, not 0
                    const $refusalNumberElement = $row.find('.visa-refusal-row-number');
                    if ($refusalNumberElement.length > 0) {
                        $refusalNumberElement.text('Visa Refusal ' + refusalNumber);
                    }
                });
            }
            
            // Function to update remove button visibility based on visa refusal count
            function updateVisaRefusalRemoveButtons() {
                const visaRefusalRows = $('.visa-refusal-row');
                const refusalCount = visaRefusalRows.length;
                
                // Simple logic: 
                // - If there's only 1 refusal, hide all remove buttons
                // - If there are 2+ refusals, show all remove buttons
                visaRefusalRows.each(function() {
                    const $row = $(this);
                    const $removeBtn = $row.find('.remove-visa-refusal');
                    
                    if (refusalCount <= 1) {
                        // Only one refusal - hide remove button
                        $removeBtn.hide();
                    } else {
                        // Two or more refusals - show remove button
                        $removeBtn.show();
                    }
                });
            }
            
            // Function to generate visa refusal row HTML
            function generateVisaRefusalRow(refusalNum, refusalData = null) {
                const date = refusalData && refusalData.date ? refusalData.date : '';
                const category = refusalData && refusalData.category ? refusalData.category : '';
                const reason = refusalData && refusalData.reason ? refusalData.reason : '';
                
                return `
                    <div class="visa-refusal-row mb-3" id="visa-refusal-row-${refusalNum}" data-refusal-index="${refusalNum}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="visa-refusal-row-number f-15 font-weight-bold">Visa Refusal ${refusalNum}</div>
                            <button type="button" class="btn btn-danger btn-sm remove-visa-refusal" data-row-id="${refusalNum}" style="display: none;">
                                <i class="fa fa-trash mr-1"></i>Remove
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="visa_rejection_date_${refusalNum}" :fieldLabel="__('app.visaRejectionDate')">
                                </x-forms.label>
                                <input type="month" class="form-control height-35 f-14" name="visa_rejection_date_${refusalNum}" id="visa_rejection_date_${refusalNum}" max="{{ date('Y-m') }}" value="${date}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="visa_refusal_category_${refusalNum}" :fieldLabel="__('app.visaCategory')">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="visa_refusal_category_${refusalNum}" id="visa_refusal_category_${refusalNum}" value="${category}">
                            </div>
                            <div class="col-md-5">
                                <x-forms.label class="mt-3" fieldId="visa_refusal_reason_${refusalNum}" :fieldLabel="__('app.reason')">
                                </x-forms.label>
                                <textarea class="form-control f-14" rows="2" name="visa_refusal_reason_${refusalNum}" id="visa_refusal_reason_${refusalNum}">${reason}</textarea>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Function to add a visa refusal row
            function addVisaRefusalRow(refusalData = null) {
                const refusalNum = getNextVisaRefusalNumber();
                const newRow = generateVisaRefusalRow(refusalNum, refusalData);
                
                // Append to the visa-refusal-rows-container
                $('#visa-refusal-rows-container').append(newRow);
                
                // Update remove buttons visibility and refusal row numbers
                setTimeout(function() {
                    updateVisaRefusalRemoveButtons();
                    updateVisaRefusalRowNumbers();
                }, 100);
            }
            
            // Add More Visa Refusal button click handler
            $('#add-more-visa-refusal').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                addVisaRefusalRow();
            });

            // Remove visa refusal row
            $(document).on('click', '.remove-visa-refusal', function() {
                const rowId = $(this).data('row-id');
                const refusalRows = $('.visa-refusal-row');
                const refusalCount = refusalRows.length;
                
                // Prevent deletion if it's the only refusal
                if (refusalCount <= 1) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'At least one visa refusal is required. You cannot delete the only refusal.',
                        toast: true,
                        position: "top-end",
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });
                    return;
                }
                
                // If there are 2+ refusals, allow deletion
                // Remove the refusal row
                $(`#visa-refusal-row-${rowId}`).remove();
                
                // Update remove buttons and refusal row numbers after deletion
                // This will hide buttons if only 1 refusal remains
                updateVisaRefusalRemoveButtons();
                updateVisaRefusalRowNumbers();
            });

            // Form submission
            $('#save-lead-form').on('click', function() {
                // Form validation and submission logic will be added here
                // This should be handled by the ajax-form class
            });

            // Tab navigation - Previous button (old handler - will be replaced by the one below)
            // This is kept for backward compatibility but the main handler is below

            // Flag to prevent subclass loading during form data load
            let isLoadingFormData = false;
            
            // Handle visa type radio buttons for Client Preference tab (dynamic)
            $(document).on('change', 'input[name="visa_type"]', function() {
                const selectedValue = $(this).val();
                const sectionId = $(this).data('section');
                const visaTypeId = selectedValue; // This is now the visa type ID from database
                
                // Clear ALL other visa type forms when any visa type is selected
                // Clear PR fields (if not selected)
                if (sectionId !== 'pr') {
                    $('#skill_assessment_letter, #pr_preferred_country, #pr_preferred_state, #pr_family, #pr_subclass').val('').selectpicker('refresh');
                    $('#pr_assessment_letter_file').val('');
                    // Remove hidden file input if exists
                    $('#pr_assessment_letter_file_hidden').remove();
                    // Remove file display if exists
                    $('#pr_assessment_letter_file').next('.file-name-display').remove();
                }
                
                // Clear Visit Visa fields (if not selected)
                if (sectionId !== 'visit') {
                    $('#purpose_of_visit, #visit_family, #visit_preferred_country, #visit_preferred_state, #visit_subclass').val('').selectpicker('refresh');
                }
                
                // Clear Work Permit fields (if not selected)
                if (sectionId !== 'work') {
                    $('#preferred_designation, #work_industry, #on_role_off_role, #work_preferred_country, #work_preferred_state, #work_category, #work_subclass').val('').selectpicker('refresh');
                }
                
                // Clear Student Visa fields (if not selected)
                if (sectionId !== 'student') {
                    $('#preferred_course, #student_country, #university, #term_intake, #student_subclass').val('').selectpicker('refresh');
                }
                
                // Hide all sections
                $('#prSection, #visitSection, #workSection, #studentSection').addClass('d-none');
                
                // Show selected section based on data-section attribute
                if (sectionId === 'pr') {
                    $('#prSection').removeClass('d-none');
                } else if (sectionId === 'visit') {
                    $('#visitSection').removeClass('d-none');
                } else if (sectionId === 'work') {
                    $('#workSection').removeClass('d-none');
                } else if (sectionId === 'student') {
                    $('#studentSection').removeClass('d-none');
                }
                
                // Load subclasses for the selected visa type (only if not loading form data)
                if (visaTypeId && !isNaN(visaTypeId) && !isLoadingFormData) {
                    loadSubclassesForVisaType(visaTypeId, sectionId);
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
            
            // Function to load subclasses for a visa type
            function loadSubclassesForVisaType(visaTypeId, sectionId, callback) {
                // Find the subclass select field for this section
                const subclassSelect = $('.subclass-select[data-section="' + sectionId + '"]');
                
                if (subclassSelect.length === 0) {
                    if (callback) callback();
                    return;
                }
                
                // Show loading state
                subclassSelect.prop('disabled', true);
                
                // Make AJAX request to get subclasses
                $.ajax({
                    url: "{{ route('add-lead.get-subclasses', ':visaTypeId') }}".replace(':visaTypeId', visaTypeId),
                    type: 'GET',
                    success: function(response) {
                        if (response.status === 'success' && response.options) {
                            // Clear existing options except the first "Select" option
                            subclassSelect.empty();
                            subclassSelect.html(response.options);
                            
                            // Reinitialize selectpicker
                            if (subclassSelect.data('selectpicker')) {
                                subclassSelect.selectpicker('destroy');
                            }
                            subclassSelect.selectpicker();
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading subclasses:', xhr);
                        // On error, just ensure the select has at least the default option
                        if (subclassSelect.find('option').length === 0) {
                            subclassSelect.html('<option value="">@lang('app.select')</option>');
                        }
                    },
                    complete: function() {
                        subclassSelect.prop('disabled', false);
                        if (subclassSelect.data('selectpicker')) {
                            subclassSelect.selectpicker('refresh');
                        }
                        // Execute callback if provided
                        if (callback) {
                            callback();
                        }
                    }
                });
            }
            
            // Function to set subclass value after subclasses are loaded
            function setSubclassValue(sectionId, data) {
                const subclassFieldMap = {
                    'pr': 'pr_subclass',
                    'visit': 'visit_subclass',
                    'work': 'work_subclass',
                    'student': 'student_subclass'
                };
                
                const fieldName = subclassFieldMap[sectionId];
                if (fieldName && data[fieldName]) {
                    const subclassSelect = $('#' + fieldName);
                    if (subclassSelect.length > 0) {
                        // Set the value
                        subclassSelect.val(data[fieldName]);
                        
                        // Ensure selectpicker is initialized and refreshed
                        if (!subclassSelect.data('selectpicker')) {
                            subclassSelect.selectpicker();
                        }
                        
                        // Refresh with a small delay to ensure options are loaded
                        setTimeout(function() {
                            subclassSelect.selectpicker('refresh');
                            // Double-check the value is set after refresh
                            if (subclassSelect.val() !== data[fieldName]) {
                                subclassSelect.val(data[fieldName]);
                                subclassSelect.selectpicker('refresh');
                            }
                        }, 100);
                    }
                }
            }
            
            // Handle PR Assessment Letter file input change
            $('#pr_assessment_letter_file').on('change', function() {
                const file = this.files[0];
                if (file) {
                    // Remove hidden input when new file is selected
                    $('#pr_assessment_letter_file_hidden').remove();
                }
            });

            // Relative Contact functionality - Dynamic relative contacts management
            let relativeContactCounter = 0;
            
            // Function to get next relative contact number
            function getNextRelativeContactNumber() {
                relativeContactCounter++;
                return relativeContactCounter;
            }
            
            // Function to update relative contact row numbers based on their index
            function updateRelativeContactRowNumbers() {
                const relativeContactRows = $('.relative-contact-row');
                relativeContactRows.each(function(index) {
                    const $row = $(this);
                    const contactNumber = index + 1; // Start from 1, not 0
                    const $contactNumberElement = $row.find('.relative-contact-row-number');
                    $contactNumberElement.text('Relative Contact ' + contactNumber);
                });
            }
            
            // Function to update remove button visibility based on relative contact count
            function updateRelativeContactRemoveButtons() {
                const relativeContactRows = $('.relative-contact-row');
                const contactCount = relativeContactRows.length;
                
                // Simple logic: 
                // - If there's only 1 contact, hide all remove buttons
                // - If there are 2+ contacts, show all remove buttons
                relativeContactRows.each(function() {
                    const $row = $(this);
                    const $removeBtn = $row.find('.remove-relative-contact');
                    
                    if (contactCount <= 1) {
                        // Only one contact - hide remove button
                        $removeBtn.hide();
                    } else {
                        // Two or more contacts - show remove button
                        $removeBtn.show();
                    }
                });
            }
            
            // Function to generate relative contact row HTML
            function generateRelativeContactRow(contactNum, contactData = null) {
                const surname = contactData && contactData.relative_surname ? contactData.relative_surname : '';
                const givenName = contactData && contactData.relative_given_name ? contactData.relative_given_name : '';
                const orgName = contactData && contactData.relative_organization_name ? contactData.relative_organization_name : '';
                const relationship = contactData && contactData.relative_relationship ? contactData.relative_relationship : '';
                const address = contactData && contactData.relative_contact_address ? contactData.relative_contact_address : '';
                const city = contactData && contactData.relative_city ? contactData.relative_city : '';
                const state = contactData && contactData.relative_state ? contactData.relative_state : '';
                const zipCode = contactData && contactData.relative_zip_code ? contactData.relative_zip_code : '';
                const email = contactData && contactData.relative_email_address ? contactData.relative_email_address : '';
                const phone = contactData && contactData.relative_phone_number ? contactData.relative_phone_number : '';
                
                return `
                    <div class="relative-contact-row mb-4" id="relative-contact-row-${contactNum}" data-contact-index="${contactNum}">
                        <div class="relative-contact-row-header mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="relative-contact-row-number f-15 font-weight-bold">Relative Contact ${contactNum}</div>
                                <button type="button" class="btn btn-danger btn-sm remove-relative-contact" data-row-id="${contactNum}" style="display: none;">
                                    <i class="fa fa-trash mr-1"></i>Remove
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_surname_${contactNum}" fieldLabel="Surname">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_surname_${contactNum}" id="relative_surname_${contactNum}" value="${surname}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_given_name_${contactNum}" fieldLabel="Given Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_given_name_${contactNum}" id="relative_given_name_${contactNum}" value="${givenName}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_organization_name_${contactNum}" fieldLabel="Organization Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_organization_name_${contactNum}" id="relative_organization_name_${contactNum}" value="${orgName}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_relationship_${contactNum}" fieldLabel="Relationship To You">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_relationship_${contactNum}" id="relative_relationship_${contactNum}" value="${relationship}">
                            </div>
                            <div class="col-md-12">
                                <x-forms.label class="mt-3" fieldId="relative_contact_address_${contactNum}" fieldLabel="Contact Address">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_contact_address_${contactNum}" id="relative_contact_address_${contactNum}" value="${address}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_city_${contactNum}" fieldLabel="City">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_city_${contactNum}" id="relative_city_${contactNum}" value="${city}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_state_${contactNum}" fieldLabel="State">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_state_${contactNum}" id="relative_state_${contactNum}" value="${state}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_zip_code_${contactNum}" fieldLabel="Zip Code">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_zip_code_${contactNum}" id="relative_zip_code_${contactNum}" maxlength="6" pattern="[0-9]{6}" title="Please enter exactly 6 digits" value="${zipCode}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_email_address_${contactNum}" fieldLabel="Email Address">
                                </x-forms.label>
                                <input type="email" class="form-control height-35 f-14" name="relative_email_address_${contactNum}" id="relative_email_address_${contactNum}" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value="${email}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_phone_number_${contactNum}" fieldLabel="Phone Number">
                                </x-forms.label>
                                <input type="number" max="9999999999" class="form-control height-35 f-14" name="relative_phone_number_${contactNum}" id="relative_phone_number_${contactNum}" oninput="if(this.value.length > 10) this.value = this.value.slice(0,10);" value="${phone}">
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Function to add a relative contact row
            function addRelativeContactRow(contactData = null) {
                const contactNum = getNextRelativeContactNumber();
                const newRow = generateRelativeContactRow(contactNum, contactData);
                
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
                    
                    // Update remove buttons visibility and contact row numbers
                    updateRelativeContactRemoveButtons();
                    updateRelativeContactRowNumbers();
                }, 100);
            }
            
            // Add More Relative Contact button click handler
            $('#add-more-relative').on('click', function() {
                addRelativeContactRow();
            });

            // Remove relative contact row
            $(document).on('click', '.remove-relative-contact', function() {
                const rowId = $(this).data('row-id');
                const contactRows = $('.relative-contact-row');
                const contactCount = contactRows.length;
                
                // Prevent deletion if it's the only contact
                if (contactCount <= 1) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'At least one relative contact is required. You cannot delete the only contact.',
                        toast: true,
                        position: "top-end",
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });
                    return;
                }
                
                // If there are 2+ contacts, allow deletion
                // Remove the contact row
                $(`#relative-contact-row-${rowId}`).remove();
                
                // Update remove buttons and contact row numbers after deletion
                // This will hide buttons if only 1 contact remains
                updateRelativeContactRemoveButtons();
                updateRelativeContactRowNumbers();
            });

            // Child functionality - Dynamic children management
            let childCounter = 0;
            
            // Function to get next child number
            function getNextChildNumber() {
                childCounter++;
                return childCounter;
            }
            
            // Function to update child row numbers based on their index
            function updateChildRowNumbers() {
                const childRows = $('.child-row');
                childRows.each(function(index) {
                    const $row = $(this);
                    const childNumber = index + 1; // Start from 1, not 0
                    const $childNumberElement = $row.find('.child-row-number');
                    $childNumberElement.text('Child ' + childNumber);
                });
            }
            
            // Function to update remove button visibility based on child count
            function updateRemoveButtons() {
                const childRows = $('.child-row');
                const childCount = childRows.length;
                
                // Simple logic: 
                // - If there's only 1 child, hide all remove buttons
                // - If there are 2+ children, show all remove buttons
                childRows.each(function() {
                    const $row = $(this);
                    const $removeBtn = $row.find('.remove-child');
                    
                    if (childCount <= 1) {
                        // Only one child - hide remove button
                        $removeBtn.hide();
                    } else {
                        // Two or more children - show remove button
                        $removeBtn.show();
                    }
                });
            }
            
            // Function to generate child row HTML
            function generateChildRow(childNum, childData = null) {
                const childName = childData && childData.child_name ? childData.child_name : '';
                const childAge = childData && childData.child_age ? childData.child_age : '';
                const childDob = childData && childData.child_date_of_birth ? childData.child_date_of_birth : '';
                const childCity = childData && childData.child_city_of_birth ? childData.child_city_of_birth : '';
                const childGender = childData && childData.child_gender ? childData.child_gender : '';
                const childHavePassport = childData && childData.child_have_passport ? childData.child_have_passport : '';
                const showPassportContainer = childHavePassport === 'Yes' ? '' : 'style="display: none;"';
                
                return `
                    <div class="child-row mb-4" id="child-row-${childNum}" data-child-index="${childNum}">
                        <div class="child-row-header mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="child-row-number f-15 font-weight-bold">Child ${childNum}</div>
                                <button type="button" class="btn btn-danger btn-sm remove-child" data-row-id="${childNum}" style="display: none;">
                                    <i class="fa fa-trash mr-1"></i>Remove
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="child_name_${childNum}" fieldLabel="Child's Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="child_name_${childNum}" id="child_name_${childNum}" value="${childName}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="child_age_${childNum}" fieldLabel="Child's Age">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="child_age_${childNum}" id="child_age_${childNum}" pattern="[0-9]*" title="Please enter only numbers" value="${childAge}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="child_date_of_birth_${childNum}" fieldLabel="Date of Birth">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="child_date_of_birth_${childNum}" id="child_date_of_birth_${childNum}" max="{{ date('Y-m-d', strtotime('-1 day')) }}" value="${childDob}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="child_city_of_birth_${childNum}" fieldLabel="City of Birth">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="child_city_of_birth_${childNum}" id="child_city_of_birth_${childNum}" value="${childCity}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="child_gender_${childNum}" fieldLabel="Gender">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="child_gender_${childNum}" id="child_gender_${childNum}">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Male" ${childGender === 'Male' ? 'selected' : ''}>Male</option>
                                    <option value="Female" ${childGender === 'Female' ? 'selected' : ''}>Female</option>
                                    <option value="Prefer not to say" ${childGender === 'Prefer not to say' ? 'selected' : ''}>Prefer not to say</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="child_document_file_${childNum}" fieldLabel="Add Child Document">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" name="child_document_file_${childNum}" id="child_document_file_${childNum}" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880" data-child-index="${childNum}">
                                ${childData && childData.child_document_file ? `<div class="mt-1"><small class="text-muted file-name-display"><a href="#" class="existing-file-link" data-file="${childData.child_document_file}" target="_blank">${childData.child_document_file}</a></small></div>` : ''}
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="child_have_passport_${childNum}" fieldLabel="Have Passport">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="child_have_passport_${childNum}" id="child_have_passport_${childNum}">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Yes" ${childHavePassport === 'Yes' ? 'selected' : ''}>Yes</option>
                                    <option value="No" ${childHavePassport === 'No' ? 'selected' : ''}>No</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="child_passport_file_container_${childNum}" ${showPassportContainer}>
                                <x-forms.label class="mt-3" fieldId="child_passport_file_${childNum}" fieldLabel="Add Child Passport">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" name="child_passport_file_${childNum}" id="child_passport_file_${childNum}" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880" data-child-index="${childNum}">
                                ${childData && childData.child_passport_file ? `<div class="mt-1"><small class="text-muted file-name-display"><a href="#" class="existing-file-link" data-file="${childData.child_passport_file}" target="_blank">${childData.child_passport_file}</a></small></div>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Function to add a child row
            function addChildRow(childData = null) {
                const childNum = getNextChildNumber();
                const newRow = generateChildRow(childNum, childData);
                
                // Append to the child-rows-container
                $('#child-rows-container').append(newRow);
                
                // Update file URLs for existing files if childData is provided
                if (childData) {
                    const $childRow = $('#child-row-' + childNum);
                    
                    // Update passport file link
                    if (childData.child_passport_file) {
                        const passportFileUrl = getFileUrl('child_passport_file', childData.child_passport_file);
                        const $passportLink = $childRow.find('.existing-file-link[data-file="' + childData.child_passport_file + '"]');
                        if ($passportLink.length > 0 && passportFileUrl) {
                            $passportLink.attr('href', passportFileUrl).attr('target', '_blank');
                        }
                    }
                    
                    // Update document file link
                    if (childData.child_document_file) {
                        const documentFileUrl = getFileUrl('child_document_file', childData.child_document_file);
                        const $documentLink = $childRow.find('.existing-file-link[data-file="' + childData.child_document_file + '"]');
                        if ($documentLink.length > 0 && documentFileUrl) {
                            $documentLink.attr('href', documentFileUrl).attr('target', '_blank');
                        }
                    }
                }
                
                // Reinitialize select picker for the new row
                setTimeout(function() {
                    $('.select-picker').each(function() {
                        if (!$(this).data('selectpicker')) {
                            $(this).selectpicker();
                        } else {
                            $(this).selectpicker('refresh');
                        }
                    });
                    
                    // Trigger change event for passport field if needed
                    if (childData && childData.child_have_passport === 'Yes') {
                        $('#child_have_passport_' + childNum).trigger('changed.bs.select');
                    }
                    
                    // Update remove buttons visibility and child row numbers
                    updateRemoveButtons();
                    updateChildRowNumbers();
                }, 100);
            }
            
            // Add More Child button click handler
            $('#add-more-child').on('click', function() {
                addChildRow();
            });

            // Remove child row
            $(document).on('click', '.remove-child', function() {
                const rowId = $(this).data('row-id');
                const childRows = $('.child-row');
                const childCount = childRows.length;
                
                // Prevent deletion if it's the only child
                if (childCount <= 1) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'At least one child is required. You cannot delete the only child.',
                        toast: true,
                        position: "top-end",
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });
                    return;
                }
                
                // If there are 2+ children, allow deletion
                // Remove the child row
                $(`#child-row-${rowId}`).remove();
                
                // Update remove buttons and child row numbers after deletion
                // This will hide buttons if only 1 child remains
                updateRemoveButtons();
                updateChildRowNumbers();
            });
            
            // Function to update all existing file links with proper URLs
            function updateChildFileLinks() {
                $('.child-row').each(function() {
                    const $childRow = $(this);
                    const childNum = $childRow.data('child-index');
                    
                    // Update passport file link
                    const $passportLink = $childRow.find('#child_passport_file_container_' + childNum + ' .existing-file-link[data-file]');
                    if ($passportLink.length > 0) {
                        const fileName = $passportLink.attr('data-file');
                        if (fileName) {
                            const fileUrl = getFileUrl('child_passport_file', fileName);
                            if (fileUrl) {
                                $passportLink.attr('href', fileUrl).attr('target', '_blank');
                            }
                        }
                    }
                    
                    // Update document file link
                    const $documentLink = $childRow.find('#child_document_file_' + childNum).closest('.col-md-3').find('.existing-file-link[data-file]');
                    if ($documentLink.length > 0) {
                        const fileName = $documentLink.attr('data-file');
                        if (fileName) {
                            const fileUrl = getFileUrl('child_document_file', fileName);
                            if (fileUrl) {
                                $documentLink.attr('href', fileUrl).attr('target', '_blank');
                            }
                        }
                    }
                });
            }
            
            function updateOtherDegreeFileLinks() {
                $('.other-degree-row').each(function() {
                    const $degreeRow = $(this);
                    const degreeNum = $degreeRow.data('degree-index');
                    
                    // Update result file link
                    const $resultLink = $degreeRow.find('#other_degree_result_file_' + degreeNum).closest('.col-md-3').find('.existing-file-link[data-file]');
                    if ($resultLink.length > 0) {
                        const fileName = $resultLink.attr('data-file');
                        if (fileName) {
                            const fileUrl = getFileUrl('other_degree_result_file', fileName);
                            if (fileUrl) {
                                $resultLink.attr('href', fileUrl).attr('target', '_blank');
                            }
                        }
                    }
                });
            }
            
            // Handle clicks on existing file links to ensure they open properly
            $(document).on('click', '.existing-file-link', function(e) {
                const href = $(this).attr('href');
                // If href is still "#", try to get the URL from data-file attribute
                if (!href || href === '#') {
                    const fileName = $(this).attr('data-file');
                    if (fileName) {
                        // Determine file type from context
                        const $container = $(this).closest('.col-md-3');
                        let fileType = 'child_document_file';
                        // Check if this is in a passport file container
                        if ($container.attr('id') && $container.attr('id').includes('passport_file_container')) {
                            fileType = 'child_passport_file';
                        } else {
                            // Check if there's a passport file input in the same container
                            const $passportInput = $container.find('input[id^="child_passport_file_"]');
                            if ($passportInput.length > 0) {
                                fileType = 'child_passport_file';
                            }
                        }
                        const fileUrl = getFileUrl(fileType, fileName);
                        if (fileUrl) {
                            $(this).attr('href', fileUrl).attr('target', '_blank');
                            window.open(fileUrl, '_blank');
                            e.preventDefault();
                            return false;
                        }
                    }
                    // Prevent default if we couldn't set a proper URL
                    e.preventDefault();
                    return false;
                }
                // Link has proper URL, let it open normally (target="_blank" is already set)
            });
            
            // Initialize with one blank child on page load
            $(document).ready(function() {
                // This will be handled after data loading check
            });

            // Other Degree functionality (similar to children)
            let otherDegreeCounter = 0;
            
            // Function to get next other degree number
            function getNextOtherDegreeNumber() {
                otherDegreeCounter++;
                return otherDegreeCounter;
            }
            
            // Function to update other degree row numbers
            function updateOtherDegreeRowNumbers() {
                const otherDegreeRows = $('.other-degree-row');
                otherDegreeRows.each(function(index) {
                    const $row = $(this);
                    const degreeNumber = index + 1; // Start from 1, not 0
                    const $degreeNumberElement = $row.find('.other-degree-row-number');
                    if ($degreeNumberElement.length > 0) {
                        $degreeNumberElement.text('Other Degree ' + degreeNumber);
                    }
                });
            }
            
            // Function to update remove buttons visibility
            function updateOtherDegreeRemoveButtons() {
                const otherDegreeRows = $('.other-degree-row');
                const otherDegreeCount = otherDegreeRows.length;
                
                // Simple logic: 
                // - If there's only 1 other degree, hide all remove buttons
                // - If there are 2+ other degrees, show all remove buttons
                if (otherDegreeCount <= 1) {
                    otherDegreeRows.find('.remove-other-degree').hide();
                } else {
                    otherDegreeRows.find('.remove-other-degree').show();
                }
            }
            
            // Function to generate other degree row HTML
            function generateOtherDegreeRow(degreeNum, degreeData = null) {
                const otherDegree = degreeData && degreeData.other_degree ? degreeData.other_degree : '';
                const universityName = degreeData && degreeData.other_degree_university_name ? degreeData.other_degree_university_name : '';
                const percentage = degreeData && degreeData.other_degree_percentage ? degreeData.other_degree_percentage : '';
                const passingYear = degreeData && degreeData.other_degree_passing_year ? degreeData.other_degree_passing_year : '';
                const trial = degreeData && degreeData.other_degree_trial ? degreeData.other_degree_trial : '';
                
                return `
                    <div class="other-degree-row mb-4" id="other-degree-row-${degreeNum}" data-degree-index="${degreeNum}">
                        <div class="other-degree-row-header mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="other-degree-row-number f-15 font-weight-bold">Other Degree ${degreeNum}</div>
                                <button type="button" class="btn btn-danger btn-sm remove-other-degree" data-row-id="${degreeNum}" style="display: none;">
                                    <i class="fa fa-trash mr-1"></i>Remove
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_degree_${degreeNum}" fieldLabel="Other Degree">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="other_degree_${degreeNum}" id="other_degree_${degreeNum}" value="${otherDegree}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_degree_university_name_${degreeNum}" fieldLabel="University Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="other_degree_university_name_${degreeNum}" id="other_degree_university_name_${degreeNum}" value="${universityName}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_degree_percentage_${degreeNum}" fieldLabel="Percentage">
                                </x-forms.label>
                                <div class="input-group">
                                    <input type="number" class="form-control height-35 f-14" name="other_degree_percentage_${degreeNum}" id="other_degree_percentage_${degreeNum}" min="0" max="100" step="0.01" maxlength="5" value="${percentage}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_degree_passing_year_${degreeNum}" fieldLabel="Passing Year">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="other_degree_passing_year_${degreeNum}" id="other_degree_passing_year_${degreeNum}">
                                    <option value="">@lang('app.select')</option>
                                    ${(() => {
                                        let yearOptions = '';
                                        const currentYear = new Date().getFullYear();
                                        for (let year = currentYear; year >= 1950; year--) {
                                            const selected = passingYear == year ? 'selected' : '';
                                            yearOptions += `<option value="${year}" ${selected}>${year}</option>`;
                                        }
                                        return yearOptions;
                                    })()}
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_degree_trial_${degreeNum}" fieldLabel="Trial">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="other_degree_trial_${degreeNum}" id="other_degree_trial_${degreeNum}" value="${trial}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_degree_result_file_${degreeNum}" fieldLabel="Add Other Degree Result">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" name="other_degree_result_file_${degreeNum}" id="other_degree_result_file_${degreeNum}" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880" data-degree-index="${degreeNum}">
                                ${degreeData && degreeData.other_degree_result_file ? `<div class="mt-1"><small class="text-muted file-name-display"><a href="#" class="existing-file-link" data-file="${degreeData.other_degree_result_file}" target="_blank">${degreeData.other_degree_result_file}</a></small></div>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Function to add an other degree row
            function addOtherDegreeRow(degreeData = null) {
                const degreeNum = getNextOtherDegreeNumber();
                const newRow = generateOtherDegreeRow(degreeNum, degreeData);
                
                // Append to the other-degree-rows-container
                $('#other-degree-rows-container').append(newRow);
                
                // Update file URLs for existing files if degreeData is provided
                if (degreeData) {
                    const $degreeRow = $('#other-degree-row-' + degreeNum);
                    
                    // Update result file link
                    if (degreeData.other_degree_result_file) {
                        const resultFileUrl = getFileUrl('other_degree_result_file', degreeData.other_degree_result_file);
                        const $resultLink = $degreeRow.find('.existing-file-link[data-file="' + degreeData.other_degree_result_file + '"]');
                        if ($resultLink.length > 0 && resultFileUrl) {
                            $resultLink.attr('href', resultFileUrl).attr('target', '_blank');
                        }
                    }
                }
                
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
                
                // Update remove buttons visibility and row numbers
                updateOtherDegreeRemoveButtons();
                updateOtherDegreeRowNumbers();
            }
            
            // Add More Education (Other Degree) button handler
            $('#add-more-education').on('click', function() {
                addOtherDegreeRow();
            });

            // Remove other degree row
            $(document).on('click', '.remove-other-degree', function() {
                const rowId = $(this).data('row-id');
                const otherDegreeCount = $('.other-degree-row').length;
                
                // Prevent deletion if only 1 other degree remains
                if (otherDegreeCount <= 1) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Cannot Remove',
                            text: 'At least one other degree must be present.',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    } else {
                        alert('At least one other degree must be present.');
                    }
                    return;
                }
                
                $(`#other-degree-row-${rowId}`).remove();
                
                // Update remove buttons visibility and row numbers
                updateOtherDegreeRemoveButtons();
                updateOtherDegreeRowNumbers();
            });

            // Job functionality (similar to children)
            let jobCounter = 0;
            
            // Function to get next job number
            function getNextJobNumber() {
                jobCounter++;
                return jobCounter;
            }
            
            // Function to update job row numbers
            function updateJobRowNumbers() {
                const jobRows = $('.job-row');
                jobRows.each(function(index) {
                    const $row = $(this);
                    const jobNumber = index + 1; // Start from 1, not 0
                    const $jobNumberElement = $row.find('.job-row-number');
                    if ($jobNumberElement.length > 0) {
                        $jobNumberElement.text('Job ' + jobNumber);
                    }
                });
            }
            
            // Function to update remove buttons visibility
            function updateJobRemoveButtons() {
                const jobRows = $('.job-row');
                const jobCount = jobRows.length;
                
                // Simple logic: 
                // - If there's only 1 job, hide all remove buttons
                // - If there are 2+ jobs, show all remove buttons
                if (jobCount <= 1) {
                    jobRows.find('.remove-job').hide();
                } else {
                    jobRows.find('.remove-job').show();
                }
            }
            
            // Function to generate job row HTML
            function generateJobRow(jobNum, jobData = null) {
                const durationFrom = jobData && jobData.job_duration_from ? jobData.job_duration_from : '';
                const durationTo = jobData && jobData.job_duration_to ? jobData.job_duration_to : '';
                const country = jobData && jobData.job_country ? jobData.job_country : '';
                const designation = jobData && jobData.job_designation ? jobData.job_designation : '';
                const companyName = jobData && jobData.job_company_name ? jobData.job_company_name : '';
                const salary = jobData && jobData.job_salary ? jobData.job_salary : '';
                
                return `
                    <div class="job-row mb-4" id="job-row-${jobNum}" data-job-index="${jobNum}">
                        <div class="job-row-header mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="job-row-number f-15 font-weight-bold">Job ${jobNum}</div>
                                <button type="button" class="btn btn-danger btn-sm remove-job" data-row-id="${jobNum}" style="display: none;">
                                    <i class="fa fa-trash mr-1"></i>Remove
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_duration_from_${jobNum}" fieldLabel="Duration - From">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="job_duration_from_${jobNum}" id="job_duration_from_${jobNum}" max="{{ date('Y-m-d', strtotime('-1 day')) }}" value="${durationFrom}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_duration_to_${jobNum}" fieldLabel="Duration - To">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="job_duration_to_${jobNum}" id="job_duration_to_${jobNum}" max="{{ date('Y-m-d', strtotime('-1 day')) }}" value="${durationTo}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_country_${jobNum}" fieldLabel="Country">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="job_country_${jobNum}" id="job_country_${jobNum}" value="${country}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_designation_${jobNum}" fieldLabel="Designation">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="job_designation_${jobNum}" id="job_designation_${jobNum}" value="${designation}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_company_name_${jobNum}" fieldLabel="Company Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="job_company_name_${jobNum}" id="job_company_name_${jobNum}" value="${companyName}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_salary_${jobNum}" fieldLabel="Salary">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="job_salary_${jobNum}" id="job_salary_${jobNum}" value="${salary}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_offer_letter_file_${jobNum}" fieldLabel="Add Offerletter">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" name="job_offer_letter_file_${jobNum}" id="job_offer_letter_file_${jobNum}" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880" data-job-index="${jobNum}">
                                ${jobData && jobData.job_offer_letter_file ? `<div class="mt-1"><small class="text-muted file-name-display"><a href="#" class="existing-file-link" data-file="${jobData.job_offer_letter_file}" target="_blank">${jobData.job_offer_letter_file}</a></small></div>` : ''}
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_experience_letter_file_${jobNum}" fieldLabel="Add Experience letter">
                                </x-forms.label>
                                <input class="form-control height-35 f-14" type="file" name="job_experience_letter_file_${jobNum}" id="job_experience_letter_file_${jobNum}" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880" data-job-index="${jobNum}">
                                ${jobData && jobData.job_experience_letter_file ? `<div class="mt-1"><small class="text-muted file-name-display"><a href="#" class="existing-file-link" data-file="${jobData.job_experience_letter_file}" target="_blank">${jobData.job_experience_letter_file}</a></small></div>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Function to add a job row
            function addJobRow(jobData = null) {
                const jobNum = getNextJobNumber();
                const newRow = generateJobRow(jobNum, jobData);
                
                // Append to the job-rows-container
                $('#job-rows-container').append(newRow);
                
                // Update file URLs for existing files if jobData is provided
                if (jobData) {
                    const $jobRow = $('#job-row-' + jobNum);
                    
                    // Update offer letter file link
                    if (jobData.job_offer_letter_file) {
                        const offerFileUrl = getFileUrl('job_offer_letter_file', jobData.job_offer_letter_file);
                        const $offerLink = $jobRow.find('.existing-file-link[data-file="' + jobData.job_offer_letter_file + '"]');
                        if ($offerLink.length > 0 && offerFileUrl) {
                            $offerLink.attr('href', offerFileUrl).attr('target', '_blank');
                        }
                    }
                    
                    // Update experience letter file link
                    if (jobData.job_experience_letter_file) {
                        const experienceFileUrl = getFileUrl('job_experience_letter_file', jobData.job_experience_letter_file);
                        const $experienceLink = $jobRow.find('.existing-file-link[data-file="' + jobData.job_experience_letter_file + '"]');
                        if ($experienceLink.length > 0 && experienceFileUrl) {
                            $experienceLink.attr('href', experienceFileUrl).attr('target', '_blank');
                        }
                    }
                }
                
                // Update remove buttons visibility and row numbers
                updateJobRemoveButtons();
                updateJobRowNumbers();
            }
            
            // Add More Job button handler
            $('#add-more-job').on('click', function() {
                addJobRow();
            });

            // Remove job row
            $(document).on('click', '.remove-job', function() {
                const rowId = $(this).data('row-id');
                const jobCount = $('.job-row').length;
                
                // Prevent deletion if only 1 job remains
                if (jobCount <= 1) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Cannot Remove',
                            text: 'At least one job must be present.',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    } else {
                        alert('At least one job must be present.');
                    }
                    return;
                }
                
                $(`#job-row-${rowId}`).remove();
                
                // Update remove buttons visibility and row numbers
                updateJobRemoveButtons();
                updateJobRowNumbers();
            });
            
            function updateJobFileLinks() {
                $('.job-row').each(function() {
                    const $jobRow = $(this);
                    const jobNum = $jobRow.data('job-index');
                    
                    // Update offer letter file link
                    const $offerLink = $jobRow.find('#job_offer_letter_file_' + jobNum).closest('.col-md-3').find('.existing-file-link[data-file]');
                    if ($offerLink.length > 0) {
                        const fileName = $offerLink.attr('data-file');
                        if (fileName) {
                            const fileUrl = getFileUrl('job_offer_letter_file', fileName);
                            if (fileUrl) {
                                $offerLink.attr('href', fileUrl).attr('target', '_blank');
                            }
                        }
                    }
                    
                    // Update experience letter file link
                    const $experienceLink = $jobRow.find('#job_experience_letter_file_' + jobNum).closest('.col-md-3').find('.existing-file-link[data-file]');
                    if ($experienceLink.length > 0) {
                        const fileName = $experienceLink.attr('data-file');
                        if (fileName) {
                            const fileUrl = getFileUrl('job_experience_letter_file', fileName);
                            if (fileUrl) {
                                $experienceLink.attr('href', fileUrl).attr('target', '_blank');
                            }
                        }
                    }
                });
            }

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
            
            // Handle dynamic child passport fields
            $(document).on('changed.bs.select', '[id^="child_have_passport_"]', function() {
                const havePassport = $(this).val();
                const childNum = $(this).attr('id').replace('child_have_passport_', '');
                const containerId = `#child_passport_file_container_${childNum}`;
                const fileId = `#child_passport_file_${childNum}`;
                
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
                
                // Check dynamic child passport fields
                $('[id^="child_have_passport_"]').each(function() {
                    const childHavePassportVal = getSelectValue('#' + $(this).attr('id'));
                    const childNum = $(this).attr('id').replace('child_have_passport_', '');
                    const containerId = `#child_passport_file_container_${childNum}`;
                    
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
                
                if ($(e.target).attr('id') === 'nav-relative-tab') {
                    setTimeout(function() {
                        // Initialize one blank relative contact if no contacts exist
                        if ($('#relative-contact-rows-container .relative-contact-row').length === 0) {
                            addRelativeContactRow();
                        }
                        
                        // Update remove buttons visibility and contact row numbers
                        updateRelativeContactRemoveButtons();
                        updateRelativeContactRowNumbers();
                    }, 300);
                }
                
                if ($(e.target).attr('id') === 'nav-family-tab') {
                    setTimeout(function() {
                        checkAndShowPassportFields();
                        
                        // Initialize one blank child if no children exist
                        if ($('#child-rows-container .child-row').length === 0) {
                            addChildRow();
                        }
                        
                        // Update remove buttons visibility and child row numbers
                        updateRemoveButtons();
                        updateChildRowNumbers();
                    }, 300);
                }
                
                if ($(e.target).attr('id') === 'nav-education-tab') {
                    setTimeout(function() {
                        // Initialize one blank other degree if no other degrees exist
                        if ($('#other-degree-rows-container .other-degree-row').length === 0) {
                            addOtherDegreeRow();
                        }
                        
                        // Update remove buttons visibility and other degree row numbers
                        updateOtherDegreeRemoveButtons();
                        updateOtherDegreeRowNumbers();
                    }, 300);
                }
                
                if ($(e.target).attr('id') === 'nav-experience-tab') {
                    setTimeout(function() {
                        // Initialize one blank job if no jobs exist
                        if ($('#job-rows-container .job-row').length === 0) {
                            addJobRow();
                        }
                        
                        // Update remove buttons visibility and job row numbers
                        updateJobRemoveButtons();
                        updateJobRowNumbers();
                    }, 300);
                }
            });
            
            // File path mapping for different file fields
            const filePathMap = {
                'upload_resume': 'lead-resume-files',
                'pr_assessment_letter_file': 'lead-assessment-letters',
                'passport_file_upload': 'lead-passport-files',
                'father_passport_file': 'lead-family-passports',
                'mother_passport_file': 'lead-family-passports',
                'spouse_passport_file': 'lead-family-passports',
                'spouse_document_file': 'lead-family-documents',
                'child_passport_file': 'lead-family-passports',
                'child_document_file': 'lead-family-documents',
                'ielts_result_file': 'lead-education-files',
                'tenth_result_file': 'lead-education-files',
                'twelfth_result_file': 'lead-education-files',
                'graduation_result_file': 'lead-education-files',
                'post_graduation_result_file': 'lead-education-files',
                'other_degree_result_file': 'lead-education-files',
                'job_offer_letter_file': 'lead-job-files',
                'job_experience_letter_file': 'lead-job-files',
                'valuation_report_file': 'lead-property-files',
                'father_income_document_file': 'lead-income-documents',
                'mother_income_document_file': 'lead-income-documents',
                'candidate_income_document_file': 'lead-income-documents',
                'spouse_income_document_file': 'lead-income-documents'
            };
            
            // Function to show file name below file input
            function showFileName(fileInputId, fileName, fileUrl = null) {
                const $fileInput = $('#' + fileInputId);
                const $existingDisplay = $fileInput.next('.file-name-display');
                
                if ($existingDisplay.length > 0) {
                    $existingDisplay.remove();
                }
                
                if (fileName) {
                    let displayHtml = '';
                    if (fileUrl) {
                        // Existing file - show as clickable link
                        displayHtml = '<div class="file-name-display mt-1"><small><a href="' + fileUrl + '" target="_blank" class="text-primary">' + fileName + '</a></small></div>';
                    } else {
                        // Newly selected file - show name only
                        displayHtml = '<div class="file-name-display mt-1"><small class="text-muted">' + fileName + '</small></div>';
                    }
                    $fileInput.after(displayHtml);
                }
            }
            
            // Function to get file URL for existing files
            function getFileUrl(fieldId, fileName) {
                if (!fileName) return null;
                
                const basePath = filePathMap[fieldId] || 'lead-files';
                const leadId = $('#lead_id').val() || '';
                let filePath = '';
                
                // upload_resume is stored directly in folder without lead_id subfolder
                if (fieldId === 'upload_resume') {
                    filePath = basePath + '/' + fileName;
                } else if (leadId) {
                    // All other files are stored in subfolder with lead_id
                    filePath = basePath + '/' + leadId + '/' + fileName;
                } else {
                    // Fallback if no lead_id
                    filePath = basePath + '/' + fileName;
                }
                
                return '{{ url("user-uploads") }}/' + filePath;
            }
            
            // Zip Code / Pin Code validation - only allow 6 digits
            $(document).on('input', 'input[name="home_pin_code"], input[name="mailing_pin_code"], input[name="relative_zip_code"], input[name="relative_zip_code[]"], input[name="spouse_postal_code"]', function() {
                let value = $(this).val();
                // Remove any non-digit characters
                value = value.replace(/\D/g, '');
                // Limit to 6 digits
                if (value.length > 6) {
                    value = value.substring(0, 6);
                }
                $(this).val(value);
            });
            
            // Phone Number validation - only allow 10 digits
            $(document).on('input', 'input[name="spouse_phone_number"]', function() {
                let value = $(this).val();
                // Remove any non-digit characters
                value = value.replace(/\D/g, '');
                // Limit to 10 digits
                if (value.length > 10) {
                    value = value.substring(0, 10);
                }
                $(this).val(value);
            });
            
            // Child Age validation - only allow numbers
            $(document).on('input', 'input[name="child_age"], input[name="child_age[]"]', function() {
                let value = $(this).val();
                // Remove any non-digit characters
                value = value.replace(/\D/g, '');
                $(this).val(value);
            });
            
            // File size validation and file name display for all file inputs
            $(document).on('change', 'input[type="file"][data-max-size]', function() {
                const file = this.files[0];
                const maxSize = $(this).data('max-size'); // 5242880 = 5MB
                const fieldId = $(this).attr('id');
                
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
                    // Remove file display if exists
                    $(this).next('.file-name-display').remove();
                } else if (file) {
                    // Show file name for newly selected file
                    showFileName(fieldId, file.name);
                    // Remove hidden field if exists (for existing files)
                    $('#' + fieldId + '_hidden').remove();
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
            
            // Show/hide Move to Lead button based on lead_id in URL
            const urlLeadId = getLeadIdFromUrl();
            if (urlLeadId) {
                // Update lead number display
                const leadNumber = 'LEAD-' + String(urlLeadId).padStart(4, '0');
                $('#leadNumberDisplay').text(leadNumber);
                $('#moveToLeadButtonContainer').addClass('d-flex').css('display', 'flex');
            } else {
                $('#leadNumberDisplay').text('--');
                $('#moveToLeadButtonContainer').removeClass('d-flex').css('display', 'none');
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
                        // Silently fail - data might already be loaded
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
                    
                    // Handle upload_resume file - create hidden input if file exists
                    if (data.upload_resume) {
                        const fileName = data.upload_resume;
                        // Store the file name in a hidden input for reference
                        if ($('#upload_resume_hidden').length === 0) {
                            $('<input>').attr({
                                type: 'hidden',
                                id: 'upload_resume_hidden',
                                name: 'upload_resume_existing',
                                value: fileName
                            }).insertAfter('#upload_resume');
                        } else {
                            $('#upload_resume_hidden').val(fileName);
                        }
                        // Show file name with link
                        const fileUrl = getFileUrl('upload_resume', fileName);
                        showFileName('upload_resume', fileName, fileUrl);
                    } else {
                        // Remove hidden field if no file exists
                        $('#upload_resume_hidden').remove();
                        $('#upload_resume').next('.file-name-display').remove();
                    }
                    
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
                                visaRefusals = [];
                            }
                        }
                        
                        // Clear any existing visa refusals
                        $('#visa-refusal-rows-container').empty();
                        visaRefusalCounter = 0;
                        
                        // Ensure it's an array
                        if (Array.isArray(visaRefusals) && visaRefusals.length > 0) {
                            // Populate all visa refusals
                            visaRefusals.forEach(function(refusal) {
                                addVisaRefusalRow(refusal);
                            });
                        } else {
                            // If no visa refusals data and status is refusal, add one blank refusal
                            if (data.visa_status === 'refusal') {
                                addVisaRefusalRow();
                            }
                        }
                    } else if (data.visa_status === 'refusal') {
                        // No visa refusals data but status is refusal - add one blank refusal
                        $('#visa-refusal-rows-container').empty();
                        visaRefusalCounter = 0;
                        addVisaRefusalRow();
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
                    // Handle both new format (ID) and old format (string like 'pr', 'visit', etc.)
                    if (data.visa_type) {
                        // Set flag to prevent change handler from loading subclasses
                        isLoadingFormData = true;
                        
                        const visaTypeValue = data.visa_type;
                        const isNumericId = !isNaN(visaTypeValue) && !isNaN(parseFloat(visaTypeValue));
                        
                        // Try to find by value (works for both ID and old string format)
                        const visaTypeInput = $('input[name="visa_type"][value="' + visaTypeValue + '"]');
                        let sectionId = null;
                        
                        if (visaTypeInput.length > 0) {
                            sectionId = visaTypeInput.data('section');
                            visaTypeInput.prop('checked', true);
                            
                            // If it's a numeric ID, load subclasses first, then trigger change
                            if (isNumericId) {
                                loadSubclassesForVisaType(visaTypeValue, sectionId, function() {
                                    // After subclasses are loaded, set the subclass value if exists
                                    setSubclassValue(sectionId, data);
                                    // Trigger change to show the section (but don't load subclasses again)
                                    visaTypeInput.trigger('change');
                                    // Reset flag after a delay to allow change handler to complete
                                    setTimeout(function() {
                                        isLoadingFormData = false;
                                    }, 500);
                                });
                            } else {
                                // Old format - just trigger change
                                visaTypeInput.trigger('change');
                                // Reset flag after a delay
                                setTimeout(function() {
                                    isLoadingFormData = false;
                                }, 500);
                            }
                        } else {
                            // If not found, might be old format - try to find by data-section attribute
                            // Map old string values to section IDs
                            const oldToSectionMap = {
                                'pr': 'pr',
                                'visit': 'visit',
                                'work': 'work',
                                'student': 'student'
                            };
                            sectionId = oldToSectionMap[visaTypeValue.toLowerCase()];
                            if (sectionId) {
                                const sectionInput = $('input[name="visa_type"][data-section="' + sectionId + '"]').first();
                                sectionInput.prop('checked', true).trigger('change');
                                // For old format, set subclass value directly (no need to load from API)
                                setTimeout(function() {
                                    setSubclassValue(sectionId, data);
                                    isLoadingFormData = false;
                                }, 500);
                            } else {
                                isLoadingFormData = false;
                            }
                        }
                        
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
                                // Show file name with link - wait a bit more to ensure PR section is visible
                                setTimeout(function() {
                                    const fileUrl = getFileUrl('pr_assessment_letter_file', fileName);
                                    showFileName('pr_assessment_letter_file', fileName, fileUrl);
                                }, 100);
                            } else {
                                $('#pr_assessment_letter_file').next('.file-name-display').remove();
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
                            // pr_subclass will be set by setSubclassValue function after subclasses are loaded
                            
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
                            // visit_subclass will be set by setSubclassValue function after subclasses are loaded
                            
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
                            // work_subclass will be set by setSubclassValue function after subclasses are loaded
                            
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
                            // student_subclass will be set by setSubclassValue function after subclasses are loaded
                        }, 300);
                    }
                }
                
                // Populate Steps 3-9 data
                for (let stepNum = 3; stepNum <= 9; stepNum++) {
                    const stepKey = 'step_' + stepNum + '_data';
                    if (stepData[stepKey] && typeof stepData[stepKey] === 'object') {
                        const stepDataObj = stepData[stepKey];
                        
                        // Special handling for Step 4 - relative contacts data
                        if (stepNum === 4 && stepDataObj.relative_contacts && Array.isArray(stepDataObj.relative_contacts)) {
                            const relativeContacts = stepDataObj.relative_contacts;
                            // Clear any existing relative contacts
                            $('#relative-contact-rows-container').empty();
                            relativeContactCounter = 0;
                            
                            if (relativeContacts.length > 0) {
                                // Populate all relative contacts
                                relativeContacts.forEach(function(contact) {
                                    addRelativeContactRow(contact);
                                });
                            } else {
                                // If no relative contacts data, add one blank contact
                                addRelativeContactRow();
                            }
                            
                            // Update remove buttons and contact row numbers after a delay to ensure DOM is ready
                            setTimeout(function() {
                                updateRelativeContactRemoveButtons();
                                updateRelativeContactRowNumbers();
                            }, 500);
                        } else if (stepNum === 4) {
                            // Step 4 but no relative contacts data - add one blank contact
                            $('#relative-contact-rows-container').empty();
                            relativeContactCounter = 0;
                            addRelativeContactRow();
                            // Update remove buttons and contact row numbers after a delay
                            setTimeout(function() {
                                updateRelativeContactRemoveButtons();
                                updateRelativeContactRowNumbers();
                            }, 300);
                        }
                        
                        // Special handling for Step 5 - child data
                        if (stepNum === 5 && stepDataObj.children && Array.isArray(stepDataObj.children)) {
                            const children = stepDataObj.children;
                            // Clear any existing children
                            $('#child-rows-container').empty();
                            childCounter = 0;
                            
                            if (children.length > 0) {
                                // Populate all children
                                children.forEach(function(child) {
                                    addChildRow(child);
                                });
                            } else {
                                // If no children data, add one blank child
                                addChildRow();
                            }
                            
                            // Update file links, remove buttons, and child row numbers after a delay to ensure DOM is ready
                            setTimeout(function() {
                                updateChildFileLinks();
                                updateRemoveButtons();
                                updateChildRowNumbers();
                            }, 500);
                        } else if (stepNum === 5) {
                            // Step 5 but no children data - add one blank child
                            $('#child-rows-container').empty();
                            childCounter = 0;
                            addChildRow();
                            // Update remove buttons and child row numbers after a delay
                            setTimeout(function() {
                                updateRemoveButtons();
                                updateChildRowNumbers();
                            }, 300);
                        }
                        
                        // Special handling for Step 6 - other degrees data
                        if (stepNum === 6 && stepDataObj.other_degrees && Array.isArray(stepDataObj.other_degrees)) {
                            const otherDegrees = stepDataObj.other_degrees;
                            // Clear any existing other degrees
                            $('#other-degree-rows-container').empty();
                            otherDegreeCounter = 0;
                            
                            if (otherDegrees.length > 0) {
                                // Populate all other degrees
                                otherDegrees.forEach(function(degree) {
                                    addOtherDegreeRow(degree);
                                });
                            } else {
                                // If no other degrees data, add one blank other degree
                                addOtherDegreeRow();
                            }
                            
                            // Update file links, remove buttons, and other degree row numbers after a delay to ensure DOM is ready
                            setTimeout(function() {
                                updateOtherDegreeFileLinks();
                                updateOtherDegreeRemoveButtons();
                                updateOtherDegreeRowNumbers();
                            }, 500);
                        } else if (stepNum === 6) {
                            // Step 6 but no other degrees data - add one blank other degree
                            $('#other-degree-rows-container').empty();
                            otherDegreeCounter = 0;
                            addOtherDegreeRow();
                            // Update remove buttons and other degree row numbers after a delay
                            setTimeout(function() {
                                updateOtherDegreeRemoveButtons();
                                updateOtherDegreeRowNumbers();
                            }, 300);
                        }
                        
                        // Special handling for Step 7 - jobs data
                        if (stepNum === 7 && stepDataObj.jobs && Array.isArray(stepDataObj.jobs)) {
                            const jobs = stepDataObj.jobs;
                            // Clear any existing jobs
                            $('#job-rows-container').empty();
                            jobCounter = 0;
                            
                            if (jobs.length > 0) {
                                // Populate all jobs
                                jobs.forEach(function(job) {
                                    addJobRow(job);
                                });
                            } else {
                                // If no jobs data, add one blank job
                                addJobRow();
                            }
                            
                            // Update file links, remove buttons, and job row numbers after a delay to ensure DOM is ready
                            setTimeout(function() {
                                updateJobFileLinks();
                                updateJobRemoveButtons();
                                updateJobRowNumbers();
                            }, 500);
                        } else if (stepNum === 7) {
                            // Step 7 but no jobs data - add one blank job
                            $('#job-rows-container').empty();
                            jobCounter = 0;
                            addJobRow();
                            // Update remove buttons and job row numbers after a delay
                            setTimeout(function() {
                                updateJobRemoveButtons();
                                updateJobRowNumbers();
                            }, 300);
                        }
                        
                        // Populate all fields for this step
                        Object.keys(stepDataObj).forEach(function(fieldName) {
                            // Skip children field - it's handled above
                            if (fieldName === 'children') {
                                return;
                            }
                            // Skip relative_contacts field - it's handled above
                            if (fieldName === 'relative_contacts') {
                                return;
                            }
                            // Skip other_degrees field - it's handled above
                            if (fieldName === 'other_degrees') {
                                return;
                            }
                            // Skip jobs field - it's handled above
                            if (fieldName === 'jobs') {
                                return;
                            }
                            
                            const $field = $('#' + fieldName + ', [name="' + fieldName + '"]').first();
                            if ($field.length) {
                                const value = stepDataObj[fieldName];
                                
                                // Check if this is a file field
                                if ($field.is('input[type="file"]') && value) {
                                    // Handle file field - create hidden input and show file name
                                    const hiddenId = fieldName + '_hidden';
                                    if ($('#' + hiddenId).length === 0) {
                                        $('<input>').attr({
                                            type: 'hidden',
                                            id: hiddenId,
                                            name: fieldName + '_existing',
                                            value: value
                                        }).insertAfter($field);
                                    } else {
                                        $('#' + hiddenId).val(value);
                                    }
                                    // Show file name with link - wait a bit for step 5 files to ensure containers are visible
                                    setTimeout(function() {
                                        const fileUrl = getFileUrl(fieldName, value);
                                        showFileName(fieldName, value, fileUrl);
                                    }, stepNum === 5 ? 300 : 0);
                                } else if ($field.is('select')) {
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

            // Map tab IDs to step numbers (mapped to original database step numbers)
            // This allows UI tab reordering without changing database structure
            const tabStepMap = {
                'nav-personal-tab': 1,      // Personal Details -> step_1_data (old step 1)
                'nav-education-tab': 6,      // Education -> step_6_data (old step 6)
                'nav-experience-tab': 7,     // Professional Experience -> step_7_data (old step 7)
                'nav-preference-tab': 2,     // Client Preference -> step_2_data (old step 2)
                'nav-passport-tab': 3,       // Passport Details -> step_3_data (old step 3)
                'nav-relative-tab': 4,       // Relative Contact Information -> step_4_data (old step 4)
                'nav-family-tab': 5,         // Family Information -> step_5_data (old step 5)
                'nav-property-tab': 8,       // Property Details -> step_8_data (old step 8)
                'nav-financial-tab': 9       // Financial Status -> step_9_data (old step 9)
            };

            // UI tab order sequence (as displayed in the interface)
            const tabOrder = [
                'nav-personal-tab',      // 1
                'nav-education-tab',     // 2
                'nav-experience-tab',    // 3
                'nav-preference-tab',    // 4
                'nav-passport-tab',      // 5
                'nav-relative-tab',      // 6
                'nav-family-tab',        // 7
                'nav-property-tab',      // 8
                'nav-financial-tab'      // 9
            ];

            // Get current step from active tab
            function getCurrentStep() {
                const activeTab = $('.nav-link-lead.active');
                const tabId = activeTab.attr('id');
                return tabStepMap[tabId] || 1;
            }

            // Get next tab in UI sequence order
            function getNextTabInSequence(currentTabId) {
                const currentIndex = tabOrder.indexOf(currentTabId);
                if (currentIndex === -1 || currentIndex === tabOrder.length - 1) {
                    return null; // Already at last tab
                }
                return tabOrder[currentIndex + 1];
            }

            // Get previous tab in UI sequence order
            function getPreviousTabInSequence(currentTabId) {
                const currentIndex = tabOrder.indexOf(currentTabId);
                if (currentIndex === -1 || currentIndex === 0) {
                    return null; // Already at first tab
                }
                return tabOrder[currentIndex - 1];
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

            // Update tab navigation - all tabs are always enabled
            function updateTabNavigation() {
                $('.nav-link-lead').each(function() {
                    // Remove disabled class and enable all tabs
                    $(this).removeClass('disabled').css('pointer-events', 'auto').css('opacity', '1');
                });
            }

            // Handle tab navigation - all tabs are accessible
            $('.nav-link-lead').on('click', function(e) {
                // All tabs are now accessible, no need to check for disabled state
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
                    $saveBtn.html('@lang('app.saveAndNext')');
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
                        // Only validate mandatory fields: Surname, Given Name, Primary Phone No, and Email
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
                        // Optional phone fields validation (format only, not required)
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
                        // Optional email validation (format only, not required)
                        const otherEmail = ($('#other_email').val() || '').trim();
                        if (otherEmail && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(otherEmail)) {
                            isValid = false;
                            showFieldError('#other_email', '@lang('app.otherEmail') must be a valid email address');
                        }
                        break;
                        
                    case 2:
                        // Step 2 - Client Preference
                        // All fields are optional - no validation required
                        break;
                        
                    case 3:
                        // Step 3 - Passport Details
                        // All passport fields are optional - no validation required
                        // Only validate date logic if both dates are provided
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
                        // All fields are optional - no validation required
                        break;
                        
                    case 5:
                        // Step 5 - Family Information
                        // All fields are optional - no validation required
                        break;
                        
                    case 8:
                        // Step 8 - Property Details
                        // All fields are optional - no validation required
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
                
                // Ensure file is included if selected (for step 1)
                if (currentStep === 1) {
                    const uploadResumeInput = document.getElementById('upload_resume');
                    if (uploadResumeInput && uploadResumeInput.files && uploadResumeInput.files.length > 0) {
                        const file = uploadResumeInput.files[0];
                        // Explicitly ensure file is in FormData
                        formData.delete('upload_resume');
                        formData.append('upload_resume', file);
                        // Remove hidden field since new file is being uploaded
                        $('#upload_resume_hidden').remove();
                    } else {
                        // If no new file but existing file exists, ensure hidden field is in FormData
                        if ($('#upload_resume_hidden').length > 0) {
                            const existingFileName = $('#upload_resume_hidden').val();
                            if (existingFileName) {
                                formData.append('upload_resume_existing', existingFileName);
                            }
                        }
                    }
                }
                
                // Ensure pr_assessment_letter_file is included if selected (for step 2)
                if (currentStep === 2) {
                    const prAssessmentLetterInput = document.getElementById('pr_assessment_letter_file');
                    if (prAssessmentLetterInput && prAssessmentLetterInput.files && prAssessmentLetterInput.files.length > 0) {
                        const file = prAssessmentLetterInput.files[0];
                        // Explicitly ensure file is in FormData
                        formData.delete('pr_assessment_letter_file');
                        formData.append('pr_assessment_letter_file', file);
                        // Remove hidden field since new file is being uploaded
                        $('#pr_assessment_letter_file_hidden').remove();
                    } else {
                        // If no new file but existing file exists, ensure hidden field is in FormData
                        if ($('#pr_assessment_letter_file_hidden').length > 0) {
                            const existingFileName = $('#pr_assessment_letter_file_hidden').val();
                            if (existingFileName) {
                                formData.append('pr_assessment_letter_file_existing', existingFileName);
                            }
                        }
                    }
                }
                
                // Ensure passport_file_upload is included if selected (for step 3)
                if (currentStep === 3) {
                    const passportFileInput = document.getElementById('passport_file_upload');
                    if (passportFileInput && passportFileInput.files && passportFileInput.files.length > 0) {
                        const file = passportFileInput.files[0];
                        // Explicitly ensure file is in FormData
                        formData.delete('passport_file_upload');
                        formData.append('passport_file_upload', file);
                        // Remove hidden field since new file is being uploaded
                        $('#passport_file_upload_hidden').remove();
                    } else {
                        // If no new file but existing file exists, ensure hidden field is in FormData
                        if ($('#passport_file_upload_hidden').length > 0) {
                            const existingFileName = $('#passport_file_upload_hidden').val();
                            if (existingFileName) {
                                formData.append('passport_file_upload_existing', existingFileName);
                            }
                        }
                    }
                }
                
                // Ensure step 4 relative contacts data is collected
                if (currentStep === 4) {
                    // Collect relative contacts data
                    const relativeContacts = [];
                    $('.relative-contact-row').each(function() {
                        const contactIndex = $(this).data('contact-index');
                        const surname = $('#relative_surname_' + contactIndex).val() || '';
                        const givenName = $('#relative_given_name_' + contactIndex).val() || '';
                        const orgName = $('#relative_organization_name_' + contactIndex).val() || '';
                        const relationship = $('#relative_relationship_' + contactIndex).val() || '';
                        const address = $('#relative_contact_address_' + contactIndex).val() || '';
                        const city = $('#relative_city_' + contactIndex).val() || '';
                        const state = $('#relative_state_' + contactIndex).val() || '';
                        const zipCode = $('#relative_zip_code_' + contactIndex).val() || '';
                        const email = $('#relative_email_address_' + contactIndex).val() || '';
                        const phone = $('#relative_phone_number_' + contactIndex).val() || '';
                        
                        // Only add contact if at least one field has a value
                        if (surname || givenName || orgName || relationship || address || city || state || zipCode || email || phone) {
                            const contactData = {
                                relative_surname: surname,
                                relative_given_name: givenName,
                                relative_organization_name: orgName,
                                relative_relationship: relationship,
                                relative_contact_address: address,
                                relative_city: city,
                                relative_state: state,
                                relative_zip_code: zipCode,
                                relative_email_address: email,
                                relative_phone_number: phone
                            };
                            relativeContacts.push(contactData);
                        }
                    });
                    
                    // Add relative contacts data as JSON
                    formData.append('relative_contacts', JSON.stringify(relativeContacts));
                }
                
                // Ensure step 5 file uploads are included if selected
                if (currentStep === 5) {
                    // Collect children data
                    const children = [];
                    $('.child-row').each(function() {
                        const childIndex = $(this).data('child-index');
                        const childName = $('#child_name_' + childIndex).val() || '';
                        const childAge = $('#child_age_' + childIndex).val() || '';
                        const childDob = $('#child_date_of_birth_' + childIndex).val() || '';
                        const childCity = $('#child_city_of_birth_' + childIndex).val() || '';
                        const childGender = getSelectValue('#child_gender_' + childIndex) || '';
                        const childHavePassport = getSelectValue('#child_have_passport_' + childIndex) || '';
                        
                        // Get child passport file
                        const childPassportFileInput = document.getElementById('child_passport_file_' + childIndex);
                        let childPassportFile = '';
                        if (childPassportFileInput && childPassportFileInput.files && childPassportFileInput.files.length > 0) {
                            // File will be handled separately in FormData
                            childPassportFile = 'NEW_FILE_' + childIndex;
                        } else {
                            // Check for existing file
                            const existingPassportLink = $(this).find('.existing-file-link[data-file]').filter(function() {
                                return $(this).closest('.col-md-3').find('#child_passport_file_' + childIndex).length > 0;
                            });
                            if (existingPassportLink.length > 0) {
                                childPassportFile = existingPassportLink.attr('data-file');
                            }
                        }
                        
                        // Get child document file
                        const childDocumentFileInput = document.getElementById('child_document_file_' + childIndex);
                        let childDocumentFile = '';
                        if (childDocumentFileInput && childDocumentFileInput.files && childDocumentFileInput.files.length > 0) {
                            // File will be handled separately in FormData
                            childDocumentFile = 'NEW_FILE_' + childIndex;
                        } else {
                            // Check for existing file
                            const existingDocumentLink = $(this).find('.existing-file-link[data-file]').filter(function() {
                                return $(this).closest('.col-md-3').find('#child_document_file_' + childIndex).length > 0;
                            });
                            if (existingDocumentLink.length > 0) {
                                childDocumentFile = existingDocumentLink.attr('data-file');
                            }
                        }
                        
                        // Only add child if at least one field has a value
                        if (childName || childAge || childDob || childCity || childGender || childHavePassport) {
                            const childData = {
                                child_name: childName,
                                child_age: childAge,
                                child_date_of_birth: childDob,
                                child_city_of_birth: childCity,
                                child_gender: childGender,
                                child_have_passport: childHavePassport,
                                child_passport_file: childPassportFile,
                                child_document_file: childDocumentFile
                            };
                            children.push(childData);
                            
                            // Handle file uploads for this child
                            if (childPassportFileInput && childPassportFileInput.files && childPassportFileInput.files.length > 0) {
                                formData.append('child_passport_file_' + childIndex, childPassportFileInput.files[0]);
                            } else if (childPassportFile && childPassportFile !== 'NEW_FILE_' + childIndex) {
                                formData.append('child_passport_file_' + childIndex + '_existing', childPassportFile);
                            }
                            
                            if (childDocumentFileInput && childDocumentFileInput.files && childDocumentFileInput.files.length > 0) {
                                formData.append('child_document_file_' + childIndex, childDocumentFileInput.files[0]);
                            } else if (childDocumentFile && childDocumentFile !== 'NEW_FILE_' + childIndex) {
                                formData.append('child_document_file_' + childIndex + '_existing', childDocumentFile);
                            }
                        }
                    });
                    
                    // Add children data as JSON
                    formData.append('children', JSON.stringify(children));
                    
                    // Handle other step 5 file fields (father, mother, spouse)
                    const step5FileFields = [
                        'father_passport_file',
                        'mother_passport_file',
                        'spouse_passport_file',
                        'spouse_document_file'
                    ];
                    
                    step5FileFields.forEach(function(fileField) {
                        const fileInput = document.getElementById(fileField);
                        if (fileInput && fileInput.files && fileInput.files.length > 0) {
                            const file = fileInput.files[0];
                            // Explicitly ensure file is in FormData
                            formData.delete(fileField);
                            formData.append(fileField, file);
                            // Remove hidden field since new file is being uploaded
                            $('#' + fileField + '_hidden').remove();
                        } else {
                            // If no new file but existing file exists, ensure hidden field is in FormData
                            if ($('#' + fileField + '_hidden').length > 0) {
                                const existingFileName = $('#' + fileField + '_hidden').val();
                                if (existingFileName) {
                                    formData.append(fileField + '_existing', existingFileName);
                                }
                            }
                        }
                    });
                }
                
                // Ensure step 6 file uploads are included if selected
                if (currentStep === 6) {
                    // Collect other degrees data
                    const otherDegrees = [];
                    $('.other-degree-row').each(function() {
                        const degreeIndex = $(this).data('degree-index');
                        const otherDegree = $('#other_degree_' + degreeIndex).val() || '';
                        const universityName = $('#other_degree_university_name_' + degreeIndex).val() || '';
                        const percentage = $('#other_degree_percentage_' + degreeIndex).val() || '';
                        const passingYear = getSelectValue('#other_degree_passing_year_' + degreeIndex) || '';
                        const trial = $('#other_degree_trial_' + degreeIndex).val() || '';
                        
                        // Get other degree result file
                        const otherDegreeResultFileInput = document.getElementById('other_degree_result_file_' + degreeIndex);
                        let otherDegreeResultFile = '';
                        if (otherDegreeResultFileInput && otherDegreeResultFileInput.files && otherDegreeResultFileInput.files.length > 0) {
                            // File will be handled separately in FormData
                            otherDegreeResultFile = 'NEW_FILE_' + degreeIndex;
                        } else {
                            // Check for existing file
                            const existingResultLink = $(this).find('.existing-file-link[data-file]').filter(function() {
                                return $(this).closest('.col-md-3').find('#other_degree_result_file_' + degreeIndex).length > 0;
                            });
                            if (existingResultLink.length > 0) {
                                otherDegreeResultFile = existingResultLink.attr('data-file');
                            }
                        }
                        
                        // Only add other degree if at least one field has a value
                        if (otherDegree || universityName || percentage || passingYear || trial) {
                            const degreeData = {
                                other_degree: otherDegree,
                                other_degree_university_name: universityName,
                                other_degree_percentage: percentage,
                                other_degree_passing_year: passingYear,
                                other_degree_trial: trial,
                                other_degree_result_file: otherDegreeResultFile
                            };
                            otherDegrees.push(degreeData);
                            
                            // Handle file uploads for this other degree
                            if (otherDegreeResultFileInput && otherDegreeResultFileInput.files && otherDegreeResultFileInput.files.length > 0) {
                                formData.append('other_degree_result_file_' + degreeIndex, otherDegreeResultFileInput.files[0]);
                            } else if (otherDegreeResultFile && otherDegreeResultFile !== 'NEW_FILE_' + degreeIndex) {
                                formData.append('other_degree_result_file_' + degreeIndex + '_existing', otherDegreeResultFile);
                            }
                        }
                    });
                    
                    // Add other degrees data as JSON
                    formData.append('other_degrees', JSON.stringify(otherDegrees));
                    
                    // Handle other step 6 file fields
                    const step6FileFields = [
                        'ielts_result_file',
                        'tenth_result_file',
                        'twelfth_result_file',
                        'graduation_result_file',
                        'post_graduation_result_file'
                    ];
                    
                    step6FileFields.forEach(function(fileField) {
                        const fileInput = document.getElementById(fileField);
                        if (fileInput && fileInput.files && fileInput.files.length > 0) {
                            const file = fileInput.files[0];
                            // Explicitly ensure file is in FormData
                            formData.delete(fileField);
                            formData.append(fileField, file);
                            // Remove hidden field since new file is being uploaded
                            $('#' + fileField + '_hidden').remove();
                        } else {
                            // If no new file but existing file exists, ensure hidden field is in FormData
                            if ($('#' + fileField + '_hidden').length > 0) {
                                const existingFileName = $('#' + fileField + '_hidden').val();
                                if (existingFileName) {
                                    formData.append(fileField + '_existing', existingFileName);
                                }
                            }
                        }
                    });
                }
                
                // Ensure step 7 file uploads are included if selected
                if (currentStep === 7) {
                    // Collect jobs data
                    const jobs = [];
                    $('.job-row').each(function() {
                        const jobIndex = $(this).data('job-index');
                        const durationFrom = $('#job_duration_from_' + jobIndex).val() || '';
                        const durationTo = $('#job_duration_to_' + jobIndex).val() || '';
                        const country = $('#job_country_' + jobIndex).val() || '';
                        const designation = $('#job_designation_' + jobIndex).val() || '';
                        const companyName = $('#job_company_name_' + jobIndex).val() || '';
                        const salary = $('#job_salary_' + jobIndex).val() || '';
                        
                        // Get job offer letter file
                        const jobOfferLetterFileInput = document.getElementById('job_offer_letter_file_' + jobIndex);
                        let jobOfferLetterFile = '';
                        if (jobOfferLetterFileInput && jobOfferLetterFileInput.files && jobOfferLetterFileInput.files.length > 0) {
                            // File will be handled separately in FormData
                            jobOfferLetterFile = 'NEW_FILE_' + jobIndex;
                        } else {
                            // Check for existing file
                            const existingOfferLink = $(this).find('.existing-file-link[data-file]').filter(function() {
                                return $(this).closest('.col-md-3').find('#job_offer_letter_file_' + jobIndex).length > 0;
                            });
                            if (existingOfferLink.length > 0) {
                                jobOfferLetterFile = existingOfferLink.attr('data-file');
                            }
                        }
                        
                        // Get job experience letter file
                        const jobExperienceLetterFileInput = document.getElementById('job_experience_letter_file_' + jobIndex);
                        let jobExperienceLetterFile = '';
                        if (jobExperienceLetterFileInput && jobExperienceLetterFileInput.files && jobExperienceLetterFileInput.files.length > 0) {
                            // File will be handled separately in FormData
                            jobExperienceLetterFile = 'NEW_FILE_' + jobIndex;
                        } else {
                            // Check for existing file
                            const existingExperienceLink = $(this).find('.existing-file-link[data-file]').filter(function() {
                                return $(this).closest('.col-md-3').find('#job_experience_letter_file_' + jobIndex).length > 0;
                            });
                            if (existingExperienceLink.length > 0) {
                                jobExperienceLetterFile = existingExperienceLink.attr('data-file');
                            }
                        }
                        
                        // Only add job if at least one field has a value
                        if (durationFrom || durationTo || country || designation || companyName || salary) {
                            const jobData = {
                                job_duration_from: durationFrom,
                                job_duration_to: durationTo,
                                job_country: country,
                                job_designation: designation,
                                job_company_name: companyName,
                                job_salary: salary,
                                job_offer_letter_file: jobOfferLetterFile,
                                job_experience_letter_file: jobExperienceLetterFile
                            };
                            jobs.push(jobData);
                            
                            // Handle file uploads for this job
                            if (jobOfferLetterFileInput && jobOfferLetterFileInput.files && jobOfferLetterFileInput.files.length > 0) {
                                formData.append('job_offer_letter_file_' + jobIndex, jobOfferLetterFileInput.files[0]);
                            } else if (jobOfferLetterFile && jobOfferLetterFile !== 'NEW_FILE_' + jobIndex) {
                                formData.append('job_offer_letter_file_' + jobIndex + '_existing', jobOfferLetterFile);
                            }
                            
                            if (jobExperienceLetterFileInput && jobExperienceLetterFileInput.files && jobExperienceLetterFileInput.files.length > 0) {
                                formData.append('job_experience_letter_file_' + jobIndex, jobExperienceLetterFileInput.files[0]);
                            } else if (jobExperienceLetterFile && jobExperienceLetterFile !== 'NEW_FILE_' + jobIndex) {
                                formData.append('job_experience_letter_file_' + jobIndex + '_existing', jobExperienceLetterFile);
                            }
                        }
                    });
                    
                    // Add jobs data as JSON
                    formData.append('jobs', JSON.stringify(jobs));
                }
                
                // Ensure valuation_report_file is included if selected (for step 8)
                if (currentStep === 8) {
                    const valuationReportInput = document.getElementById('valuation_report_file');
                    if (valuationReportInput && valuationReportInput.files && valuationReportInput.files.length > 0) {
                        const file = valuationReportInput.files[0];
                        // Explicitly ensure file is in FormData
                        formData.delete('valuation_report_file');
                        formData.append('valuation_report_file', file);
                        // Remove hidden field since new file is being uploaded
                        $('#valuation_report_file_hidden').remove();
                    } else {
                        // If no new file but existing file exists, ensure hidden field is in FormData
                        if ($('#valuation_report_file_hidden').length > 0) {
                            const existingFileName = $('#valuation_report_file_hidden').val();
                            if (existingFileName) {
                                formData.append('valuation_report_file_existing', existingFileName);
                            }
                        }
                    }
                }
                
                // Ensure step 9 file uploads are included if selected
                if (currentStep === 9) {
                    const step9FileFields = [
                        'father_income_document_file',
                        'mother_income_document_file',
                        'candidate_income_document_file',
                        'spouse_income_document_file'
                    ];
                    
                    step9FileFields.forEach(function(fileField) {
                        const fileInput = document.getElementById(fileField);
                        if (fileInput && fileInput.files && fileInput.files.length > 0) {
                            const file = fileInput.files[0];
                            // Explicitly ensure file is in FormData
                            formData.delete(fileField);
                            formData.append(fileField, file);
                            // Remove hidden field since new file is being uploaded
                            $('#' + fileField + '_hidden').remove();
                        } else {
                            // If no new file but existing file exists, ensure hidden field is in FormData
                            if ($('#' + fileField + '_hidden').length > 0) {
                                const existingFileName = $('#' + fileField + '_hidden').val();
                                if (existingFileName) {
                                    formData.append(fileField + '_existing', existingFileName);
                                }
                            }
                        }
                    });
                }
                
                // Add lead_id if exists
                if (currentLeadId) {
                    formData.append('lead_id', currentLeadId);
                }
                
                // Collect visa refusals if step 1
                if (currentStep === 1) {
                    const visaRefusals = [];
                    
                    // Collect all visa refusal rows (all are dynamic now)
                    $('.visa-refusal-row').each(function() {
                        const refusalIndex = $(this).data('refusal-index');
                        const date = $('#visa_rejection_date_' + refusalIndex).val() || '';
                        const category = $('#visa_refusal_category_' + refusalIndex).val() || '';
                        const reason = $('#visa_refusal_reason_' + refusalIndex).val() || '';
                        
                        // Only add if at least one field has a value
                        if (date || category || reason) {
                            visaRefusals.push({
                                date: date,
                                category: category,
                                reason: reason
                            });
                        }
                    });
                    
                    // Add visa refusals data as JSON
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
                                // Update lead number display
                                const leadNumber = 'LEAD-' + String(currentLeadId).padStart(4, '0');
                                $('#leadNumberDisplay').text(leadNumber);
                                // Show Move to Lead button when lead_id is added to URL
                                $('#moveToLeadButtonContainer').addClass('d-flex').css('display', 'flex');
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
                            
                            // If not last step, move to next step in UI sequence
                            const currentTab = $('.nav-link-lead.active');
                            const currentTabId = currentTab.attr('id');
                            const nextTabId = getNextTabInSequence(currentTabId);
                            
                            if (nextTabId) {
                                setTimeout(function() {
                                    $('#' + nextTabId).tab('show');
                                    currentStep = tabStepMap[nextTabId];
                                    updateFooterButtons();
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
                                        // Redirect to lead list page
                                        window.location.href = '{{ route("lead-list.index") }}';
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
                const currentTab = $('.nav-link-lead.active');
                const currentTabId = currentTab.attr('id');
                const prevTabId = getPreviousTabInSequence(currentTabId);
                if (prevTabId) {
                    // Use Bootstrap tab API to switch tabs properly
                    $('#' + prevTabId).tab('show');
                    // Update current step will be handled by the shown.bs.tab event
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
            
            // Handle tab click - all tabs are accessible
            $('.nav-link-lead').on('click', function(e) {
                // All tabs are now accessible, no restrictions
            });

            // Initialize on page load
            currentStep = getCurrentStep();
            
            // Load existing lead data if lead_id exists (with a small delay to ensure DOM is ready)
            if (currentLeadId) {
                setTimeout(function() {
                    loadExistingLeadData(currentLeadId);
                }, 500);
            }
            
            // Handle file input change - remove hidden field and existing file display when new file is selected
            $(document).on('change', 'input[type="file"]', function() {
                const fieldId = $(this).attr('id');
                if (this.files && this.files.length > 0) {
                    // New file selected - remove hidden field if exists
                    $('#' + fieldId + '_hidden').remove();
                }
            });
            
            updateTabNavigation();
            updateFooterButtons();

            // Handle Move to Lead button click
            $('#moveToLeadBtn').on('click', function() {
                // Update the hidden lead_id field in modal
                const currentLeadId = $('#lead_id').val();
                $('#move_lead_id').val(currentLeadId);
                
                // Load current lead assignment if lead exists
                if (currentLeadId) {
                    $.ajax({
                        url: '{{ route("add-lead.step-status", ":id") }}'.replace(':id', currentLeadId),
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response && response.lead && response.lead.lead_owner) {
                                $('#move_lead_assign_to').val(response.lead.lead_owner).selectpicker('refresh');
                            }
                        }
                    });
                }
                
                // Initialize select picker in modal
                $('#move_lead_assign_to').selectpicker('refresh');
            });

            // Handle Confirm Move to Lead button click
            $('#confirmMoveToLeadBtn').on('click', function() {
                const $btn = $(this);
                const originalHtml = $btn.html();
                const leadId = $('#move_lead_id').val();
                const leadAssignTo = $('#move_lead_assign_to').val();

                if (!leadAssignTo) {
                    Swal.fire({
                        icon: 'error',
                        text: 'Please select a user to assign the lead.',
                        toast: true,
                        position: "top-end",
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });
                    return;
                }

                if (!leadId) {
                    Swal.fire({
                        icon: 'error',
                        text: 'Please save the lead first before moving to lead.',
                        toast: true,
                        position: "top-end",
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });
                    return;
                }

                // Disable button and show loading
                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i>Moving...');

                $.ajax({
                    url: '{{ route("add-lead.move-to-lead") }}',
                    type: 'POST',
                    data: {
                        lead_id: leadId,
                        lead_assign_to: leadAssignTo
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                text: response.message || 'Lead moved successfully!',
                                toast: true,
                                position: "top-end",
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            });
                            
                            // Close modal
                            $('#moveToLeadModal').modal('hide');
                            
                            // Redirect to lead list or lead details page
                            setTimeout(function() {
                                window.location.href = response.redirect_url || '{{ route("lead-list.index") }}';
                            }, 1000);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: response.message || 'Failed to move lead.',
                                toast: true,
                                position: "top-end",
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            });
                            $btn.prop('disabled', false).html(originalHtml);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to move lead.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            text: errorMessage,
                            toast: true,
                            position: "top-end",
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

        });
    </script>
@endpush

