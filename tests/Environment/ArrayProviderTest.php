<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Environment;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Xdg\BaseDirectory\Environment\ArrayProvider;
use Xdg\BaseDirectory\Environment\Exception\UnexpectedValueException;

final class ArrayProviderTest extends TestCase
{
    /**
     * @dataProvider getValueProvider
     */
    public function testGetValue(array $env, string $key, ?string $expected): void
    {
        $provider = new ArrayProvider($env);
        Assert::assertSame($expected, $provider->get($key));
    }

    public function getValueProvider(): iterable
    {
        yield 'returns the value from the array' => [
            ['foo' => 'bar'],
            'foo',
            'bar',
        ];
        yield 'coerces false to null' => [
            ['foo' => false],
            'foo',
            null,
        ];
        yield 'coerces "" to null' => [
            ['foo' => ''],
            'foo',
            null,
        ];
    }

    /**
     * @dataProvider getFailsForNonScalarsProvider
     */
    public function testGetFailsForNonScalars(array $env, string $key): void
    {
        $this->expectException(UnexpectedValueException::class);
        $provider = new ArrayProvider($env);
        $provider->get($key);
    }

    public function getFailsForNonScalarsProvider(): iterable
    {
        yield 'array' => [
            ['foo' => []],
            'foo',
        ];
        yield 'object' => [
            ['foo' => new \stdClass()],
            'foo',
        ];
    }
}
