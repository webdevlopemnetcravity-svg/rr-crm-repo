<x-cards.notification :notification="$notification"
                      :link="route('lead-list.index')"
                      :image="company()->logo_url"
                      :title="__('email.followUpReminder.subject') . ' #' . $notification->data['id']"
                      :time="$notification->created_at"/>

