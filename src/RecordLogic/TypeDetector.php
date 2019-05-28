<?php
declare(strict_types=1);

namespace EventEngine\JsonSchema\RecordLogic;

use EventEngine\JsonSchema\JsonSchema;
use EventEngine\JsonSchema\JsonSchemaAwareCollection;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\Type;

final class TypeDetector
{
    public static function getTypeFromClass(string $classOrType, bool $allowNestedSchema = true): Type
    {
        if (! \class_exists($classOrType)) {
            return JsonSchema::typeRef($classOrType);
        }

        $refObj = new \ReflectionClass($classOrType);

        if ($refObj->implementsInterface(JsonSchemaAwareRecord::class)) {

            if($allowNestedSchema) {
                return \call_user_func([$classOrType, '__schema']);
            }

            return new Type\TypeRef(\call_user_func([$classOrType, '__type']));
        }

        if($refObj->implementsInterface(JsonSchemaAwareCollection::class)) {
            return JsonSchema::array(\call_user_func([$classOrType, '__itemSchema']));
        }

        if($scalarSchemaType = self::determineScalarTypeIfPossible($classOrType)) {
            return $scalarSchemaType;
        }

        return self::convertClassToType($classOrType);
    }

    private static function determineScalarTypeIfPossible(string $class): ?Type
    {
        if(is_callable([$class, 'fromString'])) {
            return JsonSchema::string();
        }

        if(is_callable([$class, 'fromInt'])) {
            return JsonSchema::integer();
        }

        if(is_callable([$class, 'fromFloat'])) {
            return JsonSchema::float();
        }

        if(is_callable([$class, 'fromBool'])) {
            return JsonSchema::boolean();
        }

        return null;
    }

    private static function convertClassToType(string $class): Type
    {
        return new Type\TypeRef(\substr(\strrchr($class, '\\'), 1));
    }
}
