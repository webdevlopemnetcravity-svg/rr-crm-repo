                <div class="tab-content px-4 pb-4" id="documentsTab">
                    <!-- Tab Header -->
                    <div class="tab-section-header">
                        <div class="tab-section-header-content">
                            <h3 class="tab-section-title">Document Checklist</h3>
                        </div>
                    </div>
                    <!-- Tab Content Area -->
                    <div class="tab-section-content" id="documentsContent">
                        @include('lead-details.components.documents-content')
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
                        <input type="hidden" name="document_master_id" id="document_master_id_input">
                        <input type="hidden" name="applicant_type" id="applicant_type_input">
                        <input type="hidden" name="child_index" id="child_index_input">
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

    <!-- Add Required Document Modal -->
    <div class="modal fade" id="addRequiredDocumentModal" tabindex="-1" role="dialog" aria-labelledby="addRequiredDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRequiredDocumentModalLabel">Add Required Documents</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addRequiredDocumentForm" method="POST" class="ajax-form" onsubmit="return false;">
                    <div class="modal-body">
                        <input type="hidden" name="lead_id" id="add_doc_lead_id">
                        <input type="hidden" name="applicant_type" id="add_doc_applicant_type">
                        <input type="hidden" name="child_index" id="add_doc_child_index">
                        <p class="mb-3"><strong id="add_doc_section_name"></strong></p>
                        <div id="documentChecklistContainer" style="max-height: 400px; overflow-y: auto;">
                            <div class="text-center py-4">
                                <i class="fa fa-spinner fa-spin"></i> Loading documents...
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="save-required-documents-btn">
                            <i class="fa fa-check"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
