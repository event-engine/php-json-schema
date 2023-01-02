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

use EventEngine\JsonSchema\Exception\JsonValidationError;
use EventEngine\JsonSchema\Exception\OpisJsonValidationError;
use EventEngine\JsonSchema\OpisJsonSchema;

class OpisJsonSchemaTest extends BasicTestCase
{
    private function schema(): array
    {
        $schema = <<<'JSON'
{
    "$schema": "http://json-schema.org/draft-07/schema#",
    "type": "object",
    "properties": {
        "name": {
            "type": "string",
            "minLength": 3
        },
        "hasValue": {"type": "boolean"},
        "age": {"type": "number"},
        "subObject": {
            "type": "object",
            "properties": {
                "p1": {
                    "type": "string",
                    "minLength": 3
                },
                "p2": {"type": "boolean"}
            },
            "required": ["p1", "p2"],
            "additionalProperties": false
        },
        "type": {
            "enum": ["Foo", "Bar", "Baz"]
        }
    },
    "required": ["name", "hasValue", "age", "type"],
    "additionalProperties": false
}
JSON;
        return json_decode($schema, true);
    }

    private function validData(): array
    {
        return [
            'name' => 'Tester',
            'hasValue' => true,
            'age' => 40,
            'type' => 'Bar',
        ];
    }

    /**
     * @test
     */
    public function it_validates_json_schema(): void
    {
        $data = $this->validData();

        $cut = new OpisJsonSchema();
        $cut->assert('myObject', $data, $this->schema());
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function it_throws_json_validation_error_exception(): void
    {
        $data = $this->validData();
        $data['unknown'] = 'set';

        $expectedMessage = <<<'Msg'
Validation of "myObject" failed: {
    "valid": false,
    "errors": [
        {
            "keywordLocation": "#\/additionalProperties",
            "instanceLocation": "#",
            "error": "Additional object properties are not allowed: unknown"
        }
    ]
}
Msg;

        $cut = new OpisJsonSchema();
        try {
            $cut->assert('myObject', $data, $this->schema());
        } catch (JsonValidationError $e) {
            $this->assertSame(400, $e->getCode());
            $this->assertStringStartsWith($expectedMessage, $e->getMessage());
        }
    }

    /**
     * @test
     */
    public function it_throws_justin_rainbow_json_validation_error_exception(): void
    {
        $data = $this->validData();
        $data['subObject']['unknown'] = 'set';

        $expectedMessage = <<<'Msg'
Validation of "myObject" failed: {
    "valid": false,
    "errors": [
        {
            "keywordLocation": "#\/properties",
            "instanceLocation": "#",
            "error": "The properties must match schema: subObject"
        },
        {
            "keywordLocation": "#\/properties\/subObject\/required",
            "instanceLocation": "#\/subObject",
            "error": "The required properties (p1) are missing"
        }
    ]
}
Msg;

        $cut = new OpisJsonSchema();
        try {
            $cut->assert('myObject', $data, $this->schema());
        } catch (OpisJsonValidationError $e) {
            $this->assertSame(400, $e->getCode());
            $this->assertCount(1, $e->errors());
            $this->assertStringStartsWith($expectedMessage, $e->getMessage());
        }
    }
}
