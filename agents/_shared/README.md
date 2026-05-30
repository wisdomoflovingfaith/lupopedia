---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: agents/_shared/README.md
  web_path: https://www.lupopedia.com/lupopedia/agents/_shared/README.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: guide
  artifact_kind: documentation
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: null
  title: null
  summary: null
---

# file: agents/_shared/README.md — delegation: cursor:root

# `agents/_shared/` — IDE facet shared prompts

## Canonical file

| File | Role |
|------|------|
| **`ide_facet_base_system_prompt.txt`** | **Single place** to update vetoes, stack rules, and common IDE-facet identity text for Cursor, Antigravity IDE, and future IDE packs. |

## Facet wrappers (thin)

| Pack | Wrapper adds |
|------|----------------|
| `agents/cursor/` | Cursor `actor_id` **102**, `--target=cursor`, lead-stewardship note |
| `agents/antigravity-ide/` | Antigravity `actor_id` **103**, propagation pending |
| `agents/kiro/` | Kiro `actor_id` **100**, `--target=kiro` |
| `agents/windsurf/` | Windsurf `actor_id` **101**, `--target=windsurf` |
| `agents/warp/` | Warp `actor_id` **104**, propagation pending |
| `agents/cascade/` | Cascade `actor_id` **105**, `--target=cascade` |
| `agents/vscode-ide/` | VS Code `actor_id` **106** (`agent_id` **113**), `--target=vscode` |
| `agents/trae/` | Trae `actor_id` **107** (`agent_id` **114**), propagation pending |

New IDE facets: add `agents/<facet>/system_prompt.txt` that **starts** with facet-specific lines, then instructs the model to apply **`../_shared/ide_facet_base_system_prompt.txt`** (or concatenate in tooling).

**Doctrine:** Agent config does not create `lupo_actors`; facet identity remains in `database/lupopedia/actors/registry.json`. See **IDENTITY_LAYERS_DOCTRINE.md** and **AGENTS.md**.

This file complies with Lupopedia Constitutional Root Rules.
