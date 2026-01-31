<div class="modal-header">
    <h5 class="modal-title">Meta Forms</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="d-flex justify-content-between mb-3">
        <h6 class="mb-0">Lead Gen Forms (by Page)</h6>
        <x-forms.button-primary icon="sync" id="sync-meta-forms-btn" class="btn-sm">
            Sync Forms
        </x-forms.button-primary>
    </div>

    <div class="table-responsive">
        <table class="table table-hover border-0 w-100" id="meta-forms-table">
            <thead>
                <tr>
                    <th>Form Name</th>
                    <th>Page Name</th>
                    <th>Form ID</th>
                    <th>Status</th>
                    <th>Last Synced</th>
                </tr>
            </thead>
            <tbody>
                @foreach($metaForms ?? [] as $form)
                    <tr>
                        <td>{{ $form->form_name ?? '-' }}</td>
                        <td>{{ $form->page_name ?? '-' }}</td>
                        <td><code class="small">{{ $form->form_id }}</code></td>
                        <td>
                            @if($form->sync_status == 'synced')
                                <span class="badge badge-success">Synced</span>
                            @elseif($form->sync_status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">Failed</span>
                            @endif
                        </td>
                        <td>{{ $form->last_synced_at ? $form->last_synced_at->format('M d, Y H:i') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

<script>
    (function() {
        // Initialize DataTable on Meta Forms table (client-side: search, sort, paging)
        var $metaFormsTable = $('#meta-forms-table');
        if ($metaFormsTable.length && typeof $.fn.DataTable !== 'undefined' && !$.fn.DataTable.isDataTable($metaFormsTable[0])) {
            $metaFormsTable.DataTable({
                pageLength: 10,
                order: [[0, 'asc']],
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                language: {
                    emptyTable: {!! json_encode($noDataMessage ?? 'No Meta forms found. Click "Sync Forms" to fetch lead gen forms from all pages.') !!},
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ forms",
                    infoEmpty: "Showing 0 to 0 of 0 forms",
                    infoFiltered: "(filtered from _MAX_ total forms)"
                }
            });
        }

        $(document).off('click', '#sync-meta-forms-btn').on('click', '#sync-meta-forms-btn', function() {
            var button = $(this);
            var originalHtml = button.html();

            button.prop('disabled', true);
            button.html('<i class="fa fa-spinner fa-spin"></i> Syncing...');

            $.ajax({
                url: "{{ route('meta-forms.sync') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.status == 'success') {
                        try {
                            if (typeof toastr !== 'undefined' && typeof $.showToastr === 'function') {
                                $.showToastr(response.message, 'success');
                            } else if (typeof toastr !== 'undefined') {
                                toastr.success(response.message);
                            }
                        } catch (e) { /* toast not available */ }
                        var modalUrl = "{{ route('meta-forms.modal') }}";
                        var $modalContent = $(MODAL_LG).find('.modal-content');
                        if ($modalContent.length) {
                            $modalContent.load(modalUrl);
                        } else {
                            button.prop('disabled', false);
                            button.html(originalHtml);
                        }
                    } else {
                        try {
                            if (typeof toastr !== 'undefined' && typeof $.showToastr === 'function') {
                                $.showToastr(response.message, 'error');
                            } else if (typeof toastr !== 'undefined') {
                                toastr.error(response.message);
                            }
                        } catch (e) { /* toast not available */ }
                        button.prop('disabled', false);
                        button.html(originalHtml);
                    }
                },
                error: function(xhr) {
                    var errorMessage = 'An error occurred while syncing Meta forms.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    try {
                        if (typeof toastr !== 'undefined' && typeof $.showToastr === 'function') {
                            $.showToastr(errorMessage, 'error');
                        } else if (typeof toastr !== 'undefined') {
                            toastr.error(errorMessage);
                        }
                    } catch (e) { /* toast not available */ }
                    button.prop('disabled', false);
                    button.html(originalHtml);
                }
            });
        });
    })();
</script>
