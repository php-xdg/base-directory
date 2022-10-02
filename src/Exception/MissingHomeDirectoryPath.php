<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Exception;

final class MissingHomeDirectoryPath extends UnsupportedEnvironment
{
    public function __construct()
    {
        parent::__construct('Could not find the home directory path.');
    }
}
