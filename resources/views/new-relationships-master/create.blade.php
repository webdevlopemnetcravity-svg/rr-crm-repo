<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">@lang('app.addNew') Relationship</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <x-form id="save-relationship-data-form">
        <div class="row">
            <div class="col-md-12">
                <x-forms.text :fieldLabel="__('app.name')" fieldName="name"
                    fieldRequired="true" fieldId="relationship_name" :fieldPlaceholder="__('app.name')" />
            </div>
        </div>
    </x-form>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="submit-relationship" icon="check">@lang('app.submit')</x-forms.button-primary>
</div>

<script>
    $('#submit-relationship').click(function() {
        var url = "{{ route('new-relationships-master.store') }}";

        $.easyAjax({
            url: url,
            container: '#save-relationship-data-form',
            type: "POST",
            disableButton: true,
            buttonSelector: "#submit-relationship",
            data: $('#save-relationship-data-form').serialize(),
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
