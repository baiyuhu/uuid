<?php

declare(strict_types=1);

namespace Ramsey\Uuid\Test\Type;

use Ramsey\Uuid\Test\TestCase;
use Ramsey\Uuid\Type\Time;

class TimestampTest extends TestCase
{
    /**
     * @param int|float|string $seconds
     * @param int|float|string|null $microSeconds
     *
     * @dataProvider provideTimestampValues
     */
    public function testTimestamp($seconds, $microSeconds): void
    {
        $params = [$seconds];

        if ($microSeconds !== null) {
            $params[] = $microSeconds;
        }

        $timestamp = new Time(...$params);

        $this->assertSame((string) $seconds, $timestamp->getSeconds()->toString());

        $this->assertSame(
            (string) $microSeconds ?: '0',
            $timestamp->getMicroSeconds()->toString()
        );
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification
     */
    public function provideTimestampValues(): array
    {
        return [
            [
                'seconds' => 103072857659,
                'microSeconds' => null,
            ],
            [
                'seconds' => -12219292800,
                'microSeconds' => 1234,
            ],
        ];
    }
}
