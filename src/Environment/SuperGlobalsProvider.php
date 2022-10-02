<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Environment;

use Xdg\BaseDirectory\Exception\UnexpectedEnvValue;

/**
 * Environment provider that fetches variables from $_SERVER and $_ENV super-globals.
 */
final class SuperGlobalsProvider implements EnvironmentProviderInterface
{
    public function get(string $key): ?string
    {
        return match ($value = $_SERVER[$key] ?? $_ENV[$key] ?? null) {
            null, '', false => null,
            default => is_scalar($value) ? (string)$value : throw new UnexpectedEnvValue($key, $value),
        };
    }
}
