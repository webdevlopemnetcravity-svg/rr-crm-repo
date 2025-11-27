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

