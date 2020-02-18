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

    private UserId $userId;

    private Age $age;

    private Member $member;

    private Score $score;

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

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function age(): Age
    {
        return $this->age;
    }

    public function member(): Member
    {
        return $this->member;
    }

    public function score(): Score
    {
        return $this->score;
    }
}
