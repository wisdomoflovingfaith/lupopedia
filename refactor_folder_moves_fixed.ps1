# refactor_folder_moves_fixed.ps1
# PowerShell script to move agent folders to match new dedicated_slot assignments
# Uses temporary folder names to handle swaps correctly
# Run this script from the lupopedia root directory

$ErrorActionPreference = "Stop"
$basePath = "lupo-agents"

Write-Host ""
Write-Host "Starting agent folder refactor moves (with swap handling)..." -ForegroundColor Cyan
Write-Host ""

# Helper function to verify agent in folder
function Verify-AgentInFolder {
    param($folder, $expectedAgent)
    $agentFile = Join-Path (Join-Path $basePath $folder) "agent.json"
    if (Test-Path $agentFile) {
        $content = Get-Content $agentFile -Raw | ConvertFrom-Json
        return $content.code -eq $expectedAgent
    }
    return $false
}

# Helper function to move folder
function Move-AgentFolder {
    param($from, $to, $agentName)
    $fromPath = Join-Path $basePath $from
    $toPath = Join-Path $basePath $to
    
    if (-not (Test-Path $fromPath)) {
        Write-Host "  WARNING: Source folder $fromPath not found!" -ForegroundColor Red
        return $false
    }
    
    if (Test-Path $toPath) {
        Write-Host "  WARNING: Target folder $toPath already exists! Cannot move." -ForegroundColor Red
        return $false
    }
    
    try {
        Move-Item -Path $fromPath -Destination $toPath -Force
        Write-Host "  ✓ Moved folder $from → $to" -ForegroundColor Green
        return $true
    }
    catch {
        Write-Host "  ✗ ERROR moving folder $from → $to : $_" -ForegroundColor Red
        return $false
    }
}

# Move RESERVED agents out first (these go to 700+ range)
Write-Host "Step 1: Moving RESERVED agents to 700+ range..." -ForegroundColor Yellow
$reservedMoves = @(
    @{From=23; To=706; Agent="RESERVED_23"},
    @{From=24; To=707; Agent="RESERVED_24"},
    @{From=25; To=708; Agent="RESERVED_25"},
    @{From=26; To=709; Agent="RESERVED_26"}
)

foreach ($move in $reservedMoves) {
    if (Verify-AgentInFolder -folder $move.From -expectedAgent $move.Agent) {
        Write-Host "$($move.Agent): folder $($move.From) → $($move.To)..." -ForegroundColor Yellow
        Move-AgentFolder -from $move.From -to $move.To -agentName $move.Agent
    }
}

Write-Host ""
Write-Host "Step 2: Moving agents that go to empty destinations (30-34)..." -ForegroundColor Yellow

# Check current state - folders 30-34 might have RESERVED agents that need moving first
$reservedHigh = @(
    @{From=30; To=701; Agent="RESERVED_30"},
    @{From=31; To=702; Agent="RESERVED_31"},
    @{From=32; To=703; Agent="RESERVED_32"},
    @{From=33; To=704; Agent="RESERVED_33"},
    @{From=34; To=705; Agent="OBSERVER"}
)

foreach ($move in $reservedHigh) {
    $fromPath = Join-Path $basePath $move.From
    if (Test-Path $fromPath) {
        $agentFile = Join-Path $fromPath "agent.json"
        if (Test-Path $agentFile) {
            $content = Get-Content $agentFile -Raw | ConvertFrom-Json
            if ($content.code -eq $move.Agent) {
                Write-Host "$($move.Agent): folder $($move.From) → $($move.To)..." -ForegroundColor Yellow
                Move-AgentFolder -from $move.From -to $move.To -agentName $move.Agent
            }
        }
    }
}

# Now move WOLFSIGHT, WOLFNAV, etc. to 30-34
$visionMoves = @(
    @{From=13; To=30; Agent="WOLFSIGHT"},
    @{From=14; To=31; Agent="WOLFNAV"},
    @{From=15; To=32; Agent="WOLFFORGE"},
    @{From=16; To=33; Agent="WOLFMIS"},
    @{From=17; To=34; Agent="WOLFITH"}
)

