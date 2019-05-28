<?php
declare(strict_types=1);

namespace EventEngineTest\JsonSchema\Stub;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\JsonSchemaAwareRecordLogic;

final class ArrayItemRecord implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private static function arrayPropItemTypeMap(): array
    {
        return ['friends' => 'string'];
    }

    /**
     * @var string[]
     */
    private $friends;

    /**
     * @return string[]
     */
    public function friends(): array
    {
        return $this->friends;
    }
}
