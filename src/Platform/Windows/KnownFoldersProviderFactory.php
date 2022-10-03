<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Platform\Windows;

use Xdg\BaseDirectory\Platform\Windows\PowerShell\ScriptExecutor;

/**
 * @internal
 */
final class KnownFoldersProviderFactory
{
    public static function fromEnvironment(): KnownFoldersProviderInterface
    {
        if (ScriptExecutor::isSupported()) {
            return new KnownFoldersPowerShellProvider(new ScriptExecutor());
        }

        return new KnownFoldersArrayProvider([]);
    }
}
