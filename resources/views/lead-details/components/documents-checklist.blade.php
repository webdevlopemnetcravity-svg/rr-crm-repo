<div class="table-responsive">
    <table class="table document-checklist-table">
        <thead>
            <tr>
                <th>DOCUMENT TYPE/NAME</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($documents) && is_array($documents) && count($documents) > 0)
                @foreach($documents as $document)
                    @php
                        $hasFile = !empty($document['file_url']);
                        $documentUrl = $hasFile ? $document['file_url'] : null;
                        $documentKey = $document['key'] ?? '';
                        $documentName = $document['name'] ?? 'N/A';
                        $status = $document['status'] ?? 'pending';
                    @endphp
                    <tr>
                        <td>
                            {{ $documentName }}
                        </td>
                        <td>
                            @if($hasFile)
                                <div class="document-action-group">
                                    @if($documentUrl)
                                        <a href="{{ $documentUrl }}" target="_blank" class="document-view-icon" title="View Document">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('lead-details.download-document', ['leadId' => $leadId, 'documentKey' => $documentKey, 'applicantType' => $applicantType, 'childIndex' => $childIndex ?? null]) }}" target="_blank" class="document-view-icon" title="View Document">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    <a href="#" class="document-change-link" 
                                       data-document-key="{{ $documentKey }}" 
                                       data-document-name="{{ $documentName }}"
                                       data-applicant-type="{{ $applicantType }}"
                                       data-child-index="{{ $childIndex ?? '' }}"
                                       title="Change Document">
                                        Change
                                    </a>
                                </div>
                            @else
                                <button type="button" class="btn btn-sm btn-success document-upload-btn" 
                                        data-document-key="{{ $documentKey }}" 
                                        data-document-name="{{ $documentName }}"
                                        data-applicant-type="{{ $applicantType }}"
                                        data-child-index="{{ $childIndex ?? '' }}">
                                    Upload
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="2" class="text-center p-5">
                        <p class="text-muted">
                            @if($applicantType === 'main_applicant')
                                No main applicant documents configured. Please add documents in Document Settings.
                            @else
                                No dependent documents configured. Please add documents in Document Settings > Depends Documents.
                            @endif
                        </p>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

