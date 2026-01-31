@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatable_css')
@endpush

@section('content')
    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        
        <!-- Task Box Start -->
        <div class="d-flex flex-column w-tables rounded mt-1 bg-white table-responsive">
            <div class="d-block d-lg-flex d-md-flex justify-content-between align-items-center p-3 pb-0">
                <div class="task-search d-flex py-1 px-0 align-items-center" style="max-width: 320px;">
                    <div class="input-group bg-grey rounded">
                        <div class="input-group-prepend">
                            <span class="input-group-text border-0 bg-additional-grey">
                                <i class="fa fa-search f-13 text-dark-grey"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control f-14 p-1 border-additional-grey" id="meta-leads-search"
                               placeholder="Search in all columns...">
                    </div>
                </div>
                <div class="mt-2 mt-lg-0 mt-md-0 ml-0">
                    <x-forms.button-primary icon="sync" id="sync-meta-leads-btn">
                        Sync Meta Leads
                    </x-forms.button-primary>
                    <x-forms.button-primary class="ml-2" id="view-meta-pages-btn">
                        View Meta Pages
                    </x-forms.button-primary>
                    <x-forms.button-primary class="ml-2" id="view-meta-forms-btn">
                        View Meta Forms
                    </x-forms.button-primary>
                    <x-forms.button-primary class="btn-sm ml-2" id="move-to-lead-btn" icon="arrow-right" disabled>
                        Move to Lead
                    </x-forms.button-primary>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white table-responsive">
            <div id="table-actions" class="mb-2"></div>
            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}
        </div>
        <!-- Task Box End -->
    </div>
    <!-- CONTENT WRAPPER END -->
@endsection

@push('scripts')
    @include('sections.datatable_js')
    <script>
        $(document).ready(function() {
            // Search in all columns: pass searchText to DataTable and redraw
            $('#meta-leads-table').on('preXhr.dt', function(e, settings, data) {
                data['searchText'] = $('#meta-leads-search').val() || '';
            });

            var metaLeadsSearchTimeout;
            $('#meta-leads-search').on('keyup', function() {
                var that = this;
                clearTimeout(metaLeadsSearchTimeout);
                metaLeadsSearchTimeout = setTimeout(function() {
                    if (window.LaravelDataTables && window.LaravelDataTables['meta-leads-table']) {
                        window.LaravelDataTables['meta-leads-table'].ajax.reload();
                    }
                }, 350);
            });

            // Select all / single checkbox: enable "Move to Lead" when at least one row is selected
            function updateMoveToLeadButton() {
                var count = $('#meta-leads-table tbody input[name="datatable_ids[]"]:checked').length;
                $('#move-to-lead-btn').prop('disabled', count === 0);
            }
            window.selectAllMetaLeads = function(source) {
                var checked = $(source).prop('checked');
                $('#meta-leads-table tbody input[name="datatable_ids[]"]').prop('checked', checked);
                updateMoveToLeadButton();
            };
            $(document).on('change', '#meta-leads-table tbody input[name="datatable_ids[]"]', updateMoveToLeadButton);

            // Move to Lead: copy selected meta leads to lead-list (full_name, email, phone); assign lead number
            $('#move-to-lead-btn').on('click', function() {
                var ids = [];
                $('#meta-leads-table tbody input[name="datatable_ids[]"]:checked').each(function() {
                    ids.push($(this).val());
                });
                if (ids.length === 0) return;
                var btn = $(this);
                var originalHtml = btn.html();
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Moving...');
                $.ajax({
                    url: "{{ route('meta-leads.move-to-lead') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        meta_lead_ids: ids
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            try {
                                if (typeof toastr !== 'undefined' && typeof $.showToastr === 'function') {
                                    $.showToastr(response.message, 'success');
                                } else if (typeof toastr !== 'undefined') {
                                    toastr.success(response.message);
                                }
                            } catch (e) {}
                            if (window.LaravelDataTables && window.LaravelDataTables['meta-leads-table']) {
                                window.LaravelDataTables['meta-leads-table'].ajax.reload(function() {
                                    btn.prop('disabled', false).html(originalHtml);
                                    updateMoveToLeadButton(); // disable button after reload (no checkboxes selected)
                                }, false);
                                return;
                            }
                        } else {
                            try {
                                if (typeof toastr !== 'undefined' && typeof $.showToastr === 'function') {
                                    $.showToastr(response.message, 'error');
                                } else if (typeof toastr !== 'undefined') {
                                    toastr.error(response.message);
                                }
                            } catch (e) {}
                        }
                        btn.prop('disabled', false).html(originalHtml);
                        updateMoveToLeadButton();
                    },
                    error: function(xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Failed to move leads.';
                        try {
                            if (typeof toastr !== 'undefined' && typeof $.showToastr === 'function') {
                                $.showToastr(msg, 'error');
                            } else if (typeof toastr !== 'undefined') {
                                toastr.error(msg);
                            }
                        } catch (e) {}
                        btn.prop('disabled', false).html(originalHtml);
                        updateMoveToLeadButton();
                    }
                });
            });

            // View Lead Details: open modal with lead detail (use delegation for DataTable rows)
            $(document).on('click', '.view-meta-lead-btn', function() {
                var url = $(this).data('url');
                if (url) {
                    $(MODAL_LG + ' ' + MODAL_HEADING).html('Lead Details');
                    $.ajaxModal(MODAL_LG, url);
                }
            });

            // View Meta Pages button click handler
            $('#view-meta-pages-btn').on('click', function() {
                var url = "{{ route('meta-pages.modal') }}";
                $(MODAL_LG + ' ' + MODAL_HEADING).html('Meta Pages');
                $.ajaxModal(MODAL_LG, url);
            });

            // View Meta Forms button click handler (same dialog style as Meta Pages)
            $('#view-meta-forms-btn').on('click', function() {
                var url = "{{ route('meta-forms.modal') }}";
                $(MODAL_LG + ' ' + MODAL_HEADING).html('Meta Forms');
                $.ajaxModal(MODAL_LG, url);
            });

            // Sync Meta Leads button click handler
            $('#sync-meta-leads-btn').on('click', function() {
                var button = $(this);
                var originalText = button.html();
                
                // Disable button and show loading
                button.prop('disabled', true);
                button.html('<i class="fa fa-spinner fa-spin"></i> Syncing...');

                $.ajax({
                    url: "{{ route('meta-leads.sync') }}",
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
                            // Reload the page after successful sync
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            try {
                                if (typeof toastr !== 'undefined' && typeof $.showToastr === 'function') {
                                    $.showToastr(response.message, 'error');
                                } else if (typeof toastr !== 'undefined') {
                                    toastr.error(response.message);
                                }
                            } catch (e) { /* toast not available */ }
                            button.prop('disabled', false);
                            button.html(originalText);
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = 'An error occurred while syncing Meta leads.';
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
                        button.html(originalText);
                    }
                });
            });
        });
    </script>
@endpush
