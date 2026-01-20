<div class="modal-header">
    <h5 class="modal-title">@lang('app.edit') @lang('app.menu.city')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>

<div class="modal-body">
    <div class="portlet-body">
        <x-form id="editCity" method="PUT" class="ajax-form">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <x-forms.label class="mt-3" fieldId="state_id" :fieldLabel="__('app.menu.state')" fieldRequired="true">
                        </x-forms.label>
                        <select class="form-control select-picker height-35 f-14" name="state_id" id="state_id" data-live-search="true">
                            <option value="">@lang('app.select') @lang('app.menu.state')</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" {{ $city->state_id == $state->id ? 'selected' : '' }}>{{ $state->name }} ({{ $state->country->name ?? '' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12 ">
                        <x-forms.text fieldId="name" :fieldLabel="__('app.menu.city')" fieldName="name"
                            fieldRequired="true" :fieldPlaceholder="__('app.menu.city')" :fieldValue="$city->name">
                        </x-forms.text>
                    </div>
                </div>
            </div>
        </x-form>
    </div>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="update-city" icon="check">@lang('app.update')</x-forms.button-primary>
</div>

<script>
    // Initialize selectpicker when modal content loads
    $('#state_id').selectpicker();
    
    // Also refresh when modal is shown (in case it needs re-initialization)
    $(MODAL_LG).on('shown.bs.modal', function() {
        $('#state_id').selectpicker('refresh');
    });

    // update city
    $('#update-city').click(function() {
        var url = "{{ route('cities.update', $city->id) }}";
        $.easyAjax({
            url: url,
            container: '#editCity',
            type: "PUT",
            blockUI: true,
            data: $('#editCity').serialize(),
            success: function(response) {
                if (response.status == "success") {
                    $(MODAL_LG).modal('hide');
                    
                    // Reload only the city tab content
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
        })
    });

</script>
