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

use EventEngine\JsonSchema\Type\EnumType;
use EventEngineTest\JsonSchema\BasicTestCase;

final class EnumTypeTest extends BasicTestCase
{
    /**
     * @test
     */
    public function it_creates_enum_type()
    {
        $enumType = new EnumType('a', 'b');

        $this->assertEquals(
            [
                'type' => 'string',
                'enum' => ['a', 'b'],
            ],
            $enumType->toArray()
        );
    }

    /**
     * @test
     */
    public function it_creates_nullable_enum_type()
    {
        $enumType = (new EnumType('a', 'b'))->asNullable();

        $this->assertEquals(
            [
                'type' => ['string', 'null'],
                'enum' => ['a', 'b', null]
            ],
            $enumType->toArray()
        );
    }
}