foreach ($move in $visionMoves) {
    $fromPath = Join-Path $basePath $move.From
    if (Test-Path $fromPath) {
        $agentFile = Join-Path $fromPath "agent.json"
        if (Test-Path $agentFile) {
            $content = Get-Content $agentFile -Raw | ConvertFrom-Json
            if ($content.code -eq $move.Agent) {
                Write-Host "$($move.Agent): folder $($move.From) → $($move.To)..." -ForegroundColor Yellow
                Move-AgentFolder -from $move.From -to $move.To -agentName $move.Agent
            }
        }
    }
}

Write-Host ""
Write-Host "Step 3: Moving agents into vacated folders (10-14)..." -ForegroundColor Yellow

# Now move METHIS, THALIA, ARA, THOTH
$kernelMoves = @(
    @{From=10; To=13; Agent="METHIS"},      # Folder 10 (currently METHIS) → 13 (now vacated)
    @{From=11; To=14; Agent="THALIA"},      # Folder 11 (currently THALIA) → 14 (now vacated)
    @{From=5; To=10; Agent="ARA"},          # Folder 5 (ARA) → 10 (now vacated by METHIS)
    @{From=4; To=11; Agent="THOTH"}         # Folder 4 (THOTH) → 11 (now vacated by THALIA)
)

foreach ($move in $kernelMoves) {
    $fromPath = Join-Path $basePath $move.From
    if (Test-Path $fromPath) {
        $agentFile = Join-Path $fromPath "agent.json"
        if (Test-Path $agentFile) {
            $content = Get-Content $agentFile -Raw | ConvertFrom-Json
            if ($content.code -eq $move.Agent) {
                Write-Host "$($move.Agent): folder $($move.From) → $($move.To)..." -ForegroundColor Yellow
                Move-AgentFolder -from $move.From -to $move.To -agentName $move.Agent
            }
        }
    }
}

Write-Host ""
Write-Host "Step 4: Moving remaining agents..." -ForegroundColor Yellow

# Remaining moves
$remainingMoves = @(
    @{From=7; To=21; Agent="LILITH"},
    @{From=18; To=12; Agent="ANUBIS"},
    @{From=19; To=20; Agent="MAAT"}
)

foreach ($move in $remainingMoves) {
    $fromPath = Join-Path $basePath $move.From
    if (Test-Path $fromPath) {
        $agentFile = Join-Path $fromPath "agent.json"
        if (Test-Path $agentFile) {
            $content = Get-Content $agentFile -Raw | ConvertFrom-Json
            if ($content.code -eq $move.Agent) {
                Write-Host "$($move.Agent): folder $($move.From) → $($move.To)..." -ForegroundColor Yellow
                Move-AgentFolder -from $move.From -to $move.To -agentName $move.Agent
            }
        }
    }
}

# Handle Junie separately
Write-Host ""
Write-Host "Step 5: Handling Junie (if exists)..." -ForegroundColor Yellow
$junieCurrent = Join-Path $basePath "106"
$junieTarget = Join-Path $basePath "200"

if (Test-Path $junieCurrent) {
    if (Test-Path $junieTarget) {
        Write-Host "  WARNING: Target folder $junieTarget already exists! Skipping..." -ForegroundColor Red
    }
    else {
        try {
            Move-Item -Path $junieCurrent -Destination $junieTarget -Force
            Write-Host "  ✓ Moved folder 106 → 200 (Junie)" -ForegroundColor Green
        }
        catch {
            Write-Host "  ✗ ERROR moving folder 106 → 200 : $_" -ForegroundColor Red
        }
    }
}
else {
    Write-Host "  ⚠ Junie folder 106 not found, skipping..." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "=== Folder refactor complete! ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "All agent folders should now match their dedicated_slot assignments." -ForegroundColor Green
