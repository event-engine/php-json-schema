<?php
declare(strict_types=1);

namespace EventEngineTest\JsonSchema\Stub;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\JsonSchemaAwareRecordLogic;

final class NullableArrayItemRecordAllowNestedRecord implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private static function arrayPropItemTypeMap(): array
    {
        return ['friends' => ScalarPropsRecord::class];
    }

    private static function __allowNestedSchema(): bool
    {
        return true;
    }


    /**
     * @var ScalarPropsRecord[]|null
     */
    private ?array $friends;

    /**
     * @return ScalarPropsRecord[]|null
     */
    public function friends(): ?array
    {
        return $this->friends;
    }
}
