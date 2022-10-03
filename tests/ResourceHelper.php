<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests;

use Symfony\Component\Filesystem\Path;

final class ResourceHelper
{
    public static function getPath(string $path): string
    {
        $absolutePath = Path::join(__DIR__, $path);
        if (!file_exists($absolutePath)) {
            throw new \LogicException(sprintf(
                'Resource not found: %s',
                $absolutePath,
            ));
        }

        return $absolutePath;
    }
}
