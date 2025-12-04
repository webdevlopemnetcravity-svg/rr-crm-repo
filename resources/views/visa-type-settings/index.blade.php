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

                            <a class="nav-item nav-link f-15 active visa-type" href="{{ route('visa-type-settings.index') }}"
                                role="tab" aria-controls="nav-visaType"
                                aria-selected="true">@lang('app.menu.visaType')
                            </a>

                            <a class="nav-item nav-link f-15 subclass" href="{{ route('visa-type-settings.index') }}?tab=subclass"
                                role="tab" aria-controls="nav-subclass"
                                aria-selected="true">@lang('app.menu.subclass')
                            </a>

                        </div>
                    </nav>
                </div>
            </x-slot>

            <x-slot name="buttons">
                <div class="row">

                    <div class="col-md-12 mb-2">
                        <x-forms.button-primary icon="plus" id="addVisaType" class="visa-type-btn mb-2 d-none actionBtn">
                            @lang('app.addNew') @lang('app.menu.visaType')
                        </x-forms.button-primary>

                        <x-forms.button-primary icon="plus" id="addSubclass" class="subclass-btn mb-2 d-none actionBtn">
                            @lang('app.addNew') @lang('app.menu.subclass')
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
            
            if (activeTab === 'visa-type') {
                $('#addVisaType').html(iconHtml + '@lang('app.addNew') @lang('app.menu.visaType')');
            } else if (activeTab === 'subclass') {
                $('#addSubclass').html(iconHtml + '@lang('app.addNew') @lang('app.menu.subclass')');
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

        /* delete visa type */
        $('body').on('click', '.delete-visa-type', function() {
            var id = $(this).data('visa-type-id');
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
                    var url = "{{ route('visaTypes.destroy', ':id') }}";
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
                                // Reload only the visa-type tab content
                                var reloadUrl = "{{ route('visa-type-settings.index') }}";
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

        $('body').on('click', '.edit-visa-type', function() {
            var visaTypeId = $(this).data('visa-type-id');
            var url = "{{ route('visaTypes.edit', ':id') }}";
            url = url.replace(':id', visaTypeId);

            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        /* delete subclass */
        $('body').on('click', '.delete-subclass', function() {
            var id = $(this).data('subclass-id');
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
                    var url = "{{ route('subclasses.destroy', ':id') }}";
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
                                // Reload only the subclass tab content
                                var reloadUrl = "{{ route('visa-type-settings.index') }}?tab=subclass";
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

        $('body').on('click', '.edit-subclass', function() {
            var subclassId = $(this).data('subclass-id');
            var url = "{{ route('subclasses.edit', ':id') }}";
            url = url.replace(':id', subclassId);

            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        /* open add visa type modal */
        $('body').on('click', '#addVisaType', function() {
            var url = "{{ route('visaTypes.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        /* open add subclass modal */
        $('body').on('click', '#addSubclass', function() {
            var url = "{{ route('subclasses.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

    </script>
@endpush

