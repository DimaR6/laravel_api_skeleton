<?php


namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Str;

class HashGeneratorHelper
{
    public static function generateHash()
    {
        return md5(Carbon::now()) . md5(mt_rand(0, 9)) . md5(Str::random(1));
    }

    public static function generateReferralCode()
    {
        return strtoupper(Str::random((10)));
    }

    public static function generateStrRandom($len)
    {
        return Str::random($len);
    }
}