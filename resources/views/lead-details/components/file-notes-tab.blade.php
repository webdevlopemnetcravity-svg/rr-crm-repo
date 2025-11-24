                <div class="tab-content px-4 pb-4" id="fileNotesTab">
                    <!-- Tab Header -->
                    <div class="tab-section-header">
                        <div class="tab-section-header-content">
                            <h3 class="tab-section-title">File Notes</h3>
                            <button type="button" class="tab-section-add-btn" data-toggle="modal" data-target="#addFileNoteModal">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Tab Content Area -->
                    <div class="tab-section-content">
                        <div class="file-notes-list">
                            <div class="file-note-item">
                                <div class="file-note-timeline">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-line"></div>
                                </div>
                                <div class="file-note-box">
                                    <div class="file-note-text">Need student visa with admission service for Australia</div>
                                    <div class="file-note-meta">
                                        <span class="file-note-bullet">•</span> Created by: Samuel Parker - 17-07-2025 2:00 PM
                                    </div>
                                </div>
                            </div>
                            <div class="file-note-item">
                                <div class="file-note-timeline">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-line"></div>
                                </div>
                                <div class="file-note-box">
                                    <div class="file-note-text">Need student visa with admission service for Australia</div>
                                    <div class="file-note-meta">
                                        <span class="file-note-bullet">•</span> Created by: Samuel Parker - 17-07-2025 2:00 PM
                                    </div>
                                </div>
                            </div>
                        </div>
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
                <div class="modal-body">
                    <div class="form-group">
                        <label>Note</label>
                        <div id="file-note-editor"></div>
                        <textarea name="note" id="file-note-editor-text" class="d-none"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-file-note-btn">Save</button>
                </div>
            </div>
        </div>
    </div>