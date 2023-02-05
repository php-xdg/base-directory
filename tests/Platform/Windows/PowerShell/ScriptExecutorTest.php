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
    public function testExecute(string|\SplFileObject $script, string $expected): void
    {
        $executor = new ScriptExecutor();
        if (!$executor->isSupported()) {
            $this->markTestSkipped('PowerShell script executor is not supported on this system.');
        }

        Assert::assertSame($expected, $executor->execute($script));
    }

    public static function executeProvider(): iterable
    {
        yield 'echo "success"' => [
            <<<'PWSH'
            Write-Host "success"
            exit 0
            PWSH,
            "success\n",
        ];
        yield 'echo success w/ stream' => [
            new \SplFileObject(ResourceHelper::getPath('powershell/echo-success.ps1'), 'r'),
            "success\n",
        ];
        yield 'exit > 0' => [
            <<<'PWSH'
            Write-Host "exit(1)"
            exit 1
            PWSH,
            '',
        ];
    }
}
