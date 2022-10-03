<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Platform\Windows;

/**
 * @internal
 * @see https://learn.microsoft.com/en-us/windows/win32/shell/csidl
 * @see https://learn.microsoft.com/en-us/windows/win32/shell/knownfolderid
 */
interface KnownFoldersProviderInterface
{
    public function get(KnownFolder $id): ?string;
}
