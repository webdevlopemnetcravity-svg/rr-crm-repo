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
                        <a class="nav-item nav-link f-14 active" id="nav-personal-tab" data-toggle="tab" href="#nav-personal" role="tab" aria-controls="nav-personal" aria-selected="true">
                            <i class="fa fa-user mr-2"></i>Personal Details
                        </a>
                        <a class="nav-item nav-link f-14" id="nav-preference-tab" data-toggle="tab" href="#nav-preference" role="tab" aria-controls="nav-preference" aria-selected="false">
                            <i class="fa fa-briefcase mr-2"></i>Client Preference
                        </a>
                        <a class="nav-item nav-link f-14" id="nav-passport-tab" data-toggle="tab" href="#nav-passport" role="tab" aria-controls="nav-passport" aria-selected="false">
                            <i class="fa fa-id-card mr-2"></i>Passport Details
                        </a>
                        <a class="nav-item nav-link f-14" id="nav-relative-tab" data-toggle="tab" href="#nav-relative" role="tab" aria-controls="nav-relative" aria-selected="false">
                            <i class="fa fa-address-book mr-2"></i>Relative Contact Information
                        </a>
                        <a class="nav-item nav-link f-14" id="nav-family-tab" data-toggle="tab" href="#nav-family" role="tab" aria-controls="nav-family" aria-selected="false">
                            <i class="fa fa-users mr-2"></i>Family Information
                        </a>
                        <a class="nav-item nav-link f-14" id="nav-education-tab" data-toggle="tab" href="#nav-education" role="tab" aria-controls="nav-education" aria-selected="false">
                            <i class="fa fa-graduation-cap mr-2"></i>Education
                        </a>
                        <a class="nav-item nav-link f-14" id="nav-experience-tab" data-toggle="tab" href="#nav-experience" role="tab" aria-controls="nav-experience" aria-selected="false">
                            <i class="fa fa-briefcase mr-2"></i>Professional Experience
                        </a>
                        <a class="nav-item nav-link f-14" id="nav-property-tab" data-toggle="tab" href="#nav-property" role="tab" aria-controls="nav-property" aria-selected="false">
                            <i class="fa fa-building mr-2"></i>Property Details
                        </a>
                        <a class="nav-item nav-link f-14" id="nav-financial-tab" data-toggle="tab" href="#nav-financial" role="tab" aria-controls="nav-financial" aria-selected="false">
                            <i class="fa fa-money-bill-wave mr-2"></i>Financial Status
                        </a>
                        {{-- <a class="nav-item nav-link f-14" id="nav-travel-tab" data-toggle="tab" href="#nav-travel" role="tab" aria-controls="nav-travel" aria-selected="false">
                            <i class="fa fa-plane mr-2"></i>Travel Details
                        </a> --}}
                    </div>
                </nav>
            </div>

            <!-- Form Card -->
            <x-form id="addLeadForm" class="ajax-form">
                <div class="tab-content p-20" id="nav-tabContent">
                    <!-- Personal Details Tab -->
                    <div class="tab-pane fade show active" id="nav-personal" role="tabpanel" aria-labelledby="nav-personal-tab">
                        <!-- Personal Details Section -->
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="lead_source" :fieldLabel="__('modules.lead.leadSource')" fieldRequired="true">
                                </x-forms.label>
                                <select class="form-control select-picker" name="lead_source" id="lead_source">
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
                                <x-forms.label class="mt-3" fieldId="lead_added_by" fieldLabel="Lead Added by">
                                </x-forms.label>
                                <input type="text" class="form-control" id="lead_added_by" name="lead_added_by" value="{{ user()->name }}" disabled>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="lead_assign_to" fieldLabel="Lead Assign to" fieldRequired="true">
                                </x-forms.label>
                                <select class="form-control select-picker" name="lead_assign_to" id="lead_assign_to">
                                    <option value="">@lang('app.select') Lead Assign to</option>
                                    <option value="{{ user()->id }}">{{ user()->name }}</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="passport_file" fieldLabel="Add Passport">
                                </x-forms.label>
                                <input class="form-control" type="file" id="passport_file" name="passport_file">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="surname" fieldLabel="Surname" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="surname" id="surname">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="given_name" fieldLabel="Given Name" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="given_name" id="given_name">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="gender" fieldLabel="Gender" fieldRequired="true">
                                </x-forms.label>
                                <select class="form-control select-picker" name="gender" id="gender">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="marital_status" fieldLabel="Marital Status" fieldRequired="true">
                                </x-forms.label>
                                <select class="form-control select-picker" name="marital_status" id="marital_status">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Widowed">Widowed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="date_of_birth" fieldLabel="Date of Birth" fieldRequired="true">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="date_of_birth" id="date_of_birth">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="country_of_origin" fieldLabel="Country of Origin (Nationality)" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="country_of_origin" id="country_of_origin">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Home Address Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">Home Address</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="home_address" :fieldLabel="__('modules.lead.address')" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="home_address" id="home_address">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="home_city" fieldLabel="City" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="home_city" id="home_city">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="home_state" fieldLabel="State" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="home_state" id="home_state">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="home_pin_code" fieldLabel="Pin Code" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="home_pin_code" id="home_pin_code">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Mailing Address Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">Mailing Address</h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mailing_same_as_home" id="mailing_same_as_home" value="1">
                                    <label class="form-check-label" for="mailing_same_as_home">
                                        Mailing Address As Above
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mailing_address" :fieldLabel="__('modules.lead.address')" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mailing_address" id="mailing_address">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mailing_city" fieldLabel="City" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mailing_city" id="mailing_city">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mailing_state" fieldLabel="State" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mailing_state" id="mailing_state">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mailing_pin_code" fieldLabel="Pin Code" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mailing_pin_code" id="mailing_pin_code">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Contact Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">Contact Details</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="primary_phone" fieldLabel="Primary Phone No" fieldRequired="true">
                                </x-forms.label>
                                <input type="tel" maxlength="10" class="form-control height-35 f-14" name="primary_phone" id="primary_phone">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="secondary_phone" fieldLabel="Secondary Phone No">
                                </x-forms.label>
                                <input type="tel" maxlength="10" class="form-control height-35 f-14" name="secondary_phone" id="secondary_phone">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="work_phone" fieldLabel="Work Phone No">
                                </x-forms.label>
                                <input type="tel" maxlength="10" class="form-control height-35 f-14" name="work_phone" id="work_phone">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_phone" fieldLabel="Other Phone No (Used in Last 5 Years)">
                                </x-forms.label>
                                <textarea class="form-control f-14" rows="3" name="other_phone" id="other_phone"></textarea>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="email_address" :fieldLabel="__('modules.lead.email')" fieldRequired="true">
                                </x-forms.label>
                                <input type="email" class="form-control height-35 f-14" name="email_address" id="email_address">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_email" fieldLabel="Other Email (Used in Last 5 Years)">
                                </x-forms.label>
                                <textarea class="form-control f-14" rows="3" name="other_email" id="other_email"></textarea>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="social_media_preference" fieldLabel="Social Media Preference">
                                </x-forms.label>
                                <select class="form-control select-picker" name="social_media_preference" id="social_media_preference">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Facebook">Facebook</option>
                                    <option value="Instagram">Instagram</option>
                                    <option value="LinkedIn">LinkedIn</option>
                                    <option value="Twitter / X">Twitter / X</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Visa Status Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">Last Five Years Visa Status (If applicable)</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="radio" name="visa_status" id="visa_granted" value="granted">
                                    <label class="form-check-label" for="visa_granted">
                                        Visa Granted
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="visa_issue_date" fieldLabel="Visa Issue Date">
                                </x-forms.label>
                                <input type="month" class="form-control height-35 f-14" name="visa_issue_date" id="visa_issue_date">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="visa_expire_date" fieldLabel="Visa Expire Date">
                                </x-forms.label>
                                <input type="month" class="form-control height-35 f-14" name="visa_expire_date" id="visa_expire_date">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="visa_category" fieldLabel="Visa Category">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="visa_category" id="visa_category">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="visa_status" id="visa_refusal" value="refusal">
                                    <label class="form-check-label" for="visa_refusal">
                                        Visa Refusal
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="visa_rejection_date" fieldLabel="Visa Rejection Date">
                                </x-forms.label>
                                <input type="month" class="form-control height-35 f-14" name="visa_rejection_date" id="visa_rejection_date">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="visa_refusal_category" fieldLabel="Visa Category">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="visa_refusal_category" id="visa_refusal_category">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <x-forms.label class="mt-3" fieldId="visa_refusal_reason" fieldLabel="Reason">
                                </x-forms.label>
                                <textarea class="form-control f-14" rows="2" name="visa_refusal_reason" id="visa_refusal_reason"></textarea>
                            </div>
                            <div class="col-md-3 mt-3">
                                <button type="button" class="btn btn-secondary btn-sm" id="add-more-visa-refusal">
                                    <i class="fa fa-plus mr-1"></i>Add More Visa Refusal
                                </button>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Languages Spoken Section -->
                <div class="row">
                            <div class="col-md-12">
                                <x-forms.label class="mt-3" fieldId="languages_spoken" fieldLabel="Languages Spoken">
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
                                <x-forms.label class="mt-3 mb-3" fieldId="visa_type" fieldLabel="Select Visa Type:" fieldRequired="true">
                                </x-forms.label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input visa-type" type="radio" name="visa_type" id="visa_pr" value="pr">
                                    <label class="form-check-label" for="visa_pr">PR</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input visa-type" type="radio" name="visa_type" id="visa_visit" value="visit">
                                    <label class="form-check-label" for="visa_visit">Visit Visa</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input visa-type" type="radio" name="visa_type" id="visa_work" value="work">
                                    <label class="form-check-label" for="visa_work">Work Permit</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input visa-type" type="radio" name="visa_type" id="visa_student" value="student">
                                    <label class="form-check-label" for="visa_student">Student Visa</label>
                                </div>
                    </div>
                </div>

                        <!-- PR Section -->
                        <div id="prSection" class="form-section d-none">
                <div class="row">
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="skill_assessment_letter" fieldLabel="Skill Assessment Letter" fieldRequired="true">
                                    </x-forms.label>
                                    <select class="form-control select-picker" name="skill_assessment_letter" id="skill_assessment_letter">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Positive">Positive</option>
                                        <option value="Negative">Negative</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="pr_assessment_letter_file" fieldLabel="Add Assessment Letter" fieldRequired="true">
                                    </x-forms.label>
                                    <input class="form-control" type="file" id="pr_assessment_letter_file" name="pr_assessment_letter_file">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="pr_preferred_country" fieldLabel="Preferred Country">
                                    </x-forms.label>
                                    <select class="form-control select-picker" name="pr_preferred_country" id="pr_preferred_country">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Canada">Canada</option>
                                        <option value="Australia">Australia</option>
                                        <option value="UK">UK</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="pr_preferred_state" fieldLabel="Preferred State">
                                    </x-forms.label>
                                    <select class="form-control select-picker" name="pr_preferred_state" id="pr_preferred_state">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Victoria">Victoria</option>
                                        <option value="Ontario">Ontario</option>
                                        <option value="Alberta">Alberta</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="pr_family" fieldLabel="Family" fieldRequired="true">
                                    </x-forms.label>
                                    <select class="form-control select-picker" name="pr_family" id="pr_family">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Single">Single</option>
                                        <option value="Couple Visa">Couple Visa</option>
                                        <option value="Couple + Children Visa">Couple + Children Visa</option>
                                        <option value="Family Visa">Family Visa</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="pr_subclass" fieldLabel="Subclass">
                                    </x-forms.label>
                                    <select class="form-control select-picker" name="pr_subclass" id="pr_subclass">
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
                                    <x-forms.label class="mt-3" fieldId="purpose_of_visit" fieldLabel="Purpose of Visit" fieldRequired="true">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="purpose_of_visit" id="purpose_of_visit">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="visit_family" fieldLabel="Family" fieldRequired="true">
                                    </x-forms.label>
                                    <select class="form-control select-picker" name="visit_family" id="visit_family">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Single">Single</option>
                                        <option value="Couple Visa">Couple Visa</option>
                                        <option value="Couple + Children Visa">Couple + Children Visa</option>
                                        <option value="Family Visa">Family Visa</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="visit_preferred_country" fieldLabel="Preferred Country">
                                    </x-forms.label>
                                    <select class="form-control select-picker" name="visit_preferred_country" id="visit_preferred_country">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Australia">Australia</option>
                                        <option value="New Zealand">New Zealand</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="visit_preferred_state" fieldLabel="Preferred State">
                                    </x-forms.label>
                                    <select class="form-control select-picker" name="visit_preferred_state" id="visit_preferred_state">
                                        <option value="">@lang('app.select')</option>
                                        <option value="New York">New York</option>
                                        <option value="Dubai">Dubai</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="visit_subclass" fieldLabel="Subclass">
                                    </x-forms.label>
                                    <select class="form-control select-picker" name="visit_subclass" id="visit_subclass">
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
                                    <x-forms.label class="mt-3" fieldId="preferred_designation" fieldLabel="Preferred Designation" fieldRequired="true">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="preferred_designation" id="preferred_designation" placeholder="Enter designation">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="industry" fieldLabel="Industry">
                                    </x-forms.label>
                                    <select class="form-control select-picker" name="industry" id="industry">
                                        <option value="List given in document" selected>List given in document</option>
                                        <option value="IT">IT</option>
                                        <option value="Finance">Finance</option>
                                        <option value="Healthcare">Healthcare</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="on_role_off_role" fieldLabel="On Role / Off Role">
                                    </x-forms.label>
                                    <select class="form-control select-picker" name="on_role_off_role" id="on_role_off_role">
                                        <option value="">@lang('app.select')</option>
                                        <option value="On Role">On Role</option>
                                        <option value="Off Role">Off Role</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="work_preferred_country" fieldLabel="Preferred Country">
                                    </x-forms.label>
                                    <select class="form-control select-picker" name="work_preferred_country" id="work_preferred_country">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Australia">Australia</option>
                                        <option value="New Zealand">New Zealand</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="work_category" fieldLabel="Work Category">
                                    </x-forms.label>
                                    <select class="form-control select-picker" name="work_category" id="work_category">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Skilled">Skilled</option>
                                        <option value="Semi-Skilled">Semi-Skilled</option>
                                        <option value="Unskilled">Unskilled</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="work_subclass" fieldLabel="Subclass">
                                    </x-forms.label>
                                    <select class="form-control select-picker" name="work_subclass" id="work_subclass">
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
                                    <x-forms.label class="mt-3" fieldId="preferred_course" fieldLabel="Preferred Course">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="preferred_course" id="preferred_course">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="student_country" fieldLabel="Country">
                                    </x-forms.label>
                                    <select class="form-control select-picker" name="student_country" id="student_country">
                                        <option value="">@lang('app.select')</option>
                                        <option value="Canada">Canada</option>
                                        <option value="UK">UK</option>
                                        <option value="Australia">Australia</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="university" fieldLabel="University">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="university" id="university">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="term_intake" fieldLabel="Term/ Intake" fieldRequired="true">
                                    </x-forms.label>
                                    <input type="text" class="form-control height-35 f-14" name="term_intake" id="term_intake">
                                </div>
                                <div class="col-md-3">
                                    <x-forms.label class="mt-3" fieldId="student_subclass" fieldLabel="Subclass">
                                    </x-forms.label>
                                    <select class="form-control select-picker" name="student_subclass" id="student_subclass">
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
                                <x-forms.label class="mt-3" fieldId="passport_number" fieldLabel="Passport Number">
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
                                <x-forms.label class="mt-3" fieldId="issuance_date" fieldLabel="Issuance Date" fieldRequired="true">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="issuance_date" id="issuance_date">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="expiration_date" fieldLabel="Expiration Date" fieldRequired="true">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="expiration_date" id="expiration_date">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="passport_file_upload" fieldLabel="Add Passport" fieldRequired="true">
                                </x-forms.label>
                                <input class="form-control" type="file" id="passport_file_upload" name="passport_file_upload">
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
                                <input type="text" class="form-control height-35 f-14" name="relative_zip_code" id="relative_zip_code">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_email_address" fieldLabel="Email Address">
                                </x-forms.label>
                                <input type="email" class="form-control height-35 f-14" name="relative_email_address" id="relative_email_address">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="relative_phone_number" fieldLabel="Phone Number">
                                </x-forms.label>
                                <input type="tel" maxlength="10" class="form-control height-35 f-14" name="relative_phone_number" id="relative_phone_number">
                            </div>
                            <div class="col-md-3 mt-3">
                                <button type="button" class="btn btn-secondary btn-sm" id="add-more-relative">
                                    <i class="fa fa-plus mr-1"></i>Add More
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Family Information Tab -->
                    <div class="tab-pane fade" id="nav-family" role="tabpanel" aria-labelledby="nav-family-tab">
                        <!-- Father Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">Father Details</h6>
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
                                <input type="date" class="form-control height-35 f-14" name="father_date_of_birth" id="father_date_of_birth">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="father_occupation" fieldLabel="Father's Occupation" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="father_occupation" id="father_occupation">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="father_have_passport" fieldLabel="Have Passport" fieldRequired="true">
                                </x-forms.label>
                                <select class="form-control select-picker" name="father_have_passport" id="father_have_passport">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="father_passport_file" fieldLabel="Add Father Passport">
                                </x-forms.label>
                                <input class="form-control" type="file" id="father_passport_file" name="father_passport_file">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Mother Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">Mother Details</h6>
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
                                <input type="date" class="form-control height-35 f-14" name="mother_date_of_birth" id="mother_date_of_birth">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mother_occupation" fieldLabel="Mother's Occupation" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="mother_occupation" id="mother_occupation">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mother_have_passport" fieldLabel="Have Passport" fieldRequired="true">
                                </x-forms.label>
                                <select class="form-control select-picker" name="mother_have_passport" id="mother_have_passport">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mother_passport_file" fieldLabel="Add Mother Passport" fieldRequired="true">
                                </x-forms.label>
                                <input class="form-control" type="file" id="mother_passport_file" name="mother_passport_file">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Spouse Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">Spouse Details</h6>
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
                                <input type="date" class="form-control height-35 f-14" name="spouse_date_of_birth" id="spouse_date_of_birth">
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
                                <select class="form-control select-picker" name="spouse_have_passport" id="spouse_have_passport">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_passport_file" fieldLabel="Add Spouse Passport">
                                </x-forms.label>
                                <input class="form-control" type="file" id="spouse_passport_file" name="spouse_passport_file">
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
                                <input type="text" class="form-control height-35 f-14" name="spouse_phone_number" id="spouse_phone_number">
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
                                <input type="text" class="form-control height-35 f-14" name="spouse_yearly_income" id="spouse_yearly_income">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_document_file" fieldLabel="Add Spouse Document">
                                </x-forms.label>
                                <input class="form-control" type="file" id="spouse_document_file" name="spouse_document_file">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Child Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">Child 1</h6>
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
                                <input type="date" class="form-control height-35 f-14" name="child_date_of_birth" id="child_date_of_birth">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="child_city_of_birth" fieldLabel="City of Birth">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="child_city_of_birth" id="child_city_of_birth">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="child_have_passport" fieldLabel="Have Passport">
                                </x-forms.label>
                                <select class="form-control select-picker" name="child_have_passport" id="child_have_passport">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="child_gender" fieldLabel="Gender">
                                </x-forms.label>
                                <select class="form-control select-picker" name="child_gender" id="child_gender">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Prefer not to say">Prefer not to say</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="child_document_file" fieldLabel="Add Child Document">
                                </x-forms.label>
                                <input class="form-control" type="file" id="child_document_file" name="child_document_file">
                            </div>
                            <div class="col-md-3 mt-3">
                                <button type="button" class="btn btn-secondary btn-sm" id="add-more-child">
                                    <i class="fa fa-plus mr-1"></i>Add More Child
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Education Tab -->
                    <div class="tab-pane fade" id="nav-education" role="tabpanel" aria-labelledby="nav-education-tab">
                        <!-- IELTS/PTC/OET/TOEFL Exam Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">IELTS/PTC/OET/TOEFL Exam Details</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="ielts_clear_or_not" fieldLabel="IELTS/PTC/OET/TOEFL is Clear or Not">
                                </x-forms.label>
                                <select class="form-control select-picker" name="ielts_clear_or_not" id="ielts_clear_or_not">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="ielts_passing_year" fieldLabel="Passing Year">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="ielts_passing_year" id="ielts_passing_year">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="ielts_score" fieldLabel="Score">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="ielts_score" id="ielts_score">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="ielts_trial" fieldLabel="Trial">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="ielts_trial" id="ielts_trial">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="ielts_result_file" fieldLabel="Add IELTS/PTC/OET/TOEFL Result">
                                </x-forms.label>
                                <input class="form-control" type="file" id="ielts_result_file" name="ielts_result_file">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- 10th Exam Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">10th Exam Details</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="tenth_passing_year" fieldLabel="10th Passing Year" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="tenth_passing_year" id="tenth_passing_year">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="tenth_percentage" fieldLabel="Percentage" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="tenth_percentage" id="tenth_percentage">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="tenth_board_name" fieldLabel="Board Name" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="tenth_board_name" id="tenth_board_name">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="tenth_trial" fieldLabel="Trial" fieldRequired="true">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="tenth_trial" id="tenth_trial">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="tenth_result_file" fieldLabel="Add 10th Result">
                                </x-forms.label>
                                <input class="form-control" type="file" id="tenth_result_file" name="tenth_result_file">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- 12th Exam Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">12th Exam Details</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="twelfth_passing_year" fieldLabel="12th Passing Year">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="twelfth_passing_year" id="twelfth_passing_year">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="twelfth_stream" fieldLabel="Stream">
                                </x-forms.label>
                                <select class="form-control select-picker" name="twelfth_stream" id="twelfth_stream">
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
                                <input type="text" class="form-control height-35 f-14" name="twelfth_percentage" id="twelfth_percentage">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="twelfth_board_name" fieldLabel="Board Name">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="twelfth_board_name" id="twelfth_board_name">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="twelfth_trial" fieldLabel="Trial">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="twelfth_trial" id="twelfth_trial">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="twelfth_result_file" fieldLabel="Add 12th Result">
                                </x-forms.label>
                                <input class="form-control" type="file" id="twelfth_result_file" name="twelfth_result_file">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Graduation Degree Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">Graduation Degree Details</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="graduation_degree" fieldLabel="Graduation Degree">
                                </x-forms.label>
                                <select class="form-control select-picker" name="graduation_degree" id="graduation_degree">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Bachelor of Fine Arts (B.F.A)">Bachelor of Fine Arts (B.F.A)</option>
                                    <option value="Bachelor of Business Administration (B.B.A)">Bachelor of Business Administration (B.B.A)</option>
                                    <option value="Bachelor of Engineering (B.Eng. or B.S.E)">Bachelor of Engineering (B.Eng. or B.S.E)</option>
                                    <option value="Bachelor of Education (B.Ed.)">Bachelor of Education (B.Ed.)</option>
                                    <option value="Bachelor of Medicine, Bachelor of Surgery (M.B.B.S)">Bachelor of Medicine, Bachelor of Surgery (M.B.B.S)</option>
                                    <option value="Bachelor of Laws (LL.B.)">Bachelor of Laws (LL.B.)</option>
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
                                <input type="text" class="form-control height-35 f-14" name="graduation_percentage" id="graduation_percentage">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="graduation_passing_year" fieldLabel="Passing Year">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="graduation_passing_year" id="graduation_passing_year">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="graduation_trial" fieldLabel="Trial">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="graduation_trial" id="graduation_trial">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="graduation_result_file" fieldLabel="Add Graduation Result">
                                </x-forms.label>
                                <input class="form-control" type="file" id="graduation_result_file" name="graduation_result_file">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Post Graduation Degree Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">Post Graduation Degree Details</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="post_graduation_degree" fieldLabel="Post Graduation Degree">
                                </x-forms.label>
                                <select class="form-control select-picker" name="post_graduation_degree" id="post_graduation_degree">
                                    <option value="">@lang('app.select')</option>
                                    <option value="Masters of Arts (M.A.)">Masters of Arts (M.A.)</option>
                                    <option value="Master of Science (M.S.)">Master of Science (M.S.)</option>
                                    <option value="Master of Business Administration (M.B.A)">Master of Business Administration (M.B.A)</option>
                                    <option value="Master of Education (M.Ed.)">Master of Education (M.Ed.)</option>
                                    <option value="Master of Fine Arts (M.F.A)">Master of Fine Arts (M.F.A)</option>
                                    <option value="Master of Public Health (M.P.H.)">Master of Public Health (M.P.H.)</option>
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
                                <input type="text" class="form-control height-35 f-14" name="post_graduation_percentage" id="post_graduation_percentage">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="post_graduation_passing_year" fieldLabel="Passing Year">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="post_graduation_passing_year" id="post_graduation_passing_year">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="post_graduation_trial" fieldLabel="Trial">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="post_graduation_trial" id="post_graduation_trial">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="post_graduation_result_file" fieldLabel="Add Post Graduation Result">
                                </x-forms.label>
                                <input class="form-control" type="file" id="post_graduation_result_file" name="post_graduation_result_file">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Other Degree Details Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">Other Degree Details</h6>
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
                                <input type="text" class="form-control height-35 f-14" name="other_degree_percentage" id="other_degree_percentage">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_degree_passing_year" fieldLabel="Passing Year">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="other_degree_passing_year" id="other_degree_passing_year">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_degree_trial" fieldLabel="Trial">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="other_degree_trial" id="other_degree_trial">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="other_degree_result_file" fieldLabel="Add Other Degree Result">
                                </x-forms.label>
                                <input class="form-control" type="file" id="other_degree_result_file" name="other_degree_result_file">
                            </div>
                            <div class="col-md-3 mt-3">
                                <button type="button" class="btn btn-secondary btn-sm" id="add-more-education">
                                    <i class="fa fa-plus mr-1"></i>Add More
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Professional Experience Tab -->
                    <div class="tab-pane fade" id="nav-experience" role="tabpanel" aria-labelledby="nav-experience-tab">
                        <!-- Job 1 Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">Job 1</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_duration_from" fieldLabel="Duration - From">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="job_duration_from" id="job_duration_from">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_duration_to" fieldLabel="Duration - To">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="job_duration_to" id="job_duration_to">
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
                                <input type="text" class="form-control height-35 f-14" name="job_salary" id="job_salary">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_offer_letter_file" fieldLabel="Add Offerletter">
                                </x-forms.label>
                                <input class="form-control" type="file" id="job_offer_letter_file" name="job_offer_letter_file">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="job_experience_letter_file" fieldLabel="Add Experience letter">
                                </x-forms.label>
                                <input class="form-control" type="file" id="job_experience_letter_file" name="job_experience_letter_file">
                            </div>
                            <div class="col-md-3 mt-3">
                                <button type="button" class="btn btn-secondary btn-sm" id="add-more-job">
                                    <i class="fa fa-plus mr-1"></i>Add More Job
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Property Details Tab -->
                    <div class="tab-pane fade" id="nav-property" role="tabpanel" aria-labelledby="nav-property-tab">
                        <p class="small-text mt-2 mb-3">Enter valuation for each property type. Total valuation will be calculated automatically.</p>
                        
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
                                <input type="number" id="total_valuation" class="form-control height-35 f-14" readonly placeholder="Auto calculated" name="total_valuation">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Loan Info Section -->
                        <h6 class="mb-3 f-15 font-weight-bold">Loan Information</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="total_loan_value" fieldLabel="Total Loan Value">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="total_loan_value" id="total_loan_value">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="loan_years" fieldLabel="Loan Years">
                                </x-forms.label>
                                <input type="number" class="form-control height-35 f-14" name="loan_years" id="loan_years">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="loan_availed_on" fieldLabel="Loan Availed On">
                                </x-forms.label>
                                <input type="date" class="form-control height-35 f-14" name="loan_availed_on" id="loan_availed_on">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="valuation_report_file" fieldLabel="Add Valuation Report">
                                </x-forms.label>
                                <input class="form-control" type="file" id="valuation_report_file" name="valuation_report_file">
                            </div>
                        </div>
                    </div>
                    <!-- Financial Status Tab -->
                    <div class="tab-pane fade" id="nav-financial" role="tabpanel" aria-labelledby="nav-financial-tab">
                        <p class="small-text mt-2 mb-3">Enter Income for each users. Total income will be calculated automatically.</p>
                        
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
                                <input class="form-control" type="file" id="father_income_document_file" name="father_income_document_file">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="mother_income_document_file" fieldLabel="Add Mother's Income Document">
                                </x-forms.label>
                                <input class="form-control" type="file" id="mother_income_document_file" name="mother_income_document_file">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="candidate_income_document_file" fieldLabel="Add Candidate's Income Document">
                                </x-forms.label>
                                <input class="form-control" type="file" id="candidate_income_document_file" name="candidate_income_document_file">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="spouse_income_document_file" fieldLabel="Add Spouse Income Document">
                                </x-forms.label>
                                <input class="form-control" type="file" id="spouse_income_document_file" name="spouse_income_document_file">
                            </div>
                            <div class="col-md-3">
                                <x-forms.label class="mt-3" fieldId="total_income" fieldLabel="Total Income">
                                </x-forms.label>
                                <input type="number" id="total_income" class="form-control height-35 f-14" readonly placeholder="Auto calculated" name="total_income">
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
                            @lang('app.save') @lang('app.and') @lang('app.next')
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
            $('.select-picker').selectpicker();

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
                if ($(this).val() === 'granted') {
                    $('#visa_rejection_date, #visa_refusal_category, #visa_refusal_reason').closest('.col-md-3, .col-md-12').hide();
                    $('#visa_issue_date, #visa_expire_date, #visa_category').closest('.col-md-3').show();
                } else if ($(this).val() === 'refusal') {
                    $('#visa_issue_date, #visa_expire_date, #visa_category').closest('.col-md-3').hide();
                    $('#visa_rejection_date, #visa_refusal_category, #visa_refusal_reason').closest('.col-md-3, .col-md-12').show();
                } else {
                    // If neither is selected, show all fields
                    $('#visa_issue_date, #visa_expire_date, #visa_category, #visa_rejection_date, #visa_refusal_category, #visa_refusal_reason').closest('.col-md-3, .col-md-12').show();
                }
            });

            // Add more visa refusal functionality
            let visaRefusalCount = 0;
            $('#add-more-visa-refusal').on('click', function() {
                visaRefusalCount++;
                const newRow = `
                    <div class="row mt-3 visa-refusal-row" id="visa-refusal-row-${visaRefusalCount}">
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="visa_rejection_date_${visaRefusalCount}">Visa Rejection Date</label>
                            <input type="month" class="form-control height-35 f-14" name="visa_rejection_date[]" id="visa_rejection_date_${visaRefusalCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="visa_refusal_category_${visaRefusalCount}">Visa Category</label>
                            <input type="text" class="form-control height-35 f-14" name="visa_refusal_category[]" id="visa_refusal_category_${visaRefusalCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="visa_refusal_reason_${visaRefusalCount}">Reason</label>
                            <textarea class="form-control f-14" rows="2" name="visa_refusal_reason[]" id="visa_refusal_reason_${visaRefusalCount}"></textarea>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-visa-refusal" data-row-id="${visaRefusalCount}">
                                <i class="fa fa-trash mr-1"></i>Remove
                            </button>
                        </div>
                    </div>
                `;
                $(this).closest('.row').after(newRow);
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

            // Tab navigation - Previous button
            $('#btn-previous').on('click', function() {
                const activeTab = $('.nav-link.active');
                const prevTab = activeTab.parent().prev().find('.nav-link');
                if (prevTab.length) {
                    prevTab.tab('show');
                }
            });

            // Handle visa type radio buttons for Client Preference tab
            $('input[name="visa_type"]').on('change', function() {
                const selectedValue = $(this).val();
                
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
                $('.select-picker').selectpicker('refresh');
            });

            // Add More Relative Contact functionality
            let relativeContactCount = 0;
            $('#add-more-relative').on('click', function() {
                relativeContactCount++;
                const newRow = `
                    <div class="row mt-3 relative-contact-row" id="relative-contact-row-${relativeContactCount}">
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="relative_surname_${relativeContactCount}">Surname</label>
                            <input type="text" class="form-control height-35 f-14" name="relative_surname[]" id="relative_surname_${relativeContactCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="relative_given_name_${relativeContactCount}">Given Name</label>
                            <input type="text" class="form-control height-35 f-14" name="relative_given_name[]" id="relative_given_name_${relativeContactCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="relative_organization_name_${relativeContactCount}">Organization Name</label>
                            <input type="text" class="form-control height-35 f-14" name="relative_organization_name[]" id="relative_organization_name_${relativeContactCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="relative_relationship_${relativeContactCount}">Relationship To You</label>
                            <input type="text" class="form-control height-35 f-14" name="relative_relationship[]" id="relative_relationship_${relativeContactCount}">
                        </div>
                        <div class="col-md-12">
                            <label class="f-14 font-weight-bold mt-3" for="relative_contact_address_${relativeContactCount}">Contact Address</label>
                            <input type="text" class="form-control height-35 f-14" name="relative_contact_address[]" id="relative_contact_address_${relativeContactCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="relative_city_${relativeContactCount}">City</label>
                            <input type="text" class="form-control height-35 f-14" name="relative_city[]" id="relative_city_${relativeContactCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="relative_state_${relativeContactCount}">State</label>
                            <input type="text" class="form-control height-35 f-14" name="relative_state[]" id="relative_state_${relativeContactCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="relative_zip_code_${relativeContactCount}">Zip Code</label>
                            <input type="text" class="form-control height-35 f-14" name="relative_zip_code[]" id="relative_zip_code_${relativeContactCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="relative_email_address_${relativeContactCount}">Email Address</label>
                            <input type="email" class="form-control height-35 f-14" name="relative_email_address[]" id="relative_email_address_${relativeContactCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="relative_phone_number_${relativeContactCount}">Phone Number</label>
                            <input type="tel" maxlength="10" class="form-control height-35 f-14" name="relative_phone_number[]" id="relative_phone_number_${relativeContactCount}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-relative-contact" data-row-id="${relativeContactCount}">
                                <i class="fa fa-trash mr-1"></i>Remove
                            </button>
                        </div>
                    </div>
                `;
                $(this).closest('.row').after(newRow);
            });

            // Remove relative contact row
            $(document).on('click', '.remove-relative-contact', function() {
                const rowId = $(this).data('row-id');
                $(`#relative-contact-row-${rowId}`).remove();
            });

            // Add More Child functionality
            let childCount = 1; // Start from 1 since we already have Child 1
            $('#add-more-child').on('click', function() {
                childCount++;
                const newRow = `
                    <div class="row mt-3 child-row" id="child-row-${childCount}">
                        <div class="col-md-12">
                            <h6 class="mb-3 f-15 font-weight-bold">Child ${childCount}</h6>
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="child_name_${childCount}">Child's Name</label>
                            <input type="text" class="form-control height-35 f-14" name="child_name[]" id="child_name_${childCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="child_age_${childCount}">Child's Age</label>
                            <input type="text" class="form-control height-35 f-14" name="child_age[]" id="child_age_${childCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="child_date_of_birth_${childCount}">Date of Birth</label>
                            <input type="date" class="form-control height-35 f-14" name="child_date_of_birth[]" id="child_date_of_birth_${childCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="child_city_of_birth_${childCount}">City of Birth</label>
                            <input type="text" class="form-control height-35 f-14" name="child_city_of_birth[]" id="child_city_of_birth_${childCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="child_have_passport_${childCount}">Have Passport</label>
                            <select class="form-control select-picker" name="child_have_passport[]" id="child_have_passport_${childCount}">
                                <option value="">@lang('app.select')</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="child_gender_${childCount}">Gender</label>
                            <select class="form-control select-picker" name="child_gender[]" id="child_gender_${childCount}">
                                <option value="">@lang('app.select')</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Prefer not to say">Prefer not to say</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="child_document_file_${childCount}">Add Child Document</label>
                            <input class="form-control" type="file" name="child_document_file[]" id="child_document_file_${childCount}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-child" data-row-id="${childCount}">
                                <i class="fa fa-trash mr-1"></i>Remove
                            </button>
                        </div>
                    </div>
                `;
                $(this).closest('.row').after(newRow);
                // Reinitialize select picker for the new row
                $('.select-picker').selectpicker('refresh');
            });

            // Remove child row
            $(document).on('click', '.remove-child', function() {
                const rowId = $(this).data('row-id');
                $(`#child-row-${rowId}`).remove();
            });

            // Add More Education (Other Degree) functionality
            let otherDegreeCount = 0;
            $('#add-more-education').on('click', function() {
                otherDegreeCount++;
                const newRow = `
                    <div class="row mt-3 other-degree-row" id="other-degree-row-${otherDegreeCount}">
                        <div class="col-md-12">
                            <h6 class="mb-3 f-15 font-weight-bold">Other Degree ${otherDegreeCount + 1}</h6>
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="other_degree_${otherDegreeCount}">Other Degree</label>
                            <input type="text" class="form-control height-35 f-14" name="other_degree[]" id="other_degree_${otherDegreeCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="other_degree_university_name_${otherDegreeCount}">University Name</label>
                            <input type="text" class="form-control height-35 f-14" name="other_degree_university_name[]" id="other_degree_university_name_${otherDegreeCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="other_degree_percentage_${otherDegreeCount}">Percentage</label>
                            <input type="text" class="form-control height-35 f-14" name="other_degree_percentage[]" id="other_degree_percentage_${otherDegreeCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="other_degree_passing_year_${otherDegreeCount}">Passing Year</label>
                            <input type="text" class="form-control height-35 f-14" name="other_degree_passing_year[]" id="other_degree_passing_year_${otherDegreeCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="other_degree_trial_${otherDegreeCount}">Trial</label>
                            <input type="text" class="form-control height-35 f-14" name="other_degree_trial[]" id="other_degree_trial_${otherDegreeCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="other_degree_result_file_${otherDegreeCount}">Add Other Degree Result</label>
                            <input class="form-control" type="file" name="other_degree_result_file[]" id="other_degree_result_file_${otherDegreeCount}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-other-degree" data-row-id="${otherDegreeCount}">
                                <i class="fa fa-trash mr-1"></i>Remove
                            </button>
                        </div>
                    </div>
                `;
                $(this).closest('.row').after(newRow);
            });

            // Remove other degree row
            $(document).on('click', '.remove-other-degree', function() {
                const rowId = $(this).data('row-id');
                $(`#other-degree-row-${rowId}`).remove();
            });

            // Add More Job functionality
            let jobCount = 1; // Start from 1 since we already have Job 1
            $('#add-more-job').on('click', function() {
                jobCount++;
                const newRow = `
                    <div class="row mt-3 job-row" id="job-row-${jobCount}">
                        <div class="col-md-12">
                            <h6 class="mb-3 f-15 font-weight-bold">Job ${jobCount}</h6>
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="job_duration_from_${jobCount}">Duration - From</label>
                            <input type="date" class="form-control height-35 f-14" name="job_duration_from[]" id="job_duration_from_${jobCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="job_duration_to_${jobCount}">Duration - To</label>
                            <input type="date" class="form-control height-35 f-14" name="job_duration_to[]" id="job_duration_to_${jobCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="job_country_${jobCount}">Country</label>
                            <input type="text" class="form-control height-35 f-14" name="job_country[]" id="job_country_${jobCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="job_designation_${jobCount}">Designation</label>
                            <input type="text" class="form-control height-35 f-14" name="job_designation[]" id="job_designation_${jobCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="job_company_name_${jobCount}">Company Name</label>
                            <input type="text" class="form-control height-35 f-14" name="job_company_name[]" id="job_company_name_${jobCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="job_salary_${jobCount}">Salary</label>
                            <input type="text" class="form-control height-35 f-14" name="job_salary[]" id="job_salary_${jobCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="job_offer_letter_file_${jobCount}">Add Offerletter</label>
                            <input class="form-control" type="file" name="job_offer_letter_file[]" id="job_offer_letter_file_${jobCount}">
                        </div>
                        <div class="col-md-3">
                            <label class="f-14 font-weight-bold mt-3" for="job_experience_letter_file_${jobCount}">Add Experience letter</label>
                            <input class="form-control" type="file" name="job_experience_letter_file[]" id="job_experience_letter_file_${jobCount}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-job" data-row-id="${jobCount}">
                                <i class="fa fa-trash mr-1"></i>Remove
                            </button>
                        </div>
                    </div>
                `;
                $(this).closest('.row').after(newRow);
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

        });
    </script>
@endpush

