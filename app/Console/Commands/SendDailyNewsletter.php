<?php

namespace App\Console\Commands;

use App\Mail\DailyNewsletter;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyNewsletter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-newsletter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie la newsletter quotidienne avec les derniers deals.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $deals = Deal::query()
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('votes', 'desc')
            ->get();

        if ($deals->isEmpty()) {
            $this->info('Aucun deal n\'a été publié aujourd\'hui.');
            return;
        }

        $latestDeals = $deals->take(5);
        $subscribers = User::whereJsonContains('preferences->newsletter', true)->get();

        if ($subscribers->isEmpty()) {
            $this->info('Aucun utilisateur n\'a activé les notifications de deals.');
            return;
        }

        foreach ($subscribers as $subscriber) {
            // Send the daily newsletter to each subscriber
            Mail::to($subscriber->email)->send(new DailyNewsletter($latestDeals, $deals->count()));
        }

        $this->info('La newsletter quotidienne a été envoyée avec succès.');
    }
}
