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

namespace Ramsey\Uuid\Converter\Number;

use Brick\Math\BigInteger;
use Ramsey\Uuid\Converter\DependencyCheckTrait;
use Ramsey\Uuid\Converter\NumberConverterInterface;
use Ramsey\Uuid\Converter\NumberStringTrait;
use Ramsey\Uuid\Exception\InvalidArgumentException;

/**
 * BigNumberConverter uses brick/math to convert UUIDs from hexadecimal
 * characters into string representations of integers and vice versa
 */
class BigNumberConverter implements NumberConverterInterface
{
    use DependencyCheckTrait;
    use NumberStringTrait;

    /**
     * @throws InvalidArgumentException if $hex is not a hexadecimal string
     *
     * @inheritDoc
     *
     * @psalm-pure
     */
    public function fromHex(string $hex): string
    {
        $this->checkHexadecimalString($hex, 'hex');

        /** @psalm-suppress ImpureMethodCall */
        return BigInteger::fromBase($hex, 16)->toBase(10);
    }

    /**
     * @throws InvalidArgumentException if $integer is not an integer string
     *
     * @inheritDoc
     *
     * @psalm-pure
     */
    public function toHex(string $number): string
    {
        $this->checkIntegerString($number, 'number');

        /** @psalm-suppress ImpureMethodCall */
        return BigInteger::fromBase($number, 10)->toBase(16);
    }
}
