<?php
declare(strict_types=1);

namespace EventEngineTest\JsonSchema\Stub;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\JsonSchemaAwareRecordLogic;
use EventEngineTest\JsonSchema\Stub\VoProp\Age;
use EventEngineTest\JsonSchema\Stub\VoProp\Member;
use EventEngineTest\JsonSchema\Stub\VoProp\Score;
use EventEngineTest\JsonSchema\Stub\VoProp\UserId;

final class VoOptionalPropsRecord implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    public const USER_ID = 'userId';
    public const AGE = 'age';
    public const MEMBER = 'member';
    public const SCORE = 'score';

    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var Age
     */
    private $age;

    /**
     * @var Member
     */
    private $member;

    /**
     * @var Score
     */
    private $score;

    private static function __optionalProperties(): array
    {
        return [self::SCORE];
    }

    private function init(): void
    {
        if(null === $this->score) {
            $this->score = Score::fromFloat(1.0);
        }
    }

    /**
     * @return UserId
     */
    public function userId(): UserId
    {
        return $this->userId;
    }

    /**
     * @return Age
     */
    public function age(): Age
    {
        return $this->age;
    }

    /**
     * @return Member
     */
    public function member(): Member
    {
        return $this->member;
    }

    /**
     * @return Score
     */
    public function score(): Score
    {
        return $this->score;
    }
}
