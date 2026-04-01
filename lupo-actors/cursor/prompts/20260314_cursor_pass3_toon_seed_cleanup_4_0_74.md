---
lupopedia.init:
  required_reading:
    - path: "plan.md"
      reason: "Root consolidated implementation plan and remaining P1/P2 priorities"
    - path: "report.md"
      reason: "Consolidated findings and evidence base already accepted into root canon"
    - path: "CHANGELOG.md"
      reason: "Append only verified implementation results for 4.0.74"
    - path: "lupo-docs/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74.md"
      reason: "Continue from Pass 2 and record exact Pass 3 implementation results"
    - path: "lupo-docs/database/lupopedia/SCHEMA_REGISTRY.md"
      reason: "Schema registry must remain aligned with install SQL and derived artifacts"
    - path: "lupo-docs/database/lupopedia/tables/active/lupo_projects.md"
      reason: "Reference example of current schema documentation alignment"
    - path: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
      reason: "Canonical block ordering and header conventions"
    - path: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS.md"
      reason: "next_actions, edges, actor references, and backward compatibility doctrine"
    - path: "lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md"
      reason: "lupopedia.init discipline must continue during wider cleanup"
    - path: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"
      reason: "Canonical schema authority"
    - path: "lupo-database/lupopedia/mysql/seed/seed_projects.sql"
      reason: "Seed artifact to either wire into installer or explicitly preserve as manual"
  required_context:
    - "Cursor (actor_id 102) remains lead orchestrator for the root implementation pass."
    - "Pass 2 completed root doc alignment; this pass must now resolve schema-adjacent repo truth and remaining canonical cleanup."
    - "Install SQL is the schema authority; TOON artifacts are derived outputs."
    - "Do not claim a canonical TOON path unless tooling, docs, and output behavior are aligned to make that statement true."
    - "Do not guess about generator behavior, installer seed flow, or file authority. Verify directly and document honestly."

lupopedia.actor_references:
  comment: "Actor IDs per lupo-database/lupopedia/actors/actor_id/registry.json"
  cursor: 102
  wolfie: 1
  kiro: 100
  windsurf: 101
  antigravity: 103
  warp: 104
  cascade: 105
  codex: "TBD — JetBrains/Codex not in registry; do not invent a numeric ID"

lupopedia.metadata:
  comment: "Implementation directive for Cursor Pass 3: TOON path authority, seed integration decision, and broader canonical cleanup."
  title:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Cursor Implementation Directive — Pass 3 TOON alignment and canonical cleanup", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260314000000, updated_ymdhis: 20260314000000 }
  description:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Directive for Cursor to verify TOON generation/output truth, decide installer seed wiring status, and continue doctrine-aligned canonical cleanup for v4.0.74.", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260314000000, updated_ymdhis: 20260314000000 }
  author:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "wolfie", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260314000000, updated_ymdhis: 20260314000000 }
  orchestrator:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "cursor", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260314000000, updated_ymdhis: 20260314000000 }

lupopedia.headers:
  lupopedia.version: "4.0.74"
  lupopedia.schema: "directive"
  file_path_from_root: "lupo-prompts/cursor/20260314_cursor_pass3_toon_seed_cleanup_4_0_74.md"
  web_path: "http://www.lupopedia.com/prompts/cursor/20260314_cursor_pass3_toon_seed_cleanup_4_0_74"
  last_modified_utc: "20260314"
  system_version: "4.0.74"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  delegation_chain: "wolfie:cursor"
  artifact_type: "implementation-directive"
  artifact_kind: "execution"
  purpose: "Direct Cursor to execute Pass 3: verify TOON paths/output authority, resolve seed integration status, and continue canonical repo cleanup"

lupopedia.edges:
  comment: "Execution directive edges for Pass 3."
  outbound_edges:
    - { to: "plan.md", type: "implements", weight: 1.0 }
    - { to: "report.md", type: "references", weight: 0.95 }
    - { to: "CHANGELOG.md", type: "updates", weight: 0.95 }
    - { to: "README.md", type: "references", weight: 0.9 }
    - { to: "AGENTS.md", type: "references", weight: 0.85 }
    - { to: "lupo-docs/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74.md", type: "updates", weight: 0.95 }
    - { to: "lupo-docs/database/lupopedia/SCHEMA_REGISTRY.md", type: "updates", weight: 0.92 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS.md", type: "references", weight: 0.85 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "authority", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/seed/seed_projects.sql", type: "authority", weight: 0.95 }
    - { to: "lupo-prompts/cursor/20260314_cursor_execute_plan_4_0_74.md", type: "follows", weight: 0.9 }
  semantic_tags: ["cursor", "directive", "implementation", "toon", "seed", "cleanup", "v4.0.74"]

lupopedia.footer:
  version: "4.0.74"
  last_verified: "20260314"
  last_verified_by: "wolfie"
  orchestrator: "cursor"
  next_action:
    - "Verify actual TOON generation scripts, output paths, and repo truth before editing docs."
    - "Decide and implement seed_projects installer handling or document the manual status explicitly."
    - "Update CHANGELOG and implementation report only with verified results."

lupopedia.next_actions:
  next_actions:
    - "Execute Pass 3 implementation items in order"
    - "Update CHANGELOG.md with one clean Pass 3 subsection under 4.0.74"
    - "Update CURSOR_IMPLEMENTATION_REPORT_4_0_74.md with exact file list and validation results"
---
# Cursor Implementation Directive — Pass 3 TOON alignment, seed integration, and canonical cleanup (v4.0.74)

Cursor, Pass 2 completed the root documentation alignment successfully.

This next pass is for the remaining implementation items that are still materially important for 4.0.74:

1. **TOON generation/output path truth**
2. **Installer seed integration decision**
3. **Broader canonical cleanup in touched files**
4. **Schema/documentation consistency evidence**
5. **P1 merge-process and inventory groundwork where directly implementable**

Do not produce another planning-only response.
Implement verified corrections and then document exactly what changed.

---

## Primary goals

### Goal 1 — Resolve TOON path/output truth
Verify TOON-related scripts, output paths, and align documentation to verified truth.

### Goal 2 — Resolve `seed_projects.sql` installer status
Either wire `seed_projects.sql` into installer seed execution or document manual status clearly.

### Goal 3 — Build a single schema inventory artifact or section
Create or update a canonical comparison: install SQL tables, TOON coverage, registry counts (truthful; distinguish authority vs derived vs gaps).

### Goal 4 — Continue canonical cleanup in touched files only
Prefer `lupopedia.*`; correct misused `lupopedia.init`; prefer `lupopedia.next_actions` where needed.

### Goal 5 — Strengthen merge-process clarity
Document merge rules: when faucet-specific files remain authoritative vs when root canon absorbs; practical rules only.

---

## Execution order

1. Verify TOON scripts, path references, and output locations
2. Verify installer seed execution flow and `seed_projects.sql` status
3. Implement whichever verified fixes are safe
4. Create/update schema inventory comparison artifact or section
5. Apply any directly related canonical cleanup in touched files
6. Update `CHANGELOG.md`
7. Update `lupo-docs/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74.md`

---

*Cursor (actor_id 102) — Pass 3 directive 2026-03-14*
