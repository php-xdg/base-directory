<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Platform;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use Xdg\BaseDirectory\Platform\Windows\KnownFolder;
use Xdg\Environment\Provider\ArrayProvider;
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

    #[DataProvider('homeDirectoryUsesUserProfileEnvProvider')]
    public function testHomeDirectoryUsesUserProfileEnv(array $env, array $knownFolders, string $home): void
    {
        $platform = new WindowsPlatform(
            new ArrayProvider($env),
            new KnownFoldersArrayProvider($knownFolders)
        );
        Assert::assertSame("{$home}/AppData/Local", $platform->getDataHome());
    }

    public static function homeDirectoryUsesUserProfileEnvProvider(): iterable
    {
        yield 'prioritizes KnownFolder::Profile' => [
            ['USERPROFILE' => 'Z:/baz'],
            [KnownFolder::Profile->name => 'C:/foo/bar'],
            'C:/foo/bar',
        ];
        yield 'fallback to USERPROFILE' => [
            ['USERPROFILE' => 'Z:/baz'],
            [],
            'Z:/baz',
        ];
    }

    public static function getDataHomeProvider(): iterable
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

    public static function getConfigHomeProvider(): iterable
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

    public static function getCacheHomeProvider(): iterable
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

    public static function getStateHomeProvider(): iterable
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

    public static function getRuntimeDirectoryProvider(): iterable
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

    public static function getDataDirectoriesProvider(): iterable
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

    public static function getConfigDirectoriesProvider(): iterable
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

    public static function findDataPathProvider(): iterable
    {
        yield 'defaults, no subPath, no predicate' => [
            [], '', null,
            'Z:/users/test/AppData/Local',
        ];
        yield 'defaults, no subPath, false predicate' => [
            [], '', fn($p) => false,
            null,
        ];
        yield 'defaults, no subPath, predicate' => [
            [], '', fn($p) => str_starts_with($p, 'C:'),
            'C:/ProgramData',
        ];
        yield 'defaults, subPath, no predicate' => [
            [], 'foo/bar', null,
            'Z:/users/test/AppData/Local/foo/bar',
        ];
        yield 'defaults, subPath, predicate' => [
            [], 'foo/bar', fn($p) => str_starts_with($p, 'C:'),
            'C:/ProgramData/foo/bar',
        ];
    }

    public static function findConfigPathProvider(): iterable
    {
        yield 'defaults, no subPath, no predicate' => [
            [], '', null,
            'Z:/users/test/AppData/Local',
        ];
        yield 'defaults, no subPath, false predicate' => [
            [], '', fn($p) => false,
            null,
        ];
        yield 'defaults, no subPath, predicate' => [
            [], '', fn($p) => str_starts_with($p, 'C:'),
            'C:/ProgramData',
        ];
        yield 'defaults, subPath, no predicate' => [
            [], 'foo/bar', null,
            'Z:/users/test/AppData/Local/foo/bar',
        ];
        yield 'defaults, subPath, predicate' => [
            [], 'foo/bar', fn($p) => str_starts_with($p, 'C:'),
            'C:/ProgramData/foo/bar',
        ];
    }

    public static function collectDataPathsProvider(): iterable
    {
        yield 'defaults, noSubPath, no predicate' => [
            [], '', null,
            ['C:/ProgramData', 'Z:/users/test/AppData/Roaming', 'Z:/users/test/AppData/Local'],
        ];
        yield 'defaults, noSubPath, false predicate' => [
            [], '', fn($p) => false, [],
        ];
        yield 'defaults, noSubPath, predicate' => [
            [], '', fn($p) => str_starts_with($p, 'Z:'),
            ['Z:/users/test/AppData/Roaming', 'Z:/users/test/AppData/Local'],
        ];
        yield 'defaults, subPath, no predicate' => [
            [], 'foo', null,
            ['C:/ProgramData/foo', 'Z:/users/test/AppData/Roaming/foo', 'Z:/users/test/AppData/Local/foo'],
        ];
        yield 'defaults, subPath, predicate' => [
            [], 'foo', fn($p) => str_starts_with($p, 'Z:'),
            ['Z:/users/test/AppData/Roaming/foo', 'Z:/users/test/AppData/Local/foo'],
        ];
    }

    public static function collectConfigPathsProvider(): iterable
    {
        yield 'defaults, noSubPath, no predicate' => [
            [], '', null,
            [
                'Z:/users/test/AppData/Roaming',
                'C:/ProgramData',
                'Z:/users/test/AppData/Local',
            ],
        ];
        yield 'defaults, noSubPath, false predicate' => [
            [], '', fn($p) => false, [],
        ];
        yield 'defaults, noSubPath, predicate' => [
            [], '', fn($p) => str_starts_with($p, 'Z:'),
            [
                'Z:/users/test/AppData/Roaming',
                'Z:/users/test/AppData/Local',
            ],
        ];
        yield 'defaults, subPath, no predicate' => [
            [], 'foo', null,
            [
                'Z:/users/test/AppData/Roaming/foo',
                'C:/ProgramData/foo',
                'Z:/users/test/AppData/Local/foo',
            ],
        ];
        yield 'defaults, subPath, predicate' => [
            [], 'foo', fn($p) => str_starts_with($p, 'Z:'),
            [
                'Z:/users/test/AppData/Roaming/foo',
                'Z:/users/test/AppData/Local/foo',
            ],
        ];
    }
}
