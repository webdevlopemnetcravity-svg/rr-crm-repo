<?php

namespace App\Mail;

use App\Models\NewLead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TravelDetailsNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $travelDetails;
    public $leadName;
    public $consultantName;
    public $consultantNumber;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(NewLead $lead, $travelDetails, $pdfContent, $pdfFilename, $leadName = null, $consultantName = null, $consultantNumber = null)
    {
        $this->lead = $lead;
        $this->travelDetails = $travelDetails;
        $this->pdfContent = $pdfContent;
        $this->pdfFilename = $pdfFilename;
        $this->leadName = $leadName;
        $this->consultantName = $consultantName;
        $this->consultantNumber = $consultantNumber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Congratulations! Find your travel details')
            ->view('emails.lead.travel-details-notification')
            ->attachData($this->pdfContent, $this->pdfFilename, [
                'mime' => 'application/pdf',
            ]);
    }
}

