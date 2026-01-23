$content = Get-Content '../CHANGELOG.md' -Raw
$newEntry = Get-Content 'JETBRAINS_4_1_X_BRANCH_HANDLING_DOCTRINE.md' -Raw
$insertPoint = $content.IndexOf('## [4.0.94] - 2026-01-17')
if ($insertPoint -ge 0) {
    $newContent = $content.Substring(0, $insertPoint) + $newEntry + "`n`n" + $content.Substring($insertPoint)
    $newContent | Set-Content '../CHANGELOG.md' -Encoding UTF8
    Write-Host 'CHANGELOG.md updated with JetBrains 4.1.x Branch Handling Doctrine'
} else {
    Write-Host 'Insertion point not found in CHANGELOG.md'
}
