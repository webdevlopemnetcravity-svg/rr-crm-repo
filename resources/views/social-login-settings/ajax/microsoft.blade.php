<div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-20">
    @method('PUT')

    <input type="hidden" name="tab" value="microsoft">
    <div class="row">
        <div class="col-lg-12 mb-4">
            <x-forms.checkbox :fieldLabel="__('app.status')" fieldName="microsoft_status" fieldId="microsoftButton"
                fieldValue="enable" fieldRequired="true" :checked="isset($credentials->microsoft_status) && $credentials->microsoft_status == 'enable'" disabled="true" />
        </div>

        <div class="col-lg-12 microsoftSection mb-3  @if (!isset($credentials->microsoft_status) || $credentials->microsoft_status !== 'enable') d-none @endif">
            <div class="row">
                <div class="col-lg-6">
                    <x-forms.text :fieldLabel="__('app.socialAuthSettings.microsoftClientId')"
                        fieldName="microsoft_client_id" fieldRequired="true" fieldId="microsoft_client_id"
                        :fieldValue="isset($credentials->microsoft_client_id) ? $credentials->microsoft_client_id : ''" disabled="disabled"></x-forms.text>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <x-forms.label class="mt-3" fieldId="password" fieldRequired="true"
                            :fieldLabel="__('app.socialAuthSettings.microsoftSecret')">
                        </x-forms.label>
                        <x-forms.input-group>
                            <input type="password" name="microsoft_secret_id" id="microsoft_secret_id"
                                class="form-control height-35 f-14"
                                value="{{ isset($credentials->microsoft_secret_id) ? $credentials->microsoft_secret_id : '' }}" disabled>

                            <x-slot name="preappend">
                                <button type="button" data-toggle="tooltip"
                                    data-original-title="{{ __('messages.viewKey') }}"
                                    class="btn btn-outline-secondary border-grey height-35 toggle-password" disabled><i
                                        class="fa fa-eye"></i></button>
                            </x-slot>
                        </x-forms.input-group>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group my-3">
                        <label for="mail_from_name">@lang('app.callback')</label>
                        <p class="text-bold"><span
                                id="microsoft_webhook_link">{{ env('MS_REDIRECT_URI', url('/auth/microsoft/callback')) }}</span>
                            <a href="javascript:;" class="btn-copy btn-secondary f-12 rounded p-1 py-2 ml-1"
                                data-clipboard-target="#microsoft_webhook_link">
                                <i class="fa fa-copy mx-1"></i>@lang('app.copy')</a>
                        </p>
                        <p class="text-primary">(@lang('messages.addMicrosoftCallback'))</p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="alert alert-warning">
                        <div class="form-group mb-0">
                            @if(Route::has('microsoftAuth'))
                                <a href="{{ route('microsoftAuth') }}" class="btn btn-primary">
                                    <i class="fab fa-microsoft mr-2"></i>Get Microsoft Token
                                </a>
                            @else
                                <button type="button" class="btn btn-primary" disabled>
                                    <i class="fab fa-microsoft mr-2"></i>Get Microsoft Token
                                </button>
                            @endif
                            <p class="text-muted mt-2 mb-0">Click to authenticate with Microsoft and store your calendar tokens for appointment booking.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Buttons Start -->
        <div class="w-100 border-top-grey">
            <x-setting-form-actions>
                <x-forms.button-primary id="save_microsoft_data" class="mr-3" icon="check" disabled>@lang('app.save')
                </x-forms.button-primary>
            </x-setting-form-actions>
        </div>
        <!-- Buttons End -->
    </div>
</div>

<script>
    $(document).ready(function() {
        // Disable the microsoft_client_id field
        $('#microsoft_client_id').prop('disabled', true);
        
        // Disable checkbox toggle functionality
        $('#microsoftButton').prop('disabled', true);
        
        // Disable save button
        $('#save_microsoft_data').prop('disabled', true);
    });
    
    $('#microsoftButton').on('change', function() {
        $('.microsoftSection').toggleClass('d-none');
    });
</script>
