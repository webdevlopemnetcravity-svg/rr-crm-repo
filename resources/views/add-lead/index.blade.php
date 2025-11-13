@extends('layouts.app')

@section('content')
    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        <!-- Add Task Export Buttons Start -->
        <div class="d-grid d-lg-flex d-md-flex action-bar">
            <div id="table-actions" class="flex-grow-1 align-items-center">
                <h4 class="mb-0 f-21 font-weight-normal text-capitalize">
                    @lang('app.addLead')
                </h4>
            </div>
        </div>
        <!-- Add Task Export Buttons End -->

        <div class="d-flex flex-column w-100 rounded mt-3 bg-white">
            {{-- <div class="p-20 border-bottom-grey">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <h5 class="mb-0 f-18 font-weight-normal">
                            Add New Lead to the System
                        </h5>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="p-20">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            <strong>@lang('app.addLead')</strong> - This page is for adding new leads to the system.
                        </div>
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="mb-3">Add New Lead</h5>
                                <p class="text-muted mb-4">
                                    Use the button below to open the lead creation form, or implement a custom form on this page.
                                </p>
                                
                                <div class="mt-3">
                                    <x-forms.link-primary :link="route('lead-contact.create')" class="openRightModal" icon="plus">
                                        @lang('modules.leadContact.addLeadContact')
                                    </x-forms.link-primary>
                                    
                                    <x-forms.link-secondary :link="route('lead-list.index')" class="ml-2" icon="list">
                                        @lang('app.view') @lang('app.leadList')
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
            // Add Lead page scripts will be added here
        });
    </script>
@endpush

