<?php

namespace Tests\Feature;

use App\Enums\UserStatusesEnum;
use App\Http\Controllers\Api\V1\Widgets\ShowController;
use App\Http\Requests\Api\V1\Widgets\WidgetRequest;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ShowControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShowUsersWidget()
    {
        // Создаем тестового пользователя с тремя отзывами с рейтингом 50 каждый
        $user = User::factory()
            ->has(
                Review::factory()->published()->withRating5()->count(3)
            )
            ->active()
            ->create();

        $rating = Review::getAverageRatingByUserId($user->id);
        $imageName = '';

        // Создаем тестовый запрос для виджета
        $response = $this->get("/api/v1/users/$user->id/widget?width=500&height=400&color=fff&bgcolor=673ab7");
        $response->assertOk();
        $response->assertHeader('content-type', 'image/png');
    }

    //protected function setUp(): void
    //{
    //    parent::setUp();
    //    // Создаем тестовую директорию для изображений в хранилище
    //    Storage::fake('public');
    //}
    //
    //protected function tearDown(): void
    //{
    //    parent::tearDown();
    //    // Удаляем временные изображения после тестов
    //    Storage::disk('public')->deleteDirectory('images');
    //}
    //
    //public function testShowWidgetForActiveUser(): void
    //{
    //    // Создаем тестового пользователя
    //    $user = User::factory()->create(['status' => UserStatusesEnum::Active]);
    //
    //    // Создаем тестовый запрос для виджета
    //    $request = WidgetRequest::create('/api/v1/users/' . $user->id . '/widget', 'GET');
    //
    //    // Вызываем контроллер
    //    $controller = new ShowController();
    //    $response = $controller($request, (string) $user->id);
    //
    //    // Проверяем, что ответ имеет статус 200 OK
    //    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    //
    //    // Проверяем, что ответ возвращает изображение
    //    $this->assertTrue($response->headers->contains('Content-Type', 'image/png'));
    //
    //    // Проверяем, что изображение было создано в хранилище
    //    //Storage::disk('public')->assertExists('images/' . $user->id . '_widget.png');
    //}
    //
    //public function testShowWidgetForInactiveUser(): void
    //{
    //    // Создаем тестового пользователя со статусом "неактивный"
    //    $user = User::factory()->create(['status' => UserStatusesEnum::Inactive]);
    //
    //    // Создаем тестовый запрос для виджета
    //    $request = WidgetRequest::create('/api/v1/users/' . $user->id . '/widget', 'GET');
    //
    //    // Вызываем контроллер
    //    $controller = new ShowController();
    //    $response = $controller($request, (string) $user->id);
    //
    //    // Проверяем, что ответ имеет статус 404 Not Found
    //    $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    //}
}
