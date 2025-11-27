<div class="table-responsive">
    <table class="table document-checklist-table">
        <thead>
            <tr>
                <th>DOCUMENT TYPE/NAME</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($allExpectedDocuments) && is_array($allExpectedDocuments) && !empty($allExpectedDocuments))
                @foreach($allExpectedDocuments as $document)
                    @php
                        $hasFile = !empty($document['file']);
                        $documentUrl = null;
                        if ($hasFile && isset($document['file']) && isset($document['folder']) && isset($lead) && $lead) {
                            try {
                                // Step 1 resume files don't have lead ID subfolder
                                $noLeadId = !empty($document['no_lead_id']);
                                $folder = $noLeadId ? $document['folder'] : $document['folder'] . '/' . $lead->id;
                                $filePath = $folder . '/' . $document['file'];
                                $documentUrl = asset_url_local_s3($filePath);
                            } catch (\Exception $e) {
                                $documentUrl = null;
                            }
                        }
                    @endphp
                    <tr>
                        <td>
                            {{ $document['name'] ?? 'N/A' }}
                            @if(!empty($document['required']))
                                <span class="text-danger">*</span>
                            @endif
                        </td>
                        <td>
                            @if($hasFile)
                                <div class="document-action-group">
                                    @if($documentUrl)
                                        <a href="{{ $documentUrl }}" target="_blank" class="document-view-icon" title="View Document">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('lead-details.download-document', ['leadId' => $lead->id, 'documentKey' => $document['key'] ?? '']) }}" target="_blank" class="document-view-icon" title="View Document">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    <a href="#" class="document-change-link" data-document-key="{{ $document['key'] ?? '' }}" data-document-name="{{ $document['name'] ?? '' }}" title="Change Document">
                                        Change
                                    </a>
                                </div>
                            @else
                                <button type="button" class="btn btn-sm btn-success document-upload-btn" data-document-key="{{ $document['key'] ?? '' }}" data-document-name="{{ $document['name'] ?? '' }}">
                                    Upload
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="2" class="text-center p-5">
                        <p class="text-muted">No documents found</p>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

