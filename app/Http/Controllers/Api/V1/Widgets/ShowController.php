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

class ShowController extends Controller
{
    public function __invoke(WidgetRequest $request, string $id)
    {
        $options = $request->validated();

        if (User::getUserStatus($id) !== UserStatusesEnum::Active->value) {
            abort(Response::HTTP_NOT_FOUND, 'User not found or not active');
        }

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
