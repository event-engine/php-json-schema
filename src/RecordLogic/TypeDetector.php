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
            $validation = is_callable([$classOrType, 'validationRules'])? \call_user_func([$classOrType, 'validationRules']) : null;
            return JsonSchema::array(\call_user_func([$classOrType, '__itemSchema']), $validation);
        }

        if($scalarSchemaType = self::determineScalarTypeIfPossible($classOrType)) {
            return $scalarSchemaType;
        }

        return self::convertClassToType($classOrType);
    }

    private static function determineScalarTypeIfPossible(string $class): ?Type
    {
        if(is_callable([$class, 'fromString'])) {
            $validation = is_callable([$class, 'validationRules'])? \call_user_func([$class, 'validationRules']) : null;
            return JsonSchema::string($validation);
        }

        if(is_callable([$class, 'fromInt'])) {
            $validation = is_callable([$class, 'validationRules'])? \call_user_func([$class, 'validationRules']) : null;
            return JsonSchema::integer($validation);
        }

        if(is_callable([$class, 'fromFloat'])) {
            $validation = is_callable([$class, 'validationRules'])? \call_user_func([$class, 'validationRules']) : null;
            return JsonSchema::float($validation);
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
