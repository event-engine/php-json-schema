<?php
declare(strict_types=1);

namespace EventEngineTest\JsonSchema\Stub;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\JsonSchemaAwareRecordLogic;

final class NullableScalarPropsRecord implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    /**
     * @var string|null
     */
    private $userId;

    /**
     * @var int|null
     */
    private $age;

    /**
     * @var bool|null
     */
    private $member;

    /**
     * @var float|null
     */
    private $score;

    /**
     * @return string|null
     */
    public function userId(): ?string
    {
        return $this->userId;
    }

    /**
     * @return int|null
     */
    public function age(): ?int
    {
        return $this->age;
    }

    /**
     * @return bool|null
     */
    public function member(): ?bool
    {
        return $this->member;
    }

    /**
     * @return float|null
     */
    public function score(): ?float
    {
        return $this->score;
    }
}
