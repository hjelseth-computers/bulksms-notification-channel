<?php

namespace Aldemeery\BulkSMS\Channels;

use Aldemeery\BulkSMS\BulkSMSClient;
use Illuminate\Notifications\Notification;

class BulkSMSChannel
{
    /**
     * The BulkSMS client instance.
     *
     * @var \Aldemeery\BulkSMS\BulkSMSClient
     */
    protected $blukSms;

    /**
     * The phone number notifications should be sent from.
     *
     * @var string
     */
    protected $from;

    /**
     * Create a new BulkSMS channel instance.
     *
     * @param \Aldemeery\BulkSMS\BulkSMSClient $bulkSms
     * @param string $from
     */
    public function __construct(BulkSMSClient $bulkSms, $from)
    {
        $this->blukSms = $bulkSms;
        $this->from = $from;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification  $notification
     *
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('bulk_sms', $notification)) {
            return;
        }

        $message = $notification->toBulkSms($notifiable);

        if (is_string($message)) {
            $message = new BulkSMSMessage($message);
        }

        $message->to($to)->from($this->from);

        $this->blukSms->addMessage($message->toArray());

        $this->blukSms->send();
    }
}
