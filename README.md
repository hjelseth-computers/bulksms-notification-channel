
# BulkSMS Notification Channel
[BulkSMS](https://www.bulksms.com/) notification channel for laravel.

* [Installation](#installation)
* [Sending SMS Notifications](#sending-sms-notifications)

## Installation
This package requires `php 7.0` or above, and works with Laravel `5.5` or above.

Open the comman line in your project root directory and enter:
```bash
composer require aldemeery/bulksms-notification-channel
```

The service provider will automatically be registered. Or you may manually add the service provider in your `config/app.php` file:

```php
'providers' => [
    // ...
    Aldemeery\BulkSMS\BulkSMSChannelServiceProvider::class,
];
```
Next, you will need to add a few configuration options to your  `config/services.php`configuration file. You may copy the example configuration below to get started:

```php
'bulk_sms' => [
    'username'  =>  env('BULK_SMS_USERNAME'),
    'password'  =>  env('BULK_SMS_PASSWORD'),
    'sms_from'  =>  env('BULK_SMS_FROM'),
    'base_url'  =>  env('BULK_SMS_BASEURL'),
],
```
The  `sms_from`  option is the phone number that your SMS messages will be sent from.

Finally add the `routeNotificationForBulkSms` method in your Model, which will be used to get the phone number the message should be sent to.

```php
/**
 * Get the notification routing information for the Bulk SMS driver.
 *
 * @param \Illuminate\Notifications\Notification|null $notification Notification instance.
 *
 * @return  mixed
 */
public function routeNotificationForBulkSms($notification = null)
{
	return  $this->phone_number; // or whatever field name that has the phone number.
}
```

## Sending SMS Notifications

If a notification supports being sent as an SMS, you should define a  `toBulkSms`  method on the notification class. This method will receive a  `$notifiable`  entity and should return a  `Aldemeery\BulkSMS\Messages\BulkSMSMessage`  instance:

```php
/**
 * Get the BulkSMS representation of the notification.
 *
 * @param mixed $notifiable Notifiable instance.
 *
 * @return \Aldemeery\BulkSMS\Messages\BulkSMSMessage
 */
public function toBulkSms($notifiable)
{
	return new BulkSMSMessage('Your verification code is 1234');
}
```
You also need to add the `bulkSms` channel to your notification channels in the `via` method:

```php
/**
 * Get the notification channels.
 *
 * @param mixed $notifiable
 *
 * @return array|string
 */
public function via($notifiable)
{
	return ['bulkSms'];
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
