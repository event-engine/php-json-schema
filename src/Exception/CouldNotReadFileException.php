<?php

/**
 * This file is part of event-engine/php-json-schema.
 * (c) 2018-2021 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace EventEngine\JsonSchema\Exception;

final class CouldNotReadFileException extends RuntimeException
{
    public static function forFile(string $file): self
    {
        return new self(
            \sprintf('Could not read "%s" file.', $file)
        );
    }
}
