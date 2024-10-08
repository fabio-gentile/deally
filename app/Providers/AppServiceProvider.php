<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->greeting('Bonjour !')
                ->subject('Vérifiez votre adresse e-mail')
                ->line('Veuillez cliquer sur le bouton ci-dessous pour vérifier votre adresse e-mail.')
                ->action('Vérifiez votre adresse e-mail', $url)
                ->line('Si vous n\'avez pas créé de compte, aucune autre action n\'est requise.')
                ->salutation('Cordialement, ' . config('app.name'))
                ;
        });

        Vite::prefetch(concurrency: 3);

        Inertia::share([
            // Always share the authenticated user
            'auth.user' => function () {
                return Auth::user() ? Auth::user()->only('id', 'name', 'email') : null;
            },

            // You can also share flash messages or errors
            'flash' => function () {
                return [
                    'success' => session('success'),
                    'error' => session('error'),
                ];
            },
        ]);
    }
}
