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
                                    <div class="info-field-label-text">Visa Rejection Date</div>
                                    <div class="info-field-value-text">{{ $formatDate($step1Data['visa_rejection_date'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Visa Category</div>
                                    <div class="info-field-value-text">{{ $getValue($step1Data['visa_category'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Reason</div>
                                    <div class="info-field-value-text">{{ $getValue($step1Data['visa_refusal_reason'] ?? null) }}</div>
                                </div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Languages Spoken</div>
                                    <div class="info-field-value-text">{{ $getValue($step1Data['languages_spoken'] ?? null) }}</div>
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
                                    <div class="info-field-value-text">{{ $getValue($step2Data['preferred_designation'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Industry</div>
                                    <div class="info-field-value-text">{{ $getValue($step2Data['industry'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Role</div>
                                    <div class="info-field-value-text">{{ $getValue($step2Data['role'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Preferred Country</div>
                                    <div class="info-field-value-text">{{ $getValue($step2Data['preferred_country'] ?? null) }}</div>
                                </div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Work Category</div>
                                    <div class="info-field-value-text">{{ $getValue($step2Data['work_category'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-9 mb-3">
                                    <div class="info-field-label-text">Subclass</div>
                                    <div class="info-field-value-text">
                                        @php
                                            $subclass = '-';
                                            if (isset($step2Data['pr_subclass']) && !empty($step2Data['pr_subclass'])) {
                                                $subclass = $step2Data['pr_subclass'];
                                            } elseif (isset($step2Data['visit_subclass']) && !empty($step2Data['visit_subclass'])) {
                                                $subclass = $step2Data['visit_subclass'];
                                            } elseif (isset($step2Data['work_subclass']) && !empty($step2Data['work_subclass'])) {
                                                $subclass = $step2Data['work_subclass'];
                                            } elseif (isset($step2Data['student_subclass']) && !empty($step2Data['student_subclass'])) {
                                                $subclass = $step2Data['student_subclass'];
                                            }
                                        @endphp
                                        {{ $subclass }}
                                    </div>
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
                                $relatives = [];
                                if (isset($step4Data['relatives']) && is_array($step4Data['relatives'])) {
                                    $relatives = $step4Data['relatives'];
                                } elseif (!empty($step4Data)) {
                                    // If it's a single relative object, wrap it in array
                                    $relatives = [$step4Data];
                                }
                            @endphp
                            @if(count($relatives) > 0)
                                @foreach($relatives as $index => $relative)
                                    <div class="info-subsection-title mb-2">
                                        <h5 class="info-subsection-title-text">Relative Contact {{ $index + 1 }}</h5>
                                    </div>
                                    <div class="info-grid-row row">
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Surname</div>
                                            <div class="info-field-value-text">{{ $getValue($relative['surname'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Given Name</div>
                                            <div class="info-field-value-text">{{ $getValue($relative['given_name'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Organization Name</div>
                                            <div class="info-field-value-text">{{ $getValue($relative['organization_name'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Relationship To You</div>
                                            <div class="info-field-value-text">{{ $getValue($relative['relationship'] ?? null) }}</div>
                                        </div>
                                    </div>
                                    <div class="info-grid-row row">
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Contact Address</div>
                                            <div class="info-field-value-text">{{ $getValue($relative['contact_address'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Email</div>
                                            <div class="info-field-value-text">{{ $getValue($relative['email'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Phone Number</div>
                                            <div class="info-field-value-text">{{ $getValue($relative['phone_number'] ?? null) }}</div>
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
                                    <div class="info-field-value-text">{{ $getValue($step5Data['spouse_address'] ?? null) }}</div>
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
                                $children = [];
                                if (isset($step5Data['children']) && is_array($step5Data['children'])) {
                                    $children = $step5Data['children'];
                                }
                            @endphp
                            @if(count($children) > 0)
                                @foreach($children as $index => $child)
                                    <div class="info-subsection-title mb-2">
                                        <h5 class="info-subsection-title-text">Child {{ $index + 1 }}</h5>
                                    </div>
                                    <div class="info-grid-row row">
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Child's Name</div>
                                            <div class="info-field-value-text">{{ $getValue($child['name'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Child's Age</div>
                                            <div class="info-field-value-text">{{ $getValue($child['age'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Date of Birth</div>
                                            <div class="info-field-value-text">{{ $formatDate($child['date_of_birth'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">City of Birth</div>
                                            <div class="info-field-value-text">{{ $getValue($child['city_of_birth'] ?? null) }}</div>
                                        </div>
                                    </div>
                                    <div class="info-grid-row row">
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Have Passport</div>
                                            <div class="info-field-value-text">{{ $getValue($child['have_passport'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Gender</div>
                                            <div class="info-field-value-text">{{ $getValue($child['gender'] ?? null) }}</div>
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
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">IELTS/PTE/OET/TOEFL Exam Details</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Exam Type</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['exam_type'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Passing Year</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['exam_passing_year'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Score</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['exam_score'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Trial</div>
                                    <div class="info-field-value-text">{{ $getValue($step6Data['exam_trial'] ?? null) }}</div>
                                </div>
                            </div>

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
                                    <div class="info-field-value-text">{{ $getValue($step6Data['graduation_university'] ?? null) }}</div>
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

                            <!-- Post Graduation Degree Details -->
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
                                    <div class="info-field-value-text">{{ $getValue($step6Data['post_graduation_university'] ?? null) }}</div>
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

                            <!-- Other Degree Details -->
                            @php
                                $otherDegrees = [];
                                if (isset($step6Data['other_degrees']) && is_array($step6Data['other_degrees'])) {
                                    $otherDegrees = $step6Data['other_degrees'];
                                }
                            @endphp
                            @if(count($otherDegrees) > 0)
                                @foreach($otherDegrees as $index => $degree)
                                    <div class="info-subsection-title mb-2">
                                        <h5 class="info-subsection-title-text">Other Degree {{ $index + 1 }}</h5>
                                    </div>
                                    <div class="info-grid-row row">
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Degree</div>
                                            <div class="info-field-value-text">{{ $getValue($degree['degree'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Institution Name</div>
                                            <div class="info-field-value-text">{{ $getValue($degree['institution_name'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Percentage</div>
                                            <div class="info-field-value-text">{{ $getValue($degree['percentage'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Passing Year</div>
                                            <div class="info-field-value-text">{{ $getValue($degree['passing_year'] ?? null) }}</div>
                                        </div>
                                    </div>
                                    <div class="info-grid-row row mb-4">
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Trial</div>
                                            <div class="info-field-value-text">{{ $getValue($degree['trial'] ?? null) }}</div>
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
                                $experiences = [];
                                if (isset($step7Data['experiences']) && is_array($step7Data['experiences'])) {
                                    $experiences = $step7Data['experiences'];
                                } elseif (!empty($step7Data)) {
                                    $experiences = [$step7Data];
                                }
                            @endphp
                            @if(count($experiences) > 0)
                                @foreach($experiences as $index => $experience)
                                    <div class="info-subsection-title mb-2">
                                        <h5 class="info-subsection-title-text">Experience {{ $index + 1 }}</h5>
                                    </div>
                                    <div class="info-grid-row row">
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Duration - From</div>
                                            <div class="info-field-value-text">{{ $formatDate($experience['from_date'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Duration - To</div>
                                            <div class="info-field-value-text">{{ $getValue($experience['to_date'] ?? ($experience['is_present'] ?? false ? 'Present' : null)) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Country</div>
                                            <div class="info-field-value-text">{{ $getValue($experience['country'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Designation</div>
                                            <div class="info-field-value-text">{{ $getValue($experience['designation'] ?? null) }}</div>
                                        </div>
                                    </div>
                                    <div class="info-grid-row row">
                                        <div class="info-field-item col-md-6 mb-3">
                                            <div class="info-field-label-text">Company Name</div>
                                            <div class="info-field-value-text">{{ $getValue($experience['company_name'] ?? null) }}</div>
                                        </div>
                                        <div class="info-field-item col-md-3 mb-3">
                                            <div class="info-field-label-text">Salary</div>
                                            <div class="info-field-value-text">{{ $getValue($experience['salary'] ?? null) }}</div>
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
                                    <div class="info-field-value-text">{{ $getValue($step8Data['home_value'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Land</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['land_value'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Plot</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['plot_value'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Commercials</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['commercials_value'] ?? null) }}</div>
                                </div>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Other</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['other_value'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Shop</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['shop_value'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Gold</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['gold_value'] ?? null) }}</div>
                                </div>
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Silver</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['silver_value'] ?? null) }}</div>
                                </div>
                            </div>
                            <div class="info-grid-row row mb-4">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Total Asset Valuation</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['total_asset_valuation'] ?? null) }}</div>
                                </div>
                            </div>

                            <!-- Loan Information -->
                            <div class="info-subsection-title mb-2">
                                <h5 class="info-subsection-title-text">Loan Information</h5>
                            </div>
                            <div class="info-grid-row row">
                                <div class="info-field-item col-md-3 mb-3">
                                    <div class="info-field-label-text">Total Loan Value</div>
                                    <div class="info-field-value-text">{{ $getValue($step8Data['total_loan_value'] ?? null) }}</div>
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
                                $totalIncome = $fatherIncome + $motherIncome + $candidateIncome + $spouseIncome;
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
