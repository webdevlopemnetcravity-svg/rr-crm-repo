                <div class="tab-content px-4 pb-4" id="documentsTab">
                    <!-- Tab Header -->
                    <div class="tab-section-header">
                        <div class="tab-section-header-content">
                            <h3 class="tab-section-title">Document Checklist</h3>
                        </div>
                    </div>
                    <!-- Tab Content Area -->
                    <div class="tab-section-content" id="documentsContent">
                        @if(isset($lead) && $lead)
                            @php
                                $familyDetails = $familyDetails ?? [];
                                $documentChecklists = $documentChecklists ?? [];
                            @endphp
                            
                            <!-- Main Applicant Section -->
                            @if(!empty($familyDetails['main_applicant']))
                                <div class="document-section mb-4">
                                    <div class="document-section-header" data-toggle="collapse" data-target="#mainApplicantDocuments" aria-expanded="true">
                                        <h4 class="document-section-title">
                                            <i class="fas fa-chevron-down"></i>
                                            {{ $familyDetails['main_applicant']['name'] }} (Main Applicant)
                                        </h4>
                                    </div>
                                    <div class="collapse show" id="mainApplicantDocuments">
                                        <div class="document-section-body">
                                            @include('lead-details.components.documents-checklist', [
                                                'applicantType' => 'main_applicant',
                                                'documents' => $documentChecklists['main_applicant'] ?? [],
                                                'leadId' => $lead->id
                                            ])
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Father Section -->
                            @if(!empty($familyDetails['father']))
                                <div class="document-section mb-4">
                                    <div class="document-section-header" data-toggle="collapse" data-target="#fatherDocuments" aria-expanded="false">
                                        <h4 class="document-section-title">
                                            <i class="fas fa-chevron-down"></i>
                                            {{ $familyDetails['father']['name'] }} (Dependent - Father)
                                        </h4>
                                    </div>
                                    <div class="collapse" id="fatherDocuments">
                                        <div class="document-section-body">
                                            @include('lead-details.components.documents-checklist', [
                                                'applicantType' => 'father',
                                                'documents' => $documentChecklists['father'] ?? [],
                                                'leadId' => $lead->id
                                            ])
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Mother Section -->
                            @if(!empty($familyDetails['mother']))
                                <div class="document-section mb-4">
                                    <div class="document-section-header" data-toggle="collapse" data-target="#motherDocuments" aria-expanded="false">
                                        <h4 class="document-section-title">
                                            <i class="fas fa-chevron-down"></i>
                                            {{ $familyDetails['mother']['name'] }} (Dependent - Mother)
                                        </h4>
                                    </div>
                                    <div class="collapse" id="motherDocuments">
                                        <div class="document-section-body">
                                            @include('lead-details.components.documents-checklist', [
                                                'applicantType' => 'mother',
                                                'documents' => $documentChecklists['mother'] ?? [],
                                                'leadId' => $lead->id
                                            ])
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Spouse Section -->
                            @if(!empty($familyDetails['spouse']))
                                <div class="document-section mb-4">
                                    <div class="document-section-header" data-toggle="collapse" data-target="#spouseDocuments" aria-expanded="false">
                                        <h4 class="document-section-title">
                                            <i class="fas fa-chevron-down"></i>
                                            {{ $familyDetails['spouse']['name'] }} (Dependent - Spouse)
                                        </h4>
                                    </div>
                                    <div class="collapse" id="spouseDocuments">
                                        <div class="document-section-body">
                                            @include('lead-details.components.documents-checklist', [
                                                'applicantType' => 'spouse',
                                                'documents' => $documentChecklists['spouse'] ?? [],
                                                'leadId' => $lead->id
                                            ])
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Children Sections -->
                            @if(!empty($familyDetails['children']) && is_array($familyDetails['children']))
                                @foreach($familyDetails['children'] as $child)
                                    @php
                                        $childIndex = $child['index'];
                                        $childName = $child['name'];
                                        $childDocuments = [];
                                        foreach ($documentChecklists['children'] ?? [] as $childChecklist) {
                                            if (isset($childChecklist['child_index']) && $childChecklist['child_index'] == $childIndex) {
                                                $childDocuments = $childChecklist['documents'] ?? [];
                                                break;
                                            }
                                        }
                                    @endphp
                                    <div class="document-section mb-4">
                                        <div class="document-section-header" data-toggle="collapse" data-target="#child{{ $childIndex }}Documents" aria-expanded="false">
                                            <h4 class="document-section-title">
                                                <i class="fas fa-chevron-down"></i>
                                                {{ $childName }} (Dependent - Child {{ $childIndex }})
                                            </h4>
                                        </div>
                                        <div class="collapse" id="child{{ $childIndex }}Documents">
                                            <div class="document-section-body">
                                                @include('lead-details.components.documents-checklist', [
                                                    'applicantType' => 'child',
                                                    'childIndex' => $childIndex,
                                                    'documents' => $childDocuments,
                                                    'leadId' => $lead->id
                                                ])
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            
                            @if(empty($familyDetails['main_applicant']) && empty($familyDetails['father']) && empty($familyDetails['mother']) && empty($familyDetails['spouse']) && empty($familyDetails['children']))
                                <div class="alert alert-info">
                                    <p class="mb-0">No family information found. Please complete the lead form to see document checklists.</p>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info">
                                <p class="mb-0">No lead selected.</p>
                            </div>
                        @endif
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
