<?php

namespace App\Http\Controllers\API\User;

use App\Http\Requests\API\User\UpdateUserAPIRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class CustomerController
 * @package App\Http\Controllers\API
 */

class UserAPIController extends AppBaseController
{
    /** @var  UserService */
    private $userService;

    /**
     * UserAPIController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        return $this->sendResponse($this->userService->getFullUserByUserIdAndRole($user->id, $user->role));
    }

    /**
     * @param UpdateUserAPIRequest $request
     * @return mixed
     */
    public function update(UpdateUserAPIRequest $request)
    {
        $input = $request->all();
        $user = $request->user();

        return $this->sendResponse($this->userService->update($user, $input));
    }

}
