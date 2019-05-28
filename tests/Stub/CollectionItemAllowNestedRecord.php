<?php
declare(strict_types=1);

namespace EventEngineTest\JsonSchema\Stub;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\JsonSchemaAwareRecordLogic;

final class CollectionItemAllowNestedRecord implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    /**
     * @var ScalarPropsRecordAllowNestedCollection
     */
    private $friends;

    /**
     * @return ScalarPropsRecordAllowNestedCollection
     */
    public function friends(): ScalarPropsRecordAllowNestedCollection
    {
        return $this->friends;
    }

    private static function __allowNestedSchema(): bool
    {
        return true;
    }
}
