# LUPOPEDIA HEADERS (replaces FLARE) — see http://www.lupopedia.com/status/REPOSITORY_CLEANUP_SAFE_LIST_4.0.57
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "report"
  file_path_from_root: "docs/status/REPOSITORY_CLEANUP_SAFE_LIST_4.0.57.md"
  web_path: "http://www.lupopedia.com/status/REPOSITORY_CLEANUP_SAFE_LIST_4.0.57"
  last_modified_utc: "20260306"
  system_version: "4.0.57"
  channel_id: 42
  actor_id: 1003
  delegation_chain: "1003:10000"
  artifact_type: "report"
  artifact_kind: "analysis"
  purpose: "Safe cleanup list for repository_cleanup_legacy_files_removal; no deletion of required assets"
  mood_rgb: "4169E1"
  traits: ["report", "v4.0.57", "cleanup", "safe_list"]
  tags: ["4.0.57", "repository_cleanup", "cursor"]
  lupo_agent: "cursor"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/repository_cleanup_legacy_files_removal.md", type: "references", weight: 1.0 }
    - { to: "DIRECTORY_TREE.md", type: "references", weight: 0.9 }
    - { to: ".cursor/rules/required-tables-future-features-doctrine.mdc", type: "references", weight: 0.9 }
lupopedia.footer:
  last_verified: "20260306"
  last_verified_by: "cursor"
---

## 1. Scope and Doctrine

Per **repository_cleanup_legacy_files_removal** (Channel 42, v4.0.57) and **required-tables-future-features-doctrine**:

