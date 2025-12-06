@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatable_css')
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/lead-details.css') }}">
    <style>
        /* Hide disabled subclass options completely */
        .bootstrap-select .dropdown-menu li.disabled {
            display: none !important;
        }
    </style>
@endpush

@push('head-scripts')
    <script src="{{ asset('vendor/jquery/html2pdf.bundle.min.js') }}"></script>
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
                            <img src="{{ asset('img/icon/User.svg') }}">
                        </div>
                        <div class="lead-info-group">
                            <div class="lead-priority d-flex align-items-center mb-1">
                                @if(isset($lead) && $lead)
                                    @php
                                        $priority = $lead->priority ?? 'Select Priority';
                                    @endphp
                                    @if($priority == 'Select Priority')
                                        <span class="f-12">No Priority Set</span>
                                    @else
                                        @php
                                            // Map priority to icon filename
                                            $priorityIconMap = [
                                                '1st Priority' => '1st_Priority.svg',
                                                '2nd Priority' => '2nd_Priority.svg',
                                                '3rd Priority' => '3rd_Priority.svg',
                                                '4th Priority' => '4th_Priority.svg',
                                                '5th Priority' => '5th_Priority.svg',
                                            ];
                                            $iconFile = $priorityIconMap[$priority] ?? '1st_Priority.svg';
                                        @endphp
                                        <img src="{{ asset('img/icon/' . $iconFile) }}">
                                        <span class="f-12 pl-1">{{ $priority }}</span>
                                    @endif
                                @else
                                    <span class="f-12">No Priority Set</span>
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
                            $step1Data = is_string($lead->step_1_data) ? json_decode($lead->step_1_data, true) : ($lead->step_1_data ?? []);
                            $primaryPhone = $step1Data['primary_phone'] ?? $lead->mobile ?? '';
                            $email = $step1Data['email_address'] ?? $lead->client_email ?? '';
                            $resumeFile = $step1Data['upload_resume'] ?? null;
                            $resumeUrl = null;
                            if ($resumeFile) {
                                try {
                                    $filePath = 'lead-resume-files/' . $resumeFile;
                                    $resumeUrl = asset_url_local_s3($filePath);
                                } catch (\Exception $e) {
                                    $resumeUrl = null;
                                }
                            }
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
                @if(isset($lead) && $lead && !empty($resumeFile))
                    @if($resumeUrl)
                        <a href="{{ $resumeUrl }}" target="_blank" class="btn btn-success btn-sm">View Resume</a>
                    @else
                        <a href="{{ route('lead-details.download-document', ['leadId' => $lead->id, 'documentKey' => 'upload_resume']) }}" target="_blank" class="btn btn-success btn-sm">View Resume</a>
                    @endif
                @else
                    <button class="btn btn-success btn-sm" disabled>View Resume</button>
                @endif

                <!-- Service Name and Action Icons -->
                <div class="lead-header-right-group d-flex align-items-center">
                    <div class="lead-service-actions-group d-flex align-items-center">
                        <div class="lead-service-section mr-3">
                            <div class="lead-service-name f-14 font-weight-bold">
                                @if(isset($lead) && $lead && $lead->step_2_data)
                                    @php
                                        $step2Data = $lead->step_2_data ?? [];
                                        $serviceName = '--';
                                        $subclassId = null;
                                        
                                        if (isset($step2Data['pr_subclass']) && !empty($step2Data['pr_subclass'])) {
                                            $subclassId = $step2Data['pr_subclass'];
                                        } elseif (isset($step2Data['visit_subclass']) && !empty($step2Data['visit_subclass'])) {
                                            $subclassId = $step2Data['visit_subclass'];
                                        } elseif (isset($step2Data['work_subclass']) && !empty($step2Data['work_subclass'])) {
                                            $subclassId = $step2Data['work_subclass'];
                                        } elseif (isset($step2Data['student_subclass']) && !empty($step2Data['student_subclass'])) {
                                            $subclassId = $step2Data['student_subclass'];
                                        }
                                        
                                        // If subclassId is numeric, get name from database, otherwise use as-is (backward compatibility)
                                        if ($subclassId && is_numeric($subclassId)) {
                                            $subclassModel = \App\Models\NewLeadSubclass::find($subclassId);
                                            $serviceName = $subclassModel ? $subclassModel->name : '--';
                                        } elseif ($subclassId) {
                                            // Backward compatibility: if it's a string (old format), use it directly
                                            $serviceName = $subclassId;
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
                        @php
                            $userRoles = user_roles();
                            $isAdmin = in_array('admin', $userRoles);
                        @endphp
                        @if($isAdmin)
                        <a class="nav-item-lead nav-link-lead f-14" data-tab="accountsTab" href="#">
                            <div class="tab-item"><img src="{{ asset('img/icon/Accounts.svg') }}"></div>Accounts
                        </a>
                        @endif
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
                @php
                    $userRoles = user_roles();
                    $isAdmin = in_array('admin', $userRoles);
                @endphp
                @if($isAdmin)
                @include('lead-details.components.accounts-tab')
                @endif
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
                // Explicitly clear date and time fields
                $('#next_follow_up_date_details').val('');
                $('#next_follow_up_time_details').val('');
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
                
                // Clear date and time fields if not in edit mode
                if (!isEditMode) {
                    $('#next_follow_up_date_details').val('');
                    $('#next_follow_up_time_details').val('');
                }
                
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
                // Explicitly clear date and time fields
                $('#next_follow_up_date_details').val('');
                $('#next_follow_up_time_details').val('');
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
                var selectedVisaTypeId = $('#visa_category').selectpicker('val');
                var $subclassSelect = $('#subclass');
                
                // Get current value - try multiple methods to ensure we get the saved value
                var currentValue = $subclassSelect.val() || $subclassSelect.selectpicker('val') || $subclassSelect.find('option:selected').val();
                
                // If no value from selectpicker, check the HTML selected attribute
                if (!currentValue) {
                    var selectedOption = $subclassSelect.find('option[selected]');
                    if (selectedOption.length > 0) {
                        currentValue = selectedOption.val();
                    }
                }
                
                // Show/hide options based on visa type ID
                $subclassSelect.find('option').each(function() {
                    var $option = $(this);
                    var optionVisaTypeId = $option.data('visa-type-id');
                    
                    // Always show the "Select" option
                    if ($option.val() === '') {
                        $option.prop('disabled', false);
                        return;
                    }
                    
                    // Enable/disable options based on visa type ID
                    if (selectedVisaTypeId && optionVisaTypeId == selectedVisaTypeId) {
                        $option.prop('disabled', false);
                    } else if (!selectedVisaTypeId) {
                        // If no category selected, show all options
                        $option.prop('disabled', false);
                    } else {
                        $option.prop('disabled', true);
                    }
                });
                
                // Refresh selectpicker first
                $subclassSelect.selectpicker('refresh');
                
                // After refresh, check if we need to set/clear the value
                if (selectedVisaTypeId && currentValue) {
                    var currentOption = $subclassSelect.find('option[value="' + currentValue + '"]');
                    var currentOptionVisaTypeId = currentOption.data('visa-type-id');
                    
                    // If current value doesn't match selected visa type, clear it
                    if (currentOptionVisaTypeId != selectedVisaTypeId) {
                        $subclassSelect.val('').selectpicker('refresh');
                    } else {
                        // Ensure the value is set correctly after filtering
                        $subclassSelect.val(currentValue);
                        $subclassSelect.selectpicker('refresh');
                    }
                } else if (currentValue && !selectedVisaTypeId) {
                    // If there's a value but no visa category selected, preserve it
                    $subclassSelect.val(currentValue);
                    $subclassSelect.selectpicker('refresh');
                } else if (currentValue) {
                    // If there's a value, try to preserve it
                    $subclassSelect.val(currentValue);
                    $subclassSelect.selectpicker('refresh');
                }
            }
            
            // Initialize subclass filtering when form section is shown or on page load
            function initializeSubclassFiltering() {
                if ($('#processFormSection').is(':visible')) {
                    // Store current value before filtering
                    var $subclassSelect = $('#subclass');
                    var savedValue = $subclassSelect.val() || $subclassSelect.find('option[selected]').val();
                    
                    filterSubclassOptions();
                    
                    // Restore value after filtering if it exists
                    if (savedValue) {
                        setTimeout(function() {
                            var option = $subclassSelect.find('option[value="' + savedValue + '"]');
                            if (option.length > 0 && !option.prop('disabled')) {
                                $subclassSelect.val(savedValue);
                                $subclassSelect.selectpicker('refresh');
                            }
                        }, 150);
                    }
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
                // Wait for selectpickers to be initialized first
                setTimeout(function() {
                    // Store the current subclass value before filtering
                    var $subclassSelect = $('#subclass');
                    var savedValue = $subclassSelect.val() || $subclassSelect.find('option[selected]').val();
                    
                    // Filter options
                    filterSubclassOptions();
                    
                    // After filtering, restore the saved value if it's still valid
                    if (savedValue) {
                        setTimeout(function() {
                            var selectedVisaTypeId = $('#visa_category').selectpicker('val');
                            var option = $subclassSelect.find('option[value="' + savedValue + '"]');
                            
                            // Only set if the option exists and is not disabled, and matches visa type
                            if (option.length > 0 && !option.prop('disabled')) {
                                if (!selectedVisaTypeId || option.data('visa-type-id') == selectedVisaTypeId) {
                                    $subclassSelect.val(savedValue);
                                    $subclassSelect.selectpicker('refresh');
                                }
                            }
                        }, 150);
                    }
                }, 200);
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
                
                // Store subclass value before initializing selectpickers
                var $subclassSelect = $('#subclass');
                var savedSubclassValue = $subclassSelect.find('option[selected]').val() || $subclassSelect.val();
                
                // Initialize select pickers
                $('.select-picker').selectpicker('refresh');
                
                // Restore subclass value after selectpicker initialization
                if (savedSubclassValue) {
                    setTimeout(function() {
                        $subclassSelect.val(savedSubclassValue);
                        $subclassSelect.selectpicker('refresh');
                    }, 100);
                }
                
                // Remove any previous errors
                removeProcessFieldErrors();
                
                // Initialize additional documents when form is shown (with delay to ensure DOM is ready)
                setTimeout(function() {
                    window.initializeAdditionalDocuments();
                }, 300);
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
                    { id: '#submission_fees', name: 'Submission Fees' }
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
                
                // File uploads are optional - no validation needed
                
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
                
                // Collect additional documents data
                var additionalDocuments = [];
                $('#additional-documents-container .document-row').each(function() {
                    var $row = $(this);
                    var docIndex = $row.data('document-index');
                    var docName = $('#additional_document_name_' + docIndex).val();
                    var docFileInput = $('#additional_document_file_' + docIndex)[0];
                    var hasNewFile = docFileInput && docFileInput.files && docFileInput.files.length > 0;
                    var existingFile = $row.find('.existing-file-link').attr('data-file');
                    
                    // Only add if document name is provided
                    if (docName && docName.trim() !== '') {
                        // Validate that either new file or existing file is present
                        if (!hasNewFile && !existingFile) {
                            isValid = false;
                            showProcessFieldError('#additional_document_file_' + docIndex, 'Document file is required');
                            return false;
                        }
                        
                        var docData = {
                            document_name: docName,
                            document_file: hasNewFile ? 'NEW_FILE_' + docIndex : (existingFile || '')
                        };
                        additionalDocuments.push(docData);
                    }
                });
                
                // Add additional documents as JSON to form (remove any existing one first)
                $('#processForm input[name="additional_documents_json"]').remove();
                if (additionalDocuments.length > 0) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'additional_documents_json',
                        value: JSON.stringify(additionalDocuments)
                    }).appendTo('#processForm');
                }
                
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
                            
                            // Refresh the page to show updated data
                            window.location.hash = 'processTab';
                            window.location.reload();
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

            // ========== ADDITIONAL DOCUMENTS DYNAMIC FORM ==========
            let documentCounter = 0;

            // Function to get next document number
            function getNextDocumentNumber() {
                documentCounter++;
                return documentCounter;
            }

            // Function to generate document row HTML
            function generateDocumentRow(docNum, docData = null) {
                const docName = docData && docData.document_name ? docData.document_name : '';
                const docFile = docData && docData.document_file ? docData.document_file : '';
                const fileName = docFile ? docFile.split('/').pop() : '';
                const existingFileHtml = docFile ? `<div class="mt-1"><small class="text-muted file-name-display">Current: <a href="#" class="existing-file-link" data-file="${docFile}" target="_blank">${fileName}</a></small></div>` : '';
                
                return `
                    <div class="document-row mb-3 p-3 border rounded" id="document-row-${docNum}" data-document-index="${docNum}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 f-14 font-weight-bold">Document ${docNum}</h6>
                            <button type="button" class="btn btn-danger btn-sm remove-document" data-row-id="${docNum}">
                                <i class="fa fa-trash mr-1"></i> Remove
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <x-forms.label fieldId="additional_document_name_${docNum}" fieldLabel="Document Name *">
                                </x-forms.label>
                                <input type="text" class="form-control height-35 f-14" name="additional_document_name_${docNum}" id="additional_document_name_${docNum}" value="${docName}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-forms.label fieldId="additional_document_file_${docNum}" fieldLabel="Upload Document ${docFile ? '' : '*'}" fieldRequired="${!docFile}">
                                </x-forms.label>
                                <input type="file" class="form-control height-35 f-14" name="additional_document_file_${docNum}" id="additional_document_file_${docNum}" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" data-max-size="5242880" data-document-index="${docNum}" ${!docFile ? 'required' : ''}>
                                ${existingFileHtml}
                            </div>
                        </div>
                    </div>
                `;
            }

            // Function to add a document row
            function addDocumentRow(docData = null) {
                const docNum = getNextDocumentNumber();
                const newRow = generateDocumentRow(docNum, docData);
                
                // Append to the container
                $('#additional-documents-container').append(newRow);
                
                // Update file URL for existing file if docData is provided
                if (docData && docData.document_file) {
                    const $docRow = $('#document-row-' + docNum);
                    const fileUrl = getDocumentFileUrl(docData.document_file);
                    const $fileLink = $docRow.find('.existing-file-link[data-file="' + docData.document_file + '"]');
                    if ($fileLink.length > 0 && fileUrl) {
                        $fileLink.attr('href', fileUrl).attr('target', '_blank');
                    }
                }
            }

            // Function to get document file URL (will be set when document row is added with existing data)
            function getDocumentFileUrl(fileName) {
                if (!fileName) return null;
                // The URL will be set from PHP when loading existing documents
                return null; // Will be updated by PHP when rendering
            }

            // Add More Document button click handler
            $(document).on('click', '#add-more-document', function() {
                addDocumentRow();
            });

            // Function to update document row numbers sequentially
            function updateDocumentRowNumbers() {
                const $rows = $('#additional-documents-container .document-row');
                $rows.each(function(index) {
                    const newNum = index + 1;
                    const $row = $(this);
                    const oldNum = $row.data('document-index');
                    
                    // Skip if number is already correct
                    if (oldNum == newNum) {
                        return;
                    }
                    
                    // Update row ID
                    $row.attr('id', 'document-row-' + newNum);
                    $row.attr('data-document-index', newNum);
                    
                    // Update document title
                    $row.find('h6').text('Document ' + newNum);
                    
                    // Update remove button data attribute
                    $row.find('.remove-document').attr('data-row-id', newNum);
                    
                    // Update all field IDs and names
                    $row.find('[id*="additional_document_name_' + oldNum + '"]').each(function() {
                        const $field = $(this);
                        const oldId = $field.attr('id');
                        const newId = oldId.replace('_' + oldNum, '_' + newNum);
                        $field.attr('id', newId);
                        if ($field.attr('name')) {
                            $field.attr('name', $field.attr('name').replace('_' + oldNum, '_' + newNum));
                        }
                    });
                    
                    $row.find('[id*="additional_document_file_' + oldNum + '"]').each(function() {
                        const $field = $(this);
                        const oldId = $field.attr('id');
                        const newId = oldId.replace('_' + oldNum, '_' + newNum);
                        $field.attr('id', newId);
                        if ($field.attr('name')) {
                            $field.attr('name', $field.attr('name').replace('_' + oldNum, '_' + newNum));
                        }
                        if ($field.attr('data-document-index')) {
                            $field.attr('data-document-index', newNum);
                        }
                    });
                    
                    // Update label for attributes
                    $row.find('label[for*="additional_document_name_' + oldNum + '"]').each(function() {
                        const $label = $(this);
                        const oldFor = $label.attr('for');
                        const newFor = oldFor.replace('_' + oldNum, '_' + newNum);
                        $label.attr('for', newFor);
                    });
                    
                    $row.find('label[for*="additional_document_file_' + oldNum + '"]').each(function() {
                        const $label = $(this);
                        const oldFor = $label.attr('for');
                        const newFor = oldFor.replace('_' + oldNum, '_' + newNum);
                        $label.attr('for', newFor);
                    });
                });
                
                // Update documentCounter to match the highest number
                documentCounter = $rows.length;
            }

            // Remove document row
            $(document).on('click', '.remove-document', function() {
                const rowId = $(this).data('row-id');
                $('#document-row-' + rowId).remove();
                // Update row numbers after removal
                updateDocumentRowNumbers();
            });

            // Store additional documents data in JavaScript variable
            var additionalDocumentsData = [];
            
            // Get processData from lead (same way as in process-tab.blade.php)
            @php
                $processDataForJS = null;
                if (isset($lead) && $lead && $lead->process) {
                    $processDataForJS = $lead->process;
                }
            @endphp
            
            @if($processDataForJS && $processDataForJS->additional_documents)
                @php
                    // Get additional_documents - handle both JSON string and array (from cast)
                    $additionalDocsRaw = $processDataForJS->additional_documents;
                    if (is_string($additionalDocsRaw)) {
                        $additionalDocs = json_decode($additionalDocsRaw, true);
                    } else {
                        $additionalDocs = $additionalDocsRaw;
                    }
                    $additionalDocs = is_array($additionalDocs) ? $additionalDocs : [];
                    
                    // Helper function to get document URL (same as in process-tab)
                    $getDocumentUrl = function($fileName) use ($lead) {
                        if (empty($fileName)) return null;
                        try {
                            if (strpos($fileName, 'public/') === 0) {
                                $filePath = $fileName;
                            } else {
                                $filePath = 'public/' . $fileName;
                            }
                            return asset_url_local_s3($filePath);
                        } catch (\Exception $e) {
                            return null;
                        }
                    };
                @endphp
                @if(count($additionalDocs) > 0)
                    additionalDocumentsData = [
                        @foreach($additionalDocs as $index => $doc)
                            @php
                                $docName = $doc['document_name'] ?? '';
                                $docFile = $doc['document_file'] ?? '';
                                $docUrl = $getDocumentUrl($docFile);
                            @endphp
                            {
                                document_name: {!! json_encode($docName) !!},
                                document_file: {!! json_encode($docFile) !!},
                                document_url: {!! json_encode($docUrl) !!}
                            }@if(!$loop->last),@endif
                        @endforeach
                    ];
                @endif
            @endif

            // Function to initialize additional documents (make it globally accessible)
            window.initializeAdditionalDocuments = function() {
                // Wait for form section to be visible
                var $container = $('#additional-documents-container');
                var $formSection = $('#processFormSection');
                
                // Check if container exists and form section is visible
                if ($container.length === 0 || !$formSection.is(':visible')) {
                    // Retry after a short delay (max 5 retries)
                    if (!window.initRetryCount) window.initRetryCount = 0;
                    if (window.initRetryCount < 5) {
                        window.initRetryCount++;
                        setTimeout(function() {
                            window.initializeAdditionalDocuments();
                        }, 200);
                    } else {
                        window.initRetryCount = 0;
                    }
                    return;
                }
                
                window.initRetryCount = 0; // Reset counter on success
                
                // Clear existing documents first to avoid duplicates
                $container.empty();
                documentCounter = 0;
                
                // Check if helper functions are available
                if (typeof addDocumentRow !== 'function') {
                    return;
                }
                if (typeof getNextDocumentNumber !== 'function') {
                    return;
                }
                
                // Load documents from stored data
                if (additionalDocumentsData && Array.isArray(additionalDocumentsData) && additionalDocumentsData.length > 0) {
                    additionalDocumentsData.forEach(function(doc, index) {
                        if (doc && doc.document_name) {
                            try {
                                addDocumentRow({
                                    document_name: doc.document_name || '',
                                    document_file: doc.document_file || ''
                                });
                                
                                // Update file link URL after row is added
                                if (doc.document_url) {
                                    setTimeout(function() {
                                        var $fileLink = $('#additional-documents-container').find('.existing-file-link[data-file="' + doc.document_file + '"]').last();
                                        if ($fileLink.length > 0) {
                                            $fileLink.attr('href', doc.document_url);
                                        }
                                    }, 300);
                                }
                            } catch (e) {
                                // Silently handle errors
                            }
                        }
                    });
                }
            };

            // Initialize additional documents on page load if form is visible
            $(document).ready(function() {
                if ($('#processFormSection').is(':visible')) {
                    window.initializeAdditionalDocuments();
                }
            });

            // File size validation for additional documents
            $(document).on('change', '#additional-documents-container input[type="file"][data-max-size]', function() {
                const file = this.files[0];
                const maxSize = $(this).data('max-size'); // 5242880 = 5MB
                const fieldId = $(this).attr('id');
                
                // Remove previous error
                $(this).removeClass('is-invalid');
                $(this).closest('.document-row').find('.invalid-feedback').remove();
                
                if (file && file.size > maxSize) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Too Large',
                            text: 'File size must be less than 5MB.',
                            confirmButtonText: 'OK'
                        });
                    }
                    $(this).val('');
                    $(this).addClass('is-invalid');
                    return false;
                }
            });
            // ========== END ADDITIONAL DOCUMENTS ==========

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

            // Send Template Document via WhatsApp
            $(document).on('click', '.send-template-whatsapp', function(e) {
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
                    title: 'Send WhatsApp?',
                    text: 'Do you want to send this template document via WhatsApp to the lead?',
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
                        var url = "{{ route('lead-details.send-template-document-whatsapp', [':leadId', ':documentId']) }}";
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
                                        text: response.message || 'WhatsApp message sent successfully!',
                                        confirmButtonText: 'OK'
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message || 'Failed to send WhatsApp message.',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function(xhr) {
                                var errorMessage = 'Failed to send WhatsApp message. Please try again.';
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

        // ========== TRAVEL DETAILS TAB FUNCTIONALITY ==========
        
        // Helper function to show error message below a field
        function showTravelDetailsFieldError(fieldId, errorMessage) {
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
            } else {
                // For regular inputs, add is-invalid to the field
                $field.addClass('is-invalid');
                // Add error message below the field
                $field.after('<div class="invalid-feedback d-block">' + errorMessage + '</div>');
            }
        }
        
        // Function to remove all field errors
        function removeTravelDetailsFieldErrors() {
            $('#travelDetailsFormSection .invalid-feedback').remove();
            $('#travelDetailsFormSection .is-invalid').removeClass('is-invalid');
        }
        
        // Function to update the travel details display section with form data
        function updateTravelDetailsDisplay() {
            // Helper function to format date
            function formatDate(dateString) {
                if (!dateString) return '-';
                try {
                    var date = new Date(dateString);
                    var day = String(date.getDate()).padStart(2, '0');
                    var monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    var month = monthNames[date.getMonth()];
                    var year = date.getFullYear();
                    return day + '-' + month + '-' + year;
                } catch (e) {
                    return dateString;
                }
            }
            
            // Helper function to get value or default
            function getValue(value, defaultValue) {
                return value && value.trim() !== '' ? value : (defaultValue || '-');
            }
            
            // Get all value elements in order
            var $valueElements = $('#travelDetailsDetailsSection').find('.info-field-value-text');
            
            // Update Travel Details Section (indices 0-8)
            if ($valueElements.length > 0) $valueElements.eq(0).text(getValue($('#purpose_of_trip').val()));
            if ($valueElements.length > 1) $valueElements.eq(1).text(getValue($('#place_to_visit').val()));
            if ($valueElements.length > 2) $valueElements.eq(2).text(formatDate($('#date_of_arrival').val()));
            if ($valueElements.length > 3) $valueElements.eq(3).text(getValue($('#arrival_flight').val()));
            if ($valueElements.length > 4) $valueElements.eq(4).text(getValue($('#arrival_city').val()));
            if ($valueElements.length > 5) $valueElements.eq(5).text(formatDate($('#date_of_departure').val()));
            if ($valueElements.length > 6) $valueElements.eq(6).text(getValue($('#departure_flight').val()));
            if ($valueElements.length > 7) $valueElements.eq(7).text(getValue($('#departure_city').val()));
            if ($valueElements.length > 8) $valueElements.eq(8).text(getValue($('#phone_number_other_country').val()));
            
            // Update Address Section (indices 9-12)
            if ($valueElements.length > 9) $valueElements.eq(9).text(getValue($('#address_stay').val()));
            if ($valueElements.length > 10) $valueElements.eq(10).text(getValue($('#city').val()));
            if ($valueElements.length > 11) $valueElements.eq(11).text(getValue($('#state').val()));
            if ($valueElements.length > 12) $valueElements.eq(12).text(getValue($('#postal_code').val()));
            
            // Update Personal Information Section (indices 13-16)
            if ($valueElements.length > 13) $valueElements.eq(13).text(getValue($('#person_paying').val()));
            
            // Update relatives fields (handle select pickers)
            var motherInCountry = $('#mother_in_country').selectpicker('val') || $('#mother_in_country').val();
            var immediateRelatives = $('#immediate_relatives').selectpicker('val') || $('#immediate_relatives').val();
            var otherRelatives = $('#other_relatives').selectpicker('val') || $('#other_relatives').val();
            
            if ($valueElements.length > 14) {
                var motherValue = motherInCountry ? motherInCountry.charAt(0).toUpperCase() + motherInCountry.slice(1) : '-';
                $valueElements.eq(14).text(motherValue);
            }
            if ($valueElements.length > 15) {
                var immediateValue = immediateRelatives ? immediateRelatives.charAt(0).toUpperCase() + immediateRelatives.slice(1) : '-';
                $valueElements.eq(15).text(immediateValue);
            }
            if ($valueElements.length > 16) {
                var otherValue = otherRelatives ? otherRelatives.charAt(0).toUpperCase() + otherRelatives.slice(1) : '-';
                $valueElements.eq(16).text(otherValue);
            }
        }
        
        // Edit Travel Details Button Click
        $(document).on('click', '#editTravelDetailsBtn', function(e) {
            e.preventDefault();
            // Hide details section and show form section
            $('#travelDetailsDetailsSection').hide();
            $('#travelDetailsFormSection').show();
            // Hide edit button and notify button, show save/cancel buttons
            $('#editTravelDetailsBtn').hide();
            $('#notifyClientTravelDetailsBtn').hide();
            $('#travelDetailsFormActions').show();
            // Initialize select pickers
            $('.select-picker').selectpicker('refresh');
            // Remove any previous errors
            removeTravelDetailsFieldErrors();
        });

        // Cancel Travel Details Button Click
        $(document).on('click', '#cancelTravelDetailsBtn', function(e) {
            e.preventDefault();
            // Show details section and hide form section
            $('#travelDetailsDetailsSection').show();
            $('#travelDetailsFormSection').hide();
            // Show edit button and notify button, hide save/cancel buttons
            $('#editTravelDetailsBtn').show();
            $('#notifyClientTravelDetailsBtn').show();
            $('#travelDetailsFormActions').hide();
            // Remove any errors
            removeTravelDetailsFieldErrors();
        });

        // Restrict Phone Number (other country) input to numbers only and max 10 digits
        $(document).on('input', '#phone_number_other_country', function(e) {
            var value = $(this).val().replace(/[^0-9]/g, '');
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            $(this).val(value);
        });

        // Restrict Postal/Zip Code input to numbers only and max 6 digits
        $(document).on('input', '#postal_code', function(e) {
            var value = $(this).val().replace(/[^0-9]/g, '');
            if (value.length > 6) {
                value = value.substring(0, 6);
            }
            $(this).val(value);
        });

        // Save Travel Details Button Click (from header or form bottom)
        $(document).on('click', '#saveTravelDetailsBtn, #saveTravelDetailsFormBtn', function(e) {
            e.preventDefault();
            
            // Remove previous errors
            removeTravelDetailsFieldErrors();
            
            var form = $('#travelDetailsForm')[0];
            var isValid = true;
            
            // Basic HTML5 validation
            if (!form.checkValidity()) {
                form.reportValidity();
                isValid = false;
            }
            
            var leadId = $('#travel_details_lead_id').val();
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
                { id: '#purpose_of_trip', name: 'Purpose of Trip' },
                { id: '#place_to_visit', name: 'Place To Visit' },
                { id: '#date_of_arrival', name: 'Date of Arrival' },
                { id: '#arrival_flight', name: 'Arrival Flight' },
                { id: '#arrival_city', name: 'Arrival City' },
                { id: '#date_of_departure', name: 'Date of Departure From' },
                { id: '#departure_flight', name: 'Departure Flight' },
                { id: '#departure_city', name: 'Departure City' },
                { id: '#address_stay', name: 'Address Where You Will Stay' },
                { id: '#city', name: 'City' },
                { id: '#state', name: 'State' },
                { id: '#postal_code', name: 'Postal/Zip Code' },
                { id: '#person_paying', name: 'Person Paying For Your Trip (Details)' }
            ];
            
            requiredFields.forEach(function(field) {
                var $field = $(field.id);
                var value = $field.val();
                
                if (!value || value.trim() === '') {
                    isValid = false;
                    showTravelDetailsFieldError(field.id, field.name + ' is required');
                }
            });
            
            // Validate Phone Number (other country) - must be exactly 10 digits if provided
            var phoneNumber = $('#phone_number_other_country').val();
            if (phoneNumber && phoneNumber.trim() !== '') {
                var phoneRegex = /^[0-9]{10}$/;
                if (!phoneRegex.test(phoneNumber)) {
                    isValid = false;
                    showTravelDetailsFieldError('#phone_number_other_country', 'Phone Number must be exactly 10 digits (numbers only)');
                }
            }
            
            // Validate Postal/Zip Code - must be exactly 6 digits
            var postalCode = $('#postal_code').val();
            if (postalCode && postalCode.trim() !== '') {
                var postalRegex = /^[0-9]{6}$/;
                if (!postalRegex.test(postalCode)) {
                    isValid = false;
                    showTravelDetailsFieldError('#postal_code', 'Postal/Zip Code must be exactly 6 digits (numbers only)');
                }
            }
            
            if (!isValid) {
                return false;
            }
            
            // Get select picker values and add to form
            $('#mother_in_country, #immediate_relatives, #other_relatives').each(function() {
                var $select = $(this);
                var selectedValue = $select.selectpicker('val');
                if (selectedValue) {
                    $select.val(selectedValue);
                }
            });
            
            // Ensure form exists and is visible
            var $form = $('#travelDetailsForm');
            if ($form.length === 0) {
                try {
                    if (typeof $.showToastr === 'function') {
                        $.showToastr('Form not found. Please refresh the page.', 'error');
                    } else if (typeof toastr !== 'undefined') {
                        toastr.error('Form not found. Please refresh the page.');
                    }
                } catch (e) {}
                return false;
            }
            
            // Serialize form data
            var formData = $form.serialize();
            
            // Check if form data is empty
            if (!formData || formData.trim() === '') {
                try {
                    if (typeof $.showToastr === 'function') {
                        $.showToastr('No form data to submit. Please fill in the required fields.', 'error');
                    } else if (typeof toastr !== 'undefined') {
                        toastr.error('No form data to submit. Please fill in the required fields.');
                    }
                } catch (e) {}
                return false;
            }
            
            $.easyAjax({
                url: "{{ route('new-leads.travel-details-store') }}",
                container: '#travelDetailsForm',
                type: "POST",
                blockUI: true,
                disableButton: true,
                buttonSelector: "#saveTravelDetailsBtn, #saveTravelDetailsFormBtn",
                data: formData,
                success: function(response) {
                    if (response.status == "success") {
                        try {
                            if (typeof $.showToastr === 'function') {
                                $.showToastr(response.message || 'Travel details saved successfully', 'success');
                            } else if (typeof toastr !== 'undefined') {
                                toastr.success(response.message || 'Travel details saved successfully');
                            }
                        } catch (e) {}
                        
                        // Update the details section with the form data we just saved
                        updateTravelDetailsDisplay();
                        
                        // Show details section and hide form section
                        $('#travelDetailsDetailsSection').show();
                        $('#travelDetailsFormSection').hide();
                        $('#editTravelDetailsBtn').show();
                        $('#notifyClientTravelDetailsBtn').show();
                        $('#travelDetailsFormActions').hide();
                    }
                },
                error: function(xhr, status, error) {
                    // Handle validation errors from server
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            var fieldId = '#' + field;
                            showTravelDetailsFieldError(fieldId, messages[0]);
                        });
                    } else {
                        try {
                            if (typeof $.showToastr === 'function') {
                                $.showToastr('An error occurred while saving the travel details. Please try again.', 'error');
                            } else if (typeof toastr !== 'undefined') {
                                toastr.error('An error occurred while saving the travel details. Please try again.');
                            }
                        } catch (e) {}
                    }
                }
            });
            
            return false;
        });

        // ========== ACCOUNTS FUNCTIONALITY ==========
        
        // Handle Add Account button click
        $(document).on('click', '#addAccountBtn', function(e) {
            e.preventDefault();
            var leadId = $('#account_lead_id_details').val();
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
            $('#account_id_details').val('');
            $('#addAccountModalLabel').text('ADD INVOICE');
            $('#accountFormDetails')[0].reset();
            $('#account_lead_id_details').val(leadId);
            $('#installment_months_container').hide();
            $('#installment_payment_toggle').prop('checked', false);
            calculateAccountSummary();
            $('#addAccountModal').modal('show');
        });

        // Handle Edit Account button click
        $(document).on('click', '.edit-account-btn', function(e) {
            e.preventDefault();
            var accountId = $(this).data('account-id');
            
            $.easyAjax({
                url: "{{ route('new-leads.account-get', ':id') }}".replace(':id', accountId),
                type: "GET",
                blockUI: true,
                success: function(response) {
                    if (response.status == "success" && response.account) {
                        var account = response.account;
                        $('#account_id_details').val(account.id);
                        $('#addAccountModalLabel').text('EDIT INVOICE');
                        $('#account_lead_id_details').val(account.new_lead_id);
                        $('#invoice_date').val(account.invoice_date ? moment(account.invoice_date).format('{{ company()->moment_date_format }}') : '');
                        $('#bill_to').val(account.bill_to || '');
                        $('#agent').val(account.agent || '').selectpicker('refresh');
                        $('#service').val(account.service || '');
                        $('#price').val(account.price || 0);
                        $('#tax').val(account.tax || 'GST 18%').selectpicker('refresh');
                        $('#discount').val(account.discount || 0);
                        $('#service_description').val(account.service_description || '');
                        $('#installment_payment_toggle').prop('checked', account.installment_payment == 1 || account.installment_payment === true);
                        if (account.installment_payment) {
                            $('#installment_months_container').show();
                            $('#installment_months').val(account.installment_months || '').selectpicker('refresh');
                        } else {
                            $('#installment_months_container').hide();
                        }
                        $('#invoice_notes').val(account.invoice_notes || '');
                        calculateAccountSummary();
                        $('#addAccountModal').modal('show');
                    }
                }
            });
        });

        // Handle View Account button click
        $(document).on('click', '.view-account-btn', function(e) {
            e.preventDefault();
            var accountId = $(this).data('account-id');
            
            $.easyAjax({
                url: "{{ route('new-leads.account-get', ':id') }}".replace(':id', accountId),
                type: "GET",
                blockUI: true,
                success: function(response) {
                    if (response.status == "success" && response.account) {
                        var account = response.account;
                        var leadId = account.new_lead_id ? 'LEAD-' + String(account.new_lead_id).padStart(4, '0') : '--';
                        
                        // Get currency symbol
                        var currencySymbol = '₹';
                        try {
                            @php
                                try {
                                    $currencySymbol = company()->currency ? company()->currency->currency_symbol : '₹';
                                } catch (\Exception $e) {
                                    $currencySymbol = '₹';
                                }
                            @endphp
                            currencySymbol = '{{ $currencySymbol }}';
                        } catch (e) {
                            currencySymbol = '₹';
                        }
                        
                        // Format amounts
                        var formatAmount = function(amount) {
                            return currencySymbol + ' ' + (amount ? parseFloat(amount).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00');
                        };
                        
                        // Populate modal
                        $('#view_invoice_lead_number').text(leadId);
                        $('#view_client_name').text(account.client_name || '--');
                        $('#view_email').text(account.email || '--');
                        $('#view_phone').text(account.phone || '--');
                        $('#view_invoice_date').text(account.invoice_date ? moment(account.invoice_date).format('{{ company()->date_format }}') : '--');
                        $('#view_bill_to').text(account.bill_to || '--');
                        $('#view_agent_name').text((account.agent_user && account.agent_user.name) ? account.agent_user.name : ((account.agentUser && account.agentUser.name) ? account.agentUser.name : '--'));
                        $('#view_address').text(account.address || '--');
                        $('#view_service').text(account.service || '--');
                        $('#view_service_description').text(account.service_description || account.invoice_notes || '--');
                        $('#view_sub_total').text(formatAmount(account.sub_total || account.price || 0));
                        $('#view_discount').text(formatAmount(account.discount_amount || account.discount || 0));
                        $('#view_tax_amount').text(formatAmount(account.tax_amount || 0));
                        $('#view_total_amount').text(formatAmount(account.total_amount || account.net_amount || 0));
                        
                        // Installment note
                        if (account.installment_payment && account.installment_months) {
                            var installmentAmount = (account.total_amount || account.net_amount || 0) / account.installment_months;
                            $('#view_installment_note').show();
                            $('#view_installment_note_text').text('Installment ' + currencySymbol + ' ' + installmentAmount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' for ' + account.installment_months + ' months');
                        } else {
                            $('#view_installment_note').hide();
                        }
                        
                        // Store account ID for download
                        $('#viewInvoiceModal').data('account-id', accountId);
                        
                        $('#viewInvoiceModal').modal('show');
                    }
                }
            });
        });
        
        // Handle Download Invoice button click
        $(document).on('click', '.invoice-download-btn', function(e) {
            e.preventDefault();
            
            // Check if html2pdf is loaded
            if (typeof html2pdf === 'undefined') {
                alert('PDF library is loading. Please wait a moment and try again.');
                // html2pdf library is not loaded
                return;
            }
            
            // Get the invoice view body content
            var invoiceBody = document.querySelector('#viewInvoiceModal .invoice-view-body');
            
            if (!invoiceBody) {
                alert('Invoice content not found. Please try viewing the invoice again.');
                return;
            }
            
            // Get lead number for filename
            var leadNumber = $('#view_invoice_lead_number').text() || 'Invoice';
            var filename = leadNumber + '-' + moment().format('YYYY-MM-DD') + '.pdf';
            
            // Configure html2pdf options
            var opt = {
                margin: [10, 10, 10, 10],
                filename: filename,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { 
                    scale: 2,
                    useCORS: true,
                    logging: false
                },
                jsPDF: { 
                    unit: 'mm', 
                    format: 'a4', 
                    orientation: 'portrait' 
                }
            };
            
            // Show loading message
            var btn = $(this);
            var originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Generating PDF...');
            
            // Generate and download PDF
            html2pdf().set(opt).from(invoiceBody).save().then(function() {
                // Restore button
                btn.prop('disabled', false).html(originalText);
            }).catch(function(error) {
                // PDF generation error occurred
                alert('Failed to generate PDF. Please try again.');
                btn.prop('disabled', false).html(originalText);
            });
        });

        // Handle Delete Account button click
        $(document).on('click', '.delete-account-btn', function(e) {
            e.preventDefault();
            var accountId = $(this).data('account-id');
            
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
                    var url = "{{ route('new-leads.account-delete', ':id') }}".replace(':id', accountId);
                    var token = "{{ csrf_token() }}";
                    
                    $.easyAjax({
                        url: url,
                        type: "POST",
                        data: {
                            '_token': token,
                            '_method': 'DELETE'
                        },
                        blockUI: true,
                        success: function(response) {
                            if (response.status == "success") {
                                // Refresh accounts list via AJAX
                                var leadId = $('#account_lead_id_details').val() || "{{ isset($lead) && $lead ? $lead->id : '' }}";
                                if (leadId) {
                                    var accountsUrl = "{{ route('new-leads.accounts', ':id') }}".replace(':id', leadId);
                                    
                                    $.easyAjax({
                                        url: accountsUrl,
                                        type: "GET",
                                        blockUI: false,
                                        success: function(accountsResponse) {
                                            var html = null;
                                            if (accountsResponse.status == "success" && accountsResponse.data && accountsResponse.data.html) {
                                                html = accountsResponse.data.html;
                                            } else if (accountsResponse.html) {
                                                html = accountsResponse.html;
                                            } else if (accountsResponse.data && accountsResponse.data.html) {
                                                html = accountsResponse.data.html;
                                            }
                                            
                                            if (html) {
                                                $('#accountsContent').html(html);
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            // Silent fail
                                        }
                                    });
                                }
                            }
                        }
                    });
                }
            });
        });

        // Calculate account summary
        function calculateAccountSummary() {
            var price = parseFloat($('#price').val()) || 0;
            var discount = parseFloat($('#discount').val()) || 0;
            var tax = $('#tax').val() || 'GST 0%';
            
            // Extract tax percentage from tax field (e.g., "GST 18%" -> 18)
            var taxPercent = 0;
            var taxMatch = tax.match(/(\d+(?:\.\d+)?)/);
            if (taxMatch) {
                taxPercent = parseFloat(taxMatch[1]);
            }
            
            // Calculate: Tax Amount = Price * (Tax Percentage / 100)
            // Convert tax percentage to actual value based on price
            var taxAmount = (price * taxPercent) / 100;
            
            // Calculate: Sub Total = Price (for display purposes)
            var subTotal = price;
            
            // Calculate: Total Amount = Price + Tax Amount - Discount
            // This is the same formula for both Net Amount and Summary Total Amount
            var totalAmount = price + taxAmount - discount;
            totalAmount = Math.max(0, totalAmount); // Ensure non-negative
            
            // Get currency symbol safely
            var currencySymbol = '₹';
            try {
                @php
                    try {
                        $currencySymbol = company()->currency ? company()->currency->currency_symbol : '₹';
                    } catch (\Exception $e) {
                        $currencySymbol = '₹';
                    }
                @endphp
                currencySymbol = '{{ $currencySymbol }}';
            } catch (e) {
                currencySymbol = '₹';
            }
            
            // Update summary display
            $('#summary_sub_total').text(currencySymbol + ' ' + subTotal.toFixed(2));
            $('#summary_discount').text(currencySymbol + ' ' + discount.toFixed(2));
            $('#summary_tax_amount').text(currencySymbol + ' ' + taxAmount.toFixed(2));
            $('#summary_total_amount').text(currencySymbol + ' ' + totalAmount.toFixed(2));
            
            // Update Net Amount field: Same as Total Amount = Price + Tax - Discount
            $('#net_amount').val(totalAmount.toFixed(2));
            
            // Calculate installment if enabled
            if ($('#installment_payment_toggle').is(':checked')) {
                var months = parseInt($('#installment_months').val()) || 1;
                if (months > 0) {
                    var installmentAmount = totalAmount / months;
                    $('#installment_note').show();
                    $('#installment_note_text').text('Installment ' + currencySymbol + ' ' + installmentAmount.toFixed(2) + ' for ' + months + ' months');
                } else {
                    $('#installment_note').hide();
                }
            } else {
                $('#installment_note').hide();
            }
        }

        // Update summary on price, discount, tax change
        $(document).on('input change', '#price, #discount, #tax, #installment_months', function() {
            calculateAccountSummary();
        });

        // Toggle installment months container
        $(document).on('change', '#installment_payment_toggle', function() {
            if ($(this).is(':checked')) {
                $('#installment_months_container').slideDown();
            } else {
                $('#installment_months_container').slideUp();
            }
            calculateAccountSummary();
        });

        // Save account
        $('#save-account-btn').click(function() {
            // Enable disabled fields temporarily for form submission
            $('#client_name, #phone, #email, #address').prop('disabled', false);
            
            var formData = $('#accountFormDetails').serialize();
            var accountId = $('#account_id_details').val();
            var url = accountId ? "{{ route('new-leads.account-store') }}" : "{{ route('new-leads.account-store') }}";
            
            $.easyAjax({
                url: url,
                container: '#accountFormDetails',
                type: "POST",
                blockUI: true,
                data: formData,
                success: function(response) {
                    // Re-disable fields after submission
                    $('#client_name, #phone, #email, #address').prop('disabled', true);
                    
                    if (response.status == "success") {
                            $('#addAccountModal').modal('hide');
                            $('#accountFormDetails')[0].reset();
                            $('#account_id_details').val('');
                            $('#addAccountModalLabel').text('ADD INVOICE');
                            
                            // Refresh accounts list via AJAX
                            var leadId = $('#account_lead_id_details').val();
                            if (leadId) {
                                var accountsUrl = "{{ route('new-leads.accounts', ':id') }}".replace(':id', leadId);
                                
                                $.easyAjax({
                                    url: accountsUrl,
                                    type: "GET",
                                    blockUI: false,
                                    success: function(accountsResponse) {
                                        var html = null;
                                        if (accountsResponse.status == "success" && accountsResponse.data && accountsResponse.data.html) {
                                            html = accountsResponse.data.html;
                                        } else if (accountsResponse.html) {
                                            html = accountsResponse.html;
                                        } else if (accountsResponse.data && accountsResponse.data.html) {
                                            html = accountsResponse.data.html;
                                        }
                                        
                                        if (html) {
                                            $('#accountsContent').html(html);
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        // Silent fail - accounts will refresh on next page load
                                    }
                                });
                            }
                        }
                }
            });
        });

        // Reset form when account modal is closed
        $('#addAccountModal').on('hidden.bs.modal', function () {
            $('#accountFormDetails')[0].reset();
            $('#account_id_details').val('');
            $('#addAccountModalLabel').text('ADD INVOICE');
            $('#installment_months_container').hide();
            $('#installment_payment_toggle').prop('checked', false);
            $('.select-picker').selectpicker('refresh');
        });

        // Initialize date picker for invoice date
        $('#addAccountModal').on('shown.bs.modal', function() {
            $('.select-picker').selectpicker();
            if ($('#invoice_date').length && typeof datepicker !== 'undefined') {
                // Destroy existing datepicker if any
                const dateInput = document.getElementById('invoice_date');
                if (dateInput && dateInput._datepicker) {
                    dateInput._datepicker.destroy();
                }
                // Initialize datepicker
                const dp = datepicker('#invoice_date', {
                    position: 'bl',
                    ...datepickerConfig
                });
            }
        });
        
        // Destroy datepicker when modal is hidden
        $('#addAccountModal').on('hidden.bs.modal', function() {
            const dateInput = document.getElementById('invoice_date');
            if (dateInput && dateInput._datepicker) {
                dateInput._datepicker.destroy();
            }
        });
    </script>
@endpush


