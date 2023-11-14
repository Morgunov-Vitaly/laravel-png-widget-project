<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserStatusesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rating',
        'is_published',
        'user_id',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_published' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('users');
    }

    public static function getAverageRatingByUserId(string $userId, ?int $precision = 0)
    {
        $avgRating = self::where('user_id', $userId)
            ->where('is_published', true)
            ->avg('rating');
        return round((float) $avgRating, $precision);
    }
}
