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

                            <a class="nav-item nav-link f-15 active industry" href="{{ route('industry-settings.index') }}"
                                role="tab" aria-controls="nav-industry"
                                aria-selected="true">@lang('app.menu.industry')
                            </a>

                            <a class="nav-item nav-link f-15 sector" href="{{ route('industry-settings.index') }}?tab=sector"
                                role="tab" aria-controls="nav-sector"
                                aria-selected="true">@lang('app.menu.sector')
                            </a>

                        </div>
                    </nav>
                </div>
            </x-slot>

            <x-slot name="buttons">
                <div class="row">

                    <div class="col-md-12 mb-2">
                        <x-forms.button-primary icon="plus" id="addIndustry" class="industry-btn mb-2 d-none actionBtn">
                            @lang('app.addNew') @lang('app.menu.industry')
                        </x-forms.button-primary>

                        <x-forms.button-primary icon="plus" id="addSector" class="sector-btn mb-2 d-none actionBtn">
                            @lang('app.addNew') @lang('app.menu.sector')
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

            var iconHtml = '<i class="fa fa-plus mr-1"></i> ';

            if (activeTab === 'industry') {
                $('#addIndustry').html(iconHtml + '@lang('app.addNew') @lang('app.menu.industry')');
            } else if (activeTab === 'sector') {
                $('#addSector').html(iconHtml + '@lang('app.addNew') @lang('app.menu.sector')');
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

        /* delete industry */
        $('body').on('click', '.delete-industry', function() {
            var id = $(this).data('industry-id');
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
                    var url = "{{ route('industries.destroy', ':id') }}";
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
                                var reloadUrl = "{{ route('industry-settings.index') }}";
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

        $('body').on('click', '.edit-industry', function() {
            var industryId = $(this).data('industry-id');
            var url = "{{ route('industries.edit', ':id') }}";
            url = url.replace(':id', industryId);
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        /* delete sector */
        $('body').on('click', '.delete-sector', function() {
            var id = $(this).data('sector-id');
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
                    var url = "{{ route('sectors.destroy', ':id') }}";
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
                                var reloadUrl = "{{ route('industry-settings.index') }}?tab=sector";
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

        $('body').on('click', '.edit-sector', function() {
            var sectorId = $(this).data('sector-id');
            var url = "{{ route('sectors.edit', ':id') }}";
            url = url.replace(':id', sectorId);
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        /* open add industry modal */
        $('body').on('click', '#addIndustry', function() {
            var url = "{{ route('industries.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        /* open add sector modal */
        $('body').on('click', '#addSector', function() {
            var url = "{{ route('sectors.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

    </script>
@endpush
