<?php
declare(strict_types=1);

namespace EventEngineTest\JsonSchema\Stub;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\JsonSchemaAwareRecordLogic;

final class CollectionItemRecord implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private ScalarPropsRecordCollection $friends;

    public function friends(): ScalarPropsRecordCollection
    {
        return $this->friends;
    }
}
