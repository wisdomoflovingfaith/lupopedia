---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: decision
  when_updated: "20260406173021"
  file_path_from_root: "lupo-docs/versions/4.0.94/decisions/20260406_173021_DECISION_version_4_0_94_five_w_one_h_doc_sync_closeout.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/decisions/20260406_173021_DECISION_version_4_0_94_five_w_one_h_doc_sync_closeout.md"
  last_modified_utc: "20260406173021"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-doc-sync-closeout"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "decision"
  artifact_kind: "documentation"
  purpose: "Close-out receipt for 5W1H-directed updates to lupo-docs/versions/4.0.94/ without duplicating 4.0.95-line doctrine batch narrative"
  tags: ["decision", "4.0.94", "documentation", "5w1h", "cursor"]
lupopedia.footer:
  last_verified: "20260406173021"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: decisions/20260406_173021_DECISION_version_4_0_94_five_w_one_h_doc_sync_closeout.md — delegation: cursor:root

# DECISION: Version 4.0.94 folder — 5W1H documentation sync close-out

## Metadata

| Field | Value |
|-------|-------|
| **Decision ID** | 20260406-173021 |
| **Date** | 2026-04-06 |
| **Author** | CURSOR (facet `actor_id` 102) |
| **Status** | APPROVED (documentation receipt) |

## 5W1H

| Element | Answer |
|---------|--------|
| **WHO** | Cursor IDE agent (`actor_id` 102); human orchestrator per delegation |
| **WHAT** | Apply the 5W1H framework to **`lupo-docs/versions/4.0.94/`**: new decision + question + answer artifacts; refresh **`PLAN.md`**, **`TODO.md`**, **`edges.md`**, **`CHANGELOG.md`** headers; prepend **`CHANGELOG.md`** with an hourly epoch block; update **`THREAD_INDEX.md`** in **`decisions/`**, **`questions/`**, **`answers/`** |
| **WHERE** | `lupo-docs/versions/4.0.94/` (version-scoped documentation only) |
| **WHEN** | Receipt UTC **`20260406173021`** from **`python lupo-bin/tick.py`** (same batch for header/footer fields touched here) |
| **WHY** | Preserve decision history, align multi-agent version docs, and avoid silent drift between **`4.0.94`** (closed-period snapshot) and the active **`4.0.95`** documentation line |
| **HOW** | One decision file (this), one Q/A pair on where post-baseline doctrine narrative lives, **`edges.md`** outbound edges to **`4.0.95/CHANGELOG.md`**, **`FOR_CLAUDE_CODE_2026_04_06.md`**, and root **`CHANGELOG.md`** routing; **`CHANGELOG`** hourly section points readers to existing **## [4.0.94] - 2026-04-06** detail for PRD 16/26/30/31, validators, COUNTERMEASURE feedback, PK naming, and 5W1H-as-heuristic (no duplicate paste of that rollup) |

## Decision

**Treat `lupo-docs/versions/4.0.94/` as a frozen-period documentation snapshot.** New work that belongs to the **current** patch line (doctrine batches, agent sync outlines, expanded **`README`** sections) is **indexed** from here via **`edges.md`** and **`CHANGELOG`** cross-references to **`lupo-docs/versions/4.0.95/`** and **`FOR_CLAUDE_CODE_2026_04_06.md`**, not re-copied in full into **`4.0.94/CHANGELOG.md`** when the same facts already appear under **## [4.0.94] - 2026-04-06** or would duplicate the **4.0.95** line.

### Non-goals (this receipt)

- **No** new claims about PRD or validator edits without matching repo evidence in the cited paths.
- **No** replacement of the existing long-form **2026-04-06** subsection already in **`4.0.94/CHANGELOG.md`**; the hourly prepend is a **navigation and handoff** layer only.

## Related artifacts

| Artifact | Role |
|----------|------|
| [questions/20260406_173022_QUESTION_where_record_post_baseline_doctrine_batch.md](../questions/20260406_173022_QUESTION_where_record_post_baseline_doctrine_batch.md) | Scope question — where to record post-close-out doctrine batch |
| [answers/20260406_173022_ANSWER_record_under_4_0_95_changelog_and_for_claude.md](../answers/20260406_173022_ANSWER_record_under_4_0_95_changelog_and_for_claude.md) | Answer — **4.0.95** changelog + **`FOR_CLAUDE_CODE`** + root **`CHANGELOG`** pointer |
| [../CHANGELOG.md](../CHANGELOG.md) | Hourly epoch + **## [4.0.94] - 2026-04-06** narrative |
| [../edges.md](../edges.md) | Outbound edges to next-line docs |

---

This output complies with Lupopedia Constitutional Root Rules.
