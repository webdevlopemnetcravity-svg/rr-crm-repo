<div class="modal-header">
    <h5 class="modal-title">Lead Details</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    @php
        $lead = $metaLead;
        $fieldDataArray = $lead->field_data ? json_decode($lead->field_data, true) : [];
        $fieldDataArray = is_array($fieldDataArray) ? $fieldDataArray : [];
    @endphp

    <div class="row mb-3">
        <div class="col-md-6">
            <table class="table table-sm table-bordered mb-0">
                <tbody>
                    <tr>
                        <th class="bg-light" style="width: 140px;">Lead ID</th>
                        <td>{{ $lead->meta_lead_id ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Name</th>
                        <td>{{ $lead->full_name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Email</th>
                        <td>{{ $lead->email ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Phone</th>
                        <td>{{ $lead->phone ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Created Date</th>
                        <td>{{ $lead->lead_created_time ? $lead->lead_created_time->format(company()->date_format . ' ' . (company()->time_format ?? 'H:i')) : '—' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Status</th>
                        <td><span class="badge bg-{{ $lead->status === 'new' ? 'info' : 'secondary' }}">{{ ucfirst($lead->status) }}</span></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Page Name</th>
                        <td>{{ $lead->page_name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Form Name</th>
                        <td>{{ $lead->form_name ?? '—' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <h6 class="mb-2">Field Data (from Facebook)</h6>
    @if(count($fieldDataArray) > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th class="bg-light">Field Name</th>
                        <th class="bg-light">Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fieldDataArray as $field)
                        <tr>
                            <td>{{ $field['name'] ?? '—' }}</td>
                            <td>
                                @if(isset($field['values']) && is_array($field['values']))
                                    {{ implode(', ', $field['values']) }}
                                @else
                                    {{ $field['values'] ?? '—' }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted mb-0">No field data available.</p>
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
