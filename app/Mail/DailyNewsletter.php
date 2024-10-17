<?php

namespace App\Mail;

use App\Models\Deal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyNewsletter extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(private $deals, private int $dealCount)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Newsletter quotidienne',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.newsletter.daily',
            with: [
                'deals' => $this->deals,
                'actionText' => 'Voir tous les deals',
                'actionUrl' => route('search.deals', ['filter_by' => 'popular', 'period' => 'today']),
                'introLines' => [
                    $this->dealCount . ' deal' . ($this->dealCount > 1 ? 's' : '') . ' ont été publié' . ($this->dealCount > 1 ? 's' : '') . ' aujourd\'hui.'
                ],
                'salutation' => 'Cordialement, ' . config('app.name'),
                'outroLines' => [
                    'Vous recevez cet e-mail car vous avez activé les notifications de deals dans vos préférences de compte.',
                    'Pour vous désabonner, modifiez vos préférences de compte.',
                ],
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
