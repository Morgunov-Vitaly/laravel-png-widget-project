<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use RuntimeException;

class PngWidgetService
{
    public const FONT_PATH = '/fonts/Montserrat-Bold.ttf';

    public const FONT_SIZE_MULTIPLICATION = 0.2;

    public const ANGLE = 0;

    public const EXTENSION = 'png';

    public const SUB_DIR = 'w-images';

    public static function createPngWidget(WidgetParamsDto $dto): ?string
    {
        $fileName = self::getFileName($dto);
        $storage = Storage::disk('widget-storage');

        // TODO This is image cache. It needs to create a demon for deleting old files from storage
        if ($storage->exists($fileName . '.' . self::EXTENSION)) {
            return $storage->path($fileName . '.' . self::EXTENSION);
        }

        if (!$image = imagecreatetruecolor($dto->width, $dto->height)) {
            return null;
        }

        $font = public_path() . self::FONT_PATH;
        $minSize = ($dto->width >= $dto->height) ? $dto->height : $dto->width;
        $fontSize = $minSize * self::FONT_SIZE_MULTIPLICATION;

        $bgColor = self::hex2rgb($dto->bgColor);
        $color = self::hex2rgb($dto->color);

        // Estimate colors
        $backgroundColor = imagecolorallocate($image, $bgColor['r'], $bgColor['g'], $bgColor['b']);
        $textColor = imagecolorallocate($image, $color['r'], $color['g'], $color['b']);

        // Fill background
        imagefilledrectangle($image, 0, 0, $dto->width, $dto->height, $backgroundColor);

        // Add text
        $textBox = imagettfbbox($fontSize, self::ANGLE, $font, $dto->text);

        // Get your Text Width and Height
        $textWidth = $textBox[2] - $textBox[0];
        $textHeight = $textBox[7] - $textBox[1];

        // Calculate coordinates of the text
        $offsetX = ($dto->width / 2) - ($textWidth / 2);
        $offsetY = ($dto->height / 2) - ($textHeight / 2);

        imagettftext($image, $fontSize, 0, (int)$offsetX, (int)$offsetY, $textColor, $font, $dto->text);

        //Save image as a file
        $tempImagePath = self::getTempImagePath($fileName);
        imagepng($image, $tempImagePath);
        imagedestroy($image);

        // Move file to storage
        $storage->put($fileName . '.' . self::EXTENSION, file_get_contents($tempImagePath));
        unlink($tempImagePath);

        return $storage->path($fileName . '.' . self::EXTENSION);
    }

    public static function hex2rgb(string $hex): array
    {
        $hex = str_replace('#', '', $hex);

        switch (strlen($hex)) {
            case 1:
                $hex .= $hex;
                // no break
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

    private static function getFileName(WidgetParamsDto $dto): string
    {
        return implode($dto->toArray());
    }

    private static function getTempImagePath(string $fileName): string
    {
        $directory = public_path(self::SUB_DIR);

        if (
            !file_exists($directory)
            && !mkdir($directory, 0777, true)
            && !is_dir($directory)
        ) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }

        $path = self::SUB_DIR . DIRECTORY_SEPARATOR . $fileName;

        return public_path($path);
    }
}
