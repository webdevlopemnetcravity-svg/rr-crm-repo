@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatable_css')
    <link rel="stylesheet" href="{{ asset('css/lead-list.css') }}">
    <style>
        .follow-up-tooltip-trigger {
            pointer-events: auto;
        }
        .follow-up-tooltip-trigger .feedback-section,
        .follow-up-tooltip-trigger .reminder-section {
            pointer-events: none;
        }
        .follow-up-tooltip-trigger:hover {
            z-index: 1050;
        }
        .add-follow-up-btn,
        .edit-follow-up-btn {
            position: relative;
            pointer-events: auto !important;
        }
        /* Ensure table stays BELOW filter - set lower z-index for table container */
        
        /* Ensure follow-up text doesn't overflow */
        .follow-up-column .last-follow-up-info {
            max-width: 100%;
            overflow: hidden;
            width: 100%;
        }
        .follow-up-column .feedback-section,
        .follow-up-column .reminder-section {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            line-height: 1.4;
            max-height: 2.8em;
        }
        /* Ensure filter inner elements also have high z-index */
    </style>
@endpush

@section('filter-section')
    @php
        $addLeadPermission = user()->permission('add_lead');
    @endphp

    <x-filters.filter-box>
        <!-- DATE START -->
        <div class="select-box d-flex pr-2 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.duration')</p>
            <div class="select-status d-flex">
                <input type="text" class="position-relative text-dark form-control border-0 p-2 text-left f-14 f-w-500 border-additional-grey"
                    id="datatableRange" placeholder="@lang('placeholders.dateRange')">
            </div>
        </div>
        <!-- DATE END -->


        <!-- SEARCH BY TASK START -->
        <div class="task-search d-flex  py-1 px-lg-3 px-0 border-right-grey align-items-center">
            <form class="w-100 mr-1 mr-lg-0 mr-md-1 ml-md-1 ml-0 ml-lg-0">
                <div class="input-group bg-grey rounded">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-0 bg-additional-grey">
                            <i class="fa fa-search f-13 text-dark-grey"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control f-14 p-1 border-additional-grey" id="search-text-field"
                           placeholder="Search by Name / Lead Number / Subclass">
                </div>
            </form>
        </div>
        <!-- SEARCH BY TASK END -->

        <!-- RESET START -->
        <div class="select-box d-flex py-1 px-lg-2 px-md-2 px-0">
            <x-forms.button-secondary class="btn-xs d-none" id="reset-filters" icon="times-circle">
                @lang('app.clearFilters')
            </x-forms.button-secondary>
        </div>
        <!-- RESET END -->

        <!-- MORE FILTERS START -->
        <x-filters.more-filter-box>
            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 " for="usr">@lang('app.dateFilterOn')</label>
                <div class="select-filter mb-4">
                    <select class="form-control select-picker" name="date_filter_on" id="date_filter_on">
                        <option value="created_at">@lang('app.createdOn')</option>
                        <option value="updated_at">@lang('app.updatedOn')</option>
                    </select>
                </div>
            </div>

            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 " for="usr">@lang('modules.lead.leadStatus')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" id="filter_lead_status" data-live-search="true" data-container="body" data-size="8">
                            <option value="all">@lang('app.all')</option>
                            @if(isset($leadStatuses))
                                @foreach ($leadStatuses as $status)
                                    <option value="{{ $status->type }}">{{ $status->type }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 " for="usr">@lang('modules.lead.leadSource')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" id="filter_source_id" data-live-search="true" data-container="body" data-size="8">
                            <option value="all">@lang('app.all')</option>
                            @foreach ($sources as $source)
                                <option value="{{ $source->id }}">{{ $source->type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 " for="usr">@lang('app.services')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" id="filter_subclass" data-live-search="true" data-container="body" data-size="8">
                            <option value="all">@lang('app.all')</option>
                            @if(isset($subclasses))
                                @foreach ($subclasses as $subclass)
                                    <option value="{{ $subclass }}">{{ $subclass }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 " for="usr">Priority</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" id="filter_priority" data-container="body" data-size="8">
                            <option value="all">@lang('app.all')</option>
                            <option value="Select Priority">Select Priority</option>
                            <option value="1st Priority">1st Priority</option>
                            <option value="2nd Priority">2nd Priority</option>
                            <option value="3rd Priority">3rd Priority</option>
                            <option value="4th Priority">4th Priority</option>
                            <option value="5th Priority">5th Priority</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 " for="usr">@lang('app.addedBy')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" id="filter_addedBy" data-live-search="true" data-container="body" data-size="8">
                            <option value="all">@lang('app.all')</option>
                            @foreach ($employees as $item)
                                <x-user-option :user="$item" />
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </x-filters.more-filter-box>
        <!-- MORE FILTERS END -->
    </x-filters.filter-box>

@endsection

@section('content')
    @php
        $addLeadPermission = user()->permission('add_lead');
    @endphp
    
    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        <!-- Add Lead Button Start -->
        <div class="d-flex justify-content-between action-bar mb-3">
            <div id="table-actions" class="d-block d-lg-flex align-items-center">
                @if ($addLeadPermission == 'all' || $addLeadPermission == 'added')
                    <x-forms.link-primary :link="route('add-lead.index')" class="mr-3 mb-2 mb-lg-0" icon="plus">
                        @lang('app.addLead')
                    </x-forms.link-primary>
                @endif
            </div>

            <!-- Quick Actions Start -->
            <x-datatable.actions>
                <div class="select-status mr-3 pl-3">
                    <select name="action_type" class="form-control select-picker" id="quick-action-type" disabled>
                        <option value="">@lang('app.selectAction')</option>
                        <option value="delete">@lang('app.delete')</option>
                    </select>
                </div>
            </x-datatable.actions>
            <!-- Quick Actions End -->
        </div>
        <!-- Add Lead Button End -->
        <!-- Task Box Start -->
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white table-responsive">

            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}

        </div>
        <!-- Task Box End -->
    </div>
    <!-- CONTENT WRAPPER END -->

    <!-- Add Follow-Up Modal -->
    <div class="modal fade" id="addFollowUpModalList" tabindex="-1" role="dialog" aria-labelledby="addFollowUpModalListLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFollowUpModalListLabel">Add Follow-Up</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <x-form id="followUpFormList" method="POST" class="ajax-form">
                    <div class="modal-body">
                        <input type="hidden" name="new_lead_id" id="follow_up_lead_id">
                        <input type="hidden" name="id" id="follow_up_id">
                        <div class="form-group">
                            <label class="f-14 font-weight-bold mb-2">Follow-Up Type</label>
                            <div class="d-flex gap-2">
                                <label class="form-check-label mr-3">
                                    <input type="radio" name="follow_up_type" value="call" checked class="mr-1"> Call
                                </label>
                                <label class="form-check-label mr-3">
                                    <input type="radio" name="follow_up_type" value="meeting" class="mr-1"> Meeting
                                </label>
                                <label class="form-check-label mr-3">
                                    <input type="radio" name="follow_up_type" value="sms" class="mr-1"> SMS
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" name="follow_up_type" value="email" class="mr-1"> Email
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <x-forms.label fieldId="subject" fieldLabel="Subject">
                            </x-forms.label>
                            <input type="text" name="subject" id="subject" class="form-control height-35 f-14" value="">
                        </div>
                        <div class="form-group">
                            <x-forms.label fieldId="outcome" fieldLabel="Outcome of Call">
                            </x-forms.label>
                            <input type="text" name="outcome" id="outcome" class="form-control height-35 f-14" value="">
                        </div>
                        <div class="form-group">
                            <x-forms.label fieldId="notes" fieldLabel="Notes">
                            </x-forms.label>
                            <textarea name="notes" id="notes" class="form-control f-14" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="f-14 font-weight-bold mb-2">Do you want to get update for next follow-up - Set reminder?</label>
                            <x-forms.checkbox :fieldLabel="__('modules.tasks.reminder')" fieldName="send_reminder"
                                fieldId="send_reminder" fieldValue="yes" />
                        </div>
                        <div class="form-group next_follow_up_datetime_div d-none">
                            <div class="row">
                                <div class="col-md-6">
                                    <x-forms.datepicker fieldId="next_follow_up_date"
                                        fieldLabel="Next Follow Up Date" fieldName="next_follow_up_date"
                                        :fieldValue="''"
                                        :fieldPlaceholder="__('placeholders.date')" />
                                </div>
                                <div class="col-md-6">
                                    <div class="bootstrap-timepicker timepicker">
                                        <x-forms.text fieldLabel="Time" :fieldPlaceholder="__('placeholders.hours')"
                                            fieldName="next_follow_up_time" fieldId="next_follow_up_time"
                                            :fieldValue="''" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group send_reminder_div d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <x-forms.label fieldId="remind_time" fieldLabel="Remind Time">
                                    </x-forms.label>
                                    <select name="remind_time" id="remind_time" class="form-control select-picker height-35 f-14">
                                        <option value="15 Minutes Before" selected>15 Minutes Before</option>
                                        <option value="30 Minutes Before">30 Minutes Before</option>
                                        <option value="1 Hour Before">1 Hour Before</option>
                                        <option value="2 Hours Before">2 Hours Before</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group follow_up_subject_line_div d-none">
                            <x-forms.label fieldId="follow_up_subject_line" fieldLabel="Follow Up Subject Line" fieldRequired="true">
                            </x-forms.label>
                            <input type="text" name="follow_up_subject_line" id="follow_up_subject_line" class="form-control height-35 f-14" value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <x-forms.button-primary id="save-followup-list" icon="check">Save</x-forms.button-primary>
                    </div>
                </x-form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    @include('sections.datatable_js')

    <script>
        $('#new-leads-table').on('preXhr.dt', function(e, settings, data) {

            var dateRangePicker = $('#datatableRange').data('daterangepicker');
            var startDate = $('#datatableRange').val();

            if (startDate == '') {
                startDate = null;
                endDate = null;
            } else {
                startDate = dateRangePicker.startDate.format('{{ company()->moment_date_format }}');
                endDate = dateRangePicker.endDate.format('{{ company()->moment_date_format }}');
            }

            var searchText = $('#search-text-field').val();
            var source_id = $('#filter_source_id').val();
            var date_filter_on = $('#date_filter_on').val();
            var filter_added_by = $('#filter_addedBy').val();
            var filter_lead_status = $('#filter_lead_status').val();
            var filter_subclass = $('#filter_subclass').val();
            var filter_priority = $('#filter_priority').val();

            data['startDate'] = startDate;
            data['filter_addedBy'] = filter_added_by;
            data['endDate'] = endDate;
            data['searchText'] = searchText;
            data['source_id'] = source_id;
            data['date_filter_on'] = date_filter_on;
            data['filter_lead_status'] = filter_lead_status;
            data['filter_subclass'] = filter_subclass;
            data['filter_priority'] = filter_priority;
        });

        const showTable = () => {
            window.LaravelDataTables["new-leads-table"].draw(true);
        }

        $('#filter_source_id, #date_filter_on, #filter_addedBy, #filter_lead_status, #filter_subclass, #filter_priority').on('change keyup',
            function () {
                if ($('#filter_source_id').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#date_filter_on').val() != "created_at") {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#filter_addedBy').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#filter_lead_status').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#filter_subclass').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#filter_priority').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                } else {
                    $('#reset-filters').addClass('d-none');
                }
                showTable();
            });

        $('#search-text-field').on('keyup', function () {
            if ($('#search-text-field').val() != "") {
                $('#reset-filters').removeClass('d-none');
                showTable();
            }
        });

        $('#reset-filters, #reset-filters-2').click(function () {
            $('#filter-form')[0].reset();
            $('.filter-box .select-picker').selectpicker("refresh");
            $('#reset-filters').addClass('d-none');
            showTable();
        });

        $('#quick-action-type').change(function () {
            const actionValue = $(this).val();
            if (actionValue != '') {
                $('#quick-action-apply').removeAttr('disabled');
            } else {
                $('#quick-action-apply').attr('disabled', true);
            }
        });

        $('#quick-action-apply').click(function () {
            const actionValue = $('#quick-action-type').val();
            if (actionValue == 'delete') {
                Swal.fire({
                    title: "@lang('messages.sweetAlertTitle')",
                    text: "@lang('messages.recoverRecord')",
                    icon: 'warning',
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: "@lang('messages.confirmDelete')",
                    cancelButtonText: "@lang('app.cancel')",
                    customClass: {
                        confirmButton: 'btn btn-primary mr-3',
                        cancelButton: 'btn btn-secondary'
                    },
                    showClass: {
                        popup: 'swal2-noanimation',
                        backdrop: 'swal2-noanimation'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        applyQuickAction();
                    }
                });

            } else {
                applyQuickAction();
            }
        });

        $('body').on('click', '.delete-table-row', function () {
            var id = $(this).data('id');
            Swal.fire({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.recoverRecord')",
                icon: 'warning',
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "@lang('messages.confirmDelete')",
                cancelButtonText: "@lang('app.cancel')",
                customClass: {
                    confirmButton: 'btn btn-primary mr-3',
                    cancelButton: 'btn btn-secondary'
                },
                showClass: {
                    popup: 'swal2-noanimation',
                    backdrop: 'swal2-noanimation'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('new-leads.destroy', ':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {
                            '_token': token,
                            '_method': 'DELETE'
                        },
                        success: function (response) {
                            if (response.status == "success") {
                                showTable();
                            }
                        }
                    });
                }
            });
        });

        const applyQuickAction = () => {
            var rowdIds = $("#new-leads-table input:checkbox:checked").map(function () {
                return $(this).val();
            }).get();

            var url = "{{ route('new-leads.apply_quick_action') }}?row_ids=" + rowdIds;

            $.easyAjax({
                url: url,
                container: '#quick-action-form',
                type: "POST",
                disableButton: true,
                buttonSelector: "#quick-action-apply",
                data: $('#quick-action-form').serialize(),
                blockUI: true,
                success: function (response) {
                    if (response.status == 'success') {
                        showTable();
                        resetActionButtons();
                        deSelectAll();
                        $('#quick-action-form').hide();
                    }
                }
            })
        };

        $( document ).ready(function() {
            @if (!is_null(request('start')) && !is_null(request('end')))
            $('#datatableRange').val('{{ request('start') }}' +
            ' @lang("app.to") ' + '{{ request('end') }}');
            $('#datatableRange').data('daterangepicker').setStartDate("{{ request('start') }}");
            $('#datatableRange').data('daterangepicker').setEndDate("{{ request('end') }}");
                showTable();
            @endif

            // Function to initialize follow-up tooltips
            function initFollowUpTooltips() {
                // Destroy existing follow-up tooltips to prevent conflicts
                $('.follow-up-tooltip-trigger').each(function() {
                    if ($(this).data('bs.tooltip')) {
                        $(this).tooltip('dispose');
                    }
                });
                // Initialize follow-up tooltips
                $('.follow-up-tooltip-trigger').tooltip({
                    html: true,
                    placement: 'auto',
                    trigger: 'hover',
                    container: 'body',
                    delay: { show: 300, hide: 100 }
                });
            }

            // Function to update priority dropdown display with icon
            // Note: bootstrap-select should automatically render from data-content attribute,
            // but we use this as a fallback to ensure icons are displayed correctly
            function updatePriorityDisplay($select) {
                if (!$select || !$select.length) {
                    return false;
                }
                
                try {
                    var priority = $select.val();
                    if (!priority) {
                        return false;
                    }
                    
                    // Wait a bit to ensure bootstrap-select has rendered
                    var $bootstrapSelect = $select.closest('.bootstrap-select');
                    
                    if (!$bootstrapSelect.length) {
                        // Try parent if closest doesn't work
                        $bootstrapSelect = $select.parent('.bootstrap-select');
                    }
                    
                    if (!$bootstrapSelect.length) {
                        return false;
                    }
                    
                    // Find the filter-option element - check if it exists and is not null
                    var $filterOption = $bootstrapSelect.find('.filter-option');
                    
                    if (!$filterOption.length) {
                        // Try alternative selectors
                        $filterOption = $bootstrapSelect.find('.filter-option-inner');
                    }
                    
                    if (!$filterOption.length) {
                        $filterOption = $bootstrapSelect.find('.dropdown-toggle .filter-option');
                    }
                    
                    // If still not found, return early to avoid null reference error
                    if (!$filterOption.length || $filterOption.length === 0) {
                        return false;
                    }
                    
                    // Double check the element exists before manipulating
                    var filterOptionElement = $filterOption[0];
                    if (!filterOptionElement) {
                        return false;
                    }
                    
                    var priorityIconMap = {
                        'Select Priority': '',
                        '1st Priority': '1st_Priority.svg',
                        '2nd Priority': '2nd_Priority.svg',
                        '3rd Priority': '3rd_Priority.svg',
                        '4th Priority': '4th_Priority.svg',
                        '5th Priority': '5th_Priority.svg',
                    };
                    
                    var iconFile = priorityIconMap[priority] || '';
                    
                    // Only update if we have an icon and element exists, otherwise let bootstrap-select handle it via data-content
                    if (iconFile && priority !== 'Select Priority' && filterOptionElement) {
                        var iconPath = '{{ asset("img/icon") }}/' + iconFile;
                        var $existingContent = $filterOption.find('img');
                        if (!$existingContent.length && filterOptionElement.innerHTML !== undefined) {
                            // Only update if icon is not already there and element is ready
                            filterOptionElement.innerHTML = '<div class="d-flex align-items-center"><img src="' + iconPath + '" style="width: 18px; height: 18px; margin-right: 6px;"><span>' + priority + '</span></div>';
                        }
                    } else if (priority === 'Select Priority' && filterOptionElement && filterOptionElement.textContent !== undefined) {
                        // For Select Priority, ensure it shows the text
                        var currentText = filterOptionElement.textContent.trim();
                        if (currentText !== priority && currentText !== 'No Priority Set') {
                            filterOptionElement.textContent = priority;
                        }
                    }
                    
                    return true;
                } catch (e) {
                    // Silently fail - bootstrap-select should handle rendering via data-content
                    return false;
                }
            }
            
            // Make function globally accessible
            window.updatePriorityDisplay = updatePriorityDisplay;

            // Initialize select pickers and tooltips after table draw
            $('#new-leads-table').on('draw.dt', function() {
                $('.priority-select, .status-select, .quality-select').selectpicker();
                // Small delay to ensure DOM is ready
                setTimeout(function() {
                    initFollowUpTooltips();
                    applyAllDropdownColors();
                    // Update priority displays with icons after selectpicker has fully rendered
                    setTimeout(function() {
                        $('.priority-select').each(function() {
                            try {
                                if (typeof updatePriorityDisplay === 'function') {
                                    updatePriorityDisplay($(this));
                                }
                            } catch (e) {
                                // Silently fail - bootstrap-select should handle rendering via data-content
                            }
                        });
                    }, 200);
                }, 100);
            });

            // Also initialize on initial load if table is already drawn
            setTimeout(function() {
                initFollowUpTooltips();
                applyAllDropdownColors();
                // Update priority displays with icons
                $('.priority-select').each(function() {
                    if (typeof updatePriorityDisplay === 'function') {
                        updatePriorityDisplay($(this));
                    }
                });
            }, 500);
        });

        // Color mappings for status dropdown
        var statusColors = {
            "Untouched": "#9E9E9E",
            "Introduction": "#42A5F5",
            "Info Collected": "#26C6DA",
            "Consultation Call 1": "#9575CD",
            "Consultation Call 2": "#7E57C2",
            "Consultation Meet 1": "#5C6BC0",
            "Consultation Meet 2": "#3F51B5",
            "Documentation": "#81C784",
            "Final Discussion": "#4CAF50",
            "Estimation": "#C0CA33",
            "Payment": "#FFC107",
            "MOU": "#FB8C00",
            "File in Process": "#64B5F6",
            "File Submission": "#00BCD4",
            "Visa Process": "#8BC34A",
            "Flying Date Received": "#4DD0E1",
            "Join/Move/Admissions": "#43A047",
            "Follow Up": "#F06292",
            "Lead Close": "#E53935"
        };

        // Color mappings for Lead Quality dropdown
        var qualityColors = {
            "Assigned": "#42A5F5",
            "In-Process": "#26C6DA",
            "On Hold": "#FFC107",
            "Plan Dropped": "#FF7043",
            "Negotiation": "#8E24AA",
            "Future Prospect": "#7CB342",
            "Ringing": "#5C6BC0",
            "Dead/Junk Lead": "#E53935",
            "Not Interested": "#F06292",
            "Rejected": "#9E9E9E"
        };

        // Function to apply background color to dropdown button
        function applyDropdownColor($select, colorMap) {
            var selectedValue = $select.val();
            if (!selectedValue) return;
            
            var color = colorMap[selectedValue];
            if (!color) return;
            
            // Find the bootstrap-select wrapper and dropdown toggle button
            var $bootstrapSelect = $select.closest('.bootstrap-select');
            if (!$bootstrapSelect.length) {
                // If bootstrap-select wrapper doesn't exist, try parent
                $bootstrapSelect = $select.parent('.bootstrap-select');
            }
            
            var $button = $bootstrapSelect.find('.dropdown-toggle');
            
            if ($button.length) {
                $button.css({
                    'background-color': color,
                    'border-color': color,
                    'color': '#ffffff'
                });
            }
        }

        // Function to apply colors to all dropdowns
        function applyAllDropdownColors() {
            $('.status-select').each(function() {
                applyDropdownColor($(this), statusColors);
            });
            $('.quality-select').each(function() {
                applyDropdownColor($(this), qualityColors);
            });
        }

        // Track previous values to prevent duplicate calls
        var previousValues = {};

        // Handle priority dropdown change
        $(document).on('changed.bs.select', '.priority-select', function(e, clickedIndex, isSelected, previousValue) {
            e.stopImmediatePropagation();
            var $select = $(this);
            var leadId = $select.data('lead-id');
            var priority = $select.val();
            var key = 'priority_' + leadId;
            
            // Prevent duplicate calls and validate value
            if (!priority || priority === '' || previousValues[key] === priority) {
                return;
            }
            
            previousValues[key] = priority;
            
            // Let bootstrap-select render first via data-content, then update if needed
            setTimeout(function() {
                try {
                    if (typeof updatePriorityDisplay === 'function') {
                        updatePriorityDisplay($select);
                    }
                } catch (e) {
                    // Silently fail - bootstrap-select should handle rendering via data-content
                }
            }, 200);
            
            $.easyAjax({
                url: "{{ route('new-leads.update_priority') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    lead_id: leadId,
                    priority: priority
                },
                success: function(response) {
                    if (response.status == 'success') {
                        // Refresh selectpicker to ensure correct display
                        setTimeout(function() {
                            try {
                                $select.selectpicker('refresh');
                                // Wait for refresh to complete, then update display
                                setTimeout(function() {
                                    try {
                                        if (typeof updatePriorityDisplay === 'function') {
                                            updatePriorityDisplay($select);
                                        }
                                    } catch (e) {
                                        // Silently fail - bootstrap-select should handle rendering via data-content
                                    }
                                }, 100);
                            } catch (e) {
                                // Silently fail
                            }
                        }, 100);
                    }
                },
                error: function() {
                    // Reset previous value on error
                    delete previousValues[key];
                    // Revert display
                    try {
                        $select.selectpicker('val', previousValue);
                        $select.selectpicker('refresh');
                        setTimeout(function() {
                            if (typeof updatePriorityDisplay === 'function') {
                                updatePriorityDisplay($select);
                            }
                        }, 100);
                    } catch (e) {
                        console.error('Error reverting priority:', e);
                    }
                }
            });
        });

        // Handle status dropdown change
        $(document).on('changed.bs.select', '.status-select', function(e, clickedIndex, isSelected, previousValue) {
            e.stopImmediatePropagation();
            var $select = $(this);
            var leadId = $select.data('lead-id');
            var status = $select.val();
            var key = 'status_' + leadId;
            
            // Apply color immediately
            applyDropdownColor($select, statusColors);
            
            // Prevent duplicate calls and validate value
            if (!status || status === '' || previousValues[key] === status) {
                return;
            }
            
            previousValues[key] = status;
            
            $.easyAjax({
                url: "{{ route('new-leads.update_status') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    lead_id: leadId,
                    status: status
                },
                success: function(response) {
                    if (response.status == 'success') {
                        // Optionally show a success message
                    }
                },
                error: function() {
                    // Reset previous value on error
                    delete previousValues[key];
                }
            });
        });

        // Handle lead quality dropdown change
        $(document).on('changed.bs.select', '.quality-select', function(e, clickedIndex, isSelected, previousValue) {
            e.stopImmediatePropagation();
            var $select = $(this);
            var leadId = $select.data('lead-id');
            var quality = $select.val();
            var key = 'quality_' + leadId;
            
            // Apply color immediately
            applyDropdownColor($select, qualityColors);
            
            // Prevent duplicate calls and validate value
            if (!quality || quality === '' || previousValues[key] === quality) {
                return;
            }
            
            previousValues[key] = quality;
            
            $.easyAjax({
                url: "{{ route('new-leads.update_quality') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    lead_id: leadId,
                    quality: quality
                },
                success: function(response) {
                    if (response.status == 'success') {
                        // Optionally show a success message
                    }
                },
                error: function() {
                    // Reset previous value on error
                    delete previousValues[key];
                }
            });
        });

        // Handle follow-up button click (Add) - stop propagation to prevent dropdown interference
        $(document).on('click', '.add-follow-up-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            var leadId = $(this).data('lead-id');
            $('#follow_up_lead_id').val(leadId);
            $('#follow_up_id').val(''); // Clear edit ID
            $('#addFollowUpModalListLabel').text('Add Follow-Up');
            $('#followUpFormList')[0].reset();
            $('#follow_up_lead_id').val(leadId); // Set lead ID again after reset
            // Explicitly clear date and time fields
            $('#next_follow_up_date').val('');
            $('#next_follow_up_time').val('');
            $('.next_follow_up_datetime_div, .send_reminder_div, .follow_up_subject_line_div').addClass('d-none');
            $('#send_reminder').prop('checked', false);
            // Trigger change event to ensure UI is in sync
            $('#send_reminder').trigger('change');
            
            // Open modal programmatically instead of using data-toggle
            $('#addFollowUpModalList').modal('show');
        });

        // Handle edit follow-up button click - stop propagation
        $(document).on('click', '.edit-follow-up-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            var followUpId = $(this).data('follow-up-id');
            var leadId = $(this).data('lead-id');
            
            $.easyAjax({
                url: "{{ route('new-leads.follow-up-edit', ':id') }}".replace(':id', followUpId),
                type: "GET",
                blockUI: true,
                success: function(response) {
                    console.log('Edit follow-up response:', response);
                    
                    // Handle both response formats: response.follow_up or response.data.follow_up
                    var followUp = null;
                    if (response && response.status == "success") {
                        if (response.follow_up) {
                            followUp = response.follow_up;
                        } else if (response.data && response.data.follow_up) {
                            followUp = response.data.follow_up;
                        }
                    } else if (response && response.follow_up) {
                        // Handle case where status is not in response but follow_up is
                        followUp = response.follow_up;
                    } else if (response && response.data && response.data.follow_up) {
                        followUp = response.data.follow_up;
                    }
                    
                    if (followUp) {
                        // Set modal title
                        $('#addFollowUpModalListLabel').text('Edit Follow-Up');
                        
                        // Set form values
                        $('#follow_up_id').val(followUp.id);
                        $('#follow_up_lead_id').val(followUp.new_lead_id);
                        $('input[name="follow_up_type"][value="' + followUp.follow_up_type + '"]').prop('checked', true);
                        $('#subject').val(followUp.subject || '');
                        $('#outcome').val(followUp.outcome || '');
                        $('#notes').val(followUp.notes || '');
                        $('#next_follow_up_date').val(followUp.next_follow_up_date || '');
                        $('#next_follow_up_time').val(followUp.next_follow_up_time || '');
                        $('#send_reminder').prop('checked', followUp.send_reminder == 'yes');
                        $('#remind_time').val(followUp.remind_time || '15 Minutes Before');
                        $('#follow_up_subject_line').val(followUp.follow_up_subject_line || '');
                        
                        // Show/hide date/time fields based on send_reminder
                        if (followUp.send_reminder == 'yes') {
                            $('.next_follow_up_datetime_div').removeClass('d-none');
                        } else {
                            $('.next_follow_up_datetime_div').addClass('d-none');
                        }
                        
                        // Trigger change event to show/hide reminder sections
                        $('#send_reminder').trigger('change');
                        
                        // Refresh select pickers
                        $('.select-picker').selectpicker('refresh');
                        
                        // Reinitialize date and time pickers after setting values
                        setTimeout(function() {
                            // Destroy existing datepicker if any
                            var dateInput = document.getElementById('next_follow_up_date');
                            if (dateInput && dateInput._datepicker) {
                                dateInput._datepicker.destroy();
                            }
                            
                            // Initialize date picker (no minDate restriction for edit)
                            const dp = datepicker('#next_follow_up_date', {
                                position: 'bl',
                                ...datepickerConfig
                            });
                            
                            // Set the date value if exists
                            if (followUp.next_follow_up_date) {
                                var dateValue = moment(followUp.next_follow_up_date, '{{ company()->moment_date_format }}').toDate();
                                dp.setDate(dateValue, true);
                            }
                            
                            // Reinitialize time picker
                            $('#next_follow_up_time').timepicker({
                                @if (company()->time_format == 'H:i')
                                    showMeridian: false,
                                @endif
                            });
                        }, 100);
                        
                        // Open modal
                        $('#addFollowUpModalList').modal('show');
                    } else {
                        $.showToastr('Failed to load follow-up data. Please try again.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    $.showToastr('An error occurred while loading follow-up data. Please try again.', 'error');
                }
            });
        });

        // Initialize date and time pickers for follow-up modal
        $('#addFollowUpModalList').on('shown.bs.modal', function() {
            $('.select-picker').selectpicker();
            
            // Check if this is edit mode
            var isEditMode = $('#follow_up_id').val() !== '';
            
            // Clear date and time fields if not in edit mode
            if (!isEditMode) {
                $('#next_follow_up_date').val('');
                $('#next_follow_up_time').val('');
            }
            
            // Initialize date picker - only allow future dates for new follow-ups
            const dp = datepicker('#next_follow_up_date', {
                position: 'bl',
                minDate: isEditMode ? null : new Date(), // Allow past dates when editing
                ...datepickerConfig
            });

            // Initialize time picker
            $('#next_follow_up_time').timepicker({
                @if (company()->time_format == 'H:i')
                    showMeridian: false,
                @endif
            });

            // Validate time when date is selected
            $('#next_follow_up_date').on('changeDate', function() {
                var selectedDate = $(this).val();
                var today = new Date();
                today.setHours(0, 0, 0, 0);
                
                var selectedDateObj = new Date(selectedDate);
                selectedDateObj.setHours(0, 0, 0, 0);
                
                // If selected date is today, we should validate time is in future
                // This will be handled on form submission
            });

        });

        // Toggle reminder div, follow up subject line, and next follow up date/time - use event delegation so it works even when modal is opened dynamically
        $(document).on('change', '#send_reminder', function() {
            var isChecked = $(this).is(':checked');
            $('.next_follow_up_datetime_div').toggleClass('d-none', !isChecked);
            $('.send_reminder_div').toggleClass('d-none', !isChecked);
            $('.follow_up_subject_line_div').toggleClass('d-none', !isChecked);
            
            // Make follow up subject line required/unrequired
            if (isChecked) {
                $('#follow_up_subject_line').attr('required', 'required');
            } else {
                $('#follow_up_subject_line').removeAttr('required');
            }
        });

        // Save follow-up (both create and update)
        $('#save-followup-list').click(function() {
            var sendReminderChecked = $('#send_reminder').is(':checked');
            
            // Only validate reminder-related fields if Send Reminder is checked
            if (sendReminderChecked) {
                // Validate Follow Up Subject Line if Send Reminder is checked
                var followUpSubjectLine = $('#follow_up_subject_line').val();
                if (!followUpSubjectLine || followUpSubjectLine.trim() === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Follow Up Subject Line is required when Send Reminder is checked.',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
                
                var followUpDate = $('#next_follow_up_date').val();
                var followUpTime = $('#next_follow_up_time').val();
                var followUpId = $('#follow_up_id').val();
                
                // Validate that date and time are provided when send reminder is checked
                if (!followUpDate || followUpDate.trim() === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Next Follow Up Date is required when Send Reminder is checked.',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
                
                if (!followUpTime || followUpTime.trim() === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Next Follow Up Time is required when Send Reminder is checked.',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
                
                // Validate future date and time using moment.js (only for new follow-ups)
                if (!followUpId) {
                    // Parse date using company's date format
                    var selectedDate = moment(followUpDate, '{{ company()->moment_date_format }}');
                    var today = moment().startOf('day');
                    
                    // Check if date is in the past
                    if (!selectedDate.isValid() || selectedDate.isBefore(today)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Next Follow Up Date must be today or a future date.',
                            confirmButtonText: 'OK'
                        });
                        return false;
                    }
                    
                    // If date is today and time is provided, check if time is in the future
                    if (selectedDate.isSame(today, 'day') && followUpTime) {
                        // Parse time using company's time format
                        var timeFormat = '{{ company()->time_format == "H:i" ? "HH:mm" : (company()->time_format == "h:i A" ? "hh:mm A" : "hh:mm a") }}';
                        var selectedTime = moment(followUpTime, timeFormat);
                        var now = moment();
                        
                        // Combine date and time
                        var selectedDateTime = selectedDate.clone();
                        selectedDateTime.hour(selectedTime.hour());
                        selectedDateTime.minute(selectedTime.minute());
                        selectedDateTime.second(0);
                        
                        if (!selectedTime.isValid() || selectedDateTime.isSameOrBefore(now)) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Next Follow Up Time must be in the future.',
                                confirmButtonText: 'OK'
                            });
                            return false;
                        }
                    } else if (followUpDate && followUpTime) {
                        // Both date and time provided - validate combined datetime
                        var timeFormat = '{{ company()->time_format == "H:i" ? "HH:mm" : (company()->time_format == "h:i A" ? "hh:mm A" : "hh:mm a") }}';
                        var selectedTime = moment(followUpTime, timeFormat);
                        var now = moment();
                        
                        var selectedDateTime = selectedDate.clone();
                        selectedDateTime.hour(selectedTime.hour());
                        selectedDateTime.minute(selectedTime.minute());
                        selectedDateTime.second(0);
                        
                        if (!selectedTime.isValid() || selectedDateTime.isSameOrBefore(now)) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Next Follow Up Date and Time must be in the future.',
                                confirmButtonText: 'OK'
                            });
                            return false;
                        }
                    }
                }
            }
            
            // Determine if this is an update or create
            var url = followUpId ? "{{ route('new-leads.follow-up-update') }}" : "{{ route('new-leads.follow-up-store') }}";
            
            $.easyAjax({
                url: url,
                container: '#followUpFormList',
                type: "POST",
                blockUI: true,
                data: $('#followUpFormList').serialize(),
                success: function(response) {
                    if (response.status == "success") {
                        $('#addFollowUpModalList').modal('hide');
                        $('#followUpFormList')[0].reset();
                        $('#follow_up_id').val('');
                        $('#addFollowUpModalListLabel').text('Add Follow-Up');
                        showTable();
                    }
                }
            });
        });

    </script>
@endpush
