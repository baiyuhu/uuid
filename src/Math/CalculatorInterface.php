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

namespace Ramsey\Uuid\Math;

use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Type\IntegerValue;

/**
 * @psalm-immutable
 */
interface CalculatorInterface
{
    public function add(IntegerValue $augend, IntegerValue ...$addends): IntegerValue;

    public function subtract(IntegerValue $minuend, IntegerValue ...$subtrahends): IntegerValue;

    public function multiply(IntegerValue $multiplicand, IntegerValue ...$multipliers): IntegerValue;

    public function divide(IntegerValue $dividend, IntegerValue ...$divisors): IntegerValue;

    public function fromBase(string $value, int $base): IntegerValue;

    public function toBase(IntegerValue $value, int $base): string;

    public function toHexadecimal(IntegerValue $value): Hexadecimal;
}
