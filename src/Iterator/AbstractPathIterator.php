<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Iterator;

use Symfony\Component\Filesystem\Path;
use Traversable;
use Xdg\BaseDirectory\Platform\PlatformInterface;

abstract class AbstractPathIterator implements \IteratorAggregate
{
    public function __construct(
        protected readonly PlatformInterface $platform,
        private readonly string $subPath = '',
        private readonly Direction $direction = Direction::MostSpecificFirst,
    ) {
    }

    /**
     * @return Traversable<string>
     */
    public function getIterator(): Traversable
    {
        $paths = $this->getPlatformPaths();
        if ($this->subPath !== '') {
            $paths = array_map(fn($p) => Path::join($p, $this->subPath), $paths);
        }

        yield from match ($this->direction) {
            Direction::MostSpecificFirst => $paths,
            Direction::MostSpecificLast => array_reverse($paths),
        };
    }

    /**
     * @return string[]
     */
    abstract protected function getPlatformPaths(): array;
}
