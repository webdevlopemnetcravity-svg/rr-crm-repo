<div class="table-responsive p-20">
    <x-table class="table-bordered">
        <x-slot name="thead">
            <th>@lang('app.menu.visaType')</th>
            <th>@lang('app.menu.subclass')</th>
            <th class="text-right">@lang('app.action')</th>
        </x-slot>

        @forelse($subclasses as $key => $subclass)
            <tr class="row{{ $subclass->id }}">
                <td>{{ $subclass->visaType->name ?? '-' }}</td>
                <td>{{ $subclass->name }}</td>
                <td class="text-right">
                    <div class="task_view">
                        <a href="javascript:;" data-subclass-id="{{ $subclass->id }}" class="edit-subclass task_view_more d-flex align-items-center justify-content-center" > <i class="fa fa-edit icons mr-2"></i>  @lang('app.edit')
                        </a>
                    </div>
                    <div class="task_view mt-1 mt-lg-0 mt-md-0">
                        <a href="javascript:;" class="delete-table-row delete-subclass task_view_more d-flex align-items-center justify-content-center" data-subclass-id="{{ $subclass->id }}">
                            <i class="fa fa-trash icons mr-2"></i> @lang('app.delete')
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3">
                    <x-cards.no-record icon="list" :message="__('messages.noRecordFound')" />
                </td>
            </tr>
        @endforelse
    </x-table>
</div>

