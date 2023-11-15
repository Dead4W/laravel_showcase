<?php

namespace App\Common\Http\Resources;


use App\User\Models\User;
use App\User\Resources\UserResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\Support\Str;

/**
 * The abstract class BaseJsonResource is intended for representing model data in JSON format.
 * It inherits JsonResource from Laravel, providing additional functionality for controlling
 * the representation of model data. Key features include:
 */
abstract class BaseJsonResource extends JsonResource
{

    protected array $customCastClassToResourceMap;
    protected array $autoCastClassToResourceMap;

    public function __construct($resource, array $autoCastClassToResourceMap = [])
    {
        parent::__construct($resource);

        $this->customCastClassToResourceMap = $autoCastClassToResourceMap;
        $this->autoCastClassToResourceMap = array_merge(
            self::AUTO_CAST_CLASS_TO_RESOURCES_MAP,
            $autoCastClassToResourceMap,
        );
    }

    /**
     * It has the capability to dynamically pass resources for classes. For example, when returning a Collection of User
     * with internal relations, it automatically converts internal models into a resource using the map
     */
    protected const AUTO_CAST_CLASS_TO_RESOURCES_MAP = [
        User::class => UserResource::class,
    ];

    /**
     * An abstract method for defining the allowed fields of the model that will be included in the JSON representation.
     *
     * @return array<string>
     */
    abstract protected function getAllowFields(): array;

    /**
     * This method converts a model object or resource into an array. It supports filtering fields using `getAllowFields`.
     */
    public function toArray(Request $request)
    {
        return $this->objectToArray($this);
    }

    protected function objectToArray($object, $checkAllowKeys = true)
    {
        $result = [];

        $fields = $this->getAllowFields();

        if ($fields === ['*'] && $checkAllowKeys === true) {
            $object = $this->resource;
            $checkAllowKeys = false;
        }

        if ($checkAllowKeys === false) {
            $fields = $this->getObjectKeysOrNull($object);

            if ($fields === null) {
                return $object;
            }
        }

        foreach ($fields as $field) {
            try {
                $value = $this->getObjectValueByKey($object, $field);
            } catch (ItemNotFoundException $e) {
                if ($checkAllowKeys === true) {
                    continue;
                } else {
                    $value = null;
                }
            }

            $value = $this->valueToSimpleType($value);

            $fieldResult = $field;

            if ($object instanceof Model || ($object instanceof BaseJsonResource && $object->resource instanceof Model)) {
                $fieldResult = Str::snake($field);
            }

            $result[$fieldResult] = $value;
        }

        return $result;
    }

    protected function valueToSimpleType($value)
    {
        $valueClass = is_object($value) ? get_class($value) : gettype($value);
        $autoCast = $this->autoCastClassToResourceMap[$valueClass] ?? null;
        if ($autoCast !== null) {
            if (mb_strpos($autoCast, 'App\\') !== 0) {
                try {
                    $value = $this->getObjectValueByKey($value, $autoCast);
                } catch (ItemNotFoundException $e) {
                    $value = null;
                }
            } else {
                $value = new $autoCast($value, $this->customCastClassToResourceMap);
            }
        }

        if (is_iterable($value) || is_object($value)) {
            $value = $this->objectToArray($value, false);
        }

        return $value;
    }

    protected function getObjectKeysOrNull($object): ?array
    {
        if ($object instanceof Model) {
            $fields = array_keys($object->attributesToArray());

            $relations = $object->relationsToArray();
            foreach ($relations as $relationField => $relationVal) {
                $fields[] = Str::camel($relationField);
            }

            return $fields;
        }

        if (is_array($object)) {
            return array_keys($object);
        }

        if (is_iterable($object)) {
            return array_keys(iterator_to_array($object));
        }

        return null;
    }

    protected function getObjectValueByKey($object, $key)
    {
        if ($object instanceof JsonResource && $object->resource instanceof Model) {
            /** @var Model $model */
            $model = $object->resource;

            if (in_array($key, $model->getMutatedAttributes())) {
                return $model->{$key};
            }

            $attributes = $model->getAttributes() ?? [];
            $attributeKeys = array_merge(
                array_keys($attributes),
                $model->getAppends(),
            );

            if (in_array($key, $attributeKeys)) {
                return $attributes[$key] ?? $object->{$key};
            }

            if ($model->relationLoaded($key)) {
                return $model->{$key};
            } else {
                throw new ItemNotFoundException();
            }
        }

        // IMPORTANT!!!
        // `$object->{$key} ?? $object[$key]` NOT WORKING CORRECTLY
        if (isset($object->{$key})) {
            return $object->{$key};
        } elseif (isset($object[$key])) {
            return $object[$key];
        }

        throw new ItemNotFoundException();
    }
}
