<?php

namespace Tests\Unit\Services;

use App\Services\PngWidgetService;
use App\Services\WidgetParamsDto;
use Tests\TestCase;

class PngWidgetServiceTest extends TestCase
{
    public function testCreatePngWidget(): void
    {
        // Подготовка данных для тестирования
        $params = [
            'id' => '01hf6nqwhq45e19t9r4qe660mj',
            'width' => 200,
            'height' => 100,
            'text' => 'Test Widget Text',
            'color' => '#ffffff',
            'bgColor' => '#000000',
            // Другие параметры, если необходимо
        ];
        $dto = WidgetParamsDto::fromArray($params);

        // Вызов метода, который тестируется
        $imagePath = PngWidgetService::createPngWidget($dto);

        // Проверка результата
        $this->assertNotNull($imagePath); // Убедимся, что метод вернул путь к изображению
        $this->assertFileExists($imagePath); // Проверим, что изображение было создано

        // Очистка после теста (удаление созданного изображения)
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
}
