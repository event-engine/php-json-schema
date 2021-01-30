<?php
/**
 * This file is part of event-engine/php-json-schema.
 * (c) 2018-2021 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace EventEngine\JsonSchema\Type;

use EventEngine\JsonSchema\AnnotatedType;
use EventEngine\JsonSchema\JsonSchema;
use EventEngine\JsonSchema\Type;
use EventEngine\Schema\PayloadSchema;
use EventEngine\Schema\TypeSchema;

final class ArrayType implements AnnotatedType, PayloadSchema
{
    use NullableType,
        HasAnnotations;

    public const MAX_ITEMS = 'maxItems';
    public const MIN_ITEMS = 'minItems';
    public const UNIQUE_ITEMS = 'uniqueItems';
    public const CONTAINS = 'contains';

    /**
     * @var string|array
     */
    private $type = JsonSchema::TYPE_ARRAY;

    /**
     * @var TypeSchema
     */
    private $itemSchema;

    /**
     * @var null|array
     */
    private $validation;

    public function __construct(TypeSchema $itemSchema, array $validation = null)
    {
        $this->itemSchema = $itemSchema;
        $this->validation = $validation;
    }

    public function withMaxItems(int $maxItems): self
    {
        $cp = clone $this;

        $validation = (array) $this->validation;

        $validation[self::MAX_ITEMS] = $maxItems;

        $cp->validation = $validation;

        return $cp;
    }

    public function withMinItems(int $minItems): self
    {
        $cp = clone $this;

        $validation = (array) $this->validation;

        $validation[self::MIN_ITEMS] = $minItems;

        $cp->validation = $validation;

        return $cp;
    }

    public function withUniqueItems(): self
    {
        $cp = clone $this;

        $validation = (array) $this->validation;

        $validation[self::UNIQUE_ITEMS] = true;

        $cp->validation = $validation;

        return $cp;
    }

    public function withContains(TypeSchema $itemSchema): self
    {
        $cp = clone $this;

        $validation = (array) $this->validation;

        $validation[self::CONTAINS] = $itemSchema->toArray();

        $cp->validation = $validation;

        return $cp;
    }

    public function toArray(): array
    {
        return \array_merge([
            'type' => $this->type,
            'items' => $this->itemSchema->toArray(),
        ], (array) $this->validation, $this->annotations());
    }
}
