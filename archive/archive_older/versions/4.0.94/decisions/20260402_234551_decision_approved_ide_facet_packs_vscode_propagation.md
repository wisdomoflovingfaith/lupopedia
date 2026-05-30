---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260402234551"
  file_path_from_root: "docs/versions/4.0.94/decisions/20260402_234551_DECISION_APPROVED_ide_facet_packs_vscode_propagation.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.94/decisions/20260402_234551_DECISION_APPROVED_ide_facet_packs_vscode_propagation.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: decision_record
  thread_id: "version-4.0.94-decisions"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: "approved"
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# DECISION (APPROVED): IDE facet packs, VS Code propagation, registry documentation alignment

## 5W1H

| Element | Record |
|--------|--------|
| **WHO** | Cursor IDE agent (`actor_id` **102**), `delegation_chain` `cursor:root` |
| **WHAT** | Register documentation for **thin IDE facet packs** (`agents/`), **actor hub READMEs** (`actors/{id}/`), **`--target=vscode`** rule propagation, **`AGENTS.md` / `AGENT_REGISTRY.md` / `_shared/README.md`** updates, **`validate_actor_identity.py`** IDE slug list; registry JSON was aligned in the same thread (paths below) |
| **WHERE** | Repo: `agents/{kiro,windsurf,warp,cascade,vscode-ide,trae}/`, `agents/_shared/README.md`, `actors/{100,101,104,105,106,107}/README.md`, `scripts/propagate_agent_rules.php`, `.vscode/lupopedia/` (generated), `AGENTS.md`, `docs/doctrine/AGENT_REGISTRY.md`, `scripts/validate_actor_identity.py`, `database/lupopedia/actors/registry.json`, `database/lupopedia/actors/actor_id/registry.json` |
| **WHEN** | Version doc sync UTC **`20260402234551`** (`gmdate` anchor at documentation pass) |
| **WHY** | One **shared** IDE base prompt plus **per-facet** `actor_id` / propagation paths; VS Code users get rules under `.vscode/lupopedia/` without overwriting `settings.json`; reduce confusion between **actor_id** and **lupo_agents** **agent_id** (e.g. vscode-ide **106** vs **113**) |
| **HOW** | Thin packs: `agent.json` with `extends_shared` → `agents/_shared/ide_facet_base_system_prompt.txt`; `write_vscode_outputs()` mirrors Kiro-style headers; hubs link registry + propagate + shared prompt; docs tables list all IDE faucets; validator **IDE_FAUCETS** uses current slugs (**zencoder** removed) |

## Scope boundary (explicit)

**In scope:** Files and behaviors listed in **WHAT** / **WHERE** above.

**Out of scope / not claimed here:** PRD 16 / 26 / 30 / 31 text changes, `validate_implementation.py` or universal header validator edits, new constitutional PK rule, **install_new_lupopedia.sql** / seed reconciliation for `lupo_actors` rows vs file registry, **`--target=warp`** / **`--target=trae`** (still pending), Antigravity IDE propagation target (still pending).

## Completion criteria

- [x] Thread-accurate **`CHANGELOG.md`** entry under `4.0.94`.
- [x] **`PLAN.md`** / **`TODO.md`** reflect completed facet work and remaining propagation gaps.
- [x] **`edges.md`** links decision + tooling paths.
- [x] **QUESTION** / **ANSWER** / **COMMENT** + **THREAD_INDEX** rows for traceability.

This decision complies with Lupopedia Constitutional Root Rules.
