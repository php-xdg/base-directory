<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Platform\Windows\PowerShell;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Xdg\BaseDirectory\Platform\Windows\PowerShell\DummyScriptExecutor;

final class DummyScriptExecutorTest extends TestCase
{
    public function testItIsSupported(): void
    {
        Assert::assertTrue(DummyScriptExecutor::isSupported());
    }

    public function testItReturnsTheConstructorArgument(): void
    {
        $output = (new DummyScriptExecutor('foo'))->execute('/foo/bar');
        Assert::assertSame('foo', $output);
    }
}
