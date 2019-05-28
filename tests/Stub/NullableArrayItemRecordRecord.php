<?php
declare(strict_types=1);

namespace EventEngineTest\JsonSchema\Stub;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\JsonSchemaAwareRecordLogic;

final class NullableArrayItemRecordRecord implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private static function arrayPropItemTypeMap(): array
    {
        return ['friends' => ScalarPropsRecord::class];
    }

    /**
     * @var ScalarPropsRecord[]|null
     */
    private $friends;

    /**
     * @return ScalarPropsRecord[]|null
     */
    public function friends(): ?array
    {
        return $this->friends;
    }
}
