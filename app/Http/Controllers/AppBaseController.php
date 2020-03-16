<?php

namespace App\Http\Controllers;

use Flugg\Responder\Facades\Responder;

/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    public function sendResponse($result = [])
    {
        return  Responder::success($result);
    }
}
