<?php

namespace App\Mail;

use App\Models\NewLead;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeadReassignedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $employee;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(NewLead $lead, User $employee)
    {
        $this->lead = $lead;
        $this->employee = $employee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Lead Reassigned to You - ' . ($this->lead->client_name ?? 'Lead #' . $this->lead->id))
            ->view('emails.lead.reassigned-notification');
    }
}

