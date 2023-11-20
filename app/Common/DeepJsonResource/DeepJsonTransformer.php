<?php

namespace App\Common\DeepJsonResource;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ItemNotFoundException;

class DeepJsonTransformer
{

    public function __construct(
        protected array $castsMap = [],
    ) {
        $this->validateCastClasses($castsMap);
    }

    public function valueToSimpleType($value, array $castsMap = [])
    {
        $this->validateCastClasses($castsMap);

        if (is_iterable($value)) {
            $result = [];
            foreach ($value as $itemKey => $itemValue) {
                $result[$itemKey] = $this->valueToSimpleType($itemValue, $castsMap);
            }
            return $result;
        }

        if ($value instanceof DeepJsonResource) {
            return $value;
        }

        $castsMap = array_merge(
            $this->castsMap,
            $castsMap,
        );

        $valueClass = $this->getValueClassOrType($value);
        $autoCastClass = $castsMap[$valueClass] ?? null;

        if ($autoCastClass !== null) {
            return new $autoCastClass($value, $castsMap);
        } elseif ($value instanceof Arrayable) {
            return $value->toArray();
        } elseif (is_object($value)) {
            return (array) $value;
        }

        return $value;
    }

    public function getObjectValueByKey($object, string $key)
    {
        if ($object instanceof JsonResource && $object->resource instanceof Model) {
            return ModelTransformerHelper::getValueByKey($object->resource, $key);
        }

        if ($object instanceof JsonResource) {
            $object = $object->resource;
        }

        // IMPORTANT!!!
        // `$object->{$key} ?? $object[$key]` NOT WORKING CORRECTLY
        if (is_object($object)) {
            return $object->{$key};
        } elseif (is_iterable($object)) {
            return $object[$key];
        }

        throw new ItemNotFoundException();
    }

    protected function getValueClassOrType($value): string {
        if (is_object($value)) {
            return get_class($value);
        }

        return gettype($value);
    }

    protected function validateCastClasses(array $castClasses) {
        foreach ($castClasses as $castClass) {
            if (!class_exists($castClass)) {
                throw new InvalidArgumentException("Cast class '$castClass' not exist!");
            }

            if (!is_subclass_of($castClass, DeepJsonResource::class)) {
                throw new InvalidArgumentException("Cast class '$castClass' must be instance of DeepJsonResource!");
            }
        }
    }
}
