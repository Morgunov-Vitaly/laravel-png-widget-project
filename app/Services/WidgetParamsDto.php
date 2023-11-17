<?php

declare(strict_types=1);

namespace App\Services;

readonly class WidgetParamsDto
{
    private function __construct(
        public string $id,
        public string $text,
        public int $width,
        public int $height,
        public string $color,
        public string $bgColor,
    ) {
    }

    public static function fromArray(array $options): WidgetParamsDto
    {
        return new self(
            $options['id'],
            empty($options['text']) ? '0' : (string) $options['text'],
            empty($options['width']) ? 500 : (int) $options['width'],
            empty($options['height']) ? 500 : (int) $options['height'],
            empty($options['color']) ? '#fff' : (string) $options['color'],
            empty($options['bgcolor']) ? '#000' : (string) $options['bgcolor'],
        );
    }

    public function toArray(): array
    {
        return [
            $this->id,
            $this->text,
            $this->width,
            $this->height,
            $this->color,
            $this->bgColor,
        ];
    }
}
