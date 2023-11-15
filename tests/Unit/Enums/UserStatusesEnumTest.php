<?php

namespace Tests\Unit\Enums;

use App\Enums\UserStatusesEnum;
use PHPUnit\Framework\TestCase;

class UserStatusesEnumTest extends TestCase
{

    public function testGetNameByValue(): void
    {
        $this->assertEquals('Inactive', UserStatusesEnum::getNameByValue(0));
    }

    public function testForSelect(): void
    {
        $this->assertEquals(
            [
                0 => 'Inactive',
                1 => 'Active',
                2 => 'Blocked',
            ],
            UserStatusesEnum::forSelect(),
        );
    }
}
