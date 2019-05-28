<?php
declare(strict_types=1);

namespace EventEngineTest\JsonSchema;

use EventEngine\JsonSchema\JsonSchema;
use EventEngine\JsonSchema\Type\TypeRef;
use EventEngineTest\JsonSchema\Stub\ArrayItemRecord;
use EventEngineTest\JsonSchema\Stub\CollectionItemAllowNestedRecord;
use EventEngineTest\JsonSchema\Stub\CollectionItemRecord;
use EventEngineTest\JsonSchema\Stub\NullableArrayItemRecordAllowNestedRecord;
use EventEngineTest\JsonSchema\Stub\NullableArrayItemRecordRecord;
use EventEngineTest\JsonSchema\Stub\NullableScalarPropsRecord;
use EventEngineTest\JsonSchema\Stub\ScalarPropsRecord;
use EventEngineTest\JsonSchema\Stub\VoOptionalPropsRecord;
use EventEngineTest\JsonSchema\Stub\VoPropsRecord;

final class JsonSchemaAwareRecordLogicTest extends BasicTestCase
{
    /**
     * @test
     */
    public function it_can_deal_with_scalar_prop_types()
    {
        $schema = ScalarPropsRecord::__schema();

        $expected = JsonSchema::object([
            'userId' => JsonSchema::string(),
            'age' => JsonSchema::integer(),
            'member' => JsonSchema::boolean(),
            'score' => JsonSchema::float()
        ]);

        $this->assertEquals($expected->toArray(), $schema->toArray());
    }

    /**
     * @test
     */
    public function it_marks_props_as_nullable()
    {
        $schema = NullableScalarPropsRecord::__schema();

        $expected = JsonSchema::object([
            'userId' => JsonSchema::string()->asNullable(),
            'age' => JsonSchema::integer()->asNullable(),
            'member' => JsonSchema::boolean()->asNullable(),
            'score' => JsonSchema::float()->asNullable()
        ]);

        $this->assertEquals($expected->toArray(), $schema->toArray());
    }

    /**
     * @test
     */
    public function it_handles_array_item()
    {
        $schema = ArrayItemRecord::__schema();

        $expected = JsonSchema::object([
            'friends' => JsonSchema::array(JsonSchema::string())
        ]);

        $this->assertEquals($expected->toArray(), $schema->toArray());
    }

    /**
     * @test
     */
    public function it_handles_nullable_array_item_record()
    {
        $schema = NullableArrayItemRecordRecord::__schema();

        $expected = JsonSchema::object([
            'friends' => JsonSchema::array(JsonSchema::typeRef(ScalarPropsRecord::class))->asNullable()
        ]);

        $this->assertEquals($expected->toArray(), $schema->toArray());

        $schema = NullableArrayItemRecordAllowNestedRecord::__schema();

        $expected = JsonSchema::object([
            'friends' => JsonSchema::array(JsonSchema::object([
                'userId' => JsonSchema::string(),
                'age' => JsonSchema::integer(),
                'member' => JsonSchema::boolean(),
                'score' => JsonSchema::float(),
            ]))->asNullable()
        ]);

        $this->assertEquals($expected->toArray(), $schema->toArray());
    }

    /**
     * @test
     */
    public function it_uses_item_schema_from_collection()
    {
        $schema = CollectionItemRecord::__schema();

        $expected = JsonSchema::object([
            'friends' => JsonSchema::array(JsonSchema::typeRef(ScalarPropsRecord::__type()))
        ]);

        $this->assertEquals($expected->toArray(), $schema->toArray());

        $schema = CollectionItemAllowNestedRecord::__schema();

        $expected = JsonSchema::object([
            'friends' => JsonSchema::array(JsonSchema::object([
                'userId' => JsonSchema::string(),
                'age' => JsonSchema::integer(),
                'member' => JsonSchema::boolean(),
                'score' => JsonSchema::float(),
            ]))
        ]);

        $this->assertEquals($expected->toArray(), $schema->toArray());
    }

    /**
     * @test
     */
    public function it_detects_scalar_types_through_method_analysis_of_vo_classes()
    {
        $schema = VoPropsRecord::__schema();

        $expected = JsonSchema::object([
            'userId' => JsonSchema::string(),
            'age' => JsonSchema::integer(),
            'member' => JsonSchema::boolean(),
            'score' => JsonSchema::float()
        ]);

        $this->assertEquals($expected->toArray(), $schema->toArray());
    }

    /**
     * @test
     */
    public function it_respects_optional_properties()
    {
        $schema = VoOptionalPropsRecord::__schema();

        $expected = JsonSchema::object([
            'userId' => JsonSchema::string(),
            'age' => JsonSchema::integer(),
            'member' => JsonSchema::boolean(),
        ], [
            'score' => JsonSchema::float()
        ]);

        $this->assertEquals($expected->toArray(), $schema->toArray());
    }
}
