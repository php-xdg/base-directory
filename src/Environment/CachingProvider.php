<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Environment;

/**
 * Environment provider that fetches variables from the supplied provider and caches the result.
 */
final class CachingProvider implements EnvironmentProviderInterface
{
    private array $cache = [];

    public function __construct(
        private readonly EnvironmentProviderInterface $provider,
    ) {
    }

    public function get(string $key): ?string
    {
        return $this->cache[$key] ??= \array_key_exists($key, $this->cache) ? null : $this->provider->get($key);
    }
}
