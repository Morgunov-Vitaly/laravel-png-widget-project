<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Enums\UserStatusesEnum;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="UserResource",
     *     title="User Resource",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="status", type="string"),
     *     @OA\Property(property="avgRating", type="integer"),
     * )
     */

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => UserStatusesEnum::from($this->status)->name,
            'avgRating' => Review::getAverageRatingByUserId($this->id),
        ];
    }
}
