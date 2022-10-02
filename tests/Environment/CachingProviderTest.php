<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Environment;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Xdg\BaseDirectory\Environment\CachingProvider;
use Xdg\BaseDirectory\Environment\EnvironmentProviderInterface;

final class CachingProviderTest extends TestCase
{
    /**
     * @dataProvider getValueProvider
     */
    public function testGetValue(string $key, ?string $expected): void
    {
        $provider = $this->createMock(EnvironmentProviderInterface::class);
        $provider->expects($this->once())
            ->method('get')
            ->with($key)
            ->willReturn($expected)
        ;

        $cache = new CachingProvider($provider);
        Assert::assertSame($expected, $cache->get($key));
        Assert::assertSame($expected, $cache->get($key));
    }

    public function getValueProvider(): iterable
    {
        yield 'null value' => ['foo', null];
        yield 'non-null value' => ['foo', 'bar'];
    }
}
