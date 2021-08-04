<?php

/**
 * This file is part of event-engine/php-json-schema.
 * (c) 2018-2021 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace EventEngine\JsonSchema;

use EventEngine\JsonSchema\Exception\CouldNotReadFileException;
use EventEngine\Schema\InputTypeSchema;
use EventEngine\Schema\PayloadSchema;
use EventEngine\Schema\ResponseTypeSchema;
use const JSON_THROW_ON_ERROR;

final class JsonSchemaArray implements PayloadSchema, ResponseTypeSchema, InputTypeSchema
{
    private array $schema;

    public static function fromFile(string $file, int $flags = JSON_THROW_ON_ERROR, int $depth = 512): self
    {
        if (! \file_exists($file) || ! \is_readable($file)) {
            throw CouldNotReadFileException::forFile($file);
        }

        return self::fromString(\file_get_contents($file), $depth, $flags);
    }

    public static function fromString(string $jsonSchema, int $flags = JSON_THROW_ON_ERROR, int $depth = 512): self
    {
        return new self(
            \json_decode($jsonSchema, true, $depth, $flags)
        );
    }

    public function __construct(array $schema)
    {
        $this->schema = $schema;
    }

    public function toArray(): array
    {
        return $this->schema;
    }
}
