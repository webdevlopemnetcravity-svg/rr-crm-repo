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
                                <img src="{{ asset('img/icon/1st_Priority.svg') }}">
                                <span class="f-12 pl-1">1st Priority</span>
                            </div>
                            <div class="lead-id-header f-14 font-weight-bold">LEAD-0008</div>
                        </div>
                    </div>
                </div>

                <!-- Middle-Left Section: Contact Info -->
                <div class="lead-contact-info d-flex align-items-center">
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
                </div>

                <!-- View Resume Button -->
                <button class="btn btn-success btn-sm">View Resume</button>

                <!-- Service Name and Action Icons -->
                <div class="lead-header-right-group d-flex align-items-center">
                    <div class="lead-service-actions-group d-flex align-items-center">
                        <div class="lead-service-section mr-3">
                            <div class="lead-service-name f-14 font-weight-bold">PR - Employer Nomination Scheme (ENS)(Subclass 186)</div>
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

            // Save File Note button handler
            $('#save-file-note-btn').on('click', function() {
                // Copy content from Quill editor to hidden textarea
                if (document.getElementById('file-note-editor') && document.getElementById('file-note-editor').children[0]) {
                    var note = document.getElementById('file-note-editor').children[0].innerHTML;
                    document.getElementById('file-note-editor-text').value = note;
                }
                
                // Here you can add your save logic
                // For example: submit form, make AJAX call, etc.
                console.log('Note content:', $('#file-note-editor-text').val());
                
                // Close modal after save (you can modify this based on your needs)
                // $('#addFileNoteModal').modal('hide');
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
        });
    </script>
@endpush


