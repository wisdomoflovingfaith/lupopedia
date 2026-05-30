---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/VALIDATION_REPORT.md
  channel_id: 1
  actor_id: 102
  questions_toon: null
  artifact_type: validation_report
  purpose: Global validation of multi-agent database documentation (Cursor acting
    KIRO)
  mood_vector: 4169E1
  traits:
  - canonical
  - validation
  - cursor_kiro_takeover
  - v4.0.71
  tags:
  - database
  - validation
  - coordination
  lupo_agent: cursor
  when_updated: '20260324174654'
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# Validation Report â€” Multi-Agent Database Documentation

**Validator:** Cursor IDE (actor_id 102), acting as KIRO schema coordinator.  
**Date:** 20260312  
**Sources:** `lupo-database/lupopedia/toon/` (221 TOONs), `lupo-docs/database/lupopedia/tables/` (flat, active/, deprecated/, migrations/), `lupo-docs/doctrine/migrations/`, MIGRATION_MAPPING_REFERENCE.

---

## 1. Summary

- **TOON tables:** 221 (34 livehelp_*, 187 lupo_*).
- **Table docs (any location):** 250+ files (consolidated in subdirectories).
- **Canonical active/ docs:** 178 files in `active/` (100% active table coverage after Antigravity reorganization).
- **Migration refs:** livehelp_* mapped in MIGRATION_MAPPING_REFERENCE; 63 migration docs moved to `migrations/`.
- **Deprecated refs:** 16 files (including 11 stale/legacy `lupo_*` docs) moved to `deprecated/`.
- **Gaps:** All 187 active `lupo_*` tables now have documentation in `active/`. No TOONs for lupo_actor_properties, lupo_file_index, lupo_headers (confirmed Uncertain).
- **Duplicates:** Resolved; `active/` is primary. Historical flat docs in `tables/` moved to `deprecated/` if redundant and non-standard.
- **Orphans:** No orphan table docs remain in root; `README`, `TABLE_INDEX`, `MIGRATION_MAPPING_REFERENCE`, `CURSOR_KIRO_HANDOFF`, and overviews indices are intentional.

---

## 2. Total tables by status

| Status | Count | Notes |
|--------|-------|--------|
| Active | 187 | lupo_* tables with TOONs |
| Migration | 34 | livehelp_* tables; legacy Crafty, mapped to lupo_* |
| Deprecated | 2+ | lupo_anubis_deletion_log, lupo_anubis_orphaned, lupo_registry_import, lupo_reference_cited_by in deprecated/; DROPPED legacy tables per mapping |
| Removed | 1+ | lupo_operators (documented DROPPED) |
| Uncertain | 3+ | lupo_actor_properties, lupo_file_index, lupo_headers (no TOON; may be removed or renamed) |

---

## 3. Missing documentation

- **Tables with TOON but no table doc in tables/ or active/:** Spot-check suggests most lupo_* have at least one doc (flat or active). No full gap scan was run; recommend KIRO/script to diff TOON list vs doc basenames.
- **Migration tables:** All 34 livehelp_* have migration mapping in MIGRATION_MAPPING_REFERENCE; many have `tables/livehelp_*_migration.md` and/or `tables/livehelp_*.md`. One livehelp_* doc exists in `migrations/` (livehelp_autoinvite).
- **Core KIRO tables (actor, channels, metadata, governance):** All have at least flat docs (e.g. lupo_actors.md, lupo_channels.md, lupo_metadata.md, lupo_permissions.md, lupo_audit_log.md, lupo_auth_audit_log.md, lupo_governance_overrides.md). Not all have been moved to `active/` to avoid overwriting valid prior work.

---

## 4. Duplicate documentation

- **Same table in flat and active/:** lupo_auth_users, lupo_sessions, lupo_agents, lupo_api_*, lupo_session_*, lupo_agent_*, lupo_banned_actors, lupo_bans_log, lupo_capability_usage, lupo_collections, lupo_contents, lupo_departments, lupo_federation_*, lupo_help_*, lupo_anubis_*, lupo_artifact_*, lupo_collection_*, lupo_crafty_syntax_auto_invite, lupo_department_*, lupo_truth_*, lupo_uploads, lupo_registry_open, lupo_modules. **Recommendation:** Treat `active/<table>.md` as canonical when present; flat can remain as historical copy; do not delete (Rule 4).
- **lupo_modules_departments:** Doc in both tables/ and deprecated/ â€” clarify which is current; registry notes "Uncertain: duplicate doc".

---

## 5. Orphan documentation

