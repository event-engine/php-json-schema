<?php
declare(strict_types=1);

namespace EventEngineTest\JsonSchema\Stub;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\JsonSchemaAwareRecordLogic;

final class ScalarPropsRecord implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private string $userId;

    private int $age;

    private bool $member;

    private float $score;

    public function userId(): string
    {
        return $this->userId;
    }

    public function age(): int
    {
        return $this->age;
    }

    public function member(): bool
    {
        return $this->member;
    }

    public function score(): float
    {
        return $this->score;
    }
}
