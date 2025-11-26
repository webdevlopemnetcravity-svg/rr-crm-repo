<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">@lang('app.addNew') Document</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <x-form id="save-document-data-form">
        <div class="row">
            <div class="col-md-12">
                <x-forms.text :fieldLabel="__('modules.projects.fileName')" fieldName="name"
                    fieldRequired="true" fieldId="document_name" :fieldPlaceholder="__('app.name')" />
            </div>
            <div class="col-md-12">
                <x-forms.file :fieldLabel="__('modules.projects.uploadFile')" fieldName="document"
                    fieldRequired="true" fieldId="document_file"
                    allowedFileExtensions="txt pdf doc xls xlsx docx rtf png jpg jpeg svg"
                    :popover="__('messages.fileFormat.multipleImageFile')" />
            </div>
        </div>
    </x-form>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="submit-document" icon="check">@lang('app.submit')</x-forms.button-primary>
</div>

<script>
    $('#submit-document').click(function() {
        var url = "{{ route('new-lead-template-documents.store') }}";

        $.easyAjax({
            url: url,
            container: '#save-document-data-form',
            type: "POST",
            disableButton: true,
            buttonSelector: "#submit-document",
            file: true,
            data: $('#save-document-data-form').serialize(),
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

