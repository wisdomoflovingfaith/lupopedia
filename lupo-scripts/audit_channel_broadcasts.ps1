# ============================================================================
# DUAL-CHANNEL BROADCAST AUDIT SCRIPT
# ============================================================================
# Purpose: Audit and normalize broadcasts in Channel 0 and Channel 42
# System Version: 4.0.45
# Author: KIRO (Warp IDE Agent 1004)
# Date: 2026-02-25
# ============================================================================

$ErrorActionPreference = "Stop"

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "DUAL-CHANNEL BROADCAST AUDIT" -ForegroundColor Cyan
Write-Host "System Version: 4.0.45" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

# ============================================================================
# CONFIGURATION
# ============================================================================

$channels = @(
    @{ Id = 0; Name = "System Kernel"; Path = "channels/0/broadcasts" },
    @{ Id = 42; Name = "Development"; Path = "channels/42/broadcasts" }
)

$requiredPattern = '^\d{14}_\d+_\d+_\d+_.+\.md$'
$validActorIds = @(0, 1, 2, 3, 4, 5, 1000, 1001, 1002, 1003, 1004, 1005, 10000, 10001)

$report = @{
    Channel0 = @{
        Total = 0
        Compliant = 0
        Fixed = 0
        Duplicates = 0
        Violations = @()
    }
    Channel42 = @{
        Total = 0
        Compliant = 0
        Fixed = 0
        Duplicates = 0
        Violations = @()
    }
}

# ============================================================================
# HELPER FUNCTIONS
# ============================================================================

function Test-BroadcastFilename {
    param([string]$Filename)
    return $Filename -match $requiredPattern
}

function Get-FilenameComponents {
    param([string]$Filename)
    
    if ($Filename -match '^(\d{14})_(\d+)_(\d+)_(\d+)_(.+)\.md$') {
        return @{
            Timestamp = $Matches[1]
            FromActor = [int]$Matches[2]
            ToActor = [int]$Matches[3]
            Channel = [int]$Matches[4]
            Title = $Matches[5]
            Valid = $true
        }
    }
    return @{ Valid = $false }
}

function Test-ActorIdValid {
    param([int]$ActorId)
    return $validActorIds -contains $ActorId
}

function Get-FileHeader {
    param([string]$FilePath)
    
    $content = Get-Content -Path $FilePath -Raw
    if ($content -match '(?s)^---\s*\n(.+?)\n---') {
        return $Matches[1]
    }
    return $null
}

function Test-HeaderComplete {
    param([string]$Header)
    
    $requiredFields = @('from_actor_id', 'to_actor_id', 'channel_id', 'delegation_chain', 'created_utc')
    $hasAll = $true
    
    foreach ($field in $requiredFields) {
        if ($Header -notmatch $field) {
            $hasAll = $false
            break
        }
    }
    
    return $hasAll
}

# ============================================================================
# AUDIT FUNCTION
# ============================================================================

function Invoke-ChannelAudit {
    param(
        [hashtable]$Channel,
        [hashtable]$Report
    )
    
    Write-Host "[AUDIT] Channel $($Channel.Id) ($($Channel.Name))..." -ForegroundColor Yellow
    
    $files = Get-ChildItem -Path $Channel.Path -Filter "*.md" -ErrorAction SilentlyContinue
    $Report.Total = $files.Count
    
    Write-Host "  Found $($files.Count) broadcast files"
    
    $seenTimestamps = @{}
    
    foreach ($file in $files) {
        $components = Get-FilenameComponents -Filename $file.Name
        
        # Check filename format
        if (-not (Test-BroadcastFilename -Filename $file.Name)) {
            $Report.Violations += @{
                File = $file.Name
                Issue = "Non-compliant filename format"
                Resolution = "Needs manual review and rename"
            }
            Write-Host "    ❌ $($file.Name) - Non-compliant format" -ForegroundColor Red
            continue
        }
        
        # Check if valid
        if (-not $components.Valid) {
            $Report.Violations += @{
                File = $file.Name
                Issue = "Cannot parse filename"
                Resolution = "Needs manual review"
            }
            Write-Host "    ❌ $($file.Name) - Cannot parse" -ForegroundColor Red
            continue
        }
        
        # Check channel ID matches
        if ($components.Channel -ne $Channel.Id) {
            $Report.Violations += @{
                File = $file.Name
                Issue = "Channel ID mismatch (file says $($components.Channel), should be $($Channel.Id))"
                Resolution = "Rename file with correct channel ID"
            }
            Write-Host "    ⚠️  $($file.Name) - Channel ID mismatch" -ForegroundColor Yellow
        }
        
        # Check actor IDs
        if (-not (Test-ActorIdValid -ActorId $components.FromActor)) {
            $Report.Violations += @{
                File = $file.Name
                Issue = "Invalid FROM actor ID: $($components.FromActor)"
                Resolution = "Verify actor exists in registry"
            }
            Write-Host "    ⚠️  $($file.Name) - Invalid FROM actor" -ForegroundColor Yellow
        }
        
        if (-not (Test-ActorIdValid -ActorId $components.ToActor)) {
            $Report.Violations += @{
                File = $file.Name
                Issue = "Invalid TO actor ID: $($components.ToActor)"
                Resolution = "Verify actor exists in registry"
            }
            Write-Host "    ⚠️  $($file.Name) - Invalid TO actor" -ForegroundColor Yellow
        }
        
        # Check for duplicates
        if ($seenTimestamps.ContainsKey($components.Timestamp)) {
            $Report.Duplicates++
            $Report.Violations += @{
                File = $file.Name
                Issue = "Duplicate timestamp: $($components.Timestamp)"
                Resolution = "Archive or merge duplicate"
            }
            Write-Host "    ⚠️  $($file.Name) - Duplicate timestamp" -ForegroundColor Yellow
        } else {
            $seenTimestamps[$components.Timestamp] = $file.Name
        }
        
        # Check header
        $header = Get-FileHeader -FilePath $file.FullName
        if ($null -eq $header) {
            $Report.Violations += @{
                File = $file.Name
                Issue = "Missing YAML header"
                Resolution = "Add FLIP header"
            }
            Write-Host "    ❌ $($file.Name) - Missing header" -ForegroundColor Red
        } elseif (-not (Test-HeaderComplete -Header $header)) {
            $Report.Violations += @{
                File = $file.Name
                Issue = "Incomplete header (missing required fields)"
                Resolution = "Add missing fields: from_actor_id, to_actor_id, channel_id, delegation_chain, created_utc"
            }
            Write-Host "    ⚠️  $($file.Name) - Incomplete header" -ForegroundColor Yellow
        } else {
            $Report.Compliant++
            Write-Host "    ✅ $($file.Name)" -ForegroundColor Green
        }
    }
    
    Write-Host ""
}

