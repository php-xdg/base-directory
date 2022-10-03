<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Platform;

use Xdg\BaseDirectory\Exception\UnsupportedEnvironment;

final class UnixPlatform extends AbstractPlatform
{
    use UnixPathTrait;

    protected function getDefaultDataHome(): string
    {
        return $this->getHomePath('.local/share');
    }

    protected function getDefaultConfigHome(): string
    {
        return $this->getHomePath('.config');
    }

    protected function getDefaultCacheHome(): string
    {
        return $this->getHomePath('.cache');
    }

    protected function getDefaultStateHome(): string
    {
        return $this->getHomePath('.local/state');
    }

    protected function getDefaultDataDirectories(): array
    {
        return ['/usr/local/share', '/usr/share'];
    }

    protected function getDefaultConfigDirectories(): array
    {
        return ['/etc/xdg'];
    }

    protected function getDefaultRuntimeDirectory(): string
    {
        return sprintf('/run/user/%d', $this->getUid());
    }

    private function getUid(): int
    {
        if (function_exists('posix_getuid')) {
            return posix_getuid();
        }

        throw new UnsupportedEnvironment('Missing PHP extension: posix');
    }
}
