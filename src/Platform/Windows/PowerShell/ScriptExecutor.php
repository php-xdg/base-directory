<?php declare(strict_types=1);

namespace Xdg\BaseDirectory\Platform\Windows\PowerShell;

/**
 * @internal
 */
final class ScriptExecutor implements ScriptExecutorInterface
{
    private static string|false|null $binary;

    public function isSupported(): bool
    {
        return !!self::getBinary();
    }

    public function execute(\SplFileObject|string $script): string
    {
        if (!$bin = self::getBinary()) {
            return '';
        }

        $p = proc_open(
            [$bin, '-NonInteractive', '-Command', '-'],
            [0 => ['pipe', 'r'], 1 => ['pipe', 'w']],
            $pipes,
            null,
            array_merge(getenv(), ['NO_COLOR' => '1', 'TERM' => 'dumb']),
            ['bypass_shell' => true, 'suppress_errors' => true],
        );

        if (!\is_resource($p)) {
            return '';
        }

        if (\is_string($script)) {
            fwrite($pipes[0], $script);
        } else {
            fwrite($pipes[0], $script->fread($script->getSize()));
        }
        fclose($pipes[0]);

        $output = stream_get_contents($pipes[1]);
        $code = proc_close($p);
        if ($code !== 0) {
            return '';
        }

        return $output;
    }

    private static function getBinary(): string|null|false
    {
        self::$binary ??= self::findBinary('powershell') ?? self::findBinary('pwsh');
        if (self::$binary === null) {
            self::$binary = false;
        }

        return self::$binary;
    }

    private static function findBinary(string $name): ?string
    {
        $which = match (\PHP_OS_FAMILY) {
            'Windows' => 'where',
            default => 'which',
        };
        if (false === $output = exec("{$which} {$name}")) {
            return null;
        }

        if ($bin = strtok($output, \PHP_EOL)) {
            return $bin;
        }

        return null;
    }
}
