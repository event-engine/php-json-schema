<?php

declare(strict_types=1);

namespace EventEngine\JsonSchema\Type;

use EventEngine\JsonSchema\JsonSchema;

class StringEnumType extends EnumType
{
    /**
     * @var string|array
     */
    protected $type = JsonSchema::TYPE_STRING;
}
