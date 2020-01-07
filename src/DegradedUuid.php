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

namespace Ramsey\Uuid;

/**
 * DegradedUuid represents an RFC 4122 UUID on 32-bit systems
 *
 * Some of the functionality of a DegradedUuid is not present or degraded, since
 * 32-bit systems are unable to perform the necessary mathematical operations or
 * represent the integers appropriately.
 *
 * @psalm-immutable
 */
class DegradedUuid extends Uuid implements UuidInterface
{
}
