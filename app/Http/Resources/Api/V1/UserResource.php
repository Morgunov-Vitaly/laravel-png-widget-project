<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Enums\UserStatusesEnum;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
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
