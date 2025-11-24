@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/lead-details.css') }}">
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
                        <a class="nav-item-lead nav-link-lead f-14" data-tab="communicationTab" href="#">
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
                <!-- Communication Tab Content -->
                @include('lead-details.components.communication-tab')
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
                    // Update URL hash
                    window.location.hash = tabId;
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
                
                console.log('Save file note button clicked');
                
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
                    $.showToastr('Lead ID is missing.', 'error');
                    return false;
                }
                
                console.log('Saving file note for lead:', leadId);
                console.log('Form data:', $('#fileNoteFormDetails').serialize());
                
                $.easyAjax({
                    url: "{{ route('new-leads.file-note-store') }}",
                    container: '#fileNoteFormDetails',
                    type: "POST",
                    blockUI: true,
                    data: $('#fileNoteFormDetails').serialize(),
                    success: function(response) {
                        console.log('File note save response:', response);
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
                            
                            $.showToastr(response.message || 'File note saved successfully', 'success');
                            
                            // Reload the page after a short delay to ensure modal is closed and toastr is shown
                            // Add hash to URL to ensure file notes tab is active after reload
                            setTimeout(function() {
                                window.location.hash = 'fileNotesTab';
                                window.location.reload();
                            }, 500);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error saving file note:', error);
                        console.error('Status:', status);
                        console.error('Response:', xhr.responseText);
                        $.showToastr('An error occurred while saving the file note. Please try again.', 'error');
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
                    $.showToastr('Lead ID is missing.', 'error');
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
                            $.showToastr('Failed to load follow-up data. Please try again.', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        $.showToastr('An error occurred while loading follow-up data. Please try again.', 'error');
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
                            $.showToastr(response.message || 'Follow-up saved successfully', 'success');
                            
                            // Reload the page after a short delay to ensure modal is closed and toastr is shown
                            setTimeout(function() {
                                window.location.reload();
                            }, 500);
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
        });
    </script>
@endpush


