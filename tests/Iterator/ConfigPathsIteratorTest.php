<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Iterator;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Xdg\BaseDirectory\Iterator\ConfigPathsIterator;
use Xdg\BaseDirectory\Iterator\Direction;
use Xdg\BaseDirectory\Platform\PlatformInterface;

final class ConfigPathsIteratorTest extends TestCase
{
    private function mockPlatform(string $cfgHome, array $cfgDirs): PlatformInterface
    {
        $platform = $this->createMock(PlatformInterface::class);
        $platform->expects(self::any())
            ->method('getConfigHome')
            ->willReturn($cfgHome);
        $platform->expects(self::any())
            ->method('getConfigDirectories')
            ->willReturn($cfgDirs);

        return $platform;
    }

    #[DataProvider('iterationProvider')]
    public function testIteration(string $cfgHome, array $cfgDirs, string $subPath, Direction $direction, array $expected): void
    {
        $platform = self::mockPlatform($cfgHome, $cfgDirs);
        $it = new ConfigPathsIterator($platform, $subPath, $direction);
        $paths = iterator_to_array($it);
        Assert::assertSame($expected, $paths);
    }

    public static function iterationProvider(): iterable
    {
        yield 'no subPath, user order order' => [
            '/home/me', ['/usr/local/etc', '/etc'],
            '', Direction::MostSpecificFirst,
            ['/home/me', '/usr/local/etc', '/etc'],
        ];
        yield 'no subPath, system order' => [
            '/home/me', ['/usr/local/etc', '/etc'],
            '', Direction::MostSpecificLast,
            ['/etc', '/usr/local/etc', '/home/me'],
        ];
        yield 'with subPath, user order order' => [
            '/home/me', ['/usr/local/etc', '/etc'],
            'foo/bar', Direction::MostSpecificFirst,
            ['/home/me/foo/bar', '/usr/local/etc/foo/bar', '/etc/foo/bar'],
        ];
        yield 'with subPath, system order' => [
            '/home/me', ['/usr/local/etc', '/etc'],
            'foo/bar', Direction::MostSpecificLast,
            ['/etc/foo/bar', '/usr/local/etc/foo/bar', '/home/me/foo/bar'],
        ];
    }
}
