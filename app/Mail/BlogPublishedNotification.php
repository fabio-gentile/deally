<?php

namespace App\Mail;

use App\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BlogPublishedNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(private Blog $blog)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Un nouvel article a été publié !',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.blog.notification',
            with: [
                'blog' => $this->blog,
                'actionText' => 'Lire l\'article',
                'actionUrl' => route('blog.show', $this->blog->slug),
                'introLines' => [
                    'Un nouvel article a été publié sur notre blog ! Vous pouvez le consulter en cliquant sur le bouton ci-dessous',
                ],
                'salutation' => 'Cordialement, ' . config('app.name'),
                'outroLines' => [
                    'Vous recevez cet e-mail car vous avez activé les notifications lors de la publication d\'un nouvel article dans vos préférences de compte.',
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
