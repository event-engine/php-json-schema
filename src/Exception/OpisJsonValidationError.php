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


use Opis\JsonSchema\ValidationError;

class OpisJsonValidationError extends JsonValidationError
{
    /**
     * @var ValidationError[]
     */
    private $errors;

    public static function withError(string $objectName, ValidationError ...$validationErrors): OpisJsonValidationError
    {
        $self = new self('Validation of "' . $objectName . '" failed: ');
        $self->errors = $validationErrors;

        foreach ($validationErrors as $error) {
            $self->message .= $self->errorMessage($error);

            if ($error->subErrorsCount()) {
                $self->message .= \array_reduce(
                    $error->subErrors(),
                    static function ($message, ValidationError $error) use ($self) {
                        return $message . "\n" . $self->errorMessage($error);
                    }
                );
            }
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

    private function errorMessage(ValidationError $error): string
    {
        $dataPointer = $error->dataPointer();

        if (count($dataPointer) === 0) {
            return \sprintf('[%s] %s', $error->keyword(), \json_encode($error->keywordArgs(), JSON_PRETTY_PRINT));
        }

        return \sprintf('field "%s" [%s] %s',
            implode('.', $dataPointer),
            $error->keyword(),
            \json_encode($error->keywordArgs(), JSON_PRETTY_PRINT)
        );
    }
}
