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

use EventEngine\JsonSchema\Exception\OpisJsonValidationError;
use Opis\JsonSchema\Helper as OpisHelper;
use Opis\JsonSchema\Validator;

class OpisJsonSchema extends AbstractJsonSchema
{
    /**
     * @var Validator
     */
    private static $jsonValidator;

    public function assert(string $objectName, array $data, array $jsonSchema)
    {
        if ($data === [] && JsonSchema::isObjectType($jsonSchema)) {
            $data = new \stdClass();
        }

        if (empty($jsonSchema['properties'])) {
            // properties must be an object
            unset($jsonSchema['properties']);
        }

        $enforcedObjectData = \json_decode(\json_encode($data));

        $schema = $this->jsonValidator()
            ->loader()
            ->loadObjectSchema(OpisHelper::toJSON($jsonSchema));

        $result = $this->jsonValidator()->validate($enforcedObjectData, $schema);

        if (! $result->isValid()) {
            throw OpisJsonValidationError::withError($objectName, $result->error());
        }
    }

    private function jsonValidator(): Validator
    {
        if (null === self::$jsonValidator) {
            self::$jsonValidator = new Validator();
        }

        return self::$jsonValidator;
    }
}