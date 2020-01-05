<?php

/**
 * This file is part of the ramsey/uuid library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace Ramsey\Uuid\Converter\Time;

use Brick\Math\BigInteger;
use Brick\Math\RoundingMode;
use Ramsey\Uuid\Converter\DependencyCheckTrait;
use Ramsey\Uuid\Converter\NumberStringTrait;
use Ramsey\Uuid\Converter\TimeConverterInterface;
use Ramsey\Uuid\Exception\InvalidArgumentException;

/**
 * BigNumberTimeConverter uses the brick/math library's `BigInteger` to
 * provide facilities for converting parts of time into representations that may
 * be used in UUIDs
 */
class BigNumberTimeConverter implements TimeConverterInterface
{
    use DependencyCheckTrait;
    use NumberStringTrait;

    /**
     * @throws InvalidArgumentException if $seconds or $microseconds are not integer strings
     *
     * @inheritDoc
     *
     * @psalm-pure
     * @psalm-suppress ImpureMethodCall The use of the external brick/math
     *     library causes Psalm to complain about impure method calls.
     */
    public function calculateTime(string $seconds, string $microSeconds): array
    {
        $this->checkIntegerString($seconds, 'seconds');
        $this->checkIntegerString($microSeconds, 'microSeconds');

        $sec = BigInteger::of($seconds)->multipliedBy('10000000');
        $usec = BigInteger::of($microSeconds)->multipliedBy('10');

        $uuidTime = BigInteger::zero()
            ->plus($sec)
            ->plus($usec)
            ->plus('122192928000000000');

        $uuidTimeHex = str_pad($uuidTime->toBase(16), 16, '0', STR_PAD_LEFT);

        return [
            'low' => substr($uuidTimeHex, 8),
            'mid' => substr($uuidTimeHex, 4, 4),
            'hi' => substr($uuidTimeHex, 0, 4),
        ];
    }

    /**
     * @throws InvalidArgumentException if $timestamp is not an integer string
     *
     * @inheritDoc
     *
     * @psalm-pure
     */
    public function convertTime(string $timestamp): string
    {
        $this->checkIntegerString($timestamp, 'timestamp');

        /** @psalm-suppress ImpureMethodCall */
        $ts = BigInteger::of($timestamp)
            ->minus('122192928000000000')
            ->dividedBy('10000000', RoundingMode::HALF_CEILING);

        return (string) $ts;
    }
}
