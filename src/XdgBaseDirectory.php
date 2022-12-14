<?php declare(strict_types=1);

namespace Xdg\BaseDirectory;

use Xdg\Environment\ChainProvider;
use Xdg\Environment\GetenvProvider;
use Xdg\Environment\SuperGlobalsProvider;
use Xdg\BaseDirectory\Platform\MacOsPlatform;
use Xdg\BaseDirectory\Platform\PlatformInterface;
use Xdg\BaseDirectory\Platform\UnixPlatform;
use Xdg\BaseDirectory\Platform\Windows\KnownFoldersProviderFactory;
use Xdg\BaseDirectory\Platform\WindowsPlatform;

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
        $env = new ChainProvider(
            new SuperGlobalsProvider(),
            new GetenvProvider(),
        );

        return match (\PHP_OS_FAMILY) {
            'Windows' => new WindowsPlatform($env, KnownFoldersProviderFactory::fromEnvironment()),
            'Darwin' => new MacOsPlatform($env),
            default => new UnixPlatform($env),
        };
    }
}
