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

                            <a class="nav-item nav-link f-15 active country" href="{{ route('new-country-master.index') }}"
                                role="tab" aria-controls="nav-country"
                                aria-selected="true">@lang('app.menu.country')
                            </a>

                            <a class="nav-item nav-link f-15 state" href="{{ route('new-country-master.index') }}?tab=state"
                                role="tab" aria-controls="nav-state"
                                aria-selected="true">@lang('app.menu.state')
                            </a>

                            <a class="nav-item nav-link f-15 city" href="{{ route('new-country-master.index') }}?tab=city"
                                role="tab" aria-controls="nav-city"
                                aria-selected="true">@lang('app.menu.city')
                            </a>

                            {{-- <button type="button" class="btn btn-outline-secondary btn-sm ml-3 align-self-center" id="importCountriesCsvBtn">Import Countries</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm ml-2 align-self-center" id="importStatesCsvBtn">Import States</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm ml-2 align-self-center" id="importCitiesCsvBtn">Import Cities</button> --}}

                        </div>
                    </nav>
                </div>
            </x-slot>

            <x-slot name="buttons">
                <div class="row">

                    <div class="col-md-12 mb-2">
                        <x-forms.button-primary icon="plus" id="addCountry" class="country-btn mb-2 d-none actionBtn">
                            @lang('app.addNew') @lang('app.menu.country')
                        </x-forms.button-primary>

                        <x-forms.button-primary icon="plus" id="addState" class="state-btn mb-2 d-none actionBtn">
                            @lang('app.addNew') @lang('app.menu.state')
                        </x-forms.button-primary>

                        <x-forms.button-primary icon="plus" id="addCity" class="city-btn mb-2 d-none actionBtn">
                            @lang('app.addNew') @lang('app.menu.city')
                        </x-forms.button-primary>
                    </div>

                </div>
            </x-slot>

            {{-- include tabs here --}}
            <div id="nav-tabContent">
                <div class="flex-wrap">
                    @include($view)
                </div>
            </div>

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

        /* delete country */
        $('body').on('click', '.delete-country', function() {
            var id = $(this).data('country-id');
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
                    var url = "{{ route('countries.destroy', ':id') }}";
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
                                var reloadUrl = "{{ route('new-country-master.index') }}";
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

        $('body').on('click', '.edit-country', function() {
            var countryId = $(this).data('country-id');
            var url = "{{ route('countries.edit', ':id') }}";
            url = url.replace(':id', countryId);

            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        /* delete state */
        $('body').on('click', '.delete-state', function() {
            var id = $(this).data('state-id');
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
                    var url = "{{ route('states.destroy', ':id') }}";
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
                                var reloadUrl = "{{ route('new-country-master.index') }}?tab=state";
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

        $('body').on('click', '.edit-state', function() {
            var stateId = $(this).data('state-id');
            var url = "{{ route('states.edit', ':id') }}";
            url = url.replace(':id', stateId);

            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        /* delete city */
        $('body').on('click', '.delete-city', function() {
            var id = $(this).data('city-id');
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
                    var url = "{{ route('cities.destroy', ':id') }}";
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
                                var reloadUrl = "{{ route('new-country-master.index') }}?tab=city";
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

        $('body').on('click', '.edit-city', function() {
            var cityId = $(this).data('city-id');
            var url = "{{ route('cities.edit', ':id') }}";
            url = url.replace(':id', cityId);

            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        /* open add country modal */
        $('body').on('click', '#addCountry', function() {
            var url = "{{ route('countries.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        /* open add state modal */
        $('body').on('click', '#addState', function() {
            var url = "{{ route('states.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        /* open add city modal */
        $('body').on('click', '#addCity', function() {
            var url = "{{ route('cities.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        function runCsvImport(url, btnId) {
            var $btn = $('#' + btnId);
            $btn.prop('disabled', true);
            $.ajax({
                url: url,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                blockUI: true,
                success: function(response) {
                    $btn.prop('disabled', false);
                    if (response.status === 'success') {
                        Swal.fire({ icon: 'success', text: response.message || 'Import completed.' });
                        var reloadUrl = "{{ route('new-country-master.index') }}";
                        $.easyAjax({
                            url: reloadUrl,
                            blockUI: true,
                            container: "#nav-tabContent",
                            success: function(res) {
                                if (res.status === "success") {
                                    $('#nav-tabContent .flex-wrap').html(res.html);
                                    init('#nav-tabContent');
                                }
                            }
                        });
                    } else {
                        Swal.fire({ icon: 'error', text: response.message || 'Import failed.' });
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false);
                    var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Import failed.';
                    Swal.fire({ icon: 'error', text: msg });
                }
            });
        }

        $('body').on('click', '#importCountriesCsvBtn', function() {
            runCsvImport("{{ route('new-country-master.import-countries-csv') }}", 'importCountriesCsvBtn');
        });
        $('body').on('click', '#importStatesCsvBtn', function() {
            runCsvImport("{{ route('new-country-master.import-states-csv') }}", 'importStatesCsvBtn');
        });
        $('body').on('click', '#importCitiesCsvBtn', function() {
            runCsvImport("{{ route('new-country-master.import-cities-csv') }}", 'importCitiesCsvBtn');
        });

    </script>
@endpush
