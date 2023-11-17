<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ShowControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShowUsersWidget(): void
    {
        // Создаем тестового пользователя с тремя отзывами с рейтингом 50 каждый
        $user = User::factory()
            ->has(
                Review::factory()->published()->withRating5()->count(3)
            )
            ->active()
            ->create();

        $width = 500;
        $height = 400;
        $color = 'fff';
        $bgcolor = '673ab7';
        $rating = Review::getAverageRatingByUserId($user->id);

        // Создаем тестовый запрос для виджета
        $response = $this->get(
            "/api/v1/users/$user->id/widget?width=$width&height=$height&color=$color&bgcolor=$bgcolor"
        );
        $response->assertOk();
        $response->assertHeader('content-type', 'image/png');

        $fileName = "$user->id$rating$width$height$color$bgcolor.png";
        $storage = Storage::disk('widget-storage');
        $this->assertTrue($storage->exists($fileName));
    }
}
