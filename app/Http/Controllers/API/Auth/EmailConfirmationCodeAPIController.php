<?php


namespace App\Http\Controllers\API\Auth;


use App\Http\Controllers\AppBaseController;
use App\Models\User;
use App\Models\UserVerificationCode;
use Illuminate\Http\Request;

class EmailConfirmationCodeAPIController extends AppBaseController
{

    public function getCodeByEmail(Request $request)
    {
        $email = $request->email;

        $user = User::query()
            ->where('email', $email)
            ->first();

        $user_id = $user->id;

        if (!$user) {
            return [];
        }

        $user_verification = UserVerificationCode::query()
            ->where('user_id', $user_id)
            ->first();

        return response()->json($user_verification);
    }

}