# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\channels\schema\migrations\8_TABLE_REDUCTION_PLAN_4.1.17.md"
  file_hash: "842954025db4aa1acc9362bbaa99d27254a3752ce0056a2e2667eb1f295fbe58"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\schema\migrations\8_TABLE_REDUCTION_PLAN_4.1.17.md"
  file_hash: "07200b9327bb8b41b41f0ce6ea5d25c412ebaa28cf148c6b71d23b99a86ea841"
  file_path_from_root: "docs\channels\schema\migrations\8_TABLE_REDUCTION_PLAN_4.1.17.md"
  file_hash: "856d98638dcd4472321752930988ca067fe9e618bb2cf577b1c34182e7764e7b"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "8‑Table Reduction Plan (Doctrine Compliance)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "schema", "migrations", "8_table_reduction_plan_4117md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# 8‑Table Reduction Plan (Doctrine Compliance)

**Target version:** 3.1.17  
**Purpose:** Reduce table count toward doctrine target.  
**Status:** Plan; execute after freeze lift or approval.

---

## Candidate Tables for Removal (Legacy, Safe, Unused)

1. `livehelp_channels`
2. `livehelp_messages`
3. `livehelp_modules`
4. `livehelp_modules_dep`
5. `livehelp_operator_channels`
6. `livehelp_referers_monthly`
7. `livehelp_sessions`
8. `livehelp_visit_track`

---

## Why These 8

- No INSERT targets in `craftysyntax_to_lupopedia_mysql.sql`
- No JOIN dependencies in the migration script
- No modern Lupopedia equivalents in scope
- Fully deprecated in migration audit
- Removal reduces table count toward doctrine target

---

## Patch Version Recommendation

- Perform reduction in **3.1.17**
- Document in CHANGELOG + dialogs
- Regenerate TOON files after DROP

---

## Pre‑reduction Checklist

- [ ] Confirm migration script has run and legacy data is migrated or archived
- [ ] Confirm no application code references these tables
- [ ] Backup or document final row counts if needed
- [ ] Run DROP in migration; then regenerate TOON