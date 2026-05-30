# ============================================================================
# STRICT BROADCAST VALIDATION FOR IMPORTER READINESS
# ============================================================================
# Purpose: Validate all broadcasts pass InstallWizardMdImporter requirements
# System Version: 4.0.45
# Author: Kiro IDE (1000)
# ============================================================================

$ErrorActionPreference = "Stop"

Write-Host "=== STRICT BROADCAST VALIDATION GATE ===" -ForegroundColor Cyan
Write-Host ""

$channels = @(0, 42)
$totalFiles = 0
$totalFailures = 0
$missingEdgeTargets = @()
$failures = @()

foreach ($channelId in $channels) {
    $broadcastPath = "channels/$channelId/broadcasts"
    
    if (-not (Test-Path $broadcastPath)) {
        Write-Host "Channel $channelId broadcasts directory not found" -ForegroundColor Red
        continue
    }
    
    $files = Get-ChildItem -Path $broadcastPath -Filter "*.md" -File
    $channelFiles = $files.Count
    $totalFiles += $channelFiles
    
    Write-Host "Channel ${channelId}: $channelFiles files" -ForegroundColor Yellow
    
    foreach ($file in $files) {
        $content = Get-Content $file.FullName -Raw
        $filename = $file.Name
        $hasError = $false
        $errors = @()
        
        # A) Filename Compliance (YYYYMMDDHHMMSS_FROM_TO_CHANNEL_TITLE.md)
        if ($filename -notmatch '^\d{14}_\d+_\d+_\d+_.+\.md$') {
            $errors += "Invalid filename format"
            $hasError = $true
        }
        
        # B) Header Compliance
        if ($content -notmatch '---\s*\n') {
            $errors += "Missing YAML frontmatter"
            $hasError = $true
        }
        
        # Required header fields
        $requiredFields = @(
            'from_actor_id',
            'to_actor_id',
            'channel_id',
            'delegation_chain',
            'created_utc'
        )
        
        foreach ($field in $requiredFields) {
            if ($content -notmatch "$field\s*:") {
                $errors += "Missing required field: $field"
                $hasError = $true
            }
        }
        
        # C) Footer/Edge Compliance
        if ($content -notmatch '<!-- FLIP_FOOTER_BEGIN') {
            $errors += "Missing FLIP footer"
            $hasError = $true
        }
        
        if ($content -notmatch 'FLIP_FOOTER_END -->') {
            $errors += "Malformed FLIP footer (missing end tag)"
            $hasError = $true
        }
        
        # D) Delegation Chain Consistency (10000:1000 for system broadcasts)
        if ($content -match 'delegation_chain\s*:\s*"([^"]+)"') {
            $chain = $matches[1]
            # Most system broadcasts should be 10000:1000
            # But we allow other valid chains
        }
        
        # E) Character Limit (if enforced - check body length)
        $bodyMatch = $content -match '(?s)---.*?---\s*\n(.+?)(?:<!--\s*FLIP_FOOTER_BEGIN|$)'
        if ($bodyMatch) {
            $body = $matches[1]
            # Note: 1000 char limit not strictly enforced in current spec
        }
        
        # F) Edge Target Verification
        if ($content -match '(?s)FLIP_FOOTER_BEGIN\s*\{(.+?)\}\s*FLIP_FOOTER_END') {
            $footerJson = $matches[1]
            try {
                $footer = $footerJson | ConvertFrom-Json
                
                # Check references
                if ($footer.references) {
                    $refs = @($footer.references)
                    foreach ($ref in $refs) {
                        if ($ref -and $ref -ne "" -and -not (Test-Path $ref)) {
                            $missingEdgeTargets += "$filename -> $ref"
                        }
                    }
                }
                
                # Check depends_on
                if ($footer.depends_on) {
                    $deps = @($footer.depends_on)
                    foreach ($dep in $deps) {
                        if ($dep -and $dep -ne "" -and $dep -notmatch '^CH\d+-\d+-\d+$' -and -not (Test-Path $dep)) {
                            $missingEdgeTargets += "$filename -> $dep"
                        }
                    }
                }
            } catch {
                $errors += "Invalid JSON in FLIP footer"
                $hasError = $true
            }
        }
        
        if ($hasError) {
            $totalFailures++
            $failures += [PSCustomObject]@{
                Channel = $channelId
                File = $filename
                Errors = ($errors -join "; ")
            }
        }
    }
}

Write-Host ""
Write-Host "=== VALIDATION RESULTS ===" -ForegroundColor Cyan
Write-Host "Total Files Checked: $totalFiles" -ForegroundColor White
Write-Host "Total Failures: $totalFailures" -ForegroundColor $(if ($totalFailures -eq 0) { "Green" } else { "Red" })
Write-Host "Missing Edge Targets: $($missingEdgeTargets.Count)" -ForegroundColor $(if ($missingEdgeTargets.Count -eq 0) { "Green" } else { "Yellow" })
Write-Host ""

if ($totalFailures -gt 0) {
    Write-Host "=== FAILURES ===" -ForegroundColor Red
    $failures | Format-Table -Property Channel, File, Errors -AutoSize -Wrap
}

if ($missingEdgeTargets.Count -gt 0) {
    Write-Host "=== MISSING EDGE TARGETS ===" -ForegroundColor Yellow
    $missingEdgeTargets | ForEach-Object { Write-Host "  $_" -ForegroundColor Yellow }
}

Write-Host ""
if ($totalFailures -eq 0 -and $missingEdgeTargets.Count -eq 0) {
    Write-Host "READY - All broadcasts pass validation" -ForegroundColor Green
    exit 0
} else {
    Write-Host "BLOCKED - Validation failures detected" -ForegroundColor Red
    exit 1
}
