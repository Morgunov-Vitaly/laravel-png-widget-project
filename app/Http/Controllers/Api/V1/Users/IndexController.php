<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Resources\Api\V1\UserCollection;
use App\Models\User;
use Illuminate\Routing\Controller;

class IndexController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     summary="Get list of users",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserResource")
     *         )
     *     )
     * )
     */
    public function __invoke()
    {
        return new UserCollection(User::paginate(15));
    }
}
