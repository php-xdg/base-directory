<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Environment;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Xdg\BaseDirectory\Environment\SuperGlobalsProvider;

final class SuperGlobalsProviderTest extends TestCase
{
    /**
     * @dataProvider getValueProvider
     * @backupGlobals enabled
     */
    public function testGetValue(array $server, array $env, string $key, ?string $expected): void
    {
        $provider = new SuperGlobalsProvider();
        $this->populateEnv($server, $env);
        Assert::assertSame($expected, $provider->get($key));
    }

    public function getValueProvider(): iterable
    {
        yield 'non-existent key' => [
            [], [], 'foo', null,
        ];
        yield 'prioritizes $_SERVER' => [
            ['foo' => 'bar'], ['foo' => 'baz'], 'foo', 'bar',
        ];
        yield '[internal] ensure env is cleaned up' => [
            [], [], 'foo', null,
        ];
        yield 'falls back to $_ENV' => [
            [], ['foo' => 'bar'], 'foo', 'bar',
        ];
        yield 'coerces "" to null' => [
            [], ['foo' => ''], 'foo', null,
        ];
        yield 'coerces false to null' => [
            [], ['foo' => false], 'foo', null,
        ];
    }

    private function populateEnv(array $server, array $env): void
    {
        foreach ($server as $key => $value) {
            $_SERVER[$key] = $value;
        }
        foreach ($env as $key => $value) {
            $_ENV[$key] = $value;
        }
    }
}
