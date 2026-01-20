<div class="table-responsive p-20">
    <x-table class="table-bordered">
        <x-slot name="thead">
            <th>@lang('app.menu.country')</th>
            <th>@lang('app.menu.state')</th>
            <th>@lang('app.menu.city')</th>
            <th class="text-right">@lang('app.action')</th>
        </x-slot>

        @forelse($cities as $key => $city)
            <tr class="row{{ $city->id }}">
                <td>{{ $city->state->country->name ?? '-' }}</td>
                <td>{{ $city->state->name ?? '-' }}</td>
                <td>{{ $city->name }}</td>
                <td class="text-right">
                    <div class="task_view">
                        <a href="javascript:;" data-city-id="{{ $city->id }}" class="edit-city task_view_more d-flex align-items-center justify-content-center" > <i class="fa fa-edit icons mr-2"></i>  @lang('app.edit')
                        </a>
                    </div>
                    <div class="task_view mt-1 mt-lg-0 mt-md-0">
                        <a href="javascript:;" class="delete-table-row delete-city task_view_more d-flex align-items-center justify-content-center" data-city-id="{{ $city->id }}">
                            <i class="fa fa-trash icons mr-2"></i> @lang('app.delete')
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">
                    <x-cards.no-record icon="list" :message="__('messages.noRecordFound')" />
                </td>
            </tr>
        @endforelse
    </x-table>
</div>
