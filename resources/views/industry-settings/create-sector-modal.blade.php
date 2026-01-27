<div class="modal-header">
    <h5 class="modal-title">@lang('app.addNew') @lang('app.menu.sector')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>

<div class="modal-body">
    <div class="portlet-body">
        <x-form id="createSector" method="POST" class="ajax-form">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <x-forms.label class="mt-3" fieldId="industry_id" :fieldLabel="__('app.menu.industry')" fieldRequired="true">
                        </x-forms.label>
                        <select class="form-control select-picker height-35 f-14" name="industry_id" id="industry_id" data-live-search="true">
                            <option value="">@lang('app.select') @lang('app.menu.industry')</option>
                            @foreach($industries as $industry)
                                <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12 ">
                        <x-forms.text fieldId="name" :fieldLabel="__('app.menu.sector')" fieldName="name"
                            fieldRequired="true" :fieldPlaceholder="__('app.menu.sector')">
                        </x-forms.text>
                    </div>
                </div>
            </div>
        </x-form>
    </div>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="save-sector" icon="check">@lang('app.save')</x-forms.button-primary>
</div>

<script>
    $('#industry_id').selectpicker();

    $(MODAL_LG).on('shown.bs.modal', function() {
        $('#industry_id').selectpicker('refresh');
    });

    $('#save-sector').click(function() {
        $.easyAjax({
            url: "{{ route('sectors.store') }}",
            container: '#createSector',
            type: "POST",
            blockUI: true,
            data: $('#createSector').serialize(),
            success: function(response) {
                if (response.status == "success") {
                    $(MODAL_LG).modal('hide');

                    var url = "{{ route('industry-settings.index') }}?tab=sector";
                    $.easyAjax({
                        url: url,
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
