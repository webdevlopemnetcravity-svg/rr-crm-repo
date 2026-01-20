<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">@lang('app.edit') Language</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <x-form id="save-language-data-form" method="PUT">
        <div class="row">
            <div class="col-md-12">
                <x-forms.text :fieldLabel="__('app.name')" fieldName="name"
                    fieldRequired="true" fieldId="language_name" :fieldPlaceholder="__('app.name')" :fieldValue="$language->name" />
            </div>
        </div>
    </x-form>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="update-language" icon="check">@lang('app.update')</x-forms.button-primary>
</div>

<script>
    $('#update-language').click(function() {
        var url = "{{ route('new-language-master.update', $language->id) }}";

        $.easyAjax({
            url: url,
            container: '#save-language-data-form',
            type: "PUT",
            disableButton: true,
            buttonSelector: "#update-language",
            data: $('#save-language-data-form').serialize(),
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
