<?php

namespace Apisteroid\Realtime;

use Pusher;
use Illuminate\Support\Facades\Redis;
use Apisteroid\Realtime\Realtime;

class Sniper extends Realtime
{
    /**
     * The Instance Sniper Object.
     *
     * @var \Sniper
     */
    protected static $Instance = NULL;

    /**
     * The Target Shoot From Sniper.
     *
     * @var array
     */
    protected $Target = [];

    /**
     * The data from server to client, so client will be understand what it is.
     *
     * @var array
     */
    protected $Data = [];

    /**
     * The Array of the FcmPayload attributes for notification.
     *
     * @var array
     */
    protected static $FcmPayload = [
        'notification' => [
            'title' => NULL,
            'icon' => NULL,
            'body' => NULL,
            'color' => NULL,
            'click_action' => 'CHEERS_NOTIFICATION'
        ],
        'data' => []
    ];

    /**
     * The Array of the ApnsPayload attributes for notification.
     *
     * @var array
     */
    protected static $ApnsPayload = [
        'aps' => [
            'alert' => [
                'title' => NULL,
                'subtitle' => NULL,
                'body' => NULL,
                'category' => NULL
            ],
        ],
        'data' => []
    ];

    protected static $MoonPayload = [
        'notification' => [
            'avatar' => NULL,
            'title' => NULL,
            'icon' => NULL,
            'body' => NULL,
        ],
        'data' => []
    ];

    /**
     * The Array of the Notify attributes for notification.
     *
     * @var array
     */
    protected $Notify = [];

    /**
     * Option for queue
     *
     * @var boolean
     */
    protected $Queue = false;

    /**
     * Init FCM Setup.
     *
     * @param array|string  $Args
     * @return static
     */
    public static function FCM($Args = NULL)
    {
        if (static::$Instance == NULL) {
            static::$Instance = new self();
        }
        return static::$Instance->SetFCM($Args);
    }

    /**
     * Set Notification To FcmPayload When Sniper buliding Notify.
     *
     * @param array|string  $Args
     * @return $this
     */
    protected function SetFCM($Args = NULL)
    {
        if ($Args !== NULL) {
            foreach ($Args as $key => $value) {
                if (in_array($key, ['title', 'icon', 'body', 'color', 'click_action'])) {
                    static::$FcmPayload['notification'][$key] = $value;
                }
            }
        }
        return $this;
    }

    /**
     * Init APNS Setup.
     *
     * @param array|string  $Args
     * @return static
     */
    public static function APNS($Args = NULL)
    {
        if (static::$Instance == NULL) {
            static::$Instance = new self();
        }
        return static::$Instance->SetAPNS($Args);
    }

    /**
     * Set Notification To ApnsPayload When Sniper buliding Notify.
     *
     * @param array|string  $Args
     * @return $this
     */
    protected function SetAPNS($Args = NULL)
    {
        if ($Args !== NULL) {
            foreach ($Args as $key => $value) {
                if (in_array($key, ['title', 'subtitle', 'body', 'category'])) {
                    static::$ApnsPayload['aps']['alert'][$key] = $value;
                }
            }
        }
        return $this;
    }

    /**
     * Init FCM Setup.
     *
     * @param array|string  $Args
     * @return static
     */
    public static function MOON($Args = NULL)
    {
        if (static::$Instance == NULL) {
            static::$Instance = new self();
        }
        return static::$Instance->SetMOON($Args);
    }

    /**
     * Set Notification To MoonPayload When Sniper buliding Notify.
     *
     * @param array|string  $Args
     * @return $this
     */
    protected function SetMOON($Args = NULL)
    {
        if ($Args !== NULL) {
            foreach ($Args as $key => $value) {
                if (in_array($key, ['avatar', 'title', 'icon', 'body'])) {
                    static::$MoonPayload['notification'][$key] = $value;
                }
            }
        }
        return $this;
    }

    /**
     * Init Data value.
     *
     * @param $Service
     * @return static
     */
    public static function Data($Data)
    {
        if (static::$Instance == NULL) {
            static::$Instance = new self();
        }
        return static::$Instance->SetData($Data);
    }

    /**
     * Set Data From Server Service To Client Service.
     *
     * @param $value
     * @return $this
     */
    public function SetData($value)
    {
        $this->Data = $value;
        self::$FcmPayload['data'] = $this->Data;
        self::$ApnsPayload['data'] = $this->Data;
        self::$MoonPayload['data'] = $this->Data;

        return $this;
    }

