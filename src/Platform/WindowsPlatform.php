<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Platform;

use Symfony\Component\Filesystem\Path;
use Xdg\BaseDirectory\Exception\MissingHomeDirectoryPath;

/**
 * @todo find a way to call the KnownFolders API
 * @see https://learn.microsoft.com/en-us/windows/win32/shell/csidl
 * @see https://learn.microsoft.com/en-us/windows/win32/shell/knownfolderid
 */
final class WindowsPlatform extends AbstractPlatform
{
    /**
     * The file system directory that serves as a common repository for application-specific data.
     * A typical path is C:\Documents and Settings\username\Application Data.
     */
    private const APP_DATA = 'APPDATA';
    private const APP_DATA_DEFAULT = 'AppData\\Roaming';
    /**
     * The file system directory that serves as a data repository for local (non-roaming) applications.
     * A typical path is C:\Documents and Settings\username\Local Settings\Application Data.
     */
    private const LOCAL_APP_DATA = 'LOCALAPPDATA';
    private const LOCAL_APP_DATA_DEFAULT = 'AppData\\Local';
    /**
     * The file system directory that contains application data for all users.
     * A typical path is C:\Documents and Settings\All Users\Application Data.
     * This folder is used for application data that is not user specific.
     */
    private const PROGRAM_DATA = 'ProgramData';
    private const PROGRAM_DATA_DEFAULT = 'ProgramData';

    protected function getDefaultDataHome(): string
    {
        return $this->getLocalAppDataPath();
    }

    protected function getDefaultConfigHome(): string
    {
        return $this->getLocalAppDataPath();
    }

    protected function getDefaultCacheHome(): string
    {
        return Path::join($this->getLocalAppDataPath(), 'cache');
    }

    protected function getDefaultStateHome(): string
    {
        return $this->getLocalAppDataPath();
    }

    protected function getDefaultDataDirectories(): array
    {
        return [
            $this->getAppDataPath(),
            $this->getProgramDataPath(),
        ];
    }

    protected function getDefaultConfigDirectories(): array
    {
        return [
            $this->getProgramDataPath(),
            $this->getAppDataPath(),
        ];
    }

    protected function getDefaultRuntimeDirectory(): string
    {
        return $this->getLocalAppDataPath();
    }

    private function getAppDataPath(): string
    {
        return $this->env->get(self::APP_DATA) ?? $this->getHomePath(self::APP_DATA_DEFAULT);
    }

    private function getLocalAppDataPath(): string
    {
        return $this->env->get(self::LOCAL_APP_DATA) ?? $this->getHomePath(self::LOCAL_APP_DATA_DEFAULT);
    }

    private function getProgramDataPath(): string
    {
        return $this->env->get(self::PROGRAM_DATA) ?? $this->getSystemPath(self::PROGRAM_DATA_DEFAULT);
    }

    private function getHomePath(string $path): string
    {
        return Path::join($this->getHomeDirectory(), $path);
    }

    private function getSystemPath(string $path): string
    {
        return Path::join($this->getSystemDrive(), $path);
    }

    private function getHomeDirectory(): string
    {
        $drive = $this->env->get('HOMEDRIVE');
        $path = $this->env->get('HOMEPATH');
        if ($drive && $path) {
            return $drive . $path;
        }

        throw new MissingHomeDirectoryPath();
    }

    private function getSystemDrive(): string
    {
        return $this->env->get('SystemDrive') ?? 'C:';
    }
}
