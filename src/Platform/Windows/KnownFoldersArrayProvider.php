<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Platform\Windows;

/**
 * @internal
 */
final class KnownFoldersArrayProvider implements KnownFoldersProviderInterface
{
    public function __construct(
        /**
         * @var array<string, string> $folders
         */
        private readonly array $folders,
    ) {
    }

    public function isSupported(): bool
    {
        return true;
    }

    public function get(KnownFolder $id): ?string
    {
        return $this->folders[$id->name] ?? null;
    }
}
