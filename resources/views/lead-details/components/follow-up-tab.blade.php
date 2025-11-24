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
                        <!-- Follow Up List -->
                        <div class="follow-up-list">
                            <!-- Follow Up Card 1 -->
                            <div class="follow-up-card">
                                <div class="follow-up-card-content">
                                    <img src="{{ asset('img/icon/follow_up_list.svg') }}">
                                    <div class="follow-up-details">
                                        <div class="follow-up-subject">
                                            <span class="follow-up-label">Subject:</span> Introductory meeting with the customer
                                        </div>
                                        <div class="follow-up-outcome">
                                            <span class="follow-up-label">Outcome:</span> Interested
                                        </div>
                                        <div class="follow-up-description">
                                            The customer has shown interest in the Australian admission service
                                        </div>
                                        <div class="follow-up-meta">
                                            Created by : Shivani Patel - 09-09-2025 11:46 AM
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Follow Up Card 2 -->
                            <div class="follow-up-card">
                                <div class="follow-up-card-content">
                                    <img src="{{ asset('img/icon/follow_up_list.svg') }}">
                                    <div class="follow-up-details">
                                        <div class="follow-up-subject">
                                            <span class="follow-up-label">Subject:</span> Introductory meeting with the customer
                                        </div>
                                        <div class="follow-up-outcome">
                                            <span class="follow-up-label">Outcome:</span> Interested
                                        </div>
                                        <div class="follow-up-description">
                                            The customer has shown interest in the Australian admission service
                                        </div>
                                        <div class="follow-up-meta">
                                            Created by : Shivani Patel - 09-09-2025 11:46 AM
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Follow Up Card 3 -->
                            <div class="follow-up-card">
                                <div class="follow-up-card-content">
                                    <img src="{{ asset('img/icon/follow_up_list.svg') }}">
                                    <div class="follow-up-details">
                                        <div class="follow-up-subject">
                                            <span class="follow-up-label">Subject:</span> Introductory meeting with the customer
                                        </div>
                                        <div class="follow-up-outcome">
                                            <span class="follow-up-label">Outcome:</span> Interested
                                        </div>
                                        <div class="follow-up-description">
                                            The customer has shown interest in the Australian admission service
                                        </div>
                                        <div class="follow-up-meta">
                                            Created by : Shivani Patel - 09-09-2025 11:46 AM
                                        </div>
                                    </div>
                                </div>
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
                            <div class="col-md-6 mb-2">
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