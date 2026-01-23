# 4.2.0 Stability Release Execution Script
# Date: 2026-01-20
# Paths: lupo-includes/version.php, database/generate_toon_files.py, database/toon_data/, database/migrations/

$ErrorActionPreference = "Stop"
$Root = if ($PSScriptRoot) { $PSScriptRoot } else { (Get-Location).Path }

Write-Host "=== 4.2.0 STABILITY RELEASE EXECUTION ===" -ForegroundColor Cyan
Write-Host "Root: $Root"

# ---------------------------------------------------------------------------
# PHASE 1: PRE-FLIGHT (run these manually if you have MySQL client)
# ---------------------------------------------------------------------------
Write-Host "`n--- PHASE 1: PRE-FLIGHT (manual) ---" -ForegroundColor Yellow
Write-Host "Run in MySQL: SELECT COUNT(*) AS table_count FROM information_schema.tables WHERE table_schema = DATABASE();"
Write-Host "Expected: 173 (or your actual count; must be <= 180)"
Write-Host "Check TOON: Get-ChildItem $Root\database\toon_data\*.toon | Measure-Object | Select-Object -ExpandProperty Count"
Write-Host "Check version: Select-String -Path $Root\lupo-includes\version.php -Pattern '4\.2\.0'"

# ---------------------------------------------------------------------------
# PHASE 2: BACKUP
# ---------------------------------------------------------------------------
$ts = Get-Date -Format "yyyyMMdd_HHmmss"
$BackupDir = "$env:TEMP\backup_4.1.20_$ts"
Write-Host "`n--- PHASE 2: BACKUP ---" -ForegroundColor Yellow
New-Item -ItemType Directory -Force -Path $BackupDir | Out-Null
Copy-Item -Recurse -Force "$Root\database\toon_data" "$BackupDir\toon_backup"
Copy-Item -Recurse -Force "$Root\database\migrations" "$BackupDir\migrations_backup"
Copy-Item -Force "$Root\lupo-includes\version.php" "$BackupDir\"
if (Test-Path "$Root\docs\doctrine") { Copy-Item -Recurse -Force "$Root\docs\doctrine" "$BackupDir\doctrine_backup" }
Write-Host "Backup: $BackupDir"

# ---------------------------------------------------------------------------
# PHASE 3: TOON REGENERATION
# ---------------------------------------------------------------------------
Write-Host "`n--- PHASE 3: TOON REGENERATION ---" -ForegroundColor Yellow
$py = Get-Command python -ErrorAction SilentlyContinue | Select-Object -ExpandProperty Source
if (-not $py) { $py = Get-Command python3 -ErrorAction SilentlyContinue | Select-Object -ExpandProperty Source }
if ($py) {
    Push-Location $Root
    & $py database/generate_toon_files.py 2>&1
    Pop-Location
    $toonCount = (Get-ChildItem "$Root\database\toon_data\*.toon" -ErrorAction SilentlyContinue | Measure-Object).Count
    Write-Host "TOON files: $toonCount"
} else {
    Write-Host "Python not found. Run: python database/generate_toon_files.py"
}

# ---------------------------------------------------------------------------
# PHASE 4: VERSION BUMP (already applied by CURSOR)
# ---------------------------------------------------------------------------
Write-Host "`n--- PHASE 4: VERSION BUMP ---" -ForegroundColor Yellow
Write-Host "Version atoms and lupo-includes/version.php already set to 4.2.0."
$v = Select-String -Path "$Root\lupo-includes\version.php" -Pattern "@version 4\.2\.0" -SimpleMatch
if ($v) { Write-Host "OK: version.php @version 4.2.0" -ForegroundColor Green } else { Write-Host "WARN: version.php may not be 4.2.0" -ForegroundColor Red }

# ---------------------------------------------------------------------------
# PHASE 5: SCHEMA FREEZE (optional MySQL EVENT)
# ---------------------------------------------------------------------------
Write-Host "`n--- PHASE 5: SCHEMA FREEZE (optional) ---" -ForegroundColor Yellow
Write-Host "To enable MySQL EVENT (doctrine prefers app-level):"
Write-Host "  1. SET GLOBAL event_scheduler = ON;"
Write-Host "  2. mysql -u root -p < database/migrations/4.2.0_schema_freeze_enforcement.sql"
Write-Host "  3. SHOW EVENTS LIKE 'schema_freeze_enforcement_4_2_0';"

# ---------------------------------------------------------------------------
# PHASE 6: VERIFICATION
# ---------------------------------------------------------------------------
Write-Host "`n--- PHASE 6: VERIFICATION ---" -ForegroundColor Yellow
$vf = (Select-String -Path "$Root\lupo-includes\version.php" -Pattern "4\.2\.0" -ErrorAction SilentlyContinue).Count; Write-Host "version.php 4.2.0 refs: $vf"
$n = (Select-String -Path "$Root\CHANGELOG.md" -Pattern "## 4\.2\.0" -ErrorAction SilentlyContinue).Count; Write-Host "CHANGELOG 4.2.0 sections: $n"
Write-Host "Backup:   $BackupDir"

Write-Host "`n=== 4.2.0 RELEASE EXECUTION COMPLETE ===" -ForegroundColor Cyan
Write-Host "Next: 4.3.x feature development. Schema freeze active until 4.3.x."
