@if(isset($lead) && $lead && $lead->accounts && $lead->accounts->count() > 0)
    <div class="accounts-list">
        <div class="table-responsive">
            <table class="table table-hover border-0 w-100">
                <thead>
                    <tr>
                        <th>Invoice Info</th>
                        <th>Service Info</th>
                        <th>Amount Info</th>
                        <th>Agent Info</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lead->accounts->sortByDesc('created_at') as $account)
                        @php
                            $invoiceId = 'INV-' . str_pad($account->id, 4, '0', STR_PAD_LEFT);
                            $invoiceDate = $account->invoice_date ? $account->invoice_date->format(company()->date_format) : '--';
                            $clientName = $account->client_name ?? '--';
                            $service = $account->service ?? '--';
                            $price = $account->price ? number_format($account->price, 2) : '0.00';
                            $netAmount = $account->net_amount ? number_format($account->net_amount, 2) : '0.00';
                            $tax = $account->tax ?? '--';
                            $agentName = $account->agentUser ? $account->agentUser->name : '--';
                            try {
                                $currencySymbol = company()->currency ? company()->currency->currency_symbol : '₹';
                            } catch (\Exception $e) {
                                $currencySymbol = '₹';
                            }
                        @endphp
                        <tr>
                            <td>
                                <div class="invoice-info">
                                    <div class="invoice-id f-14 f-w-500 text-darkest-grey">{{ $invoiceId }}</div>
                                    <div class="invoice-date f-12 text-dark-grey">{{ $invoiceDate }}</div>
                                    <div class="client-name f-12 text-dark-grey">{{ $clientName }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="service-info">
                                    <div class="service-name f-14 f-w-500 text-darkest-grey">{{ $service }}</div>
                                    <div class="service-price f-12 text-dark-grey">Price: {{ $currencySymbol }} {{ $price }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="amount-info">
                                    <div class="net-amount f-14 f-w-500 text-darkest-grey">{{ $currencySymbol }} {{ $netAmount }}</div>
                                    <div class="tax-info f-12 text-dark-grey">Tax: {{ $tax }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="agent-info">
                                    <div class="agent-name f-14 f-w-500 text-darkest-grey">{{ $agentName }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="task_view">
                                    <div class="dropdown">
                                        <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
                                            id="dropdownMenuLink-{{ $account->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-options-vertical icons"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-{{ $account->id }}" tabindex="0">
                                            <a href="javascript:;" class="dropdown-item edit-account-btn" data-account-id="{{ $account->id }}">
                                                <i class="fa fa-edit mr-2"></i>{{ __('app.edit') }}
                                            </a>
                                            <a href="javascript:;" class="dropdown-item view-account-btn" data-account-id="{{ $account->id }}">
                                                <i class="fa fa-eye mr-2"></i>{{ __('app.view') }}
                                            </a>
                                            <a href="javascript:;" class="dropdown-item delete-account-btn" data-account-id="{{ $account->id }}">
                                                <i class="fa fa-trash mr-2"></i>{{ __('app.delete') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="text-center p-5">
        <p class="text-muted mb-3">No accounts have been created for this Lead.</p>
        <p class="text-muted">To create an Account, click on <i class="fa fa-plus"></i> at the top right corner</p>
    </div>
@endif

