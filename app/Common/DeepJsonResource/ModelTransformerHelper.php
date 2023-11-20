<?php

namespace App\Common\DeepJsonResource;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\Support\Str;

class ModelTransformerHelper
{

    static function getModelKeys(Model $model): array {
        $fields = array_keys(
            $model->attributesToArray()
        );

        $relations = $model->relationsToArray();
        foreach ($relations as $relationField => $relationVal) {
            $fields[] = Str::camel($relationField);
        }

        return $fields;
    }

    static function getValueByKey(Model $model, string $key): mixed {
        if (in_array($key, $model->getMutatedAttributes())) {
            return $model->{$key};
        }

        $attributes = $model->getAttributes() ?? [];
        $attributeKeys = array_merge(
            array_keys($attributes),
            $model->getAppends(),
        );

        if (in_array($key, $attributeKeys)) {
            return $attributes[$key] ?? $model->{$key};
        }

        if ($model->relationLoaded($key)) {
            return $model->{$key};
        } else {
            throw new ItemNotFoundException();
        }
    }

}
