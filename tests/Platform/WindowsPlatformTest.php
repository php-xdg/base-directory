<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Platform;

use Xdg\BaseDirectory\Environment\ArrayProvider;
use Xdg\BaseDirectory\Exception\MissingHomeDirectoryPath;
use Xdg\BaseDirectory\Platform\Windows\KnownFoldersArrayProvider;
use Xdg\BaseDirectory\Platform\WindowsPlatform;

final class WindowsPlatformTest extends PlatformTestCase
{
    protected static function createPlatform(array $env): WindowsPlatform
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
