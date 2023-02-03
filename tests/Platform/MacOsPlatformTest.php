<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Platform;

use Xdg\Environment\ArrayProvider;
use Xdg\BaseDirectory\Exception\MissingHomeDirectoryPath;
use Xdg\BaseDirectory\Platform\MacOsPlatform;

final class MacOsPlatformTest extends PlatformTestCase
{
    protected static function createPlatform(array $env): MacOsPlatform
    {
        return new MacOsPlatform(
            new ArrayProvider([
                'HOME' => '/Users/test',
                ...$env,
            ]),
        );
    }

    public static function getDataHomeProvider(): iterable
    {
        yield 'env is set' => [
            ['XDG_DATA_HOME' => '/foo/bar'],
            '/foo/bar',
        ];
        yield 'defaults' => [
            [],
            '/Users/test/Library/Application Support',
        ];
        yield 'defaults (HOME = /)' => [
            ['HOME' => '/'],
            '/Library/Application Support',
        ];
        yield 'HOME not set' => [
            ['HOME' => null],
            '',
            MissingHomeDirectoryPath::class,
        ];
    }

    public static function getConfigHomeProvider(): iterable
    {
        yield 'env is set' => [
            ['XDG_CONFIG_HOME' => '/foo/bar'],
            '/foo/bar',
        ];
        yield 'defaults' => [
            [],
            '/Users/test/Library/Application Support',
        ];
        yield 'defaults (HOME = /)' => [
            ['HOME' => '/'],
            '/Library/Application Support',
        ];
        yield 'HOME not set' => [
            ['HOME' => null],
            '',
            MissingHomeDirectoryPath::class,
        ];
    }

    public static function getCacheHomeProvider(): iterable
    {
        yield 'env is set' => [
            ['XDG_CACHE_HOME' => '/foo/bar'],
            '/foo/bar',
        ];
        yield 'defaults' => [
            [],
            '/Users/test/Library/Caches',
        ];
        yield 'defaults (HOME = /)' => [
            ['HOME' => '/'],
            '/Library/Caches',
        ];
        yield 'HOME not set' => [
            ['HOME' => null],
            '',
            MissingHomeDirectoryPath::class,
        ];
    }

    public static function getStateHomeProvider(): iterable
    {
        yield 'env is set' => [
            ['XDG_STATE_HOME' => '/foo/bar'],
            '/foo/bar',
        ];
        yield 'defaults' => [
            [],
            '/Users/test/Library/Application Support',
        ];
        yield 'defaults (HOME = /)' => [
            ['HOME' => '/'],
            '/Library/Application Support',
        ];
        yield 'HOME not set' => [
            ['HOME' => null],
            '',
            MissingHomeDirectoryPath::class,
        ];
    }

    public static function getRuntimeDirectoryProvider(): iterable
    {
        yield 'env is set' => [
            ['XDG_RUNTIME_DIR' => '/foo/bar'],
            '/foo/bar',
        ];
        yield 'defaults' => [
            [],
            '/Users/test/Library/Application Support',
        ];
        yield 'defaults (HOME = /)' => [
            ['HOME' => '/'],
            '/Library/Application Support',
        ];
        yield 'HOME not set' => [
            ['HOME' => null],
            '',
            MissingHomeDirectoryPath::class,
        ];
    }

    public static function getDataDirectoriesProvider(): iterable
    {
        yield 'env is set' => [
            ['XDG_DATA_DIRS' => '/foo:/bar'],
            ['/foo', '/bar'],
        ];
        yield 'defaults' => [
            [],
            ['/Library/Application Support'],
        ];
    }

    public static function getConfigDirectoriesProvider(): iterable
    {
        yield 'env is set' => [
            ['XDG_CONFIG_DIRS' => '/foo:/bar'],
            ['/foo', '/bar'],
        ];
        yield 'defaults' => [
            [],
            [
                '/Users/test/Library/Preferences',
                '/Library/Application Support',
                '/Library/Preferences',
            ],
        ];
    }

    public static function findDataPathProvider(): iterable
    {
        yield 'defaults, no subPath, no predicate' => [
            [], '', null,
            '/Users/test/Library/Application Support',
        ];
        yield 'defaults, no subPath, false predicate' => [
            [], '', fn($p) => false,
            null,
        ];
        yield 'defaults, no subPath, predicate' => [
            [], '', fn($p) => str_starts_with($p, '/Lib'),
            '/Library/Application Support',
        ];
        yield 'defaults, subPath, no predicate' => [
            [], 'foo/bar', null,
            '/Users/test/Library/Application Support/foo/bar',
        ];
        yield 'defaults, subPath, predicate' => [
            [], 'foo/bar', fn($p) => str_starts_with($p, '/Lib'),
            '/Library/Application Support/foo/bar',
        ];
    }

    public static function findConfigPathProvider(): iterable
    {
        yield 'defaults, no subPath, no predicate' => [
            [], '', null,
            '/Users/test/Library/Application Support',
        ];
        yield 'defaults, no subPath, false predicate' => [
            [], '', fn($p) => false,
            null,
        ];
        yield 'defaults, no subPath, predicate' => [
            [], '', fn($p) => str_starts_with($p, '/Lib'),
            '/Library/Application Support',
        ];
        yield 'defaults, subPath, no predicate' => [
            [], 'foo/bar', null,
            '/Users/test/Library/Application Support/foo/bar',
        ];
        yield 'defaults, subPath, predicate' => [
            [], 'foo/bar', fn($p) => str_starts_with($p, '/Lib'),
            '/Library/Application Support/foo/bar',
        ];
    }

    public static function collectDataPathsProvider(): iterable
    {
        yield 'defaults, noSubPath, no predicate' => [
            [], '', null,
            ['/Library/Application Support', '/Users/test/Library/Application Support'],
        ];
        yield 'defaults, noSubPath, false predicate' => [
            [], '', fn($p) => false, [],
        ];
        yield 'defaults, noSubPath, predicate' => [
            [], '', fn($p) => str_starts_with($p, '/Users'),
            ['/Users/test/Library/Application Support'],
        ];
        yield 'defaults, subPath, no predicate' => [
            [], 'foo', null,
            ['/Library/Application Support/foo', '/Users/test/Library/Application Support/foo'],
        ];
        yield 'defaults, subPath, predicate' => [
            [], 'foo', fn($p) => str_starts_with($p, '/Users'),
            ['/Users/test/Library/Application Support/foo'],
        ];
    }

    public static function collectConfigPathsProvider(): iterable
    {
        yield 'defaults, noSubPath, no predicate' => [
            [], '', null,
            [
                '/Library/Preferences',
                '/Library/Application Support',
                '/Users/test/Library/Preferences',
                '/Users/test/Library/Application Support',
            ],
        ];
        yield 'defaults, noSubPath, false predicate' => [
            [], '', fn($p) => false, [],
        ];
        yield 'defaults, noSubPath, predicate' => [
            [], '', fn($p) => str_starts_with($p, '/Users'),
            [
                '/Users/test/Library/Preferences',
                '/Users/test/Library/Application Support',
            ],
        ];
        yield 'defaults, subPath, no predicate' => [
            [], 'foo', null,
            [
                '/Library/Preferences/foo',
                '/Library/Application Support/foo',
                '/Users/test/Library/Preferences/foo',
                '/Users/test/Library/Application Support/foo',
            ],
        ];
        yield 'defaults, subPath, predicate' => [
            [], 'foo', fn($p) => str_starts_with($p, '/Users'),
            [
                '/Users/test/Library/Preferences/foo',
                '/Users/test/Library/Application Support/foo',
            ],
        ];
    }
}
