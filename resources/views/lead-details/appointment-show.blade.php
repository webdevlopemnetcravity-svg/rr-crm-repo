@php
$appointment = $appointment ?? null;
@endphp

<style>
    #appointment-status2 {
        border-radius: 0 5px 5px 0;
    }

    #appointment-status {
        border-radius: 5px 0 0 5px;
    }
</style>

<div id="appointment-detail-section">
    <h3 class="heading-h1 mb-3">{{ $appointment->meeting_title ?? 'Appointment' }}</h3>
    <div class="row">
        <div class="col-sm-12">
            <div class="card bg-white border-0 b-shadow-4">
                <div class="card-header bg-white border-bottom-grey justify-content-between p-20">
                    <div class="row">
                        <div class="col-lg-12 col-12">
                            <x-forms.button-primary icon="edit" id="update-appointment-btn" class="mr-2 mb-2 mb-lg-0 mb-md-0">
                                Update Meeting
                            </x-forms.button-primary>

                            <x-forms.button-secondary icon="times" id="cancel-appointment-btn" class="mr-3">
                                Cancel Appointment
                            </x-forms.button-secondary>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <x-cards.data-row label="Meeting Title" :value="$appointment->meeting_title ?? 'N/A'"
                        html="true" />

                    @if($appointment->lead)
                        <x-cards.data-row label="Lead" :value="$appointment->lead->client_name ?? 'N/A'"
                            html="true" />
                    @endif

                    <x-cards.data-row label="Description" :value="$appointment->description ?? 'N/A'"
                        html="true" />

                    <x-cards.data-row label="Start On"
                        :value="$appointment->start_time->translatedFormat(company()->date_format . ' - ' . company()->time_format)"
                        html="true" />

                    <x-cards.data-row label="End On"
                        :value="$appointment->end_time->translatedFormat(company()->date_format . ' - ' . company()->time_format)"
                        html="true" />

                    @if($appointment->zoom_link)
                        @php
                            $url = str_starts_with($appointment->zoom_link, 'http') ? $appointment->zoom_link : 'http://' . $appointment->zoom_link;
                            $link = "<a href='" . $url . "' style='color:black; cursor: pointer;' target='_blank'>" . $appointment->zoom_link . "</a>";
                        @endphp
                        <x-cards.data-row label="Zoom Meeting Link"
                            html="true" :value="$link"/>
                        
                        @if($appointment->zoom_meeting_id)
                            <x-cards.data-row label="Zoom Meeting ID" :value="$appointment->zoom_meeting_id" />
                        @endif
                        
                        @if($appointment->zoom_meeting_password)
                            <x-cards.data-row label="Zoom Meeting Password" :value="$appointment->zoom_meeting_password" />
                        @endif
                    @elseif($appointment->google_meet_link)
                        @php
                            $url = str_starts_with($appointment->google_meet_link, 'http') ? $appointment->google_meet_link : 'http://' . $appointment->google_meet_link;
                            $link = "<a href='" . $url . "' style='color:black; cursor: pointer;' target='_blank'>" . $appointment->google_meet_link . "</a>";
                        @endphp
                        <x-cards.data-row label="Google Meet Link"
                            html="true" :value="$link"/>
                    @else
                        <x-cards.data-row label="Meeting Link" value="Not Set" html="true" />
                    @endif

                    @if($appointment->creator)
                        <x-cards.data-row label="Created By" :value="$appointment->creator->name ?? 'N/A'"
                            html="true" />
                    @endif

                    @if($appointment->created_at)
                        <x-cards.data-row label="Created At"
                            :value="$appointment->created_at->translatedFormat(company()->date_format . ' - ' . company()->time_format)"
                            html="true" />
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Appointment Modal -->
<div class="modal fade" id="updateAppointmentModal" tabindex="-1" role="dialog" aria-labelledby="updateAppointmentModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateAppointmentModalLabel">Update Meeting</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="updateAppointmentForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="update_meeting_title" class="font-weight-bold text-dark">Meeting Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control height-35 f-14" id="update_meeting_title" name="meeting_title" value="{{ $appointment->meeting_title ?? '' }}" placeholder="Enter meeting title" required>
                    </div>
                    <div class="form-group">
                        <label for="update_appointment_date" class="font-weight-bold text-dark">Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control height-35 f-14" id="update_appointment_date" name="appointment_date" value="{{ $appointment->appointment_date ? $appointment->appointment_date->format(company()->date_format) : '' }}" placeholder="Select Date" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="update_start_time" class="font-weight-bold text-dark">Start Time <span class="text-danger">*</span></label>
                        <div class="bootstrap-timepicker timepicker">
                            <input type="text" class="form-control height-35 f-14" id="update_start_time" name="start_time" value="{{ $appointment->start_time ? $appointment->start_time->format(company()->time_format) : '' }}" placeholder="Select Start Time" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="update_end_time" class="font-weight-bold text-dark">End Time <span class="text-danger">*</span></label>
                        <div class="bootstrap-timepicker timepicker">
                            <input type="text" class="form-control height-35 f-14" id="update_end_time" name="end_time" value="{{ $appointment->end_time ? $appointment->end_time->format(company()->time_format) : '' }}" placeholder="Select End Time" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="update_description" class="font-weight-bold text-dark">Description</label>
                        <textarea class="form-control f-14" id="update_description" name="description" rows="3" placeholder="Optional: Add appointment description">{{ $appointment->description ?? '' }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveUpdateAppointmentBtn">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var appointmentId = {{ $appointment->id }};

    // Custom close function for appointments (without history back)
    // This keeps user on events page instead of navigating back
    function closeAppointmentDetail() {
        // Add delay to ensure this executes before any history navigation
        setTimeout(function() {
            var ctd1 = document.getElementById("task-detail-1");
            if (ctd1) {
                ctd1.classList.remove("in");
            }

            var ctd2 = document.getElementById("close-task-detail-overlay");
            if (ctd2) {
                ctd2.classList.remove("in");
            }

            var ctd3 = document.getElementById("close-task-detail");
            if (ctd3) {
                ctd3.classList.remove("in");
            }
        }, 200);
        // Do NOT call window.history.back() - stay on current page (events page)
    }

    // Override the global closeTaskDetail function for appointments
    // Store original function before overriding
    var originalCloseTaskDetail = typeof window.closeTaskDetail !== 'undefined' ? window.closeTaskDetail : null;
    
    // Override it to not use history.back for appointments
    window.closeTaskDetail = function() {
        // Check if we're in appointment context (appointment detail is loaded)
        if (document.getElementById('appointment-detail-section')) {
            closeAppointmentDetail();
            return false; // Exit early, don't call original function
        } else {
            // Use original function for other contexts
            if (originalCloseTaskDetail) {
                return originalCloseTaskDetail();
            }
        }
    };

    // Prevent history.back() from being called for appointments
    // Override window.history.back temporarily
    var originalHistoryBack = window.history.back;
    var preventHistoryBack = false;
    
    window.history.back = function() {
        if (preventHistoryBack && document.getElementById('appointment-detail-section')) {
            // Don't navigate back, just close the modal
            closeAppointmentDetail();
            preventHistoryBack = false; // Reset after use
            return;
        }
        if (originalHistoryBack) {
            return originalHistoryBack.apply(window.history, arguments);
        }
    };

    // Override close button click handlers with delay
    // Handle backdrop immediately and also with delay
    (function() {
        // Hide the close button for appointments
        var closeBtn = document.getElementById('close-task-detail');
        if (closeBtn) {
            closeBtn.style.display = 'none';
        }
        
        // Set flag to prevent history.back when closing appointment (for backdrop/overlay)
        var overlay = document.getElementById('close-task-detail-overlay');
        if (overlay) {
            // Remove existing listeners by cloning
            var newOverlay = overlay.cloneNode(true);
            overlay.parentNode.replaceChild(newOverlay, overlay);
            
            // Add multiple event listeners to catch all scenarios
            newOverlay.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                if (document.getElementById('appointment-detail-section')) {
                    preventHistoryBack = true;
                    closeAppointmentDetail();
                }
                return false;
            }, true); // Use capture phase to catch early
            
            // Also add in bubble phase as backup
            newOverlay.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                if (document.getElementById('appointment-detail-section')) {
                    preventHistoryBack = true;
                    closeAppointmentDetail();
                }
                return false;
            }, false);
        }
    })();
    
    // Also set up with delay as additional backup
    setTimeout(function() {
        var overlay = document.getElementById('close-task-detail-overlay');
        if (overlay && document.getElementById('appointment-detail-section')) {
            overlay.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                preventHistoryBack = true;
                closeAppointmentDetail();
                return false;
            }, true);
        }
    }, 100);

    // Also use jQuery as backup with delay (for both close button and backdrop)
    setTimeout(function() {
        // Handle backdrop/overlay click
        $('#close-task-detail-overlay').off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            // Check if this is appointment detail
            if (document.getElementById('appointment-detail-section')) {
                preventHistoryBack = true;
                closeAppointmentDetail();
                return false;
            } else {
                // For non-appointments, use original behavior
                if (originalCloseTaskDetail) {
                    return originalCloseTaskDetail();
                }
            }
            return false;
        });
        
        // Handle close button click
        $('#close-task-detail').off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            // Check if this is appointment detail
            if (document.getElementById('appointment-detail-section')) {
                preventHistoryBack = true;
                closeAppointmentDetail();
                return false;
            } else {
                // For non-appointments, use original behavior
                if (originalCloseTaskDetail) {
                    return originalCloseTaskDetail();
                }
            }
            return false;
        });
    }, 150);

    // Update Appointment
    $('#update-appointment-btn').click(function() {
        // Remove any existing backdrop before opening modal
        $('.modal-backdrop').not('#close-task-detail-overlay').remove();
        
        // Open modal without backdrop to prevent stacking
        $('#updateAppointmentModal').modal({
            backdrop: false,
            show: true
        });
        
        // Initialize datepicker and timepicker
        setTimeout(function() {
            if (typeof datepicker !== 'undefined') {
                const updateDate = document.getElementById('update_appointment_date');
                if (updateDate && updateDate._datepicker) {
                    updateDate._datepicker.destroy();
                }
                datepicker('#update_appointment_date', {
                    position: 'bl',
                    minDate: new Date(),
                    ...datepickerConfig
                });
            }

            if ($('#update_start_time').data('timepicker')) {
                $('#update_start_time').timepicker('remove');
            }
            $('#update_start_time').timepicker({
                @if (company()->time_format == 'H:i')
                showMeridian: false,
                @endif
            });

            if ($('#update_end_time').data('timepicker')) {
                $('#update_end_time').timepicker('remove');
            }
            $('#update_end_time').timepicker({
                @if (company()->time_format == 'H:i')
                showMeridian: false,
                @endif
            });
        }, 100);
    });

    $('#updateAppointmentModal').on('hidden.bs.modal', function () {
        const updateDate = document.getElementById('update_appointment_date');
        if (updateDate && updateDate._datepicker) {
            updateDate._datepicker.destroy();
        }
        if ($('#update_start_time').data('timepicker')) {
            $('#update_start_time').timepicker('remove');
        }
        if ($('#update_end_time').data('timepicker')) {
            $('#update_end_time').timepicker('remove');
        }
        // Clean up any backdrop that might have been created
        $('.modal-backdrop').not('#close-task-detail-overlay').remove();
    });

    $(document).on('submit', '#updateAppointmentForm', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var url = "{{ route('appointments.update', ':id') }}".replace(':id', appointmentId);

        $.easyAjax({
            url: url,
            type: "POST",
            container: '#updateAppointmentForm',
            blockUI: true,
            data: formData,
            success: function(response) {
                if (response.status == "success") {
                    $('#updateAppointmentModal').modal('hide');
                    Swal.fire({ icon: 'success', text: response.message || 'Appointment updated successfully!', toast: true, position: 'top-end', timer: 3000, showConfirmButton: false });
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                var errorMsg = 'Failed to update appointment.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire({ icon: 'error', text: errorMsg, toast: true, position: 'top-end', timer: 5000, showConfirmButton: false });
            }
        });
    });

    // Cancel Appointment
    $('#cancel-appointment-btn').click(function() {
        Swal.fire({
            title: "@lang('messages.sweetAlertTitle')",
            text: "Are you sure you want to cancel this appointment?",
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
                var url = "{{ route('appointments.cancel', ':id') }}".replace(':id', appointmentId);
                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {
                        '_token': token
                    },
                    success: function(response) {
                        if (response.status == "success") {
                            Swal.fire({ icon: 'success', text: response.message || 'Appointment cancelled successfully!', toast: true, position: 'top-end', timer: 3000, showConfirmButton: false });
                            setTimeout(function() {
                                window.location.href = response.redirectUrl || "{{ route('events.index') }}";
                            }, 1000);
                        }
                    }
                });
            }
        });
    });

    // Copy Meet Link
    $('#copy-meet-link').click(function() {
        var meetLink = "{{ $appointment->google_meet_link ?? '' }}";
        if (meetLink) {
            navigator.clipboard.writeText(meetLink).then(function() {
                Swal.fire({ icon: 'success', text: 'Link copied to clipboard!', toast: true, position: 'top-end', timer: 3000, showConfirmButton: false });
            }, function() {
                // Fallback for older browsers
                var textArea = document.createElement("textarea");
                textArea.value = meetLink;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                Swal.fire({ icon: 'success', text: 'Link copied to clipboard!', toast: true, position: 'top-end', timer: 3000, showConfirmButton: false });
            });
        }
    });
</script>

