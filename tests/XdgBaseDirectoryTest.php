<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Xdg\BaseDirectory\Platform\PlatformInterface;
use Xdg\BaseDirectory\XdgBaseDirectory;

final class XdgBaseDirectoryTest extends TestCase
{
    public function testFromEnvironment(): void
    {
        Assert::assertInstanceOf(PlatformInterface::class, XdgBaseDirectory::fromEnvironment());
    }
}
