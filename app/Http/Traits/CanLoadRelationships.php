<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CanLoadRelationships
{
    public function loadRelationShips(
        Model|QueryBuilder|EloquentBuilder|HasMany $for,
        ?array $relations = null
    ): Model|QueryBuilder|EloquentBuilder|HasMany  {
        $relations = $relations ?? $this->relations ?? [];

        foreach ($relations as $relation) {
            $this->loadRelation($for, $relation);
        }
        return $for;
    }

    protected function loadRelation($query, string $relation)
    {
        return $query->when(
            $this->shouldIncludeRelation($relation),
            fn ($q) => $query instanceof Model ? $query->load($relation) : $q->with($relation)
        );
    }

    protected function shouldIncludeRelation(string $relation): bool
    {
        $include = request()->query('include');

        if (!$include) {
            return false;
        }

        $relations = array_map('trim', explode(',', $include));

        return in_array($relation, $relations);
    }
}
