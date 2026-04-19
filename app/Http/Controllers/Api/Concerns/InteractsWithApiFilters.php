<?php

namespace App\Http\Controllers\Api\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait InteractsWithApiFilters
{
    protected function applySearch(Builder $query, ?string $search, array $columns): Builder
    {
        $search = trim((string) $search);

        if ($search === '') {
            return $query;
        }

        $query->where(function (Builder $searchQuery) use ($columns, $search) {
            foreach ($columns as $index => $column) {
                $method = $index === 0 ? 'where' : 'orWhere';
                $searchQuery->{$method}($column, 'like', '%' . $search . '%');
            }
        });

        return $query;
    }

    protected function applyBooleanFilter(
        Builder $query,
        Request $request,
        string $parameter,
        ?string $column = null,
        ?bool $default = null
    ): Builder {
        $column ??= $parameter;

        if ($request->filled($parameter)) {
            return $query->where($column, $request->boolean($parameter));
        }

        if (!is_null($default)) {
            return $query->where($column, $default);
        }

        return $query;
    }
}
