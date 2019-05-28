<?php
declare(strict_types=1);

namespace EventEngine\JsonSchema;

use EventEngine\Schema\TypeSchema;

interface JsonSchemaAwareCollection
{
    public static function __itemSchema(): TypeSchema;
}
