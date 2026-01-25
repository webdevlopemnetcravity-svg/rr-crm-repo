<?php

namespace App\Mail;

use App\Models\NewLeadAppointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeadAppointmentUpdatedToLead extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $company;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(NewLeadAppointment $appointment)
    {
        $this->appointment = $appointment;
        $this->company = $appointment->company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $appointment = $this->appointment;
        $company = $this->company;
        
        $dateTime = $appointment->appointment_date->translatedFormat($company->date_format) . ' ' . $appointment->start_time->translatedFormat($company->time_format);

        // Get consultant details from lead owner
        $consultantName = 'Our Team';
        $consultantNumber = '';
        
        if ($appointment->lead && $appointment->lead->leadOwner) {
            $consultant = $appointment->lead->leadOwner;
            $consultantName = $consultant->name ?? 'Our Team';
            $consultantNumber = $consultant->mobile ?? $consultant->phone ?? '';
            // Format consultant number - keep only 10 digits (no country code)
            if ($consultantNumber) {
                $consultantNumber = preg_replace('/[^0-9]/', '', $consultantNumber);
                $consultantNumber = ltrim($consultantNumber, '0');
                // Remove country code if present (keep only last 10 digits)
                if (strlen($consultantNumber) > 10) {
                    $consultantNumber = substr($consultantNumber, -10);
                }
            }
        }

        $content = '<strong>Meeting Title:</strong> ' . $appointment->meeting_title . '<br>';
        
        if ($appointment->description) {
            $content .= '<strong>Description:</strong> ' . $appointment->description . '<br>';
        }
        
        $content .= '<strong>Date & Time:</strong> ' . $dateTime . '<br>';
        $content .= '<strong>Updated By:</strong> ' . ($appointment->updater->name ?? $appointment->creator->name ?? 'Admin') . '<br>';
        $content .= '<strong>Consultant Name:</strong> ' . $consultantName . '<br>';
        $content .= '<strong>Consultant Contact Number:</strong> ' . ($consultantNumber ?: 'N/A') . '<br>';

        $url = $appointment->zoom_link ?? $appointment->google_meet_link;

        // Prepare meeting credentials for display
        $meetingId = $appointment->zoom_meeting_id ?? null;
        $meetingPassword = $appointment->zoom_meeting_password ?? null;

        return $this->from(config('mail.from.address'), $company->company_name ?? config('mail.from.name'))
            ->subject('Your Appointment Has Been Updated | RR Patel Overseas & Education')
            ->view('emails.appointment-details', [
                'content' => $content,
                'notifiableName' => $appointment->lead->client_name ?? '',
                'themeColor' => $company->header_color,
                'actionText' => 'Join Meeting',
                'url' => $url,
                'meetingId' => $meetingId,
                'meetingPassword' => $meetingPassword,
                'isUpdate' => true
            ]);
    }
}
