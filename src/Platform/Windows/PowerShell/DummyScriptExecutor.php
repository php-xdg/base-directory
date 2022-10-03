<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Platform\Windows\PowerShell;

/**
 * @internal
 */
final class DummyScriptExecutor implements ScriptExecutorInterface
{
    public function __construct(
        private readonly string $output,
    ) {
    }

    public static function isSupported(): bool
    {
        return true;
    }

    public function execute(string $script, string ...$arguments): string
    {
        return $this->output;
    }
}
