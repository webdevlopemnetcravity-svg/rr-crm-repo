@extends('layouts.app')

@section('content')

    <!-- SETTINGS START -->
    <div class="w-100 d-flex ">

        <x-setting-sidebar :activeMenu="$activeSettingMenu" />

        <x-setting-card>
            <x-slot name="header">
                <div class="s-b-n-header" id="tabs">
                    <nav class="tabs px-6 border-bottom-grey">
                        <div class="nav" id="nav-tab" role="tablist">

                            <a class="nav-item nav-link f-15 active main-documents" href="{{ route('lead-document-settings.index') }}"
                                role="tab" aria-controls="nav-mainDocuments"
                                aria-selected="true">@lang('app.menu.mainDocuments')
                            </a>

                            <a class="nav-item nav-link f-15 depends-documents" href="{{ route('lead-document-settings.index') }}?tab=depends-documents"
                                role="tab" aria-controls="nav-dependsDocuments"
                                aria-selected="true">@lang('app.menu.dependsDocuments')
                            </a>

                        </div>
                    </nav>
                </div>
            </x-slot>

            <x-slot name="buttons">
                <div class="row">

                    <div class="col-md-12 mb-2">
                        <x-forms.button-primary icon="plus" id="addMainDocument" class="main-documents-btn mb-2 d-none actionBtn">
                            @lang('app.addNew') @lang('app.menu.mainDocuments')
                        </x-forms.button-primary>

                        <x-forms.button-primary icon="plus" id="addDependsDocument" class="depends-documents-btn mb-2 d-none actionBtn">
                            @lang('app.addNew') @lang('app.menu.dependsDocuments')
                        </x-forms.button-primary>
                    </div>

                </div>
            </x-slot>

            {{-- include tabs here --}}
            @include($view)

        </x-setting-card>

    </div>
    <!-- SETTINGS END -->

@endsection

@push('scripts')
    <script>
        /* manage menu active class */
        $('.nav-item').removeClass('active');
        const activeTab = "{{ $activeTab }}";
        $('.' + activeTab).addClass('active');

        showBtn(activeTab);

        function showBtn(activeTab) {
            $('.actionBtn').addClass('d-none');
            var $activeBtn = $('.' + activeTab + '-btn');
            $activeBtn.removeClass('d-none');
            
            // Update button text based on active tab (always include icon)
            // The icon is always 'fa fa-plus mr-1' as per button-primary component
            var iconHtml = '<i class="fa fa-plus mr-1"></i> ';
            
            if (activeTab === 'main-documents') {
                $('#addMainDocument').html(iconHtml + '@lang('app.addNew') @lang('app.menu.mainDocuments')');
            } else if (activeTab === 'depends-documents') {
                $('#addDependsDocument').html(iconHtml + '@lang('app.addNew') @lang('app.menu.dependsDocuments')');
            }
        }

        $(document).on('show.bs.dropdown', '.table-responsive', function() {
            $('.table-responsive').css( "overflow", "inherit" );
        });

       $("body").on("click", "#editSettings .nav a", function(event) {
            event.preventDefault();

            $('.nav-item').removeClass('active');
            $(this).addClass('active');

            const requestUrl = this.href;

            $.easyAjax({
                url: requestUrl,
                blockUI: true,
                container: "#nav-tabContent",
                historyPush: true,
                success: function(response) {
                    if (response.status == "success") {
                        showBtn(response.activeTab);

                        $('#nav-tabContent .flex-wrap').html(response.html);
                        init('#nav-tabContent');
                    }
                }
            });
        });

        /* delete main document */
        $('body').on('click', '.delete-main-document', function() {
            var id = $(this).data('main-document-id');
            Swal.fire({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.recoverRecord')",
                icon: 'warning',
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "@lang('messages.confirmDelete')",
                cancelButtonText: "@lang('app.cancel')",
                customClass: {
                    confirmButton: 'btn btn-primary mr-3',
                    cancelButton: 'btn btn-secondary'
                },
                showClass: {
                    popup: 'swal2-noanimation',
                    backdrop: 'swal2-noanimation'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('mainDocuments.destroy', ':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        blockUI: true,
                        data: {
                            '_token': token,
                            '_method': 'DELETE'
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                // Reload only the main-documents tab content
                                var reloadUrl = "{{ route('lead-document-settings.index') }}";
                                $.easyAjax({
                                    url: reloadUrl,
                                    blockUI: true,
                                    container: "#nav-tabContent",
                                    success: function(response) {
                                        if (response.status == "success") {
                                            $('#nav-tabContent .flex-wrap').html(response.html);
                                            init('#nav-tabContent');
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
            });
        });

        $('body').on('click', '.edit-main-document', function() {
            var mainDocumentId = $(this).data('main-document-id');
            var url = "{{ route('mainDocuments.edit', ':id') }}";
            url = url.replace(':id', mainDocumentId);

            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        /* delete depends document */
        $('body').on('click', '.delete-depends-document', function() {
            var id = $(this).data('depends-document-id');
            Swal.fire({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.recoverRecord')",
                icon: 'warning',
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "@lang('messages.confirmDelete')",
                cancelButtonText: "@lang('app.cancel')",
                customClass: {
                    confirmButton: 'btn btn-primary mr-3',
                    cancelButton: 'btn btn-secondary'
                },
                showClass: {
                    popup: 'swal2-noanimation',
                    backdrop: 'swal2-noanimation'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('dependsDocuments.destroy', ':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        blockUI: true,
                        data: {
                            '_token': token,
                            '_method': 'DELETE'
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                // Reload only the depends-documents tab content
                                var reloadUrl = "{{ route('lead-document-settings.index') }}?tab=depends-documents";
                                $.easyAjax({
                                    url: reloadUrl,
                                    blockUI: true,
                                    container: "#nav-tabContent",
                                    success: function(response) {
                                        if (response.status == "success") {
                                            $('#nav-tabContent .flex-wrap').html(response.html);
                                            init('#nav-tabContent');
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
            });
        });

        $('body').on('click', '.edit-depends-document', function() {
            var dependsDocumentId = $(this).data('depends-document-id');
            var url = "{{ route('dependsDocuments.edit', ':id') }}";
            url = url.replace(':id', dependsDocumentId);

            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        /* open add main document modal */
        $('body').on('click', '#addMainDocument', function() {
            var url = "{{ route('mainDocuments.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        /* open add depends document modal */
        $('body').on('click', '#addDependsDocument', function() {
            var url = "{{ route('dependsDocuments.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

    </script>
@endpush

