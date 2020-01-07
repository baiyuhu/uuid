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

use Ramsey\Uuid\Converter\DependencyCheckTrait;
use Ramsey\Uuid\Converter\NumberStringTrait;
use Ramsey\Uuid\Converter\TimeConverterInterface;

class DefaultTimeConverter implements TimeConverterInterface
{
    use DependencyCheckTrait;
    use NumberStringTrait;

    /**
     * @psalm-pure
     * @inheritDoc
     */
    public function calculateTime(string $seconds, string $microSeconds): array
    {
        if (PHP_INT_SIZE === 8) {
            return (new PhpTimeConverter())->calculateTime($seconds, $microSeconds);
        }

        return (new BigNumberTimeConverter())->calculateTime($seconds, $microSeconds);
    }

    /**
     * @psalm-pure
     * @inheritDoc
     */
    public function convertTime(string $timestamp): string
    {
        if (PHP_INT_SIZE === 8) {
            return (new PhpTimeConverter())->convertTime($timestamp);
        }

        return (new BigNumberTimeConverter())->convertTime($timestamp);
    }
}
