<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(): Collection
    {
        return User::all();
    }
}