# ============================================================================
# EXECUTE AUDITS
# ============================================================================

Write-Host "[PHASE 1] Auditing Channel 0..." -ForegroundColor Cyan
Invoke-ChannelAudit -Channel $channels[0] -Report $report.Channel0

Write-Host "[PHASE 2] Auditing Channel 42..." -ForegroundColor Cyan
Invoke-ChannelAudit -Channel $channels[1] -Report $report.Channel42

# ============================================================================
# GENERATE REPORT
# ============================================================================

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "AUDIT REPORT" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "CHANNEL 0 (System Kernel):" -ForegroundColor Yellow
Write-Host "  Total Files: $($report.Channel0.Total)"
Write-Host "  Compliant: $($report.Channel0.Compliant)" -ForegroundColor Green
Write-Host "  Violations: $($report.Channel0.Violations.Count)" -ForegroundColor $(if ($report.Channel0.Violations.Count -gt 0) { "Red" } else { "Green" })
Write-Host "  Duplicates: $($report.Channel0.Duplicates)" -ForegroundColor $(if ($report.Channel0.Duplicates -gt 0) { "Yellow" } else { "Green" })
Write-Host ""

Write-Host "CHANNEL 42 (Development):" -ForegroundColor Yellow
Write-Host "  Total Files: $($report.Channel42.Total)"
Write-Host "  Compliant: $($report.Channel42.Compliant)" -ForegroundColor Green
Write-Host "  Violations: $($report.Channel42.Violations.Count)" -ForegroundColor $(if ($report.Channel42.Violations.Count -gt 0) { "Red" } else { "Green" })
Write-Host "  Duplicates: $($report.Channel42.Duplicates)" -ForegroundColor $(if ($report.Channel42.Duplicates -gt 0) { "Yellow" } else { "Green" })
Write-Host ""

if ($report.Channel0.Violations.Count -gt 0 -or $report.Channel42.Violations.Count -gt 0) {
    Write-Host "VIOLATIONS SUMMARY:" -ForegroundColor Red
    Write-Host ""
    
    if ($report.Channel0.Violations.Count -gt 0) {
        Write-Host "  Channel 0:" -ForegroundColor Yellow
        foreach ($violation in $report.Channel0.Violations) {
            Write-Host "    - $($violation.File)"
            Write-Host "      Issue: $($violation.Issue)"
            Write-Host "      Resolution: $($violation.Resolution)"
            Write-Host ""
        }
    }
    
    if ($report.Channel42.Violations.Count -gt 0) {
        Write-Host "  Channel 42:" -ForegroundColor Yellow
        foreach ($violation in $report.Channel42.Violations) {
            Write-Host "    - $($violation.File)"
            Write-Host "      Issue: $($violation.Issue)"
            Write-Host "      Resolution: $($violation.Resolution)"
            Write-Host ""
        }
    }
}

# ============================================================================
# READINESS ASSESSMENT
# ============================================================================

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "READINESS ASSESSMENT" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

$totalViolations = $report.Channel0.Violations.Count + $report.Channel42.Violations.Count
$totalDuplicates = $report.Channel0.Duplicates + $report.Channel42.Duplicates

if ($totalViolations -eq 0 -and $totalDuplicates -eq 0) {
    Write-Host "[GREEN] BOTH CHANNELS CLEAN" -ForegroundColor Green
    Write-Host "   All broadcasts are compliant and ready for install.php"
} elseif ($totalViolations -le 5 -and $totalDuplicates -eq 0) {
    Write-Host "[YELLOW] MINOR ISSUES" -ForegroundColor Yellow
    Write-Host "   $totalViolations violations found, but no blocking issues"
} else {
    Write-Host "[RED] BLOCKING ISSUES" -ForegroundColor Red
    Write-Host "   $totalViolations violations and $totalDuplicates duplicates must be resolved"
}

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan

# Export report to JSON
$reportJson = $report | ConvertTo-Json -Depth 10
Set-Content -Path "BROADCAST_AUDIT_REPORT_4.0.45.json" -Value $reportJson
Write-Host "Report exported to BROADCAST_AUDIT_REPORT_4.0.45.json" -ForegroundColor Cyan
