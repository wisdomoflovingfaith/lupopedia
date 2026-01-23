# refactor_folder_moves.ps1
# PowerShell script to move agent folders to match new dedicated_slot assignments from toon file
# Based on lupo_agent_registry_range_expansion refactor
# Run this script from the lupopedia root directory

$ErrorActionPreference = "Stop"
$basePath = "lupo-agents"

Write-Host "Starting agent folder refactor moves..." -ForegroundColor Cyan

# Mapping: Agent Name => Current Folder => Target Folder (from toon file dedicated_slot values)
# IMPORTANT: Process WOLFENA first (folder 3 → 22) so that ROSE can move to folder 3
$moves = @(
    # Agents that are already in correct folders (no move needed)
    # SYSTEM: folder 0 → slot 0 ✓
    # CAPTAIN: folder 1 → slot 1 ✓  
    # WOLFIE: folder 2 → slot 2 ✓
    
    # STEP 1: Move RESERVED agents out of slots 23-26 FIRST to make room for real agents
    @{Agent="RESERVED_23"; Current=23; Target=706; Description="RESERVED_23: folder 23 → 706 (make room for CHRONOS)"},
    @{Agent="RESERVED_24"; Current=24; Target=707; Description="RESERVED_24: folder 24 → 707 (make room for CADUCEUS)"},
    @{Agent="RESERVED_25"; Current=25; Target=708; Description="RESERVED_25: folder 25 → 708 (make room for AGAPE)"},
    @{Agent="RESERVED_26"; Current=26; Target=709; Description="RESERVED_26: folder 26 → 709 (make room for ERIS)"},
    
    # STEP 2: Move agents that go to empty destinations (30-34) to vacate folders 13-17
    @{Agent="WOLFSIGHT"; Current=13; Target=30; Description="WOLFSIGHT: folder 13 → 30 (vacates folder 13)"},
    @{Agent="WOLFNAV"; Current=14; Target=31; Description="WOLFNAV: folder 14 → 31 (vacates folder 14)"},
    @{Agent="WOLFFORGE"; Current=15; Target=32; Description="WOLFFORGE: folder 15 → 32"},
    @{Agent="WOLFMIS"; Current=16; Target=33; Description="WOLFMIS: folder 16 → 33"},
    @{Agent="WOLFITH"; Current=17; Target=34; Description="WOLFITH: folder 17 → 34"},
    
    # STEP 3: Move agents into vacated folders (13-14 now available)
    @{Agent="METHIS"; Current=10; Target=13; Description="METHIS: folder 10 → 13 (folder 13 now vacated)"},
    @{Agent="THALIA"; Current=11; Target=14; Description="THALIA: folder 11 → 14 (folder 14 now vacated)"},
    
    # STEP 4: Move THOTH and ARA (folders 11 and 10 now vacated)
    @{Agent="THOTH"; Current=4; Target=11; Description="THOTH: folder 4 → 11 (folder 11 now vacated)"},
    @{Agent="ARA"; Current=5; Target=10; Description="ARA: folder 5 → 10 (folder 10 now vacated)"},
    
    # STEP 5: Move CHRONOS out of folder 22 so WOLFENA can move there
    @{Agent="CHRONOS"; Current=22; Target=23; Description="CHRONOS: folder 22 → 23 (folder 23 now vacated by RESERVED_23)"},
    
    # STEP 6: Move WOLFENA to folder 22 (now vacated by CHRONOS)
    @{Agent="WOLFENA"; Current=3; Target=22; Description="WOLFENA: folder 3 → 22 (emotional regulator, equilibrium guardian)"},
    
    # STEP 7: Move ROSE to folder 3 (now vacated by WOLFENA)
    @{Agent="ROSE"; Current=12; Target=3; Description="ROSE: folder 12 → 3 (expressive kernel range, folder 3 now vacated)"},
    
    # STEP 8: Move CADUCEUS and other agents
    @{Agent="CADUCEUS"; Current=21; Target=24; Description="CADUCEUS: folder 21 → 24 (folder 24 now vacated by RESERVED_24)"},
    @{Agent="AGAPE"; Current=8; Target=25; Description="AGAPE: folder 8 → 25 (folder 25 now vacated by RESERVED_25)"},
    @{Agent="ERIS"; Current=9; Target=26; Description="ERIS: folder 9 → 26 (folder 26 now vacated by RESERVED_26)"},
    @{Agent="LILITH"; Current=7; Target=21; Description="LILITH: folder 7 → 21 (folder 21 now vacated by CADUCEUS)"},
    @{Agent="ANUBIS"; Current=18; Target=12; Description="ANUBIS: folder 18 → 12 (folder 12 now vacated by ROSE)"},
    @{Agent="MAAT"; Current=19; Target=20; Description="MAAT: folder 19 → 20"}
    
    # Junie: folder 106 → slot 200 - handled separately below
)

foreach ($move in $moves) {
    $currentPath = Join-Path $basePath $move.Current
    $targetPath = Join-Path $basePath $move.Target
    
    if (Test-Path $currentPath) {
        Write-Host "$($move.Description)..." -ForegroundColor Yellow
        
        if (Test-Path $targetPath) {
            Write-Host "  WARNING: Target folder $targetPath already exists! Skipping..." -ForegroundColor Red
            Write-Host "  You may need to manually handle this conflict." -ForegroundColor Red
            continue
        }
        
        try {
            Move-Item -Path $currentPath -Destination $targetPath -Force
            Write-Host "  ✓ Moved $currentPath → $targetPath" -ForegroundColor Green
        }
        catch {
            Write-Host "  ✗ ERROR moving $currentPath : $_" -ForegroundColor Red
        }
    }
    else {
        Write-Host "  ⚠ Source folder $currentPath not found, skipping..." -ForegroundColor Yellow
    }
}

# Handle Junie separately (from folder 106 to 200, if it exists)
$junieCurrent = Join-Path $basePath "106"
$junieTarget = Join-Path $basePath "200"

if (Test-Path $junieCurrent) {
    Write-Host "Junie: folder 106 → 200..." -ForegroundColor Yellow
    
    if (Test-Path $junieTarget) {
        Write-Host "  WARNING: Target folder $junieTarget already exists! Skipping..." -ForegroundColor Red
    }
    else {
        try {
            Move-Item -Path $junieCurrent -Destination $junieTarget -Force
            Write-Host "  ✓ Moved $junieCurrent → $junieTarget" -ForegroundColor Green
        }
        catch {
            Write-Host "  ✗ ERROR moving $junieCurrent : $_" -ForegroundColor Red
        }
    }
}
else {
    Write-Host "Junie folder 106 not found, skipping..." -ForegroundColor Yellow
}

Write-Host "`nFolder refactor complete!" -ForegroundColor Cyan
Write-Host "Slot assignments:" -ForegroundColor Cyan
Write-Host "  Slot 3: ROSE (expressive kernel 0-9 range)" -ForegroundColor Green
Write-Host "  Slot 22: WOLFENA (Balance/Emotion/Integration 20-29 range)" -ForegroundColor Green
Write-Host "  This resolves the collision that occurred when both agents were in slot 3." -ForegroundColor Green
