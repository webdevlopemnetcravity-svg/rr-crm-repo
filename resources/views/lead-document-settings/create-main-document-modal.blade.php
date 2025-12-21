<div class="modal-header">
    <h5 class="modal-title">@lang('app.addNew') @lang('app.menu.mainDocuments')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>

<div class="modal-body">
    <div class="portlet-body">
        <x-form id="createMainDocument" method="POST" class="ajax-form">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12 ">
                        <x-forms.text fieldId="name" :fieldLabel="__('app.name')" fieldName="name"
                            fieldRequired="true" :fieldPlaceholder="__('app.name')">
                        </x-forms.text>
                    </div>
                </div>
            </div>
        </x-form>
    </div>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="save-main-document" icon="check">@lang('app.save')</x-forms.button-primary>
</div>

<script>
    // save main document
    $('#save-main-document').click(function() {
        $.easyAjax({
            url: "{{ route('mainDocuments.store') }}",
            container: '#createMainDocument',
            type: "POST",
            blockUI: true,
            data: $('#createMainDocument').serialize(),
            success: function(response) {
                if (response.status == "success") {
                    $(MODAL_LG).modal('hide');
                    
                    // Reload only the main-documents tab content
                    var url = "{{ route('lead-document-settings.index') }}";
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

