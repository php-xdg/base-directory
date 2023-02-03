<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Platform;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Xdg\BaseDirectory\PlatformInterface;

/**
 * @psalm-type EnvVars=array<string, string>
 */
abstract class PlatformTestCase extends TestCase
{
    abstract protected static function createPlatform(array $env): PlatformInterface;

    #[DataProvider('getDataHomeProvider')]
    public function testGetDataHome(array $env, string $expected, ?string $exception = null): void
    {
        if ($exception) {
            $this->expectException($exception);
        }
        Assert::assertSame($expected, static::createPlatform($env)->getDataHome());
    }

    abstract public static function getDataHomeProvider(): iterable;

    #[DataProvider('getConfigHomeProvider')]
    public function testGetConfigHome(array $env, string $expected, ?string $exception = null): void
    {
        if ($exception) {
            $this->expectException($exception);
        }
        Assert::assertSame($expected, static::createPlatform($env)->getConfigHome());
    }

    abstract public static function getConfigHomeProvider(): iterable;

    #[DataProvider('getDataDirectoriesProvider')]
    public function testGetDataDirectories(array $env, array $expected): void
    {
        Assert::assertSame($expected, static::createPlatform($env)->getDataDirectories());
    }

    abstract public static function getDataDirectoriesProvider(): iterable;

    #[DataProvider('getConfigDirectoriesProvider')]
    public function testGetConfigDirectories(array $env, array $expected): void
    {
        Assert::assertSame($expected, static::createPlatform($env)->getConfigDirectories());
    }

    abstract public static function getConfigDirectoriesProvider(): iterable;

    #[DataProvider('getStateHomeProvider')]
    public function testGetStateHome(array $env, string $expected, ?string $exception = null): void
    {
        if ($exception) {
            $this->expectException($exception);
        }
        Assert::assertSame($expected, static::createPlatform($env)->getStateHome());
    }

    abstract public static function getStateHomeProvider(): iterable;

    #[DataProvider('getCacheHomeProvider')]
    public function testGetCacheHome(array $env, string $expected, ?string $exception = null): void
    {
        if ($exception) {
            $this->expectException($exception);
        }
        Assert::assertSame($expected, static::createPlatform($env)->getCacheHome());
    }

    abstract public static function getCacheHomeProvider(): iterable;

    #[DataProvider('getRuntimeDirectoryProvider')]
    public function testGetRuntimeDirectory(array $env, string $expected, ?string $exception = null): void
    {
        if ($exception) {
            $this->expectException($exception);
        }
        Assert::assertSame($expected, static::createPlatform($env)->getRuntimeDirectory());
    }

    abstract public static function getRuntimeDirectoryProvider(): iterable;

    #[DataProvider('findDataPathProvider')]
    public function testFindDataPath(array $env,string $subPath, ?callable $predicate, ?string $expected): void
    {
        Assert::assertSame($expected, static::createPlatform($env)->findDataPath($subPath, $predicate));
    }

    abstract public static function findDataPathProvider(): iterable;

    #[DataProvider('findConfigPathProvider')]
    public function testFindConfigPath(array $env,string $subPath, ?callable $predicate, ?string $expected): void
    {
        Assert::assertSame($expected, static::createPlatform($env)->findConfigPath($subPath, $predicate));
    }

    /**
     * @return iterable<array{EnvVars, string, callable, string[]}>
     */
    abstract public static function findConfigPathProvider(): iterable;

    #[DataProvider('collectDataPathsProvider')]
    public function testCollectDataPaths(array $env, string $subPath, ?callable $predicate, array $expected): void
    {
        Assert::assertSame($expected, static::createPlatform($env)->collectDataPaths($subPath, $predicate));
    }

    /**
     * @return iterable<array{EnvVars, string, callable, string[]}>
     */
    abstract public static function collectDataPathsProvider(): iterable;

    #[DataProvider('collectConfigPathsProvider')]
    public function testCollectConfigPaths(array $env, string $subPath, ?callable $predicate, array $expected): void
    {
        Assert::assertSame($expected, static::createPlatform($env)->collectConfigPaths($subPath, $predicate));
    }

    /**
     * @return iterable<array{EnvVars, string, callable, string[]}>
     */
    abstract public static function collectConfigPathsProvider(): iterable;
}
