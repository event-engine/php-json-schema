<?php
/**
 * This file is part of event-engine/php-json-schema.
 * (c) 2018-2019 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace EventEngine\JsonSchema\Type;

use EventEngine\JsonSchema\AnnotatedType;
use EventEngine\JsonSchema\JsonSchema;
use EventEngine\JsonSchema\Type;

class EnumType implements AnnotatedType
{
    use NullableType,
        HasAnnotations;

    /**
     * @var string|array
     */
    protected $type = JsonSchema::TYPE_STRING;

    /**
     * @var string[]
     */
    protected $entries;

    public function __construct(string ...$entries)
    {
        $this->entries = $entries;
    }

    public function toArray(): array
    {
        return \array_merge([
            'type' => $this->type,
            'enum' => $this->entries,
        ], $this->annotations());
    }

    public function asNullable(): Type
    {
        $cp = clone $this;

        $this->assertTypeCanBeConvertedToNullableType($cp);

        $cp->type = [$cp->type, JsonSchema::TYPE_NULL];
        $cp->entries[] = null;

        return $cp;
    }
}
