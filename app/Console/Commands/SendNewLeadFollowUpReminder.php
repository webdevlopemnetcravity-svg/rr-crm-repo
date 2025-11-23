<?php

namespace App\Console\Commands;

use App\Events\NewLeadFollowUpReminderEvent;
use App\Models\Company;
use App\Models\NewLeadFollowUp;
use Illuminate\Console\Command;

class SendNewLeadFollowUpReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-new-lead-followup-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification of new lead followup to employee or added by user';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Company::active()->chunk(50, function ($companies) {
            foreach ($companies as $company) {
                $this->sendFollowUpReminder($company);
            }
        });

        return Command::SUCCESS;
    }

    public function sendFollowUpReminder($company)
    {
        $followups = NewLeadFollowUp::with('newLead', 'newLead.leadOwner')
            ->where('next_follow_up_date', '>=', now($company->timezone))
            ->whereHas('newLead', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->where('send_reminder', 'yes')
            ->get();

        foreach ($followups as $followup) {
            $remindTime = $followup->remind_time;
            if (!$remindTime) {
                continue;
            }

            // Parse remind_time (e.g., "15 Minutes Before", "1 Hour Before")
            $reminderDate = null;
            $remindTimeValue = (int) filter_var($remindTime, FILTER_SANITIZE_NUMBER_INT);
            
            if (strpos($remindTime, 'Hour') !== false) {
                $reminderDate = $followup->next_follow_up_date->copy()->subHours($remindTimeValue);
            } elseif (strpos($remindTime, 'Minute') !== false) {
                $reminderDate = $followup->next_follow_up_date->copy()->subMinutes($remindTimeValue);
            } else {
                continue;
            }

            // Check if it's time to send the reminder
            $currentDateTime = now($company->timezone);
            if ($reminderDate->format('Y-m-d H:i') == $currentDateTime->format('Y-m-d H:i')) {
                event(new NewLeadFollowUpReminderEvent($followup, false));
            }
        }
    }
}

