<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class DataTable
{
    public static function sort(
        Builder $query,
        Request $request,
        array $columns,
        string $default = 'created_at',
    ): array {
        $sort = $request->string('sort')->toString();
        $sort = array_key_exists($sort, $columns) ? $sort : $default;

        $direction = $request->string('direction')->toString();
        $direction = in_array($direction, ['asc', 'desc'], true) ? $direction : 'desc';

        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        $query->orderBy($columns[$sort] ?? $default, $direction);

        return [$query, $sort, $direction, $perPage];
    }
}
