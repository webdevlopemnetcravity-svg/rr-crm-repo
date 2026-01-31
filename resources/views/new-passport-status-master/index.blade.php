@extends('layouts.app')

@section('content')

    <!-- SETTINGS START -->
    <div class="w-100 d-flex ">

        @include('sections.setting-sidebar')

        <x-setting-card>
            <x-slot name="header">
                <div class="s-b-n-header" id="tabs">
                    <h2 class="mb-0 p-20 f-21 font-weight-normal  border-bottom-grey">
                        @lang($pageTitle)</h2>
                </div>
            </x-slot>

            @if (in_array('admin', user_roles()))
                <x-slot name="buttons">
                    <div class="row">

                        <div class="col-md-12 mb-2">
                            <x-forms.button-primary icon="plus" id="add-passport-status" class="type-btn mb-2 actionBtn">
                                @lang('app.addNew')
                            </x-forms.button-primary>
                        </div>

                    </div>
                </x-slot>
            @endif

            <div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-0">
                <div class="table-responsive">
                    <x-table class="table-bordered table-hover">
                        <x-slot name="thead">
                            <th>#</th>
                            <th>@lang('app.name')</th>
                            <th class="text-right pr-20">@lang('app.action')</th>
                        </x-slot>

                        @forelse($passportStatuses as $key => $passportStatus)
                            <tr id="passport-status-{{ $passportStatus->id }}">
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $passportStatus->name }}</td>
                                <td class="text-right pr-20">
                                    <div class="task_view">
                                        <a class="task_view_more d-flex align-items-center justify-content-center edit-passport-status"
                                           href="javascript:;" data-passport-status-id="{{ $passportStatus->id }}">
                                            <i class="fa fa-edit icons mr-2"></i> @lang('app.edit')
                                        </a>
                                        <a class="task_view_more d-flex align-items-center justify-content-center delete-passport-status"
                                           href="javascript:;" data-passport-status-id="{{ $passportStatus->id }}">
                                            <i class="fa fa-trash icons mr-2"></i> @lang('app.delete')
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <x-cards.no-record-found-list colspan="3"/>

                        @endforelse
                    </x-table>
                </div>
            </div>

        </x-setting-card>

    </div>
    <!-- SETTINGS END -->
@endsection

@push('scripts')

    <script>
        // create new passport status
        $('#add-passport-status').click(function () {
            const url = "{{ route('new-passport-status-master.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        // edit passport status
        $('body').on('click', '.edit-passport-status', function () {
            const id = $(this).data('passport-status-id');
            const url = "{{ route('new-passport-status-master.edit', ':id') }}";
            const editUrl = url.replace(':id', id);
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, editUrl);
        });

        $('body').on('click', '.delete-passport-status', function () {
            const id = $(this).data('passport-status-id');
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
                    let url = "{{ route('new-passport-status-master.destroy', ':id') }}";
                    url = url.replace(':id', id);

                    const token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        blockUI: true,
                        data: {
                            '_token': token,
                            '_method': 'DELETE'
                        },
                        success: function (response) {
                            if (response.status === "success") {
                                $('#passport-status-' + id).fadeOut(100);
                            }
                        }
                    });
                }
            });
        });
    </script>

@endpush
