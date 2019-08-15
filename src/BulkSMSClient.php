<?php

namespace Aldemeery\BulkSMS;

use Aldemeery\BulkSMS\BulkSMSException;

class BulkSMSClient
{
    /**
     * API token id
     *
     * @var string
     */
    protected $username;

    /**
     * API token secret.
     *
     * @var string
     */
    protected $password;

    /**
     * API base URL.
     */
    protected $baseurl;

    /**
     * Array of messages to be sent.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Array of headers.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Sending messages endpoint.
     *
     * @var string
     */
    protected $endpoint = '/messages?auto-unicode=true';

    /**
     * Create a new client instance.
     *
     * @param string $username
     * @param string $password
     * @param string $baseurl
     */
    public function __construct($username, $password, $baseurl)
    {
        $this->username = $username;
        $this->password = $password;
        $this->baseurl = $baseurl;

        $this->addHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode("{$this->username}:{$this->password}"),
        ]);
    }

    /**
     * Add a list of messages to be sent.
     *
     * @param array $messages
     *
     * @return $this
     */
    public function addMessages($messages)
    {
        foreach ($messages as $message) {
            $this->addMessage($message);
        }

        return $this;
    }

    /**
     * Add a message to be sent.
     *
     * @param array $message
     *
     * @return $this
     */
    public function addMessage($message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Send the messages.
     *
     * @return array
     */
    public function send()
    {
        $headers = $this->prepareHeaders();
        $url = rtrim($this->baseurl, '/') . '/' . ltrim($this->endpoint, '/');
        $body = json_encode($this->messages);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $result = [];
        $result['server_response'] = curl_exec($ch);
        $curl_info = curl_getinfo($ch);
        $result['http_status'] = $curl_info['http_code'];
        $result['error'] = curl_error($ch);
        curl_close($ch);

        $this->validateResponse($result);

        return $result['server_response'];
    }

    /**
     * Add a list of headers.
     *
     * @param array $headers
     *
     * @return $this
     */
    public function addHeaders($headers)
    {
        foreach ($headers as $key => $value) {
            $this->addHeader($key, $value);
        }

        return $this;
    }

    /**
     * Add a header.
     *
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Prepare the headers array.
     *
     * @return array
     */
    protected function prepareHeaders()
    {
        $headers = [];

        foreach ($this->headers as $key => $value) {
            $headers[] = "$key: $value";
        }

        return $headers;
    }

    /**
     * Validate the response.
     *
     * @param array $result
     *
     * @throws \Aldemeery\BulkSMS\BulkSMSException
     *
     * @return void
     */
    protected function validateResponse($result)
    {
        if ($result['http_status'] != 201) {
            $message = $result['error'];

            if (!$message) {
                $message = "HTTP status " . $result['http_status'] . "; Response was " . $result['server_response'];
            }

            $message = "Error sending SMS: " . $message;

            throw new BulkSMSException($message);
        }
    }
}
