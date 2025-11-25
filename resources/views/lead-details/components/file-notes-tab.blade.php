                <div class="tab-content px-4 pb-4" id="fileNotesTab">
                    <!-- Tab Header -->
                    <div class="tab-section-header">
                        <div class="tab-section-header-content">
                            <h3 class="tab-section-title">File Notes</h3>
                            @php
                                $userRoles = user_roles();
                                $isAdmin = in_array('admin', $userRoles);
                                $isEmployee = in_array('employee', $userRoles);
                                $isDraft = false;
                                if (isset($lead) && $lead && $lead->stepStatus && $lead->stepStatus->final_status == 'draft') {
                                    $isDraft = true;
                                }
                            @endphp
                            @if(($isAdmin || $isEmployee) && !$isDraft)
                                <button type="button" class="tab-section-add-btn" data-toggle="modal" data-target="#addFileNoteModal">
                                    <i class="fa fa-plus"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    <!-- Tab Content Area -->
                    <div class="tab-section-content">
                        @if(isset($lead) && $lead && $lead->fileNotes && $lead->fileNotes->count() > 0)
                            <div class="file-notes-list">
                                @foreach($lead->fileNotes->sortByDesc('created_at') as $index => $fileNote)
                                    @php
                                        $createdBy = $fileNote->addedBy ? $fileNote->addedBy->name : 'N/A';
                                        $createdDate = $fileNote->created_at ? $fileNote->created_at->format(company()->date_format . ' ' . company()->time_format) : 'N/A';
                                        $isLast = ($index == $lead->fileNotes->count() - 1);
                                    @endphp
                                    <div class="file-note-item">
                                        <div class="file-note-timeline">
                                            <div class="timeline-dot"></div>
                                            <div class="timeline-line"></div>
                                        </div>
                                        <div class="file-note-box">
                                            <div class="file-note-text" style="margin-bottom: 0;">{!! $fileNote->note !!}</div>
                                            <div class="file-note-meta" style="margin-top: -10px;">
                                                Created by: {{ $createdBy }} - {{ $createdDate }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center p-5">
                                <p class="text-muted">No file notes found</p>
                            </div>
                        @endif
                    </div>
                </div>

    <!-- Add File Note Modal -->
    <div class="modal fade" id="addFileNoteModal" tabindex="-1" role="dialog" aria-labelledby="addFileNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFileNoteModalLabel">Add File Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <x-form id="fileNoteFormDetails" method="POST" class="ajax-form">
                    <div class="modal-body">
                        <input type="hidden" name="new_lead_id" id="file_note_lead_id_details" value="{{ isset($lead) && $lead ? $lead->id : '' }}">
                        <div class="form-group">
                            <label>Note</label>
                            <div id="file-note-editor"></div>
                            <textarea name="note" id="file-note-editor-text" class="d-none"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <x-forms.button-primary id="save-file-note-btn" icon="check">Save</x-forms.button-primary>
                    </div>
                </x-form>
            </div>
        </div>
    </div>