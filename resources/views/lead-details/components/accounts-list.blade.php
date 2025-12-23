@if(isset($lead) && $lead && $lead->accounts && $lead->accounts->count() > 0)
    <div class="accounts-list">
        <div class="list-group">
            @foreach($lead->accounts->sortByDesc('created_at') as $account)
                @php
                    $leadId = 'LEAD-' . str_pad($lead->id, 4, '0', STR_PAD_LEFT);
                    $invoiceDate = $account->invoice_date ? $account->invoice_date->format(company()->date_format) : '--';
                    $clientName = $account->client_name ?? '--';
                @endphp
                <div class="list-group-item d-flex justify-content-between align-items-center" style="border: none; border-bottom: 1px solid #E0E0E0; padding: 16px 0;">
                    <div class="invoice-info-simple f-14 text-darkest-grey">
                        Invoice {{ $leadId }} | {{ $clientName }} | {{ $invoiceDate }}
                        @php
                            $status = $account->status ?? 'pending';
                            $statusClass = $status === 'received' ? 'badge-success' : 'badge-warning';
                            $statusText = ucfirst($status);
                        @endphp
                        <span class="badge {{ $statusClass }} ml-2" style="font-size: 11px; padding: 4px 8px;">{{ $statusText }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <select class="form-control form-control-sm account-status-select mr-3" data-account-id="{{ $account->id }}" style="width: 120px; height: 30px; font-size: 12px;">
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="received" {{ $status === 'received' ? 'selected' : '' }}>Received</option>
                        </select>
                        <a href="javascript:;" class="view-account-btn mr-3" data-account-id="{{ $account->id }}" style="color: #000; text-decoration: none;">
                            <i class="fa fa-eye" style="font-size: 18px;"></i>
                        </a>
                        <a href="javascript:;" class="delete-account-btn" data-account-id="{{ $account->id }}" style="color: #F5213D; text-decoration: none;">
                            Delete
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="text-center p-5">
        <p class="text-muted mb-3">No accounts have been created for this Lead.</p>
        <p class="text-muted">To create an Account, click on <i class="fa fa-plus"></i> at the top right corner</p>
    </div>
@endif

