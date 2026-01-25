<div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-20">
    @method('PUT')

    <input type="hidden" name="tab" value="zoom">
    <div class="row">
        <div class="col-lg-12 mb-4">
            <x-forms.checkbox :fieldLabel="__('app.status')" fieldName="zoom_status" fieldId="zoomButton"
                fieldValue="enable" fieldRequired="true" :checked="isset($credentials->zoom_status) && $credentials->zoom_status == 'enable'" />
        </div>

        <div class="col-lg-12 zoomSection mb-3  @if (!isset($credentials->zoom_status) || $credentials->zoom_status !== 'enable') d-none @endif">
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-info">
                        <p class="mb-0"><strong>Server-to-Server OAuth</strong>: To integrate Zoom, create a <strong>Server-to-Server OAuth</strong> app in the <a href="https://marketplace.zoom.us/develop/create" target="_blank" class="text-white underline">Zoom App Marketplace</a>. Enable the required scopes:
                            <br>• <code>meeting:write:meeting:admin</code>
                            <br>• <code>meeting:read:meeting:admin</code>
                            <br>• <code>meeting:write:meeting</code>
                            <br>• <code>meeting:read:meeting</code>
                        </p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <x-forms.text :fieldLabel="__('app.socialAuthSettings.zoomAccountId')"
                        fieldName="zoom_account_id" fieldRequired="true" fieldId="zoom_account_id"
                        :fieldValue="isset($credentials->zoom_account_id) ? $credentials->zoom_account_id : ''"></x-forms.text>
                </div>
                <div class="col-lg-4">
                    <x-forms.text :fieldLabel="__('app.socialAuthSettings.zoomClientId')"
                        fieldName="zoom_client_id" fieldRequired="true" fieldId="zoom_client_id"
                        :fieldValue="isset($credentials->zoom_client_id) ? $credentials->zoom_client_id : ''"></x-forms.text>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <x-forms.label class="mt-3" fieldId="zoom_client_secret" fieldRequired="true"
                            :fieldLabel="__('app.socialAuthSettings.zoomSecret')">
                        </x-forms.label>
                        <x-forms.input-group>
                            <input type="password" name="zoom_client_secret" id="zoom_client_secret"
                                class="form-control height-35 f-14"
                                value="{{ isset($credentials->zoom_client_secret) ? $credentials->zoom_client_secret : '' }}">

                            <x-slot name="preappend">
                                <button type="button" data-toggle="tooltip"
                                    data-original-title="{{ __('messages.viewKey') }}"
                                    class="btn btn-outline-secondary border-grey height-35 toggle-password"><i
                                        class="fa fa-eye"></i></button>
                            </x-slot>
                        </x-forms.input-group>
                    </div>
                </div>
            </div>
        </div>
        <!-- Buttons Start -->
        <div class="w-100 border-top-grey">
            <x-setting-form-actions>
                <x-forms.button-primary id="save_zoom_data" class="mr-3" icon="check">@lang('app.save')
                </x-forms.button-primary>
            </x-setting-form-actions>
        </div>
        <!-- Buttons End -->
    </div>
</div>

<script>
    $('#zoomButton').on('change', function() {
        $('.zoomSection').toggleClass('d-none');
    });
</script>
