@php
    $travelData = null;
    $hasTravelData = false;
    if (isset($lead) && $lead && $lead->travelDetails) {
        $travelData = $lead->travelDetails;
        $hasTravelData = true;
    }
    
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
    
    // Get registered date from lead
    $registeredDate = isset($lead) && $lead ? ($lead->created_at ? $formatDate($lead->created_at) : 'N/A') : 'N/A';
@endphp

<div class="tab-content px-4 pb-4" id="travelDetailsTab">
    <!-- Tab Header -->
    <div class="tab-section-header">
        <div class="tab-section-header-content d-flex justify-content-between align-items-center">
            <h3 class="tab-section-title">Travel Details</h3>
            <div class="tab-header-actions d-flex align-items-center">
                <!-- Notify Client Button (shown when data exists and not in edit mode) -->
                <button type="button" class="btn-primary btn-sm mr-2" id="notifyClientTravelDetailsBtn" style="{{ $hasTravelData ? '' : 'display:none;' }}">
                    <i class="fa fa-bell mr-1"></i> Notify Client
                </button>
                <!-- Edit Button (shown when data exists and not in edit mode) -->
                <button type="button" class="btn-primary btn-sm" id="editTravelDetailsBtn" style="{{ $hasTravelData ? '' : 'display:none;' }}">
                    <i class="fa fa-edit mr-1"></i> Edit
                </button>
                <!-- Save and Cancel Buttons (shown when in edit mode) -->
                <div id="travelDetailsFormActions" style="display:none;">
                    <button type="button" class="btn btn-primary btn-sm" id="saveTravelDetailsBtn">
                        <i class="fa fa-save mr-1"></i> Save
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm ml-2" id="cancelTravelDetailsBtn">
                        <i class="fa fa-times mr-1"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tab Content Area -->
    <div class="tab-section-content">
        <!-- Form Section (shown by default if no data, or when editing) -->
        <div id="travelDetailsFormSection" style="{{ $hasTravelData ? 'display:none;' : '' }}">
            <form id="travelDetailsForm">
                @csrf
                <input type="hidden" name="new_lead_id" id="travel_details_lead_id" value="{{ isset($lead) && $lead ? $lead->id : '' }}">
                <input type="hidden" name="travel_details_id" id="travel_details_id" value="{{ $travelData ? $travelData->id : '' }}">
                
                <!-- Travel Details Section -->
                <div class="info-section mb-1">
                    <div class="info-section-title mb-3">
                        <h4 class="f-16 font-weight-bold">Travel Details</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <x-forms.label fieldId="purpose_of_trip" fieldLabel="Purpose of Trip *">
                            </x-forms.label>
                            <textarea class="form-control f-14" name="purpose_of_trip" id="purpose_of_trip" rows="3" required>{{ $travelData ? $travelData->purpose_of_trip : '' }}</textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-forms.label fieldId="place_to_visit" fieldLabel="Place To Visit *">
                            </x-forms.label>
                            <textarea class="form-control f-14" name="place_to_visit" id="place_to_visit" rows="3" required>{{ $travelData ? $travelData->place_to_visit : '' }}</textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="date_of_arrival" fieldLabel="Date of Arrival *">
                            </x-forms.label>
                            <input type="date" class="form-control height-35 f-14" name="date_of_arrival" id="date_of_arrival" value="{{ $travelData && $travelData->date_of_arrival ? $travelData->date_of_arrival->format('Y-m-d') : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="arrival_flight" fieldLabel="Arrival Flight *">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="arrival_flight" id="arrival_flight" value="{{ $travelData ? $travelData->arrival_flight : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="arrival_city" fieldLabel="Arrival City *">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="arrival_city" id="arrival_city" value="{{ $travelData ? $travelData->arrival_city : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="date_of_departure" fieldLabel="Date of Departure From *">
                            </x-forms.label>
                            <input type="date" class="form-control height-35 f-14" name="date_of_departure" id="date_of_departure" value="{{ $travelData && $travelData->date_of_departure ? $travelData->date_of_departure->format('Y-m-d') : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="departure_flight" fieldLabel="Departure Flight *">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="departure_flight" id="departure_flight" value="{{ $travelData ? $travelData->departure_flight : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="departure_city" fieldLabel="Departure City *">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="departure_city" id="departure_city" value="{{ $travelData ? $travelData->departure_city : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="phone_number_other_country" fieldLabel="Phone Number (of other country)">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="phone_number_other_country" id="phone_number_other_country" value="{{ $travelData ? $travelData->phone_number_other_country : '' }}" pattern="[0-9]{10}" minlength="10" maxlength="10" inputmode="numeric" title="Please enter exactly 10 digits (numbers only)">
                        </div>
                    </div>
                </div>

                <!-- Address Where You Will Stay Section -->
                <div class="info-section mb-1">
                    <div class="info-section-title mb-3">
                        <h4 class="f-16 font-weight-bold">Address Where You Will Stay</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <x-forms.label fieldId="address_stay" fieldLabel="Address Where You Will Stay *">
                            </x-forms.label>
                            <textarea class="form-control f-14" name="address_stay" id="address_stay" rows="3" required>{{ $travelData ? $travelData->address_stay : '' }}</textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="city" fieldLabel="City *">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="city" id="city" value="{{ $travelData ? $travelData->city : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="state" fieldLabel="State *">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="state" id="state" value="{{ $travelData ? $travelData->state : '' }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <x-forms.label fieldId="postal_code" fieldLabel="Postal/Zip Code *">
                            </x-forms.label>
                            <input type="text" class="form-control height-35 f-14" name="postal_code" id="postal_code" value="{{ $travelData ? $travelData->postal_code : '' }}" pattern="[0-9]{6}" minlength="6" maxlength="6" inputmode="numeric" title="Please enter exactly 6 digits (numbers only)" required>
                        </div>
                    </div>
                </div>

                <!-- Personal Information Section -->
                <div class="info-section mb-1">
                    <div class="info-section-title mb-3">
                        <h4 class="f-16 font-weight-bold">Personal Information</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <x-forms.label fieldId="person_paying" fieldLabel="Person Paying For Your Trip (Details) *">
                            </x-forms.label>
                            <textarea class="form-control f-14" name="person_paying" id="person_paying" rows="3" required>{{ $travelData ? $travelData->person_paying : '' }}</textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-forms.label fieldId="mother_in_country" fieldLabel="Is Your Mother in that country?">
                            </x-forms.label>
                            <select class="form-control select-picker height-35 f-14" name="mother_in_country" id="mother_in_country">
                                <option value="">Select</option>
                                <option value="yes" {{ $travelData && $travelData->mother_in_country == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ $travelData && $travelData->mother_in_country == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-forms.label fieldId="immediate_relatives" fieldLabel="Immediate Relatives in that country?">
                            </x-forms.label>
                            <select class="form-control select-picker height-35 f-14" name="immediate_relatives" id="immediate_relatives">
                                <option value="">Select</option>
                                <option value="yes" {{ $travelData && $travelData->immediate_relatives == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ $travelData && $travelData->immediate_relatives == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-forms.label fieldId="other_relatives" fieldLabel="Other Relatives in that country?">
                            </x-forms.label>
                            <select class="form-control select-picker height-35 f-14" name="other_relatives" id="other_relatives">
                                <option value="">Select</option>
                                <option value="yes" {{ $travelData && $travelData->other_relatives == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ $travelData && $travelData->other_relatives == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Save Button at bottom of form -->
                <div class="mt-4">
                    <button type="button" class="btn btn-primary" id="saveTravelDetailsFormBtn">
                        <i class="fa fa-save mr-1"></i> Save
                    </button>
                </div>
            </form>
        </div>

        <!-- Details Section (shown when data exists and not editing) -->
        <div id="travelDetailsDetailsSection" style="{{ $hasTravelData ? '' : 'display:none;' }}">
            <!-- Travel Details Section -->
            <div class="info-section mb-1">
                <div class="info-section-header mb-3">
                    <h4 class="info-section-title-text">Travel Details</h4>
                    <div class="info-section-divider"></div>
                </div>
                <div class="info-grid-row row">
                    <div class="info-field-item col-md-6 mb-3">
                        <div class="info-field-label-text">Purpose of Trip</div>
                        <div class="info-field-value-text">{{ $getValue($travelData ? $travelData->purpose_of_trip : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-6 mb-3">
                        <div class="info-field-label-text">Place To Visit</div>
                        <div class="info-field-value-text">{{ $getValue($travelData ? $travelData->place_to_visit : null) }}</div>
                    </div>
                </div>
                <div class="info-grid-row row mt-3">
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Date of Arrival</div>
                        <div class="info-field-value-text">{{ $formatDate($travelData ? $travelData->date_of_arrival : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Arrival Flight</div>
                        <div class="info-field-value-text">{{ $getValue($travelData ? $travelData->arrival_flight : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Arrival City</div>
                        <div class="info-field-value-text">{{ $getValue($travelData ? $travelData->arrival_city : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Date of Departure From</div>
                        <div class="info-field-value-text">{{ $formatDate($travelData ? $travelData->date_of_departure : null) }}</div>
                    </div>
                </div>
                <div class="info-grid-row row mt-3">
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Departure Flight</div>
                        <div class="info-field-value-text">{{ $getValue($travelData ? $travelData->departure_flight : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Departure City</div>
                        <div class="info-field-value-text">{{ $getValue($travelData ? $travelData->departure_city : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Phone Number (of other country)</div>
                        <div class="info-field-value-text">{{ $getValue($travelData ? $travelData->phone_number_other_country : null) }}</div>
                    </div>
                </div>
            </div>

            <!-- Address Where You Will Stay Section -->
            <div class="info-section mb-1">
                <div class="info-section-header mb-3">
                    <h4 class="info-section-title-text">Address Where You Will Stay</h4>
                    <div class="info-section-divider"></div>
                </div>
                <div class="info-grid-row row">
                    <div class="info-field-item col-md-12 mb-3">
                        <div class="info-field-label-text">Address Where You Will Stay</div>
                        <div class="info-field-value-text">{{ $getValue($travelData ? $travelData->address_stay : null) }}</div>
                    </div>
                </div>
                <div class="info-grid-row row mt-3">
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">City</div>
                        <div class="info-field-value-text">{{ $getValue($travelData ? $travelData->city : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">State</div>
                        <div class="info-field-value-text">{{ $getValue($travelData ? $travelData->state : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Postal/Zip Code</div>
                        <div class="info-field-value-text">{{ $getValue($travelData ? $travelData->postal_code : null) }}</div>
                    </div>
                </div>
            </div>

            <!-- Personal Information Section -->
            <div class="info-section mb-1">
                <div class="info-section-header mb-3">
                    <h4 class="info-section-title-text">Personal Information</h4>
                    <div class="info-section-divider"></div>
                </div>
                <div class="info-grid-row row">
                    <div class="info-field-item col-md-12 mb-3">
                        <div class="info-field-label-text">Person Paying For Your Trip (Details)</div>
                        <div class="info-field-value-text">{{ $getValue($travelData ? $travelData->person_paying : null) }}</div>
                    </div>
                </div>
                <div class="info-grid-row row mt-3">
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Is Your Mother in that country?</div>
                        <div class="info-field-value-text">{{ $getValue($travelData ? ucfirst($travelData->mother_in_country) : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Immediate Relatives in that country?</div>
                        <div class="info-field-value-text">{{ $getValue($travelData ? ucfirst($travelData->immediate_relatives) : null) }}</div>
                    </div>
                    <div class="info-field-item col-md-3 mb-3">
                        <div class="info-field-label-text">Other Relatives in that country?</div>
                        <div class="info-field-value-text">{{ $getValue($travelData ? ucfirst($travelData->other_relatives) : null) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
