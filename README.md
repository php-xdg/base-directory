# xdg/base-directory

[![codecov](https://codecov.io/gh/php-xdg/base-directory/branch/main/graph/badge.svg?token=9J51T4A6S8)](https://codecov.io/gh/php-xdg/base-directory)

PHP implementation of the [XDG Base Directory Specification](https://specifications.freedesktop.org/basedir-spec/basedir-spec-latest.html),
with sensible default fallbacks for non-linux platforms.

## Installation

```sh
composer require xdg/base-directory
```

## Usage

```php
use Xdg\BaseDirectory\XdgBaseDirectory;

// we start by instantiating a platform:
$platform = XdgBaseDirectory::fromEnvironment();
// get the XDG_CONFIG_HOME directory
$configHome = $platform->getConfigHome();
// find the most user-specific existing configuration file:
$configPath = $platform->findConfigPath('my-app/config.json', \file_exists(...));
```

See [PlatformInterface](src/Platform/PlatformInterface.php) for all available methods.


## Fallback values for Windows and macOS platforms

While on most Unix platforms, the XDG environment variables are usually defined,
this is typically not the case on Windows and macOS.

This library choses to deviate from the spec and provide sensible defaults on those platforms.

### Windows

On windows, when an XDG environment variable is not set, we first try to find a matching
[Known Folder](https://learn.microsoft.com/en-us/windows/win32/shell/known-folders):

|     XDG env     |       Known Folder(s)       |
|:---------------:|:---------------------------:|
|      HOME       |           Profile           |
| XDG_CONFIG_HOME |        LocalAppData         |
|  XDG_DATA_HOME  |        LocalAppData         |
| XDG_STATE_HOME  |        LocalAppData         |
| XDG_CACHE_HOME  |     LocalAppData/cache      |
| XDG_RUNTIME_DIR |        LocalAppData         |
| XDG_CONFIG_DIRS | ProgramData, RoamingAppData |
|  XDG_DATA_DIRS  | RoamingAppData, ProgramData |

If the known folder is not defined, we then try to fall back to a windows-specific environment variable,
then fall back to a static default:

|   KnownFolder   |            Windows env            |                  default                  |
|:---------------:|:---------------------------------:|:-----------------------------------------:|
|     Profile     | USERPROFILE, HOMEDRIVE + HOMEPATH |                🚨 Error 🚨                |
|  LocalAppData   |           LOCALAPPDATA            |            $HOME/AppData/Local            |
| 	RoamingAppData |              APPDATA              |           $HOME/AppData/Roaming           |
|  	ProgramData   |            ProgramData            | %SystemDrive%/ProgramData, C:/ProgramData |


### MacOS

|     XDG env     |                                   Fallback                                    |
|:---------------:|:-----------------------------------------------------------------------------:|
| XDG_CONFIG_HOME |                       $HOME/Library/Application Support                       |
|  XDG_DATA_HOME  |                       $HOME/Library/Application Support                       |
| XDG_STATE_HOME  |                       $HOME/Library/Application Support                       |
| XDG_CACHE_HOME  |                             $HOME/Library/caches                              |
| XDG_RUNTIME_DIR |                       $HOME/Library/Application Support                       |
| XDG_CONFIG_DIRS | $HOME/Library/Preferences, /Library/Application Support, /Library/Preferences |
|  XDG_DATA_DIRS  |                         /Library/Application Support                          |
