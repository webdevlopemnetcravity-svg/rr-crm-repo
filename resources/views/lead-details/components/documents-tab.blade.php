                <div class="tab-content px-4 pb-4" id="documentsTab">
                    <!-- Tab Header -->
                    <div class="tab-section-header">
                        <div class="tab-section-header-content">
                            @php
                                $applicantName = 'N/A';
                                if (isset($lead) && $lead && $lead->step_1_data) {
                                    $step1Data = is_string($lead->step_1_data) ? json_decode($lead->step_1_data, true) : $lead->step_1_data;
                                    if (is_array($step1Data)) {
                                        $surname = $step1Data['surname'] ?? '';
                                        $givenName = $step1Data['given_name'] ?? '';
                                        $applicantName = trim($surname . ' ' . $givenName) ?: 'N/A';
                                    }
                                }
                            @endphp
                            <h3 class="tab-section-title">Document Checklist - <span class="info-field-value">{{ strtoupper($applicantName) }} (MAIN APPLICANT)</span></h3>
                        </div>
                    </div>
                    <!-- Tab Content Area -->
                    <div class="tab-section-content" id="documentsContent">
                        @include('lead-details.components.documents-list')
                    </div>
                </div>

    <!-- Document Upload Modal -->
    <div class="modal fade" id="documentUploadModal" tabindex="-1" role="dialog" aria-labelledby="documentUploadModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentUploadModalLabel">Upload Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <x-form id="documentUploadForm" method="POST" class="ajax-form" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="document_key" id="document_key_input">
                        <div class="form-group">
                            <label id="document_name_label">Document</label>
                            <input type="file" name="document_file" id="document_file_input" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                            <small class="form-text text-muted">Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <x-forms.button-primary id="save-document-btn" icon="check">Upload</x-forms.button-primary>
                    </div>
                </x-form>
            </div>
        </div>
    </div>
