<?php
/**
 * This file is part of the event-engine/php-json-schema.
 * (c) 2017-2019 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace EventEngineTest\JsonSchema\Type;

use EventEngine\JsonSchema\Type\IntType;
use EventEngineTest\JsonSchema\BasicTestCase;

final class IntTypeTest extends BasicTestCase
{
    /**
     * @test
     */
    public function it_creates_int_type_with_minimum()
    {
        $floatType = (new IntType())->withMinimum(2);

        $this->assertEquals(
            [
                'type' => 'integer',
                'minimum' => 2,
            ],
            $floatType->toArray()
        );
    }

    /**
     * @test
     */
    public function it_creates_int_type_with_maximum()
    {
        $floatType = (new IntType())->withMaximum(7);

        $this->assertEquals(
            [
                'type' => 'integer',
                'maximum' => 7,
            ],
            $floatType->toArray()
        );
    }

    /**
     * @test
     */
    public function it_creates_int_type_with_range()
    {
        $floatType = (new IntType())->withRange(2, 7);

        $this->assertEquals(
            [
                'type' => 'integer',
                'minimum' => 2,
                'maximum' => 7,
            ],
            $floatType->toArray()
        );
    }

    /**
     * @test
     */
    public function it_creates_int_with_custom_validation_through_constructor()
    {
        $floatType = new IntType(['multipleOf' => 25]);

        $this->assertEquals(
            [
                'type' => 'integer',
                'multipleOf' => 25,
            ],
            $floatType->toArray()
        );
    }
}
