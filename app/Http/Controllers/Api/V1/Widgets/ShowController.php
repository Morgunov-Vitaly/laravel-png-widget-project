<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Widgets;

use App\Enums\UserStatusesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Widgets\WidgetRequest;
use App\Models\Review;
use App\Models\User;
use App\Services\PngWidgetService;
use App\Services\WidgetParamsDto;
use Symfony\Component\HttpFoundation\Response;

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
class ShowController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/users/{id}/widget",
     *     tags={"Show Users Png Widget"},
     *     summary="Generate Png Widget for User",
     *     description="Generate Png Widget",
     *     operationId="usersWidget",
     *     deprecated=false,
     *
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User id",
     *          required=true,
     *          explode=true,
     *
     *          @OA\Schema(
     *               type="string",
     *           )
     *      ),
     *
     *     @OA\Parameter(
     *         name="width",
     *         in="query",
     *         description="width of the widget in px",
     *         required=false,
     *         explode=true,
     *
     *         @OA\Schema(
     *              default=500,
     *              type="integer",
     *          )
     *     ),
     *
     *     @OA\Parameter(
     *          name="height",
     *          in="query",
     *          description="height of the widget in px",
     *          required=false,
     *          explode=true,
     *
     *          @OA\Schema(
     *               default=500,
     *               type="integer",
     *           )
     *      ),
     *
     *     @OA\Parameter(
     *          name="color",
     *          in="query",
     *          description="color of the widget text in HEX",
     *          required=false,
     *          explode=true,
     *
     *          @OA\Schema(
     *               default="#fff",
     *               type="string",
     *           )
     *      ),
     *
     *     @OA\Parameter(
     *           name="bgcolor",
     *           in="query",
     *           description="Background color of the widget text in HEX",
     *           required=false,
     *           explode=true,
     *
     *           @OA\Schema(
     *                default="#000",
     *                type="string",
     *            )
     *       ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="image/png"
     *      ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     ),
     * )
     */
    public function __invoke(WidgetRequest $request, string $id)
    {
        if (User::getUserStatus($id) !== UserStatusesEnum::Active->value) {
            abort(Response::HTTP_NOT_FOUND, 'User not found or not active');
        }

        $options = $request->validated();
        $options['text'] = (string) Review::getAverageRatingByUserId($id, 2);
        $options['id'] = $id;
        $dto = WidgetParamsDto::fromArray($options);

        $imagePath = PngWidgetService::createPngWidget($dto);

        if (!$imagePath || !file_exists($imagePath)) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->file($imagePath);
    }
}