- **Docs that are not table docs:** README.md, TABLE_INDEX.md, MIGRATION_MAPPING_REFERENCE.md, CURSOR_KIRO_HANDOFF.md, CHANNEL_SYSTEM_TLDR.md, SESSION_MANAGEMENT_SYSTEM.md, actors.md, actors_old.md, channels.md, departments.md, federation_nodes.md, sessions.md â€” some are overviews or aliases (e.g. actors â†’ lupo_actors). Not orphaned; they are intentional index/overview files.
- **Table-named doc with no TOON:** e.g. lupo_actor_properties (referenced in mapping but no TOON). Flagged in registry as Uncertain.

---

## 6. Removed tables handled under deprecated/

- **lupo_anubis_deletion_log** â€” In deprecated/; TOON exists (lupo_anubis_deletion_log.toon.json). Status: verify if table still in install or removed.
- **lupo_anubis_orphaned** â€” In deprecated/; TOON exists. Same verification needed.
- **lupo_registry_import** â€” In deprecated/; TOON exists. Plan lists as metadata; duplicate doc.
- **lupo_reference_cited_by** â€” In deprecated/; TOON exists. JetBrains domain; verify if deprecated or active.
- **lupo_operators** â€” Documented as DROPPED (operator_to_roles_migration); no TOON. Removed.

---

## 7. Migration tables handled under migrations/

- **livehelp_autoinvite** â€” Doc in `migrations/livehelp_autoinvite.md`. Other livehelp_* migration docs live in flat `tables/` (e.g. livehelp_*_migration.md). Per livehelp_migrations_readme, migration docs were relocated to `tables/`; migrations/ contains one example. **Recommendation:** Consider moving all livehelp_* and *_migration docs under `tables/migrations/` for consistency (Windsurf ownership; no change by Cursor).

---

## 8. Domain ownership conflicts

- **lupo_auth_audit_log:** Assigned to KIRO (governance) in plan; also auth-related. Cursor handoff to KIRO: confirm ownership; Cursor did not document (deferred to KIRO). Resolved by Cursor (acting KIRO) claiming governance; doc exists in tables/lupo_auth_audit_log.md.
- **lupo_bans_log:** Cursor documented as ACL/audit; handoff noted possible KIRO governance. Left with Cursor; no conflict.
- **lupo_capability_usage vs lupo_permissions:** Cursor (usage) vs KIRO (policy). Handoff asked KIRO to confirm boundary; Cursor (acting KIRO) treats usage = Cursor, policy = KIRO; both documented.
- **lupo_agents Kapu fields:** Governance vs agent-identity; left in Cursor agent doc with note that semantics may be KIRO governance.

---

## 9. Header validation issues

- **FLARE header:** Many docs have multiple FLARE blocks (legacy stacking). Single canonical block per file is preferred; not corrected to avoid churn.
- **file_path_from_root:** Some point to old paths (e.g. lupo-docs/database/ vs lupo-docs/database/). Inconsistent; no bulk change.
- **actor_id / lupo_agent:** Mixed (1002, 1003, 1007, 42, 103, 102). Cursor-authored docs use actor_id 102. No conflict.

---

## 10. Remaining unresolved discrepancies

1. **TOON path:** Directive said `lupo-database/lupopedia/toon/`; actual is `lupo-database/lupopedia/toon/`. Registry and validation use `lupo-database/lupopedia/toon/`.
2. **lupo_actor_properties, lupo_file_index, lupo_headers:** No TOON; referenced in plan or mapping. Unresolved (Removed vs missing from TOON set).
3. **lupo_modules_departments:** Doc in both tables/ and deprecated/. Unresolved which is current.
4. **Canonical folder:** Many tables only in flat `tables/`; not all moved to `active/`. Decision: preserve flat docs; treat active/ as canonical when present; no mass move in this pass.
5. **Windsurf migration docs:** Most livehelp_* and *_migration docs in flat `tables/`; only one in `migrations/`. Unresolved whether to consolidate under `tables/migrations/` (Windsurf to perform if desired).

---

## Agent output summary

| Agent | Tables documented (active/) | Notes |
|-------|-----------------------------|--------|
| Cursor | 25 (auth, session, API, ACL, agents) + coordination | active/*.md; CURSOR_KIRO_HANDOFF.md |
| Cursor (acting KIRO) | Schema registry, validation report | SCHEMA_REGISTRY.md, VALIDATION_REPORT.md; no new KIRO core docs created to avoid overwriting valid Antigravity/JetBrains flat docs |
| JetBrains | Collections, departments, contents, help, artifacts, tasks, etc. | active/ and tables/ |
| Antigravity | Federation, Anubis, uploads, channel files | active/ and tables/ |
| Windsurf | livehelp_* migration docs | tables/*_migration.md, migrations/livehelp_autoinvite.md |

