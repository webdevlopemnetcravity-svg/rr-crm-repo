<div class="tab-content px-4 pb-4" id="templateDocumentTab">
    <!-- Tab Header -->
    <div class="tab-section-header">
        <div class="tab-section-header-content">
            <h3 class="tab-section-title">Template Document</h3>
        </div>
    </div>
    <!-- Tab Content Area -->
    <div class="tab-section-content">

        <!-- Available Documents Section -->
        <div class="available-documents-section">
            <div class="info-section-title mb-3">
                <h4 class="f-16 font-weight-bold">Available Documents</h4>
            </div>
            <div class="documents-list">
                @forelse($templateDocuments as $document)
                    <div class="document-card mb-3 p-3 bg-light rounded">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center flex-grow-1">
                                <div class="document-icon mr-3">
                                    <i class="fa {{ $document->icon }} {{ $document->file_type_color }}" style="font-size: 1.5rem;"></i>
                                </div>
                                <div class="document-details flex-grow-1">
                                    <div class="document-name f-14 font-weight-bold mb-1">{{ $document->name }}</div>
                                    <div class="document-meta f-12 text-dark-grey">
                                        @if($document->file_size)
                                            @php
                                                $fileSizeKB = $document->file_size / 1024;
                                                $fileSizeMB = $fileSizeKB / 1024;
                                                $displaySize = $fileSizeMB >= 1 ? number_format($fileSizeMB, 2) . ' MB' : number_format($fileSizeKB, 2) . ' KB';
                                            @endphp
                                            <span class="mr-3">Size: {{ $displaySize }}</span>
                                        @endif
                                        @if($document->created_at)
                                            <span>Uploaded: {{ $document->created_at->format('d-m-Y h:i A') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="document-actions d-flex align-items-center">
                                @if($document->file_path)
                                    <a href="{{ $document->file_url }}" target="_blank" class="btn btn-sm btn-info mr-2" title="View Document">
                                        <i class="fa fa-eye mr-1"></i> View
                                    </a>
                                    <button class="btn btn-sm document-email-btn mr-2 send-template-email" 
                                            data-lead-id="{{ $lead->id ?? '' }}" 
                                            data-document-id="{{ $document->id }}" 
                                            title="Send via Email">
                                        <i class="fa fa-envelope mr-1"></i> Email
                                    </button>
                                    <button class="btn btn-sm document-whatsapp-btn send-template-whatsapp" 
                                            data-lead-id="{{ $lead->id ?? '' }}" 
                                            data-document-id="{{ $document->id }}" 
                                            title="Send via WhatsApp">
                                        <i class="fa fa-whatsapp mr-1"></i> WhatsApp
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <p class="text-muted">No template documents available.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

