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


use Opis\JsonSchema\Errors\ErrorFormatter;
use Opis\JsonSchema\Errors\ValidationError;

class OpisJsonValidationError extends JsonValidationError
{
    /**
     * @var ValidationError[]
     */
    private array $errors;
    private ?ErrorFormatter $errorFormatter = null;

    public static function withError(string $objectName, ValidationError ...$validationErrors): OpisJsonValidationError
    {
        $self = new self('Validation of "' . $objectName . '" failed: ');
        $self->errors = $validationErrors;

        foreach ($validationErrors as $error) {
            $self->message .= $self->errorMessage($error);
        }

        return $self;
    }

    /**
     * @return ValidationError[]
     */
    public function errors(): array
    {
        return $this->errors;
    }

    private function errorFormatter(): ErrorFormatter
    {
        return $this->errorFormatter ??= new ErrorFormatter();
    }

    private function errorMessage(ValidationError $error): string
    {
        return json_encode($this->errorFormatter()->formatOutput($error, "basic"), JSON_PRETTY_PRINT);
    }
}
