<?php declare(strict_types=1);

namespace Xdg\BaseDirectory;

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

    /**
     * Returns the base directory relative to which user-specific non-essential runtime files
     * and other file objects (such as sockets, named pipes, ...) should be stored.
     */
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

    /**
     * Iterates XDG config paths in most user-specific order,
     * and returns the first one matching the given predicate.
     * If no predicate is given, returns the first config path.
     * Paths are iterated from most to least user-specific.
     *
     * @param string $subPath
     * @param null|callable(string): bool $predicate
     * @return string|null
     */
    public function findConfigPath(string $subPath = '', ?callable $predicate = null): ?string;

    /**
     * Iterates XDG data paths in most user-specific order,
     * and returns the first one matching the given predicate.
     * If no predicate is given, returns the first data path.
     * Paths are iterated from most to least user-specific
     *
     * @param string $subPath
     * @param null|callable(string $path): bool $predicate
     * @return string|null
     */
    public function findDataPath(string $subPath = '', ?callable $predicate = null): ?string;

    /**
     * Returns the list of XDG config paths, in least to most user-specific order,
     * optionally filtered by the given predicate.
     *
     * @param string $subPath
     * @param null|callable(string): bool $predicate
     * @return string[]
     */
    public function collectConfigPaths(string $subPath = '', ?callable $predicate = null): array;

    /**
     * Returns the list of XDG data paths, in least to most user-specific order.
     * optionally filtered by the given predicate.
     *
     * @param string $subPath
     * @param null|callable(string): bool $predicate
     * @return string[]
     */
    public function collectDataPaths(string $subPath = '', ?callable $predicate = null): array;
}
