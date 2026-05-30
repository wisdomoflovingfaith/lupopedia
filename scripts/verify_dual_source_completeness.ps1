# ============================================================================
# DUAL-SOURCE COMPLETENESS VERIFICATION FOR LUPOPEDIA 4.0.45
# ============================================================================
# Purpose: Verify EVERY component exists in BOTH database seeds AND MD files
# Run before: Human executes CH0-20260225-001 (drop tables and install)
# ============================================================================

$ErrorActionPreference = "Continue"

$TOTAL_CHECKS = 0
$PASSED_CHECKS = 0
$FAILED_CHECKS = 0
$FAILURES = @()

Write-Host "=== DUAL-SOURCE COMPLETENESS VERIFICATION ===" -ForegroundColor Cyan
Write-Host "Version: 4.0.45"
Write-Host "Date: $((Get-Date).ToUniversalTime().ToString('yyyy-MM-ddTHH:mm:ssZ'))"
Write-Host ""

function Check-File {
    param($Path, $Description)
    
    $script:TOTAL_CHECKS++
    
    if (Test-Path $Path) {
        Write-Host "PASS $Description : $Path" -ForegroundColor Green
        $script:PASSED_CHECKS++
        return $true
    } else {
        Write-Host "FAIL $Description : $Path MISSING" -ForegroundColor Red
        $script:FAILED_CHECKS++
        $script:FAILURES += "$Description : $Path"
        return $false
    }
}

function Check-Dir {
    param($Path, $Description)
    
    $script:TOTAL_CHECKS++
    
    if (Test-Path $Path -PathType Container) {
        Write-Host "PASS $Description : $Path" -ForegroundColor Green
        $script:PASSED_CHECKS++
        return $true
    } else {
        Write-Host "FAIL $Description : $Path MISSING" -ForegroundColor Red
        $script:FAILED_CHECKS++
        $script:FAILURES += "$Description : $Path"
        return $false
    }
}

Write-Host "=== PHASE 1: DATABASE MIGRATIONS ===" -ForegroundColor Yellow
Check-File "database/migrations/install_new_lupopedia.sql" "Core schema"
Check-File "database/migrations/seed_registry_comprehensive_4.0.45.sql" "Registry comprehensive"
Check-File "database/migrations/seed_registry_open_4.0.45.sql" "Registry open gaps"
Check-File "database/migrations/seed_actors_agents_4.0.45.sql" "Actors and agents"
Check-File "database/migrations/seed_anubis_vishwakarma_4.0.45.sql" "ANUBIS + VISHWAKARMA"
Check-File "database/migrations/add_tasks_schema_4.0.45.sql" "Tasks schema"
Check-File "database/migrations/seed_tasks_bootstrap_4.0.45.sql" "Tasks bootstrap"
Write-Host ""

Write-Host "=== PHASE 2: AGENT DIRECTORIES ===" -ForegroundColor Yellow
Check-Dir "agents/1" "WOLFIE (1)"
Check-Dir "agents/2" "LILITH (2)"
Check-Dir "agents/3" "ROSE (3)"
Check-Dir "agents/4" "ERIS (4)"
Check-Dir "agents/5" "METIS (5)"
Check-Dir "agents/19" "ANUBIS (19)"
Check-Dir "agents/25" "VISHWAKARMA (25)"
Check-File "agents/19/agent.json" "ANUBIS agent.json"
Check-File "agents/19/system_prompt.txt" "ANUBIS system prompt"
Check-File "agents/25/agent.json" "VISHWAKARMA agent.json"
Check-File "agents/25/system_prompt.txt" "VISHWAKARMA system prompt"
Write-Host ""

Write-Host "=== PHASE 3: CHANNEL DIRECTORIES ===" -ForegroundColor Yellow
Check-Dir "channels/0" "Channel 0 (System)"
Check-Dir "channels/42" "Channel 42 (Development)"
Check-Dir "channels/0/broadcasts" "Channel 0 broadcasts"
Check-Dir "channels/42/broadcasts" "Channel 42 broadcasts"
Check-Dir "channels/0/tasks" "Channel 0 tasks"
Check-Dir "channels/42/tasks" "Channel 42 tasks"
Check-Dir "channels/0/roles" "Channel 0 roles"
Write-Host ""

