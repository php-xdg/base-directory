<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Platform;

use Xdg\BaseDirectory\Environment\EnvironmentProviderInterface;

abstract class AbstractPlatform implements PlatformInterface
{
    public function __construct(
        protected readonly EnvironmentProviderInterface $env,
    ) {
    }

    /**
     * Returns the base directory relative to which user-specific data files should be stored.
     */
    final public function getDataHome(): string
    {
        return $this->env->get('XDG_DATA_HOME') ?? $this->getDefaultDataHome();
    }

    abstract protected function getDefaultDataHome(): string;

    /**
     * Returns the base directory relative to which user-specific configuration files should be stored.
     */
    final public function getConfigHome(): string
    {
        return $this->env->get('XDG_CONFIG_HOME') ?? $this->getDefaultConfigHome();
    }

    abstract protected function getDefaultConfigHome(): string;

    /**
     * Returns the base directory relative to which user-specific non-essential (cached) data should be written.
     */
    final public function getCacheHome(): string
    {
        return $this->env->get('XDG_CACHE_HOME') ?? $this->getDefaultCacheHome();
    }

    abstract protected function getDefaultCacheHome(): string;

    /**
     * Returns the base directory relative to which user-specific state files should be stored.
     */
    final public function getStateHome(): string
    {
        return $this->env->get('XDG_STATE_HOME') ?? $this->getDefaultStateHome();
    }

    abstract protected function getDefaultStateHome(): string;

    final public function getRuntimeDirectory(): string
    {
        return $this->env->get('XDG_RUNTIME_DIR') ?? $this->getDefaultRuntimeDirectory();
    }

    abstract protected function getDefaultRuntimeDirectory(): string;

    /**
     * Returns the preference-ordered set of base directories to search for data files
     * in addition to the base directory return by {@see self::getDataHome()}.
     *
     * @return string[]
     */
    final public function getDataDirectories(): array
    {
        if ($env = $this->env->get('XDG_DATA_DIRS')) {
            return explode(':', $env);
        }

        return $this->getDefaultDataDirectories();
    }

    abstract protected function getDefaultDataDirectories(): array;

    /**
     * Returns the preference-ordered set of base directories to search for config files
     * in addition to the base directory return by {@see self::getConfigHome()}.
     *
     * @return string[]
     */
    final public function getConfigDirectories(): array
    {
        if ($env = $this->env->get('XDG_CONFIG_DIRS')) {
            return explode(':', $env);
        }

        return $this->getDefaultConfigDirectories();
    }

    abstract protected function getDefaultConfigDirectories(): array;
}
