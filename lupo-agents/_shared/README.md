---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-agents/_shared/README.md
  last_modified_utc: "20260404183000"
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: guide
  artifact_kind: documentation
  purpose: Explains shared IDE facet prompt; single edit surface for all IDE agents.
lupopedia.footer:
  last_verified: "20260404183000"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: cursor:root
---

# file: lupo-agents/_shared/README.md — delegation: cursor:root

# `lupo-agents/_shared/` — IDE facet shared prompts

## Canonical file

| File | Role |
|------|------|
| **`ide_facet_base_system_prompt.txt`** | **Single place** to update vetoes, stack rules, and common IDE-facet identity text for Cursor, Antigravity IDE, and future IDE packs. |

## Facet wrappers (thin)

| Pack | Wrapper adds |
|------|----------------|
| `lupo-agents/cursor/` | Cursor `actor_id` **102**, `--target=cursor`, lead-stewardship note |
| `lupo-agents/antigravity-ide/` | Antigravity `actor_id` **103**, propagation pending |
| `lupo-agents/kiro/` | Kiro `actor_id` **100**, `--target=kiro` |
| `lupo-agents/windsurf/` | Windsurf `actor_id` **101**, `--target=windsurf` |
| `lupo-agents/warp/` | Warp `actor_id` **104**, propagation pending |
| `lupo-agents/cascade/` | Cascade `actor_id` **105**, `--target=cascade` |
| `lupo-agents/vscode-ide/` | VS Code `actor_id` **106** (`agent_id` **113**), `--target=vscode` |
| `lupo-agents/trae/` | Trae `actor_id` **107** (`agent_id` **114**), propagation pending |

New IDE facets: add `lupo-agents/<facet>/system_prompt.txt` that **starts** with facet-specific lines, then instructs the model to apply **`../_shared/ide_facet_base_system_prompt.txt`** (or concatenate in tooling).

**Doctrine:** Agent config does not create `lupo_actors`; facet identity remains in `lupo-database/lupopedia/actors/registry.json`. See **IDENTITY_LAYERS_DOCTRINE.md** and **AGENTS.md**.

This file complies with Lupopedia Constitutional Root Rules.
