<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Platform;

interface PlatformInterface
{
    /**
     * Returns the base directory relative to which user-specific data files should be stored.
     */
    public function getDataHome(): string;

    /**
     * Returns the base directory relative to which user-specific configuration files should be stored.
     */
    public function getConfigHome(): string;

    /**
     * Returns the base directory relative to which user-specific non-essential (cached) data should be written.
     */
    public function getCacheHome(): string;

    /**
     * Returns the base directory relative to which user-specific state files should be stored.
     */
    public function getStateHome(): string;

    public function getRuntimeDirectory(): string;

    /**
     * Returns the preference-ordered set of base directories to search for data files
     * in addition to the base directory return by {@see self::getDataHome()}.
     *
     * @return string[]
     */
    public function getDataDirectories(): array;

    /**
     * Returns the preference-ordered set of base directories to search for config files
     * in addition to the base directory return by {@see self::getConfigHome()}.
     *
     * @return string[]
     */
    public function getConfigDirectories(): array;
}
