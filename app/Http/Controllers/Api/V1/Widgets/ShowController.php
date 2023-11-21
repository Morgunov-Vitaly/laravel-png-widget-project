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
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class ShowController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/users/{id}/widget",
     *     summary="Get user's widget",
     *     tags={"Widgets"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Parameter(
     *           name="width",
     *           in="query",
     *           description="Width of the widget in px",
     *           required=false,
     *           @OA\Schema(type="integer", format="int32", minimum=100, maximum=500, default=500)
     *       ),
     *  @OA\Parameter(
     *       name="height",
     *       in="query",
     *       description="Height of the widget in px",
     *       required=false,
     *       @OA\Schema(type="integer", format="int32", minimum=100, maximum=500, default=500)
     *   ),
     *   @OA\Parameter(
     *       name="color",
     *       in="query",
     *       description="Color of the widget in hex format",
     *       required=false,
     *       @OA\Schema(type="string", pattern="^#?([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$", default="#fff")
     *   ),
     *   @OA\Parameter(
     *       name="bgcolor",
     *       in="query",
     *       description="Background color of the widget in hex format",
     *       required=false,
     *       @OA\Schema(type="string", pattern="^#?([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$", default="#000")
     *   ),
     *     @OA\Response(
     *         response=200,
     *         description="Widget image",
     *         @OA\MediaType(
     *             mediaType="image/png"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found or not active"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function __invoke(WidgetRequest $request, string $id): BinaryFileResponse
    {
        if (User::getUserStatus($id) !== UserStatusesEnum::Active->value) {
            abort(Response::HTTP_NOT_FOUND, 'User not found or not active');
        }

        $options = $request->validated();
        $options['text'] = (string)Review::getAverageRatingByUserId($id, 2);
        $options['id'] = $id;
        $dto = WidgetParamsDto::fromArray($options);

        $imagePath = PngWidgetService::createPngWidget($dto);

        if (!$imagePath || !file_exists($imagePath)) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->file($imagePath);
    }
}
