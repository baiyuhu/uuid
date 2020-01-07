<?php

declare(strict_types=1);

namespace Ramsey\Uuid\Test;

use AspectMock\Test as AspectMock;
use Mockery;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;

class TestCase extends PhpUnitTestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        AspectMock::clean();
        Mockery::close();
    }

    protected function skipIfNoGmp(): void
    {
        if (!$this->hasGmp()) {
            $this->markTestSkipped(
                'Skipping test that requires GMP.'
            );
        }
    }

    protected function hasGmp(): bool
    {
        return extension_loaded('gmp');
    }

    public static function isLittleEndianSystem(): bool
    {
        return current(unpack('v', pack('S', 0x00FF))) === 0x00FF;
    }
}
