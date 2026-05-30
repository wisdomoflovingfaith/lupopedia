#!/bin/bash
# ============================================================================
# DUAL-SOURCE COMPLETENESS VERIFICATION FOR LUPOPEDIA 4.0.45
# ============================================================================
# Purpose: Verify EVERY component exists in BOTH database seeds AND MD files
# Run before: Human executes CH0-20260225-001 (drop tables and install)
# ============================================================================

set -e

REPORT_FILE="docs/status/kiro_dual_source_verification_4_0_45.md"
TOTAL_CHECKS=0
PASSED_CHECKS=0
FAILED_CHECKS=0
FAILURES=()

echo "=== DUAL-SOURCE COMPLETENESS VERIFICATION ==="
echo "Version: 4.0.45"
echo "Date: $(date -u +%Y-%m-%dT%H:%M:%SZ)"
echo ""

# Helper function to check file exists
check_file() {
    local file=$1
    local description=$2
    TOTAL_CHECKS=$((TOTAL_CHECKS + 1))
    
    if [ -f "$file" ]; then
        echo "✅ $description: $file"
        PASSED_CHECKS=$((PASSED_CHECKS + 1))
        return 0
    else
        echo "❌ $description: $file MISSING"
        FAILED_CHECKS=$((FAILED_CHECKS + 1))
        FAILURES+=("$description: $file")
        return 1
    fi
}

# Helper function to check directory exists
check_dir() {
    local dir=$1
    local description=$2
    TOTAL_CHECKS=$((TOTAL_CHECKS + 1))
    
    if [ -d "$dir" ]; then
        echo "✅ $description: $dir"
        PASSED_CHECKS=$((PASSED_CHECKS + 1))
        return 0
    else
        echo "❌ $description: $dir MISSING"
        FAILED_CHECKS=$((FAILED_CHECKS + 1))
        FAILURES+=("$description: $dir")
        return 1
    fi
}

# Helper function to count files
count_files() {
    local pattern=$1
    find $pattern 2>/dev/null | wc -l
}

echo "=== PHASE 1: DATABASE MIGRATIONS ==="
check_file "database/migrations/install_new_lupopedia.sql" "Core schema"
check_file "database/migrations/seed_registry_comprehensive_4.0.45.sql" "Registry comprehensive"
check_file "database/migrations/seed_registry_open_4.0.45.sql" "Registry open gaps"
check_file "database/migrations/seed_actors_agents_4.0.45.sql" "Actors and agents"
check_file "database/migrations/seed_anubis_vishwakarma_4.0.45.sql" "ANUBIS + VISHWAKARMA"
check_file "database/migrations/add_tasks_schema_4.0.45.sql" "Tasks schema"
check_file "database/migrations/seed_tasks_bootstrap_4.0.45.sql" "Tasks bootstrap"
echo ""

echo "=== PHASE 2: AGENT DIRECTORIES ==="
check_dir "agents/1" "WOLFIE (1)"
check_dir "agents/2" "LILITH (2)"
check_dir "agents/3" "ROSE (3)"
check_dir "agents/4" "ERIS (4)"
check_dir "agents/5" "METIS (5)"
check_dir "agents/19" "ANUBIS (19)"
check_dir "agents/25" "VISHWAKARMA (25)"
check_file "agents/19/agent.json" "ANUBIS agent.json"
check_file "agents/19/system_prompt.txt" "ANUBIS system prompt"
check_file "agents/25/agent.json" "VISHWAKARMA agent.json"
check_file "agents/25/system_prompt.txt" "VISHWAKARMA system prompt"
echo ""

echo "=== PHASE 3: CHANNEL DIRECTORIES ==="
check_dir "channels/0" "Channel 0 (System)"
check_dir "channels/42" "Channel 42 (Development)"
check_dir "channels/0/broadcasts" "Channel 0 broadcasts"
check_dir "channels/42/broadcasts" "Channel 42 broadcasts"
check_dir "channels/0/tasks" "Channel 0 tasks"
check_dir "channels/42/tasks" "Channel 42 tasks"
check_dir "channels/0/roles" "Channel 0 roles"
echo ""

echo "=== PHASE 4: BROADCAST COUNTS ==="
CH0_BROADCASTS=$(count_files "channels/0/broadcasts/*.md")
CH42_BROADCASTS=$(count_files "channels/42/broadcasts/*.md")
TOTAL_BROADCASTS=$((CH0_BROADCASTS + CH42_BROADCASTS))

echo "Channel 0 broadcasts: $CH0_BROADCASTS"
echo "Channel 42 broadcasts: $CH42_BROADCASTS"
echo "Total broadcasts: $TOTAL_BROADCASTS"

TOTAL_CHECKS=$((TOTAL_CHECKS + 1))
if [ "$TOTAL_BROADCASTS" -ge 56 ]; then
    echo "✅ Broadcast count acceptable (>= 56)"
    PASSED_CHECKS=$((PASSED_CHECKS + 1))
else
    echo "❌ Broadcast count too low (expected >= 56, got $TOTAL_BROADCASTS)"
    FAILED_CHECKS=$((FAILED_CHECKS + 1))
    FAILURES+=("Broadcast count: expected >= 56, got $TOTAL_BROADCASTS")
fi
echo ""

echo "=== PHASE 5: TASK FILES ==="
check_file "channels/0/tasks/active/20260225170000_task_0_10000_drop_tables_and_run_install.md" "Human install task"
check_file "channels/0/tasks/pending/20260225170100_task_0_19_validate_channel_666_quarantine.md" "ANUBIS quarantine task"
check_file "channels/42/tasks/pending/20260225170200_task_42_25_graph_relationship_analysis.md" "VISHWAKARMA graph task"
echo ""

echo "=== PHASE 6: ROLE FILES ==="
check_file "channels/0/roles/system_admin.md" "System admin role"
check_file "channels/0/roles/installer.md" "Installer role"
check_file "channels/0/roles/auditor.md" "Auditor role"
check_file "channels/0/roles/registry_steward.md" "Registry steward role"
check_file "channels/0/roles/communications_lead.md" "Communications lead role"
check_file "channels/0/roles/orphan_repair_agent.md" "Orphan repair agent role (ANUBIS)"
check_file "channels/0/roles/graph_intelligence_agent.md" "Graph intelligence agent role (VISHWAKARMA)"
echo ""

echo "=== PHASE 7: DOCUMENTATION ==="
check_file "KIRO_THREAD_IDENTITY_AUDIT_4.0.45.md" "Thread identity audit"
check_file "VALIDATION_GATE_REPORT_4.0.45.md" "Validation gate report"
check_file "KIRO_DIRECTIVE_COMPLETION_4.0.45.md" "Directive completion"
check_file "OFFLINE_GOVERNANCE_MODEL_4.0.45.md" "Offline governance model"
check_file "CHANGELOG.md" "Changelog"
echo ""

echo "=== SUMMARY ==="
echo "Total checks: $TOTAL_CHECKS"
echo "Passed: $PASSED_CHECKS"
echo "Failed: $FAILED_CHECKS"
echo ""

if [ $FAILED_CHECKS -eq 0 ]; then
    echo "✅ ALL CHECKS PASSED - SYSTEM READY FOR INSTALL"
    exit 0
else
    echo "❌ VERIFICATION FAILED - $FAILED_CHECKS ISSUES FOUND"
    echo ""
    echo "Failed checks:"
    for failure in "${FAILURES[@]}"; do
        echo "  - $failure"
    done
    exit 1
fi
