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
                <button type="button" class="btn btn-sm btn-primary add-required-document-btn" 
                        data-applicant-type="main_applicant"
                        data-section-name="{{ $familyDetails['main_applicant']['name'] }} (Main Applicant)">
                    <i class="fa fa-plus"></i> Add Required Document
                </button>
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
                <button type="button" class="btn btn-sm btn-primary add-required-document-btn" 
                        data-applicant-type="father"
                        data-section-name="{{ $familyDetails['father']['name'] }} (Dependent - Father)">
                    <i class="fa fa-plus"></i> Add Required Document
                </button>
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
                <button type="button" class="btn btn-sm btn-primary add-required-document-btn" 
                        data-applicant-type="mother"
                        data-section-name="{{ $familyDetails['mother']['name'] }} (Dependent - Mother)">
                    <i class="fa fa-plus"></i> Add Required Document
                </button>
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
                <button type="button" class="btn btn-sm btn-primary add-required-document-btn" 
                        data-applicant-type="spouse"
                        data-section-name="{{ $familyDetails['spouse']['name'] }} (Dependent - Spouse)">
                    <i class="fa fa-plus"></i> Add Required Document
                </button>
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
                    <button type="button" class="btn btn-sm btn-primary add-required-document-btn" 
                            data-applicant-type="child"
                            data-child-index="{{ $childIndex }}"
                            data-section-name="{{ $childName }} (Dependent - Child {{ $childIndex }})">
                        <i class="fa fa-plus"></i> Add Required Document
                    </button>
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