- **Canonical SQL:** Install/seed/import/migrations live under **lupo-database/lupopedia/mysql/** (install/, seed/, import/, migrations/, manifest/). The repo-root **database/migrations/** is no longer the installer source of truth.
- **Migrations cleanup:** All SQL in **database/migrations/** that is not part of the canonical set (Crafty→Lupopedia path only) should be **moved** to **database/migrations_legacy/** — no deletion of required assets.
- **Directory tree:** Per doctrine §6, run `python scripts/generate_directory_tree.py` and use **DIRECTORY_TREE.md** as the authoritative file list before any cleanup.
- **No Lupopedia→Lupopedia upgrades:** Only Crafty Syntax 3.7.5 → Lupopedia 4.0.x is supported; one-time Lupopedia→Lupopedia migrations are legacy.

---

## 2. database/migrations/ — Safe Move List

The following files in **database/migrations/** are **Lupopedia→Lupopedia** or one-off dev migrations. **Recommended action:** Move to **database/migrations_legacy/** (do not delete). Canonical installer SQL is already under **lupo-database/lupopedia/mysql/**; install.php does not load from database/migrations/.

| File | Notes |
|------|--------|
| actor_420_complete_integration_4.0.23.sql | Lupo→Lupo |
| actor_420_registry_integration_4.0.23.sql | Lupo→Lupo |
| add_forwarding_columns_4.0.24.sql | Lupo→Lupo |
| add_tasks_schema_4.0.45.sql | Lupo→Lupo |
| 4.0.29_20260222_edge_resolution_headers.sql | Lupo→Lupo |
| 4.0.29_20260222_add_featured_flag.sql | Lupo→Lupo |
| 4.0.29_20260222_anubis_unknown_recipient_routing.sql | Lupo→Lupo |
| 4.0.29_20260222_hybrid_actor_security_gate.sql | Lupo→Lupo |
| 4.0.30_20260222_semantic_security_framework.sql | Lupo→Lupo |
| 4.0.31_20260223_semantic_security_install_integration.sql | Lupo→Lupo |
| 4.0.35_register_ide_agents.sql | Lupo→Lupo |
| 4.0.41_fair_harmonization.sql | Lupo→Lupo |
| 20260217_add_contents_file_path_from_root_index.sql | Lupo→Lupo |
| 20260217_add_flip_header_fields.sql | Lupo→Lupo |
| 20260217_add_missing_flip_fields.sql | Lupo→Lupo |
| 20260218_create_lupo_banned_actors.sql | Lupo→Lupo |
| 20260219_create_lupo_bans_log.sql | Lupo→Lupo |
| 20260220_consolidate_content_tables.sql | Lupo→Lupo |
| 20260222_420_final_closure.sql | Lupo→Lupo |
| 20260224_add_read_receipts_to_dialog_messages.sql | Lupo→Lupo |
| 20260226_4_0_49_schema_updates.sql | (if present) Lupo→Lupo |
| 20260227_4_0_49_schema_updates.sql | Lupo→Lupo |
| 20260301_add_lupo_channel_content.sql | Lupo→Lupo |
| dev_20260222_fix_seed_schema_mismatch.sql | Dev one-off |
| dev_20260223_registry_consolidation.sql | Dev one-off |
| dev_20260226_normalize_engagement_schema.sql | Dev one-off |
| dev_20260227_dbdoc_schema_updates.sql | Dev one-off |
| dev_20260227_actor_directory_enhancement_4_0_48.sql | Dev one-off |
| dev_20260303_lilith_flame_faucet.sql | Dev one-off (canonical copy in lupo-database/.../mysql/migrations/ or apply there) |
| engagement_schema_normalization_4_0_48.sql | Lupo→Lupo |
| final_ide_ai_actor_integration_4.0.23.sql | Lupo→Lupo |
| fix_identity_collision_4.0.29.sql | Lupo→Lupo |
| importer_patch_4.0.22.sql | Lupo→Lupo (verify not used by import_from_old_crafty) |
| migrate_documents_to_artifacts_4.0.42.sql | Lupo→Lupo |
| migrate_refinements_to_tickets_4.0.42.sql | Lupo→Lupo |
| migration_4.0.48_actor_identity_capsule.sql | Lupo→Lupo |
| register_external_ai_actors_4.0.23.sql | Lupo→Lupo |
| register_ide_actors_4.0.23.sql | Lupo→Lupo |
| seed_all_25_ai_agents_4.0.24.sql | Seed one-off (canonical seeds in lupo-database/.../mysql/seed/) |
| seed_anubis_vishwakarma_4.0.45.sql | Seed one-off |
| seed_antigravity_flip_4.0.27.sql | Seed one-off |
| seed_antigravity_ide_4.0.23.sql | Seed one-off |
| seed_channel_420_complete_4.0.25.sql | Seed one-off |
| seed_educational_messages_4.0.24.sql | Seed one-off |
| seed_minimal_4.0.26.sql | Seed one-off |
| seed_tasks_bootstrap_4.0.45.sql | Seed one-off |
| seed_actor_identity_capsule_4.0.48.sql | Seed one-off |
| seed_lupopedia_comprehensive.sql | Duplicate/candidate for canonical seed? Verify against lupo-database/.../mysql/seed/ |
| survivor_protocol_4.0.24.sql | Lupo→Lupo |
| table_consolidation_phase1.sql | Lupo→Lupo |
| table_consolidation_phase2.sql | Lupo→Lupo |
| table_consolidation_phase3.sql | Lupo→Lupo |
| table_optimization_changes_for_install.sql | One-off; install is in lupo-database/.../mysql/install/ |
| verify_active_agents_4.0.26.sql | Lupo→Lupo |
| CRITICAL_SCHEMA_FIX_4.0.26.sql | Lupo→Lupo |
| single_message_header_update_template.sql | Template; move or archive |
| bulk_update_flip_headers_4.0.24.sql | Lupo→Lupo |

**Do NOT move (required / referenced):**

- **seed_lupopedia.sql** — Referenced by doctrine and possibly install flow; confirm whether canonical copy is in lupo-database/.../mysql/seed/ and if so, this can be a symlink or removed after verification.
- **future_features_lupopedia.sql** — Canonical copy is in **lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql**; repo-root copy may be redundant — verify then move or remove reference only.

**Pre-step:** Run `python scripts/generate_directory_tree.py` and refresh DIRECTORY_TREE.md before moving files.

---

## 3. Other Directories — Review Only (No Deletion)

Per task, the following are **review only**; propose no deletion until manual file-by-file review:

- **audits/** — Old audit files; keep current and referenced.
- **dialogs/** — Old dialog files; keep if referenced by channels/threads.
- **docs/status/** — Status/reports; keep v4.0.56/v4.0.57 and referenced reports; archive only with approval.
- **lupo-docs/**, **lupo-database/** — Do not delete; align with DIRECTORY_TREE and doctrine.

---

## 4. Summary

- **Safe action:** Move the listed **database/migrations/** SQL files to **database/migrations_legacy/** after regenerating DIRECTORY_TREE.md and confirming install.php (and any docs) do not reference them. Do not delete required assets or canonical installer SQL under **lupo-database/lupopedia/mysql/**.
- **Delegation:** Captain Wolfie (10000) to confirm before bulk move of database/migrations/ files. Lilith (2) optional meta-review of this safe list if desired.

---

## 5. Timestamp and Actor

- **Report generated:** 2026-03-06  
- **Actor ID:** 1003 (Cursor IDE Agent)  
- **lupo_agent:** cursor  

---

*End of report.*
