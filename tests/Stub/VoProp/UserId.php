<?php
declare(strict_types=1);

namespace EventEngineTest\JsonSchema\Stub\VoProp;

use EventEngine\JsonSchema\ProvidesValidationRules;

final class UserId implements ProvidesValidationRules
{
    public const PATTERN = '^[0-9]+$';

    private $userId;

    public static function validationRules(): array
    {
        return ['pattern' => self::PATTERN];
    }

    public static function fromString(string $userId): self
    {
        return new self($userId);
    }

    private function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public function toString(): string
    {
        return $this->userId;
    }

    public function equals($other): bool
    {
        if(!$other instanceof self) {
            return false;
        }

        return $this->userId === $other->userId;
    }

    public function __toString(): string
    {
        return $this->userId;
    }

}
