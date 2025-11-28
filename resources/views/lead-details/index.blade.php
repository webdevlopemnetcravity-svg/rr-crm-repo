@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/lead-details.css') }}">
    <style>
        /* Hide disabled subclass options completely */
        .bootstrap-select .dropdown-menu li.disabled {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        <div class="lead-details-container">
            <!-- Top Header Bar -->
            <div class="lead-header-bar bg-white p-3 border-bottom-grey">
                <!-- Left Section: Avatar + Priority + Lead ID -->
                <div class="lead-header-left-group d-flex align-items-center">
                    <div class="lead-avatar-section d-flex align-items-center">
                        <div class="lead-avatar-circle text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px;">
                            <img src="{{ asset('img/icon/user.svg') }}">
                        </div>
                        <div class="lead-info-group">
                            <div class="lead-priority d-flex align-items-center mb-1">
                                @if(isset($lead) && $lead)
                                    <img src="{{ asset('img/icon/' . str_replace(' ', '_', $lead->priority) . '.svg') }}">
                                    <span class="f-12 pl-1">{{ $lead->priority ?? 'Select Priority' }}</span>
                                @else
                                    <img src="{{ asset('img/icon/1st_Priority.svg') }}">
                                    <span class="f-12 pl-1">1st Priority</span>
                                @endif
                            </div>
                            <div class="lead-id-header f-14 font-weight-bold">
                                @if(isset($lead) && $lead)
                                    LEAD-{{ str_pad($lead->id, 4, '0', STR_PAD_LEFT) }}
                                @else
                                    LEAD-0008
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Middle-Left Section: Contact Info -->
                <div class="lead-contact-info d-flex align-items-center">
                    @if(isset($lead) && $lead)
                        @php
                            $step1Data = $lead->step_1_data ?? [];
                            $primaryPhone = $step1Data['primary_phone'] ?? $lead->mobile ?? '';
                            $email = $step1Data['email_address'] ?? $lead->client_email ?? '';
                        @endphp
                        @if($primaryPhone)
                            <div class="contact-info-item mr-4">
                                <a href="tel:{{ $primaryPhone }}" class="text-dark">
                                    <img src="{{ asset('img/icon/Phone.svg') }}">
                                    <span class="pl-1">{{ $primaryPhone }}</span>
                                </a>
                            </div>
                        @endif
                        @if($email)
                            <div class="contact-info-item">
                                <a href="mailto:{{ $email }}?subject=SUBJECT&body=Demo email" target="_blank" class="text-dark">
                                    <img src="{{ asset('img/icon/Mail.svg') }}">
                                    <span class="pl-1">{{ $email }}</span>
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="contact-info-item mr-4">
                            <a href="tel:+91123-456-7890" class="text-dark">
                                <img src="{{ asset('img/icon/Phone.svg') }}">
                                <span class="pl-1">+91 123 4567 890</span>
                            </a>
                        </div>
                        <div class="contact-info-item">
                            <a href="mailto:abc@gmail.com?subject=SUBJECT&body=Demo email" target="_blank" class="text-dark">
                                <img src="{{ asset('img/icon/Mail.svg') }}">
                                <span class="pl-1">abc@gmail.com</span>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- View Resume Button -->
                <button class="btn btn-success btn-sm">View Resume</button>

                <!-- Service Name and Action Icons -->
                <div class="lead-header-right-group d-flex align-items-center">
                    <div class="lead-service-actions-group d-flex align-items-center">
                        <div class="lead-service-section mr-3">
                            <div class="lead-service-name f-14 font-weight-bold">
                                @if(isset($lead) && $lead && $lead->step_2_data)
                                    @php
                                        $step2Data = $lead->step_2_data ?? [];
                                        $serviceName = '--';
                                        if (isset($step2Data['pr_subclass']) && !empty($step2Data['pr_subclass'])) {
                                            $serviceName = $step2Data['pr_subclass'];
                                        } elseif (isset($step2Data['visit_subclass']) && !empty($step2Data['visit_subclass'])) {
                                            $serviceName = $step2Data['visit_subclass'];
                                        } elseif (isset($step2Data['work_subclass']) && !empty($step2Data['work_subclass'])) {
                                            $serviceName = $step2Data['work_subclass'];
                                        } elseif (isset($step2Data['student_subclass']) && !empty($step2Data['student_subclass'])) {
                                            $serviceName = $step2Data['student_subclass'];
                                        }
                                    @endphp
                                    {{ $serviceName }}
                                @else
                                    PR - Employer Nomination Scheme (ENS)(Subclass 186)
                                @endif
                            </div>
                        </div>
                        <div class="lead-header-actions d-flex align-items-center">
                            <a class="Whatsapp mr-2" href="#">
                                <img src="{{ asset('img/icon/Whatsapp_icon.svg') }}">
                            </a>
                            <a class="Email mr-2" href="#">
                                <img src="{{ asset('img/icon/Mail_1.svg') }}">
                            </a>
                            <a class="back-arrow" href="{{ route('lead-list.index') }}">
                                <img src="{{ asset('img/icon/Back_Arrow.svg') }}">
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Tabs Bar -->
                        <!-- Tabs Navigation -->
            <div class="s-b-n-header bg-white" id="tabs">
                <nav class="tabs px-4 border-bottom-grey">
                    <div class="nav" id="nav-tab" role="tablist">
                        <a class="nav-item-lead nav-link-lead f-14 active" data-tab="clientInfoTab" href="#">
                            <div class="tab-item"><img src="{{ asset('img/icon/Client_Info.svg') }}"></div>Client Info
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" data-tab="processTab" href="#">
                            <div class="tab-item"><img src="{{ asset('img/icon/Process.svg') }}"></div>Process
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" data-tab="fileNotesTab" href="#">
                            <div class="tab-item"><img src="{{ asset('img/icon/File_Notes.svg') }}"></div>File Notes
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" data-tab="documentsTab" href="#">
                            <div class="tab-item"><img src="{{ asset('img/icon/Documents.svg') }}"></div>Documents
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" data-tab="accountsTab" href="#">
                            <div class="tab-item"><img src="{{ asset('img/icon/Accounts.svg') }}"></div>Accounts
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" data-tab="templateDocumentTab" href="#">
                            <div class="tab-item"><img src="{{ asset('img/icon/Communication.svg') }}"></div>Template Document
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" data-tab="followUpTab" href="#">
                            <div class="tab-item"><img src="{{ asset('img/icon/Follow_Up.svg') }}"></div>Follow Up
                        </a>
                        <a class="nav-item-lead nav-link-lead f-14" data-tab="travelDetailsTab" href="#">
                            <div class="tab-item"><img src="{{ asset('img/icon/Travel_Details.svg') }}"></div>Travel Details
                        </a>
                    </div>
                </nav>
            </div>

            <!-- Main Content Area -->
            <div class="lead-content-area bg-white p-20" id="mainContentArea">
                <!-- Client Info Tab Content -->
                @include('lead-details.components.client-info-tab')
                <!-- Process Tab Content -->
                @include('lead-details.components.process-tab')
                <!-- File Notes Tab Content -->
                @include('lead-details.components.file-notes-tab')
                <!-- Documents Tab Content -->
                @include('lead-details.components.documents-tab')
                <!-- Accounts Tab Content -->
                @include('lead-details.components.accounts-tab')
                <!-- Template Document Tab Content -->
                @include('lead-details.components.template-document-tab')
                <!-- Follow Up Tab Content -->
                @include('lead-details.components.follow-up-tab')
                <!-- Travel Details Tab Content -->
                @include('lead-details.components.travel-details-tab')
            </div>
        </div>
    </div>
    <!-- CONTENT WRAPPER END -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize select pickers
            $('.select-picker').selectpicker();
            
            // Check if there's a hash in URL to activate specific tab
            if (window.location.hash) {
                var hash = window.location.hash.substring(1);
                var $tabLink = $('.nav-item-lead[data-tab="' + hash + '"]');
                if ($tabLink.length) {
                    $('.nav-item-lead').removeClass('active');
                    $tabLink.addClass('active');
                    $('.tab-content').removeClass('active');
                    $('#' + hash).addClass('active');
                }
            }
            
            // Tab Switching Functionality
            $('.nav-item-lead').on('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all tabs
                $('.nav-item-lead').removeClass('active');
                // Add active class to clicked tab
                $(this).addClass('active');
                
                // Hide all tab contents
                $('.tab-content').removeClass('active');
                
                // Show selected tab content
                const tabId = $(this).data('tab');
                if (tabId) {
                    $('#' + tabId).addClass('active');
                    // Update URL hash without triggering hashchange events
                    // Only update if hash is different to prevent unnecessary updates
                    const currentHash = window.location.hash.substring(1);
                    if (currentHash !== tabId) {
                        // Use replaceState to update hash silently without triggering hashchange
                        if (window.history && window.history.replaceState) {
                            window.history.replaceState(null, null, '#' + tabId);
                        } else {
                            // Fallback for older browsers - directly set hash (may trigger hashchange in old browsers)
                            window.location.hash = tabId;
                        }
                    }
                }
            });

            // Reinitialize select pickers when modals are opened
            $('#addFileNoteModal, #addInvoiceModal, #addCommunicationModal, #addFollowUpModal, #notifyClientModal, #addTravelDetailsModal').on('shown.bs.modal', function () {
                $('.select-picker').selectpicker('refresh');
            });

            // Initialize Quill editor when File Note modal opens
            $('#addFileNoteModal').on('shown.bs.modal', function () {
                // Check if Quill is already initialized for this editor
                if (!quillArray['#file-note-editor']) {
                    quillImageLoad('#file-note-editor');
                }
            });

            // Clear and destroy Quill editor when modal is closed
            $('#addFileNoteModal').on('hidden.bs.modal', function () {
                if (quillArray['#file-note-editor']) {
                    destory_editor('#file-note-editor');
                    delete quillArray['#file-note-editor'];
                    $('#file-note-editor').html('');
                    $('#file-note-editor-text').val('');
                }
            });

            // Prevent form submission for file note form
            $('#fileNoteFormDetails').on('submit', function(e) {
                e.preventDefault();
                return false;
            });
            
            // Save File Note button handler - use event delegation
            $(document).on('click', '#save-file-note-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Copy content from Quill editor to hidden textarea
                if (document.getElementById('file-note-editor') && document.getElementById('file-note-editor').children[0]) {
                    var note = document.getElementById('file-note-editor').children[0].innerHTML;
                    document.getElementById('file-note-editor-text').value = note;
                }
                
                // Validate note content
                var noteContent = $('#file-note-editor-text').val();
                if (!noteContent || noteContent.trim() === '' || noteContent.trim() === '<p><br></p>') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please enter a note.',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
                
                // Get lead ID
                var leadId = $('#file_note_lead_id_details').val();
                if (!leadId) {
                    try {
                        if (typeof $.showToastr === 'function') {
                            $.showToastr('Lead ID is missing.', 'error');
                        } else if (typeof toastr !== 'undefined') {
                            toastr.error('Lead ID is missing.');
                        }
                    } catch (e) {}
                    return false;
                }
                
                $.easyAjax({
                    url: "{{ route('new-leads.file-note-store') }}",
                    container: '#fileNoteFormDetails',
                    type: "POST",
                    blockUI: true,
                    data: $('#fileNoteFormDetails').serialize(),
                    success: function(response) {
                        if (response.status == "success") {
                            $('#addFileNoteModal').modal('hide');
                            $('#fileNoteFormDetails')[0].reset();
                            
                            // Clear Quill editor
                            if (quillArray['#file-note-editor']) {
                                destory_editor('#file-note-editor');
                                delete quillArray['#file-note-editor'];
                                $('#file-note-editor').html('');
                                $('#file-note-editor-text').val('');
                            }
                            
                            // Show success message (with error handling)
                            try {
                                if (typeof $.showToastr === 'function') {
                                    $.showToastr(response.message || 'File note saved successfully', 'success');
                                } else if (typeof toastr !== 'undefined') {
                                    toastr.success(response.message || 'File note saved successfully');
                                }
                            } catch (e) {}
                            
                            // Refresh file notes section via AJAX
                            // Use leadId from outer scope (captured before the AJAX call)
                            if (!leadId) {
                                // Fallback: try to get leadId again
                                leadId = $('#file_note_lead_id_details').val();
                            }
                            
                            if (leadId) {
                                var fileNotesUrl = "{{ route('new-leads.file-notes', ':id') }}".replace(':id', leadId);
                                
                                $.easyAjax({
                                    url: fileNotesUrl,
                                    type: "GET",
                                    blockUI: false,
                                    success: function(fileNotesResponse) {
                                        // Handle different possible response structures
                                        var html = null;
                                        if (fileNotesResponse.status == "success" && fileNotesResponse.data && fileNotesResponse.data.html) {
                                            html = fileNotesResponse.data.html;
                                        } else if (fileNotesResponse.html) {
                                            html = fileNotesResponse.html;
                                        } else if (fileNotesResponse.data && fileNotesResponse.data.html) {
                                            html = fileNotesResponse.data.html;
                                        }
                                        
                                        if (html) {
                                            $('#fileNotesContent').html(html);
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        // Silent fail - file notes will refresh on next page load
                                    }
                                });
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        try {
                            if (typeof $.showToastr === 'function') {
                                $.showToastr('An error occurred while saving the file note. Please try again.', 'error');
                            } else if (typeof toastr !== 'undefined') {
                                toastr.error('An error occurred while saving the file note. Please try again.');
                            }
                        } catch (e) {}
                    }
                });
                
                return false;
            });

            // Initialize installment months selectpicker when invoice modal opens
            $('#addInvoiceModal').on('shown.bs.modal', function () {
                $('#installment_months').selectpicker();
                // Set toggle to checked (ON) by default
                $('#installment_payment_toggle').prop('checked', true).attr('aria-checked', 'true');
                $('#installment_months_container').show();
                // Calculate and show installment note
                calculateTotalAmount();
            });

            // Calculate Total Amount Function
            function calculateTotalAmount() {
                const price = parseFloat($('#price').val()) || 0;
                const taxValue = $('#tax').val() || '';
                const taxPercent = taxValue ? parseFloat(taxValue.replace('GST ', '').replace('%', '')) : 0;
                const discount = parseFloat($('#discount').val()) || 0;
                
                // Sub Total is the price
                const subTotal = price;
                
                // Calculate tax: Based on image, tax seems to be calculated on (price - discount)
                // But to match image values: Price=50,000, Discount=5,000, Tax=500, Total=45,500
                // Formula: Total = Price - Discount + Tax
                // So: 45,500 = 50,000 - 5,000 + Tax => Tax = 500
                let taxAmount = 0;
                if (taxPercent > 0) {
                    // Calculate tax on (price - discount)
                    const taxableAmount = price - discount;
                    taxAmount = (taxableAmount * taxPercent) / 100;
                }
                
                // Calculate total: Sub Total - Discount + Tax
                const totalAmount = subTotal - discount + taxAmount;
                
                // Update Net Amount field (amount after discount, before tax)
                const netAmount = price - discount;
                $('#net_amount').val(Math.round(netAmount));
                
                // Update summary box
                $('#summary_sub_total').text('INR ' + formatNumber(subTotal));
                $('#summary_discount').text('INR ' + formatNumber(discount));
                $('#summary_tax_amount').text('INR ' + formatNumber(Math.round(taxAmount)));
                $('#summary_total_amount').text('INR ' + formatNumber(Math.round(totalAmount)));
                
                // Update installment note if toggle is ON
                if ($('#installment_payment_toggle').is(':checked')) {
                    updateInstallmentNote(Math.round(totalAmount));
                }
            }
            
            // Get current total amount
            function getCurrentTotal() {
                const price = parseFloat($('#price').val()) || 0;
                const taxValue = $('#tax').val() || '';
                const taxPercent = taxValue ? parseFloat(taxValue.replace('GST ', '').replace('%', '')) : 0;
                const discount = parseFloat($('#discount').val()) || 0;
                
                let taxAmount = 0;
                if (taxPercent > 0) {
                    const taxableAmount = price - discount;
                    taxAmount = (taxableAmount * taxPercent) / 100;
                }
                
                return Math.round(price - discount + taxAmount);
            }

            // Format number with commas
            function formatNumber(num) {
                return num.toLocaleString('en-IN', { maximumFractionDigits: 0 });
            }

            // Update Installment Note
            function updateInstallmentNote(totalAmount) {
                const months = parseInt($('#installment_months').val());
                if (months > 0 && totalAmount > 0) {
                    const installmentAmount = totalAmount / months;
                    $('#installment_note_text').text('Installment Rs.' + installmentAmount.toFixed(1) + ' for ' + months + ' months');
                    $('#installment_note').show();
                } else {
                    $('#installment_note').hide();
                }
            }

            // Calculate totals when price, tax, or discount changes
            $(document).on('input change', '#price, #tax, #discount', function() {
                calculateTotalAmount();
            });

            // Installment Payment Toggle Functionality
            $(document).on('change', '#installment_payment_toggle', function() {
                const isChecked = $(this).is(':checked');
                $(this).attr('aria-checked', isChecked);
                
                if (isChecked) {
                    $('#installment_months_container').slideDown();
                    // Refresh selectpicker after showing
                    setTimeout(function() {
                        $('#installment_months').selectpicker('refresh');
                        // Calculate installment if months already selected
                        updateInstallmentNote(getCurrentTotal());
                    }, 100);
                } else {
                    $('#installment_months_container').slideUp();
                    $('#installment_months').val('').selectpicker('refresh');
                    $('#installment_note').hide();
                }
            });

            // Update installment note when months change
            $(document).on('change', '#installment_months', function() {
                if ($('#installment_payment_toggle').is(':checked')) {
                    updateInstallmentNote(getCurrentTotal());
                }
            });

            // Initialize calculation when modal opens
            $('#addInvoiceModal').on('shown.bs.modal', function () {
                calculateTotalAmount();
            });

            // Reset form when invoice modal is closed
            $('#addInvoiceModal').on('hidden.bs.modal', function () {
                $('#installment_payment_toggle').prop('checked', true).attr('aria-checked', 'true');
                $('#installment_months_container').show();
                $('#installment_months').val('8').selectpicker('refresh');
            });

            // ========== FOLLOW-UP FUNCTIONALITY ==========
            
            // Handle Add Follow-Up button click
            $(document).on('click', '#addFollowUpBtn', function(e) {
                e.preventDefault();
                var leadId = $('#follow_up_lead_id_details').val();
                if (!leadId) {
                    try {
                        if (typeof $.showToastr === 'function') {
                            $.showToastr('Lead ID is missing.', 'error');
                        } else if (typeof toastr !== 'undefined') {
                            toastr.error('Lead ID is missing.');
                        }
                    } catch (e) {}
                    return;
                }
                $('#follow_up_id_details').val(''); // Clear edit ID
                $('#addFollowUpModalLabel').text('Add Follow-Up');
                $('#followUpFormDetails')[0].reset();
                $('#follow_up_lead_id_details').val(leadId); // Set lead ID again after reset
                $('.next_follow_up_datetime_div_details, .send_reminder_div_details, .follow_up_subject_line_div_details').addClass('d-none');
                $('#send_reminder_details').prop('checked', false);
                $('#send_reminder_details').trigger('change');
                $('#addFollowUpModal').modal('show');
            });

            // Initialize date and time pickers for follow-up modal
            $('#addFollowUpModal').on('shown.bs.modal', function() {
                $('.select-picker').selectpicker();
                
                // Check if this is edit mode
                var isEditMode = $('#follow_up_id_details').val() !== '';
                
                // Initialize date picker - only allow future dates for new follow-ups
                const dp = datepicker('#next_follow_up_date_details', {
                    position: 'bl',
                    minDate: isEditMode ? null : new Date(), // Allow past dates when editing
                    ...datepickerConfig
                });

                // Initialize time picker
                $('#next_follow_up_time_details').timepicker({
                    @if (company()->time_format == 'H:i')
                        showMeridian: false,
                    @endif
                });
            });

            // Toggle reminder div, follow up subject line, and next follow up date/time
            $(document).on('change', '#send_reminder_details', function() {
                var isChecked = $(this).is(':checked');
                $('.next_follow_up_datetime_div_details').toggleClass('d-none', !isChecked);
                $('.send_reminder_div_details').toggleClass('d-none', !isChecked);
                $('.follow_up_subject_line_div_details').toggleClass('d-none', !isChecked);
                
                // Make follow up subject line required/unrequired
                if (isChecked) {
                    $('#follow_up_subject_line_details').attr('required', 'required');
                } else {
                    $('#follow_up_subject_line_details').removeAttr('required');
                }
            });

            // Handle edit follow-up button click
            $(document).on('click', '.edit-follow-up-btn-details', function(e) {
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
                        var followUp = null;
                        if (response && response.status == "success") {
                            if (response.follow_up) {
                                followUp = response.follow_up;
                            } else if (response.data && response.data.follow_up) {
                                followUp = response.data.follow_up;
                            }
                        } else if (response && response.follow_up) {
                            followUp = response.follow_up;
                        } else if (response && response.data && response.data.follow_up) {
                            followUp = response.data.follow_up;
                        }
                        
                        if (followUp) {
                            // Set modal title
                            $('#addFollowUpModalLabel').text('Edit Follow-Up');
                            
                            // Set form values
                            $('#follow_up_id_details').val(followUp.id);
                            $('#follow_up_lead_id_details').val(followUp.new_lead_id);
                            $('input[name="follow_up_type"][value="' + followUp.follow_up_type + '"]').prop('checked', true);
                            $('#subject_details').val(followUp.subject || '');
                            $('#outcome_details').val(followUp.outcome || '');
                            $('#notes_details').val(followUp.notes || '');
                            $('#next_follow_up_date_details').val(followUp.next_follow_up_date || '');
                            $('#next_follow_up_time_details').val(followUp.next_follow_up_time || '');
                            $('#send_reminder_details').prop('checked', followUp.send_reminder == 'yes');
                            $('#remind_time_details').val(followUp.remind_time || '15 Minutes Before');
                            $('#follow_up_subject_line_details').val(followUp.follow_up_subject_line || '');
                            
                            // Show/hide date/time fields based on send_reminder
                            if (followUp.send_reminder == 'yes') {
                                $('.next_follow_up_datetime_div_details').removeClass('d-none');
                            } else {
                                $('.next_follow_up_datetime_div_details').addClass('d-none');
                            }
                            
                            // Trigger change event to show/hide reminder sections
                            $('#send_reminder_details').trigger('change');
                            
                            // Refresh select pickers
                            $('.select-picker').selectpicker('refresh');
                            
                            // Reinitialize date and time pickers after setting values
                            setTimeout(function() {
                                // Destroy existing datepicker if any
                                var dateInput = document.getElementById('next_follow_up_date_details');
                                if (dateInput && dateInput._datepicker) {
                                    dateInput._datepicker.destroy();
                                }
                                
                                // Initialize date picker (no minDate restriction for edit)
                                const dp = datepicker('#next_follow_up_date_details', {
                                    position: 'bl',
                                    ...datepickerConfig
                                });
                                
                                // Set the date value if exists
                                if (followUp.next_follow_up_date) {
                                    var dateValue = moment(followUp.next_follow_up_date, '{{ company()->moment_date_format }}').toDate();
                                    dp.setDate(dateValue, true);
                                }
                                
                                // Reinitialize time picker
                                $('#next_follow_up_time_details').timepicker({
                                    @if (company()->time_format == 'H:i')
                                        showMeridian: false,
                                    @endif
                                });
                            }, 100);
                            
                            // Open modal
                            $('#addFollowUpModal').modal('show');
                        } else {
                            try {
                                if (typeof $.showToastr === 'function') {
                                    $.showToastr('Failed to load follow-up data. Please try again.', 'error');
                                } else if (typeof toastr !== 'undefined') {
                                    toastr.error('Failed to load follow-up data. Please try again.');
                                }
                            } catch (e) {}
                        }
                    },
                    error: function(xhr, status, error) {
                        try {
                            if (typeof $.showToastr === 'function') {
                                $.showToastr('An error occurred while loading follow-up data. Please try again.', 'error');
                            } else if (typeof toastr !== 'undefined') {
                                toastr.error('An error occurred while loading follow-up data. Please try again.');
                            }
                        } catch (e) {}
                    }
                });
            });


            // Save follow-up (both create and update)
            $('#save-followup-details').click(function() {
                var sendReminderChecked = $('#send_reminder_details').is(':checked');
                var followUpId = $('#follow_up_id_details').val();
                
                // Only validate reminder-related fields if Send Reminder is checked
                if (sendReminderChecked) {
                    // Validate Follow Up Subject Line if Send Reminder is checked
                    var followUpSubjectLine = $('#follow_up_subject_line_details').val();
                    if (!followUpSubjectLine || followUpSubjectLine.trim() === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Follow Up Subject Line is required when Send Reminder is checked.',
                            confirmButtonText: 'OK'
                        });
                        return false;
                    }
                    
                    var followUpDate = $('#next_follow_up_date_details').val();
                    var followUpTime = $('#next_follow_up_time_details').val();
                    
                    // Validate future date and time using moment.js (only for new follow-ups)
                    if (!followUpId && followUpDate) {
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
                        }
                    }
                }
                
                // Determine if this is an update or create
                var url = followUpId ? "{{ route('new-leads.follow-up-update') }}" : "{{ route('new-leads.follow-up-store') }}";
                
                $.easyAjax({
                    url: url,
                    container: '#followUpFormDetails',
                    type: "POST",
                    blockUI: true,
                    data: $('#followUpFormDetails').serialize(),
                    success: function(response) {
                        if (response.status == "success") {
                            $('#addFollowUpModal').modal('hide');
                            $('#followUpFormDetails')[0].reset();
                            $('#follow_up_id_details').val('');
                            $('#addFollowUpModalLabel').text('Add Follow-Up');
                            try {
                                if (typeof $.showToastr === 'function') {
                                    $.showToastr(response.message || 'Follow-up saved successfully', 'success');
                                } else if (typeof toastr !== 'undefined') {
                                    toastr.success(response.message || 'Follow-up saved successfully');
                                }
                            } catch (e) {}
                            
                            // Refresh follow-up section via AJAX
                            var leadId = $('#follow_up_lead_id_details').val();
                            if (leadId) {
                                var followUpListUrl = "{{ route('new-leads.follow-up-list', ':id') }}".replace(':id', leadId);
                                
                                $.easyAjax({
                                    url: followUpListUrl,
                                    type: "GET",
                                    blockUI: false,
                                    success: function(followUpResponse) {
                                        // Handle different possible response structures
                                        var html = null;
                                        if (followUpResponse.status == "success" && followUpResponse.data && followUpResponse.data.html) {
                                            html = followUpResponse.data.html;
                                        } else if (followUpResponse.html) {
                                            html = followUpResponse.html;
                                        } else if (followUpResponse.data && followUpResponse.data.html) {
                                            html = followUpResponse.data.html;
                                        }
                                        
                                        if (html) {
                                            $('#followUpContent').html(html);
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        // Silent fail - follow-up will refresh on next page load
                                    }
                                });
                            }
                        }
                    }
                });
            });

            // Reset form when follow-up modal is closed
            $('#addFollowUpModal').on('hidden.bs.modal', function () {
                $('#followUpFormDetails')[0].reset();
                $('#follow_up_id_details').val('');
                $('#addFollowUpModalLabel').text('Add Follow-Up');
                $('.next_follow_up_datetime_div_details, .send_reminder_div_details, .follow_up_subject_line_div_details').addClass('d-none');
                $('#send_reminder_details').prop('checked', false);
            });

            // ========== PROCESS TAB FUNCTIONALITY ==========
            
            // Helper function to show error message below a field (similar to add-lead)
            function showProcessFieldError(fieldId, errorMessage) {
                const $field = $(fieldId);
                
                // Find the parent column container
                const $parentColumn = $field.closest('.col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-12');
                
                // Check if it's a bootstrap-select field first
                const $bootstrapSelect = $field.closest('.bootstrap-select');
                
                // Remove ALL existing error messages comprehensively
                if ($parentColumn.length) {
                    // Remove from entire parent column
                    $parentColumn.find('.invalid-feedback').remove();
                }
                // Remove from field itself and siblings
                $field.next('.invalid-feedback').remove();
                $field.siblings('.invalid-feedback').remove();
                
                if ($bootstrapSelect.length) {
                    // For bootstrap-select, add is-invalid to the wrapper
                    $bootstrapSelect.addClass('is-invalid');
                    
                    // Remove errors from wrapper and its parent
                    $bootstrapSelect.next('.invalid-feedback').remove();
                    $bootstrapSelect.siblings('.invalid-feedback').remove();
                    $bootstrapSelect.parent().find('.invalid-feedback').remove();
                    
                    // Add error after the wrapper (only once)
                    $bootstrapSelect.after('<div class="invalid-feedback d-block">' + errorMessage + '</div>');
                } else if ($field.attr('type') === 'file') {
                    // For file inputs, add is-invalid to the field
                    $field.addClass('is-invalid');
                    // For file inputs, add error after the parent container
                    if ($parentColumn.length) {
                        $parentColumn.append('<div class="invalid-feedback d-block">' + errorMessage + '</div>');
                    } else {
                        $field.after('<div class="invalid-feedback d-block">' + errorMessage + '</div>');
                    }
                } else {
                    // For regular inputs, add is-invalid to the field
                    $field.addClass('is-invalid');
                    // Add error message below the field
                    $field.after('<div class="invalid-feedback d-block">' + errorMessage + '</div>');
                }
            }
            
            // Function to remove all field errors
            function removeProcessFieldErrors() {
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');
            }
            
            // Number validation for fee fields - only allow numbers and decimal point
            $(document).on('input', '#advance_fees, #remaining_fees, #agent_fees, #submission_fees', function() {
                var value = $(this).val();
                // Remove any non-numeric characters except decimal point
                var numericValue = value.replace(/[^0-9.]/g, '');
                // Ensure only one decimal point
                var parts = numericValue.split('.');
                if (parts.length > 2) {
                    numericValue = parts[0] + '.' + parts.slice(1).join('');
                }
                // Update the value if it changed
                if (value !== numericValue) {
                    $(this).val(numericValue);
                }
            });

            // Prevent non-numeric characters on keypress for fee fields
            $(document).on('keypress', '#advance_fees, #remaining_fees, #agent_fees, #submission_fees', function(e) {
                var char = String.fromCharCode(e.which);
                // Allow: backspace, delete, tab, escape, enter, decimal point, and numbers
                if (char === '.' && $(this).val().indexOf('.') !== -1) {
                    e.preventDefault(); // Prevent multiple decimal points
                    return false;
                }
                // Allow numbers and decimal point
                if (!/[0-9.]/.test(char) && !e.ctrlKey && !e.metaKey && e.keyCode !== 8 && e.keyCode !== 46 && e.keyCode !== 9 && e.keyCode !== 27 && e.keyCode !== 13) {
                    e.preventDefault();
                    return false;
                }
            });

            // Dynamic subclass filtering based on visa category
            function filterSubclassOptions() {
                var selectedCategory = $('#visa_category').selectpicker('val');
                var $subclassSelect = $('#subclass');
                var currentValue = $subclassSelect.selectpicker('val');
                
                // Show/hide options based on visa category
                $subclassSelect.find('option').each(function() {
                    var $option = $(this);
                    var visaCategory = $option.data('visa-category');
                    
                    // Always show the "Select" option
                    if ($option.val() === '') {
                        $option.prop('disabled', false);
                        return;
                    }
                    
                    // Enable/disable options based on visa category
                    if (selectedCategory && visaCategory === selectedCategory) {
                        $option.prop('disabled', false);
                    } else if (!selectedCategory) {
                        // If no category selected, show all options
                        $option.prop('disabled', false);
                    } else {
                        $option.prop('disabled', true);
                    }
                });
                
                // If current value doesn't match selected category, clear it
                if (selectedCategory && currentValue) {
                    var currentOptionCategory = $subclassSelect.find('option[value="' + currentValue + '"]').data('visa-category');
                    if (currentOptionCategory !== selectedCategory) {
                        $subclassSelect.val('').selectpicker('refresh');
                    } else {
                        $subclassSelect.selectpicker('refresh');
                    }
                } else {
                    $subclassSelect.selectpicker('refresh');
                }
            }
            
            // Initialize subclass filtering when form section is shown or on page load
            function initializeSubclassFiltering() {
                if ($('#processFormSection').is(':visible')) {
                    filterSubclassOptions();
                }
            }
            
            // Initialize on page load
            setTimeout(function() {
                initializeSubclassFiltering();
            }, 100);
            
            // Update subclass options when visa category changes
            $(document).on('changed.bs.select', '#visa_category', function() {
                filterSubclassOptions();
            });
            
            // Re-initialize when edit button is clicked
            $(document).on('click', '#editProcessBtn', function() {
                setTimeout(function() {
                    filterSubclassOptions();
                }, 100);
            });

            // File size validation and file name display for all file inputs (similar to add-lead)
            $(document).on('change', '#processForm input[type="file"][data-max-size]', function() {
                const file = this.files[0];
                const maxSize = $(this).data('max-size'); // 5242880 = 5MB
                const fieldId = $(this).attr('id');
                
                // Remove previous error
                $(this).removeClass('is-invalid');
                $(this).closest('.col-md-3').find('.invalid-feedback').remove();
                
                if (file && file.size > maxSize) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'File too large',
                            text: 'File size must be less than 5MB',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    } else {
                        alert('File size must be less than 5MB');
                    }
                    $(this).val('');
                }
            });
            
            // Edit Process Button Click
            $(document).on('click', '#editProcessBtn', function(e) {
                e.preventDefault();
                // Hide details section and show form section
                $('#processDetailsSection').hide();
                $('#processFormSection').show();
                // Hide edit button and show save/cancel buttons
                $('#editProcessBtn').hide();
                $('#processFormActions').show();
                // Initialize select pickers
                $('.select-picker').selectpicker('refresh');
                // Remove any previous errors
                removeProcessFieldErrors();
            });

            // Cancel Process Button Click
            $(document).on('click', '#cancelProcessBtn', function(e) {
                e.preventDefault();
                // Show details section and hide form section
                $('#processDetailsSection').show();
                $('#processFormSection').hide();
                // Show edit button and hide save/cancel buttons
                $('#editProcessBtn').show();
                $('#processFormActions').hide();
                // Remove any errors
                removeProcessFieldErrors();
            });

            // Save Process Button Click (from header or form bottom)
            $(document).on('click', '#saveProcessBtn, #saveProcessFormBtn', function(e) {
                e.preventDefault();
                
                // Remove previous errors
                removeProcessFieldErrors();
                
                var form = $('#processForm')[0];
                var isValid = true;
                
                // Basic HTML5 validation
                if (!form.checkValidity()) {
                    form.reportValidity();
                    isValid = false;
                }
                
                var leadId = $('#process_lead_id').val();
                if (!leadId) {
                    try {
                        if (typeof $.showToastr === 'function') {
                            $.showToastr('Lead ID is missing.', 'error');
                        } else if (typeof toastr !== 'undefined') {
                            toastr.error('Lead ID is missing.');
                        }
                    } catch (e) {}
                    return false;
                }
                
                // Custom validation for required fields
                var requiredFields = [
                    { id: '#applicant_name', name: 'Applicant Name' },
                    { id: '#visa_category', name: 'Visa Category' },
                    { id: '#subclass', name: 'Subclass' },
                    { id: '#passport_name', name: 'Passport Name' },
                    { id: '#passport_number', name: 'Passport Number' },
                    { id: '#agent_name', name: 'Agent Name' },
                    { id: '#advance_fees', name: 'Advance Fees' },
                    { id: '#advance_fees_due_date', name: 'Advance Fees Due Date' },
                    { id: '#remaining_fees', name: 'Remaining Fees' },
                    { id: '#remaining_fees_due_date', name: 'Remaining Fees Due Date' },
                    { id: '#agent_fees', name: 'Agent Fees' },
                    { id: '#submission_fees', name: 'Submission Fees' },
                    { id: '#status', name: 'Status' },
                    { id: '#processing_time', name: 'Processing Time' },
                    { id: '#bank_cheque_handover_date', name: 'Bank Cheque Document Handover Date' },
                    { id: '#passport_handover_date', name: 'Passport Handover Date' },
                    { id: '#process_note', name: 'Note related to agent or process' }
                ];
                
                requiredFields.forEach(function(field) {
                    var $field = $(field.id);
                    var value = $field.val();
                    
                    if (field.id === '#visa_category' || field.id === '#subclass') {
                        // For select pickers, check the actual select value
                        value = $field.selectpicker('val');
                    }
                    
                    if (!value || value.trim() === '') {
                        isValid = false;
                        showProcessFieldError(field.id, field.name + ' is required');
                    }
                });
                
                // Validate file uploads (only if no existing file)
                var fileFields = [
                    { id: '#contract_letter', name: 'Contract Letter' },
                    { id: '#grant_letter', name: 'Grant Letter' },
                    { id: '#offer_letter', name: 'Offer Letter/Sponsor Letter' },
                    { id: '#medical_letter', name: 'Medical Letter' },
                    { id: '#air_ticket', name: 'Air Ticket' },
                    { id: '#accommodation_letter', name: 'Accommodation Configuration Letter' }
                ];
                
                fileFields.forEach(function(field) {
                    var $field = $(field.id);
                    var hasFile = $field.val() && $field.val() !== '';
                    var hasExistingFile = $field.closest('.col-md-3').find('small a').length > 0;
                    
                    if (!hasFile && !hasExistingFile) {
                        isValid = false;
                        showProcessFieldError(field.id, field.name + ' is required');
                    }
                });
                
                if (!isValid) {
                    return false;
                }
                
                // Get select picker values and add to form
                $('#visa_category, #subclass').each(function() {
                    var $select = $(this);
                    var selectedValue = $select.selectpicker('val');
                    if (selectedValue) {
                        $select.val(selectedValue);
                    }
                });
                
                $.easyAjax({
                    url: "{{ route('new-leads.process-store') }}",
                    container: '#processForm',
                    type: "POST",
                    blockUI: true,
                    file: true,
                    disableButton: true,
                    buttonSelector: "#saveProcessBtn, #saveProcessFormBtn",
                    success: function(response) {
                        if (response.status == "success") {
                            try {
                                if (typeof $.showToastr === 'function') {
                                    $.showToastr(response.message || 'Process saved successfully', 'success');
                                } else if (typeof toastr !== 'undefined') {
                                    toastr.success(response.message || 'Process saved successfully');
                                }
                            } catch (e) {}
                            
                            // Reload the page after a short delay to show updated data
                            setTimeout(function() {
                                window.location.hash = 'processTab';
                                window.location.reload();
                            }, 500);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle validation errors from server
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                var fieldId = '#' + field;
                                showProcessFieldError(fieldId, messages[0]);
                            });
                        } else {
                            try {
                                if (typeof $.showToastr === 'function') {
                                    $.showToastr('An error occurred while saving the process. Please try again.', 'error');
                                } else if (typeof toastr !== 'undefined') {
                                    toastr.error('An error occurred while saving the process. Please try again.');
                                }
                            } catch (e) {}
                        }
                    }
                });
                
                return false;
            });

            // Send Template Document via Email
            $(document).on('click', '.send-template-email', function(e) {
                e.preventDefault();
                
                var leadId = $(this).data('lead-id');
                var documentId = $(this).data('document-id');
                
                if (!leadId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Lead ID is missing.',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
                
                if (!documentId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Document ID is missing.',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
                
                // Show confirmation
                Swal.fire({
                    title: 'Send Email?',
                    text: 'Do you want to send this template document via email to the lead?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Send',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-primary mr-3',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url = "{{ route('lead-details.send-template-document', [':leadId', ':documentId']) }}";
                        url = url.replace(':leadId', leadId).replace(':documentId', documentId);
                        
                        $.easyAjax({
                            url: url,
                            type: "POST",
                            blockUI: true,
                            data: {
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.status == "success") {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: response.message || 'Email sent successfully!',
                                        confirmButtonText: 'OK'
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message || 'Failed to send email.',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function(xhr) {
                                var errorMessage = 'Failed to send email. Please try again.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: errorMessage,
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });
        });
        
        // ========== DOCUMENT UPLOAD FUNCTIONALITY ==========
        
        // Handle document upload/change button clicks
        $(document).on('click', '.document-upload-btn, .document-change-link', function(e) {
            e.preventDefault();
            
            var documentKey = $(this).data('document-key');
            var documentName = $(this).data('document-name');
            
            if (!documentKey) {
                return;
            }
            
            $('#document_key_input').val(documentKey);
            $('#document_name_label').text(documentName || 'Document');
            $('#documentUploadModalLabel').text($(this).hasClass('document-change-link') ? 'Change Document' : 'Upload Document');
            $('#document_file_input').val('');
            $('#documentUploadModal').modal('show');
        });
        
        // Handle document form submission
        $(document).on('click', '#save-document-btn', function(e) {
            e.preventDefault();
            
            var documentKey = $('#document_key_input').val();
            var fileInput = $('#document_file_input')[0];
            
            if (!documentKey) {
                try {
                    if (typeof $.showToastr === 'function') {
                        $.showToastr('Document key is missing.', 'error');
                    } else if (typeof toastr !== 'undefined') {
                        toastr.error('Document key is missing.');
                    }
                } catch (e) {}
                return;
            }
            
            if (!fileInput.files || !fileInput.files[0]) {
                try {
                    if (typeof $.showToastr === 'function') {
                        $.showToastr('Please select a file to upload.', 'error');
                    } else if (typeof toastr !== 'undefined') {
                        toastr.error('Please select a file to upload.');
                    }
                } catch (e) {}
                return;
            }
            
            var leadId = {{ isset($lead) && $lead ? $lead->id : 'null' }};
            if (!leadId) {
                try {
                    if (typeof $.showToastr === 'function') {
                        $.showToastr('Lead ID is missing.', 'error');
                    } else if (typeof toastr !== 'undefined') {
                        toastr.error('Lead ID is missing.');
                    }
                } catch (e) {}
                return;
            }
            
            var formData = new FormData();
            formData.append('document_key', documentKey);
            formData.append('document_file', fileInput.files[0]);
            formData.append('_token', '{{ csrf_token() }}');
            
            $.easyAjax({
                url: "{{ route('new-leads.upload-document', ':leadId') }}".replace(':leadId', leadId),
                container: '#documentUploadForm',
                type: "POST",
                blockUI: true,
                file: true,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == "success") {
                        $('#documentUploadModal').modal('hide');
                        $('#documentUploadForm')[0].reset();
                        
                        try {
                            if (typeof $.showToastr === 'function') {
                                $.showToastr(response.message || 'Document uploaded successfully', 'success');
                            } else if (typeof toastr !== 'undefined') {
                                toastr.success(response.message || 'Document uploaded successfully');
                            }
                        } catch (e) {}
                        
                        // Refresh only the documents tab content
                        var leadId = {{ $lead->id ?? 0 }};
                        if (leadId) {
                            var documentsUrl = "{{ route('new-leads.documents-tab', ':id') }}".replace(':id', leadId);
                            
                            $.easyAjax({
                                url: documentsUrl,
                                type: "GET",
                                blockUI: false,
                                success: function(response) {
                                    // Handle different possible response structures
                                    var html = null;
                                    if (response.status == "success" && response.data && response.data.html) {
                                        html = response.data.html;
                                    } else if (response.html) {
                                        html = response.html;
                                    } else if (response.data && response.data.html) {
                                        html = response.data.html;
                                    }
                                    
                                    if (html) {
                                        $('#documentsContent').html(html);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    // Silent fail - documents will refresh on next page load
                                }
                            });
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = 'An error occurred while uploading the document. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    try {
                        if (typeof $.showToastr === 'function') {
                            $.showToastr(errorMessage, 'error');
                        } else if (typeof toastr !== 'undefined') {
                            toastr.error(errorMessage);
                        }
                    } catch (e) {}
                }
            });
        });
    </script>
@endpush


