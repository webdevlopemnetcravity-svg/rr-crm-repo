<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">@lang('app.edit') Designation</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <x-form id="save-designation-data-form" method="PUT">
        <div class="row">
            <div class="col-md-12">
                <x-forms.text :fieldLabel="__('app.name')" fieldName="name"
                    fieldRequired="true" fieldId="designation_name" :fieldPlaceholder="__('app.name')" :fieldValue="$designation->name" />
            </div>
        </div>
    </x-form>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="update-designation" icon="check">@lang('app.update')</x-forms.button-primary>
</div>

<script>
    $('#update-designation').click(function() {
        var url = "{{ route('new-designation-master.update', $designation->id) }}";

        $.easyAjax({
            url: url,
            container: '#save-designation-data-form',
            type: "PUT",
            disableButton: true,
            buttonSelector: "#update-designation",
            data: $('#save-designation-data-form').serialize(),
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
