<div class="modal-header">
    <h5 class="modal-title">@lang('app.edit') @lang('app.menu.sector')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>

<div class="modal-body">
    <div class="portlet-body">
        <x-form id="editSector" method="PUT" class="ajax-form">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <x-forms.label class="mt-3" fieldId="industry_id" :fieldLabel="__('app.menu.industry')" fieldRequired="true">
                        </x-forms.label>
                        <select class="form-control select-picker height-35 f-14" name="industry_id" id="industry_id" data-live-search="true">
                            <option value="">@lang('app.select') @lang('app.menu.industry')</option>
                            @foreach($industries as $industry)
                                <option value="{{ $industry->id }}" {{ $industry->id == $sector->industry_id ? 'selected' : '' }}>{{ $industry->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12 ">
                        <x-forms.text fieldId="name" :fieldLabel="__('app.menu.sector')" fieldName="name"
                            fieldRequired="true" :fieldPlaceholder="__('app.menu.sector')" :fieldValue="$sector->name">
                        </x-forms.text>
                    </div>
                </div>
            </div>
        </x-form>
    </div>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="update-sector" icon="check">@lang('app.update')</x-forms.button-primary>
</div>

<script>
    $('#industry_id').selectpicker();

    $(MODAL_LG).on('shown.bs.modal', function() {
        $('#industry_id').selectpicker('refresh');
    });

    $('#update-sector').click(function() {
        var url = "{{ route('sectors.update', $sector->id) }}";
        $.easyAjax({
            url: url,
            container: '#editSector',
            type: "PUT",
            blockUI: true,
            data: $('#editSector').serialize(),
            success: function(response) {
                if (response.status == "success") {
                    $(MODAL_LG).modal('hide');

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
        })
    });
</script>
