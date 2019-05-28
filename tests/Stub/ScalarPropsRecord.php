<?php
declare(strict_types=1);

namespace EventEngineTest\JsonSchema\Stub;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\JsonSchemaAwareRecordLogic;

final class ScalarPropsRecord implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    /**
     * @var string
     */
    private $userId;

    /**
     * @var int
     */
    private $age;

    /**
     * @var bool
     */
    private $member;

    /**
     * @var float
     */
    private $score;

    /**
     * @return string
     */
    public function userId(): string
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function age(): int
    {
        return $this->age;
    }

    /**
     * @return bool
     */
    public function member(): bool
    {
        return $this->member;
    }

    /**
     * @return float
     */
    public function score(): float
    {
        return $this->score;
    }
}
