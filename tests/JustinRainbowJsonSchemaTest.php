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
use EventEngine\JsonSchema\Exception\JustinRainbowJsonValidationError;
use EventEngine\JsonSchema\JustinRainbowJsonSchema;

class JustinRainbowJsonSchemaTest extends BasicTestCase
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

        $cut = new JustinRainbowJsonSchema();
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
Validation of "myObject" failed: 
[additionalProp] The property unknown is not defined and the definition does not allow additional properties
Msg;

        $cut = new JustinRainbowJsonSchema();
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
Validation of "myObject" failed: 
field "subObject.p1" [required] The property p1 is required
field "subObject.p2" [required] The property p2 is required
field "subObject" [additionalProp] The property unknown is not defined and the definition does not allow additional properties
Msg;


        $cut = new JustinRainbowJsonSchema();
        try {
            $cut->assert('myObject', $data, $this->schema());
        } catch (JustinRainbowJsonValidationError $e) {
            $this->assertSame(400, $e->getCode());
            $this->assertCount(3, $e->errors());
            $this->assertStringStartsWith($expectedMessage, $e->getMessage());
        }
    }
}
