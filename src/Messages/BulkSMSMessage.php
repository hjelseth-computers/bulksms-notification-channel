<?php

namespace Aldemeery\BulkSMS\Messages;

use Illuminate\Contracts\Support\Arrayable;

class BulkSMSMessage implements Arrayable
{
    /**
     * The message body.
     *
     * @var string
     */
    public $body;

    /**
     * The phone number the message should be sent from.
     *
     * @var string
     */
    public $from;

    /**
     * The phone number the message should be sent to.
     *
     * @var string
     */
    public $to;

    /**
     * Create a new message instance.
     *
     * @param string $body.
     */
    public function __construct($body = '')
    {
        $this->body = $body;
    }

    /**
     * Set the message body.
     *
     * @param string $body
     *
     * @return $this
     */
    public function body($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Set the phone number the message should be sent from.
     *
     * @param string $from
     *
     * @return $this
     */
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Set the phone number the message should be sent to.
     *
     * @param string $to
     *
     * @return $this
     */
    public function to($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get the array representation of the message object.
     *
     * @return array
     */
    public function toArray()
    {
        return (array) $this;
    }
}
