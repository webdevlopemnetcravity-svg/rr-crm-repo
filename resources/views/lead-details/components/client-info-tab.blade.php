                <!-- Client Info Tab Content -->
                <div class="tab-content px-4 pb-4 active" id="clientInfoTab">
                    @php
                        // Extract step data
                        $step1Data = isset($lead) && $lead ? ($lead->step_1_data ?? []) : [];
                        $step2Data = isset($lead) && $lead ? ($lead->step_2_data ?? []) : [];
                        $step3Data = isset($lead) && $lead ? ($lead->step_3_data ?? []) : [];
                        $step4Data = isset($lead) && $lead ? ($lead->step_4_data ?? []) : [];
                        $step5Data = isset($lead) && $lead ? ($lead->step_5_data ?? []) : [];
                        $step6Data = isset($lead) && $lead ? ($lead->step_6_data ?? []) : [];
                        $step7Data = isset($lead) && $lead ? ($lead->step_7_data ?? []) : [];
                        $step8Data = isset($lead) && $lead ? ($lead->step_8_data ?? []) : [];
                        $step9Data = isset($lead) && $lead ? ($lead->step_9_data ?? []) : [];
                        
                        // Helper function to format date
                        $formatDate = function($date) {
                            if (empty($date)) return '-';
                            try {
                                if (is_string($date)) {
                                    $dateObj = \Carbon\Carbon::parse($date);
                                    return $dateObj->format('d-M-Y');
                                }
                                return $date->format('d-M-Y');
                            } catch (\Exception $e) {
                                return $date;
                            }
                        };
                        
                        // Helper function to get value or default
                        $getValue = function($value, $default = '-') {
                            return !empty($value) ? $value : $default;
                        };
                    @endphp
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
                        <div style="background-color: #f6f8fb; border: 1px solid #B5B5B5; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                            <!-- Candidate Information Section -->
                            <div class="info-section mb-1">
                                <div class="info-section-header mb-3">
                                    <h4 class="info-section-title-text">Candidate Information</h4>
                                    <div class="info-section-divider"></div>
                                </div>
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Surname</div>
                                        <div class="info-field-value-text">{{ $getValue($step1Data['surname'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Given Name</div>
                                        <div class="info-field-value-text">{{ $getValue($step1Data['given_name'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Date of Birth</div>
                                        <div class="info-field-value-text">{{ $formatDate($step1Data['date_of_birth'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Gender</div>
                                        <div class="info-field-value-text">{{ $getValue($step1Data['gender'] ?? null) }}</div>
                                    </div>
                                </div>
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Marital Status</div>
                                        <div class="info-field-value-text">{{ $getValue($step1Data['marital_status'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Visa Expiry Date</div>
                                        <div class="info-field-value-text">{{ $formatDate($step1Data['visa_expire_date'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Passport Number</div>
                                        <div class="info-field-value-text">{{ $getValue($step3Data['passport_number'] ?? null) }}</div>
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
                                        <div class="info-field-value-text">{{ $getValue($step1Data['primary_phone'] ?? $lead->mobile ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Secondary Phone</div>
                                        <div class="info-field-value-text">{{ $getValue($step1Data['secondary_phone'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Work Phone</div>
                                        <div class="info-field-value-text">{{ $getValue($step1Data['work_phone'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Other Phone Number Used in Last Five Years</div>
                                        <div class="info-field-value-text">{{ $getValue($step1Data['other_phone'] ?? null) }}</div>
                                    </div>
                                </div>
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Email</div>
                                        <div class="info-field-value-text">{{ $getValue($step1Data['email_address'] ?? $lead->client_email ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Other Email Used in Last Five Years</div>
                                        <div class="info-field-value-text">{{ $getValue($step1Data['other_email'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Home Address</div>
                                        <div class="info-field-value-text">
                                            @php
                                                $homeAddress = '';
                                                if (!empty($step1Data['home_address'])) {
                                                    $homeAddress = $step1Data['home_address'];
                                                    if (!empty($step1Data['home_city'])) $homeAddress .= ', ' . $step1Data['home_city'];
                                                    if (!empty($step1Data['home_state'])) $homeAddress .= ', ' . $step1Data['home_state'];
                                                    if (!empty($step1Data['home_pin_code'])) $homeAddress .= ', ' . $step1Data['home_pin_code'];
                                                    if (!empty($step1Data['country_of_origin'])) $homeAddress .= ', ' . $step1Data['country_of_origin'];
                                                }
                                            @endphp
                                            {{ $getValue($homeAddress) }}
                                        </div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Mailing Address</div>
                                        <div class="info-field-value-text">
                                            @php
                                                $mailingAddress = '';
                                                if (!empty($step1Data['mailing_address'])) {
                                                    $mailingAddress = $step1Data['mailing_address'];
                                                    if (!empty($step1Data['mailing_city'])) $mailingAddress .= ', ' . $step1Data['mailing_city'];
                                                    if (!empty($step1Data['mailing_state'])) $mailingAddress .= ', ' . $step1Data['mailing_state'];
                                                    if (!empty($step1Data['mailing_pin_code'])) $mailingAddress .= ', ' . $step1Data['mailing_pin_code'];
                                                } elseif (!empty($step1Data['mailing_same_as_home']) && $step1Data['mailing_same_as_home'] == '1') {
                                                    $mailingAddress = $homeAddress;
                                                }
                                            @endphp
                                            {{ $getValue($mailingAddress) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Linkedin Link</div>
                                        <div class="info-field-value-text">{{ $getValue($step1Data['linkedin_profile_url'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Facebook Link</div>
                                        <div class="info-field-value-text">{{ $getValue($step1Data['facebook_profile_url'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Instagram Link</div>
                                        <div class="info-field-value-text">{{ $getValue($step1Data['instagram_profile_url'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Sub Agent</div>
                                        <div class="info-field-value-text">{{ $getValue($step1Data['sub_agent'] ?? null) }}</div>
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
                                    <div class="info-field-value-text">{{ $getValue($lead->lead_source ?? $step1Data['lead_source'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Lead Added by</div>
                                    <div class="info-field-value-text">{{ $getValue($lead->addedBy->name ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Lead Assign to</div>
                                    <div class="info-field-value-text">{{ $getValue($lead->leadOwner->name ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Country Of Origin (Nationality)</div>
                                    <div class="info-field-value-text">{{ $getValue($step1Data['country_of_origin'] ?? null) }}</div>
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
                                    <div class="info-field-value-text">{{ $getValue($step1Data['visa_status'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Visa Issue Date</div>
                                    <div class="info-field-value-text">{{ $formatDate($step1Data['visa_issue_date'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Visa Expiry Date</div>
                                    <div class="info-field-value-text">{{ $formatDate($step1Data['visa_expire_date'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Visa Category</div>
                                    <div class="info-field-value-text">{{ $getValue($step1Data['visa_category'] ?? null) }}</div>
                                </div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Languages Spoken</div>
                                    <div class="info-field-value-text">{{ $getValue($step1Data['languages_spoken'] ?? null) }}</div>
                                </div>
                            </div>
                            @php
                                $visaRefusals = $step1Data['visa_refusals'] ?? [];
                            @endphp
                            @if(count($visaRefusals) > 0)
                                @foreach($visaRefusals as $index => $refusal)
                                    <div class="info-subsection-title mb-2">
                                        <h5 class="info-subsection-title-text">Visa Refusal {{ $index + 1 }}</h5>
                                    </div>
                                    <div class="info-grid-row row">
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Date</div>
                                            <div class="info-field-value-text">{{ $getValue($refusal['date'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Category</div>
                                            <div class="info-field-value-text">{{ $getValue($refusal['category'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-6 mb-3">
                                            <div class="info-field-label-text">Reason</div>
                                            <div class="info-field-value-text">{{ $getValue($refusal['reason'] ?? null) }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Client Preference Section -->
                        <div class="info-section mb-1">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Client Preference</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            @php
                                $visaType = $step2Data['visa_type'] ?? null;
                            @endphp
                            @if($visaType == 'pr')
                                <!-- PR Visa Details -->
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Visa Type</div>
                                        <div class="info-field-value-text">PR (Permanent Residence)</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Skill Assessment Letter</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['skill_assessment_letter'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Preferred Country</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['pr_preferred_country'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Preferred State</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['pr_preferred_state'] ?? null) }}</div>
                                    </div>
                                </div>
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Family Type</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['pr_family'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-9 mb-3">
                                        <div class="info-field-label-text">Subclass</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['pr_subclass'] ?? null) }}</div>
                                    </div>
                                </div>
                            @elseif($visaType == 'visit')
                                <!-- Visit Visa Details -->
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Visa Type</div>
                                        <div class="info-field-value-text">Visit Visa</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Purpose of Visit</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['purpose_of_visit'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Preferred Country</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['visit_preferred_country'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Preferred State</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['visit_preferred_state'] ?? null) }}</div>
                                    </div>
                                </div>
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Family Type</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['visit_family'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-9 mb-3">
                                        <div class="info-field-label-text">Subclass</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['visit_subclass'] ?? null) }}</div>
                                    </div>
                                </div>
                            @elseif($visaType == 'work')
                                <!-- Work Visa Details -->
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Visa Type</div>
                                        <div class="info-field-value-text">Work Visa</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Preferred Designation</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['preferred_designation'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Industry</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['industry'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Role</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['on_role_off_role'] ?? null) }}</div>
                                    </div>
                                </div>
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Preferred Country</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['work_preferred_country'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Preferred State</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['work_preferred_state'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Work Category</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['work_category'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Subclass</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['work_subclass'] ?? null) }}</div>
                                    </div>
                                </div>
                            @elseif($visaType == 'student')
                                <!-- Student Visa Details -->
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Visa Type</div>
                                        <div class="info-field-value-text">Student Visa</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Preferred Course</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['preferred_course'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Country</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['student_country'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">University</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['university'] ?? null) }}</div>
                                    </div>
                                </div>
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-3 mb-3">
                                        <div class="info-field-label-text">Term Intake</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['term_intake'] ?? null) }}</div>
                                    </div>
                                    <div class="info-field-item col-md-9 mb-3">
                                        <div class="info-field-label-text">Subclass</div>
                                        <div class="info-field-value-text">{{ $getValue($step2Data['student_subclass'] ?? null) }}</div>
                                    </div>
                                </div>
                            @else
                                <!-- Default/Unknown Visa Type -->
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-12 mb-3">
                                        <div class="info-field-value-text">-</div>
                                    </div>
                                </div>
                            @endif
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
                                    <div class="info-field-value-text">{{ $getValue($step3Data['passport_number'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Issuing Country</div>
                                    <div class="info-field-value-text">{{ $getValue($step3Data['issuing_country'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">City Where Issued</div>
                                    <div class="info-field-value-text">{{ $getValue($step3Data['city_where_issued'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Issuance Date</div>
                                    <div class="info-field-value-text">{{ $formatDate($step3Data['issuance_date'] ?? null) }}</div>
                                </div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Expiration Date</div>
                                    <div class="info-field-value-text">{{ $formatDate($step3Data['expiration_date'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-9 mb-3">
                                    <div class="info-field-label-text">Lost Passport History</div>
                                    <div class="info-field-value-text">{{ $getValue($step3Data['lost_passport_history'] ?? null) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Relative Contact Information Section -->
                        <div class="info-section mb-1">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Relative Contact Information</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            @php
                                $relatives = $step4Data['relative_contacts'] ?? [];
                            @endphp
                            @if(count($relatives) > 0)
                                @foreach($relatives as $index => $relative)
                                    <div class="info-subsection-title mb-2">
                                        <h5 class="info-subsection-title-text">Relative Contact {{ $index + 1 }}</h5>
                                    </div>
                                    <div class="info-grid-row row">
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Surname</div>
                                            <div class="info-field-value-text">{{ $getValue($relative['relative_surname'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Given Name</div>
                                            <div class="info-field-value-text">{{ $getValue($relative['relative_given_name'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Organization Name</div>
                                            <div class="info-field-value-text">{{ $getValue($relative['relative_organization_name'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Relationship To You</div>
                                            <div class="info-field-value-text">{{ $getValue($relative['relative_relationship'] ?? null) }}</div>
                                        </div>
                                    </div>
                                    <div class="info-grid-row row">
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Contact Address</div>
                                            <div class="info-field-value-text">
                                                @php
                                                    $relativeAddress = '';
                                                    if (!empty($relative['relative_contact_address'])) {
                                                        $relativeAddress = $relative['relative_contact_address'];
                                                        if (!empty($relative['relative_city'])) $relativeAddress .= ', ' . $relative['relative_city'];
                                                        if (!empty($relative['relative_state'])) $relativeAddress .= ', ' . $relative['relative_state'];
                                                        if (!empty($relative['relative_zip_code'])) $relativeAddress .= ', ' . $relative['relative_zip_code'];
                                                    }
                                                @endphp
                                                {{ $getValue($relativeAddress) }}
                                            </div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Email</div>
                                            <div class="info-field-value-text">{{ $getValue($relative['relative_email_address'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Phone Number</div>
                                            <div class="info-field-value-text">{{ $getValue($relative['relative_phone_number'] ?? null) }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="info-subsection-title mb-2">
                                    <h5 class="info-subsection-title-text">Relative Contact 1</h5>
                                </div>
                                <div class="info-grid-row row">
                                    <div class="info-field-item col-md-12 mb-3">
                                        <div class="info-field-value-text">-</div>
                                    </div>
                                </div>
                            @endif
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
                                    <div class="info-field-value-text">{{ $getValue($step5Data['father_surname'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Father's Given Name</div>
                                    <div class="info-field-value-text">{{ $getValue($step5Data['father_given_name'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Father's Date of Birth</div>
                                    <div class="info-field-value-text">{{ $formatDate($step5Data['father_date_of_birth'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Father's Occupation</div>
                                    <div class="info-field-value-text">{{ $getValue($step5Data['father_occupation'] ?? null) }}</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Have Passport</div>
                                    <div class="info-field-value-text">{{ $getValue($step5Data['father_have_passport'] ?? null) }}</div>
                                </div>
                            </div>

                            <!-- Mother Details -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Mother's Details</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Mother's Surname</div>
                                    <div class="info-field-value-text">{{ $getValue($step5Data['mother_surname'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Mother's Given Name</div>
                                    <div class="info-field-value-text">{{ $getValue($step5Data['mother_given_name'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Mother's Date of Birth</div>
                                    <div class="info-field-value-text">{{ $formatDate($step5Data['mother_date_of_birth'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Mother's Occupation</div>
                                    <div class="info-field-value-text">{{ $getValue($step5Data['mother_occupation'] ?? null) }}</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Have Passport</div>
                                    <div class="info-field-value-text">{{ $getValue($step5Data['mother_have_passport'] ?? null) }}</div>
                                </div>
                            </div>

                            <!-- Spouse Details -->
                            @if(!empty($step5Data['spouse_surname']) || !empty($step5Data['spouse_given_name']))
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Spouse Details</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's Surname</div>
                                    <div class="info-field-value-text">{{ $getValue($step5Data['spouse_surname'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's Given Name</div>
                                    <div class="info-field-value-text">{{ $getValue($step5Data['spouse_given_name'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's Date of Birth</div>
                                    <div class="info-field-value-text">{{ $formatDate($step5Data['spouse_date_of_birth'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's Country</div>
                                    <div class="info-field-value-text">{{ $getValue($step5Data['spouse_country'] ?? null) }}</div>
                                </div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's City of Birth</div>
                                    <div class="info-field-value-text">{{ $getValue($step5Data['spouse_city_of_birth'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Have Passport</div>
                                    <div class="info-field-value-text">{{ $getValue($step5Data['spouse_have_passport'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's Address</div>
                                    <div class="info-field-value-text">
                                        @php
                                            $spouseAddress = '';
                                            if (!empty($step5Data['spouse_address'])) {
                                                $spouseAddress = $step5Data['spouse_address'];
                                                if (!empty($step5Data['spouse_city'])) $spouseAddress .= ', ' . $step5Data['spouse_city'];
                                                if (!empty($step5Data['spouse_state'])) $spouseAddress .= ', ' . $step5Data['spouse_state'];
                                                if (!empty($step5Data['spouse_postal_code'])) $spouseAddress .= ', ' . $step5Data['spouse_postal_code'];
                                            }
                                        @endphp
                                        {{ $getValue($spouseAddress) }}
                                    </div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's Phone Number</div>
                                    <div class="info-field-value-text">{{ $getValue($step5Data['spouse_phone_number'] ?? null) }}</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's Education</div>
                                    <div class="info-field-value-text">{{ $getValue($step5Data['spouse_education'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's Occupation</div>
                                    <div class="info-field-value-text">{{ $getValue($step5Data['spouse_occupation'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse's Yearly Income</div>
                                    <div class="info-field-value-text">{{ $getValue($step5Data['spouse_yearly_income'] ?? null) }}</div>
                                </div>
                            </div>
                            @endif

                            <!-- Children Details -->
                            @php
                                $children = $step5Data['children'] ?? [];
                            @endphp
                            @if(count($children) > 0)
                                @foreach($children as $index => $child)
                                    <div class="info-subsection-title mb-2">
                                        <h5 class="info-subsection-title-text">Child {{ $index + 1 }}</h5>
                                    </div>
                                    <div class="info-grid-row row">
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Child's Name</div>
                                            <div class="info-field-value-text">{{ $getValue($child['child_name'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Child's Age</div>
                                            <div class="info-field-value-text">{{ $getValue($child['child_age'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Date of Birth</div>
                                            <div class="info-field-value-text">{{ $formatDate($child['child_date_of_birth'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">City of Birth</div>
                                            <div class="info-field-value-text">{{ $getValue($child['child_city_of_birth'] ?? null) }}</div>
                                        </div>
                                    </div>
                                    <div class="info-grid-row row">
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Have Passport</div>
                                            <div class="info-field-value-text">{{ $getValue($child['child_have_passport'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Gender</div>
                                            <div class="info-field-value-text">{{ $getValue($child['child_gender'] ?? null) }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Education Information Section -->
                        <div class="info-section mb-1">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Education Information</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            
                            <!-- IELTS/PTE/OET/TOEFL Exam Details -->
                            @if(!empty($step6Data['ielts_clear_or_not']))
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">IELTS/PTE/OET/TOEFL Exam Details</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Exam Type</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['ielts_clear_or_not'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Passing Year</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['ielts_passing_year'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Score</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['ielts_score'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Trial</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['ielts_trial'] ?? null) }}</div>
                                </div>
                            </div>
                            @endif

                            <!-- 10th Exam Details -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">10th Exam Details</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Passing Year</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['tenth_passing_year'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Percentage</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['tenth_percentage'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Board Name</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['tenth_board_name'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Trial</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['tenth_trial'] ?? null) }}</div>
                                </div>
                            </div>

                            <!-- 12th Exam Details -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">12th Exam Details</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Passing Year</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['twelfth_passing_year'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Stream</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['twelfth_stream'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Percentage</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['twelfth_percentage'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Board Name</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['twelfth_board_name'] ?? null) }}</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Trial</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['twelfth_trial'] ?? null) }}</div>
                                </div>
                            </div>

                            <!-- Graduation Degree Details -->
                            @if(!empty($step6Data['graduation_degree']))
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Graduation Degree Details</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Degree</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['graduation_degree'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">University Name</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['graduation_university_name'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Percentage</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['graduation_percentage'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Passing Year</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['graduation_passing_year'] ?? null) }}</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Trial</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['graduation_trial'] ?? null) }}</div>
                                </div>
                            </div>
                            @endif

                            <!-- Post Graduation Degree Details -->
                            @if(!empty($step6Data['post_graduation_degree']))
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Post Graduation Degree Details</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Degree</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['post_graduation_degree'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">University Name</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['post_graduation_university_name'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Percentage</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['post_graduation_percentage'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Passing Year</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['post_graduation_passing_year'] ?? null) }}</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Trial</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['post_graduation_trial'] ?? null) }}</div>
                                </div>
                            </div>
                            @endif

                            <!-- Other Degree Details -->
                            @php
                                $otherDegrees = $step6Data['other_degrees'] ?? [];
                            @endphp
                            @if(count($otherDegrees) > 0)
                                @foreach($otherDegrees as $index => $degree)
                                    <div class="info-subsection-title mb-2">
                                        <h5 class="info-subsection-title-text">Other Degree {{ $index + 1 }}</h5>
                                    </div>
                                    <div class="info-grid-row row">
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Degree</div>
                                            <div class="info-field-value-text">{{ $getValue($degree['other_degree'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Institution Name</div>
                                            <div class="info-field-value-text">{{ $getValue($degree['other_degree_university_name'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Percentage</div>
                                            <div class="info-field-value-text">{{ $getValue($degree['other_degree_percentage'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Passing Year</div>
                                            <div class="info-field-value-text">{{ $getValue($degree['other_degree_passing_year'] ?? null) }}</div>
                                        </div>
                                    </div>
                                    <div class="info-grid-row row mb-4">
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Trial</div>
                                            <div class="info-field-value-text">{{ $getValue($degree['other_degree_trial'] ?? null) }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Professional Experience Section -->
                        <div class="info-section mb-1">
                            <div class="info-section-header mb-3">
                                <h4 class="info-section-title-text">Professional Experience</h4>
                                <div class="info-section-divider"></div>
                            </div>
                            
                            @php
                                $jobs = $step7Data['jobs'] ?? [];
                            @endphp
                            @if(count($jobs) > 0)
                                @foreach($jobs as $index => $job)
                                    <div class="info-subsection-title mb-2">
                                        <h5 class="info-subsection-title-text">Experience {{ $index + 1 }}</h5>
                                    </div>
                                    <div class="info-grid-row row">
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Duration - From</div>
                                            <div class="info-field-value-text">{{ $formatDate($job['job_duration_from'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Duration - To</div>
                                            <div class="info-field-value-text">{{ $formatDate($job['job_duration_to'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Country</div>
                                            <div class="info-field-value-text">{{ $getValue($job['job_country'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Designation</div>
                                            <div class="info-field-value-text">{{ $getValue($job['job_designation'] ?? null) }}</div>
                                        </div>
                                    </div>
                                    <div class="info-grid-row row">
                                        <div class="info-field-item col-md-6 mb-3">
                                            <div class="info-field-label-text">Company Name</div>
                                            <div class="info-field-value-text">{{ $getValue($job['job_company_name'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Salary</div>
                                            <div class="info-field-value-text">{{ $getValue($job['job_salary'] ?? null) }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
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
                                    <div class="info-field-value-text">{{ $getValue($step8Data['property_home'] ? '₹ ' . number_format($step8Data['property_home']) : null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Land</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['property_land'] ? '₹ ' . number_format($step8Data['property_land']) : null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Plot</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['property_plot'] ? '₹ ' . number_format($step8Data['property_plot']) : null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Commercials</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['property_commercials'] ? '₹ ' . number_format($step8Data['property_commercials']) : null) }}</div>
                                </div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Other</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['property_other'] ? '₹ ' . number_format($step8Data['property_other']) : null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Shop</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['property_shop'] ? '₹ ' . number_format($step8Data['property_shop']) : null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Gold</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['property_gold'] ? '₹ ' . number_format($step8Data['property_gold']) : null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Silver</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['property_silver'] ? '₹ ' . number_format($step8Data['property_silver']) : null) }}</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Total Asset Valuation</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['total_valuation'] ? '₹ ' . number_format($step8Data['total_valuation']) : null) }}</div>
                                </div>
                            </div>

                            <!-- Loan Information -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Loan Information</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Total Loan Value</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['total_loan_value'] ? '₹ ' . number_format($step8Data['total_loan_value']) : null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Loan Years</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['loan_years'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Loan Availed On</div>
                                    <div class="info-field-value-text">{{ $formatDate($step8Data['loan_availed_on'] ?? null) }}</div>
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
                            @php
                                $fatherIncome = $step9Data['father_income'] ?? 0;
                                $motherIncome = $step9Data['mother_income'] ?? 0;
                                $candidateIncome = $step9Data['candidate_income'] ?? 0;
                                $spouseIncome = $step9Data['spouse_income'] ?? 0;
                                $totalIncome = $step9Data['total_income'] ?? ($fatherIncome + $motherIncome + $candidateIncome + $spouseIncome);
                            @endphp
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Father's Income</div>
                                    <div class="info-field-value-text">{{ $getValue($fatherIncome ? '₹ ' . number_format($fatherIncome) : null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Mother's Income</div>
                                    <div class="info-field-value-text">{{ $getValue($motherIncome ? '₹ ' . number_format($motherIncome) : null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Candidate's Income</div>
                                    <div class="info-field-value-text">{{ $getValue($candidateIncome ? '₹ ' . number_format($candidateIncome) : null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Spouse Income</div>
                                    <div class="info-field-value-text">{{ $getValue($spouseIncome ? '₹ ' . number_format($spouseIncome) : null) }}</div>
                                </div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Total Income</div>
                                    <div class="info-field-value-text">{{ $getValue($totalIncome ? '₹ ' . number_format($totalIncome) : null) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
