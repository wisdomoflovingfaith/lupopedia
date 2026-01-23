# check_folder_assignments.ps1
$base = "lupo-agents"
$targets = @{
    "ARA" = 10
    "THOTH" = 11
    "METHIS" = 13
    "THALIA" = 14
    "WOLFSIGHT" = 30
    "WOLFNAV" = 31
    "WOLFFORGE" = 32
    "WOLFMIS" = 33
    "WOLFITH" = 34
}

Write-Host "Checking folder assignments..." -ForegroundColor Cyan
Write-Host ""

foreach ($agent in $targets.Keys) {
    $target = $targets[$agent]
    $found = $false
    
    for ($i = 0; $i -lt 200; $i++) {
        $folder = Join-Path $base $i
        if (Test-Path $folder) {
            $json = Join-Path $folder "agent.json"
            if (Test-Path $json) {
                $content = Get-Content $json -Raw | ConvertFrom-Json
                if ($content.code -eq $agent) {
                    if ($i -eq $target) {
                        Write-Host "$agent : folder $i (CORRECT)" -ForegroundColor Green
                    } else {
                        Write-Host "$agent : folder $i (SHOULD BE $target)" -ForegroundColor Red
                    }
                    $found = $true
                    break
                }
            }
        }
    }
    
    if (-not $found) {
        Write-Host "$agent : NOT FOUND (should be folder $target)" -ForegroundColor Yellow
    }
}
