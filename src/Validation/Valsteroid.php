<?php

namespace Apisteroid\Validation;

use Illuminate\Support\Facades\Hash;

class Valsteroid
{

    public static function Exist($exist)
    {
        if ($exist == 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public static function Username($username)
    {
        if (preg_match('/^[a-zA-Z0-9]{5,}$/', $username)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function Email($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function Gender($gender)
    {
        if($gender == "male" OR $gender == "female"){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function GenderEvent($gender)
    {
        if($gender == "male" OR $gender == "female" OR $gender == "all"){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function Date($date)
    {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function DateTime($date, $format = 'Y-m-d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public static function DateBetween($startdate, $enddate)
    {
        if (($startdate < $enddate) && ($enddate > $startdate)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function DateTimeBetween($startdate, $enddate)
    {
        $d1=new \DateTime($startdate);
        $d2=new \DateTime($enddate);
        $diff=$d2->diff($d1);
        if (!($startdate < $enddate) && ($enddate > $startdate)) {
            return FALSE;
        } elseif ($diff->m > 0) {
            return FALSE;
        } elseif ($diff->d < 1 AND $diff->h < 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public static function Required($value)
    {
        if ($value != NULL OR $value != "") {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function CheckPassword($password, $OriginPassword)
    {
        if (Hash::check($password, $OriginPassword)){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function CheckPin($Pin, $OriginPin)
    {
        if (Hash::check($Pin, $OriginPin)){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function Identical($a, $b)
    {
        if ($a === $b) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function ContainAtLeastOneCapital($value)
    {
        if (preg_match("#[A-Z]+#", $value)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function ContainAtLeastOneLowercase($value)
    {
        if (preg_match("#[a-z]+#", $value)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function ContainAtLeastOneNumber($value)
    {
        if (preg_match("#[0-9]+#", $value)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function LengthMinimum($value, $minimum)
    {
        if (!(strlen($value) <= $minimum)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function LengthMaximal($value, $maximal)
    {
        if (!(strlen($value) >= $maximal)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function CheckMimeType($MimeType)
    {
        if(substr($MimeType, 0, 5) == 'image') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function OperatorLogicRaw($Raw)
    {
        if($Raw) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function NaturalNumber($Number)
    {
        if (preg_match('/^[0-9]+$/', $Number)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function CheckBalance($balance, $price)
    {
        if($balance >= $price) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function CheckNumberNotZero($number)
    {
        if ($number > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
