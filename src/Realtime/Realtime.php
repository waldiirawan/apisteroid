<?php

namespace Apisteroid\Realtime;

use Exception;
use Pusher;

abstract class Realtime
{
    /**
     * The Default Service Reltime Connection.
     *
     * @var string
     */
    protected static $DefaultService;

    /**
     * The Service Reltime Connection.
     *
     * @var string
     */
    protected $Service;

    /**
     * The Pusher instance.
     *
     * @var \Pusher
     */
    protected static $Pusher;

    /**
     * Pusher Option.
     *
     * @var array
     */
    protected $PusherOption = ['cluster' => 'eu', 'encrypted' => true];

    /**
     * The AppId for the Pusher.
     *
     * @var string
     */
    protected static $PuserAppId;

    /**
     * The Key for the Pusher.
     *
     * @var string
     */
    protected static $PuserKey;

    /**
     * The Secret for the Pusher.
     *
     * @var string
     */
    protected static $PuserSecret;

    /**
     * The Host for the Rocket.
     *
     * @var string
     */
    protected static $RocketHost;

    /**
     * The Port for the Rocket.
     *
     * @var string
     */
    protected static $RocketPort;

    /**
     * The AppId for the Rocket.
     *
     * @var string
     */
    protected static $RocketAppId;

    /**
     * The SecretKey for the Rocket.
     *
     * @var string
     */
    protected static $RocketSecretKey;

    /**
     * [$WebhookUrl description]
     * @var [type]
     */
    protected static $SniperWebhookUrl;

    /**
     * [$WebhookLevel description]
     * @var [type]
     */
    protected static $SniperWebhookLevel;

    /*
     * Var_Dump for debugging
     */
    public $Callback = [ 'response', 'error' ];

    /**
     * Create a new Realtime model.
     *
     * @return $this
     */
    public function __construct()
    {
        return $this->Sync();
    }

    /**
     * Set Synchronize Object Instance.
     *
     * @return $this
     */
    public function Sync()
    {
        if($this->Service == 'pusher') {
            $this->Pusher();
        }
        return $this;
    }

    /**
     * New Pusher Instance.
     *
     * @return static
     */
    public function Pusher()
    {
        if (class_exists('Pusher')) {
            return static::$Pusher = new Pusher(static::$PuserKey, static::$PuserSecret, static::$PuserAppId, $this->PusherOption);
        } else {
            throw new Exception('Please install pusher before you use it!');
        }
    }

    /**
     * Set Pusher App Id Value.
     *
     * @param $AppId
     * @return string
     */
    public static function PusherAppId($AppId)
    {
        return static::$PuserAppId = $AppId;
    }

    /**
     * Set Pusher Key Value.
     *
     * @param $PuserKey
     * @return string
     */
    public static function PusherKey($PuserKey)
    {
        return static::$PuserKey = $PuserKey;
    }

    /**
     * Set Pusher Secret Value.
     *
     * @param $PuserSecret
     * @return string
     */
    public static function PusherSecret($PuserSecret)
    {
        return static::$PuserSecret = $PuserSecret;
    }

    /**
     * Set Rocket Host Value.
     *
     * @param $RocketHost
     * @return string
     */
    public static function RocketHost($RocketHost)
    {
        return static::$RocketHost = $RocketHost;
    }

    /**
     * Set Rocket Port Value.
     *
     * @param $RocketPort
     * @return string
     */
    public static function RocketPort($RocketPort)
    {
        return static::$RocketPort = $RocketPort;
    }

    /**
     * Set Rocket AppId Value.
     *
     * @param $PuserSecret
     * @return string
     */
    public static function RocketAppId($RocketAppId)
    {
        return static::$RocketAppId = $RocketAppId;
    }

    /**
     * Set Rocket SecretKey Value.
     *
     * @param $RocketSecretKey
     * @return string
     */
    public static function RocketSecretKey($RocketSecretKey)
    {
        return static::$RocketSecretKey = $RocketSecretKey;
    }

    /**
     * Change the Realtime Service method.
     *
     * @param string $Service
     * @return $this
     */
    public function ChangeService($Service)
    {
        $this->Service = $Service;
        return $this;
    }

    /**
     * Set Default the Realtime Service method.
     *
     * @param $Service
     * @return static
     */
    public static function SetService($Service)
    {
        return static::$DefaultService = $Service;
    }

    /**
     * [SetSniperWebhookUrl description]
     * @method SetSniperWebhookUrl
     * @param  static             $SniperWebhookUrl [description]
     */
    public static function SetSniperWebhookUrl($SniperWebhookUrl)
    {
        return static::$SniperWebhookUrl = $SniperWebhookUrl;
    }

    /**
     * [SetSniperWebhookLevel description]
     * @method SetSniperWebhookLevel
     * @param  static                $SniperWebhookLevel [description]
     */
    public static function SetSniperWebhookLevel($SniperWebhookLevel)
    {
        return static::$SniperWebhookLevel = $SniperWebhookLevel;
    }

    /**
     * Curl Service.
     *
     * @return void
     */
    public function Curl($data, $url, $port)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_PORT => $port,
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json",
                "appid: ".static::$RocketAppId,
                "secretkey: ".static::$RocketSecretKey
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        static::$Instance->Callback['respone'] = $response;
        static::$Instance->Callback['error'] = $err;
        curl_close($curl);
    }
}
