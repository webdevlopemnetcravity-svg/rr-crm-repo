<?php

namespace App\Listeners;

use App\Events\NewLeadFollowUpReminderEvent;
use App\Models\User;
use App\Notifications\NewLeadFollowUpReminder;
use Illuminate\Support\Facades\Notification;

class NewLeadFollowUpReminderListener
{
    /**
     * Handle the event.
     *
     * @param NewLeadFollowUpReminderEvent $event
     * @return void
     */
    public function handle(NewLeadFollowUpReminderEvent $event)
    {
        $companyId = $event->followup->newLead->company_id;
        $adminUserIds = User::allAdmins($companyId)->pluck('id')->toArray();
        
        // Notify lead owner or first admin
        $notifyUser = $event->followup->newLead->leadOwner;
        
        if (!$notifyUser) {
            $notifyUser = User::whereIn('id', $adminUserIds)->first();
        }
        
        if ($notifyUser) {
            Notification::send($notifyUser, new NewLeadFollowUpReminder($event->followup, $event->subject));
        }
    }
}

