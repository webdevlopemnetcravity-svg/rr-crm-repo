<?php

namespace App\Mail;

use App\Models\NewLeadAppointment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeadAppointmentCancelledToUser extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $company;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(NewLeadAppointment $appointment, User $user)
    {
        $this->appointment = $appointment;
        $this->company = $appointment->company;
        $this->user = $user;
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
        $user = $this->user;
        
        $dateTime = $appointment->appointment_date->translatedFormat($company->date_format) . ' ' . $appointment->start_time->translatedFormat($company->time_format);

        // Get lead person name
        $leadPersonName = $appointment->lead->client_name ?? 'Lead';

        // Build appointment details content
        $content = '<strong>Meeting Title:</strong> ' . $appointment->meeting_title . '<br>';
        
        if ($appointment->description) {
            $content .= '<strong>Description:</strong> ' . $appointment->description . '<br>';
        }
        
        $content .= '<strong>Date & Time:</strong> ' . $dateTime . '<br>';
        $content .= '<strong>Lead ID:</strong> ' . $appointment->lead->id . '<br>';
        $content .= '<strong>Lead Person Name:</strong> ' . $leadPersonName . '<br>';

        // Get lead details URL
        $leadDetailsUrl = route('lead-details.index', ['id' => $appointment->lead->id]);

        return $this->from(config('mail.from.address'), $company->company_name ?? config('mail.from.name'))
            ->subject('Appointment Cancelled | RR Patel Overseas & Education')
            ->view('emails.appointment-cancelled-scheduler', [
                'content' => $content,
                'notifiableName' => $user->name,
                'leadPersonName' => $leadPersonName,
                'themeColor' => $company->header_color,
                'leadDetailsUrl' => $leadDetailsUrl
            ]);
    }
}
