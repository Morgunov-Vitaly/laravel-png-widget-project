<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/users/",
     *     tags={"Show All Users"},
     *     summary="Show List Of ALl Users",
     *     description="Show List Of ALl Users",
     *     operationId="users",
     *     deprecated=false,
     *     @OA\Response(
     *          response=200,
     *          description="Application/json"
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="User Not Found"
     *     ),
     * )
     */
    public function __invoke(): Collection
    {
        return User::all();
    }
}
