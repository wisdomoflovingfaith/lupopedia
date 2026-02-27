---
task_id: "CH0-20260226-005"
channel_id: 42
assigned_to: [1001]
status: "in-progress"
priority: "high"
created_utc: "20260226"
target_version: "4.0.49"
rolled_from: "4.0.48"
task_type: "database_optimization"
---

# 🔍 Legacy Table Optimization Review

**Task ID:** CH0-20260226-005  
**Assigned:** Windsurf (1001)  
**Priority:** High  
**Status:** 🔄 In Progress  

## Objective
Optimize and review all legacy Crafty Syntax tables used in Lupopedia, focusing on schema consistency and performance improvements.

## Scope
1. Review all 34 legacy tables from Crafty Syntax.
2. Identify redundant indexes or missing performance optimizations.
3. Ensure all tables follow Lupopedia's "Dumb Storage" doctrine (no FKs).
4. Standardize field types (BIGINT for timestamps, etc.).

## Next Steps
- Manual review of TOON files for legacy tables.
- Cross-reference with usage in `lupo-includes/`.
- Prepare optimization SQL migration.
