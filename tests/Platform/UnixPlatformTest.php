<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Platform;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Xdg\BaseDirectory\Environment\ArrayProvider;
use Xdg\BaseDirectory\Exception\MissingHomeDirectoryPath;
use Xdg\BaseDirectory\Exception\UnsupportedEnvironment;
use Xdg\BaseDirectory\Platform\UnixPlatform;

final class UnixPlatformTest extends TestCase
{
    private static function createPlatform(array $env): UnixPlatform
    {
        return new UnixPlatform(
            new ArrayProvider([
                'HOME' => '/home/test',
                ...$env,
            ]),
        );
    }

    /**
     * @dataProvider getDataHomeProvider
     */
    public function testGetDataHome(array $env, string $expected, ?string $exception = null): void
    {
        if ($exception) {
            $this->expectException($exception);
        }
        Assert::assertSame($expected, self::createPlatform($env)->getDataHome());
    }

    public function getDataHomeProvider(): iterable
    {
        yield 'env is set' => [
            ['XDG_DATA_HOME' => '/foo/bar'],
            '/foo/bar',
        ];
        yield 'defaults' => [
            [],
            '/home/test/.local/share',
        ];
        yield 'defaults (HOME = /)' => [
            ['HOME' => '/'],
            '/.local/share',
        ];
        yield 'HOME not set' => [
            ['HOME' => null],
            '',
            MissingHomeDirectoryPath::class,
        ];
    }

    /**
     * @dataProvider getConfigHomeProvider
     */
    public function testGetConfigHome(array $env, string $expected, ?string $exception = null): void
    {
        if ($exception) {
            $this->expectException($exception);
        }
        Assert::assertSame($expected, self::createPlatform($env)->getConfigHome());
    }

    public function getConfigHomeProvider(): iterable
    {
        yield 'env is set' => [
            ['XDG_CONFIG_HOME' => '/foo/bar'],
            '/foo/bar',
        ];
        yield 'defaults' => [
            [],
            '/home/test/.config',
        ];
        yield 'defaults (HOME = /)' => [
            ['HOME' => '/'],
            '/.config',
        ];
        yield 'HOME not set' => [
            ['HOME' => null],
            '',
            MissingHomeDirectoryPath::class,
        ];
    }

    /**
     * @dataProvider getCacheHomeProvider
     */
    public function testGetCacheHome(array $env, string $expected, ?string $exception = null): void
    {
        if ($exception) {
            $this->expectException($exception);
        }
        Assert::assertSame($expected, self::createPlatform($env)->getCacheHome());
    }

    public function getCacheHomeProvider(): iterable
    {
        yield 'env is set' => [
            ['XDG_CACHE_HOME' => '/foo/bar'],
            '/foo/bar',
        ];
        yield 'defaults' => [
            [],
            '/home/test/.cache',
        ];
        yield 'defaults (HOME = /)' => [
            ['HOME' => '/'],
            '/.cache',
        ];
        yield 'HOME not set' => [
            ['HOME' => null],
            '',
            MissingHomeDirectoryPath::class,
        ];
    }

    /**
     * @dataProvider getStateHomeProvider
     */
    public function testGetStateHome(array $env, string $expected, ?string $exception = null): void
    {
        if ($exception) {
            $this->expectException($exception);
        }
        Assert::assertSame($expected, self::createPlatform($env)->getStateHome());
    }

    public function getStateHomeProvider(): iterable
    {
        yield 'env is set' => [
            ['XDG_STATE_HOME' => '/foo/bar'],
            '/foo/bar',
        ];
        yield 'defaults' => [
            [],
            '/home/test/.local/state',
        ];
        yield 'defaults (HOME = /)' => [
            ['HOME' => '/'],
            '/.local/state',
        ];
        yield 'HOME not set' => [
            ['HOME' => null],
            '',
            MissingHomeDirectoryPath::class,
        ];
    }

    /**
     * @dataProvider getRuntimeDirectoryProvider
     */
    public function testGetRuntimeDirectory(array $env, string $expected, ?string $exception = null): void
    {
        if ($exception) {
            $this->expectException($exception);
        }
        Assert::assertSame($expected, self::createPlatform($env)->getRuntimeDirectory());
    }

    public function getRuntimeDirectoryProvider(): iterable
    {
        yield 'env is set' => [
            ['XDG_RUNTIME_DIR' => '/foo/bar'],
            '/foo/bar',
        ];
        if (function_exists('posix_getuid')) {
            yield 'defaults' => [
                [],
                '/run/user/' . posix_getuid(),
            ];
        } else {
            yield 'missing posix extension' => [
                [],
                '',
                UnsupportedEnvironment::class,
            ];
        }
    }

    /**
     * @dataProvider getDataDirectoriesProvider
     */
    public function testGetDataDirectories(array $env, array $expected): void
    {
        Assert::assertSame($expected, self::createPlatform($env)->getDataDirectories());
    }

    public function getDataDirectoriesProvider(): iterable
    {
        yield 'env is set' => [
            ['XDG_DATA_DIRS' => '/foo:/bar'],
            ['/foo', '/bar'],
        ];
        yield 'defaults' => [
            [],
            ['/usr/local/share', '/usr/share'],
        ];
    }

    /**
     * @dataProvider getConfigDirectoriesProvider
     */
    public function testGetConfigDirectories(array $env, array $expected): void
    {
        Assert::assertSame($expected, self::createPlatform($env)->getConfigDirectories());
    }

    public function getConfigDirectoriesProvider(): iterable
    {
        yield 'env is set' => [
            ['XDG_CONFIG_DIRS' => '/foo:/bar'],
            ['/foo', '/bar'],
        ];
        yield 'defaults' => [
            [],
            ['/etc/xdg'],
        ];
    }
}
