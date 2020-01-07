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

namespace Ramsey\Uuid\Converter;

use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

/**
 * Provides methods to check dependencies for various converters
 *
 * @psalm-immutable
 */
trait DependencyCheckTrait
{
    /**
     * Returns boolean true if the GMP extension is loaded, throws
     * UnsatisfiedDependencyException otherwise
     *
     * @throws UnsatisfiedDependencyException if GMP is not loaded
     */
    private function checkGmpExtension(): bool
    {
        if (!extension_loaded('gmp')) {
            throw new UnsatisfiedDependencyException(
                'ext-gmp must be present to use this converter'
            );
        }

        return true;
    }
}
