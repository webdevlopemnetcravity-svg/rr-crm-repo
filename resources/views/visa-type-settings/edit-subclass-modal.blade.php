<div class="modal-header">
    <h5 class="modal-title">@lang('app.edit') @lang('app.menu.subclass')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>

<div class="modal-body">
    <div class="portlet-body">
        <x-form id="editSubclass" method="PUT" class="ajax-form">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <x-forms.label class="mt-3" fieldId="visa_type_id" :fieldLabel="__('app.menu.visaType')" fieldRequired="true">
                        </x-forms.label>
                        <select class="form-control select-picker height-35 f-14" name="visa_type_id" id="visa_type_id" data-live-search="true">
                            <option value="">@lang('app.select') @lang('app.menu.visaType')</option>
                            @foreach($visaTypes as $visaType)
                                <option value="{{ $visaType->id }}" {{ $visaType->id == $subclass->visa_type_id ? 'selected' : '' }}>{{ $visaType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12 ">
                        <x-forms.text fieldId="name" :fieldLabel="__('app.menu.subclass')" fieldName="name"
                            fieldRequired="true" :fieldPlaceholder="__('app.menu.subclass')" :fieldValue="$subclass->name">
                        </x-forms.text>
                    </div>
                </div>
            </div>
        </x-form>
    </div>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="update-subclass" icon="check">@lang('app.update')</x-forms.button-primary>
</div>

<script>
    // Initialize selectpicker when modal content loads
    $('#visa_type_id').selectpicker();
    
    // Also refresh when modal is shown (in case it needs re-initialization)
    $(MODAL_LG).on('shown.bs.modal', function() {
        $('#visa_type_id').selectpicker('refresh');
    });

    // update subclass
    $('#update-subclass').click(function() {
        var url = "{{ route('subclasses.update', $subclass->id) }}";
        $.easyAjax({
            url: url,
            container: '#editSubclass',
            type: "PUT",
            blockUI: true,
            data: $('#editSubclass').serialize(),
            success: function(response) {
                if (response.status == "success") {
                    $(MODAL_LG).modal('hide');
                    
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
        })
    });

</script>

