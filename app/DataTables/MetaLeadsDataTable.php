<?php

namespace App\DataTables;

use App\Models\NewMetaLead;
use Yajra\DataTables\Html\Column;

class MetaLeadsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $table = (new NewMetaLead())->getTable();

        return datatables()
            ->eloquent($query)
            ->orderColumn('meta_lead_id', fn($query, $direction) => $query->orderBy("{$table}.meta_lead_id", $direction))
            ->orderColumn('full_name', fn($query, $direction) => $query->orderBy("{$table}.full_name", $direction))
            ->orderColumn('email', fn($query, $direction) => $query->orderBy("{$table}.email", $direction))
            ->orderColumn('phone', fn($query, $direction) => $query->orderBy("{$table}.phone", $direction))
            ->orderColumn('lead_created_time', fn($query, $direction) => $query->orderBy("{$table}.lead_created_time", $direction))
            ->orderColumn('status', fn($query, $direction) => $query->orderBy("{$table}.status", $direction))
            ->orderColumn('lead_number', fn($query, $direction) => $query->orderBy("{$table}.lead_number", $direction))
            ->addColumn('check', fn($row) => $this->checkBox($row, (bool) $row->new_lead_id))
            ->addColumn('meta_lead_id', fn($row) => $row->meta_lead_id)
            ->addColumn('full_name', fn($row) => $row->full_name ?? '—')
            ->addColumn('email', fn($row) => $row->email ?? '—')
            ->addColumn('phone', fn($row) => $row->phone ?? '—')
            ->addColumn('lead_created_time', function ($row) {
                return $row->lead_created_time
                    ? $row->lead_created_time->format(company()->date_format)
                    : '—';
            })
            ->addColumn('status', function ($row) {
                $badge = $row->status === 'new' ? 'info' : 'secondary';
                return '<span class="badge bg-' . $badge . '">' . ucfirst($row->status) . '</span>';
            })
            ->addColumn('lead_number', function ($row) {
                if ($row->new_lead_id && $row->lead_number) {
                    return '<a href="' . route('add-lead.index', ['lead_id' => $row->new_lead_id]) . '" class="text-primary">' . e($row->lead_number) . '</a>';
                }
                return $row->lead_number ?? '—';
            })
            ->addColumn('action', function ($row) {
                $url = route('meta-leads.detail', $row->id);
                return '<a href="javascript:;" class="btn btn-sm btn-primary view-meta-lead-btn" data-url="' . e($url) . '" data-toggle="tooltip" title="View details"><i class="fa fa-eye"></i> View</a>';
            })
            ->smart(false)
            ->setRowId(fn($row) => 'row-' . $row->id)
            ->rawColumns(['status', 'action', 'check', 'lead_number']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param NewMetaLead $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(NewMetaLead $model)
    {
        $query = $model->newQuery()
            ->where('user_id', user()->id)
            ->where('company_id', company()->id);

        // Global search across all columns
        $searchText = $this->request()->get('searchText');
        if (!empty($searchText)) {
            $term = '%' . $searchText . '%';
            $query->where(function ($q) use ($term) {
                $q->where('meta_lead_id', 'like', $term)
                    ->orWhere('full_name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone', 'like', $term)
                    ->orWhere('status', 'like', $term)
                    ->orWhere('lead_created_time', 'like', $term);
            });
        }

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        // Column 5 = Created Date (0=check, 1-4=Lead ID..Phone, 5=Created Date); default desc = latest first
        return $this->setBuilder('meta-leads-table', 5)
            ->orderBy(5, 'desc')
            ->stateSave(false)
            ->parameters([
                'order' => [[5, 'desc']],
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
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
            'check' => [
                'title' => '<input type="checkbox" name="select_all_meta_leads" id="select-all-meta-leads" onclick="selectAllMetaLeads(this)">',
                'exportable' => false,
                'orderable' => false,
                'searchable' => false,
                'data' => 'check',
                'name' => 'check',
            ],
            'Lead ID' => ['data' => 'meta_lead_id', 'name' => 'meta_lead_id', 'title' => 'Lead ID'],
            'Name' => ['data' => 'full_name', 'name' => 'full_name', 'title' => 'Name'],
            'Email' => ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            'Phone' => ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone'],
            'Created Date' => ['data' => 'lead_created_time', 'name' => 'lead_created_time', 'title' => 'Created Date'],
            'Status' => ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'orderable' => true, 'searchable' => false],
            'Assigned Lead' => ['data' => 'lead_number', 'name' => 'lead_number', 'title' => 'Assigned Lead', 'orderable' => true, 'searchable' => false],
            Column::computed('action', 'Actions')
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->addClass('text-right pr-20'),
        ];
    }
}
