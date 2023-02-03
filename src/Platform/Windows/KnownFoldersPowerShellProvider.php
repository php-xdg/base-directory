<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Platform\Windows;

use Xdg\BaseDirectory\Platform\Windows\PowerShell\ScriptExecutorInterface;

/**
 * @internal
 */
final class KnownFoldersPowerShellProvider implements KnownFoldersProviderInterface
{
    private ?array $cache = null;

    public function __construct(
        private readonly ScriptExecutorInterface $executor,
    ) {
    }

    public function isSupported(): bool
    {
        return $this->executor->isSupported();
    }

    public function get(KnownFolder $id): ?string
    {
        $this->cache ??= $this->fetchDirectories();
        return $this->cache[$id->name] ?? null;
    }

    private function fetchDirectories(): array
    {
        $output = $this->executor->execute(__DIR__ . '/get-known-folders.ps1');
        return $this->parseOutput($output);
    }

    private function parseOutput(string $output): array
    {
        $lines = preg_split('/\R/', $output, -1, \PREG_SPLIT_NO_EMPTY);
        $folders = [];
        foreach ($lines as $line) {
            if (preg_match('/^(?<key>[a-z]+)=(?<value>.+)$/i', $line, $m)) {
                ['key' => $key, 'value' => $value] = $m;
                $folders[$key] = $value;
            }
        }

        return $folders;
    }
}