    /**
     * Set Target To Pusher When Sniper ready to Shoot.
     *
     * @param array|string  $Target
     * @return $this
     */
    public function Target($Target = [], $ID = NULL)
    {
        $this->Target = $Target;
        return $this->Notify($ID);
    }

    /**
     * Set Notify To Pusher When Sniper ready to Shoot.
     *
     * @return $this
     */
    protected function Notify($ID)
    {
        if( $ID !== NULL) {
            $SniperWebhookUrl = self::$SniperWebhookUrl.'/'.$ID;
        } else {
            $SniperWebhookUrl = self::$SniperWebhookUrl;
        }
        $this->Notify = [
            'webhook_url' => $SniperWebhookUrl,
            'webhook_level' => self::$SniperWebhookLevel,
            'apns' => self::$ApnsPayload,
            'fcm' => self::$FcmPayload,
            'moon' => self::$MoonPayload,
        ];
        return $this;
    }

    /**
     * Get FcmPayload Value.
     *
     * @return array
     */
    public static function GetFCM()
    {
        return self::$FcmPayload;
    }

    /**
     * Get ApnsPayload Value.
     *
     * @return array
     */
    public static function GetAPNS()
    {
        return self::$ApnsPayload;
    }

    /**
     * Get ApnsPayload Value.
     *
     * @return array
     */
    public static function GetMOON()
    {
        return self::$MoonPayload;
    }

    /**
     * Shoot Notify Sniper.
     *
     * @return $this
     */
    public function shoot()
    {
        //
        $this->SyncService();
        if ($this->Service !== NULL) {
            if ($this->Service == 'pusher') {
                static::$Pusher->notify($this->Target, $this->Notify);
            } elseif($this->Service == 'rocket') {
                $this->Curl($this->RocketPushNotification, static::$RocketHost.':'.static::$RocketPort.'/rocket/push_notification', static::$RocketPort);
            } elseif ($this->Service == 'redis') {
                Redis::publish('notifications', $this->RedisPushNotification);
            }
        } else {
            if (static::$DefaultService == 'pusher') {
                static::$Pusher->notify($this->Target, $this->Notify);
            }  elseif(static::$DefaultService == 'rocket') {
                $this->Curl($this->RocketPushNotification, static::$RocketHost.':'.static::$RocketPort.'/rocket/push_notification', static::$RocketPort);
            } elseif (static::$DefaultService == 'redis') {
                Redis::publish('notifications', $this->RedisPushNotification);
            }
        }
        return $this;
    }

    /**
     * Queue Notify Sniper.
     *
     * @return $this
     */
    public function Queue($queue)
    {
        if($queue) {
            $this->Queue = $queue;
        }
        return $this;
    }

    /**
     * Accessing Multiple Realtime Services.
     *
     * @param $Service
     * @return static
     */
    public static function Service($Service)
    {
        if (static::$Instance == NULL) {
            static::$Instance = new self();
        }
        return static::$Instance->ChangeService($Service);
    }

    /**
     * Synchronize Service when using rocket Services.
     *
     * @return $this
     */
    public function SyncService()
    {
        if ($this->Service == 'rocket' OR static::$DefaultService == 'rocket') {
            $RocketPushNotificationInterest = [];
            foreach ($this->Target as $key => $value) {
                $RocketPushNotificationInterest[] = ['code' => $value];
            }
            $RocketPushNotification = [
                'interest' => $RocketPushNotificationInterest,
                'options' => [
                    'queue' => $this->Queue,
                ],
                'notify' => $this->Notify
            ];
            $this->RocketPushNotification = json_encode($RocketPushNotification);
        }
        if (($this->Service == 'pusher' OR static::$DefaultService == 'pusher') && (!static::$Pusher)) {
            $this->Pusher();
        }
        if ($this->Service == 'redis' OR static::$DefaultService == 'redis') {
            $RedisPushNotificationInterest = [];
            foreach ($this->Target as $key => $value) {
                $RedisPushNotificationInterest[] = $value;
            }
            unset($this->Notify['fcm']);
            unset($this->Notify['apns']);
            $RedisPushNotification = [
                'routeNotification' => $RedisPushNotificationInterest,
                'options' => [
                    'queue' => $this->Queue,
                ],
                'notify' => $this->Notify
            ];
            $this->RedisPushNotification = json_encode($RedisPushNotification);
        }
        return $this;
    }

}
