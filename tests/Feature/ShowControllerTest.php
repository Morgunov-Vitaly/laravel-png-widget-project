<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ShowControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShowUsersWidget(): void
    {
        // Создаем тестового пользователя с тремя отзывами с рейтингом 5 каждый
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

        // Проверяем на то, что в сторадже создан файл и его имя соответствует ожидаемому значению
        $fileName = "$user->id$rating$width$height$color$bgcolor.png";
        $storage = Storage::disk('widget-storage');
        $this->assertTrue($storage->exists($fileName));
    }

    # Альтернативный способ тестирования большого количества случаем с помощью dataProvider
    // Fail - набор тестов, который должен быть провален

    #[DataProvider('dataThatShouldFail')]
    public function testShowUsersWidgetFail(array $requestData): void
    {
        $id = $requestData['id'] ?? '';
        unset($requestData['id']);

        $response = $this->json('GET', "/api/v1/users/$id/widget?" . http_build_query($requestData));

        if (empty($id)) {
            $response->assertStatus(404);
        } else {
            $response->assertStatus(422);
        }
    }

    // Pass - набор тестов который должен быть пройден
    #[DataProvider('dataThatShouldPass')]
    public function testShowUsersWidgetPass(array $requestData): void
    {
        $user = User::factory()
            ->has(
                Review::factory()->published()->withRating100()->count(3)
            )
            ->active()
            ->create();

        $response = $this->json('GET', "/api/v1/users/$user->id/widget?" . http_build_query($requestData));
        $response->assertOk();

    }
    public static function dataThatShouldFail(): array
    {
        return [
            'empty_params' => [
                [],
            ],
            'no_id' => [
                [
                    'width' => 200,
                    'height' => 100,
                    'color' => '#ffffff',
                    'bgcolor' => '#000000',
                ],
            ],
            'invalid_width' => [
                [
                    'id' => 'any',
                    'width' => '200text',
                ],
            ],
            'invalid_height' => [
                [
                    'id' => 'any',
                    'height' => '100text',
                ],
            ],
            'width_less_than_available' => [
                [
                    'id' => 'any',
                    'width' => 99,
                ],
            ],
            'width_bigger_than_available' => [
                [
                    'id' => 'any',
                    'width' => 501,
                ],
            ],
            'height_less_than_available' => [
                [
                    'id' => 'any',
                    'height' => '100text',
                ],
            ],
            'height_bigger_than_available' => [
                [
                    'id' => 'any',
                    'height' => 501,
                ],
            ],
            'too_small_color' => [
                [
                    'id' => 'any',
                    'color' => 'ff',
                ],
            ],
            'too_big_bgcolor' => [
                [
                    'id' => 'any',
                    'height' => '#fffffffff',
                ],
            ],
            'wrong_color_letters' => [
                [
                    'id' => 'any',
                    'color' => '#4559gh',
                ],
            ],
        ];
    }

    public static function dataThatShouldPass(): array
    {
        return [
            'all_params' => [
                [
                    'width' => 200,
                    'height' => 100,
                    'color' => '#ffffff',
                    'bgcolor' => '#000000',
                ]
            ],
            'only_id' => [
                []
            ],
            'only_width' => [
                [
                    'width' => 200,
                ]
            ],
            'only_height' => [
                [
                    'height' => 100,
                ]
            ],
            'color_with_alpha' => [
                [
                    'color' => '#4559a4b5',
                ]
            ],
        ];
    }
}
