<?php
declare(strict_types=1);

namespace EventEngineTest\JsonSchema;

use EventEngine\JsonSchema\Exception\JsonValidationError;
use EventEngine\JsonSchema\JsonSchema;
use EventEngine\JsonSchema\JsonSchemaArray;
use EventEngine\JsonSchema\OpisJsonSchema;
use EventEngine\Schema\TypeSchemaMap;
use function json_decode;

final class TypeSchemaReferenceTest extends BasicTestCase
{
    private function schema(): array
    {
        $schema = <<<'JSON'
        {
            "$schema": "http://json-schema.org/draft-07/schema#",
            "type": "object",
            "properties": {
                "name": {
                    "$ref": "#/definitions/Name"
                },
                "hasValue": {"type": "boolean"},
                "age": {"type": "number"},
                "subObject": {
                    "type": "object",
                    "properties": {
                        "p1": {
                            "$ref": "#/definitions/SubObject/P1"
                        },
                        "p2": {
                            "$ref": "#/definitions/SubObject/P2"
                        }
                    },
                    "required": ["p1", "p2"],
                    "additionalProperties": false
                },
                "type": {
                    "enum": ["Foo", "Bar", "Baz"]
                }
            },
            "required": ["name", "hasValue", "age", "type", "subObject"],
            "additionalProperties": false
        }
        JSON;
        return json_decode($schema, true);
    }

    private function typeSchemaMap(): TypeSchemaMap
    {
        $typeSchemaMap = new TypeSchemaMap();

        $typeSchemaMap->add('Name', JsonSchema::string(['minLength' => 3]));
        $typeSchemaMap->add('SubObject/P1', JsonSchema::string(['minLength' => 3]));
        $typeSchemaMap->add('SubObject/P2', JsonSchema::boolean());

        return $typeSchemaMap;
    }

    private function validData(): array
    {
        return [
            'name' => 'Tester',
            'hasValue' => true,
            'age' => 40,
            'type' => 'Bar',
            'subObject' => [
                'p1' => 'a sub str',
                'p2' => true
            ]
        ];
    }

    /**
     * @test
     */
    public function it_validates_payload_schema(): void
    {
        $data = $this->validData();

        $cut = new OpisJsonSchema();
        $cut->assertPayload('TestMessage', $data, new JsonSchemaArray($this->schema()), $this->typeSchemaMap());
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function it_throws_json_validation_error_exception(): void
    {
        $data = $this->validData();
        $data['subObject']['p1'] = 'to';

        $expectedMessage = <<<'Msg'
        Validation of "TestMessage payload" failed: field "subObject.p1" [minLength] {
            "min": 3,
            "length": 2
        }
        Msg;

        $cut = new OpisJsonSchema();
        try {
            $cut->assertPayload('TestMessage', $data, new JsonSchemaArray($this->schema()), $this->typeSchemaMap());
        } catch (JsonValidationError $e) {
            $this->assertSame(400, $e->getCode());
            $this->assertStringStartsWith($expectedMessage, $e->getMessage());
        }
    }
}
