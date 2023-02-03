<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Platform\Windows\PowerShell;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresOperatingSystemFamily;
use PHPUnit\Framework\TestCase;
use Xdg\BaseDirectory\Platform\Windows\PowerShell\ScriptExecutor;
use Xdg\BaseDirectory\Tests\ResourceHelper;

#[RequiresOperatingSystemFamily('Windows')]
final class ScriptExecutorTest extends TestCase
{
    #[DataProvider('executeProvider')]
    public function testExecute(array $arguments, string $expected): void
    {
        if (!ScriptExecutor::isSupported()) {
            $this->markTestSkipped('PowerShell script executor is not supported on this system.');
        }

        $executor = new ScriptExecutor();
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
