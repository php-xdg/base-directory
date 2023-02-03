<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Platform\Windows;

use Xdg\BaseDirectory\Platform\Windows\PowerShell\ScriptExecutor;

/**
 * @internal
 */
final class KnownFoldersProviderFactory
{
    /** @var KnownFoldersProviderInterface[] */
    private readonly array $providers;

    public function __construct(
        KnownFoldersProviderInterface ...$providers,
    ) {
        $this->providers = $providers;
    }

    public static function default(): KnownFoldersProviderInterface
    {
        $self = new self(
            new KnownFoldersPowerShellProvider(new ScriptExecutor()),
        );

        return $self->create();
    }

    public function create(): KnownFoldersProviderInterface
    {
        foreach ($this->providers as $provider) {
            if ($provider->isSupported()) {
                return $provider;
            }
        }

        return new KnownFoldersArrayProvider([]);
    }
}
