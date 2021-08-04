<?php

/**
 * This file is part of event-engine/php-json-schema.
 * (c) 2018-2021 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace EventEngineTest\JsonSchema;

use EventEngine\JsonSchema\Exception\CouldNotReadFileException;
use EventEngine\JsonSchema\JsonSchemaArray;

final class JsonSchemaArrayTest extends BasicTestCase
{
    private const FILE = __DIR__ . DIRECTORY_SEPARATOR . '_files/schema.json';

    /**
     * @test
     */
    public function it_creates_json_schema_from_file(): void
    {
        $cut = JsonSchemaArray::fromFile(self::FILE);
        $this->assertArrayHasKey('properties', $cut->toArray());
    }

    /**
     * @test
     */
    public function it_creates_json_schema_from_string(): void
    {
        $cut = JsonSchemaArray::fromString(\file_get_contents(self::FILE));
        $this->assertArrayHasKey('properties', $cut->toArray());
    }

    /**
     * @test
     */
    public function it_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(CouldNotReadFileException::class);
        JsonSchemaArray::fromFile('unknown.json');
    }
}
