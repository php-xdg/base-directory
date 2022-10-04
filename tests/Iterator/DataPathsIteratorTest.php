<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Iterator;

use PHPUnit\Framework\Assert;
use Xdg\BaseDirectory\Iterator\DataPathsIterator;
use PHPUnit\Framework\TestCase;
use Xdg\BaseDirectory\Iterator\Direction;
use Xdg\BaseDirectory\Platform\PlatformInterface;

final class DataPathsIteratorTest extends TestCase
{
    private function mockPlatform(string $dataHome, array $dataDirs): PlatformInterface
    {
        $platform = $this->createMock(PlatformInterface::class);
        $platform->expects(self::any())
            ->method('getDataHome')
            ->willReturn($dataHome);
        $platform->expects(self::any())
            ->method('getDataDirectories')
            ->willReturn($dataDirs);

        return $platform;
    }

    /**
     * @dataProvider iterationProvider
     */
    public function testIteration(string $dataHome, array $dataDirs, string $subPath, Direction $direction, array $expected): void
    {
        $platform = self::mockPlatform($dataHome, $dataDirs);
        $it = new DataPathsIterator($platform, $subPath, $direction);
        $paths = iterator_to_array($it);
        Assert::assertSame($expected, $paths);
    }

    public function iterationProvider(): iterable
    {
        yield 'no subPath, user order order' => [
            '/home/me', ['/usr/local/share', '/usr/share'],
            '', Direction::MostSpecificFirst,
            ['/home/me', '/usr/local/share', '/usr/share'],
        ];
        yield 'no subPath, system order' => [
            '/home/me', ['/usr/local/share', '/usr/share'],
            '', Direction::MostSpecificLast,
            ['/usr/share', '/usr/local/share', '/home/me'],
        ];
        yield 'with subPath, user order order' => [
            '/home/me', ['/usr/local/share', '/usr/share'],
            'foo/bar', Direction::MostSpecificFirst,
            ['/home/me/foo/bar', '/usr/local/share/foo/bar', '/usr/share/foo/bar'],
        ];
        yield 'with subPath, system order' => [
            '/home/me', ['/usr/local/share', '/usr/share'],
            'foo/bar', Direction::MostSpecificLast,
            ['/usr/share/foo/bar', '/usr/local/share/foo/bar', '/home/me/foo/bar'],
        ];
    }
}
