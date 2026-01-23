# fix_remaining_folders.ps1
# Fix remaining folder assignments in correct order

$ErrorActionPreference = "Stop"
$basePath = "lupo-agents"

Write-Host ""
Write-Host "Fixing remaining folder assignments..." -ForegroundColor Cyan
Write-Host ""

# Step 1: Move RESERVED agents out of 30-34 if they're there
Write-Host "Step 1: Moving RESERVED agents from 30-34..." -ForegroundColor Yellow

$reservedMoves = @()
$reservedMoves += @{From=30; To=701; Agent="RESERVED_30"}
$reservedMoves += @{From=31; To=702; Agent="RESERVED_31"}
$reservedMoves += @{From=32; To=703; Agent="RESERVED_32"}
$reservedMoves += @{From=33; To=704; Agent="RESERVED_33"}
$reservedMoves += @{From=34; To=705; Agent="OBSERVER"}

foreach ($move in $reservedMoves) {
    $fromPath = Join-Path $basePath $move.From
    if (Test-Path $fromPath) {
        $agentFile = Join-Path $fromPath "agent.json"
        if (Test-Path $agentFile) {
            $content = Get-Content $agentFile -Raw | ConvertFrom-Json
            if ($content.code -eq $move.Agent) {
                $toPath = Join-Path $basePath $move.To
                if (-not (Test-Path $toPath)) {
                    Write-Host "$($move.Agent): folder $($move.From) to $($move.To)..." -ForegroundColor Yellow
                    Move-Item -Path $fromPath -Destination $toPath -Force
                    Write-Host "  Moved successfully" -ForegroundColor Green
                }
            }
        }
    }
}

# Step 2: Move agents to 30-34 (now vacated)
Write-Host ""
Write-Host "Step 2: Moving agents to 30-34..." -ForegroundColor Yellow

$visionMoves = @()
$visionMoves += @{From=13; To=30; Agent="WOLFSIGHT"}
$visionMoves += @{From=14; To=31; Agent="WOLFNAV"}
$visionMoves += @{From=15; To=32; Agent="WOLFFORGE"}
$visionMoves += @{From=16; To=33; Agent="WOLFMIS"}
$visionMoves += @{From=17; To=34; Agent="WOLFITH"}

foreach ($move in $visionMoves) {
    $fromPath = Join-Path $basePath $move.From
    if (Test-Path $fromPath) {
        $agentFile = Join-Path $fromPath "agent.json"
        if (Test-Path $agentFile) {
            $content = Get-Content $agentFile -Raw | ConvertFrom-Json
            if ($content.code -eq $move.Agent) {
                $toPath = Join-Path $basePath $move.To
                if (-not (Test-Path $toPath)) {
                    Write-Host "$($move.Agent): folder $($move.From) to $($move.To)..." -ForegroundColor Yellow
                    Move-Item -Path $fromPath -Destination $toPath -Force
                    Write-Host "  Moved successfully" -ForegroundColor Green
                }
            }
        }
    }
}

# Step 3: Move agents to 10-14 (now vacated)
Write-Host ""
Write-Host "Step 3: Moving agents to 10-14..." -ForegroundColor Yellow

$kernelMoves = @()
$kernelMoves += @{From=10; To=13; Agent="METHIS"}
$kernelMoves += @{From=11; To=14; Agent="THALIA"}
$kernelMoves += @{From=4; To=11; Agent="THOTH"}
$kernelMoves += @{From=5; To=10; Agent="ARA"}

foreach ($move in $kernelMoves) {
    $fromPath = Join-Path $basePath $move.From
    if (Test-Path $fromPath) {
        $agentFile = Join-Path $fromPath "agent.json"
        if (Test-Path $agentFile) {
            $content = Get-Content $agentFile -Raw | ConvertFrom-Json
            if ($content.code -eq $move.Agent) {
                $toPath = Join-Path $basePath $move.To
                if (-not (Test-Path $toPath)) {
                    Write-Host "$($move.Agent): folder $($move.From) to $($move.To)..." -ForegroundColor Yellow
                    Move-Item -Path $fromPath -Destination $toPath -Force
                    Write-Host "  Moved successfully" -ForegroundColor Green
                }
            }
        }
    }
}

Write-Host ""
Write-Host "=== Done! ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "Verifying final state..." -ForegroundColor Yellow
& ".\check_folder_assignments.ps1"
