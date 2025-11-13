@extends('layouts.app')

@section('content')
    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        <!-- Add Task Export Buttons Start -->
        <div class="d-grid d-lg-flex d-md-flex action-bar">
            <div id="table-actions" class="flex-grow-1 align-items-center">
                <h4 class="mb-0 f-21 font-weight-normal text-capitalize">
                    @lang('app.leadDetails')
                </h4>
            </div>
        </div>
        <!-- Add Task Export Buttons End -->

        <div class="d-flex flex-column w-100 rounded mt-3 bg-white">
            {{-- <div class="p-20 border-bottom-grey">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <h5 class="mb-0 f-18 font-weight-normal">
                            Lead Details Overview
                        </h5>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="p-20">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            <strong>@lang('app.leadDetails')</strong> - This page displays detailed information about leads.
                        </div>
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="mb-3">Lead Details Overview</h5>
                                <p class="text-muted mb-4">
                                    This page will show comprehensive details about leads including contact information, 
                                    status, notes, deals, and other related data. The detailed view will be implemented here.
                                </p>
                                
                                <div class="mt-3">
                                    <x-forms.link-primary :link="route('lead-contact.index')" icon="list">
                                        @lang('app.view') @lang('app.leadContact')
                                    </x-forms.link-primary>
                                    
                                    <x-forms.link-secondary :link="route('lead-list.index')" class="ml-2" icon="list">
                                        @lang('app.view') @lang('app.leadList')
                                    </x-forms.link-secondary>
                                    
                                    <x-forms.link-secondary :link="route('add-lead.index')" class="ml-2" icon="plus">
                                        @lang('app.addLead')
                                    </x-forms.link-secondary>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
    <!-- CONTENT WRAPPER END -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Lead Details page scripts will be added here
        });
    </script>
@endpush

