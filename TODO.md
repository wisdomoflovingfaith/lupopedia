---
lupopedia.init:
  file_identity: "TODO.md"
  artifact_type: "repository-core"
  artifact_kind: "metadata-snapshot"
  namespace: "lupopedia"
  domain: "core"
  system_version: "4.0.75"

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."

lupopedia.headers:
  lupopedia.version: "4.0.75"
  lupopedia.schema: "documentation"
  file_path_from_root: "TODO.md"
  web_path: "http://www.lupopedia.com/TODO"
  last_modified_utc: "20260314"
  system_version: "4.0.75"
  channel_id: 42
  actor_id: 102
  artifact_type: "repository-core"
  artifact_kind: "todo"
  purpose: "Root-level pending tasks and next actions for Lupopedia (v4.0.75)."
  tags: ["todo", "tasks", "core", "v4.0.75"]

lupopedia.edges:
  comment: "Snapshot at artifact creation. Core repo files."
  meta: "Orchestrator audit fixes; metadata headers."
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "README.md", type: "references", weight: 1.0 }
    - { to: "lupo-rules/root/README.md", type: "references", weight: 0.9 }

lupopedia.engagement:
  comment: "Root TODO for orchestrator and agents."
  meta: "L-LUPO-ROOT-CURSOR; v4.0.75."
  views: 0

lupopedia.footer:
  version: "4.0.75"
  last_verified: "20260314"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "v4.0.76: Continue repeated install/upgrade validation (see By version below)"
    - "If using orchestrator rules: run future_features_lupopedia.sql for lupo_orchestrator_rules, then php lupo-scripts/sync_orchestrator_rules_to_db.php"
---
# file: TODO (root) — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/TODO

# TODO (root)

Pending tasks and next actions for Lupopedia. **v4.0.75** is **finalized and released** (tagged 4.0.75). **v4.0.76** is the active development version. Schema (lupo_projects, 12-table expansion), seed wiring, path normalization, and governance (root rules, IDE propagation, ONBOARDING, Safe DB Operations) are in place. Continued validation and regression work proceed under v4.0.76.

## Immediate (v4.0.76)

- [ ] **v4.0.76:** **Repeated** validation: **fresh Lupopedia install** end-to-end (install SQL + seeds including seed_projects.sql). Already exercised during the 4.0.75 cycle; ongoing regression confidence.
- [ ] **v4.0.76:** **Repeated** validation: **upgrade from Crafty Syntax 3.7.5** end-to-end (drop tables → load Crafty 3.7.5 → run install.php → verify). Already exercised during the 4.0.75 cycle; ongoing regression confidence.
- [ ] **v4.0.76:** Optional: final documentation review and cross-reference validation (see [V4_0_75_FINALIZATION_REPORT.md](lupo-docs/status/V4_0_75_FINALIZATION_REPORT.md)).

## Optional / follow-up

- [x] **v4.0.75:** Cursor rules propagation: `php lupo-scripts/propagate_agent_rules.php --target=cursor`; validation: `php lupo-tests/unit/cursor_rules_enforcement.php`. See `.cursor/README.md`.
- [x] **v4.0.75:** Antigravity / Kiro generic rules propagation gap closure: added `.kiro/rules/*.md` generation and struct array parity. Validation: `php lupo-tests/unit/kiro_rules_enforcement.php`.
- [ ] If using orchestrator rules table: run `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql` (includes `lupo_orchestrator_rules`), then `php lupo-scripts/sync_orchestrator_rules_to_db.php`.
- [x] Unify or document TOON output path (install SQL is authoritative; TOONs are derived; script now uniformly writes to lupo-database/lupopedia/toon/).

## By version

- **v4.0.73:** Consolidation release. Supported paths: fresh install, Crafty 3.7.5 → Lupopedia upgrade only.
- **v4.0.74:** Pushed to GitHub. Implemented: lupo_projects + seed_projects.sql (wired), 12-table expansion, path normalization (legacy/ exception), table count 159 (advisory doctrine), image paths (lupo-images/).
- **v4.0.75:** **Finalized and released.** Version bump, rules and governance updates, multi-agent propagation, schema-reference continuity, ONBOARDING, Safe DB Operations (DB009). Fresh install and Crafty 3.7.5 upgrade validation were performed during the 4.0.75 cycle; continued repeated validation is carried forward to v4.0.76. See [CHANGELOG.md](CHANGELOG.md) and [plan.md](plan.md).
- **v4.0.76:** Active development. Recurring install/upgrade validation and regression passes; optional doc polish.

## Doctrine

All work must follow rules in `lupo-rules/root/` and `.cursor/rules/`. Metadata blocks in README, CHANGELOG, and this file mirror `lupo_metadata` columns for future rehydration.
