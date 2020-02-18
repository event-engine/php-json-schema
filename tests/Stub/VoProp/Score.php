<?php
declare(strict_types=1);

namespace EventEngineTest\JsonSchema\Stub\VoProp;

use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\FloatType;

final class Score implements ProvidesValidationRules
{
    private float $score;

    public static function validationRules(): array
    {
        return [FloatType::MINIMUM => 0.1, FloatType::MAXIMUM => 1];
    }

    public static function fromFloat(float $score): self
    {
        return new self($score);
    }

    private function __construct(float $score)
    {
        $this->score = $score;
    }

    public function toFloat(): float
    {
        return $this->score;
    }

    public function equals($other): bool
    {
        if(!$other instanceof self) {
            return false;
        }

        return $this->score === $other->score;
    }

    public function __toString(): string
    {
        return (string)$this->score;
    }
}
