<?php

namespace Apisteroid\Realtime;

use Pusher;

use Apisteroid\Realtime\Realtime;

class Message extends Realtime
{
    /**
     * The Instance Message Object.
     *
     * @var \Message
     */
    protected static $Instance = NULL;

    /**
     * The Channel Receiver.
     *
     * @var array
     */
    protected $Channel = [];

    /**
     * The Event Receiver.
     *
     * @var string
     */
    protected $Event;

    /**
     * The data from server to client, so client will be understand what it is.
     *
     * @var array
     */
    protected $Data = [];

    /**
     * The json data if using rocket service.
     *
     * @var json
     */
    protected $RocketRealtimeMessage;

    /**
     * Init Data value.
     *
     * @param array|string  $Args
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
        return $this;
    }

    /**
     * Set Channel To Pusher When Message ready to Send.
     *
     * @param array|string  $Channel
     * @return $this
     */
    public function Channel($Channel = [])
    {
        $this->Channel = $Channel;
        return $this;
    }

    /**
     * Set Event To Pusher When Message ready to Send.
     *
     * @param $Event
     * @return $this
     */
    public function Event($Event)
    {
        $this->Event = $Event;
        return $this;
    }

    /**
     * Send Message.
     *
     * @return $this
     */
    public function Send()
    {
        //
        $this->SyncService();

        if ($this->Service !== NULL) {
            if ($this->Service == 'pusher') {
                static::$Pusher->trigger($this->Channel, $this->Event, $this->Data);
            } elseif($this->Service == 'rocket') {
                $this->Curl($this->RocketRealtimeMessage, static::$RocketHost.':'.static::$RocketPort.'/rocket/message', static::$RocketPort);
            }
        } else {
            if (static::$DefaultService == 'pusher') {
                static::$Pusher->trigger($this->Channel, $this->Event, $this->Data);
            }  elseif(static::$DefaultService == 'rocket') {
                $this->Curl($this->RocketRealtimeMessage, static::$RocketHost.':'.static::$RocketPort.'/rocket/message', static::$RocketPort);
            }
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
            $RocketRealtimeMessageChannel = [];
            foreach ($this->Channel as $key => $value) {
                $RocketRealtimeMessageChannel[] = ['channel' => $value];
            }
            $RocketRealtimeMessage = [
                'channel' => $RocketRealtimeMessageChannel,
                'event' => $this->Event,
                'data' => $this->Data
            ];
            $this->RocketRealtimeMessage = json_encode($RocketRealtimeMessage);
        }
        return $this;
    }

}
