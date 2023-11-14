<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Widgets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Widgets\WidgetRequest;
use Illuminate\Database\Eloquent\Collection;

class ShowController extends Controller
{
    public function __invoke(WidgetRequest $request, $id): Collection|array
    {
        $a = $request->all();

        dd($a);
        return $a;
    }
}
