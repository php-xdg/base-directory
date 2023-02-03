<?php declare(strict_types=1);

namespace Xdg\BaseDirectory;

use Xdg\BaseDirectory\Platform\MacOsPlatform;
use Xdg\BaseDirectory\Platform\PlatformInterface;
use Xdg\BaseDirectory\Platform\UnixPlatform;
use Xdg\BaseDirectory\Platform\Windows\KnownFoldersProviderFactory;
use Xdg\BaseDirectory\Platform\WindowsPlatform;
use Xdg\Environment\XdgEnvironment;

/**
 * {@see https://specifications.freedesktop.org/basedir-spec/basedir-spec-latest.html}
 */
final class XdgBaseDirectory
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    public static function fromEnvironment(): PlatformInterface
    {
        $env = XdgEnvironment::default();

        return match (\PHP_OS_FAMILY) {
            'Windows' => new WindowsPlatform($env, KnownFoldersProviderFactory::default()),
            'Darwin' => new MacOsPlatform($env),
            default => new UnixPlatform($env),
        };
    }
}
