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
                    <div class="tab-section-content">
                        @if(isset($lead) && $lead && $lead->followUps && $lead->followUps->count() > 0)
                            <!-- Follow Up List -->
                            <div class="follow-up-list" id="followUpListContainer">
                                @php
                                    $sortedFollowUps = $lead->followUps->sortByDesc('created_at');
                                    $latestFollowUp = $sortedFollowUps->first();
                                @endphp
                                @foreach($sortedFollowUps as $index => $followUp)
                                    @php
                                        $editPermission = user()->permission('edit_lead_follow_up');
                                        $canEdit = ($editPermission == 'all' || ($editPermission == 'added' && $followUp->added_by == user()->id));
                                        $isLatest = ($followUp->id == $latestFollowUp->id);
                                        $createdBy = $followUp->addedBy ? $followUp->addedBy->name : 'N/A';
                                        $createdDate = $followUp->created_at ? $followUp->created_at->format(company()->date_format . ' ' . company()->time_format) : 'N/A';
                                        $updatedBy = $followUp->lastUpdatedBy ? $followUp->lastUpdatedBy->name : null;
                                        $updatedDate = $followUp->updated_at ? $followUp->updated_at->format(company()->date_format . ' ' . company()->time_format) : null;
                                        $wasUpdated = $updatedBy && $updatedDate && $followUp->updated_at && $followUp->created_at && $followUp->updated_at->format('Y-m-d H:i:s') != $followUp->created_at->format('Y-m-d H:i:s');
                                    @endphp
                                    <div class="follow-up-card" data-follow-up-id="{{ $followUp->id }}">
                                        <div class="follow-up-card-content position-relative">
                                            @if($canEdit && $isLatest)
                                                <div class="follow-up-actions position-absolute" style="top: 0; right: 0; z-index: 10;">
                                                    <button type="button" class="btn btn-sm btn-secondary edit-follow-up-btn-details" data-follow-up-id="{{ $followUp->id }}" data-lead-id="{{ $lead->id }}" style="border: none; background: transparent; color: #6c757d; padding: 0.25rem 0.5rem;">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </button>
                                                </div>
                                            @endif
                                            <img src="{{ asset('img/icon/follow_up_list.svg') }}">
                                            <div class="follow-up-details">
                                                <div class="follow-up-subject">
                                                    <span class="follow-up-label">Subject:</span> {{ $followUp->subject ?? 'N/A' }}
                                                </div>
                                                <div class="follow-up-outcome">
                                                    <span class="follow-up-label">Outcome:</span> {{ $followUp->outcome ?? 'N/A' }}
                                                </div>
                                                <div class="follow-up-description">
                                                    {{ $followUp->notes ?? 'No notes' }}
                                                </div>
                                                <div class="follow-up-meta">
                                                    @if($wasUpdated)
                                                        <div>
                                                            <strong>Updated by:</strong> {{ $updatedBy }} - {{ $updatedDate }}
                                                        </div>
                                                    @else
                                                        <div>
                                                            <strong>Created by:</strong> {{ $createdBy }} - {{ $createdDate }}
                                                        </div>
                                                    @endif
                                                    @if($followUp->next_follow_up_date && $followUp->send_reminder == 'yes')
                                                        <div>
                                                            <span class="text-info"><strong>Next Follow-up:</strong> {{ $followUp->next_follow_up_date->format(company()->date_format . ' ' . company()->time_format) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center p-5">
                                <p class="text-muted">No follow-ups found</p>
                            </div>
                        @endif
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
                                        :fieldValue="now(company()->timezone)->format(company()->date_format)"
                                        :fieldPlaceholder="__('placeholders.date')" />
                                </div>
                                <div class="col-md-6">
                                    <div class="bootstrap-timepicker timepicker">
                                        <x-forms.text fieldLabel="Time" :fieldPlaceholder="__('placeholders.hours')"
                                            fieldName="next_follow_up_time" fieldId="next_follow_up_time_details"
                                            :fieldValue="now(company()->timezone)->format(company()->time_format)" />
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