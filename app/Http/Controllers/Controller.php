<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="Users Widget App API",
 *     description="API docs for user-widget-app project",
 *
 *     @OA\Contact(name="Swagger API Team")
 * )
 *
 * @OA\Server(
 *     url="http://localhost",
 *     description="API server"
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;
}
