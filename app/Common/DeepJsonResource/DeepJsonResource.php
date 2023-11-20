<?php

namespace App\Common\DeepJsonResource;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\Support\Str;
use JsonSerializable;
use TypeError;
use Exception;

/**
 * The abstract class DeepJsonResource is intended for representing model data in JSON format.
 * It inherits JsonResource from Laravel, providing additional functionality for controlling
 * the representation of model data. Key features include:
 */
abstract class DeepJsonResource extends JsonResource
{
    public const ALL_FIELDS = ['*'];

    protected DeepJsonTransformer $transformer;

    public function __construct(
        $resource,
        protected array $castsMap = [],
        ?DeepJsonTransformer $transformer = null,
    ) {
        parent::__construct($resource);

        if ($transformer === null) {
            $this->transformer = resolve(DeepJsonTransformer::class);
        }
    }

    abstract protected function getAllowFields(): array;

    public function toArray(Request $request): array|JsonSerializable|Arrayable
    {
        return $this->objectToArray($request, $this);
    }

    protected function getFields($object, $checkAllowKeys = true): array|JsonSerializable|Arrayable {
        $fields = $this->getAllowFields();

        if ($fields === self::ALL_FIELDS) {
            $checkAllowKeys = false;
        }

        if ($checkAllowKeys === false) {
            $fields = $this->getObjectKeys($object);
        }

        return $fields;
    }

    protected function objectToArray(Request $request, $object, $checkAllowKeys = true): array
    {
        $fields = $this->getFields($object->resource, $checkAllowKeys);

        $isModelOrResourceModel = $this->isModelOrResourceModel($object);

        $result = [];
        foreach ($fields as $field) {
            try {
                $value = $this->transformer->getObjectValueByKey($object, $field);
            } catch (ItemNotFoundException) {
                if ($checkAllowKeys === true) {
                    continue;
                }

                $value = null;
            }

            if ($isModelOrResourceModel) {
                $field = Str::snake($field);
            }

            $result[$field] = $this->transformer->valueToSimpleType($value, $this->castsMap);
        }
        return $result;
    }

    protected function getObjectKeys($object): array
    {
        if ($object instanceof Model) {
            return ModelTransformerHelper::getModelKeys($object);
        }

        if (is_array($object)) {
            return array_keys($object);
        }

        if (is_iterable($object)) {
            return array_keys(iterator_to_array($object));
        }

        if (is_object($object)) {
            return array_keys((array) $object);
        }

        throw new TypeError('Expect: model, array or iterable');
    }

    protected function isModelOrResourceModel($value): bool {
        if ($value instanceof Model) {
            return true;
        }

        if ($value instanceof DeepJsonResource && $value->resource instanceof Model) {
            return true;
        }

        return false;
    }
}
