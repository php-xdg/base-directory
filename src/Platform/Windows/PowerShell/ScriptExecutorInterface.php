<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Platform\Windows\PowerShell;

use SplFileObject;

/**
 * @internal
 */
interface ScriptExecutorInterface
{
    public function isSupported(): bool;

    public function execute(SplFileObject|string $script): string;
}
