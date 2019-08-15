<?php

namespace Aldemeery\BulkSMS;

use Aldemeery\BulkSMS\BulkSMSClient;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Aldemeery\BulkSMS\Channels\BulkSMSChannel;

class BulkSMSChannelServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('bulkSms', function ($app) {
                return new BulkSMSChannel(
                    new BulkSMSClient(
                        $this->app['config']['services.bulk_sms.username'],
                        $this->app['config']['services.bulk_sms.password'],
                        $this->app['config']['services.bulk_sms.base_url']
                    ),
                    $this->app['config']['services.bulk_sms.sms_from']
                );
            });
        });
    }
}
