# PowerShell script to rename agent folders from code-based names to slot-based names
# Based on lupo_agent_registry.toon root agents only (agent_registry_parent_id IS NULL)
# Only processes agents with valid dedicated_slot values (not null)
#
# IMPORTANT: dedicated_slot is the permanent identity number that must match agent_id
# in the lupo_agents table. dedicated_slot NEVER changes after installation.
# Names, keys, and aliases may change; identity does not.
#
# Usage: .\rename_agent_folders.ps1 [-Force]
#   -Force: Skip confirmation prompt and proceed automatically

param(
    [switch]$Force
)

$ErrorActionPreference = "Stop"

# Determine script directory and project root
# Script assumes it's in the project root directory
$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$projectRoot = $scriptDir

# Try multiple possible paths for lupo-agents folder
$possiblePaths = @(
    (Join-Path $projectRoot "lupo-agents"),           # Direct: lupo-agents/
    (Join-Path $projectRoot "lupopedia\lupo-agents")  # Nested: lupopedia/lupo-agents/
)

$basePath = $null
foreach ($path in $possiblePaths) {
    if (Test-Path $path) {
        $basePath = $path
        break
    }
}

if ($null -eq $basePath) {
    Write-Error "Could not find lupo-agents directory. Tried: $($possiblePaths -join ', ')"
    exit 1
}

Write-Host "Using agents directory: $basePath" -ForegroundColor Cyan

$toonFile = Join-Path $projectRoot "database\toon_data\lupo_agent_registry.toon"

Write-Host "Reading TOON file: $toonFile" -ForegroundColor Cyan

if (-not (Test-Path $toonFile)) {
    Write-Error "TOON file not found: $toonFile"
    exit 1
}

# Read and parse TOON file
$toonContent = Get-Content $toonFile -Raw
$toonJson = $toonContent | ConvertFrom-Json

# Extract root agents (agent_registry_parent_id IS NULL) with valid dedicated_slot
# dedicated_slot is the permanent identity number that must match agent_id in lupo_agents table
$renameMappings = @{}
$nullSlotAgents = @()
$missingFolders = @()

foreach ($agent in $toonJson.data) {
    # Only process root agents (agent_registry_parent_id IS NULL)
    if ($agent.agent_registry_parent_id -eq $null) {
        $code = $agent.code
        
        # Skip agents with null dedicated_slot (cannot rename to null)
        # dedicated_slot is required for all kernel agents with numbered folders
        if ($agent.dedicated_slot -eq $null) {
            $nullSlotAgents += $code
            continue
        }
        
        $slot = $agent.dedicated_slot
        
        # Convert slot to string for folder name
        $slotStr = $slot.ToString()
        
        $oldFolder = Join-Path $basePath $code
        $newFolder = Join-Path $basePath $slotStr
        
        # Only add if old folder exists
        if (Test-Path $oldFolder) {
            $renameMappings[$code] = @{
                Code = $code
                Slot = $slotStr
                OldFolder = $oldFolder
                NewFolder = $newFolder
            }
        } else {
            $missingFolders += $code
        }
    }
}

# Report agents with null slots
if ($nullSlotAgents.Count -gt 0) {
    Write-Host "`nAgents with null dedicated_slot (will be skipped):" -ForegroundColor Yellow
    Write-Host "  Note: dedicated_slot is the permanent identity number (must match agent_id)." -ForegroundColor Yellow
    Write-Host "  These agents may use alternative loading mechanisms." -ForegroundColor Yellow
    foreach ($code in $nullSlotAgents | Sort-Object) {
        Write-Host "  $code" -ForegroundColor Yellow
    }
}

# Report agents where folder doesn't exist
if ($missingFolders.Count -gt 0) {
    Write-Host "`nRoot agents where folder does not exist (will be skipped):" -ForegroundColor Yellow
    foreach ($code in $missingFolders | Sort-Object) {
        Write-Host "  $code" -ForegroundColor Yellow
    }
}

Write-Host "`nFound $($renameMappings.Count) agents to rename:`n" -ForegroundColor Green
foreach ($code in $renameMappings.Keys | Sort-Object) {
    $mapping = $renameMappings[$code]
    Write-Host "  $($mapping.Code) -> $($mapping.Slot)" -ForegroundColor Yellow
}

Write-Host "`n" -ForegroundColor Cyan

# Ask for confirmation unless -Force is used
if (-not $Force) {
    $confirm = Read-Host "Do you want to proceed with renaming? (yes/no)"
    if ($confirm -ne "yes") {
        Write-Host "Operation cancelled." -ForegroundColor Red
        exit 0
    }
} else {
    Write-Host "Force mode: Proceeding automatically without confirmation..." -ForegroundColor Cyan
}

Write-Host "`nStarting rename operations...`n" -ForegroundColor Cyan

$successCount = 0
$skipCount = 0
$errorCount = 0

foreach ($code in $renameMappings.Keys | Sort-Object) {
    $mapping = $renameMappings[$code]
    $oldFolder = $mapping.OldFolder
    $newFolder = $mapping.NewFolder
    $slot = $mapping.Slot
    
    Write-Host "Processing: $code -> $slot" -ForegroundColor White
    
    # Check if old folder exists
    if (-not (Test-Path $oldFolder)) {
        Write-Host "  SKIP: Old folder does not exist: $oldFolder" -ForegroundColor Yellow
        $skipCount++
        continue
    }
    
    # Check if new folder already exists
    if (Test-Path $newFolder) {
        Write-Host "  WARNING: New folder already exists: $newFolder" -ForegroundColor Yellow
        Write-Host "  SKIP: Will not overwrite existing folder" -ForegroundColor Yellow
        $skipCount++
        continue
    }
    
    try {
        # Create parent directory if needed (should already exist, but safe)
        $parentDir = Split-Path $newFolder -Parent
        if (-not (Test-Path $parentDir)) {
            New-Item -ItemType Directory -Path $parentDir -Force | Out-Null
        }
        
        # Move the folder
        Move-Item -Path $oldFolder -Destination $newFolder -Force
        Write-Host "  SUCCESS: Renamed $code -> $slot" -ForegroundColor Green
        $successCount++
    }
    catch {
        Write-Host "  ERROR: Failed to rename $code -> $slot" -ForegroundColor Red
        Write-Host "    Error: $($_.Exception.Message)" -ForegroundColor Red
        $errorCount++
    }
}

Write-Host "`n" -ForegroundColor Cyan
Write-Host "Rename operation complete!" -ForegroundColor Green
Write-Host "  Success: $successCount" -ForegroundColor Green
Write-Host "  Skipped: $skipCount" -ForegroundColor Yellow
Write-Host "  Errors:  $errorCount" -ForegroundColor $(if ($errorCount -gt 0) { "Red" } else { "Green" })

if ($errorCount -gt 0) {
    exit 1
}
