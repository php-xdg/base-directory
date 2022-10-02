<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Platform;

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
        if (null !== $uid = $this->getUid()) {
            return "/run/user/{$uid}";
        }

        return sys_get_temp_dir();
    }

    private function getUid(): ?int
    {
        if (function_exists('posix_getuid')) {
            return posix_getuid();
        }
        if (is_readable('/proc/self/status')) {
            return stat('/proc/self/status')['uid'];
        }

        return null;
    }
}
