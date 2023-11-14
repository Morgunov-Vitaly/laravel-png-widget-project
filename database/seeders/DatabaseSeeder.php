<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Review;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * @throws Exception
     */
    public function run(): void
    {
        $users = User::factory()->count(20)->create();

        // Создание отзывов для каждого пользователя (от 10 до 15 отзывов)
        foreach ($users as $user) {
            Review::factory()->count(random_int(10, 15))->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
