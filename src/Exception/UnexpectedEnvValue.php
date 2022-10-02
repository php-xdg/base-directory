<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Exception;

final class UnexpectedEnvValue extends \UnexpectedValueException
{
    public function __construct(string $key, mixed $value)
    {
        parent::__construct(sprintf(
            'Unexpected value of type "%s" for environment variable "%s", expected a scalar.',
            get_debug_type($value),
            $key,
        ));
    }
}
