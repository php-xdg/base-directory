<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Platform;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Xdg\BaseDirectory\Platform\PlatformInterface;

abstract class PlatformTestCase extends TestCase
{
    abstract protected static function createPlatform(array $env): PlatformInterface;

    /**
     * @dataProvider getDataHomeProvider
     */
    public function testGetDataHome(array $env, string $expected, ?string $exception = null): void
    {
        if ($exception) {
            $this->expectException($exception);
        }
        Assert::assertSame($expected, static::createPlatform($env)->getDataHome());
    }

    abstract public function getDataHomeProvider(): iterable;

    /**
     * @dataProvider getConfigHomeProvider
     */
    public function testGetConfigHome(array $env, string $expected, ?string $exception = null): void
    {
        if ($exception) {
            $this->expectException($exception);
        }
        Assert::assertSame($expected, static::createPlatform($env)->getConfigHome());
    }

    abstract public function getConfigHomeProvider(): iterable;

    /**
     * @dataProvider getDataDirectoriesProvider
     */
    public function testGetDataDirectories(array $env, array $expected): void
    {
        Assert::assertSame($expected, static::createPlatform($env)->getDataDirectories());
    }

    abstract public function getDataDirectoriesProvider(): iterable;

    /**
     * @dataProvider getConfigDirectoriesProvider
     */
    public function testGetConfigDirectories(array $env, array $expected): void
    {
        Assert::assertSame($expected, static::createPlatform($env)->getConfigDirectories());
    }

    abstract public function getConfigDirectoriesProvider(): iterable;

    /**
     * @dataProvider getStateHomeProvider
     */
    public function testGetStateHome(array $env, string $expected, ?string $exception = null): void
    {
        if ($exception) {
            $this->expectException($exception);
        }
        Assert::assertSame($expected, static::createPlatform($env)->getStateHome());
    }

    abstract public function getStateHomeProvider(): iterable;

    /**
     * @dataProvider getCacheHomeProvider
     */
    public function testGetCacheHome(array $env, string $expected, ?string $exception = null): void
    {
        if ($exception) {
            $this->expectException($exception);
        }
        Assert::assertSame($expected, static::createPlatform($env)->getCacheHome());
    }

    abstract public function getCacheHomeProvider(): iterable;

    /**
     * @dataProvider getRuntimeDirectoryProvider
     */
    public function testGetRuntimeDirectory(array $env, string $expected, ?string $exception = null): void
    {
        if ($exception) {
            $this->expectException($exception);
        }
        Assert::assertSame($expected, static::createPlatform($env)->getRuntimeDirectory());
    }

    abstract public function getRuntimeDirectoryProvider(): iterable;
}
