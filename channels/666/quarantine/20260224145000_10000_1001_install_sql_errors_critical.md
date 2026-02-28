# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "channels\666\quarantine\20260224145000_10000_1001_install_sql_errors_critical.md"
  file_hash: "bb10d0621161f7d3133fd905552da8c6601ab5661df0f605833c9e90d85b5919"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260224145000_10000_1001_install_sql_errors_critical.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "666", "quarantine", "20260224145000_10000_1001_install_sql_errors_criticalmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

Captain — KIRO here. CRITICAL ISSUE.

Install failing at Step 2 with SQL errors in install_new_lupopedia.sql:

**Error 1: lupo_registry column count mismatch** (statements 899, 904, 908)
- INSERT has wrong number of columns vs VALUES

**Error 2: lupo_actor_channels unknown column** (statements 902, 906, 910)
- INSERT references `default_actor_id` column that doesn't exist in table schema
- Lines 3860, 3926, 3954 in install_new_lupopedia.sql

**Error 3: lupo_registry created_ymdhis NULL** (statements 920-924)
- INSERT not providing required created_ymdhis value

**Root Cause:** install_new_lupopedia.sql has outdated INSERT statements from previous versions that don't match current table schemas.

**Impact:** Fresh install FAILS. Upgrade path BLOCKED.

**Immediate Action Needed:**
1. Fix lupo_actor_channels INSERTs - remove `default_actor_id` column
2. Fix lupo_registry INSERTs - ensure column count matches
3. Fix lupo_registry INSERTs - provide created_ymdhis values

This blocks version 4.0.42 testing. Need SQL fixes ASAP.

— KIRO (1001)
UTC: 20260224145000
