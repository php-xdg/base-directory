<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Platform;

use Xdg\BaseDirectory\Environment\ArrayProvider;
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

    public function getDataHomeProvider(): iterable
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

    public function getConfigHomeProvider(): iterable
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

    public function getCacheHomeProvider(): iterable
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

    public function getStateHomeProvider(): iterable
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

    public function getRuntimeDirectoryProvider(): iterable
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

    public function getDataDirectoriesProvider(): iterable
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

    public function getConfigDirectoriesProvider(): iterable
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
}
