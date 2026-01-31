<div class="modal-header">
    <h5 class="modal-title">Meta Pages</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="d-flex justify-content-between mb-3">
        <h6 class="mb-0">Facebook Pages</h6>
        <x-forms.button-primary icon="sync" id="sync-meta-pages-btn" class="btn-sm">
            Sync Pages
        </x-forms.button-primary>
    </div>

    <div class="table-responsive">
        <table class="table table-hover border-0 w-100" id="meta-pages-table">
            <thead>
                <tr>
                    <th>Page Name</th>
                    <th>Category</th>
                    <th>Followers</th>
                    <th>Likes</th>
                    <th>Status</th>
                    <th>Last Synced</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($metaPages && $metaPages->count() > 0)
                    @foreach($metaPages as $page)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($page->page_picture_url)
                                        <img src="{{ $page->page_picture_url }}" alt="{{ $page->page_name }}" class="rounded-circle mr-2" style="width: 32px; height: 32px;">
                                    @endif
                                    <span>{{ $page->page_name }}</span>
                                </div>
                            </td>
                            <td>{{ $page->page_category ?? '-' }}</td>
                            <td>{{ $page->page_followers_count ?? '-' }}</td>
                            <td>{{ $page->page_likes_count ?? '-' }}</td>
                            <td>
                                @if($page->sync_status == 'synced')
                                    <span class="badge badge-success">Synced</span>
                                @elseif($page->sync_status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-danger">Failed</span>
                                @endif
                            </td>
                            <td>{{ $page->last_synced_at ? $page->last_synced_at->format('M d, Y H:i') : '-' }}</td>
                            <td>
                                <div class="task_view">
                                    <a href="https://facebook.com/{{ $page->page_id }}" target="_blank" class="taskView" data-toggle="tooltip" data-original-title="View on Facebook">
                                        <i class="fa fa-external-link"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <p class="text-muted mb-0">No Meta pages found. Click "Sync Pages" to fetch pages from Facebook.</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

<script>
    (function() {
        // Use event delegation so handler works after modal content is replaced
        $(document).off('click', '#sync-meta-pages-btn').on('click', '#sync-meta-pages-btn', function() {
            var button = $(this);
            var originalHtml = button.html();
            
            // Disable button and show loading
            button.prop('disabled', true);
            button.html('<i class="fa fa-spinner fa-spin"></i> Syncing...');

            $.ajax({
                url: "{{ route('meta-pages.sync') }}",
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
                        // Reload modal content to show updated list
                        var modalUrl = "{{ route('meta-pages.modal') }}";
                        var $modalContent = $(MODAL_LG).find('.modal-content');
                        if ($modalContent.length) {
                            $modalContent.load(modalUrl, function() {
                                // Content updated; button is now fresh "Sync Pages"
                                if (typeof $('[data-toggle="tooltip"]').tooltip === 'function') {
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            });
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
                    var errorMessage = 'An error occurred while syncing Meta pages.';
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
