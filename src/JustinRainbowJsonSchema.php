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

use EventEngine\JsonSchema\Exception\JustinRainbowJsonValidationError;
use JsonSchema\Validator;

final class JustinRainbowJsonSchema extends AbstractJsonSchema
{
    private static $jsonValidator;

    public function assert(string $objectName, array $data, array $jsonSchema)
    {
        if ($data === [] && JsonSchema::isObjectType($jsonSchema)) {
            $data = new \stdClass();
        }

        $enforcedObjectData = \json_decode(\json_encode($data));
        $jsonSchema = \json_decode(\json_encode($jsonSchema));

        $this->jsonValidator()->validate($enforcedObjectData, $jsonSchema);

        if (! $this->jsonValidator()->isValid()) {
            $errors = $this->jsonValidator()->getErrors();
            $this->jsonValidator()->reset();
            throw JustinRainbowJsonValidationError::withError($objectName, ...$errors);
        }

        $this->jsonValidator()->reset();
    }

    private function jsonValidator(): Validator
    {
        if (null === self::$jsonValidator) {
            self::$jsonValidator = new Validator();
        }

        return self::$jsonValidator;
    }
}
