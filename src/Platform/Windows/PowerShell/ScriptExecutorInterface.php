<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Platform\Windows\PowerShell;

/**
 * @internal
 */
interface ScriptExecutorInterface
{
    public static function isSupported(): bool;

    public function execute(string $script, string ...$arguments): string;
}
