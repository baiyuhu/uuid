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

use Ramsey\Uuid\Converter\TimeConverterInterface;
use Ramsey\Uuid\Math\CalculatorInterface;
use Ramsey\Uuid\Type\IntegerValue;
use Ramsey\Uuid\Type\Time;

/**
 * GenericTimeConverter uses the provided calculator to calculate and convert
 * time values
 */
class GenericTimeConverter implements TimeConverterInterface
{
    /**
     * @var CalculatorInterface
     */
    private $calculator;

    public function __construct(CalculatorInterface $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @inheritDoc
     * @psalm-pure
     */
    public function calculateTime(string $seconds, string $microSeconds): array
    {
        $timestamp = new Time($seconds, $microSeconds);

        $sec = $this->calculator->multiply(
            $timestamp->getSeconds(),
            new IntegerValue('10000000')
        );

        $usec = $this->calculator->multiply(
            $timestamp->getMicroSeconds(),
            new IntegerValue('10')
        );

        $uuidTime = $this->calculator->add(
            $sec,
            $usec,
            new IntegerValue('122192928000000000')
        );

        $uuidTimeHex = str_pad(
            $this->calculator->toHexadecimal($uuidTime)->toString(),
            16,
            '0',
            STR_PAD_LEFT
        );

        return [
            'low' => substr($uuidTimeHex, 8),
            'mid' => substr($uuidTimeHex, 4, 4),
            'hi' => substr($uuidTimeHex, 0, 4),
        ];
    }

    /**
     * @inheritDoc
     * @psalm-pure
     */
    public function convertTime(string $timestamp): string
    {
        $timestamp = new IntegerValue($timestamp);

        $ts = $this->calculator->subtract(
            $timestamp,
            new IntegerValue('122192928000000000')
        );

        $ts = $this->calculator->divide($ts, new IntegerValue('10000000'));

        return $ts->toString();
    }
}
