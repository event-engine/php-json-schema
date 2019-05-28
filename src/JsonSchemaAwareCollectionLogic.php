<?php
declare(strict_types=1);

namespace EventEngine\JsonSchema;

use EventEngine\Data\ImmutableRecord;
use EventEngine\JsonSchema\RecordLogic\TypeDetector;
use EventEngine\Schema\TypeSchema;

trait JsonSchemaAwareCollectionLogic
{
    private static function __itemType(): ?string
    {
        return null;
    }

    public static function __itemSchema(): TypeSchema
    {
        if(null === self::$__itemSchema) {
            $itemType = self::__itemType();

            if(null === $itemType) {
                return JsonSchema::any();
            }

            if(self::isScalarType($itemType)) {
                return JsonSchema::schemaFromScalarPhpType($itemType, false);
            }

            self::$__itemSchema = TypeDetector::getTypeFromClass($itemType, self::__allowNestedSchema());
        }

        return self::$__itemSchema;
    }

    private static function isScalarType(string $type): bool
    {
        switch ($type) {
            case ImmutableRecord::PHP_TYPE_STRING:
            case ImmutableRecord::PHP_TYPE_INT:
            case ImmutableRecord::PHP_TYPE_FLOAT:
            case ImmutableRecord::PHP_TYPE_BOOL:
                return true;
            default:
                return false;
        }
    }

    /**
     * If item type is a class and that class implements JsonSchemaAwareRecord the resulting JsonSchema
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
     * Static item schema cache
     *
     * @var TypeSchema
     */
    private static $__itemSchema;
}
