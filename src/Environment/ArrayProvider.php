<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Environment;

use Xdg\BaseDirectory\Exception\UnexpectedEnvValue;

/**
 * Environment provider that fetches variables from the supplied array.
 */
final class ArrayProvider implements EnvironmentProviderInterface
{
    public function __construct(
        private readonly array $env,
    ) {
    }

    public function get(string $key): ?string
    {
        return match ($value = $this->env[$key] ?? null) {
            null, '', false => null,
            default => is_scalar($value) ? (string)$value : throw new UnexpectedEnvValue($key, $value),
        };
    }
}
