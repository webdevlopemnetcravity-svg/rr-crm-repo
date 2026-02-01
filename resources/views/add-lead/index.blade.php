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
                                    <option value="Meta Lead Ads">Meta Lead Ads</option>
                                    <option value="Google Ads">Google Ads</option>
                                    <option value="Walk-in">Walk-in</option>
                                    <option value="WhatsApp Inquiry">WhatsApp Inquiry</option>
                                    <option value="Reference">Reference</option>
                                    <option value="Website">Website</option>
                                    <option value="Email Marketing">Email Marketing</option>
                                </select>
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
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="country_of_origin" :fieldLabel="__('app.countryOfOrigin')">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 country-origin-select" name="country_of_origin" id="country_of_origin">
                                        <option value="">@lang('app.select')</option>
                                        @foreach($countryMasters ?? [] as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="country_of_origin_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
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
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="home_state" :fieldLabel="__('app.state')">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 address-state-select" name="home_state" id="home_state">
                                        <option value="">@lang('app.select')</option>
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="home_state_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="home_city" :fieldLabel="__('app.city')">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 address-city-select" name="home_city" id="home_city">
                                        <option value="">@lang('app.select')</option>
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="home_city_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
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
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="mailing_state" :fieldLabel="__('app.state')">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 address-state-select" name="mailing_state" id="mailing_state">
                                        <option value="">@lang('app.select')</option>
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="mailing_state_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="mailing_city" :fieldLabel="__('app.city')">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 address-city-select" name="mailing_city" id="mailing_city">
                                        <option value="">@lang('app.select')</option>
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="mailing_city_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
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
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="visa_category" fieldLabel="Visa Refusal Visa Category">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 visa-category-select" name="visa_category" id="visa_category">
                                        <option value="">@lang('app.select')</option>
                                        @foreach($visaCategories ?? [] as $vc)
                                            <option value="{{ $vc->id }}">{{ $vc->name }}</option>
                                        @endforeach
                                        <option value="other">@lang('app.other')</option>
                                    </select>
                                    <button type="button" class="btn btn-link p-0 visa-category-clear-btn" id="visa_category_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3" id="visa_category_other_wrapper" style="display: none;">
                                <x-forms.label class="mt-3" fieldId="visa_category_other" fieldLabel="Other Visa Category">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="visa_category_other" id="visa_category_other" placeholder="">
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
                            <div class="col-md-12" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="languages_spoken" :fieldLabel="__('app.languagesSpoken')">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 languages-spoken-select" name="languages_spoken[]" id="languages_spoken" multiple>
                                        @foreach($languages ?? [] as $lang)
                                            <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="languages_spoken_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
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
                                <div class="col-md-3" style="position: relative;">
                                    <x-forms.label class="mt-3" fieldId="pr_preferred_country" :fieldLabel="__('app.preferredCountry')">
                                    </x-forms.label>
                                    <div style="position: relative;">
                                        <select class="form-control height-35 f-14 pr-preferred-country-select" name="pr_preferred_country[]" id="pr_preferred_country" multiple>
                                            <option value="Canada">Canada</option>
                                            <option value="Australia">Australia</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="New Zealand">New Zealand</option>
                                            <option value="Germany">Germany</option>
                                            <option value="Ireland">Ireland</option>
                                            <option value="Portugal">Portugal</option>
                                            <option value="Other">@lang('app.other')</option>
                                        </select>
                                        <button type="button" class="btn btn-link p-0 select2-clear-btn" id="pr_preferred_country_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3" id="pr_preferred_country_other_wrapper" style="display: none;">
                                    <x-forms.label class="mt-3" fieldId="pr_preferred_country_other" fieldLabel="Other Preferred Country">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="pr_preferred_country_other" id="pr_preferred_country_other" value="">
                                </div>
                                <div class="col-md-3" style="position: relative;">
                                    <x-forms.label class="mt-3" fieldId="pr_pathway" fieldLabel="PR Pathway">
                                    </x-forms.label>
                                    <div style="position: relative;">
                                        <select class="form-control height-35 f-14 pr-pathway-select" name="pr_pathway" id="pr_pathway">
                                            <option value="">@lang('app.select')</option>
                                            <option value="Express Entry">Express Entry</option>
                                            <option value="PNP (Provincial Nominee Program)">PNP (Provincial Nominee Program)</option>
                                            <option value="State Nomination">State Nomination</option>
                                            <option value="Skilled Independent">Skilled Independent</option>
                                            <option value="Skilled Nominated">Skilled Nominated</option>
                                            <option value="Regional Migration">Regional Migration</option>
                                            <option value="Family Sponsored PR">Family Sponsored PR</option>
                                            <option value="Business PR">Business PR</option>
                                            <option value="Investor PR">Investor PR</option>
                                            <option value="Other">@lang('app.other')</option>
                                        </select>
                                        <button type="button" class="btn btn-link p-0 select2-clear-btn" id="pr_pathway_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3" id="pr_pathway_other_wrapper" style="display: none;">
                                    <x-forms.label class="mt-3" fieldId="pr_pathway_other" fieldLabel="Other PR Pathway">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="pr_pathway_other" id="pr_pathway_other" value="">
                                </div>
                                <div class="col-md-3" style="position: relative;">
                                    <x-forms.label class="mt-3" fieldId="pr_occupation_category" fieldLabel="Occupation Category">
                                    </x-forms.label>
                                    <div style="position: relative;">
                                        <select class="form-control height-35 f-14 pr-occupation-category-select" name="pr_occupation_category" id="pr_occupation_category">
                                            <option value="">@lang('app.select')</option>
                                            <option value="IT & Technology">IT & Technology</option>
                                            <option value="Healthcare">Healthcare</option>
                                            <option value="Engineering">Engineering</option>
                                            <option value="Trades">Trades</option>
                                            <option value="Hospitality">Hospitality</option>
                                            <option value="Education">Education</option>
                                            <option value="Finance">Finance</option>
                                            <option value="Logistics">Logistics</option>
                                            <option value="Agriculture">Agriculture</option>
                                            <option value="Other">@lang('app.other')</option>
                                        </select>
                                        <button type="button" class="btn btn-link p-0 select2-clear-btn" id="pr_occupation_category_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3" id="pr_occupation_category_other_wrapper" style="display: none;">
                                    <x-forms.label class="mt-3" fieldId="pr_occupation_category_other" fieldLabel="Other Occupation Category">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="pr_occupation_category_other" id="pr_occupation_category_other" value="">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="pr_points_system_awareness" fieldLabel="Points System Awareness">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="pr_points_system_awareness" id="pr_points_system_awareness">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                        <option value="Not Sure">Not Sure</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="pr_skill_assessment_status" fieldLabel="Skill Assessment Status">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="pr_skill_assessment_status" id="pr_skill_assessment_status">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Completed">Completed</option>
                                        <option value="In Process">In Process</option>
                                        <option value="Not Started">Not Started</option>
                                        <option value="Not Required">Not Required</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="pr_language_test_status" fieldLabel="Language Test Status">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="pr_language_test_status" id="pr_language_test_status">
                                        <option value="">@lang('app.select')</option>
                                        <option value="IELTS Given">IELTS Given</option>
                                        <option value="IELTS Booked">IELTS Booked</option>
                                        <option value="Planning to Give">Planning to Give</option>
                                        <option value="Not Required">Not Required</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Visit Visa Section -->
                        <div id="visitSection" class="form-section d-none">
                            <div class="row">
                                <div class="col-md-3" style="position: relative;">
                                    <x-forms.label class="mt-3" fieldId="visit_visiting_country" fieldLabel="Visiting Country">
                                    </x-forms.label>
                                    <div style="position: relative;">
                                        <select class="form-control height-35 f-14 visit-visiting-country-select" name="visit_visiting_country" id="visit_visiting_country">
                                            <option value="">@lang('app.select')</option>
                                            <option value="USA">USA</option>
                                            <option value="UK">UK</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Australia">Australia</option>
                                            <option value="Schengen">Schengen</option>
                                            <option value="UAE">UAE</option>
                                            <option value="Singapore">Singapore</option>
                                            <option value="Thailand">Thailand</option>
                                            <option value="Other">@lang('app.other')</option>
                                        </select>
                                        <button type="button" class="btn btn-link p-0 select2-clear-btn" id="visit_visiting_country_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3" id="visit_visiting_country_other_wrapper" style="display: none;">
                                    <x-forms.label class="mt-3" fieldId="visit_visiting_country_other" fieldLabel="Other Visiting Country">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="visit_visiting_country_other" id="visit_visiting_country_other" value="">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="visit_purpose" fieldLabel="Purpose of Visit">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="visit_purpose" id="visit_purpose">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Tourism">Tourism</option>
                                        <option value="Family Visit">Family Visit</option>
                                        <option value="Business Meeting">Business Meeting</option>
                                        <option value="Medical Treatment">Medical Treatment</option>
                                        <option value="Conference / Event">Conference / Event</option>
                                        <option value="Transit">Transit</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="visit_duration_of_stay" fieldLabel="Duration of Stay">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="visit_duration_of_stay" id="visit_duration_of_stay">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Up to 15 Days">Up to 15 Days</option>
                                        <option value="15 – 30 Days">15 – 30 Days</option>
                                        <option value="1 – 3 Months">1 – 3 Months</option>
                                        <option value="3 – 6 Months">3 – 6 Months</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="visit_sponsor_type" fieldLabel="Sponsor Type">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="visit_sponsor_type" id="visit_sponsor_type">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Self Sponsored">Self Sponsored</option>
                                        <option value="Family Sponsored">Family Sponsored</option>
                                        <option value="Company Sponsored">Company Sponsored</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="visit_invitation_letter" fieldLabel="Invitation Letter">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="visit_invitation_letter" id="visit_invitation_letter">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Work Permit Section -->
                        <div id="workSection" class="form-section d-none">
                            <div class="row">
                                <div class="col-md-3" style="position: relative;">
                                    <x-forms.label class="mt-3" fieldId="work_preferred_work_country" fieldLabel="Preferred Work Country">
                                    </x-forms.label>
                                    <div style="position: relative;">
                                        <select class="form-control height-35 f-14 work-preferred-work-country-select" name="work_preferred_work_country" id="work_preferred_work_country">
                                            <option value="">@lang('app.select')</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Australia">Australia</option>
                                            <option value="UK">UK</option>
                                            <option value="Germany">Germany</option>
                                            <option value="Poland">Poland</option>
                                            <option value="Lithuania">Lithuania</option>
                                            <option value="Malta">Malta</option>
                                            <option value="Romania">Romania</option>
                                            <option value="UAE">UAE</option>
                                            <option value="Other">@lang('app.other')</option>
                                        </select>
                                        <button type="button" class="btn btn-link p-0 select2-clear-btn" id="work_preferred_work_country_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3" id="work_preferred_work_country_other_wrapper" style="display: none;">
                                    <x-forms.label class="mt-3" fieldId="work_preferred_work_country_other" fieldLabel="Other Preferred Work Country">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="work_preferred_work_country_other" id="work_preferred_work_country_other" value="">
                                </div>
                                <div class="col-md-3" style="position: relative;">
                                    <x-forms.label class="mt-3" fieldId="work_industry_sector" fieldLabel="Industry">
                                    </x-forms.label>
                                    <div style="position: relative;">
                                        <select class="form-control height-35 f-14 work-industry-sector-select" name="work_industry_sector" id="work_industry_sector">
                                            <option value="">@lang('app.select')</option>
                                            @foreach($industryMasters ?? [] as $i)
                                                <option value="{{ $i->id }}">{{ $i->name }}</option>
                                            @endforeach
                                            <option value="other">@lang('app.other')</option>
                                        </select>
                                        <button type="button" class="btn btn-link p-0 select2-clear-btn" id="work_industry_sector_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3" id="work_industry_sector_other_wrapper" style="display: none;">
                                    <x-forms.label class="mt-3" fieldId="work_industry_sector_other" fieldLabel="Other Industry">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="work_industry_sector_other" id="work_industry_sector_other" value="">
                                </div>
                                <div class="col-md-3" style="position: relative;">
                                    <x-forms.label class="mt-3" fieldId="work_sector" fieldLabel="Sector">
                                    </x-forms.label>
                                    <div style="position: relative;">
                                        <select class="form-control height-35 f-14 work-sector-select" name="work_sector" id="work_sector">
                                            <option value="">@lang('app.select')</option>
                                            @foreach($sectorsMaster ?? [] as $s)
                                                <option value="{{ $s->id }}" data-industry-id="{{ $s->industry_id ?? '' }}">{{ $s->name }}</option>
                                            @endforeach
                                            <option value="other">@lang('app.other')</option>
                                        </select>
                                        <button type="button" class="btn btn-link p-0 select2-clear-btn" id="work_sector_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3" id="work_sector_other_wrapper" style="display: none;">
                                    <x-forms.label class="mt-3" fieldId="work_sector_other" fieldLabel="Other Sector">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="work_sector_other" id="work_sector_other" value="">
                                </div>
                                <div class="col-md-3" style="position: relative;">
                                    <x-forms.label class="mt-3" fieldId="work_designation" fieldLabel="Designation">
                                    </x-forms.label>
                                    <div style="position: relative;">
                                        <select class="form-control height-35 f-14 work-designation-select" name="work_designation" id="work_designation">
                                            <option value="">@lang('app.select')</option>
                                            @foreach($designationsMaster ?? [] as $d)
                                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-link p-0 select2-clear-btn" id="work_designation_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="work_job_offer_status" fieldLabel="Job Offer Status">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="work_job_offer_status" id="work_job_offer_status">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Available">Available</option>
                                        <option value="Applied">Applied</option>
                                        <option value="Not Available">Not Available</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="work_employer_type" fieldLabel="Employer Type">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="work_employer_type" id="work_employer_type">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Government">Government</option>
                                        <option value="Private">Private</option>
                                        <option value="Staffing Agency">Staffing Agency</option>
                                        <option value="Direct Employer">Direct Employer</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="work_language_requirement" fieldLabel="Language Requirement">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="work_language_requirement" id="work_language_requirement">
                                        <option value="">@lang('app.select')</option>
                                        <option value="IELTS Required">IELTS Required</option>
                                        <option value="IELTS Not Required">IELTS Not Required</option>
                                        <option value="Local Language Required">Local Language Required</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Student Visa Section -->
                        <div id="studentSection" class="form-section d-none">
                            <div class="row">
                                <div class="col-md-3" style="position: relative;">
                                    <x-forms.label class="mt-3" fieldId="student_preferred_study_country" fieldLabel="Preferred Study Country">
                                    </x-forms.label>
                                    <div style="position: relative;">
                                        <select class="form-control height-35 f-14 student-preferred-study-country-select" name="student_preferred_study_country[]" id="student_preferred_study_country" multiple>
                                            <option value="Canada">Canada</option>
                                            <option value="Australia">Australia</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="USA">USA</option>
                                            <option value="New Zealand">New Zealand</option>
                                            <option value="Germany">Germany</option>
                                            <option value="Ireland">Ireland</option>
                                            <option value="France">France</option>
                                            <option value="Italy">Italy</option>
                                            <option value="Other">@lang('app.other')</option>
                                        </select>
                                        <button type="button" class="btn btn-link p-0 select2-clear-btn" id="student_preferred_study_country_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3" id="student_preferred_study_country_other_wrapper" style="display: none;">
                                    <x-forms.label class="mt-3" fieldId="student_preferred_study_country_other" fieldLabel="Other Preferred Study Country">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="student_preferred_study_country_other" id="student_preferred_study_country_other" value="">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="student_education_level_applying_for" fieldLabel="Education Level Applying For">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="student_education_level_applying_for" id="student_education_level_applying_for">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Diploma">Diploma</option>
                                        <option value="Advanced Diploma">Advanced Diploma</option>
                                        <option value="Bachelor Degree">Bachelor Degree</option>
                                        <option value="Post Graduate Diploma">Post Graduate Diploma</option>
                                        <option value="Master Degree">Master Degree</option>
                                        <option value="PhD">PhD</option>
                                        <option value="Pathway Program">Pathway Program</option>
                                    </select>
                                </div>
                                <div class="col-md-3" style="position: relative;">
                                    <x-forms.label class="mt-3" fieldId="student_field_of_study" fieldLabel="Field of Study">
                                    </x-forms.label>
                                    <div style="position: relative;">
                                        <select class="form-control height-35 f-14 student-field-of-study-select" name="student_field_of_study" id="student_field_of_study">
                                            <option value="">@lang('app.select')</option>
                                            <option value="Engineering">Engineering</option>
                                            <option value="IT / Computer">IT / Computer</option>
                                            <option value="Business / Management">Business / Management</option>
                                            <option value="Hospitality">Hospitality</option>
                                            <option value="Healthcare">Healthcare</option>
                                            <option value="Nursing">Nursing</option>
                                            <option value="Education">Education</option>
                                            <option value="Agriculture">Agriculture</option>
                                            <option value="Design">Design</option>
                                            <option value="Science">Science</option>
                                            <option value="Other">@lang('app.other')</option>
                                        </select>
                                        <button type="button" class="btn btn-link p-0 select2-clear-btn" id="student_field_of_study_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-3" id="student_field_of_study_other_wrapper" style="display: none;">
                                    <x-forms.label class="mt-3" fieldId="student_field_of_study_other" fieldLabel="Other Field of Study">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="student_field_of_study_other" id="student_field_of_study_other" value="">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="student_intake" fieldLabel="Intake">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="student_intake" id="student_intake">
                                        <option value="">@lang('app.select')</option>
                                        <option value="January">January</option>
                                        <option value="May">May</option>
                                        <option value="September">September</option>
                                        <option value="Flexible">Flexible</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="student_intake_year" fieldLabel="Intake Year">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="student_intake_year" id="student_intake_year">
                                        <option value="">@lang('app.select')</option>
                                        <option value="2025">2025</option>
                                        <option value="2026">2026</option>
                                        <option value="2027">2027</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="student_budget_range" fieldLabel="Budget Range">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="student_budget_range" id="student_budget_range">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Below ₹10 Lakhs">Below ₹10 Lakhs</option>
                                        <option value="₹10 – 20 Lakhs">₹10 – 20 Lakhs</option>
                                        <option value="₹20 – 30 Lakhs">₹20 – 30 Lakhs</option>
                                        <option value="Above ₹30 Lakhs">Above ₹30 Lakhs</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="student_english_test_status" fieldLabel="English Test Status">
                                    </x-forms.label>
                                    <select class="form-control select-picker height-35 f-14" name="student_english_test_status" id="student_english_test_status">
                                        <option value="">@lang('app.select')</option>
                                        <option value="IELTS Given">IELTS Given</option>
                                        <option value="IELTS Booked">IELTS Booked</option>
                                        <option value="Preparing">Preparing</option>
                                        <option value="Not Required">Not Required</option>
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
                                <input type="text" class="form-control height-35 f-14" name="passport_number" id="passport_number" minlength="8" maxlength="9" pattern="[A-Za-z0-9]{8,9}" placeholder="letters and numbers only" autocomplete="off">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="passport_type" fieldLabel="Passport Type">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="passport_type" id="passport_type">
                                    <option value="">@lang('app.select')</option>
                                    @foreach($passportTypes ?? [] as $passportType)
                                        <option value="{{ $passportType->name }}" data-passport-type-id="{{ $passportType->id }}">{{ $passportType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="passport_category" fieldLabel="Passport Category">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="passport_category" id="passport_category">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Non-ECR">Non-ECR</option>
                                    <option value="ECR">ECR</option>
                                </select>
                                <div id="passport-category-message" class="mt-1 f-12" style="min-height: 1.4em;" aria-live="polite"></div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="place_of_issue" fieldLabel="Place of Issue">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="place_of_issue" id="place_of_issue">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Passport Office">Passport Office</option>
                                    <option value="Passport Seva Kendra (PSK)">Passport Seva Kendra (PSK)</option>
                                    <option value="Regional Passport Office (RPO)">Regional Passport Office (RPO)</option>
                                    <option value="Indian Mission Abroad">Indian Mission Abroad</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="passport_verification_status" fieldLabel="Verification Status">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="passport_verification_status" id="passport_verification_status">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Not Verified">Not Verified</option>
                                    <option value="Verified – Original Seen">Verified – Original Seen</option>
                                    <option value="Verified – Copy Only">Verified – Copy Only</option>
                                    <option value="Mismatch Found">Mismatch Found</option>
                                </select>
                            </div>
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="issuing_country" fieldLabel="Issuing Country">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 country-master-select" name="issuing_country" id="issuing_country">
                                        <option value="">@lang('app.select')</option>
                                        @foreach($countryMasters ?? [] as $country)
                                            <option value="{{ $country->id }}" {{ strtolower($country->name ?? '') === 'india' ? 'selected' : '' }}>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="issuing_country_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="city_where_issued" fieldLabel="City Where Issued">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 city-master-select" name="city_where_issued" id="city_where_issued">
                                        <option value="">@lang('app.select')</option>
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="city_where_issued_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
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
                                <div id="passport-validity-warning" class="mt-1 f-12" style="min-height: 1.4em;" aria-live="polite"></div>
                                <script>
                                    $(document).ready(function() {
                                        $('#issuance_date').on('change', function() {
                                            const issuanceDate = $(this).val();
                                            if (issuanceDate) {
                                                $('#expiration_date').attr('min', issuanceDate);
                                            }
                                            updatePassportValidityWarning();
                                        });
                                        $('#expiration_date').on('change', function() { updatePassportValidityWarning(); });
                                        function updatePassportValidityWarning() {
                                            var $msg = $('#passport-validity-warning');
                                            var expVal = ($('#expiration_date').val() || '').trim();
                                            if (!expVal) {
                                                $msg.removeClass('text-danger text-warning').html('');
                                                return;
                                            }
                                            var today = new Date();
                                            today.setHours(0, 0, 0, 0);
                                            var exp = new Date(expVal);
                                            exp.setHours(0, 0, 0, 0);
                                            var diffMs = exp - today;
                                            var monthsRemaining = diffMs / (1000 * 60 * 60 * 24 * 30.44);
                                            $msg.removeClass('text-danger text-warning');
                                            if (monthsRemaining < 0) {
                                                $msg.addClass('text-danger').html('<span class="font-weight-semibold">&lt; 6 months validity (Australia risk)</span>');
                                            } else if (monthsRemaining < 6) {
                                                $msg.addClass('text-danger').html('<span class="font-weight-semibold">&lt; 6 months validity (Australia risk)</span>');
                                            } else if (monthsRemaining < 12) {
                                                $msg.addClass('text-warning').html('<span class="font-weight-semibold">&lt; 12 months validity (Warning)</span>');
                                            } else {
                                                $msg.html('');
                                            }
                                        }
                                        // Run once on load if expiration already has a value (e.g. restored from saved data)
                                        $(function() { updatePassportValidityWarning(); });
                                    });
                                </script>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="last_passport_history" fieldLabel="Last Passport History">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="last_passport_history" id="last_passport_history">
                                    <option value="">@lang('app.select')</option>
                                    @foreach($passportHistories ?? [] as $passportHistory)
                                        <option value="{{ $passportHistory->name }}" data-passport-history-id="{{ $passportHistory->id }}">{{ $passportHistory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 old-passport-dependent-col">
                                <x-forms.label class="mt-3" fieldId="old_passport_number" fieldLabel="Old Passport Number">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="old_passport_number" id="old_passport_number" minlength="8" maxlength="9" pattern="[A-Za-z0-9]{8,9}" placeholder="letters and numbers only" autocomplete="off">
                            </div>
                            <div class="col-md-3 old-passport-dependent-col">
                                <x-forms.label class="mt-3" fieldId="old_passport_issue_year" fieldLabel="Old Passport Issue Year">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="old_passport_issue_year" id="old_passport_issue_year">
                                    <option value="">@lang('app.select')</option>
                                    @for($y = (int)date('Y'); $y >= 1950; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="passport_status" fieldLabel="Passport Status">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="passport_status" id="passport_status">
                                    <option value="">@lang('app.select')</option>
                                    @foreach($passportStatuses ?? [] as $passportStatus)
                                        <option value="{{ $passportStatus->name }}" data-passport-status-id="{{ $passportStatus->id }}">{{ $passportStatus->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 old-passport-dependent-col">
                                <x-forms.label class="mt-3" fieldId="lost_passport_history" fieldLabel="Reason">
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
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="spouse_country" fieldLabel="Spouse's Country">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 country-master-select" name="spouse_country" id="spouse_country">
                                        <option value="">@lang('app.select')</option>
                                        @foreach($countryMasters ?? [] as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="spouse_country_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="spouse_city_of_birth" fieldLabel="Spouse's City of Birth">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 city-master-select" name="spouse_city_of_birth" id="spouse_city_of_birth">
                                        <option value="">@lang('app.select')</option>
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="spouse_city_of_birth_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
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
                            </div>
                            <div class="col-md-12">
                                <x-forms.label class="mt-3" fieldId="spouse_address" fieldLabel="Spouse's Address">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="spouse_address" id="spouse_address">
                            </div>
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="spouse_state" fieldLabel="State">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 state-master-select" name="spouse_state" id="spouse_state">
                                        <option value="">@lang('app.select')</option>
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="spouse_state_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="spouse_city" fieldLabel="City">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 city-master-select" name="spouse_city" id="spouse_city">
                                        <option value="">@lang('app.select')</option>
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="spouse_city_clear" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
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
                        @php
                            $defaultCurrencySymbol = (company()->currency && company()->currency->currency_symbol) ? company()->currency->currency_symbol : '₹';
                        @endphp
                        <!-- Property Valuation Inputs -->
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_home" fieldLabel="Home">
                                </x-forms.label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text height-35 f-14 bg-light">{{ $defaultCurrencySymbol }}</span>
                                    </div>
                                    <input type="number" class="form-control height-35 f-14 valuation-input" name="property_home" id="property_home" min="0" step="1" onkeypress="return event.key !== '-' && event.key !== '+' && event.key !== 'e' && event.key !== 'E';">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_land" fieldLabel="Land">
                                </x-forms.label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text height-35 f-14 bg-light">{{ $defaultCurrencySymbol }}</span>
                                    </div>
                                    <input type="number" class="form-control height-35 f-14 valuation-input" name="property_land" id="property_land" min="0" step="1" onkeypress="return event.key !== '-' && event.key !== '+' && event.key !== 'e' && event.key !== 'E';">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_plot" fieldLabel="Plot">
                                </x-forms.label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text height-35 f-14 bg-light">{{ $defaultCurrencySymbol }}</span>
                                    </div>
                                    <input type="number" class="form-control height-35 f-14 valuation-input" name="property_plot" id="property_plot" min="0" step="1" onkeypress="return event.key !== '-' && event.key !== '+' && event.key !== 'e' && event.key !== 'E';">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_commercials" fieldLabel="Commercials">
                                </x-forms.label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text height-35 f-14 bg-light">{{ $defaultCurrencySymbol }}</span>
                                    </div>
                                    <input type="number" class="form-control height-35 f-14 valuation-input" name="property_commercials" id="property_commercials" min="0" step="1" onkeypress="return event.key !== '-' && event.key !== '+' && event.key !== 'e' && event.key !== 'E';">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_other" fieldLabel="Other">
                                </x-forms.label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text height-35 f-14 bg-light">{{ $defaultCurrencySymbol }}</span>
                                    </div>
                                    <input type="number" class="form-control height-35 f-14 valuation-input" name="property_other" id="property_other" min="0" step="1" onkeypress="return event.key !== '-' && event.key !== '+' && event.key !== 'e' && event.key !== 'E';">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_shop" fieldLabel="Shop">
                                </x-forms.label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text height-35 f-14 bg-light">{{ $defaultCurrencySymbol }}</span>
                                    </div>
                                    <input type="number" class="form-control height-35 f-14 valuation-input" name="property_shop" id="property_shop" min="0" step="1" onkeypress="return event.key !== '-' && event.key !== '+' && event.key !== 'e' && event.key !== 'E';">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_gold" fieldLabel="Gold">
                                </x-forms.label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text height-35 f-14 bg-light">{{ $defaultCurrencySymbol }}</span>
                                    </div>
                                    <input type="number" class="form-control height-35 f-14 valuation-input" name="property_gold" id="property_gold" min="0" step="1" onkeypress="return event.key !== '-' && event.key !== '+' && event.key !== 'e' && event.key !== 'E';">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="property_silver" fieldLabel="Silver">
                                </x-forms.label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text height-35 f-14 bg-light">{{ $defaultCurrencySymbol }}</span>
                                    </div>
                                    <input type="number" class="form-control height-35 f-14 valuation-input" name="property_silver" id="property_silver" min="0" step="1" onkeypress="return event.key !== '-' && event.key !== '+' && event.key !== 'e' && event.key !== 'E';">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Loan Info Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">@lang('app.loanInformation')</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="total_loan_value" fieldLabel="Total Loan Value">
                                </x-forms.label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text height-35 f-14 bg-light">{{ $defaultCurrencySymbol }}</span>
                                    </div>
                                    <input type="number" class="form-control height-35 f-14" name="total_loan_value" id="total_loan_value" min="0" step="1">
                                </div>
                                <div id="total_loan_value_level" class="small mt-1" style="display:none;" role="alert"></div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="loan_years" fieldLabel="Loan Years">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="loan_years" id="loan_years" min="0" step="1">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="loan_availed_on" fieldLabel="Loan Availed On">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="loan_availed_on" id="loan_availed_on" max="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="total_valuation" fieldLabel="Total Asset Valuation">
                                </x-forms.label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text height-35 f-14 bg-light">{{ $defaultCurrencySymbol }}</span>
                                    </div>
                                    <input type="number" id="total_valuation" class="form-control height-35 f-14" readonly placeholder="@lang('app.autoCalculated')" name="total_valuation">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="total_loan_value_copy" fieldLabel="Total Loan Value">
                                </x-forms.label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text height-35 f-14 bg-light">{{ $defaultCurrencySymbol }}</span>
                                    </div>
                                    <input type="number" id="total_loan_value_copy" class="form-control height-35 f-14" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="net_worth" fieldLabel="Net Worth">
                                </x-forms.label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text height-35 f-14 bg-light">{{ $defaultCurrencySymbol }}</span>
                                    </div>
                                    <input type="number" id="net_worth" class="form-control height-35 f-14" readonly name="net_worth">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Financial Status Tab -->
                    <div class="tab-pane fade" id="nav-financial" role="tabpanel" aria-labelledby="nav-financial-tab">
                        <p class="small-text mt-2 mb-3">@lang('app.enterIncomeForEachUsers')</p>
                        
                        <!-- Income Inputs (currency symbol and validation same as Property Details) -->
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="father_income" fieldLabel="Father's Income">
                                </x-forms.label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text height-35 f-14 bg-light">{{ $defaultCurrencySymbol ?? '₹' }}</span>
                                    </div>
                                    <input type="number" class="form-control height-35 f-14 income-input" name="father_income" id="father_income" min="0" step="1" onkeypress="return event.key !== '-' && event.key !== '+' && event.key !== 'e' && event.key !== 'E';">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mother_income" fieldLabel="Mother's Income">
                                </x-forms.label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text height-35 f-14 bg-light">{{ $defaultCurrencySymbol ?? '₹' }}</span>
                                    </div>
                                    <input type="number" class="form-control height-35 f-14 income-input" name="mother_income" id="mother_income" min="0" step="1" onkeypress="return event.key !== '-' && event.key !== '+' && event.key !== 'e' && event.key !== 'E';">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="candidate_income" fieldLabel="Candidate's Income">
                                </x-forms.label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text height-35 f-14 bg-light">{{ $defaultCurrencySymbol ?? '₹' }}</span>
                                    </div>
                                    <input type="number" class="form-control height-35 f-14 income-input" name="candidate_income" id="candidate_income" min="0" step="1" onkeypress="return event.key !== '-' && event.key !== '+' && event.key !== 'e' && event.key !== 'E';">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_income" fieldLabel="Spouse Income">
                                </x-forms.label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text height-35 f-14 bg-light">{{ $defaultCurrencySymbol ?? '₹' }}</span>
                                    </div>
                                    <input type="number" class="form-control height-35 f-14 income-input" name="spouse_income" id="spouse_income" min="0" step="1" onkeypress="return event.key !== '-' && event.key !== '+' && event.key !== 'e' && event.key !== 'E';">
                                </div>
                            </div>
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="total_income" fieldLabel="Total Income">
                                </x-forms.label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text height-35 f-14 bg-light">{{ $defaultCurrencySymbol ?? '₹' }}</span>
                                    </div>
                                    <input type="number" id="total_income" class="form-control height-35 f-14" readonly placeholder="@lang('app.autoCalculated')" name="total_income">
                                </div>
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
            // Make visa categories available in JavaScript
            const visaCategories = @json($visaCategories ?? []);
            
            // Make languages available in JavaScript
            const languages = @json($languages ?? []);
            
            // Make passport types available in JavaScript
            const passportTypes = @json($passportTypes ?? []);
            
            // Make passport statuses available in JavaScript
            const passportStatuses = @json($passportStatuses ?? []);
            
            // Make passport histories available in JavaScript
            const passportHistories = @json($passportHistories ?? []);

            // Make country/state/city masters available in JavaScript
            const countryMasters = @json($countryMasters ?? []);
            const stateMasters = @json($stateMasters ?? []);
            const cityMasters = @json($cityMasters ?? []);
            const industryMasters = @json($industryMasters ?? []);
            const organizationTypes = @json($organizationTypes ?? []);
            const relationshipsMaster = @json($relationshipsMaster ?? []);
            const sectorsMaster = @json($sectorsMaster ?? []);
            const designationsMaster = @json($designationsMaster ?? []);
        
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

            // Initialize searchable Select2 for Visa Category (Visa Granted)
            if ($('#visa_category').length && !$('#visa_category').hasClass('select2-hidden-accessible')) {
                $('#visa_category').select2({
                    placeholder: '@lang("app.select") @lang("app.visaCategory")',
                    allowClear: false,
                    width: '100%'
                });
            }
            attachSelect2ClearButton('#visa_category', 'visa_category_clear');
            $('#visa_category').off('change.visaCategoryOther').on('change.visaCategoryOther', function() {
                var v = $(this).val();
                if (v === 'other') {
                    $('#visa_category_other_wrapper').show();
                } else {
                    $('#visa_category_other_wrapper').hide();
                    $('#visa_category_other').val('');
                }
            });

            // Occupation Category: same dropdown as Professional Experience Industry (Select2 + clear button)
            initSelect2IfNeeded('#pr_occupation_category', '@lang("app.select") Occupation Category');
            attachSelect2ClearButton('#pr_occupation_category', 'pr_occupation_category_clear');

            // Preferred Country (PR): multi-select, same as Student Preferred Study Country
            if ($('#pr_preferred_country').length && !$('#pr_preferred_country').hasClass('select2-hidden-accessible')) {
                $('#pr_preferred_country').select2({
                    placeholder: '@lang("app.select") @lang("app.preferredCountry")',
                    allowClear: false,
                    width: '100%',
                    multiple: true,
                    closeOnSelect: false
                });
            }
            attachSelect2ClearButton('#pr_preferred_country', 'pr_preferred_country_clear');
            $(document).on('change', '#pr_preferred_country', function() {
                var v = $(this).val();
                var hasOther = Array.isArray(v) && v.indexOf('Other') !== -1;
                if (hasOther) {
                    $('#pr_preferred_country_other_wrapper').show();
                } else {
                    $('#pr_preferred_country_other_wrapper').hide();
                    $('#pr_preferred_country_other').val('');
                }
            });

            // Visiting Country (Visit Visa): same as PR Pathway (Select2 + clear + Other)
            initSelect2IfNeeded('#visit_visiting_country', '@lang("app.select") Visiting Country');
            attachSelect2ClearButton('#visit_visiting_country', 'visit_visiting_country_clear');
            $(document).on('change', '#visit_visiting_country', function() {
                var v = $(this).val();
                if (v === 'Other') {
                    $('#visit_visiting_country_other_wrapper').show();
                } else {
                    $('#visit_visiting_country_other_wrapper').hide();
                    $('#visit_visiting_country_other').val('');
                }
            });

            // Preferred Work Country (Work Permit): same as Visiting Country (Select2 + clear + Other)
            initSelect2IfNeeded('#work_preferred_work_country', '@lang("app.select") Preferred Work Country');
            attachSelect2ClearButton('#work_preferred_work_country', 'work_preferred_work_country_clear');
            $(document).on('change', '#work_preferred_work_country', function() {
                var v = $(this).val();
                if (v === 'Other') {
                    $('#work_preferred_work_country_other_wrapper').show();
                } else {
                    $('#work_preferred_work_country_other_wrapper').hide();
                    $('#work_preferred_work_country_other').val('');
                }
            });

            // Work Permit: Industry / Sector (same as Professional Experience tab – master data + Other + filter sector by industry)
            initSelect2IfNeeded('#work_industry_sector', '@lang("app.select") @lang("app.menu.industry")');
            initSelect2IfNeeded('#work_sector', '@lang("app.select") @lang("app.menu.sector")');
            attachSelect2ClearButton('#work_industry_sector', 'work_industry_sector_clear');
            attachSelect2ClearButton('#work_sector', 'work_sector_clear');
            // Work Permit: Designation (same as Professional Experience – master + tags for custom text)
            if ($('#work_designation').length && !$('#work_designation').hasClass('select2-hidden-accessible')) {
                $('#work_designation').select2({
                    placeholder: 'Select or type custom',
                    allowClear: false,
                    width: '100%',
                    tags: true
                });
            }
            attachSelect2ClearButton('#work_designation', 'work_designation_clear');
            $(document).on('change', '#work_industry_sector', function() {
                var v = $(this).val();
                if (v === 'other') {
                    $('#work_industry_sector_other_wrapper').show();
                } else {
                    $('#work_industry_sector_other_wrapper').hide();
                    $('#work_industry_sector_other').val('');
                }
                filterWorkSectorsByIndustry();
            });
            $(document).on('change', '#work_sector', function() {
                var v = $(this).val();
                if (v === 'other') {
                    $('#work_sector_other_wrapper').show();
                } else {
                    $('#work_sector_other_wrapper').hide();
                    $('#work_sector_other').val('');
                }
            });
            function filterWorkSectorsByIndustry() {
                const industryId = $('#work_industry_sector').val();
                const $sectorSelect = $('#work_sector');
                let optionsHtml = '<option value="">@lang("app.select")</option>';
                sectorsMaster.forEach(function (s) {
                    const show = !industryId || industryId === 'other' || String(s.industry_id) === String(industryId);
                    if (show) {
                        optionsHtml += '<option value="' + s.id + '">' + (s.name || '') + '</option>';
                    }
                });
                optionsHtml += '<option value="other">@lang("app.other")</option>';
                if ($sectorSelect.hasClass('select2-hidden-accessible')) {
                    $sectorSelect.select2('destroy');
                }
                $sectorSelect.html(optionsHtml).val(null).trigger('change');
                $('#work_sector_other_wrapper').hide();
                $('#work_sector_other').val('');
                initSelect2IfNeeded('#work_sector', '@lang("app.select") @lang("app.menu.sector")');
                attachSelect2ClearButton('#work_sector', 'work_sector_clear');
            }

            // PR Pathway: same dropdown style (Select2 + clear button)
            initSelect2IfNeeded('#pr_pathway', '@lang("app.select") PR Pathway');
            attachSelect2ClearButton('#pr_pathway', 'pr_pathway_clear');
            $(document).on('change', '#pr_pathway', function() {
                var v = $(this).val();
                if (v === 'Other') {
                    $('#pr_pathway_other_wrapper').show();
                } else {
                    $('#pr_pathway_other_wrapper').hide();
                    $('#pr_pathway_other').val('');
                }
            });

            // Preferred Study Country (Student Visa): multi-select, same style as Languages Spoken + Other option
            if ($('#student_preferred_study_country').length && !$('#student_preferred_study_country').hasClass('select2-hidden-accessible')) {
                $('#student_preferred_study_country').select2({
                    placeholder: '@lang("app.select") Preferred Study Country',
                    allowClear: false,
                    width: '100%',
                    multiple: true,
                    closeOnSelect: false
                });
            }
            attachSelect2ClearButton('#student_preferred_study_country', 'student_preferred_study_country_clear');
            $(document).on('change', '#student_preferred_study_country', function() {
                var v = $(this).val();
                var hasOther = Array.isArray(v) && v.indexOf('Other') !== -1;
                if (hasOther) {
                    $('#student_preferred_study_country_other_wrapper').show();
                } else {
                    $('#student_preferred_study_country_other_wrapper').hide();
                    $('#student_preferred_study_country_other').val('');
                }
            });

            // Field of Study (Student Visa): same as PR Pathway (Select2 + clear + Other)
            initSelect2IfNeeded('#student_field_of_study', '@lang("app.select") Field of Study');
            attachSelect2ClearButton('#student_field_of_study', 'student_field_of_study_clear');
            $(document).on('change', '#student_field_of_study', function() {
                var v = $(this).val();
                if (v === 'Other') {
                    $('#student_field_of_study_other_wrapper').show();
                } else {
                    $('#student_field_of_study_other_wrapper').hide();
                    $('#student_field_of_study_other').val('');
                }
            });

            // Initialize searchable Select2 for Languages Spoken (multiple selection)
            if ($('#languages_spoken').length && !$('#languages_spoken').hasClass('select2-hidden-accessible')) {
                $('#languages_spoken').select2({
                    placeholder: '@lang("app.select") @lang("app.languagesSpoken")',
                    allowClear: false,
                    width: '100%',
                    multiple: true,
                    closeOnSelect: false
                });
            }
            attachSelect2ClearButton('#languages_spoken', 'languages_spoken_clear');

            // Initialize searchable Select2 for Country/State/City masters (Tab 1)
            const stateUrlTemplate = "{{ route('add-lead.get-states', ':countryId') }}";
            const cityUrlTemplate = "{{ route('add-lead.get-cities', ':stateId') }}";
            const cityByCountryUrlTemplate = "{{ route('add-lead.get-cities-by-country', ':countryId') }}";

            function initSelect2IfNeeded(selector, placeholderText) {
                if ($(selector).length && !$(selector).hasClass('select2-hidden-accessible')) {
                    $(selector).select2({
                        placeholder: placeholderText,
                        allowClear: false,
                        width: '100%'
                    });
                }
            }

            // City dropdowns: same as Designation – suggestions from API + type custom and save
            function initSelect2CityWithTags(selector, placeholderText) {
                if ($(selector).length && !$(selector).hasClass('select2-hidden-accessible')) {
                    $(selector).select2({
                        placeholder: placeholderText || 'Select or type city',
                        allowClear: false,
                        width: '100%',
                        tags: true
                    });
                }
            }

            // Set select value by saved text; for city (tags:true) add option if not found so custom city is restored
            function setSelectBySavedTextOrTag($select, savedText) {
                if (!savedText) return;
                var str = String(savedText).trim();
                if (!str) return;
                if (!isNaN(str)) {
                    $select.val(str).trigger('change');
                    return;
                }
                var found = false;
                $select.find('option').each(function () {
                    if ($(this).text().trim() === str || $(this).val() === str) {
                        $select.val($(this).val()).trigger('change');
                        found = true;
                        return false;
                    }
                });
                if (!found) {
                    var $opt = $('<option></option>').attr('value', str).text(str);
                    $select.append($opt).val(str).trigger('change');
                }
            }

            // Reusable clear button for any Select2 dropdown (single or multiple)
            function attachSelect2ClearButton(selectSelector, clearButtonId) {
                const $sel = $(selectSelector);
                const $clearBtn = $('#' + clearButtonId);
                if (!$sel.length || !$clearBtn.length) return;
                function toggleClear() {
                    const val = $sel.val();
                    const hasVal = Array.isArray(val) ? (val && val.length > 0) : (val && val !== '');
                    $clearBtn.toggle(!!hasVal);
                }
                $clearBtn.off('click').on('click', function(e) {
                    e.preventDefault();
                    $sel.val(null).trigger('change');
                    toggleClear();
                });
                $sel.off('change.clearBtn').on('change.clearBtn', toggleClear);
                toggleClear();
            }

            function initSelect2NoSearch(selector, placeholderText) {
                if ($(selector).length && !$(selector).hasClass('select2-hidden-accessible')) {
                    $(selector).select2({
                        placeholder: placeholderText,
                        allowClear: false,
                        width: '100%',
                        minimumResultsForSearch: Infinity
                    });
                }
            }

            initSelect2IfNeeded('#country_of_origin', '@lang("app.select") @lang("app.countryOfOrigin")');
            initSelect2IfNeeded('#home_state', '@lang("app.select") @lang("app.state")');
            initSelect2CityWithTags('#home_city', '@lang("app.select") @lang("app.city")');
            initSelect2IfNeeded('#mailing_state', '@lang("app.select") @lang("app.state")');
            initSelect2CityWithTags('#mailing_city', '@lang("app.select") @lang("app.city")');
            attachSelect2ClearButton('#country_of_origin', 'country_of_origin_clear');
            attachSelect2ClearButton('#home_state', 'home_state_clear');
            attachSelect2ClearButton('#home_city', 'home_city_clear');
            attachSelect2ClearButton('#mailing_state', 'mailing_state_clear');
            attachSelect2ClearButton('#mailing_city', 'mailing_city_clear');
            // Passport dropdowns without search (passport_type, passport_category, place_of_issue, last_passport_history, old_passport_issue_year) use select-picker — same as Education Passing Year — inited via initializeSelectPickers()
            
            // Set default passport type to "Ordinary Passport" (id 1) if no value is set
            function setDefaultPassportType() {
                const $passportType = $('#passport_type');
                if ($passportType.length && (!$passportType.val() || $passportType.val() === '')) {
                    // Find "Ordinary Passport" option (id 1) and select it
                    const ordinaryPassportOption = $passportType.find('option[data-passport-type-id="1"]');
                    if (ordinaryPassportOption.length) {
                        $passportType.val(ordinaryPassportOption.val()).selectpicker('refresh');
                    }
                }
            }
            
            // Set default after selectpickers are initialized (only for new forms)
            setTimeout(function() {
                // Only set default if we're not loading existing data (check if lead_id exists in URL)
                const urlParams = new URLSearchParams(window.location.search);
                const leadId = urlParams.get('lead_id');
                if (!leadId) {
                    setDefaultPassportType();
                }
            }, 500);
            
            initSelect2IfNeeded('#issuing_country', '@lang("app.select") Issuing Country');
            initSelect2CityWithTags('#city_where_issued', '@lang("app.select") City Where Issued');
            initSelect2IfNeeded('#spouse_country', '@lang("app.select") Spouse\'s Country');
            initSelect2IfNeeded('#spouse_state', '@lang("app.select") @lang("app.state")');
            initSelect2CityWithTags('#spouse_city', '@lang("app.select") @lang("app.city")');
            initSelect2CityWithTags('#spouse_city_of_birth', '@lang("app.select") Spouse\'s City of Birth');
            attachSelect2ClearButton('#issuing_country', 'issuing_country_clear');
            attachSelect2ClearButton('#city_where_issued', 'city_where_issued_clear');
            attachSelect2ClearButton('#spouse_country', 'spouse_country_clear');
            attachSelect2ClearButton('#spouse_state', 'spouse_state_clear');
            attachSelect2ClearButton('#spouse_city', 'spouse_city_clear');
            attachSelect2ClearButton('#spouse_city_of_birth', 'spouse_city_of_birth_clear');

            function resetCitySelect($citySelect) {
                $citySelect.html('<option value="">@lang("app.select")</option>');
                $citySelect.val(null).trigger('change');
                $citySelect.prop('disabled', true);
            }

            function resetStateSelect($stateSelect) {
                $stateSelect.html('<option value="">@lang("app.select")</option>');
                $stateSelect.val(null).trigger('change');
                $stateSelect.prop('disabled', true);
            }

            function loadStates(countryId) {
                if (!countryId) {
                    resetStateSelect($('#home_state'));
                    resetStateSelect($('#mailing_state'));
                    resetCitySelect($('#home_city'));
                    resetCitySelect($('#mailing_city'));
                    return $.Deferred().resolve().promise();
                }

                const url = stateUrlTemplate.replace(':countryId', countryId);
                return $.get(url).then(function (res) {
                    if (res && res.options) {
                        $('#home_state').html(res.options).prop('disabled', false).val(null).trigger('change');
                        $('#mailing_state').html(res.options).prop('disabled', false).val(null).trigger('change');
                        resetCitySelect($('#home_city'));
                        resetCitySelect($('#mailing_city'));
                    }
                });
            }

            function loadSpouseStates(countryId) {
                if (!countryId) {
                    resetStateSelect($('#spouse_state'));
                    resetCitySelect($('#spouse_city'));
                    return $.Deferred().resolve().promise();
                }

                const url = stateUrlTemplate.replace(':countryId', countryId);
                return $.get(url).then(function (res) {
                    if (res && res.options) {
                        $('#spouse_state').html(res.options).prop('disabled', false).val(null).trigger('change');
                        resetCitySelect($('#spouse_city'));
                    }
                });
            }

            function loadCities(stateId, $citySelect) {
                if (!stateId) {
                    resetCitySelect($citySelect);
                    return $.Deferred().resolve().promise();
                }

                const url = cityUrlTemplate.replace(':stateId', stateId);
                return $.get(url).then(function (res) {
                    if (res && res.options) {
                        $citySelect.html(res.options).prop('disabled', false).val(null).trigger('change');
                    }
                });
            }

            // Load states for a relative contact row (same as Personal Details: country -> state)
            function loadStatesForRelativeRow(countryId, contactNum) {
                const stateSel = '#relative_state_' + contactNum;
                const citySel = '#relative_city_' + contactNum;
                if (!countryId) {
                    resetStateSelect($(stateSel));
                    resetCitySelect($(citySel));
                    return $.Deferred().resolve().promise();
                }
                const url = stateUrlTemplate.replace(':countryId', countryId);
                return $.get(url).then(function (res) {
                    if (res && res.options) {
                        $(stateSel).html(res.options).prop('disabled', false).val(null).trigger('change');
                        resetCitySelect($(citySel));
                    }
                });
            }

            function loadCitiesByCountry(countryId, $citySelect) {
                if (!countryId) {
                    $citySelect.html('<option value="">@lang('app.select')</option>').val(null).trigger('change');
                    return $.Deferred().resolve().promise();
                }
                const url = cityByCountryUrlTemplate.replace(':countryId', countryId);
                return $.get(url).then(function (res) {
                    if (res && res.options) {
                        $citySelect.html(res.options).prop('disabled', false).val(null).trigger('change');
                    }
                });
            }

            // Calculate and display job experience only when both Duration From and Duration To are selected
            function updateJobExperience(jobNum) {
                const fromVal = $('#job_duration_from_' + jobNum).val();
                const isCurrentJob = $('#job_current_job_' + jobNum).is(':checked');
                const $out = $('#job_experience_' + jobNum);
                if (isCurrentJob) {
                    $out.val('');
                    return;
                }
                const toVal = $('#job_duration_to_' + jobNum).val();
                if (!fromVal || !toVal) {
                    $out.val('');
                    return;
                }
                const from = new Date(fromVal);
                const to = new Date(toVal);
                if (to < from) {
                    $out.val('');
                    return;
                }
                let months = (to.getFullYear() - from.getFullYear()) * 12 + (to.getMonth() - from.getMonth());
                if (to.getDate() < from.getDate()) months -= 1;
                if (months < 0) months = 0;
                const years = Math.floor(months / 12);
                const remMonths = months % 12;
                const parts = [];
                if (years > 0) parts.push(years + ' year' + (years !== 1 ? 's' : ''));
                if (remMonths > 0) parts.push(remMonths + ' month' + (remMonths !== 1 ? 's' : ''));
                $out.val(parts.length ? parts.join(' ') : '0 months');
            }

            // Filter sector dropdown: show all sectors when no industry or industry is "other"; show only that industry's sectors when industry selected
            function filterSectorsByIndustry(jobNum) {
                const industryId = $('#job_industry_' + jobNum).val();
                const $sectorSelect = $('#job_sector_' + jobNum);
                let optionsHtml = '<option value="">@lang("app.select")</option>';
                sectorsMaster.forEach(function (s) {
                    const show = !industryId || industryId === 'other' || String(s.industry_id) === String(industryId);
                    if (show) {
                        optionsHtml += '<option value="' + s.id + '">' + (s.name || '') + '</option>';
                    }
                });
                optionsHtml += '<option value="other">@lang("app.other")</option>';
                if ($sectorSelect.hasClass('select2-hidden-accessible')) {
                    $sectorSelect.select2('destroy');
                }
                $sectorSelect.html(optionsHtml).val(null).trigger('change');
                initSelect2IfNeeded('#job_sector_' + jobNum, '@lang("app.select") @lang("app.menu.sector")');
            }

            function setSelectBySavedText($select, savedText) {
                if (!savedText) return;

                // If savedText is numeric string, try direct set (backward compatibility)
                if (!isNaN(savedText)) {
                    $select.val(savedText).trigger('change');
                    return;
                }

                let found = false;
                $select.find('option').each(function () {
                    if ($(this).text().trim() === String(savedText).trim()) {
                        $select.val($(this).val()).trigger('change');
                        found = true;
                        return false;
                    }
                });

                if (!found) {
                    $select.val(null).trigger('change');
                }
            }

            // Country of origin change -> reload states/cities
            $('#country_of_origin').on('change', function () {
                const countryId = $(this).val();
                loadStates(countryId);
            });

            // Issuing country change -> reload City Where Issued (cities in that country only)
            $('#issuing_country').on('change', function () {
                loadCitiesByCountry($(this).val(), $('#city_where_issued'));
            });
            // On load: if Issuing Country already has a value (e.g. India), load its cities into City Where Issued
            var initialIssuingCountry = $('#issuing_country').val();
            if (initialIssuingCountry) {
                loadCitiesByCountry(initialIssuingCountry, $('#city_where_issued'));
            }

            // Passport Category: Non-ECR -> green tag, ECR -> red warning under field
            function updatePassportCategoryMessage() {
                var $msg = $('#passport-category-message');
                var val = ($('#passport_category').val() || '').trim();
                $msg.removeClass('text-danger text-success').html('');
                if (val === 'Non-ECR') {
                    $msg.addClass('text-success').html('<span class="badge badge-success" style="background-color:#28a745;color:#fff;">Non-ECR</span>');
                } else if (val === 'ECR') {
                    $msg.addClass('text-danger').html('<span class="font-weight-semibold">ECR passport – additional clearance may be required</span>');
                }
            }
            $(document).on('change', '#passport_category', updatePassportCategoryMessage);
            updatePassportCategoryMessage();

            // Last Passport History: when "No Previous Passport" hide Old Passport Number & Old Passport Issue Year; otherwise show
            function toggleOldPassportFields() {
                var val = ($('#last_passport_history').val() || '').trim();
                if (val === 'No Previous Passport') {
                    $('.old-passport-dependent-col').hide();
                    $('#old_passport_number').val('');
                    var $yr = $('#old_passport_issue_year');
                    $yr.val(null);
                    if ($yr.data('selectpicker')) {
                        $yr.selectpicker('refresh');
                    }
                    $yr.trigger('change');
                    $('#lost_passport_history').val('');
                } else {
                    $('.old-passport-dependent-col').show();
                }
            }
            $(document).on('change', '#last_passport_history', toggleOldPassportFields);
            toggleOldPassportFields();

            // Passport Status: if expiration date is before today, auto-select "Expired"
            function syncPassportStatusFromExpiry() {
                var $st = $('#passport_status');
                if (!$st.length) return;
                var expVal = ($('#expiration_date').val() || '').trim();
                if (!expVal) return;
                var today = new Date();
                today.setHours(0, 0, 0, 0);
                var exp = new Date(expVal);
                exp.setHours(0, 0, 0, 0);
                if (exp < today) {
                    // Find "Expired" option from master data
                    var expiredOption = $st.find('option').filter(function() {
                        return $(this).text().trim() === 'Expired';
                    });
                    if (expiredOption.length) {
                        $st.val(expiredOption.val());
                    } else {
                        // Fallback: try to set by value if "Expired" exists
                        $st.val('Expired');
                    }
                } else {
                    // Future date or today: do not auto-select any option
                    $st.val('');
                }
                if ($st.data('selectpicker')) {
                    $st.selectpicker('refresh');
                }
            }
            $(document).on('change', '#expiration_date', syncPassportStatusFromExpiry);
            syncPassportStatusFromExpiry();

            // Spouse country change -> reload spouse states/cities and spouse city of birth
            $('#spouse_country').on('change', function () {
                var countryId = $(this).val();
                loadSpouseStates(countryId);
                loadCitiesByCountry(countryId, $('#spouse_city_of_birth'));
            });

            // Home state change -> reload home cities
            $('#home_state').on('change', function () {
                loadCities($(this).val(), $('#home_city'));
            });

            // Mailing state change -> reload mailing cities
            $('#mailing_state').on('change', function () {
                loadCities($(this).val(), $('#mailing_city'));
            });

            // Spouse state change -> reload spouse cities
            $('#spouse_state').on('change', function () {
                loadCities($(this).val(), $('#spouse_city'));
            });

            // Disable state/city until country is selected
            resetStateSelect($('#home_state'));
            resetStateSelect($('#mailing_state'));
            resetCitySelect($('#home_city'));
            resetCitySelect($('#mailing_city'));
            // Spouse state/city depend on spouse_country/state
            resetStateSelect($('#spouse_state'));
            resetCitySelect($('#spouse_city'));

            // Handle mailing address same as home address checkbox
            $('#mailing_same_as_home').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#mailing_address').val($('#home_address').val());
                    $('#mailing_pin_code').val($('#home_pin_code').val());

                    // Copy state/city selections (after city options are available)
                    const homeStateVal = $('#home_state').val();
                    const homeCityVal = $('#home_city').val();
                    $('#mailing_state').val(homeStateVal).trigger('change');
                    if (homeStateVal) {
                        loadCities(homeStateVal, $('#mailing_city')).then(function () {
                            $('#mailing_city').val(homeCityVal).trigger('change');
                        });
                    } else {
                        $('#mailing_city').val(null).trigger('change');
                    }
                    
                    // Disable mailing address fields
                    $('#mailing_address, #mailing_city, #mailing_state, #mailing_pin_code').prop('disabled', true);
                } else {
                    // Enable mailing address fields
                    $('#mailing_address, #mailing_city, #mailing_state, #mailing_pin_code').prop('disabled', false);
                }
            });

            // Auto-fill mailing address when home address changes (if checkbox is checked)
            $('#home_address, #home_pin_code').on('input', function() {
                if ($('#mailing_same_as_home').is(':checked')) {
                    $('#mailing_address').val($('#home_address').val());
                    $('#mailing_pin_code').val($('#home_pin_code').val());
                }
            });

            // Auto-fill mailing state/city when home state/city changes (if checkbox is checked)
            $('#home_state, #home_city').on('change', function () {
                if ($('#mailing_same_as_home').is(':checked')) {
                    const homeStateVal = $('#home_state').val();
                    const homeCityVal = $('#home_city').val();
                    $('#mailing_state').val(homeStateVal).trigger('change');
                    if (homeStateVal) {
                        loadCities(homeStateVal, $('#mailing_city')).then(function () {
                            $('#mailing_city').val(homeCityVal).trigger('change');
                        });
                    } else {
                        $('#mailing_city').val(null).trigger('change');
                    }
                }
                // Update relative contacts that have "Contact Address Same As Home Address" checked
                $('.relative-contact-same-as-home-cb:checked').each(function() {
                    const num = $(this).data('contact-num');
                    if (num && typeof copyHomeToRelativeContact === 'function') {
                        copyHomeToRelativeContact(num);
                    }
                });
            });

            // When home address or pin or country change, update relative contacts with same-as-home checked
            $('#home_address, #home_pin_code').on('input', function() {
                $('.relative-contact-same-as-home-cb:checked').each(function() {
                    const num = $(this).data('contact-num');
                    if (num) {
                        $('#relative_contact_address_' + num).val($('#home_address').val());
                        $('#relative_zip_code_' + num).val($('#home_pin_code').val());
                    }
                });
            });
            $('#country_of_origin').on('change', function() {
                $('.relative-contact-same-as-home-cb:checked').each(function() {
                    const num = $(this).data('contact-num');
                    if (num && typeof copyHomeToRelativeContact === 'function') {
                        copyHomeToRelativeContact(num);
                    }
                });
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
                    $('#visa_category').val(null).trigger('change');
                    $('#visa_category_other').val('');
                    $('#visa_category_other_wrapper').hide();
                    
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
                    $('#visa_category').val(null).trigger('change');
                    $('#visa_category_other').val('');
                    $('#visa_category_other_wrapper').hide();
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
                
                // Build visa category options
                let categoryOptions = '<option value="">@lang("app.select")</option>';
                visaCategories.forEach(function(vc) {
                    categoryOptions += `<option value="${vc.id}">${vc.name}</option>`;
                });
                categoryOptions += '<option value="other">@lang("app.other")</option>';
                
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
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="visa_refusal_category_${refusalNum}" fieldLabel="Visa Refusal Visa Category">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 visa-refusal-category-select" name="visa_refusal_category_${refusalNum}" id="visa_refusal_category_${refusalNum}">
                                        ${categoryOptions}
                                    </select>
                                    <button type="button" class="btn btn-link p-0 visa-refusal-category-clear-btn" id="visa_refusal_category_clear_${refusalNum}" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3" id="visa_refusal_category_other_wrapper_${refusalNum}" style="display: none;">
                                <x-forms.label class="mt-3" fieldId="visa_refusal_category_other_${refusalNum}" fieldLabel="Other Visa Category">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="visa_refusal_category_other_${refusalNum}" id="visa_refusal_category_other_${refusalNum}">
                            </div>
                            <div class="col-md-3">
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
                
                // Initialize Select2 for visa refusal category
                const categorySelectId = '#visa_refusal_category_' + refusalNum;
                if ($(categorySelectId).length && !$(categorySelectId).hasClass('select2-hidden-accessible')) {
                    $(categorySelectId).select2({
                        placeholder: '@lang("app.select") @lang("app.visaCategory")',
                        allowClear: false,
                        width: '100%'
                    });
                }
                attachSelect2ClearButton(categorySelectId, 'visa_refusal_category_clear_' + refusalNum);
                $(categorySelectId).off('change.visaRefusalOther').on('change.visaRefusalOther', function() {
                    const v = $(this).val();
                    if (v === 'other') {
                        $('#visa_refusal_category_other_wrapper_' + refusalNum).show();
                    } else {
                        $('#visa_refusal_category_other_wrapper_' + refusalNum).hide();
                        $('#visa_refusal_category_other_' + refusalNum).val('');
                    }
                });
                
                // Set initial value if category data exists
                if (refusalData && refusalData.category) {
                    const vc = refusalData.category;
                    const $sel = $(categorySelectId);
                    let found = false;
                    $sel.find('option').each(function() {
                        if ($(this).val() == vc || $(this).text().trim() === String(vc)) {
                            $sel.val($(this).val()).trigger('change');
                            found = true;
                            return false; // break
                        }
                    });
                    if (!found) {
                        $sel.val('other').trigger('change');
                        $('#visa_refusal_category_other_' + refusalNum).val(vc);
                    }
                }
                
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
                    $('#pr_points_system_awareness, #pr_skill_assessment_status, #pr_language_test_status').val('').selectpicker('refresh');
                    $('#pr_preferred_country').val([]).trigger('change');
                    $('#pr_preferred_country_other').val('');
                    $('#pr_preferred_country_other_wrapper').hide();
                    $('#pr_pathway').val(null).trigger('change');
                    $('#pr_pathway_other').val('');
                    $('#pr_pathway_other_wrapper').hide();
                    $('#pr_occupation_category').val(null).trigger('change');
                    $('#pr_occupation_category_other').val('');
                    $('#pr_occupation_category_other_wrapper').hide();
                    $('#pr_occupation_category_clear').hide();
                    $('#pr_preferred_country_clear').hide();
                    $('#pr_pathway_clear').hide();
                    $('#pr_assessment_letter_file').val('');
                    // Remove hidden file input if exists
                    $('#pr_assessment_letter_file_hidden').remove();
                    // Remove file display if exists
                    $('#pr_assessment_letter_file').next('.file-name-display').remove();
                }
                
                // Clear Visit Visa fields (if not selected)
                if (sectionId !== 'visit') {
                    $('#visit_purpose, #visit_duration_of_stay, #visit_sponsor_type, #visit_invitation_letter').val('').selectpicker('refresh');
                    $('#visit_visiting_country').val(null).trigger('change');
                    $('#visit_visiting_country_other').val('');
                    $('#visit_visiting_country_other_wrapper').hide();
                    $('#visit_visiting_country_clear').hide();
                }
                
                // Clear Work Permit fields (if not selected)
                if (sectionId !== 'work') {
                    $('#work_preferred_work_country').val(null).trigger('change');
                    $('#work_preferred_work_country_other').val('');
                    $('#work_preferred_work_country_other_wrapper').hide();
                    $('#work_preferred_work_country_clear').hide();
                    $('#work_industry_sector').val(null).trigger('change');
                    $('#work_industry_sector_other').val('');
                    $('#work_industry_sector_other_wrapper').hide();
                    $('#work_industry_sector_clear').hide();
                    if (typeof filterWorkSectorsByIndustry === 'function') filterWorkSectorsByIndustry();
                    $('#work_sector_other').val('');
                    $('#work_sector_other_wrapper').hide();
                    $('#work_sector_clear').hide();
                    $('#work_designation').val(null).trigger('change');
                    $('#work_designation_clear').hide();
                    $('#work_job_offer_status, #work_employer_type, #work_language_requirement').val('').selectpicker('refresh');
                }
                
                // Clear Student Visa fields (if not selected)
                if (sectionId !== 'student') {
                    $('#student_education_level_applying_for, #student_intake, #student_intake_year, #student_budget_range, #student_english_test_status').val('').selectpicker('refresh');
                    $('#student_preferred_study_country').val([]).trigger('change');
                    $('#student_preferred_study_country_other').val('');
                    $('#student_preferred_study_country_other_wrapper').hide();
                    $('#student_preferred_study_country_clear').hide();
                    $('#student_field_of_study').val(null).trigger('change');
                    $('#student_field_of_study_other').val('');
                    $('#student_field_of_study_other_wrapper').hide();
                    $('#student_field_of_study_clear').hide();
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
                
                // Load subclasses for the selected visa type (only if not loading form data; PR, Visit, Work and Student have no subclass field)
                if (visaTypeId && !isNaN(visaTypeId) && !isLoadingFormData && sectionId !== 'pr' && sectionId !== 'visit' && sectionId !== 'work' && sectionId !== 'student') {
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
            
            // Occupation Category: show/hide Other Occupation Category field when "Other" is selected; show/hide clear button
            $(document).on('change', '#pr_occupation_category', function() {
                const val = $(this).val();
                if (val === 'Other') {
                    $('#pr_occupation_category_other_wrapper').show();
                } else {
                    $('#pr_occupation_category_other_wrapper').hide();
                    $('#pr_occupation_category_other').val('');
                }
                $('#pr_occupation_category_clear').toggle(!!val);
            });
            // Clear button for Occupation Category is handled by attachSelect2ClearButton; change handler above updates Other wrapper and clear visibility
            
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
            
            // File inputs removed - no handlers needed

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
            
            // Copy home address to a relative contact (used when "Contact Address Same As Home Address" is checked)
            function copyHomeToRelativeContact(num) {
                if (!$('#relative_contact_address_' + num).length) return;
                $('#relative_contact_address_' + num).val($('#home_address').val());
                $('#relative_zip_code_' + num).val($('#home_pin_code').val());
                const homeCountryVal = $('#country_of_origin').val();
                const homeStateVal = $('#home_state').val();
                const homeCityVal = $('#home_city').val();
                $('#relative_country_' + num).val(homeCountryVal).trigger('change');
                if (homeCountryVal) {
                    loadStatesForRelativeRow(homeCountryVal, num).then(function () {
                        $('#relative_state_' + num).val(homeStateVal).trigger('change');
                        if (homeStateVal) {
                            loadCities(homeStateVal, $('#relative_city_' + num)).then(function () {
                                $('#relative_city_' + num).val(homeCityVal).trigger('change');
                            });
                        } else {
                            $('#relative_city_' + num).val(null).trigger('change');
                        }
                    });
                } else {
                    $('#relative_state_' + num).val(null).trigger('change');
                    $('#relative_city_' + num).val(null).trigger('change');
                }
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
                const country = contactData && contactData.relative_country ? contactData.relative_country : '';
                const state = contactData && contactData.relative_state ? contactData.relative_state : '';
                const city = contactData && contactData.relative_city ? contactData.relative_city : '';
                const zipCode = contactData && contactData.relative_zip_code ? contactData.relative_zip_code : '';
                const email = contactData && contactData.relative_email_address ? contactData.relative_email_address : '';
                const phone = contactData && contactData.relative_phone_number ? contactData.relative_phone_number : '';
                const whatsappEnable = contactData && (contactData.relative_whatsapp_enable === true || contactData.relative_whatsapp_enable === '1' || contactData.relative_whatsapp_enable === 1);
                const sameAsHome = contactData && (contactData.relative_contact_same_as_home === true || contactData.relative_contact_same_as_home === '1' || contactData.relative_contact_same_as_home === 1);
                
                // Resolve organization name to dropdown value: id if matches master, or "other" with text
                let orgTypeSelected = '';
                let orgNameOther = '';
                if (orgName) {
                    const matched = organizationTypes.find(function(ot) { return ot.name === orgName || String(ot.id) === String(orgName); });
                    if (matched) {
                        orgTypeSelected = String(matched.id);
                    } else {
                        orgTypeSelected = 'other';
                        orgNameOther = orgName;
                    }
                }
                
                // Build organization type options from master + Other
                let orgTypeOptions = '<option value="">@lang("app.select")</option>';
                organizationTypes.forEach(function(ot) {
                    const sel = orgTypeSelected === String(ot.id) ? ' selected' : '';
                    orgTypeOptions += `<option value="${ot.id}"${sel}>${ot.name}</option>`;
                });
                orgTypeOptions += '<option value="other"' + (orgTypeSelected === 'other' ? ' selected' : '') + '>@lang("app.other")</option>';
                
                // Resolve relationship to dropdown value: id if matches master, or "other" with text
                let relationshipSelected = '';
                let relationshipOther = '';
                if (relationship) {
                    const relMatched = relationshipsMaster.find(function(r) { return r.name === relationship || String(r.id) === String(relationship); });
                    if (relMatched) {
                        relationshipSelected = String(relMatched.id);
                    } else {
                        relationshipSelected = 'other';
                        relationshipOther = relationship;
                    }
                }
                // Build relationship options from master + Other
                let relationshipOptions = '<option value="">@lang("app.select")</option>';
                relationshipsMaster.forEach(function(r) {
                    const sel = relationshipSelected === String(r.id) ? ' selected' : '';
                    relationshipOptions += `<option value="${r.id}"${sel}>${r.name}</option>`;
                });
                relationshipOptions += '<option value="other"' + (relationshipSelected === 'other' ? ' selected' : '') + '>@lang("app.other")</option>';
                
                // Build country options from master (same as Personal Details)
                let countryOptions = '<option value="">Select country</option>';
                countryMasters.forEach(function (c) {
                    const sel = (country && (String(c.id) === String(country) || c.name === country)) ? ' selected' : '';
                    countryOptions += `<option value="${c.id}"${sel}>${c.name}</option>`;
                });
                // State options loaded via API when country is selected (start empty)
                let stateOptions = '<option value="">@lang("app.select")</option>';

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
                                <input type="text" class="form-control height-35 f-14" name="relative_surname_${contactNum}" id="relative_surname_${contactNum}" value="${surname}" pattern="[A-Za-z]*" title="Alphabets only. Either leave empty or enter at least 3 characters" onkeypress="return /[A-Za-z]/.test(event.key)">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_given_name_${contactNum}" fieldLabel="Given Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_given_name_${contactNum}" id="relative_given_name_${contactNum}" value="${givenName}" pattern="[A-Za-z\s]*" title="Alphabets only" onkeypress="return /[A-Za-z\s]/.test(event.key)">
                            </div>
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="relative_organization_type_${contactNum}" fieldLabel="Organization Name">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 relative-organization-type-select" name="relative_organization_type_${contactNum}" id="relative_organization_type_${contactNum}">
                                        ${orgTypeOptions}
                                    </select>
                                    <button type="button" class="btn btn-link p-0 relative-organization-type-clear-btn" id="relative_organization_type_clear_${contactNum}" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3" id="relative_organization_name_other_wrapper_${contactNum}" style="display: ${orgTypeSelected === 'other' ? 'block' : 'none'};">
                                <x-forms.label class="mt-3" fieldId="relative_organization_name_other_${contactNum}" fieldLabel="Other Organization Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_organization_name_other_${contactNum}" id="relative_organization_name_other_${contactNum}" value="${orgNameOther}">
                            </div>
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="relative_relationship_${contactNum}" fieldLabel="Relationship To You">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 relative-relationship-select" name="relative_relationship_${contactNum}" id="relative_relationship_${contactNum}">
                                        ${relationshipOptions}
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="relative_relationship_clear_${contactNum}" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="col-md-3" id="relative_relationship_other_wrapper_${contactNum}" style="display: ${relationshipSelected === 'other' ? 'block' : 'none'};">
                                <x-forms.label class="mt-3" fieldId="relative_relationship_other_${contactNum}" fieldLabel="Other Relationship To You">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_relationship_other_${contactNum}" id="relative_relationship_other_${contactNum}" value="${relationshipOther}">
                            </div>
                            <div class="col-md-12 mt-3 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input relative-contact-same-as-home-cb mr-2" type="checkbox" name="relative_contact_same_as_home_${contactNum}" id="relative_contact_same_as_home_${contactNum}" value="1" data-contact-num="${contactNum}" ${sameAsHome ? 'checked' : ''}> <label class="pl-2 mb-0" for="relative_contact_same_as_home_${contactNum}">Contact Address Same As Client Address</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <x-forms.label class="mt-3" fieldId="relative_contact_address_${contactNum}" fieldLabel="Contact Address">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_contact_address_${contactNum}" id="relative_contact_address_${contactNum}" value="${address}">
                            </div>
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="relative_country_${contactNum}" fieldLabel="Country">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 relative-country-select" name="relative_country_${contactNum}" id="relative_country_${contactNum}">
                                        ${countryOptions}
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="relative_country_clear_${contactNum}" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="relative_state_${contactNum}" fieldLabel="State">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 relative-state-select" name="relative_state_${contactNum}" id="relative_state_${contactNum}">
                                        ${stateOptions}
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="relative_state_clear_${contactNum}" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="relative_city_${contactNum}" fieldLabel="City">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 relative-city-select" name="relative_city_${contactNum}" id="relative_city_${contactNum}">
                                        <option value="">@lang('app.select')</option>
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="relative_city_clear_${contactNum}" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_zip_code_${contactNum}" fieldLabel="Zip Code">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="relative_zip_code_${contactNum}" id="relative_zip_code_${contactNum}" maxlength="6" pattern="[0-9]{6}" title="Please enter exactly 6 digits" inputmode="numeric" onkeypress="return /[0-9]/.test(event.key)" value="${zipCode}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_email_address_${contactNum}" fieldLabel="Email Address">
                                </x-forms.label>
                                <input type="email" class="form-control height-35 f-14" name="relative_email_address_${contactNum}" id="relative_email_address_${contactNum}" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value="${email}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_phone_number_${contactNum}" fieldLabel="Phone Number">
                                </x-forms.label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text height-35 f-14 bg-light">+91</span>
                                    </div>
                                    <input type="number" max="9999999999" class="form-control height-35 f-14" name="relative_phone_number_${contactNum}" id="relative_phone_number_${contactNum}" oninput="if(this.value.length > 10) this.value = this.value.slice(0,10);" value="${phone}" placeholder="10 digits">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_whatsapp_enable_${contactNum}" fieldLabel="WhatsApp Enable">
                                </x-forms.label>
                                <div class="d-flex align-items-center height-35 f-14">
                                    <div class="form-check mb-0 pl-0 d-flex align-items-center flex-row-reverse justify-content-start">
                                        <input type="checkbox" class="form-check-input ml-2" name="relative_whatsapp_enable_${contactNum}" id="relative_whatsapp_enable_${contactNum}" value="1" ${whatsappEnable ? 'checked' : ''}>
                                        <label class="form-check-label mb-0 f-14" for="relative_whatsapp_enable_${contactNum}">Yes</label>
                                    </div>
                                </div>
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

                // Init Select2 for country/state/city in the new row (same order as Personal Details)
                const countrySel = '#relative_country_' + contactNum;
                const stateSel = '#relative_state_' + contactNum;
                const citySel = '#relative_city_' + contactNum;
                initSelect2IfNeeded(countrySel, 'Select country');
                initSelect2IfNeeded(stateSel, '@lang("app.select") @lang("app.state")');
                initSelect2CityWithTags(citySel, '@lang("app.select") @lang("app.city")');
                attachSelect2ClearButton(countrySel, 'relative_country_clear_' + contactNum);
                attachSelect2ClearButton(stateSel, 'relative_state_clear_' + contactNum);
                attachSelect2ClearButton(citySel, 'relative_city_clear_' + contactNum);
                resetStateSelect($(stateSel));
                resetCitySelect($(citySel));

                $(document).off('change.relativeCountry' + contactNum, countrySel).on('change.relativeCountry' + contactNum, countrySel, function () {
                    loadStatesForRelativeRow($(this).val(), contactNum);
                });
                $(document).off('change.relativeState' + contactNum, stateSel).on('change.relativeState' + contactNum, stateSel, function () {
                    loadCities($(this).val(), $(citySel));
                });

                // If editing existing contact data (saved as text), set country then load states then set state then load cities then set city
                if (contactData) {
                    setSelectBySavedText($(countrySel), contactData.relative_country || '');
                    loadStatesForRelativeRow($(countrySel).val(), contactNum).then(function () {
                        setSelectBySavedText($(stateSel), contactData.relative_state || '');
                        return loadCities($(stateSel).val(), $(citySel));
                    }).then(function () {
                        setSelectBySavedTextOrTag($(citySel), contactData.relative_city || '');
                    });
                }

                // Init Select2 for Organization Name (Organization Type) dropdown
                const orgTypeSel = '#relative_organization_type_' + contactNum;
                initSelect2IfNeeded(orgTypeSel, '@lang("app.select") Organization Name');
                attachSelect2ClearButton(orgTypeSel, 'relative_organization_type_clear_' + contactNum);
                $(orgTypeSel).off('change.relativeOrgType' + contactNum).on('change.relativeOrgType' + contactNum, function() {
                    const v = $(this).val();
                    if (v === 'other') {
                        $('#relative_organization_name_other_wrapper_' + contactNum).show();
                    } else {
                        $('#relative_organization_name_other_wrapper_' + contactNum).hide();
                        $('#relative_organization_name_other_' + contactNum).val('');
                    }
                });

                // Init Select2 for Relationship To You dropdown
                const relationshipSel = '#relative_relationship_' + contactNum;
                initSelect2IfNeeded(relationshipSel, '@lang("app.select") Relationship To You');
                attachSelect2ClearButton(relationshipSel, 'relative_relationship_clear_' + contactNum);
                $(relationshipSel).off('change.relativeRelationship' + contactNum).on('change.relativeRelationship' + contactNum, function() {
                    const v = $(this).val();
                    if (v === 'other') {
                        $('#relative_relationship_other_wrapper_' + contactNum).show();
                    } else {
                        $('#relative_relationship_other_wrapper_' + contactNum).hide();
                        $('#relative_relationship_other_' + contactNum).val('');
                    }
                });

                // Contact Address Same As Home Address checkbox
                $('#relative_contact_same_as_home_' + contactNum).off('change.relativeSameAsHome').on('change.relativeSameAsHome', function() {
                    if ($(this).is(':checked')) {
                        copyHomeToRelativeContact(contactNum);
                        $('#relative_contact_address_' + contactNum + ', #relative_country_' + contactNum + ', #relative_state_' + contactNum + ', #relative_city_' + contactNum + ', #relative_zip_code_' + contactNum).prop('disabled', true);
                    } else {
                        $('#relative_contact_address_' + contactNum + ', #relative_country_' + contactNum + ', #relative_state_' + contactNum + ', #relative_city_' + contactNum + ', #relative_zip_code_' + contactNum).prop('disabled', false);
                    }
                });
                // If editing and same-as-home was checked, trigger change so copy + disable runs
                if (contactData && (contactData.relative_contact_same_as_home === true || contactData.relative_contact_same_as_home === '1' || contactData.relative_contact_same_as_home === 1)) {
                    $('#relative_contact_same_as_home_' + contactNum).prop('checked', true).trigger('change');
                }

                // Update remove buttons visibility and contact row numbers
                setTimeout(function () {
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
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="child_city_of_birth_${childNum}" fieldLabel="City of Birth">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 child-city-of-birth-select" name="child_city_of_birth_${childNum}" id="child_city_of_birth_${childNum}">
                                        <option value="">@lang('app.select')</option>
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="child_city_of_birth_clear_${childNum}" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
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
                                <x-forms.label class="mt-3" fieldId="child_have_passport_${childNum}" fieldLabel="Have Passport">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="child_have_passport_${childNum}" id="child_have_passport_${childNum}">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Yes" ${childHavePassport === 'Yes' ? 'selected' : ''}>Yes</option>
                                    <option value="No" ${childHavePassport === 'No' ? 'selected' : ''}>No</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="child_passport_file_container_${childNum}" ${showPassportContainer}>
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
                
                // Init city-of-birth select (city master + type custom) same as other city fields
                const childCitySel = '#child_city_of_birth_' + childNum;
                initSelect2CityWithTags(childCitySel, '@lang("app.select") City of Birth');
                attachSelect2ClearButton(childCitySel, 'child_city_of_birth_clear_' + childNum);
                var defaultCountryId = (countryMasters && countryMasters.length) ? (countryMasters.find(function(c){ return (c.name || '').toLowerCase() === 'india'; }) || countryMasters[0]).id : null;
                if (defaultCountryId) {
                    loadCitiesByCountry(defaultCountryId, $(childCitySel)).then(function () {
                        if (childData && childData.child_city_of_birth) {
                            setSelectBySavedTextOrTag($(childCitySel), childData.child_city_of_birth);
                        }
                    });
                } else if (childData && childData.child_city_of_birth) {
                    setSelectBySavedTextOrTag($(childCitySel), childData.child_city_of_birth);
                }
                
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
                const employmentType = jobData && jobData.job_employment_type ? jobData.job_employment_type : '';
                const designation = jobData && jobData.job_designation ? jobData.job_designation : '';
                const companyName = jobData && jobData.job_company_name ? jobData.job_company_name : '';
                const industry = jobData && jobData.job_industry ? jobData.job_industry : '';
                const sector = jobData && jobData.job_sector ? jobData.job_sector : '';
                const salary = jobData && jobData.job_salary ? jobData.job_salary : '';
                const isCurrentJob = !!(jobData && (jobData.job_current_job === true || jobData.job_current_job === '1' || jobData.job_current_job === 1));

                // Resolve industry/sector for "Other": if value does not match master, use "other" + other text field
                let jobIndustrySelected = '';
                let jobIndustryOther = '';
                if (industry) {
                    const industryMatch = industryMasters.find(function (i) { return String(i.id) === String(industry) || (i.name && i.name.trim() === String(industry).trim()); });
                    if (industryMatch) {
                        jobIndustrySelected = industryMatch.id;
                    } else {
                        jobIndustrySelected = 'other';
                        jobIndustryOther = industry;
                    }
                }
                let jobSectorSelected = '';
                let jobSectorOther = '';
                if (sector) {
                    const sectorMatch = sectorsMaster.find(function (s) { return String(s.id) === String(sector) || (s.name && s.name.trim() === String(sector).trim()); });
                    if (sectorMatch) {
                        jobSectorSelected = sectorMatch.id;
                    } else {
                        jobSectorSelected = 'other';
                        jobSectorOther = sector;
                    }
                }

                // Build country options from master
                let countryOptions = '<option value="">@lang("app.select")</option>';
                countryMasters.forEach(function (c) {
                    countryOptions += `<option value="${c.id}">${c.name}</option>`;
                });

                const employmentTypeOptions = [
                    'Full Time', 'Part Time', 'Contract', 'Internship', 'Apprenticeship', 'Self Employed', 'Freelancer'
                ];
                let employmentTypeSelect = '<option value="">@lang("app.select")</option>';
                employmentTypeOptions.forEach(function (opt) {
                    const sel = (employmentType === opt) ? ' selected' : '';
                    employmentTypeSelect += `<option value="${opt}"${sel}>${opt}</option>`;
                });

                // Build designation options from master (same pattern as Industry); allow custom text when editing
                let jobDesignationSelected = '';
                if (designation) {
                    const designationMatch = designationsMaster.find(function (d) { return String(d.id) === String(designation) || (d.name && d.name.trim() === String(designation).trim()); });
                    if (designationMatch) {
                        jobDesignationSelected = designationMatch.id;
                    } else {
                        jobDesignationSelected = designation; // custom text
                    }
                }
                let designationSelect = '<option value="">@lang("app.select")</option>';
                designationsMaster.forEach(function (d) {
                    const sel = (jobDesignationSelected && String(d.id) === String(jobDesignationSelected)) ? ' selected' : '';
                    designationSelect += '<option value="' + d.id + '"' + sel + '>' + (d.name || '') + '</option>';
                });
                if (designation && jobDesignationSelected && designationsMaster.findIndex(function (d) { return String(d.id) === String(jobDesignationSelected); }) === -1) {
                    const esc = function (s) { return String(s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); };
                    designationSelect += '<option value="' + esc(jobDesignationSelected) + '" selected>' + esc(jobDesignationSelected) + '</option>';
                }

                // Build industry options from master (same pattern as Country); "Other" is appended below
                let industryOptions = '<option value="">@lang("app.select")</option>';
                industryMasters.forEach(function (i) {
                    const sel = (jobIndustrySelected && String(i.id) === String(jobIndustrySelected)) ? ' selected' : '';
                    industryOptions += `<option value="${i.id}"${sel}>${i.name}</option>`;
                });
                // Sector: show ALL sectors initially; when industry is selected we filter via filterSectorsByIndustry(); include "Other" option
                let sectorOptions = '<option value="">@lang("app.select")</option>';
                sectorsMaster.forEach(function (s) {
                    const sel = (jobSectorSelected && String(s.id) === String(jobSectorSelected)) ? ' selected' : '';
                    sectorOptions += '<option value="' + s.id + '"' + sel + '>' + (s.name || '') + '</option>';
                });
                sectorOptions += '<option value="other"' + (jobSectorSelected === 'other' ? ' selected' : '') + '>@lang("app.other")</option>';

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
                        <div class="border-bottom mb-3"></div>
                        <div class="form-check mb-3">
                            <input class="form-check-input job-current-job-cb" type="checkbox" name="job_current_job_${jobNum}" id="job_current_job_${jobNum}" value="1" ${isCurrentJob ? 'checked' : ''}>
                            <label class="form-check-label pl-3 f-14" for="job_current_job_${jobNum}">Current job</label>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_duration_from_${jobNum}" fieldLabel="Duration - From">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="job_duration_from_${jobNum}" id="job_duration_from_${jobNum}" max="{{ date('Y-m-d', strtotime('-1 day')) }}" value="${durationFrom}">
                            </div>
                            <div class="col-md-3 job-duration-to-col" id="job_duration_to_col_${jobNum}" data-job-num="${jobNum}" style="${isCurrentJob ? 'display:none' : ''}">
                                <x-forms.label class="mt-3" fieldId="job_duration_to_${jobNum}" fieldLabel="Duration - To">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="job_duration_to_${jobNum}" id="job_duration_to_${jobNum}" max="{{ date('Y-m-d', strtotime('-1 day')) }}" value="${durationTo}">
                            </div>
                            <div class="col-md-3 job-experience-col" id="job_experience_col_${jobNum}" data-job-num="${jobNum}" style="${isCurrentJob ? 'display:none' : ''}">
                                <x-forms.label class="mt-3" fieldId="job_experience_${jobNum}" fieldLabel="Job Experience">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14 bg-light" name="job_experience_${jobNum}" id="job_experience_${jobNum}" readonly placeholder="Auto calculated">
                            </div>
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="job_country_${jobNum}" fieldLabel="Country">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 job-country-select" name="job_country_${jobNum}" id="job_country_${jobNum}">
                                        ${countryOptions}
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="job_country_clear_${jobNum}" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_employment_type_${jobNum}" fieldLabel="Employment Type">
                                </x-forms.label>
                                <select class="form-control select-picker height-35 f-14" name="job_employment_type_${jobNum}" id="job_employment_type_${jobNum}">
                                    ${employmentTypeSelect}
                                </select>
                            </div>
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="job_designation_${jobNum}" fieldLabel="Designation">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 job-designation-select" name="job_designation_${jobNum}" id="job_designation_${jobNum}">
                                        ${designationSelect}
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="job_designation_clear_${jobNum}" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_company_name_${jobNum}" fieldLabel="Company Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="job_company_name_${jobNum}" id="job_company_name_${jobNum}" value="${companyName}">
                            </div>
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="job_industry_${jobNum}" fieldLabel="Industry">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 job-industry-select" name="job_industry_${jobNum}" id="job_industry_${jobNum}">
                                        ${industryOptions} <option value="other" ${jobIndustrySelected === 'other' ? ' selected' : ''}>@lang('app.other')</option>
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="job_industry_clear_${jobNum}" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="col-md-3" id="job_industry_other_wrapper_${jobNum}" style="display: ${jobIndustrySelected === 'other' ? 'block' : 'none'};">
                                <x-forms.label class="mt-3" fieldId="job_industry_other_${jobNum}" fieldLabel="Other Industry"></x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="job_industry_other_${jobNum}" id="job_industry_other_${jobNum}" value="${jobIndustryOther}">
                            </div>
                            <div class="col-md-3" style="position: relative;">
                                <x-forms.label class="mt-3" fieldId="job_sector_${jobNum}" fieldLabel="Sector">
                                </x-forms.label>
                                <div style="position: relative;">
                                    <select class="form-control height-35 f-14 job-sector-select" name="job_sector_${jobNum}" id="job_sector_${jobNum}">
                                        ${sectorOptions} <option value="other" ${jobSectorSelected === 'other' ? ' selected' : ''}>@lang('app.other')</option>
                                    </select>
                                    <button type="button" class="btn btn-link p-0 select2-clear-btn" id="job_sector_clear_${jobNum}" style="display: none; position: absolute; right: -25px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 18px; line-height: 1; min-width: 20px; z-index: 10;" title="Clear"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="col-md-3" id="job_sector_other_wrapper_${jobNum}" style="display: ${jobSectorSelected === 'other' ? 'block' : 'none'};">
                                <x-forms.label class="mt-3" fieldId="job_sector_other_${jobNum}" fieldLabel="Other Sector"></x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="job_sector_other_${jobNum}" id="job_sector_other_${jobNum}" value="${jobSectorOther}">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_salary_${jobNum}" fieldLabel="Salary">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="job_salary_${jobNum}" id="job_salary_${jobNum}" value="${salary}">
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

                // Init Select2 for job country
                const jobCountrySel = '#job_country_' + jobNum;
                initSelect2IfNeeded(jobCountrySel, '@lang("app.select") Country');
                attachSelect2ClearButton(jobCountrySel, 'job_country_clear_' + jobNum);

                // Init Select2 for job industry & sector (binding like PR Preferred Country -> Preferred State)
                initSelect2IfNeeded('#job_industry_' + jobNum, '@lang("app.select") @lang("app.menu.industry")');
                initSelect2IfNeeded('#job_sector_' + jobNum, '@lang("app.select") @lang("app.menu.sector")');
                attachSelect2ClearButton('#job_industry_' + jobNum, 'job_industry_clear_' + jobNum);
                attachSelect2ClearButton('#job_sector_' + jobNum, 'job_sector_clear_' + jobNum);

                // When industry changes: show all sectors if no industry, or only that industry's sectors; show/hide Other Industry text field
                $('#job_industry_' + jobNum).off('change.jobIndustry').on('change.jobIndustry', function() {
                    filterSectorsByIndustry(jobNum);
                    const isOther = $(this).val() === 'other';
                    if (isOther) {
                        $('#job_industry_other_wrapper_' + jobNum).show();
                    } else {
                        $('#job_industry_other_wrapper_' + jobNum).hide();
                        $('#job_industry_other_' + jobNum).val('');
                    }
                });
                // When sector changes: show/hide Other Sector text field
                $('#job_sector_' + jobNum).off('change.jobSector').on('change.jobSector', function() {
                    const isOther = $(this).val() === 'other';
                    if (isOther) {
                        $('#job_sector_other_wrapper_' + jobNum).show();
                    } else {
                        $('#job_sector_other_wrapper_' + jobNum).hide();
                        $('#job_sector_other_' + jobNum).val('');
                    }
                });
                // Initial visibility for Other wrappers (when editing with "other" pre-selected)
                if ($('#job_industry_' + jobNum).val() === 'other') $('#job_industry_other_wrapper_' + jobNum).show();
                if ($('#job_sector_' + jobNum).val() === 'other') $('#job_sector_other_wrapper_' + jobNum).show();

                // Current job checkbox: only one job can be current; when checked, uncheck all other rows
                $('#job_current_job_' + jobNum).off('change.currentJob').on('change.currentJob', function() {
                    const isChecked = $(this).is(':checked');
                    if (isChecked) {
                        $('.job-row').each(function() {
                            const otherNum = $(this).data('job-index');
                            if (otherNum != null && String(otherNum) !== String(jobNum)) {
                                $('#job_current_job_' + otherNum).prop('checked', false);
                                $('#job_duration_to_col_' + otherNum).show();
                                $('#job_experience_col_' + otherNum).show();
                                updateJobExperience(otherNum);
                            }
                        });
                    }
                    $('#job_duration_to_col_' + jobNum).toggle(!isChecked);
                    $('#job_experience_col_' + jobNum).toggle(!isChecked);
                    if (isChecked) {
                        $('#job_duration_to_' + jobNum).val('');
                        $('#job_experience_' + jobNum).val('');
                    }
                    updateJobExperience(jobNum);
                });

                // Job Experience auto-calc: recompute when Duration From or Duration To changes
                $('#job_duration_from_' + jobNum).off('change.jobExp input.jobExp').on('change.jobExp input.jobExp', function() { updateJobExperience(jobNum); });
                $('#job_duration_to_' + jobNum).off('change.jobExp input.jobExp').on('change.jobExp input.jobExp', function() { updateJobExperience(jobNum); });
                updateJobExperience(jobNum);

                // If editing: industry may be set; filter sector list to that industry then set sector (or "other" + custom text)
                if (jobData && (jobData.job_industry || jobData.job_sector)) {
                    filterSectorsByIndustry(jobNum);
                    if (jobData.job_sector) {
                        setSelectBySavedText($('#job_sector_' + jobNum), jobData.job_sector);
                        // If no option matched (custom sector), set "other" and show Other Sector text field
                        if (!$('#job_sector_' + jobNum).val()) {
                            $('#job_sector_' + jobNum).val('other').trigger('change');
                            $('#job_sector_other_' + jobNum).val(jobData.job_sector);
                            $('#job_sector_other_wrapper_' + jobNum).show();
                        }
                    }
                }

                // Init Select2 for job designation (same design as country; tags:true allows custom text)
                const jobDesignationSel = '#job_designation_' + jobNum;
                if ($(jobDesignationSel).length && !$(jobDesignationSel).hasClass('select2-hidden-accessible')) {
                    $(jobDesignationSel).select2({
                        placeholder: 'Select or type custom',
                        allowClear: false,
                        width: '100%',
                        tags: true
                    });
                }
                attachSelect2ClearButton(jobDesignationSel, 'job_designation_clear_' + jobNum);
                if (jobData && jobData.job_designation) {
                    let designationVal = jobData.job_designation;
                    const designationMatch = designationsMaster.find(function (d) { return d.name && d.name.trim() === String(jobData.job_designation).trim(); });
                    if (designationMatch) designationVal = designationMatch.id;
                    $(jobDesignationSel).val(designationVal).trigger('change');
                }

                // Init select-picker for job employment type (same design as IELTS/PTE/OET/TOEFL exam dropdowns – no search)
                const $jobRow = $('#job-row-' + jobNum);
                $jobRow.find('.select-picker').each(function() {
                    if (!$(this).data('selectpicker')) {
                        $(this).selectpicker();
                    }
                });
                if (jobData && jobData.job_employment_type) {
                    $('#job_employment_type_' + jobNum).val(jobData.job_employment_type).selectpicker('refresh');
                }

                // If editing existing job data (saved as text), set country
                if (jobData && jobData.job_country) {
                    setSelectBySavedText($(jobCountrySel), jobData.job_country);
                }
                
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

            // Property Details: block - and + from appearing; only digits and one decimal allowed
            $(document).on('input paste change', '.valuation-input', function() {
                const $el = $(this);
                let val = ($el.val() || '').toString();
                val = val.replace(/[^0-9.]/g, '');
                const parts = val.split('.');
                if (parts.length > 2) val = parts[0] + '.' + parts.slice(1).join('');
                if (val !== $el.val()) $el.val(val);
                const num = parseFloat(val);
                if (val !== '' && !isNaN(num) && num < 0) $el.val(0);
            });
            // Auto-calculate total valuation for Property Details
            $('.valuation-input').on('input', function() {
                let total = 0;
                $('.valuation-input').each(function() {
                    const val = parseFloat($(this).val()) || 0;
                    total += val;
                });
                $('#total_valuation').val(total);
                updateTotalLoanValueLevel();
                updateNetWorth();
            });

            // Loan level message: Low Loan < 30%, Medium 30–60%, High > 60% of Total Asset Valuation
            function updateTotalLoanValueLevel() {
                const $msg = $('#total_loan_value_level');
                const assets = parseFloat($('#total_valuation').val()) || 0;
                const loan = parseFloat($('#total_loan_value').val()) || 0;
                $msg.removeClass('text-info text-warning text-danger').hide();
                if (assets <= 0) {
                    $msg.hide();
                    return;
                }
                const pct = (loan / assets) * 100;
                if (loan <= 0) {
                    $msg.hide();
                    return;
                }
                let text = '';
                if (pct < 30) {
                    text = 'Low Loan';
                    $msg.addClass('text-info');
                } else if (pct <= 60) {
                    text = 'Medium Loan';
                    $msg.addClass('text-warning');
                } else {
                    text = 'High Loan';
                    $msg.addClass('text-danger');
                }
                $msg.text(text).show();
            }
            function syncTotalLoanValueCopy() {
                const v = $('#total_loan_value').val();
                $('#total_loan_value_copy').val(v === '' || v === null || v === undefined ? '' : v);
            }
            // Net Worth = Total Asset Valuation - Total Loan Value (auto-calculated)
            function updateNetWorth() {
                const assets = parseFloat($('#total_valuation').val()) || 0;
                const loan = parseFloat($('#total_loan_value').val()) || 0;
                const netWorth = assets - loan;
                $('#net_worth').val(netWorth);
            }
            $('#total_loan_value').on('input change', function() {
                const val = $(this).val();
                if (val === '' || val === null || val === undefined || String(val).trim() === '') {
                    $('#loan_years').val('');
                    $('#loan_availed_on').val('');
                }
                updateTotalLoanValueLevel();
                syncTotalLoanValueCopy();
                updateNetWorth();
            });
            // Loan Availed On: do not allow future dates
            function getTodayYMD() {
                const d = new Date();
                return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
            }
            $('#loan_availed_on').attr('max', getTodayYMD());
            $('#loan_availed_on').on('focus', function() {
                $(this).attr('max', getTodayYMD());
            });
            $('#loan_availed_on').on('input change', function() {
                const max = $(this).attr('max');
                const val = $(this).val();
                if (val && max && val > max) $(this).val('');
            });
            syncTotalLoanValueCopy();
            updateNetWorth();

            // Financial Status: block - and + from income fields; only digits and one decimal allowed (same as Property Details)
            $(document).on('input paste change', '.income-input', function() {
                const $el = $(this);
                let val = ($el.val() || '').toString();
                val = val.replace(/[^0-9.]/g, '');
                const parts = val.split('.');
                if (parts.length > 2) val = parts[0] + '.' + parts.slice(1).join('');
                if (val !== $el.val()) $el.val(val);
                const num = parseFloat(val);
                if (val !== '' && !isNaN(num) && num < 0) $el.val(0);
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
            
            // Restrict passport number to alphanumeric only (no special characters)
            $(document).on('input', '#passport_number', function() {
                var $el = $(this);
                var val = $el.val();
                var filtered = val.replace(/[^A-Za-z0-9]/g, '');
                if (val !== filtered) {
                    $el.val(filtered);
                }
            });
            // Restrict old passport number to alphanumeric only (min 8, max 9, no special characters)
            $(document).on('input', '#old_passport_number', function() {
                var $el = $(this);
                var val = $el.val();
                var filtered = val.replace(/[^A-Za-z0-9]/g, '');
                if (val !== filtered) {
                    $el.val(filtered);
                }
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
            $(document).on('input', 'input[name="home_pin_code"], input[name="mailing_pin_code"], input[name^="relative_zip_code"], input[name="relative_zip_code[]"], input[name="spouse_postal_code"]', function() {
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
            
            // File inputs removed - no file handling needed

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
                            
                            // Always default to step 1 on page load/refresh (not dynamic selection)
                            // Ensure step 1 (nav-personal-tab) is always shown
                            $('#nav-personal-tab').tab('show');
                            currentStep = 1;
                            updateFooterButtons();
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
                    // Country of origin (Nationality) - saved as text, select stores id
                    if (data.country_of_origin) {
                        setSelectBySavedText($('#country_of_origin'), data.country_of_origin);
                    } else {
                        $('#country_of_origin').val(null).trigger('change');
                    }
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
                    $('#home_pin_code').val(data.home_pin_code || '');
                    
                    // Mailing address
                    $('#mailing_address').val(data.mailing_address || '');
                    $('#mailing_pin_code').val(data.mailing_pin_code || '');

                    // Load states/cities based on selected country, then set saved state/city (saved as text)
                    const selectedCountryId = $('#country_of_origin').val();
                    loadStates(selectedCountryId).then(function () {
                        setSelectBySavedText($('#home_state'), data.home_state || '');
                        return loadCities($('#home_state').val(), $('#home_city')).then(function () {
                            setSelectBySavedTextOrTag($('#home_city'), data.home_city || '');
                        });
                    }).then(function () {
                        setSelectBySavedText($('#mailing_state'), data.mailing_state || '');
                        return loadCities($('#mailing_state').val(), $('#mailing_city')).then(function () {
                            setSelectBySavedTextOrTag($('#mailing_city'), data.mailing_city || '');
                        });
                    });
                    
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
                            var vc = data.visa_category || '';
                            var $sel = $('#visa_category');
                            if (!vc) {
                                $sel.val(null).trigger('change');
                            } else {
                                var found = false;
                                $sel.find('option').each(function() {
                                    if ($(this).val() == vc || $(this).text().trim() === String(vc)) {
                                        $sel.val($(this).val()).trigger('change');
                                        found = true;
                                        return false; // break
                                    }
                                });
                                if (!found) {
                                    $sel.val('other').trigger('change');
                                    $('#visa_category_other').val(vc);
                                }
                            }
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
                    
                    // Languages Spoken - handle comma-separated string and convert to array of IDs
                    if (data.languages_spoken) {
                        const languagesSpokenStr = data.languages_spoken;
                        let selectedLanguageIds = [];
                        
                        if (typeof languagesSpokenStr === 'string' && languagesSpokenStr.trim() !== '') {
                            // Split by comma and trim each language name
                            const languageNames = languagesSpokenStr.split(',').map(function(name) {
                                return name.trim();
                            });
                            
                            // Find matching language IDs
                            languageNames.forEach(function(langName) {
                                const lang = languages.find(function(l) {
                                    return l.name === langName || l.name.trim() === langName;
                                });
                                if (lang) {
                                    selectedLanguageIds.push(lang.id.toString());
                                }
                            });
                        } else if (Array.isArray(languagesSpokenStr)) {
                            // If it's already an array (backward compatibility)
                            selectedLanguageIds = languagesSpokenStr.map(function(id) {
                                return id.toString();
                            });
                        }
                        
                        if (selectedLanguageIds.length > 0) {
                            $('#languages_spoken').val(selectedLanguageIds).trigger('change');
                        }
                    }
                    
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
                                var prCountries = Array.isArray(data.pr_preferred_country) ? data.pr_preferred_country : [data.pr_preferred_country];
                                $('#pr_preferred_country').val(prCountries).trigger('change');
                                if (prCountries.indexOf('Other') !== -1 && data.pr_preferred_country_other) {
                                    $('#pr_preferred_country_other').val(data.pr_preferred_country_other);
                                }
                            } else {
                                $('#pr_preferred_country').val([]).trigger('change');
                            }
                            if (data.pr_pathway) {
                                $('#pr_pathway').val(data.pr_pathway).trigger('change');
                                if (data.pr_pathway === 'Other' && data.pr_pathway_other) {
                                    $('#pr_pathway_other').val(data.pr_pathway_other);
                                }
                            } else {
                                $('#pr_pathway').val(null).trigger('change');
                            }
                            if (data.pr_occupation_category) {
                                $('#pr_occupation_category').val(data.pr_occupation_category).trigger('change');
                                if (data.pr_occupation_category === 'Other' && data.pr_occupation_category_other) {
                                    $('#pr_occupation_category_other').val(data.pr_occupation_category_other);
                                }
                            } else {
                                $('#pr_occupation_category').val(null).trigger('change');
                            }
                            if (data.pr_points_system_awareness) {
                                $('#pr_points_system_awareness').val(data.pr_points_system_awareness).selectpicker('refresh');
                            }
                            if (data.pr_skill_assessment_status) {
                                $('#pr_skill_assessment_status').val(data.pr_skill_assessment_status).selectpicker('refresh');
                            }
                            if (data.pr_language_test_status) {
                                $('#pr_language_test_status').val(data.pr_language_test_status).selectpicker('refresh');
                            }
                            
                            // Visit Section fields
                            if (data.visit_visiting_country) {
                                $('#visit_visiting_country').val(data.visit_visiting_country).trigger('change');
                                if (data.visit_visiting_country === 'Other' && data.visit_visiting_country_other) {
                                    $('#visit_visiting_country_other').val(data.visit_visiting_country_other);
                                }
                            }
                            if (data.visit_purpose) {
                                $('#visit_purpose').val(data.visit_purpose).selectpicker('refresh');
                            }
                            if (data.visit_duration_of_stay) {
                                $('#visit_duration_of_stay').val(data.visit_duration_of_stay).selectpicker('refresh');
                            }
                            if (data.visit_sponsor_type) {
                                $('#visit_sponsor_type').val(data.visit_sponsor_type).selectpicker('refresh');
                            }
                            if (data.visit_invitation_letter) {
                                $('#visit_invitation_letter').val(data.visit_invitation_letter).selectpicker('refresh');
                            }
                            
                            // Work Section fields
                            if (data.work_preferred_work_country) {
                                $('#work_preferred_work_country').val(data.work_preferred_work_country).trigger('change');
                                if (data.work_preferred_work_country === 'Other' && data.work_preferred_work_country_other) {
                                    $('#work_preferred_work_country_other').val(data.work_preferred_work_country_other);
                                }
                            }
                            if (data.work_industry_sector) {
                                $('#work_industry_sector').val(data.work_industry_sector).trigger('change');
                                if (data.work_industry_sector === 'other' && data.work_industry_sector_other) {
                                    $('#work_industry_sector_other').val(data.work_industry_sector_other);
                                }
                            }
                            if (data.work_sector) {
                                $('#work_sector').val(data.work_sector).trigger('change');
                                if (data.work_sector === 'other' && data.work_sector_other) {
                                    $('#work_sector_other').val(data.work_sector_other);
                                }
                            }
                            if (data.work_designation) {
                                let workDesignationVal = data.work_designation;
                                const workDesignationMatch = designationsMaster.find(function (d) { return d.name && d.name.trim() === String(data.work_designation).trim(); });
                                if (workDesignationMatch) workDesignationVal = workDesignationMatch.id;
                                $('#work_designation').val(workDesignationVal).trigger('change');
                            }
                            if (data.work_job_offer_status) {
                                $('#work_job_offer_status').val(data.work_job_offer_status).selectpicker('refresh');
                            }
                            if (data.work_employer_type) {
                                $('#work_employer_type').val(data.work_employer_type).selectpicker('refresh');
                            }
                            if (data.work_language_requirement) {
                                $('#work_language_requirement').val(data.work_language_requirement).selectpicker('refresh');
                            }
                            
                            // Student Section fields
                            if (data.student_preferred_study_country) {
                                var studyCountries = Array.isArray(data.student_preferred_study_country) ? data.student_preferred_study_country : [data.student_preferred_study_country];
                                $('#student_preferred_study_country').val(studyCountries).trigger('change');
                                if (studyCountries.indexOf('Other') !== -1 && data.student_preferred_study_country_other) {
                                    $('#student_preferred_study_country_other').val(data.student_preferred_study_country_other);
                                }
                            }
                            if (data.student_education_level_applying_for) {
                                $('#student_education_level_applying_for').val(data.student_education_level_applying_for).selectpicker('refresh');
                            }
                            if (data.student_field_of_study) {
                                $('#student_field_of_study').val(data.student_field_of_study).trigger('change');
                                if (data.student_field_of_study === 'Other' && data.student_field_of_study_other) {
                                    $('#student_field_of_study_other').val(data.student_field_of_study_other);
                                }
                            }
                            if (data.student_intake) {
                                $('#student_intake').val(data.student_intake).selectpicker('refresh');
                            }
                            if (data.student_intake_year) {
                                $('#student_intake_year').val(data.student_intake_year).selectpicker('refresh');
                            }
                            if (data.student_budget_range) {
                                $('#student_budget_range').val(data.student_budget_range).selectpicker('refresh');
                            }
                            if (data.student_english_test_status) {
                                $('#student_english_test_status').val(data.student_english_test_status).selectpicker('refresh');
                            }
                        }, 300);
                    }
                }
                
                // Populate Steps 3-9 data
                for (let stepNum = 3; stepNum <= 9; stepNum++) {
                    const stepKey = 'step_' + stepNum + '_data';
                    if (stepData[stepKey] && typeof stepData[stepKey] === 'object') {
                        const stepDataObj = stepData[stepKey];
                        
                        // Step 3 - passport: City Where Issued depends on Issuing Country
                        if (stepNum === 3) {
                            setSelectBySavedText($('#issuing_country'), stepDataObj.issuing_country || '');
                            loadCitiesByCountry($('#issuing_country').val(), $('#city_where_issued')).then(function () {
                                setSelectBySavedTextOrTag($('#city_where_issued'), stepDataObj.city_where_issued || '');
                            });
                        }

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

                        // Step 5 - spouse city/state/city_of_birth (saved as text) -> set state then load cities then set city; city of birth by country
                        if (stepNum === 5) {
                            setSelectBySavedText($('#spouse_country'), stepDataObj.spouse_country || '');
                            var spouseCountryId = $('#spouse_country').val();
                            loadSpouseStates(spouseCountryId).then(function () {
                                setSelectBySavedText($('#spouse_state'), stepDataObj.spouse_state || '');
                                return loadCities($('#spouse_state').val(), $('#spouse_city'));
                            }).then(function () {
                                setSelectBySavedTextOrTag($('#spouse_city'), stepDataObj.spouse_city || '');
                            });
                            loadCitiesByCountry(spouseCountryId, $('#spouse_city_of_birth')).then(function () {
                                setSelectBySavedTextOrTag($('#spouse_city_of_birth'), stepDataObj.spouse_city_of_birth || '');
                            });
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
                            // Ensure only one job is current when loading (first one with job_current_job wins)
                            let currentJobAssigned = false;
                            jobs.forEach(function(job) {
                                if (job.job_current_job && currentJobAssigned) {
                                    job.job_current_job = 0;
                                } else if (job.job_current_job) {
                                    currentJobAssigned = true;
                                }
                            });
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
                            // Skip passport issuing country/city (handled above to support cities-by-country loading)
                            if (stepNum === 3 && (fieldName === 'issuing_country' || fieldName === 'city_where_issued')) {
                                return;
                            }
                            // Skip spouse country/state/city/city_of_birth (handled above to support dependent city loading)
                            if (stepNum === 5 && (fieldName === 'spouse_country' || fieldName === 'spouse_state' || fieldName === 'spouse_city' || fieldName === 'spouse_city_of_birth')) {
                                return;
                            }
                            
                            const $field = $('#' + fieldName + ', [name="' + fieldName + '"]').first();
                            if ($field.length) {
                                const value = stepDataObj[fieldName];
                                
                                // File inputs removed - skip file field handling
                                if ($field.is('input[type="file"]')) {
                                    return; // Skip file fields
                                } else if ($field.is('select')) {
                                    if ($field.hasClass('select2-hidden-accessible')) {
                                        setSelectBySavedText($field, value);
                                    } else {
                                        $field.val(value).selectpicker('refresh');
                                    }
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
                
                // Update passport validity warning when step 3 data was populated (e.g. expiration_date)
                if ($('#expiration_date').length && $('#expiration_date').val()) {
                    $('#expiration_date').trigger('change');
                }
                // Update passport category message (Non-ECR green tag / ECR red warning) when step 3 data was populated
                if ($('#passport_category').length) {
                    $('#passport_category').trigger('change');
                }
                // Set default passport type if no value was loaded from step_3_data
                setTimeout(function() {
                    const $passportType = $('#passport_type');
                    if ($passportType.length && (!$passportType.val() || $passportType.val() === '')) {
                        // Find "Ordinary Passport" option (id 1) and select it
                        const ordinaryPassportOption = $passportType.find('option[data-passport-type-id="1"]');
                        if (ordinaryPassportOption.length) {
                            $passportType.val(ordinaryPassportOption.val()).selectpicker('refresh');
                        }
                    }
                }, 100);
                // Update Last Passport History visibility (hide Old Passport Number/Year when "No Previous Passport")
                if ($('#last_passport_history').length) {
                    $('#last_passport_history').trigger('change');
                }
                
                // Update tab navigation after form fields are populated
                // Use a delay to ensure all selectpickers are refreshed
                setTimeout(function() {
                    updateTabNavigation();
                    if (typeof updateTotalLoanValueLevel === 'function') {
                        updateTotalLoanValueLevel();
                    }
                    if (typeof syncTotalLoanValueCopy === 'function') {
                        syncTotalLoanValueCopy();
                    }
                    if (typeof updateNetWorth === 'function') {
                        updateNetWorth();
                    }
                }, 300);
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

            // Check if first step (Personal Details) is completed
            function isFirstStepCompleted() {
                const surname = ($('#surname').val() || '').trim();
                const givenName = ($('#given_name').val() || '').trim();
                const primaryPhone = ($('#primary_phone').val() || '').trim();
                const emailAddress = ($('#email_address').val() || '').trim();
                
                // Check if all required fields are filled
                const hasSurname = surname.length > 0;
                const hasGivenName = givenName.length > 0;
                const hasPrimaryPhone = primaryPhone.length === 10 && /^[0-9]{10}$/.test(primaryPhone);
                const hasValidEmail = emailAddress.length > 0 && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailAddress);
                
                return hasSurname && hasGivenName && hasPrimaryPhone && hasValidEmail;
            }

            // Update tab navigation - disable all tabs except first until first step is completed
            function updateTabNavigation() {
                const firstStepCompleted = isFirstStepCompleted();
                
                $('.nav-link-lead').each(function() {
                    const $tab = $(this);
                    const tabId = $tab.attr('id');
                    
                    // Always enable the first tab (Personal Details)
                    if (tabId === 'nav-personal-tab') {
                        $tab.removeClass('disabled').css('pointer-events', 'auto').css('opacity', '1');
                    } else {
                        // Enable/disable other tabs based on first step completion
                        if (firstStepCompleted) {
                            $tab.removeClass('disabled').css('pointer-events', 'auto').css('opacity', '1');
                        } else {
                            $tab.addClass('disabled').css('pointer-events', 'none').css('opacity', '0.5');
                        }
                    }
                });
            }

            // Handle tab navigation - prevent navigation to disabled tabs
            $('.nav-link-lead').on('click', function(e) {
                const $tab = $(this);
                
                // Prevent navigation if tab is disabled
                if ($tab.hasClass('disabled') || $tab.css('pointer-events') === 'none') {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Show message to user
                    Swal.fire({
                        icon: 'info',
                        text: 'Please complete the Personal Details step first before accessing other steps.',
                        toast: true,
                        position: "top-end",
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });
                    
                    return false;
                }
                
                currentStep = getCurrentStep();
            });

            // Add event listeners to required fields for real-time validation
            function setupFirstStepValidation() {
                const requiredFields = ['#surname', '#given_name', '#primary_phone', '#email_address'];
                
                requiredFields.forEach(function(fieldId) {
                    $(fieldId).on('input blur', function() {
                        // Update tab navigation when any required field changes
                        updateTabNavigation();
                    });
                });
            }

            // Initialize tab navigation on page load
            $(document).ready(function() {
                // Disable all tabs except first on initial load
                updateTabNavigation();
                
                // Setup real-time validation for first step
                setupFirstStepValidation();
                
                // Also check after a short delay to handle any pre-filled data
                setTimeout(function() {
                    updateTabNavigation();
                }, 500);
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
                        // Passport number: if provided, min 8, max 9, alphanumeric only
                        const passportNumber = ($('#passport_number').val() || '').trim().toUpperCase();
                        if (passportNumber) {
                            if (passportNumber.length < 8 || passportNumber.length > 9) {
                                isValid = false;
                                showFieldError('#passport_number', 'Passport Number must be 8 to 9 characters');
                            } else if (!/^[A-Za-z0-9]+$/.test(passportNumber)) {
                                isValid = false;
                                showFieldError('#passport_number', 'Passport Number must contain only letters and numbers');
                            }
                        }
                        // Only validate date logic if both dates are provided
                        const issuanceDate = $('#issuance_date').val();
                        const expirationDate = $('#expiration_date').val();
                        if (issuanceDate && expirationDate && expirationDate <= issuanceDate) {
                            isValid = false;
                            showFieldError('#expiration_date', 'Expiration Date must be greater than Issuance Date');
                        }
                        // Old passport number: if provided, min 8, max 9, alphanumeric only
                        const oldPassportNumber = ($('#old_passport_number').val() || '').trim();
                        if (oldPassportNumber) {
                            if (oldPassportNumber.length < 8 || oldPassportNumber.length > 9) {
                                isValid = false;
                                showFieldError('#old_passport_number', 'Old Passport Number must be 8 to 9 characters');
                            } else if (!/^[A-Za-z0-9]+$/.test(oldPassportNumber)) {
                                isValid = false;
                                showFieldError('#old_passport_number', 'Old Passport Number must contain only letters and numbers');
                            }
                        }
                        break;
                        
                    case 4:
                        // Step 4 - Relative Contact Information
                        // Validate each relative contact row: Surname required, min 2 characters, alphabets only
                        $('.relative-contact-row').each(function() {
                            const contactIndex = $(this).data('contact-index');
                            const surnameVal = ($('#relative_surname_' + contactIndex).val() || '').trim();
                            const givenNameVal = ($('#relative_given_name_' + contactIndex).val() || '').trim();
                            const orgTypeVal = $('#relative_organization_type_' + contactIndex).val() || '';
                            let orgNameVal = '';
                            if (orgTypeVal === 'other') {
                                orgNameVal = ($('#relative_organization_name_other_' + contactIndex).val() || '').trim();
                            } else if (orgTypeVal) {
                                const ot = organizationTypes.find(function(o) { return o.id == orgTypeVal; });
                                orgNameVal = ot ? ot.name : '';
                            }
                            const relationshipTypeVal = $('#relative_relationship_' + contactIndex).val() || '';
                            let relationshipVal = '';
                            if (relationshipTypeVal === 'other') {
                                relationshipVal = ($('#relative_relationship_other_' + contactIndex).val() || '').trim();
                            } else if (relationshipTypeVal) {
                                const rel = relationshipsMaster.find(function(r) { return r.id == relationshipTypeVal; });
                                relationshipVal = rel ? rel.name : '';
                            }
                            const addressVal = ($('#relative_contact_address_' + contactIndex).val() || '').trim();
                            const countryVal = $('#relative_country_' + contactIndex).val();
                            const stateVal = $('#relative_state_' + contactIndex).val();
                            const cityVal = $('#relative_city_' + contactIndex).val();
                            const zipVal = ($('#relative_zip_code_' + contactIndex).val() || '').trim();
                            const emailVal = ($('#relative_email_address_' + contactIndex).val() || '').trim();
                            const phoneVal = ($('#relative_phone_number_' + contactIndex).val() || '').trim();
                            const rowHasAnyValue = surnameVal || givenNameVal || orgNameVal || relationshipVal || addressVal || countryVal || stateVal || cityVal || zipVal || emailVal || phoneVal;
                            if (rowHasAnyValue) {
                                // Given Name: alphabets only when filled
                                if (givenNameVal && !/^[A-Za-z\s]+$/.test(givenNameVal)) {
                                    isValid = false;
                                    showFieldError('#relative_given_name_' + contactIndex, 'Given Name must contain only alphabets');
                                }
                                // Surname: if filled, 0 or 3+ alphabets (1 or 2 not valid); 3+ must be alphabets only
                                const surnameFilledAndValid = surnameVal.length >= 3 && /^[A-Za-z]+$/.test(surnameVal);
                                if (surnameVal.length === 1 || surnameVal.length === 2) {
                                    isValid = false;
                                    showFieldError('#relative_surname_' + contactIndex, 'Surname must be either empty or at least 3 characters (1 or 2 characters are not valid)');
                                } else if (surnameVal.length >= 3 && !/^[A-Za-z]+$/.test(surnameVal)) {
                                    isValid = false;
                                    showFieldError('#relative_surname_' + contactIndex, 'Surname must contain only alphabets');
                                }
                                // When Surname is filled (3+ alphabets), Given Name is compulsory
                                if (surnameFilledAndValid) {
                                    if (!givenNameVal) {
                                        isValid = false;
                                        showFieldError('#relative_given_name_' + contactIndex, 'Given Name is compulsory.');
                                    } else if (!/^[A-Za-z\s]+$/.test(givenNameVal)) {
                                        isValid = false;
                                        showFieldError('#relative_given_name_' + contactIndex, 'Given Name must contain only alphabets');
                                    }
                                }
                            }
                        });
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
                
                // Step 8 (Property Details): blank valuation and total_loan_value fields auto-store as 0
                if (currentStep === 8) {
                    $('.valuation-input').each(function() {
                        const $el = $(this);
                        const val = $el.val();
                        if (val === '' || val === null || val === undefined) {
                            $el.val(0);
                        }
                    });
                    const $tlv = $('#total_loan_value');
                    const tlvVal = $tlv.val();
                    if (tlvVal === '' || tlvVal === null || tlvVal === undefined) {
                        $tlv.val(0);
                    }
                }
                // Step 9 (Financial Status): blank income fields auto-store as 0 (same as Tab 8)
                if (currentStep === 9) {
                    $('.income-input').each(function() {
                        const $el = $(this);
                        const val = $el.val();
                        if (val === '' || val === null || val === undefined) {
                            $el.val(0);
                        }
                    });
                    const $ti = $('#total_income');
                    const tiVal = $ti.val();
                    if (tiVal === '' || tiVal === null || tiVal === undefined) {
                        $ti.val(0);
                    }
                }
                
                const formData = new FormData($('#addLeadForm')[0]);
                
                // Ensure step 4 relative contacts data is collected
                if (currentStep === 4) {
                    // Collect relative contacts data
                    const relativeContacts = [];
                    $('.relative-contact-row').each(function() {
                        const contactIndex = $(this).data('contact-index');
                        const surname = $('#relative_surname_' + contactIndex).val() || '';
                        const givenName = $('#relative_given_name_' + contactIndex).val() || '';
                        const orgTypeVal = $('#relative_organization_type_' + contactIndex).val() || '';
                        let orgName = '';
                        if (orgTypeVal === 'other') {
                            orgName = $('#relative_organization_name_other_' + contactIndex).val() || '';
                        } else if (orgTypeVal) {
                            const ot = organizationTypes.find(function(o) { return o.id == orgTypeVal; });
                            orgName = ot ? ot.name : '';
                        }
                        const relationshipTypeVal = $('#relative_relationship_' + contactIndex).val() || '';
                        let relationship = '';
                        if (relationshipTypeVal === 'other') {
                            relationship = $('#relative_relationship_other_' + contactIndex).val() || '';
                        } else if (relationshipTypeVal) {
                            const rel = relationshipsMaster.find(function(r) { return r.id == relationshipTypeVal; });
                            relationship = rel ? rel.name : '';
                        }
                        const address = $('#relative_contact_address_' + contactIndex).val() || '';
                        let country = $('#relative_country_' + contactIndex).val() || '';
                        let city = $('#relative_city_' + contactIndex).val() || '';
                        let state = $('#relative_state_' + contactIndex).val() || '';
                        const zipCode = $('#relative_zip_code_' + contactIndex).val() || '';
                        const email = $('#relative_email_address_' + contactIndex).val() || '';
                        const phone = $('#relative_phone_number_' + contactIndex).val() || '';
                        const whatsappEnable = $('#relative_whatsapp_enable_' + contactIndex).is(':checked');
                        const sameAsHome = $('#relative_contact_same_as_home_' + contactIndex).is(':checked');

                        // Resolve country/state/city ids to names
                        if (country && !isNaN(country)) {
                            const coObj = countryMasters.find(function (c) { return c.id == country; });
                            if (coObj) country = coObj.name;
                        }
                        if (state && !isNaN(state)) {
                            const stObj = stateMasters.find(function (s) { return s.id == state; });
                            if (stObj) state = stObj.name;
                        }
                        if (city && !isNaN(city)) {
                            const ctObj = cityMasters.find(function (c) { return c.id == city; });
                            if (ctObj) city = ctObj.name;
                        }
                        
                        // Only add contact if at least one field has a value
                        if (surname || givenName || orgName || relationship || address || country || city || state || zipCode || email || phone) {
                            const contactData = {
                                relative_surname: surname,
                                relative_given_name: givenName,
                                relative_organization_name: orgName,
                                relative_relationship: relationship,
                                relative_contact_address: address,
                                relative_country: country,
                                relative_city: city,
                                relative_state: state,
                                relative_zip_code: zipCode,
                                relative_email_address: email,
                                relative_phone_number: phone,
                                relative_whatsapp_enable: whatsappEnable,
                                relative_contact_same_as_home: sameAsHome
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
                        
                        // Only add child if at least one field has a value
                        if (childName || childAge || childDob || childCity || childGender || childHavePassport) {
                            const childData = {
                                child_name: childName,
                                child_age: childAge,
                                child_date_of_birth: childDob,
                                child_city_of_birth: childCity,
                                child_gender: childGender,
                                child_have_passport: childHavePassport
                            };
                            children.push(childData);
                        }
                    });
                    
                    // Add children data as JSON
                    formData.append('children', JSON.stringify(children));
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
                        
                        // Only add other degree if at least one field has a value
                        if (otherDegree || universityName || percentage || passingYear || trial) {
                            const degreeData = {
                                other_degree: otherDegree,
                                other_degree_university_name: universityName,
                                other_degree_percentage: percentage,
                                other_degree_passing_year: passingYear,
                                other_degree_trial: trial
                            };
                            otherDegrees.push(degreeData);
                        }
                    });
                    
                    // Add other degrees data as JSON
                    formData.append('other_degrees', JSON.stringify(otherDegrees));
                }
                
                // Ensure step 7 file uploads are included if selected
                if (currentStep === 7) {
                    // Collect jobs data
                    const jobs = [];
                    $('.job-row').each(function() {
                        const jobIndex = $(this).data('job-index');
                        const durationFrom = $('#job_duration_from_' + jobIndex).val() || '';
                        const durationTo = $('#job_duration_to_' + jobIndex).val() || '';
                        const jobExperience = $('#job_experience_' + jobIndex).val() || '';
                        let country = $('#job_country_' + jobIndex).val() || '';
                        const employmentType = $('#job_employment_type_' + jobIndex).val() || '';
                        let designation = $('#job_designation_' + jobIndex).val() || '';
                        const companyName = $('#job_company_name_' + jobIndex).val() || '';
                        let jobIndustry = $('#job_industry_' + jobIndex).val() || '';
                        let jobSector = $('#job_sector_' + jobIndex).val() || '';
                        const salary = $('#job_salary_' + jobIndex).val() || '';
                        const jobCurrentJob = $('#job_current_job_' + jobIndex).is(':checked') ? 1 : 0;

                        // Resolve country id to name
                        if (country && !isNaN(country)) {
                            const cObj = countryMasters.find(function (c) { return c.id == country; });
                            if (cObj) country = cObj.name;
                        }
                        // Industry: if "other" use Other Industry text; else resolve id to name
                        if (jobIndustry === 'other') {
                            jobIndustry = ($('#job_industry_other_' + jobIndex).val() || '').trim();
                        } else if (jobIndustry && !isNaN(jobIndustry)) {
                            const iObj = industryMasters.find(function (i) { return i.id == jobIndustry; });
                            if (iObj) jobIndustry = iObj.name;
                        }
                        // Designation: if value is id (numeric), resolve to name; else use as-is (custom text)
                        if (designation && !isNaN(designation)) {
                            const dObj = designationsMaster.find(function (d) { return d.id == designation; });
                            if (dObj) designation = dObj.name;
                        }
                        // Sector: if "other" use Other Sector text; else resolve id to name (use selected option text)
                        if (jobSector === 'other') {
                            jobSector = ($('#job_sector_other_' + jobIndex).val() || '').trim();
                        } else if (jobSector) {
                            const $sec = $('#job_sector_' + jobIndex).find('option:selected');
                            if ($sec.length && $sec.text()) jobSector = $sec.text().trim();
                        }
                        
                        // Only add job if at least one field has a value
                        if (durationFrom || durationTo || country || employmentType || designation || companyName || jobIndustry || jobSector || salary) {
                            const jobData = {
                                job_duration_from: durationFrom,
                                job_duration_to: jobCurrentJob ? '' : durationTo,
                                job_current_job: jobCurrentJob,
                                job_experience: jobExperience,
                                job_country: country,
                                job_employment_type: employmentType,
                                job_designation: designation,
                                job_company_name: companyName,
                                job_industry: jobIndustry,
                                job_sector: jobSector,
                                job_salary: salary
                            };
                            jobs.push(jobData);
                        }
                    });
                    
                    // Add jobs data as JSON
                    formData.append('jobs', JSON.stringify(jobs));
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
                        let category = $('#visa_refusal_category_' + refusalIndex).val() || '';
                        const reason = $('#visa_refusal_reason_' + refusalIndex).val() || '';
                        
                        // Handle category: resolve ID to name, or use "Other" text
                        if (category === 'other' || category === 'Other') {
                            category = $('#visa_refusal_category_other_' + refusalIndex).val() || '';
                        } else if (category && !isNaN(category)) {
                            // It's a numeric ID, find the category name
                            const categoryObj = visaCategories.find(function(vc) {
                                return vc.id == category;
                            });
                            if (categoryObj) {
                                category = categoryObj.name;
                            }
                        }
                        
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
                            // Check if this is a duplicate lead error
                            const duplicateData = response.data || {};
                            if (duplicateData.duplicate && duplicateData.duplicate_lead_number) {
                                // Show duplicate lead popup
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Duplicate Lead',
                                    html: '<div class="text-center">' +
                                          '<p class="mb-3">A lead with the same <strong>' + (duplicateData.duplicate_reason || 'information') + '</strong> already exists.</p>' +
                                          '<p class="mb-3"><strong>Duplicate Lead Number:</strong> ' + duplicateData.duplicate_lead_number + '</p>' +
                                          '</div>',
                                    showCancelButton: true,
                                    confirmButtonText: 'View Lead',
                                    cancelButtonText: 'Cancel',
                                    customClass: {
                                        confirmButton: 'btn btn-primary mr-2',
                                        cancelButton: 'btn btn-secondary'
                                    },
                                    buttonsStyling: false
                                }).then((result) => {
                                    if (result.isConfirmed && duplicateData.view_lead_url) {
                                        // Open duplicate lead in new tab
                                        window.open(duplicateData.view_lead_url, '_blank');
                                    }
                                });
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
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = '@lang('messages.errorOccurred')';
                        let response = xhr.responseJSON || {};
                        const duplicateData = response.data || {};
                        
                        // Check if this is a duplicate lead error
                        if (duplicateData.duplicate && duplicateData.duplicate_lead_number) {
                            // Show duplicate lead popup
                            Swal.fire({
                                icon: 'warning',
                                title: 'Duplicate Lead',
                                html: '<div class="text-center">' +
                                      '<p class="mb-3">A lead with the same <strong>' + (duplicateData.duplicate_reason || 'information') + '</strong> already exists.</p>' +
                                      '<p class="mb-3"><strong>Duplicate Lead Number:</strong> ' + duplicateData.duplicate_lead_number + '</p>' +
                                      '</div>',
                                showCancelButton: true,
                                confirmButtonText: 'View Lead',
                                cancelButtonText: 'Cancel',
                                customClass: {
                                    confirmButton: 'btn btn-primary mr-2',
                                    cancelButton: 'btn btn-secondary'
                                },
                                buttonsStyling: false
                            }).then((result) => {
                                if (result.isConfirmed && duplicateData.view_lead_url) {
                                    // Open duplicate lead in new tab
                                    window.open(duplicateData.view_lead_url, '_blank');
                                }
                            });
                        } else {
                            // Handle validation errors from backend
                            if (response.message) {
                                errorMessage = response.message;
                            } else if (response.errors) {
                                // Laravel validation errors - collect all errors
                                const errors = response.errors;
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
                        }
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
            
            // Initialize on page load - always default to step 1
            currentStep = 1;
            // Ensure step 1 (nav-personal-tab) is always active on page load/refresh
            $('#nav-personal-tab').tab('show');
            
            // Load existing lead data if lead_id exists (with a small delay to ensure DOM is ready)
            if (currentLeadId) {
                setTimeout(function() {
                    loadExistingLeadData(currentLeadId);
                }, 500);
            }
            
            // File inputs removed - no handlers needed
            
            // Initialize tab navigation on page load (will be updated after data loads if lead exists)
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

