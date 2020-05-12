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

trait HasAnnotations
{
    /**
     * @var string|null
     */
    protected $title;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var mixed
     */
    protected $default;

    /**
     * @var array<mixed>
     */
    protected $examples;

    public function entitled(string $title): self
    {
        $cp = clone $this;

        $cp->title = $title;

        return $cp;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function describedAs(string $description): self
    {
        $cp = clone $this;

        $cp->description = $description;

        return $cp;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    /**
     * @param mixed $default
     */
    public function withDefault($default): self
    {
        $cp = clone $this;

        $cp->default = $default;

        return $cp;
    }

    /**
     * @return mixed
     */
    public function defaultValue()
    {
        return $this->default;
    }

    public function withExamples(...$examples): self
    {
        $cp = clone $this;

        $cp->examples = $examples;

        return $cp;
    }

    /**
     * @return array<mixed>
     */
    public function examples(): array
    {
        return $this->examples;
    }

    public function annotations(): array
    {
        $annotations = [];

        if (null !== $this->title) {
            $annotations['title'] = $this->title;
        }

        if (null !== $this->description) {
            $annotations['description'] = $this->description;
        }

        if (null !== $this->default) {
            $annotations['default'] = $this->default;
        }

        if (null !== $this->examples) {
            $annotations['examples'] = $this->examples;
        }

        return $annotations;
    }
}
