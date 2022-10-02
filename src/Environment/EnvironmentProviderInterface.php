<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Environment;

interface EnvironmentProviderInterface
{
    public function get(string $key): ?string;
}
