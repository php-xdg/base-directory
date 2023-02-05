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

    public function isSupported(): bool
    {
        return true;
    }

    public function execute(\SplFileObject|string $script): string
    {
        return $this->output;
    }
}
