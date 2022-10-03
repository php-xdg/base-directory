<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Platform\Windows;

/**
 * @internal
 */
enum KnownFolder
{
    case Profile;
    case ProgramData;
    case RoamingAppData;
    case LocalAppData;
}
