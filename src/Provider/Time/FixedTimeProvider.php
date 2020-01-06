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

namespace Ramsey\Uuid\Provider\Time;

use Ramsey\Uuid\Exception\InvalidArgumentException;
use Ramsey\Uuid\Provider\TimeProviderInterface;
use Ramsey\Uuid\Type\Timestamp;

/**
 * FixedTimeProvider uses an known timestamp to provide the time
 *
 * This provider allows the use of a previously-generated, or known, timestamp
 * when generating time-based UUIDs.
 */
class FixedTimeProvider implements TimeProviderInterface
{
    /**
     * @var Timestamp
     */
    private $fixedTime;

    /**
     * @param int[]|string[]|Timestamp $timestamp An array containing 'sec' and
     *     'usec' keys or a Timestamp object
     *
     * @throws InvalidArgumentException if the `$timestamp` does not contain
     *     `sec` or `usec` components
     */
    public function __construct($timestamp)
    {
        if (!$timestamp instanceof Timestamp) {
            $timestamp = $this->convertToTimestamp($timestamp);
        }

        $this->fixedTime = $timestamp;
    }

    /**
     * Sets the `usec` component of the timestamp
     *
     * @param int|string|Timestamp $value The `usec` value to set
     */
    public function setUsec($value): void
    {
        $this->fixedTime = new Timestamp($this->fixedTime->getSeconds(), $value);
    }

    /**
     * Sets the `sec` component of the timestamp
     *
     * @param int|string|Timestamp $value The `sec` value to set
     */
    public function setSec($value): void
    {
        $this->fixedTime = new Timestamp($value, $this->fixedTime->getMicroSeconds());
    }

    /**
     * @deprecated Transition to {@see FixedTimeProvider::getTimestamp()}
     *
     * @inheritDoc
     */
    public function currentTime(): array
    {
        return [
            'sec' => $this->fixedTime->getSeconds()->toString(),
            'usec' => $this->fixedTime->getMicroSeconds()->toString(),
        ];
    }

    public function getTimestamp(): Timestamp
    {
        return $this->fixedTime;
    }

    /**
     * @param int[]|string[] $timestamp
     *
     * @return Timestamp A timestamp created from the provided array
     *
     * @throws InvalidArgumentException if the `$timestamp` does not contain
     *     `sec` or `usec` components
     */
    private function convertToTimestamp(array $timestamp): Timestamp
    {
        if (!array_key_exists('sec', $timestamp) || !array_key_exists('usec', $timestamp)) {
            throw new InvalidArgumentException('Array must contain sec and usec keys.');
        }

        return new Timestamp($timestamp['sec'], $timestamp['usec']);
    }
}
