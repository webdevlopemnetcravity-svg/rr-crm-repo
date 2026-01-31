<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">@lang('app.edit') Passport Type</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <x-form id="save-passport-type-data-form" method="PUT">
        <div class="row">
            <div class="col-md-12">
                <x-forms.text :fieldLabel="__('app.name')" fieldName="name"
                    fieldRequired="true" fieldId="passport_type_name" :fieldPlaceholder="__('app.name')" :fieldValue="$passportType->name" />
            </div>
        </div>
    </x-form>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="update-passport-type" icon="check">@lang('app.update')</x-forms.button-primary>
</div>

<script>
    $('#update-passport-type').click(function() {
        var url = "{{ route('new-passport-types-master.update', $passportType->id) }}";

        $.easyAjax({
            url: url,
            container: '#save-passport-type-data-form',
            type: "PUT",
            disableButton: true,
            buttonSelector: "#update-passport-type",
            data: $('#save-passport-type-data-form').serialize(),
            success: function(response) {
                if (response.status == 'success') {
                    $(MODAL_LG).modal('hide');
                    window.location.reload();
                }
            }
        })
    });
    init(MODAL_LG);
</script>
