# [2026-04-04] PRD 17 thread filenames, PRD 02/29 alignment, org thread + schema

- **`lupo-docs/prd/17_decisions_format.md`:** Authoritative **Thread filename pattern** (per-folder `TYPE`/`STATUS`, `HHIISS`, optional `YYYYMMDDHHIISS` prefix); validator and diagram updates.
- **`lupo-docs/prd/02_channels_discussions.md`**, **`lupo-docs/prd/29_project_structure.md`:** Cross-links to PRD 17; PRD 29 edge to PRD 17.
- **`lupo-docs/versions/4.0.93/README.md`:** Points to PRD 17 for full naming rules; decision example uses `DECISION_APPROVED_…`.
- **`lupo-channels/0/organization/prd_29_project_organization/`:** Cherry-pick review comment and thread indexes (PRD 29 coordination).
- **Schema / tooling:** `install_new_lupopedia.sql`, `add_thread_key_to_dialog_threads.sql`, JSON registry files, `generate_toon_files.py`, **`lupo-docs/doctrine/JSON_SCHEMA_REFERENCE_DOCTRINE.md`**.

# [2026-04-03] 4.0.93 TODO freeze cleanup + PRD 29 channel strategy

- **`lupo-docs/versions/4.0.93/TODO.md`:** Removed all open checkboxes; **Open Work → 4.0.94 Only** pointer; historical `[x]` completions retained.
- **`lupo-docs/versions/4.0.94/TODO.md`:** Merged deduplicated backlog from former 4.0.93 open items (installer, Softaculous, Glass, migration, tooling, etc.).
- **`lupo-docs/prd/29_project_structure.md`:** Channel filesystem strategy table (old archive vs new tree); coordination path `lupo-channels/0/organization/prd_29_project_organization/`.
- **`lupo-channels/channel_index.md`:** Added **organization** channel.
- **`lupo-channels/0/organization/prd_29_project_organization/`:** New thread scaffold (`README.md`, `decisions|questions|answers|comments/THREAD_INDEX.md`).

# [2026-04-02] Bump GLOBAL_CURRENT_LUPOPEDIA_VERSION to 4.0.94

- `lupo-config/global_atoms.yaml` and `lupo-includes/version.php` now report **4.0.94** for the working tree (after tag `v4.0.93`).

# [2026-04-02] Scaffold 4.0.94 version directory

- Added working version folder `lupo-docs/versions/4.0.94/` with `PLAN.md`, `TODO.md`, `CHANGELOG.md`, `edges.md`, `decisions/`, `questions/`, `answers/`, `comments/`, and `prd/`.
- PRD 30 working copy: `prd/30_prd_development_guide.md` (moved from `lupo-docs/prd/`).
- PRD 31 stub: `prd/31_context_system.md` for redesign after 4.0.93 rejection of parallel classification.

# Lupopedia 4.0.94 CHANGELOG

Further entries go below this line as work completes.
