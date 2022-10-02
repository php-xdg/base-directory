<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Platform;

final class MacOsPlatform extends AbstractPlatform
{
    use UnixPathTrait;

    private const LIBRARY_APPLICATION_SUPPORT = 'Library/Application Support';
    private const LIBRARY_CACHES = 'Library/Caches';
    private const LIBRARY_PREFERENCES = 'Library/Preferences';

    protected function getDefaultDataHome(): string
    {
        return $this->getHomePath(self::LIBRARY_APPLICATION_SUPPORT);
    }

    protected function getDefaultConfigHome(): string
    {
        return $this->getHomePath(self::LIBRARY_APPLICATION_SUPPORT);
    }

    protected function getDefaultCacheHome(): string
    {
        return $this->getHomePath(self::LIBRARY_CACHES);
    }

    protected function getDefaultStateHome(): string
    {
        return $this->getHomePath(self::LIBRARY_APPLICATION_SUPPORT);
    }

    protected function getDefaultDataDirectories(): array
    {
        return [
            '/' . self::LIBRARY_APPLICATION_SUPPORT,
        ];
    }

    protected function getDefaultConfigDirectories(): array
    {
        return [
            $this->getHomePath(self::LIBRARY_PREFERENCES),
            '/' . self::LIBRARY_APPLICATION_SUPPORT,
            '/' . self::LIBRARY_PREFERENCES,
        ];
    }

    protected function getDefaultRuntimeDirectory(): string
    {
        return $this->getHomePath(self::LIBRARY_APPLICATION_SUPPORT);
    }
}
