<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Platform;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Xdg\BaseDirectory\Environment\ArrayProvider;
use Xdg\BaseDirectory\Exception\MissingHomeDirectoryPath;
use Xdg\BaseDirectory\Platform\MacOsPlatform;
use Xdg\BaseDirectory\Platform\Windows\KnownFoldersArrayProvider;
use Xdg\BaseDirectory\Platform\WindowsPlatform;

final class WindowsPlatformTest extends TestCase
{
    private static function createPlatform(array $env): WindowsPlatform
    {
        return new WindowsPlatform(
            new ArrayProvider([
                'HOMEDRIVE' => 'Z:',
                'HOMEPATH' => '\\users\\test',
                ...$env,
            ]),
            new KnownFoldersArrayProvider([]),
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
            'Z:/users/test/AppData/Local',
        ];
        yield 'defaults (HOME = /)' => [
            ['HOMEPATH' => '\\'],
            'Z:/AppData/Local',
        ];
        yield 'HOME not set' => [
            ['HOMEDRIVE' => null, 'HOMEPATH' => null],
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
            'Z:/users/test/AppData/Local',
        ];
        yield 'defaults (HOME = /)' => [
            ['HOMEPATH' => '\\'],
            'Z:/AppData/Local',
        ];
        yield 'HOME not set' => [
            ['HOMEDRIVE' => null, 'HOMEPATH' => null],
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
            'Z:/users/test/AppData/Local/cache',
        ];
        yield 'defaults (HOME = /)' => [
            ['HOMEPATH' => '\\'],
            'Z:/AppData/Local/cache',
        ];
        yield 'HOME not set' => [
            ['HOMEDRIVE' => null, 'HOMEPATH' => null],
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
            'Z:/users/test/AppData/Local',
        ];
        yield 'defaults (HOME = /)' => [
            ['HOMEPATH' => '\\'],
            'Z:/AppData/Local',
        ];
        yield 'HOME not set' => [
            ['HOMEDRIVE' => null, 'HOMEPATH' => null],
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
        yield 'defaults' => [
            [],
            'Z:/users/test/AppData/Local',
        ];
        yield 'defaults (HOME = /)' => [
            ['HOMEPATH' => '\\'],
            'Z:/AppData/Local',
        ];
        yield 'HOME not set' => [
            ['HOMEDRIVE' => null, 'HOMEPATH' => null],
            '',
            MissingHomeDirectoryPath::class,
        ];
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
            [
                'Z:/users/test/AppData/Roaming',
                'C:/ProgramData',
            ],
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
            [
                'C:/ProgramData',
                'Z:/users/test/AppData/Roaming',
            ],
        ];
    }
}
