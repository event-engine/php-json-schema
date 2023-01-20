<?php
/**
 * This file is part of event-engine/php-json-schema.
 * (c) 2018-2021 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace EventEngine\JsonSchema;

use EventEngine\Data\ImmutableRecord;
use EventEngine\Data\ImmutableRecordLogic;
use EventEngine\JsonSchema\Exception\InvalidArgumentException;
use EventEngine\JsonSchema\RecordLogic\TypeDetector;
use EventEngine\Schema\TypeSchema;

trait JsonSchemaAwareRecordLogic
{
    use ImmutableRecordLogic;

    public static function __type(): string
    {
        return self::convertClassToTypeName(\get_called_class());
    }

    public static function __schema(): TypeSchema
    {
        return self::generateSchemaFromPropTypeMap();
    }

    /**
     * Override method to return a list property keys that are optional
     *
     * @return array
     */
    private static function __optionalProperties(): array
    {
        return [];
    }

    /**
     * If a property type is a class and that class implements JsonSchemaAwareRecord the resulting JsonSchema
     * for that type can either be a TypeRef (no nested schema allowed - default logic) or an object schema derived
     * from JsonSchemaAwareRecord::__schema (enabled by returning true from the method)
     *
     * @return bool
     */
    private static function __allowNestedSchema(): bool
    {
        return false;
    }

    /**
     * @param array $arrayPropTypeMap Map of array property name to array item type
     * @return Type
     */
    private static function generateSchemaFromPropTypeMap(array $arrayPropTypeMap = []): Type
    {
        if (null === self::$__propTypeMap) {
            self::$__propTypeMap = self::buildPropTypeMap();
        }

        //To keep BC, we cache arrayPropTypeMap internally.
        //New recommended way to provide the map is that one should override the static method  self::arrayPropItemTypeMap()
        //Hence, we check if this method returns a non empty array and only in this case cache the map
        if (\count($arrayPropTypeMap) && ! \count(self::arrayPropItemTypeMap())) {
            self::$__arrayPropItemTypeMap = $arrayPropTypeMap;
        }

        $arrayPropTypeMap = self::getArrayPropItemTypeMapFromMethodOrCache();

        if (null === self::$__schema) {
            $props = [];

            foreach (self::$__propTypeMap as $prop => [$type, $isScalar, $isNullable]) {
                if ($isScalar) {
                    $props[$prop] = JsonSchema::schemaFromScalarPhpType($type, $isNullable);
                    continue;
                }

                if ($type === ImmutableRecord::PHP_TYPE_ARRAY) {
                    if (! \array_key_exists($prop, $arrayPropTypeMap)) {
                        throw new InvalidArgumentException("Missing array item type in array property map. Please provide an array item type for property $prop.");
                    }

                    $arrayItemType = $arrayPropTypeMap[$prop];

                    if (self::isScalarType($arrayItemType)) {
                        $arrayItemSchema = JsonSchema::schemaFromScalarPhpType($arrayItemType, false);
                    } elseif ($arrayItemType === ImmutableRecord::PHP_TYPE_ARRAY) {
                        throw new InvalidArgumentException("Array item type of property $prop must not be 'array', only a scalar type or an existing class can be used as array item type.");
                    } else {
                        $arrayItemSchema = self::getTypeFromClass($arrayItemType);
                    }

                    $props[$prop] = JsonSchema::array($arrayItemSchema);
                } else {
                    $props[$prop] = self::getTypeFromClass($type);
                }

                if ($isNullable) {
                    $props[$prop] = JsonSchema::nullOr($props[$prop]);
                }
            }

            $optionalProps = [];
            foreach (self::__optionalProperties() as $optProp) {
                $optionalProps[$optProp] = $props[$optProp];
                unset($props[$optProp]);
            }

            self::$__schema = JsonSchema::object($props, $optionalProps);
        }

        return self::$__schema;
    }

    private static function convertClassToTypeName(string $class): string
    {
        return \substr(\strrchr($class, '\\'), 1);
    }

    private static function getTypeFromClass(string $classOrType): Type
    {
        return TypeDetector::getTypeFromClass($classOrType, self::__allowNestedSchema());
    }

    /**
     * @var TypeSchema
     */
    private static $__schema;
}
