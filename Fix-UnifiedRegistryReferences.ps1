# Fix-RegistryReferences.ps1
# Systematically replace all registry_id → registry_id references
# Run this from the lupopedia root directory

Write-Host "=====================================================================" -ForegroundColor Cyan
Write-Host "FIXING registry_id → registry_id IN ALL FILES" -ForegroundColor Cyan
Write-Host "=====================================================================" -ForegroundColor Cyan
Write-Host ""

$rootPath = Get-Location
$replacements = 0

# Backup critical files first
Write-Host "Creating backups..." -ForegroundColor Yellow
Copy-Item "install_wizard_classes.php" "install_wizard_classes.php.backup_registry_fix" -Force
Copy-Item "api\flip-header.php" "api\flip-header.php.backup_registry_fix" -Force
Copy-Item "install.php" "install.php.backup_registry_fix" -Force
Write-Host "✓ Backups created" -ForegroundColor Green
Write-Host ""

# Function to replace in file
function Replace-InFile {
    param(
        [string]$FilePath,
        [string]$OldText,
        [string]$NewText
    )
    
    if (Test-Path $FilePath) {
        $content = Get-Content $FilePath -Raw -Encoding UTF8
        if ($content -match $OldText) {
            $newContent = $content -replace [regex]::Escape($OldText), $NewText
            Set-Content $FilePath -Value $newContent -Encoding UTF8 -NoNewline
            return 1
        }
    }
    return 0
}

# Fix PHP files
Write-Host "Fixing PHP files..." -ForegroundColor Yellow
$phpFiles = Get-ChildItem -Path . -Filter "*.php" -Recurse -File
foreach ($file in $phpFiles) {
    $count = Replace-InFile -FilePath $file.FullName -OldText "registry_id" -NewText "registry_id"
    if ($count -gt 0) {
        $replacements += $count
        Write-Host "  ✓ $($file.Name)" -ForegroundColor Gray
    }
}

# Fix Python files
Write-Host "Fixing Python files..." -ForegroundColor Yellow
$pyFiles = Get-ChildItem -Path . -Filter "*.py" -Recurse -File
foreach ($file in $pyFiles) {
    $count = Replace-InFile -FilePath $file.FullName -OldText "registry_id" -NewText "registry_id"
    if ($count -gt 0) {
        $replacements += $count
        Write-Host "  ✓ $($file.Name)" -ForegroundColor Gray
    }
}

# Fix SQL files (except install_new_lupopedia.sql which has correct schema)
Write-Host "Fixing SQL seed files..." -ForegroundColor Yellow
$sqlFiles = Get-ChildItem -Path ".\database\migrations" -Filter "*.sql" -File | Where-Object { $_.Name -ne "install_new_lupopedia.sql" }
foreach ($file in $sqlFiles) {
    $count = Replace-InFile -FilePath $file.FullName -OldText "registry_id" -NewText "registry_id"
    if ($count -gt 0) {
        $replacements += $count
        Write-Host "  ✓ $($file.Name)" -ForegroundColor Gray
    }
}

# Fix TypeScript files
Write-Host "Fixing TypeScript files..." -ForegroundColor Yellow
$tsFiles = Get-ChildItem -Path ".\tools\vsx-extension" -Filter "*.ts" -Recurse -File
foreach ($file in $tsFiles) {
    $count = Replace-InFile -FilePath $file.FullName -OldText "registry_id" -NewText "registry_id"
    if ($count -gt 0) {
        $replacements += $count
        Write-Host "  ✓ $($file.Name)" -ForegroundColor Gray
    }
}

# Fix Documentation (MD files)
Write-Host "Fixing documentation..." -ForegroundColor Yellow
$mdFiles = Get-ChildItem -Path ".\docs" -Filter "*.md" -Recurse -File
foreach ($file in $mdFiles) {
    $count = Replace-InFile -FilePath $file.FullName -OldText "registry_id" -NewText "registry_id"
    if ($count -gt 0) {
        $replacements += $count
        Write-Host "  ✓ $($file.Name)" -ForegroundColor Gray
    }
}

$mdFiles = Get-ChildItem -Path ".\messages" -Filter "*.md" -Recurse -File
foreach ($file in $mdFiles) {
    $count = Replace-InFile -FilePath $file.FullName -OldText "registry_id" -NewText "registry_id"
    if ($count -gt 0) {
        $replacements += $count
        Write-Host "  ✓ $($file.Name)" -ForegroundColor Gray
    }
}

# Fix root files
Write-Host "Fixing root files..." -ForegroundColor Yellow
$count = Replace-InFile -FilePath "README.md" -OldText "registry_id" -NewText "registry_id"
if ($count -gt 0) {
    $replacements += $count
    Write-Host "  ✓ README.md" -ForegroundColor Gray
}

$count = Replace-InFile -FilePath "CHANGELOG.md" -OldText "registry_id" -NewText "registry_id"
if ($count -gt 0) {
    $replacements += $count
    Write-Host "  ✓ CHANGELOG.md" -ForegroundColor Gray
}

Write-Host ""
Write-Host "=====================================================================" -ForegroundColor Cyan
Write-Host "COMPLETE: $replacements files modified" -ForegroundColor Green
Write-Host "=====================================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Backups created:" -ForegroundColor Yellow
Write-Host "  - install_wizard_classes.php.backup_registry_fix"
Write-Host "  - api\flip-header.php.backup_registry_fix"
Write-Host "  - install.php.backup_registry_fix"
Write-Host ""
Write-Host "Files modified:" -ForegroundColor Yellow
Write-Host "  - PHP files"
Write-Host "  - Python files"
Write-Host "  - SQL files (except install_new_lupopedia.sql)"
Write-Host "  - TypeScript files"
Write-Host "  - MD documentation files"
Write-Host ""
Write-Host "IMPORTANT: Test installation after this change!" -ForegroundColor Red
Write-Host ""
