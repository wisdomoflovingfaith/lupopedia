---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260404200000"
  file_path_from_root: "docs/versions/4.0.94/decisions/20260404_200000_DECISION_APPROVED_documentation_coordination_channel_semantic_mood_vector.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.94/decisions/20260404_200000_DECISION_APPROVED_documentation_coordination_channel_semantic_mood_vector.md"
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
# DECISION (APPROVED): 4.0.94 documentation coordination — channels, PRD 17, Mood Vector, root README

| Field | Value |
|-------|--------|
| **WHO** | Cursor IDE agent (actor_id 102), orchestrated per repo doctrine |
| **WHAT** | Align version docs, PRDs, rules, and channel on-disk layout with PRD 17/29 and active 4.0.94 |
| **WHERE** | `docs/`, `channels/`, `.cursorrules`, root `README.md`, `AGENTS.md`, `rules/root/` |
| **WHEN** | 2026-04-02 — 2026-04-04 UTC (this release line) |
| **WHY** | Single install path for “current” docs; avoid stale 4.0.93-only pointers; preserve Mood Vector and channel evidence |
| **HOW** | Authoritative thread filename section in PRD 17; `.cursorrules` §30; channel `semantic` + thread `mood_vector_system/`; MOOD_VECTOR doctrine summary + archive edges in thread decisions; numeric `channels/42/` links retargeted to `channels_before_4_0_93/42/` where files live |

## Outcomes (APPROVED)

1. **PRD 17** — “Thread filename pattern (authoritative)” with per-folder `TYPE`/`STATUS`, `HHIISS`, optional `YYYYMMDDHHIISS` prefix; PRD 02/29 and 4.0.93 README cross-links updated.
2. **Channel filesystem** — `.cursorrules` §30; `CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md` §1.1; root `README` / `AGENTS.md` / `rules/root/README.md` / related doctrines describe active triple-key layout vs archive vs API `{id}`.
3. **Archive link fix** — Relative links that assumed live `channels/42/` now target **`channels_before_4_0_93/42/...`** where applicable.
4. **Channel `semantic` + thread `mood_vector_system/`** — `THREAD_MANIFEST`, `README`, typed folders, two APPROVED decisions (evidence + color definitions); **`channels/channel_index.md`** row; **`MOOD_VECTOR_DOCTRINE.md`** shortened to summary with edges to thread; header `thread_id` **`mood-vector-system`** (validator-safe) vs folder `mood_vector_system`.
5. **Root `README.md`** — `lupopedia.init` and `lupopedia.edges` version paths **`4.0.94`**; footer **`last_verified`** 14-digit UTC; Semantic Monitoring Widget link to **`docs/prd/28_semantic_monitoring_widget.md`**.

## Scope limits

- Does **not** claim completion of PRD 30/31 rewrites, Softaculous certification, or product UI work — those remain in `PLAN.md` / `TODO.md`.
- Historical validator work (PRD 16, COUNTERMEASURE on 26/30/31) is **out of scope** for this decision unless separately recorded under 4.0.93 frozen docs.
