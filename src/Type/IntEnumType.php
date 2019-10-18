<?php

declare(strict_types=1);

namespace EventEngine\JsonSchema\Type;

use EventEngine\JsonSchema\JsonSchema;

class IntEnumType extends EnumType
{
    /**
     * @var string|array
     */
    protected $type = JsonSchema::TYPE_INT;

    /**
     * @var int[]
     */
    protected $entries;

    public function __construct(int ...$entries)
    {
        $this->entries = $entries;
    }
}
