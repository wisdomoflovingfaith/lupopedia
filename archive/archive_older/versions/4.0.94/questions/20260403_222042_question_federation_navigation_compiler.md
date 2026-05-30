---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260403222527"
  file_path_from_root: "docs/versions/4.0.94/questions/20260403_222042_QUESTION_federation_navigation_compiler.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.94/questions/20260403_222042_QUESTION_federation_navigation_compiler.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: question
  artifact_kind: planning
  thread_id: "version-4-0-94-questions"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: "open"
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: QUESTION — federation navigation compiler — web_path: /docs/versions/4.0.94/questions/20260403_222042_QUESTION_federation_navigation_compiler.md

# QUESTION (OPEN): Navigation compiler from federation / path aggregates

**Status:** **OPEN** — awaiting **WOLFIE** product decision, **PRD 34** normative work, and **privacy / consent / opt-out** design. **Not blocking 4.0.x.**

## Context

**Doctrine-sourced narrative** (caveats in-file): **`SILENT_HARVEST_DOCTRINE.md`** discusses large historical install counts, active/reporting sites, and long-horizon path aggregates — **not** independently verified in this thread.

**Core question (paraphrase):** How should Lupopedia compile or expose **navigation suggestions** from that class of data (e.g. common entry paths, flows, referrers)?

## Options (non-binding — all require privacy gates first)

Every option below assumes **written privacy policy**, **consent model**, **opt-out mechanism**, and **WOLFIE** approval **before** any implementation.

1. **On demand** — query aggregates when a domain/node requests insight.
2. **Pre-compile** — batch job for selected domains; store compiled structures.
3. **Hybrid** — hot set pre-compiled, long tail on demand.

**Cross-cutting (must be designed, not bolted on):**

- **Privacy policy** — what aggregate or derived data may be shared or queried, and under what rules.
- **Consent** — whether site operators / nodes opted in to contributing or receiving compiler output.
- **Opt-out** — whether data can be removed or excluded from aggregates and suggestions.
- **Attribution / ownership** — whose behavioral or path data the suggestions represent; avoid implicit “public domain” assumptions.
- **Rate limiting / abuse** — protect compiler APIs or batch jobs from overload or scraping-style misuse.

## Gates before implementation

Do **not** ship compiler product behavior until:

1. **`docs/prd/34_federation_node_semantic_network.md`** (or successor) carries the **normative** federation + data-use story for this feature (draft today — **approval** is the gate).
2. A **privacy policy** exists that covers this use of aggregates (what is stored, shared, queried).
3. **Consent** and **opt-out** are defined and enforceable in product terms.
4. **WOLFIE** signs off on the above (orchestrator decision).

**Constitutional note:** No install SQL, migrations, or schema work is implied by this QUESTION until those gates close.

## LILITH audit record (2026-04-03)

| Field | Value |
|-------|--------|
| **Auditor** | LILITH (`actor_id` **2**) |
| **Accuracy score** | **100/100** |
| **Constitutional violations** | None |
| **Security / privacy** | Privacy implications **must** be resolved before implementation |
| **Bias detected** | No |
| **Verdict** | Valid **OPEN** question; **defer** normative answers to **PRD 34**; **not blocking 4.0.x** |

**LILITH recommendations (applied in this file):** record **privacy policy**, **consent**, **opt-out**, **attribution**, **rate limiting**; keep **WOLFIE** decision as prerequisite; treat **PRD 34** as the home for specification work.

*File body updated from audit report; header UTC **`20260403222527`** (`python bin/tick.py`).*

## IDE note (original)

Treat as **future product**; requires schema/API ownership only **after** policy and PRD gates. **No** install SQL or migration claimed in this question.

This output complies with Lupopedia Constitutional Root Rules.
