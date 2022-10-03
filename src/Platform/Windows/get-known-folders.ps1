<#
.SYNOPSIS
    Prints known folders in a .env format (i.e. Profile=C:\users\me)
.LINK
    https://renenyffenegger.ch/notes/Windows/dirs/_known-folders
#>

if ("shell32" -as [type]) {} else {
  Add-Type @"
    using System;
    using System.Runtime.InteropServices;

    public class shell32 {
      [DllImport("shell32.dll", CharSet = CharSet.Unicode)]
      private static extern int SHGetKnownFolderPath(
        [MarshalAs(UnmanagedType.LPStruct)]
        Guid       rfid,
        uint       dwFlags,
        IntPtr     hToken,
        out IntPtr pszPath
      );

      public static string GetKnownFolderPath(Guid rfid) {
        IntPtr pszPath;
        if (SHGetKnownFolderPath(rfid, 0, IntPtr.Zero, out pszPath) != 0) {
          return "";
        }
        string path = Marshal.PtrToStringUni(pszPath);
        Marshal.FreeCoTaskMem(pszPath);
        return path;
      }
    }
"@
}

# Define known folder GUIDs
$Global:KnownFolders = @{
#  'Desktop' = 'B4BFCC3A-DB2C-424C-B029-7FE99A87C641';
#  'Documents' = 'FDD39AD0-238F-46AF-ADB4-6C85480369C7';
#  'Downloads' = '374DE290-123F-4565-9164-39C4925E467B';
#  'Favorites' = '1777F761-68AD-4D8A-87BD-30B759FA33DD';
#  'Fonts' = 'FD228CB7-AE11-4AE3-864C-16F3910AB8FE';
#  'Games' = 'CAC52C1A-B53D-4edc-92D7-6B2E8AC19434';
  'LocalAppData' = 'F1B32785-6FBA-4FCF-9D55-7B8E7F157091';
#  'LocalAppDataLow' = 'A520A1A4-1780-4FF6-BD18-167343C5AF16';
#  'Pictures' = '33E28130-4E1E-4676-835A-98395C3BC3BB';
  'Profile' = '5E6C858F-0E22-4760-9AFE-EA3317B67173';
  'ProgramData' = '62AB5D82-FDC1-4DC3-A9DD-070D1D495D97';
#  'Public' = 'DFDF76A2-C82A-4D63-906A-5644AC457385';
  'RoamingAppData' = '3EB685DB-65F9-4CF6-A03A-E3EF65729F3D';
#  'System' = '1AC14E77-02E7-4E5D-B744-2EB1AE5198B7';
#  'Windows' = 'F38BF404-1D43-42F2-9305-67DE0B28FC23';
}

function Get-KnownFolderPath {
  Param (
    [Parameter(Mandatory = $true)]
    [string]$folder
  )
  $guid = $KnownFolders.$("$folder")
  $result = $([shell32]::GetKnownFolderPath("{$guid}"))
  return "$result"
}

$KnownFolders.Keys | ForEach-Object {
  try {
    $path = Get-KnownFolderPath($_)
    Write-Host "$($_)=$($path)"
  } catch {}
}

exit 0
