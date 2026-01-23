# check_dedicated_slot_duplicates.ps1
# Checks for duplicate dedicated_slot values where agent_registry_parent_id IS NULL
# Run from lupopedia root directory

$toonFile = "database/toon_data/lupo_agent_registry.toon"
$jsonContent = Get-Content $toonFile -Raw | ConvertFrom-Json

# Group agents by dedicated_slot where parent_id is null and slot is not null
$agentsBySlot = @{}

foreach ($agent in $jsonContent.data) {
    if ($agent.agent_registry_parent_id -eq $null -and $agent.dedicated_slot -ne $null) {
        $slot = $agent.dedicated_slot
        if (-not $agentsBySlot.ContainsKey($slot)) {
            $agentsBySlot[$slot] = @()
        }
        $agentsBySlot[$slot] += $agent
    }
}

# Find duplicates
$duplicates = @{}
foreach ($slot in $agentsBySlot.Keys) {
    if ($agentsBySlot[$slot].Count -gt 1) {
        $duplicates[$slot] = $agentsBySlot[$slot]
    }
}

Write-Host ""
Write-Host "=== DEDICATED_SLOT DUPLICATE CHECK ===" -ForegroundColor Cyan
Write-Host "Checking for duplicate dedicated_slot values where agent_registry_parent_id IS NULL" -ForegroundColor Yellow
Write-Host ""

if ($duplicates.Count -eq 0) {
    Write-Host "NO DUPLICATES FOUND - All dedicated_slot values are unique!" -ForegroundColor Green
} else {
    Write-Host "FOUND $($duplicates.Count) DUPLICATE SLOT(S):" -ForegroundColor Red
    Write-Host ""
    
    foreach ($slot in ($duplicates.Keys | Sort-Object)) {
        $agents = $duplicates[$slot]
        Write-Host "SLOT $slot has $($agents.Count) agents:" -ForegroundColor Red
        foreach ($agent in $agents) {
            Write-Host "  - ID $($agent.agent_registry_id): $($agent.name) ($($agent.code))" -ForegroundColor Yellow
        }
        Write-Host ""
    }
}

Write-Host ""
Write-Host "=== SUMMARY ===" -ForegroundColor Cyan
$totalAgents = 0
foreach ($slot in $agentsBySlot.Keys) {
    $totalAgents += $agentsBySlot[$slot].Count
}
Write-Host "Total agents with dedicated_slot (parent_id = null): $totalAgents" -ForegroundColor White
Write-Host "Unique slots used: $($agentsBySlot.Keys.Count)" -ForegroundColor White
if ($duplicates.Count -eq 0) {
    Write-Host "Duplicate slots found: $($duplicates.Count)" -ForegroundColor Green
} else {
    Write-Host "Duplicate slots found: $($duplicates.Count)" -ForegroundColor Red
}
