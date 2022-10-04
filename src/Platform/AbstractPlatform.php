<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Platform;

use Xdg\BaseDirectory\Environment\EnvironmentProviderInterface;

abstract class AbstractPlatform implements PlatformInterface
{
    public function __construct(
        protected readonly EnvironmentProviderInterface $env,
    ) {
    }

    final public function getDataHome(): string
    {
        return $this->getPathFromEnv('XDG_DATA_HOME') ?? $this->getDefaultDataHome();
    }

    abstract protected function getDefaultDataHome(): string;

    final public function getConfigHome(): string
    {
        return $this->getPathFromEnv('XDG_CONFIG_HOME') ?? $this->getDefaultConfigHome();
    }

    abstract protected function getDefaultConfigHome(): string;

    final public function getCacheHome(): string
    {
        return $this->getPathFromEnv('XDG_CACHE_HOME') ?? $this->getDefaultCacheHome();
    }

    abstract protected function getDefaultCacheHome(): string;

    final public function getStateHome(): string
    {
        return $this->getPathFromEnv('XDG_STATE_HOME') ?? $this->getDefaultStateHome();
    }

    abstract protected function getDefaultStateHome(): string;

    final public function getRuntimeDirectory(): string
    {
        return $this->getPathFromEnv('XDG_RUNTIME_DIR') ?? $this->getDefaultRuntimeDirectory();
    }

    abstract protected function getDefaultRuntimeDirectory(): string;

    final public function getDataDirectories(): array
    {
        if ($paths = $this->getPathListFromEnv('XDG_DATA_DIRS')) {
            return $paths;
        }

        return $this->getDefaultDataDirectories();
    }

    abstract protected function getDefaultDataDirectories(): array;

    final public function getConfigDirectories(): array
    {
        if ($paths = $this->getPathListFromEnv('XDG_CONFIG_DIRS')) {
            return $paths;
        }

        return $this->getDefaultConfigDirectories();
    }

    abstract protected function getDefaultConfigDirectories(): array;

    private function getPathFromEnv(string $var): ?string
    {
        $path = $this->env->get($var);
        if ($path !== null && $this->isAbsolutePath($path)) {
            return $path;
        }

        return null;
    }

    private function getPathListFromEnv(string $var): array
    {
        if ($paths = $this->env->get($var)) {
            return array_filter(
                explode(':', $paths),
                $this->isAbsolutePath(...),
            );
        }

        return [];
    }

    abstract protected function isAbsolutePath(string $path): bool;
}
