<?php
/**
 * This file is part of event-engine/php-json-schema.
 * (c) 2018-2021 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace EventEngine\JsonSchema;

interface AnnotatedType extends Type
{
    public function entitled(string $title);

    public function describedAs(string $description);

    public function withDefault($default);

    public function withExamples(...$examples);
}
