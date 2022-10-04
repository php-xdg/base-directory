<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Platform;

use Xdg\BaseDirectory\Environment\ArrayProvider;
use Xdg\BaseDirectory\Exception\MissingHomeDirectoryPath;
use Xdg\BaseDirectory\Exception\UnsupportedEnvironment;
use Xdg\BaseDirectory\Platform\UnixPlatform;

final class UnixPlatformTest extends PlatformTestCase
{
    protected static function createPlatform(array $env): UnixPlatform
    {
        return new UnixPlatform(
            new ArrayProvider([
                'HOME' => '/home/test',
                ...$env,
            ]),
        );
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

    public function findDataPathProvider(): iterable
    {
        yield 'defaults, no subPath, no predicate' => [
            [], '', null,
            '/home/test/.local/share',
        ];
        yield 'defaults, no subPath, false predicate' => [
            [], '', fn($p) => false,
            null,
        ];
        yield 'defaults, no subPath, predicate' => [
            [], '', fn($p) => str_starts_with($p, '/usr'),
            '/usr/local/share',
        ];
        yield 'defaults, subPath, no predicate' => [
            [], 'foo/bar', null,
            '/home/test/.local/share/foo/bar',
        ];
        yield 'defaults, subPath, predicate' => [
            [],
            'foo/bar', fn($p) => str_starts_with($p, '/usr'),
            '/usr/local/share/foo/bar',
        ];
    }

    public function findConfigPathProvider(): iterable
    {
        yield 'defaults, no subPath, no predicate' => [
            [], '', null,
            '/home/test/.config',
        ];
        yield 'defaults, no subPath, false predicate' => [
            [], '', fn($p) => false,
            null,
        ];
        yield 'defaults, no subPath, predicate' => [
            [], '', fn($p) => str_starts_with($p, '/etc'),
            '/etc/xdg',
        ];
        yield 'defaults, subPath, no predicate' => [
            [], 'foo/bar', null,
            '/home/test/.config/foo/bar',
        ];
        yield 'defaults, subPath, predicate' => [
            [], 'foo/bar', fn($p) => str_starts_with($p, '/etc'),
            '/etc/xdg/foo/bar',
        ];
    }

    public function collectDataPathsProvider(): iterable
    {
        yield 'defaults, noSubPath, no predicate' => [
            [], '', null,
            ['/usr/share', '/usr/local/share', '/home/test/.local/share'],
        ];
        yield 'defaults, noSubPath, false predicate' => [
            [], '', fn($p) => false, [],
        ];
        yield 'defaults, noSubPath, predicate' => [
            [], '', fn($p) => str_starts_with($p, '/usr'),
            ['/usr/share', '/usr/local/share'],
        ];
        yield 'defaults, subPath, no predicate' => [
            [], 'foo', null,
            ['/usr/share/foo', '/usr/local/share/foo', '/home/test/.local/share/foo'],
        ];
        yield 'defaults, subPath, predicate' => [
            [], 'foo', fn($p) => str_starts_with($p, '/usr'),
            ['/usr/share/foo', '/usr/local/share/foo'],
        ];
    }

    public function collectConfigPathsProvider(): iterable
    {
        yield 'defaults, noSubPath, no predicate' => [
            [], '', null,
            ['/etc/xdg', '/home/test/.config'],
        ];
        yield 'defaults, noSubPath, false predicate' => [
            [], '', fn($p) => false, [],
        ];
        yield 'defaults, noSubPath, predicate' => [
            [], '', fn($p) => str_starts_with($p, '/home'),
            ['/home/test/.config'],
        ];
        yield 'defaults, subPath, no predicate' => [
            [], 'foo', null,
            ['/etc/xdg/foo', '/home/test/.config/foo'],
        ];
        yield 'defaults, subPath, predicate' => [
            [], 'foo', fn($p) => str_starts_with($p, '/home'),
            ['/home/test/.config/foo'],
        ];
    }
}
