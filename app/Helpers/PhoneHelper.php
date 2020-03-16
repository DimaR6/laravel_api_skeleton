<?php

namespace App\Helpers;

use Propaganistas\LaravelPhone\PhoneNumber;

class PhoneHelper
{

    public static function formatPhone($phone)
    {
        $country = env('ISO_COUNTRY_CODES', 'NO');
        return PhoneNumber::make($phone, $country)->formatForMobileDialingInCountry($country);
    }

}