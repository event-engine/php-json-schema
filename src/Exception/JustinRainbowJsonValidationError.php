<?php
/**
 * This file is part of event-engine/php-json-schema.
 * (c) 2018-2019 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace EventEngine\JsonSchema\Exception;


class JustinRainbowJsonValidationError extends JsonValidationError
{
    /**
     * @var array
     */
    private $errors;

    public static function withError(string $objectName, array ...$errors): JustinRainbowJsonValidationError
    {
        $self = new self('Validation of "' . $objectName . '" failed: ');
        $self->errors = $errors;

        $self->message .= \array_reduce(
            $errors,
            static function ($message, array $error) use ($self) {
                return $message . "\n" . $self->errorMessage($error);
            }
        );

        return $self;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    private function errorMessage(array $error): string
    {
        $dataPointer = $error['pointer'];

        if ($dataPointer === '') {
            return \sprintf('[%s] %s', $error['constraint'], $error['message']);
        }

        return \sprintf('field "%s" [%s] %s',
            $error['property'],
            $error['constraint'],
            $error['message']
        );

//        return sprintf('field "%s" [%s] %s', $error['constraint'], $error['property'], $error['message']);
    }
}