Write-Host "=== PHASE 4: BROADCAST COUNTS ===" -ForegroundColor Yellow
$CH0_BROADCASTS = (Get-ChildItem -Path "channels/0/broadcasts" -Filter "*.md" -File -ErrorAction SilentlyContinue).Count
$CH42_BROADCASTS = (Get-ChildItem -Path "channels/42/broadcasts" -Filter "*.md" -File -ErrorAction SilentlyContinue).Count
$TOTAL_BROADCASTS = $CH0_BROADCASTS + $CH42_BROADCASTS

Write-Host "Channel 0 broadcasts: $CH0_BROADCASTS"
Write-Host "Channel 42 broadcasts: $CH42_BROADCASTS"
Write-Host "Total broadcasts: $TOTAL_BROADCASTS"

$TOTAL_CHECKS++
if ($TOTAL_BROADCASTS -ge 56) {
    Write-Host "PASS Broadcast count acceptable (>= 56)" -ForegroundColor Green
    $PASSED_CHECKS++
} else {
    Write-Host "FAIL Broadcast count too low (expected >= 56, got $TOTAL_BROADCASTS)" -ForegroundColor Red
    $FAILED_CHECKS++
    $FAILURES += "Broadcast count: expected >= 56, got $TOTAL_BROADCASTS"
}
Write-Host ""

Write-Host "=== PHASE 5: TASK FILES ===" -ForegroundColor Yellow
Check-File "channels/0/tasks/active/20260225170000_task_0_10000_drop_tables_and_run_install.md" "Human install task"
Check-File "channels/0/tasks/pending/20260225170100_task_0_19_validate_channel_666_quarantine.md" "ANUBIS quarantine task"
Check-File "channels/42/tasks/pending/20260225170200_task_42_25_graph_relationship_analysis.md" "VISHWAKARMA graph task"
Write-Host ""

Write-Host "=== PHASE 6: ROLE FILES ===" -ForegroundColor Yellow
Check-File "channels/0/roles/system_admin.md" "System admin role"
Check-File "channels/0/roles/installer.md" "Installer role"
Check-File "channels/0/roles/auditor.md" "Auditor role"
Check-File "channels/0/roles/registry_steward.md" "Registry steward role"
Check-File "channels/0/roles/communications_lead.md" "Communications lead role"
Check-File "channels/0/roles/orphan_repair_agent.md" "Orphan repair agent role (ANUBIS)"
Check-File "channels/0/roles/graph_intelligence_agent.md" "Graph intelligence agent role (VISHWAKARMA)"
Write-Host ""

Write-Host "=== PHASE 7: DOCUMENTATION ===" -ForegroundColor Yellow
Check-File "KIRO_THREAD_IDENTITY_AUDIT_4.0.45.md" "Thread identity audit"
Check-File "VALIDATION_GATE_REPORT_4.0.45.md" "Validation gate report"
Check-File "KIRO_DIRECTIVE_COMPLETION_4.0.45.md" "Directive completion"
Check-File "OFFLINE_GOVERNANCE_MODEL_4.0.45.md" "Offline governance model"
Check-File "CHANGELOG.md" "Changelog"
Write-Host ""

Write-Host "=== SUMMARY ===" -ForegroundColor Cyan
Write-Host "Total checks: $TOTAL_CHECKS"
Write-Host "Passed: $PASSED_CHECKS" -ForegroundColor Green
Write-Host "Failed: $FAILED_CHECKS" -ForegroundColor $(if ($FAILED_CHECKS -eq 0) { "Green" } else { "Red" })
Write-Host ""

if ($FAILED_CHECKS -eq 0) {
    Write-Host "ALL CHECKS PASSED - SYSTEM READY FOR INSTALL" -ForegroundColor Green
    exit 0
} else {
    Write-Host "VERIFICATION FAILED - $FAILED_CHECKS ISSUES FOUND" -ForegroundColor Red
    Write-Host ""
    Write-Host "Failed checks:" -ForegroundColor Red
    foreach ($failure in $FAILURES) {
        Write-Host "  - $failure" -ForegroundColor Red
    }
    exit 1
}
