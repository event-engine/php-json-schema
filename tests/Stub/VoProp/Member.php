<?php
declare(strict_types=1);

namespace EventEngineTest\JsonSchema\Stub\VoProp;

final class Member
{
    private bool $flag;

    public static function fromBool(bool $flag): self
    {
        return new self($flag);
    }

    private function __construct(bool $flag)
    {
        $this->flag = $flag;
    }

    public function toBool(): bool
    {
        return $this->flag;
    }

    public function equals($other): bool
    {
        if(!$other instanceof self) {
            return false;
        }

        return $this->flag === $other->flag;
    }

    public function __toString(): string
    {
        return $this->flag ? 'TRUE' : 'FALSE';
    }

}
