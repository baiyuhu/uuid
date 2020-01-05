<?php

declare(strict_types=1);

namespace Ramsey\Uuid\Test\Converter\Time;

use AspectMock\Test as AspectMock;
use Brick\Math\BigInteger;
use Ramsey\Uuid\Converter\Time\GmpTimeConverter;
use Ramsey\Uuid\Exception\InvalidArgumentException;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Test\TestCase;

class GmpTimeConverterTest extends TestCase
{
    public function testCalculateTimeReturnsArrayOfTimeSegments(): void
    {
        $seconds = BigInteger::of(5);
        $microSeconds = BigInteger::of(3);

        $calculatedTime = BigInteger::zero()
            ->plus($seconds->multipliedBy(10000000))
            ->plus($microSeconds->multipliedBy(10))
            ->plus(BigInteger::fromBase('01b21dd213814000', 16));

        $maskLow = BigInteger::fromBase('ffffffff', 16);
        $maskMid = BigInteger::fromBase('ffff', 16);
        $maskHi = BigInteger::fromBase('0fff', 16);

        $expectedArray = [
            'low' => sprintf('%08s', $calculatedTime->and($maskLow)->toBase(16)),
            'mid' => sprintf('%04s', $calculatedTime->shiftedRight(32)->and($maskMid)->toBase(16)),
            'hi' => sprintf('%04s', $calculatedTime->shiftedRight(48)->and($maskHi)->toBase(16)),
        ];

        $converter = new GmpTimeConverter();
        $returned = $converter->calculateTime((string) $seconds, (string) $microSeconds);

        $this->assertSame($expectedArray, $returned);
    }

    public function testConvertTime(): void
    {
        $converter = new GmpTimeConverter();
        $returned = $converter->convertTime('135606608744910000');

        $this->assertSame('1341368074', $returned);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testCalculateTimeThrowsExceptionWhenGmpExtensionNotPresent(): void
    {
        $extensionLoaded = AspectMock::func(
            'Ramsey\Uuid\Converter',
            'extension_loaded',
            false
        );

        $converter = new GmpTimeConverter();

        $this->expectException(UnsatisfiedDependencyException::class);
        $this->expectExceptionMessage('ext-gmp must be present to use this converter');

        $converter->calculateTime('1234', '5678');
        $extensionLoaded->verifyInvokedOnce(['gmp']);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testConvertTimeThrowsExceptionWhenGmpExtensionNotPresent(): void
    {
        $extensionLoaded = AspectMock::func(
            'Ramsey\Uuid\Converter',
            'extension_loaded',
            false
        );

        $converter = new GmpTimeConverter();

        $this->expectException(UnsatisfiedDependencyException::class);
        $this->expectExceptionMessage('ext-gmp must be present to use this converter');

        $converter->convertTime('1234');
        $extensionLoaded->verifyInvokedOnce(['gmp']);
    }

    public function testCalculateTimeThrowsExceptionWhenSecondsIsNotOnlyDigits(): void
    {
        $converter = new GmpTimeConverter();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$seconds must contain only digits');

        $converter->calculateTime('12.34', '5678');
    }

    public function testCalculateTimeThrowsExceptionWhenMicroSecondsIsNotOnlyDigits(): void
    {
        $converter = new GmpTimeConverter();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$microSeconds must contain only digits');

        $converter->calculateTime('1234', '56.78');
    }

    public function testConvertTimeThrowsExceptionWhenTimestampIsNotOnlyDigits(): void
    {
        $converter = new GmpTimeConverter();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$timestamp must contain only digits');

        $converter->convertTime('1234.56');
    }
}
