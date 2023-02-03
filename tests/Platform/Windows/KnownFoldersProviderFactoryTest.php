<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Platform\Windows;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Xdg\BaseDirectory\Platform\Windows\KnownFoldersArrayProvider;
use Xdg\BaseDirectory\Platform\Windows\KnownFoldersProviderFactory;
use Xdg\BaseDirectory\Platform\Windows\KnownFoldersProviderInterface;

final class KnownFoldersProviderFactoryTest extends TestCase
{
    public function testDefaultAlwaysReturnsAProvider(): void
    {
        $provider = KnownFoldersProviderFactory::default();
        Assert::assertInstanceOf(KnownFoldersProviderInterface::class, $provider);
    }

    public function testFactoryWithoutArgumentsReturnsArrayProvider(): void
    {
        $factory = new KnownFoldersProviderFactory();
        Assert::assertInstanceOf(KnownFoldersArrayProvider::class, $factory->create());
    }

    public function testCreateReturnsTheFirstSupportedProvider(): void
    {
        $unsupported = $this->createMock(KnownFoldersProviderInterface::class);
        $unsupported->method('isSupported')->willReturn(false);

        $supported = $this->createMock(KnownFoldersProviderInterface::class);
        $supported->method('isSupported')->willReturn(true);

        $expected = new KnownFoldersArrayProvider([]);

        $factory = new KnownFoldersProviderFactory(
            $unsupported,
            $expected,
            $supported,
        );
        Assert::assertSame($expected, $factory->create());
    }
}
