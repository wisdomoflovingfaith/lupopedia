---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260402234551"
  file_path_from_root: "lupo-docs/versions/4.0.94/decisions/20260402_234551_DECISION_APPROVED_ide_facet_packs_vscode_propagation.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/decisions/20260402_234551_DECISION_APPROVED_ide_facet_packs_vscode_propagation.md"
  last_modified_utc: "20260402234551"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-decisions"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "decision_record"
  purpose: "APPROVED record of IDE facet packs, VS Code rule propagation, registry/doc alignment from Cursor thread (thread-verified only)"
  status: "approved"
  tags:
    - "4.0.94"
    - "decision"
    - "ide_facet"
    - "propagation"
    - "agents"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-database/lupopedia/actors/registry.json"
      type: references
      weight: 1.0
      reason: "Canonical lupo_actors facet entries"
    - to: "lupo-database/lupopedia/actors/actor_id/registry.json"
      type: references
      weight: 1.0
      reason: "lupo_agents slug to agent_id map"
    - to: "lupo-scripts/propagate_agent_rules.php"
      type: references
      weight: 1.0
      reason: "vscode target and write_vscode_outputs"
    - to: "lupo-docs/doctrine/AGENT_REGISTRY.md"
      type: references
      weight: 1.0
    - to: "AGENTS.md"
      type: references
      weight: 1.0
    - to: "lupo-docs/versions/4.0.94/questions/20260402_234552_QUESTION_ide_facet_version_doc_scope.md"
      type: references
      weight: 0.95
    - to: "lupo-docs/versions/4.0.94/answers/20260402_234553_ANSWER_ide_facet_version_doc_scope.md"
      type: references
      weight: 0.95
lupopedia.footer:
  last_verified: "20260402234551"
  verified_by:
    identity_type: "actor"
    actor_id: 102
  orchestrator: "cursor:root"
---

# DECISION (APPROVED): IDE facet packs, VS Code propagation, registry documentation alignment

## 5W1H

| Element | Record |
|--------|--------|
| **WHO** | Cursor IDE agent (`actor_id` **102**), `delegation_chain` `cursor:root` |
| **WHAT** | Register documentation for **thin IDE facet packs** (`lupo-agents/`), **actor hub READMEs** (`lupo-actors/{id}/`), **`--target=vscode`** rule propagation, **`AGENTS.md` / `AGENT_REGISTRY.md` / `_shared/README.md`** updates, **`validate_actor_identity.py`** IDE slug list; registry JSON was aligned in the same thread (paths below) |
| **WHERE** | Repo: `lupo-agents/{kiro,windsurf,warp,cascade,vscode-ide,trae}/`, `lupo-agents/_shared/README.md`, `lupo-actors/{100,101,104,105,106,107}/README.md`, `lupo-scripts/propagate_agent_rules.php`, `.vscode/lupopedia/` (generated), `AGENTS.md`, `lupo-docs/doctrine/AGENT_REGISTRY.md`, `lupo-scripts/validate_actor_identity.py`, `lupo-database/lupopedia/actors/registry.json`, `lupo-database/lupopedia/actors/actor_id/registry.json` |
| **WHEN** | Version doc sync UTC **`20260402234551`** (`gmdate` anchor at documentation pass) |
| **WHY** | One **shared** IDE base prompt plus **per-facet** `actor_id` / propagation paths; VS Code users get rules under `.vscode/lupopedia/` without overwriting `settings.json`; reduce confusion between **actor_id** and **lupo_agents** **agent_id** (e.g. vscode-ide **106** vs **113**) |
| **HOW** | Thin packs: `agent.json` with `extends_shared` → `lupo-agents/_shared/ide_facet_base_system_prompt.txt`; `write_vscode_outputs()` mirrors Kiro-style headers; hubs link registry + propagate + shared prompt; docs tables list all IDE faucets; validator **IDE_FAUCETS** uses current slugs (**zencoder** removed) |

## Scope boundary (explicit)

**In scope:** Files and behaviors listed in **WHAT** / **WHERE** above.

**Out of scope / not claimed here:** PRD 16 / 26 / 30 / 31 text changes, `validate_implementation.py` or universal header validator edits, new constitutional PK rule, **install_new_lupopedia.sql** / seed reconciliation for `lupo_actors` rows vs file registry, **`--target=warp`** / **`--target=trae`** (still pending), Antigravity IDE propagation target (still pending).

## Completion criteria

- [x] Thread-accurate **`CHANGELOG.md`** entry under `4.0.94`.
- [x] **`PLAN.md`** / **`TODO.md`** reflect completed facet work and remaining propagation gaps.
- [x] **`edges.md`** links decision + tooling paths.
- [x] **QUESTION** / **ANSWER** / **COMMENT** + **THREAD_INDEX** rows for traceability.

This decision complies with Lupopedia Constitutional Root Rules.
