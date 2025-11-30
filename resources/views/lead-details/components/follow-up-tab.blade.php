                <div class="tab-content px-4 pb-4" id="followUpTab">
                    <!-- Tab Header -->
                    <div class="tab-section-header">
                        <div class="tab-section-header-content">
                            <div>
                                <h3 class="tab-section-title">FOLLOW UP</h3>
                                @php
                                    $nextFollowUp = null;
                                    if (isset($lead) && $lead && $lead->followUps) {
                                        $nextFollowUp = $lead->followUps()
                                            ->where('send_reminder', 'yes')
                                            ->where('status', 'pending')
                                            ->whereNotNull('next_follow_up_date')
                                            ->orderBy('next_follow_up_date', 'asc')
                                            ->first();
                                    }
                                @endphp
                                <div class="tab-section-subtitle-text">
                                    @if($nextFollowUp && $nextFollowUp->next_follow_up_date)
                                        Next Follow-up: {{ $nextFollowUp->next_follow_up_date->format(company()->date_format . ' ' . company()->time_format) }}
                                    @else
                                        Next Follow-up: N/A
                                    @endif
                                </div>
                            </div>
                            @php
                                $addLeadFollowUpPermission = user()->permission('add_lead_follow_up');
                                $isDraft = false;
                                if (isset($lead) && $lead && $lead->stepStatus && $lead->stepStatus->final_status == 'draft') {
                                    $isDraft = true;
                                }
                            @endphp
                            @if(($addLeadFollowUpPermission == 'all' || $addLeadFollowUpPermission == 'added') && !$isDraft)
                                <button type="button" class="tab-section-add-btn" id="addFollowUpBtn">
                                    <i class="fa fa-plus"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    <!-- Tab Content Area -->
                    <div class="tab-section-content" id="followUpContent">
                        @include('lead-details.components.follow-up-list')
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
                <x-form id="followUpFormDetails" method="POST" class="ajax-form">
                    <div class="modal-body">
                        <input type="hidden" name="new_lead_id" id="follow_up_lead_id_details" value="{{ isset($lead) && $lead ? $lead->id : '' }}">
                        <input type="hidden" name="id" id="follow_up_id_details">
                        <div class="form-group">
                            <label class="f-14 font-weight-bold mb-2">Follow-Up Type</label>
                            <div class="d-flex gap-2">
                                <label class="form-check-label mr-3">
                                    <input type="radio" name="follow_up_type" value="call" checked class="mr-1"> Call
                                </label>
                                <label class="form-check-label mr-3">
                                    <input type="radio" name="follow_up_type" value="meeting" class="mr-1"> Meeting
                                </label>
                                <label class="form-check-label mr-3">
                                    <input type="radio" name="follow_up_type" value="sms" class="mr-1"> SMS
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" name="follow_up_type" value="email" class="mr-1"> Email
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <x-forms.label fieldId="subject_details" fieldLabel="Subject">
                            </x-forms.label>
                            <input type="text" name="subject" id="subject_details" class="form-control height-35 f-14" value="">
                        </div>
                        <div class="form-group">
                            <x-forms.label fieldId="outcome_details" fieldLabel="Outcome of Call">
                            </x-forms.label>
                            <input type="text" name="outcome" id="outcome_details" class="form-control height-35 f-14" value="">
                        </div>
                        <div class="form-group">
                            <x-forms.label fieldId="notes_details" fieldLabel="Notes">
                            </x-forms.label>
                            <textarea name="notes" id="notes_details" class="form-control f-14" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="f-14 font-weight-bold mb-2">Do you want to get update for next follow-up - Set reminder?</label>
                            <x-forms.checkbox :fieldLabel="__('modules.tasks.reminder')" fieldName="send_reminder"
                                fieldId="send_reminder_details" fieldValue="yes" />
                        </div>
                        <div class="form-group next_follow_up_datetime_div_details d-none">
                            <div class="row">
                                <div class="col-md-6">
                                    <x-forms.datepicker fieldId="next_follow_up_date_details"
                                        fieldLabel="Next Follow Up Date" fieldName="next_follow_up_date"
                                        :fieldValue="''"
                                        :fieldPlaceholder="__('placeholders.date')" />
                                </div>
                                <div class="col-md-6">
                                    <div class="bootstrap-timepicker timepicker">
                                        <x-forms.text fieldLabel="Time" :fieldPlaceholder="__('placeholders.hours')"
                                            fieldName="next_follow_up_time" fieldId="next_follow_up_time_details"
                                            :fieldValue="''" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group send_reminder_div_details d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <x-forms.label fieldId="remind_time_details" fieldLabel="Remind Time">
                                    </x-forms.label>
                                    <select name="remind_time" id="remind_time_details" class="form-control select-picker height-35 f-14">
                                        <option value="15 Minutes Before" selected>15 Minutes Before</option>
                                        <option value="30 Minutes Before">30 Minutes Before</option>
                                        <option value="1 Hour Before">1 Hour Before</option>
                                        <option value="2 Hours Before">2 Hours Before</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group follow_up_subject_line_div_details d-none">
                            <x-forms.label fieldId="follow_up_subject_line_details" fieldLabel="Follow Up Subject Line" fieldRequired="true">
                            </x-forms.label>
                            <input type="text" name="follow_up_subject_line" id="follow_up_subject_line_details" class="form-control height-35 f-14" value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <x-forms.button-primary id="save-followup-details" icon="check">Save</x-forms.button-primary>
                    </div>
                </x-form>
            </div>
        </div>
    </div>