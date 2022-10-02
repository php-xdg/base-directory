<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Environment;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Xdg\BaseDirectory\Environment\ArrayProvider;
use Xdg\BaseDirectory\Environment\ChainProvider;

final class ChainProviderTest extends TestCase
{
    /**
     * @dataProvider getValueProvider
     */
    public function testGetValue(array $envs, string $key, ?string $expected): void
    {
        $provider = new ChainProvider(
            ...array_map(fn($env) => new ArrayProvider($env), $envs)
        );
        Assert::assertSame($expected, $provider->get($key));
    }

    public function getValueProvider(): iterable
    {
        yield 'returns null for empty chain' => [
            [],
            'foo',
            null,
        ];
        yield 'returns null when key not found' => [
            [['a' => 'b'], ['c' => 'd']],
            'foo',
            null,
        ];
        yield 'returns the first value in the chain' => [
            [[], ['nope' => 42], ['foo' => 'bar'], ['foo' => 'nope']],
            'foo',
            'bar',
        ];
    }
}
