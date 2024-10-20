<?php

namespace App\Jobs;

use App\Mail\BlogPublishedNotification;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendBlogNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private Blog $blog)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $subscribers = User::whereJsonContains('preferences->blog_notification', true)->with('blogLastNotifiedAt')->get();

        foreach ($subscribers as $subscriber) {
            if ($subscriber->blogLastNotifiedAt === null) {
                $subscriber->blogLastNotifiedAt()->create([
                    'user_id' => $subscriber->id,
                    'last_notified_at' => now(),
                ]);
            }
            if ($subscriber->blogLastNotifiedAt->last_notified_at && $subscriber->blogLastNotifiedAt->last_notified_at->diffInHours(now()) <= 24) {
                return;
            }

            Mail::to($subscriber->email)->send(new BlogPublishedNotification($this->blog));

            $subscriber->blogLastNotifiedAt->update(['last_notified_at' => now()]);
        }
    }
}
