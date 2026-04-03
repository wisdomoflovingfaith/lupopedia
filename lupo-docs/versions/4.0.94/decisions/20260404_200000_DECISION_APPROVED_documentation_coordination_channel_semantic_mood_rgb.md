---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260404200000"
  file_path_from_root: "lupo-docs/versions/4.0.94/decisions/20260404_200000_DECISION_APPROVED_documentation_coordination_channel_semantic_mood_rgb.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/decisions/20260404_200000_DECISION_APPROVED_documentation_coordination_channel_semantic_mood_rgb.md"
  last_modified_utc: "20260404200000"
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
  purpose: "Record APPROVED outcomes for 4.0.94 documentation coordination (thread filename doctrine, channels, Mood RGB thread, root README)"
  status: "approved"
  tags:
    - "4.0.94"
    - "decision"
    - "documentation"
    - "channels"
    - "mood_rgb"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/17_decisions_format.md"
      type: references
      weight: 1.0
      reason: "Thread filename pattern authority"
    - to: "lupo-docs/prd/29_project_structure.md"
      type: references
      weight: 1.0
      reason: "Channel filesystem strategy"
    - to: "lupo-channels/0/semantic/mood_rgb_system/README.md"
      type: references
      weight: 1.0
      reason: "Mood RGB canonical thread"
    - to: "lupo-docs/doctrine/MOOD_RGB_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Mood RGB summary doctrine"
lupopedia.footer:
  last_verified: "20260404200000"
  verified_by:
    identity_type: "actor"
    actor_id: 102
  orchestrator: "cursor:root"
---

# DECISION (APPROVED): 4.0.94 documentation coordination — channels, PRD 17, Mood RGB, root README

| Field | Value |
|-------|--------|
| **WHO** | Cursor IDE agent (actor_id 102), orchestrated per repo doctrine |
| **WHAT** | Align version docs, PRDs, rules, and channel on-disk layout with PRD 17/29 and active 4.0.94 |
| **WHERE** | `lupo-docs/`, `lupo-channels/`, `.cursorrules`, root `README.md`, `AGENTS.md`, `lupo-rules/root/` |
| **WHEN** | 2026-04-02 — 2026-04-04 UTC (this release line) |
| **WHY** | Single install path for “current” docs; avoid stale 4.0.93-only pointers; preserve Mood RGB and channel evidence |
| **HOW** | Authoritative thread filename section in PRD 17; `.cursorrules` §30; channel `semantic` + thread `mood_rgb_system/`; MOOD_RGB doctrine summary + archive edges in thread decisions; numeric `lupo-channels/42/` links retargeted to `lupo-channels_before_4_0_93/42/` where files live |

## Outcomes (APPROVED)

1. **PRD 17** — “Thread filename pattern (authoritative)” with per-folder `TYPE`/`STATUS`, `HHIISS`, optional `YYYYMMDDHHIISS` prefix; PRD 02/29 and 4.0.93 README cross-links updated.
2. **Channel filesystem** — `.cursorrules` §30; `CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md` §1.1; root `README` / `AGENTS.md` / `lupo-rules/root/README.md` / related doctrines describe active triple-key layout vs archive vs API `{id}`.
3. **Archive link fix** — Relative links that assumed live `lupo-channels/42/` now target **`lupo-channels_before_4_0_93/42/...`** where applicable.
4. **Channel `semantic` + thread `mood_rgb_system/`** — `THREAD_MANIFEST`, `README`, typed folders, two APPROVED decisions (evidence + color definitions); **`lupo-channels/channel_index.md`** row; **`MOOD_RGB_DOCTRINE.md`** shortened to summary with edges to thread; header `thread_id` **`mood-rgb-system`** (validator-safe) vs folder `mood_rgb_system`.
5. **Root `README.md`** — `lupopedia.init` and `lupopedia.edges` version paths **`4.0.94`**; footer **`last_verified`** 14-digit UTC; Semantic Monitoring Widget link to **`lupo-docs/prd/28_semantic_monitoring_widget.md`**.

## Scope limits

- Does **not** claim completion of PRD 30/31 rewrites, Softaculous certification, or product UI work — those remain in `PLAN.md` / `TODO.md`.
- Historical validator work (PRD 16, COUNTERMEASURE on 26/30/31) is **out of scope** for this decision unless separately recorded under 4.0.93 frozen docs.
