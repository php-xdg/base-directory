<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Iterator;

final class ConfigPathsIterator extends AbstractPathIterator
{
    protected function getPlatformPaths(): array
    {
        return [
            $this->platform->getConfigHome(),
            ...$this->platform->getConfigDirectories(),
        ];
    }
}
