<?php

namespace Apisteroid\Response;

use DateTime;

class Jsteroid
{
    static $Json = [
        'timestamp' => NULL,
        'environment' => NULL,
        'data' => NULL,
        'exception' => NULL,
    ];

    public static function get($CompositeKey = NULL)
    {
        if($CompositeKey)
        {
            $Init = new self();
            return $Init->GetVal(self::$Json, $CompositeKey);
        }
        else
        {
            return self::$Json;
        }
    }

    public static function set($CompositeKey, $Val)
    {
        $Init = new self();
        $Init->SetVal(self::$Json, $CompositeKey, $Val);
    }

    protected function GetVal(&$arr, $Path)
    {
        $loc = &$arr;
        foreach(explode('.', $Path) as $step)
        {
            $loc = &$loc[$step];
        }
        return $loc;
    }

    protected function SetVal(&$arr, $Path, $Val)
    {
        $loc = &$arr;
        foreach(explode('.', $Path) as $step)
        {
            $loc = &$loc[$step];
        }
        return $loc = $Val;
    }

}
