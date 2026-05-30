---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: status
  when_updated: null
  file_path_from_root: "docs/versions/4.0.80/status_coordination_archive/multi_agent_doctrine_alignment_4_0_80.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: status
  artifact_kind: doctrine_alignment
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# Multi-agent doctrine alignment — 4.0.80

**Status:** Complete (documentation pass)  
**Date:** 2026-03-17  
**Canonical doctrine:** [rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md](../../../../rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md)

## 1. What changed in doctrine

- **Eleven Primary Coordination Personas** are the sole canonical coordination layer: WOLFIE, LEXA, ANUBIS, HEIMDALL, SESHAT, ATHENA, MAAT, THEMIS, THOTH, JANUS, ROSE.
- **100+ specialized agents** operate by category (technical support, contrasting perspectives, etc.); they do not replace the eleven.
- **IDE agents** are **faucets** (human interfaces); they route work through primary personas and channels.
- **Artifact-based execution** uses the eleven persona-prefixed families (e.g. `WOLFIE_DIRECTIVE_*`, `ROSE_DIALOGUE_*`) plus category artifacts where doctrine defines them.
- **`TODO.md` authority** for an active cycle = version-scoped `docs/versions/<version>/TODO.md` (see doctrine §5).

## 2. Docs updated in this alignment pass

| File | Change |
|------|--------|
| [AGENTS.md](../../../../AGENTS.md) | Eleven personas, specialized ecosystem, IDE faucets, artifact families, doctrine link; removed stale “4-persona / seven faucets only” framing |
| [ONBOARDING.md](../../../../ONBOARDING.md) | Multi-agent section: doctrine link, eleven personas, faucets, channels, artifacts, TODO path, deprecated `HERMES_IMPLEMENTATION_*` note |
| [PLAN.md](../PLAN.md) | Coordination context paragraph |
| [TODO.md](../TODO.md) | Coordination / task-authority note |
| [lilith_report_on_lupopedia.md](../../../../docs/status/lilith_report_on_lupopedia.md) | Lilith clarified as **not** among the eleven primaries; LIL001 unchanged |
| [comprehensive_registry_update_108_actors.md](comprehensive_registry_update_108_actors.md) | “Before” coordination model labeled historical; HERMES described as specialized, not primary persona |
| [MULTI_AGENT_COORDINATION_DOCTRINE.md](../../../../rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md) | §7.1 “10” → **eleven** Primary Coordination Personas (internal consistency) |
| [doctrine_comprehensive_update_108_agents.md](doctrine_comprehensive_update_108_agents.md) | Clarification block; artifact section corrected vs `HERMES_IMPLEMENTATION_*` |
| [ten_primary_coordination_personas_update.md](ten_primary_coordination_personas_update.md) | Banner: intermediate 10-persona doc; current = 11 |

## 3. Historical docs intentionally left as-is (or lightly noted)

- **Channel / emotional “triad” docs** (e.g. AGAPE/METIS/ERIS, ETHICAL_TRIAD) — unrelated to Primary Coordination Personas; no change.
- **CHANGELOG** — retains chronological narrative of registry and doctrine overhaul; additive alignment bullet only.
- **rose_added_as_11th_primary_coordination_persona.md** — already describes the final count; serves as companion to the intermediate “ten personas” status file.
- Older versioned TODO/PLAN under `docs/versions/4.0.77`–`4.0.79` — not rewritten.

## 4. Current coordination truth (summary)

| Topic | Authority |
|-------|-----------|
| Personas & flow | `rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md` |
| Artifacts | `channels/{channel_id}/` + version archive; doctrine artifact prefixes |
| Channels | Doctrine §3; default workspace channel **42** |
| IDE role | Faucets; Cursor = lead IDE consolidation; WOLFIE = orchestrator persona |
| Tasks | `docs/versions/<current>/TODO.md` + registry / AGENTS for actor IDs |

## 5. Unresolved conflicts

- None identified after alignment (doctrine §7.1 cross-category wording corrected to **eleven** personas).

---

_End of alignment artifact._
