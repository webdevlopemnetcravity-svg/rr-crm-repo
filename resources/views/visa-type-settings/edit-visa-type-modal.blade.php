<div class="modal-header">
    <h5 class="modal-title">@lang('app.edit') @lang('app.menu.visaType')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>

<div class="modal-body">
    <div class="portlet-body">
        <x-form id="editVisaType" method="PUT" class="ajax-form">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12 ">
                        <x-forms.text fieldId="name" :fieldLabel="__('app.name')" fieldName="name"
                            fieldRequired="true" :fieldPlaceholder="__('app.name')" :fieldValue="$visaType->name">
                        </x-forms.text>
                    </div>
                </div>
            </div>
        </x-form>
    </div>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="update-visa-type" icon="check">@lang('app.update')</x-forms.button-primary>
</div>

<script>
    // update visa type
    $('#update-visa-type').click(function() {
        var url = "{{ route('visaTypes.update', $visaType->id) }}";
        $.easyAjax({
            url: url,
            container: '#editVisaType',
            type: "PUT",
            blockUI: true,
            data: $('#editVisaType').serialize(),
            success: function(response) {
                if (response.status == "success") {
                    $(MODAL_LG).modal('hide');
                    
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
        })
    });

</script>

