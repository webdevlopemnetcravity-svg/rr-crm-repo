<?php

namespace App\DataTables;

use App\Models\NewLeadAccount;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class NewLeadAccountDataTable extends BaseDataTable
{
    private $editAccountPermission;
    private $deleteAccountPermission;

    public function __construct()
    {
        parent::__construct();
        $this->editAccountPermission = user()->permission('edit_lead');
        $this->deleteAccountPermission = user()->permission('delete_lead');
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('invoice_info', function ($row) {
                $invoiceId = 'INV-' . str_pad($row->id, 4, '0', STR_PAD_LEFT);
                $invoiceDate = $row->invoice_date ? $row->invoice_date->format(company()->date_format) : '--';
                $clientName = $row->client_name ?? '--';
                
                return '<div class="invoice-info">
                            <div class="invoice-id f-14 f-w-500 text-darkest-grey">' . $invoiceId . '</div>
                            <div class="invoice-date f-12 text-dark-grey">' . $invoiceDate . '</div>
                            <div class="client-name f-12 text-dark-grey">' . $clientName . '</div>
                        </div>';
            })
            ->addColumn('service_info', function ($row) {
                $service = $row->service ?? '--';
                $price = $row->price ? number_format($row->price, 2) : '0.00';
                try {
                    $currencySymbol = company()->currency ? company()->currency->currency_symbol : '₹';
                } catch (\Exception $e) {
                    $currencySymbol = '₹';
                }
                
                return '<div class="service-info">
                            <div class="service-name f-14 f-w-500 text-darkest-grey">' . $service . '</div>
                            <div class="service-price f-12 text-dark-grey">Price: ' . $currencySymbol . ' ' . $price . '</div>
                        </div>';
            })
            ->addColumn('amount_info', function ($row) {
                $netAmount = $row->net_amount ? number_format($row->net_amount, 2) : '0.00';
                $tax = $row->tax ?? '--';
                try {
                    $currencySymbol = company()->currency ? company()->currency->currency_symbol : '₹';
                } catch (\Exception $e) {
                    $currencySymbol = '₹';
                }
                
                return '<div class="amount-info">
                            <div class="net-amount f-14 f-w-500 text-darkest-grey">' . $currencySymbol . ' ' . $netAmount . '</div>
                            <div class="tax-info f-12 text-dark-grey">Tax: ' . $tax . '</div>
                        </div>';
            })
            ->addColumn('agent_info', function ($row) {
                $agentName = $row->agentUser ? $row->agentUser->name : '--';
                
                return '<div class="agent-info">
                            <div class="agent-name f-14 f-w-500 text-darkest-grey">' . $agentName . '</div>
                        </div>';
            })
            ->addColumn('action', function ($row) {
                $action = '<div class="task_view">
                        <div class="dropdown">
                            <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
                                id="dropdownMenuLink-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-options-vertical icons"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->id . '" tabindex="0">';

                $action .= '<a href="javascript:;" class="dropdown-item edit-account-btn" data-account-id="' . $row->id . '">
                                <i class="fa fa-edit mr-2"></i>' . __('app.edit') . '
                            </a>';

                $action .= '<a href="javascript:;" class="dropdown-item view-account-btn" data-account-id="' . $row->id . '">
                                <i class="fa fa-eye mr-2"></i>' . __('app.view') . '
                            </a>';

                if (
                    $this->deleteAccountPermission == 'all'
                    || ($this->deleteAccountPermission == 'added' && user()->id == $row->added_by)
                    || ($this->deleteAccountPermission == 'owned' && user()->id == $row->newLead->lead_owner)
                ) {
                    $action .= '<a href="javascript:;" class="dropdown-item delete-account-btn" data-account-id="' . $row->id . '">
                                    <i class="fa fa-trash mr-2"></i>' . __('app.delete') . '
                                </a>';
                }

                $action .= '</div>
                        </div>
                    </div>';

                return $action;
            })
            ->rawColumns(['invoice_info', 'service_info', 'amount_info', 'agent_info', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\NewLeadAccount $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(NewLeadAccount $model)
    {
        $request = $this->request();
        // Get lead_id from request parameter or route parameter
        $leadId = $request->get('lead_id');
        
        // If not in request, try to get from route
        if (!$leadId && request()->route('id')) {
            $leadId = request()->route('id');
        }

        $query = $model->newQuery()
            ->with(['agentUser', 'newLead', 'addedBy'])
            ->where('new_lead_id', $leadId);

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $request = $this->request();
        $leadId = $request->get('lead_id');
        
        // If lead_id is not in request, try to get from route
        if (!$leadId && request()->route('id')) {
            $leadId = request()->route('id');
        }
        
        // Build AJAX URL - ensure we use absolute URL with account prefix
        if ($leadId) {
            // Since all routes are under 'account' prefix, construct the full URL
            $ajaxUrl = url('/account/new-leads/accounts-data/' . $leadId);
        } else {
            $ajaxUrl = '#';
        }
        
        return $this->builder()
            ->setTableId('new-lead-accounts-table')
            ->columns($this->getColumns())
            ->minifiedAjax($ajaxUrl, '', [
                'data' => 'function(d) { d.lead_id = "' . ($leadId ?? '') . '"; }'
            ])
            ->orderBy(1)
            ->destroy(true)
            ->responsive()
            ->serverSide()
            ->stateSave(true)
            ->pageLength(10)
            ->processing()
            ->dom($this->domHtml)
            ->language(__('app.datatable'))
            ->parameters([
                'initComplete' => 'function () {
                    window.LaravelDataTables["new-lead-accounts-table"].buttons().container()
                        .appendTo( "#table-actions");
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $(".select-picker").selectpicker();
                }',
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'visible' => false],
            __('app.id') => ['data' => 'id', 'name' => 'id', 'visible' => false, 'title' => __('app.id')],
            'Invoice Info' => ['data' => 'invoice_info', 'name' => 'invoice_info', 'title' => 'Invoice Info', 'orderable' => false, 'searchable' => false],
            'Service Info' => ['data' => 'service_info', 'name' => 'service_info', 'title' => 'Service Info', 'orderable' => false, 'searchable' => false],
            'Amount Info' => ['data' => 'amount_info', 'name' => 'amount_info', 'title' => 'Amount Info', 'orderable' => false, 'searchable' => false],
            'Agent Info' => ['data' => 'agent_info', 'name' => 'agent_info', 'title' => 'Agent Info', 'orderable' => false, 'searchable' => false],
            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->addClass('text-right pr-20')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'new_lead_accounts_' . now()->format('Y-m-d-H-i-s');
    }
}

