<?php


namespace App\Helpers;


use Carbon\Carbon;

class DateHelper
{
    public static $timezone = 'Europe/Oslo';

    // PARSERS
    public static function parseDate($date)
    {
        return Carbon::parse($date)->setTimezone(self::$timezone)->toDateString();
    }

    public static function parseDateToTime($date)
    {
        return Carbon::parse($date)->setTimezone(self::$timezone)->toTimeString();
    }

    public static function parseDateToDateTime($date)
    {
        return Carbon::parse($date)->setTimezone(self::$timezone)->toDateTimeString();
    }


    // DATE AND TIME
    public static function getNowDateAndTime()
    {
        return Carbon::now()->setTimezone(self::$timezone)->toDateTimeString();
    }

    public static function getNowDateTimePlusMonth($month)
    {
        return Carbon::now()->setTimezone(self::$timezone)->addMonth($month)->toDateTimeString();
    }

    public static function getNowDateTimePlusMinutes($minutes)
    {
        return Carbon::now()->setTimezone(self::$timezone)->addMinutes($minutes)->toDateTimeString();
    }


    // TIMESTAMPS
    public static function getNowTimestamp()
    {
        return Carbon::now()->setTimezone(self::$timezone)->timestamp;
    }

    public static function getTimestampDateWithoutTimezone($date)
    {
        return Carbon::parse($date)->setTimezone(self::$timezone)->addHours('4')->timestamp;
    }

    public static function getTimestampDate($date)
    {
        return Carbon::parse($date)->setTimezone(self::$timezone)->timestamp;
    }

    public static function getTimestampByDays(int $days)
    {
        return Carbon::now()->setTimezone(self::$timezone)->addDays($days)->timestamp;
    }

    public static function getTimestampByHours(int $hours)
    {
        return Carbon::now()->setTimezone(self::$timezone)->addHours($hours)->timestamp;
    }

    public static function getTimestampByMinutes(int $minutes)
    {
        return Carbon::now()->setTimezone(self::$timezone)->addMinutes($minutes)->timestamp;
    }
}