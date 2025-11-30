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
                $leadId = $row->newLead ? 'LEAD-' . str_pad($row->newLead->id, 4, '0', STR_PAD_LEFT) : '--';
                $clientName = $row->client_name ?? '--';
                $invoiceDate = $row->invoice_date ? $row->invoice_date->format(company()->date_format) : '--';
                
                return '<div class="invoice-info-simple f-14 text-darkest-grey">
                            Invoice ' . $leadId . ' | ' . $clientName . ' | ' . $invoiceDate . '
                        </div>';
            })
            ->addColumn('action', function ($row) {
                $action = '<div class="d-flex align-items-center justify-content-end">';
                
                // View button
                $action .= '<a href="javascript:;" class="view-account-btn mr-3" data-account-id="' . $row->id . '" style="color: #000; text-decoration: none;">
                                <i class="fa fa-eye" style="font-size: 18px;"></i>
                            </a>';
                
                // Delete button
                if (
                    $this->deleteAccountPermission == 'all'
                    || ($this->deleteAccountPermission == 'added' && user()->id == $row->added_by)
                    || ($this->deleteAccountPermission == 'owned' && user()->id == $row->newLead->lead_owner)
                ) {
                    $action .= '<a href="javascript:;" class="delete-account-btn" data-account-id="' . $row->id . '" style="color: #F5213D; text-decoration: none;">
                                    Delete
                                </a>';
                }
                
                $action .= '</div>';
                
                return $action;
            })
            ->rawColumns(['invoice_info', 'action']);
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
            'Invoice' => ['data' => 'invoice_info', 'name' => 'invoice_info', 'title' => 'Invoice', 'orderable' => false, 'searchable' => false],
            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->addClass('text-right')
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

