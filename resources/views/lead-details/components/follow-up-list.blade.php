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

