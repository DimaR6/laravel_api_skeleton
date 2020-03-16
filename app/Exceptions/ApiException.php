<?php

namespace App\Exceptions;

use Flugg\Responder\Facades\Responder;

class ApiException
{
    const USER_NOT_FOUND = 'user-not-found';
    const VERIFY_CODE_NOT_FOUND = 'verify-code-not-found';
    const NOT_VERIFIED_EMAIL = 'not-verified-email';
    const NOT_CORRECT_CREDENTIALS = 'not-correct-credentials';

    public static function throw($error, $code = 401)
    {
        abort(response()->json(Responder::error($code, 'message-' . $error)));
    }
}