<?php
declare(strict_types=1);

namespace EventEngineTest\JsonSchema\Stub;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\JsonSchemaAwareRecordLogic;

final class CollectionItemRecord implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    /**
     * @var ScalarPropsRecordCollection
     */
    private $friends;

    /**
     * @return ScalarPropsRecordCollection
     */
    public function friends(): ScalarPropsRecordCollection
    {
        return $this->friends;
    }
}
