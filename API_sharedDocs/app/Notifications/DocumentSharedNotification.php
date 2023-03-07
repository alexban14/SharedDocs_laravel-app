<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentSharedNotification extends Notification
{
    use Queueable;
    // a trait that implements a asynchronous task that takes some time to perform
    // ex: sending email notification

    private Document $document;
    private string $singedLink;

    /**
     * Create a new notification instance.
     */
    public function __construct(Document $document, string $signedLink)
    {
        $this->document = $document;
        $this->singedLink = $signedLink;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // the delivery channels we want to send out our notification
        // mail (send out an email to the user),
        // database (logs the notification inside out local database),
        // broadcast (send out notification to frontend js through websockets),
        // vonage (web service that will send out sms messages to our user)
        // slack, discord, telegram

        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Document shared: ' . $this->document->title)
                    ->action('View the post here', $this->singedLink)
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
