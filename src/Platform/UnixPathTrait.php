<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Platform;

use Symfony\Component\Filesystem\Path;
use Xdg\BaseDirectory\Exception\MissingHomeDirectoryPath;

trait UnixPathTrait
{
    private function getHomePath(string $path): string
    {
        return Path::join($this->getHomeDirectory(), $path);
    }

    private function getHomeDirectory(): string
    {
        if (null !== $home = $this->env->get('HOME')) {
            return $home;
        }

        throw new MissingHomeDirectoryPath();
    }

    protected function isAbsolutePath(string $path): bool
    {
        return str_starts_with($path, '/');
    }
}
