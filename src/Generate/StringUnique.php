<?php

namespace Apisteroid\Generate;

class StringUnique
{
    public static $RandomSalt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    public static $RandomLength = 64;

    /*
     * Set Random Salt everything what you need
     */
    public static function SetRandomSalt($Salt = '', $Reload = TRUE)
    {
        if ($Reload == TRUE) {
            return self::$RandomSalt = $Salt;
        }
        return self::$RandomSalt .= $Salt;
    }

    /*
     * Set Random Length everything what you need
     */
    public static function SetRandomLength($Length = 0, $Reload = TRUE)
    {
        if ($Reload == TRUE) {
            return self::$RandomLength = $Length;
        }
        return self::$RandomLength = self::$RandomLength + $Length;
    }

    /*
     * You Need a someting like Random Key ? Ha! you can use method Random
     */
    public static function Random(
        $Salt = NULL,
        $Length = NULL,
        $SaltReload = TRUE,
        $LengthReload = TRUE
    )
    {
        if ($Salt == NULL) {
            $Salt = self::$RandomSalt;
        } else {
            if ($SaltReload == FALSE) {
                $Salt .= self::$RandomSalt;
            }
        }
        if ($Length == NULL) {
            $Length = self::$RandomLength;
        } else {
            if ($LengthReload == FALSE) {
                $Length = $Length + self::$RandomLength;
            }
        }
        $len = strlen($Salt);
        $hash = '';
        mt_srand(20000000*(double)microtime());
        for ($i = 0; $i < $Length; $i++) {
            $hash .= $Salt[mt_rand(0,$len - 1)];
        }
        return $hash;
    }

}
