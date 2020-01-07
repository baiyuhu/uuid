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

use Ramsey\Uuid\Converter\NumberConverterInterface;
use Ramsey\Uuid\Math\Calculator;
use Ramsey\Uuid\Type\IntegerValue;

/**
 * BigNumberConverter uses brick/math to convert UUIDs from hexadecimal
 * characters into string representations of integers and vice versa
 */
class BigNumberConverter implements NumberConverterInterface
{
    /**
     * @inheritDoc
     * @psalm-pure
     */
    public function fromHex(string $hex): string
    {
        return (new Calculator())->fromBase($hex, 16)->toString();
    }

    /**
     * @inheritDoc
     * @psalm-pure
     */
    public function toHex(string $number): string
    {
        return (new Calculator())->toBase(new IntegerValue($number), 16);
    }
}
