<?php

namespace App\Events;

use App\Models\NewLeadFollowUp;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewLeadFollowUpReminderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $followup;
    public $subject;

    public function __construct(NewLeadFollowUp $followup, $subject)
    {
        $this->followup = $followup;
        $this->subject = $subject;
    }
}

