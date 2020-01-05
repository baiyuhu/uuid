<?php

declare(strict_types=1);

namespace Ramsey\Uuid\Test\Converter\Time;

use Ramsey\Uuid\Converter\Time\BigNumberTimeConverter;
use Ramsey\Uuid\Exception\InvalidArgumentException;
use Ramsey\Uuid\Test\TestCase;

class BigNumberTimeConverterTest extends TestCase
{
    public function testCalculateTimeReturnsArrayOfTimeSegments(): void
    {
        $this->skip64BitTest();

        $seconds = 5;
        $microSeconds = 3;
        $calculatedTime = ($seconds * 10000000) + ($microSeconds * 10) + 0x01b21dd213814000;

        $expectedArray = [
            'low' => sprintf('%08x', $calculatedTime & 0xffffffff),
            'mid' => sprintf('%04x', ($calculatedTime >> 32) & 0xffff),
            'hi' => sprintf('%04x', ($calculatedTime >> 48) & 0x0fff),
        ];

        $converter = new BigNumberTimeConverter();
        $returned = $converter->calculateTime((string) $seconds, (string) $microSeconds);

        $this->assertSame($expectedArray, $returned);
    }

    public function testConvertTime(): void
    {
        $this->skip64BitTest();

        $converter = new BigNumberTimeConverter();
        $returned = $converter->convertTime('135606608744910000');

        $this->assertSame('1341368074', $returned);
    }

    public function testCalculateTimeThrowsExceptionWhenSecondsIsNotOnlyDigits(): void
    {
        $converter = new BigNumberTimeConverter();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$seconds must contain only digits');

        $converter->calculateTime('12.34', '5678');
    }

    public function testCalculateTimeThrowsExceptionWhenMicroSecondsIsNotOnlyDigits(): void
    {
        $converter = new BigNumberTimeConverter();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$microSeconds must contain only digits');

        $converter->calculateTime('1234', '56.78');
    }

    public function testConvertTimeThrowsExceptionWhenTimestampIsNotOnlyDigits(): void
    {
        $converter = new BigNumberTimeConverter();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$timestamp must contain only digits');

        $converter->convertTime('1234.56');
    }
}
