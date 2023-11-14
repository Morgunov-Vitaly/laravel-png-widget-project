<?php

declare(strict_types=1);

namespace App\Services;

class PngWidgetService
{
    public const FONT_PATH = '/fonts/Montserrat-Bold.ttf';
    public const FONT_SIZE_MULTIPLICATION = 0.2;
    public const ANGLE = 0;

    public static function createPngWidget(WidgetParamsDto $dto): ?string
    {
        // Создание изображения
        if ($image = imagecreatetruecolor($dto->width, $dto->height)) {
            $background = self::hex2rgb($dto->bgColor);
            $color = self::hex2rgb($dto->color);

            // Определение цветов
            $backgroundColor = imagecolorallocate($image, $background['r'], $background['g'], $background['b']);
            $textColor = imagecolorallocate($image, $color['r'], $color['g'], $color['b']);

            // Заливка фона
            imagefilledrectangle($image, 0, 0, $dto->width, $dto->height, $backgroundColor);

            // Добавление текста
            $font = public_path() . self::FONT_PATH;
            $minSize = ($dto->width >= $dto->height) ? $dto->height : $dto->width;
            $fontSize = $minSize * self::FONT_SIZE_MULTIPLICATION;

            // Get Bounding Box Size
            $textBox = imagettfbbox($fontSize, self::ANGLE, $font, $dto->text);

            // Get your Text Width and Height
            $textWidth = $textBox[2] - $textBox[0];
            $textHeight = $textBox[7] - $textBox[1];

            // Calculate coordinates of the text
            $offsetX = ($dto->width / 2) - ($textWidth / 2);
            $offsetY = ($dto->height / 2) - ($textHeight / 2);

            imagettftext($image, $fontSize, 0, (int)$offsetX, (int)$offsetY, $textColor, $font, $dto->text);

            // Сохранение изображения на диск
            $imagePath = public_path("images/$dto->id.png");
            imagepng($image, $imagePath);
            imagedestroy($image);

            return $imagePath;
        }

        return null;
    }

    public static function hex2rgb(string $hex): array
    {
        $hex = str_replace('#', '', $hex);

        switch (strlen($hex)) {
            case 1:
                $hex .= $hex;
            case 2:
                $r = hexdec($hex);
                $g = hexdec($hex);
                $b = hexdec($hex);
                break;

            case 3:
                $r = hexdec($hex[0] . $hex[0]);
                $g = hexdec($hex[1] . $hex[1]);
                $b = hexdec($hex[2] . $hex[2]);
                break;

            default:
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
                break;
        }

        return ['r' => $r, 'g' => $g, 'b' => $b];
    }
}
