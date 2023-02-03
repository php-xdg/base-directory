<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Tests\Platform\Windows;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Xdg\BaseDirectory\Platform\Windows\KnownFolder;
use Xdg\BaseDirectory\Platform\Windows\KnownFoldersPowerShellProvider;
use Xdg\BaseDirectory\Platform\Windows\PowerShell\DummyScriptExecutor;

final class KnownFolderPowerShellProviderTest extends TestCase
{
    #[DataProvider('getFolderProvider')]
    public function testGetFolder(KnownFolder $id, string $output, ?string $expected): void
    {
        $provider = new KnownFoldersPowerShellProvider(new DummyScriptExecutor($output));
        Assert::assertSame($expected, $provider->get($id));
    }

    public static function getFolderProvider(): iterable
    {
        $output = <<<'EOS'
        Profile=C:\users\me
        LocalAppData=C:\users\me\AppData\Local
        RoamingAppData=C:\users\me\AppData\Roaming
        ProgramData=C:\ProgramData
        EOS;

        yield 'Profile' => [KnownFolder::Profile, $output, 'C:\users\me'];
        yield 'LocalAppData' => [KnownFolder::LocalAppData, $output, 'C:\users\me\AppData\Local'];
        yield 'RoamingAppData' => [KnownFolder::RoamingAppData, $output, 'C:\users\me\AppData\Roaming'];
        yield 'ProgramData' => [KnownFolder::ProgramData, $output, 'C:\ProgramData'];

        $garbage = <<<'EOS'
        Error: unknown exception in file on line 42,666
        Foo=
        Profile=C:\users\me
        EOS;

        yield 'garbage output' => [
            KnownFolder::Profile,
            $garbage,
            'C:\users\me',
        ];
        yield 'not set' => [
            KnownFolder::ProgramData,
            $garbage,
            null,
        ];
    }
}
