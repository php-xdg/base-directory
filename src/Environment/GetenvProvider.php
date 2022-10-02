<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Environment;

/**
 * Environment provider that fetches variables using {@see getenv()}.
 */
final class GetenvProvider implements EnvironmentProviderInterface
{
    public function get(string $key): ?string
    {
        return match ($value = getenv($key)) {
            '', false => null,
            default => $value,
        };
    }
}
