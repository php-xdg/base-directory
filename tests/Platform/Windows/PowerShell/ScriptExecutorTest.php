<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Platform\Windows\PowerShell;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Xdg\BaseDirectory\Platform\Windows\PowerShell\ScriptExecutor;
use Xdg\BaseDirectory\Tests\ResourceHelper;

final class ScriptExecutorTest extends TestCase
{
    #[DataProvider('executeProvider')]
    public function testExecute(array $arguments, string $expected): void
    {
        $executor = new ScriptExecutor();
        if (!$executor->isSupported()) {
            $this->markTestSkipped('PowerShell script executor is not supported on this system.');
        }

        Assert::assertSame($expected, $executor->execute(...$arguments));
    }

    public static function executeProvider(): iterable
    {
        yield 'echo "success"' => [
            [ResourceHelper::getPath('powershell/echo.ps1'), 'success'],
            "success\n",
        ];
        yield 'exit > 0' => [
            [ResourceHelper::getPath('powershell/exit-err.ps1')],
            '',
        ];
    }
}
