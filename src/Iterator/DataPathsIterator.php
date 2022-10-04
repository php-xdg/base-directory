<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Iterator;

final class DataPathsIterator extends AbstractPathIterator
{
    protected function getPlatformPaths(): array
    {
        return [
            $this->platform->getDataHome(),
            ...$this->platform->getDataDirectories(),
        ];
    }
}
