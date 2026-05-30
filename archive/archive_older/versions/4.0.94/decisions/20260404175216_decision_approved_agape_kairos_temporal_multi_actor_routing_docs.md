---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260404175216"
  file_path_from_root: "docs/versions/4.0.94/decisions/20260404175216_DECISION_APPROVED_agape_kairos_temporal_multi_actor_routing_docs.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.94/decisions/20260404175216_DECISION_APPROVED_agape_kairos_temporal_multi_actor_routing_docs.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: decision
  thread_id: "version-4.0.94-decisions"
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
# file: DECISION agape_kairos_temporal_multi_actor_routing — delegation: cursor:root

# DECISION (APPROVED): AGAPE + KAIROS temporal discipline + multi-actor routing — documentation receipt

## 5W1H

| Element | Record |
|---------|--------|
| **WHO** | Cursor IDE agent (`actor_id` **102**), orchestration `cursor:root`. |
| **WHAT** | Document and tool changes **merged in this thread** only: (1) **AGAPE** as technical constitutional metric; (2) **PRD 37** temporal discipline + **`scaffold_implementation.py add-status`**; (3) **Multi-actor channel routing** — **`to_actor_id`** routing-only, full-thread context across **PRD 18 / 36 / 37 / 31 / 05**. |
| **WHERE** | **Constitution / doctrine:** `docs/prd/00_root_constitutional_system_requirements.md` §14.6; `docs/doctrine/AGAPE_DOCTRINE.md`; `rules/root/lilith-noninterference-doctrine.md`; `.cursor/rules/lilith-noninterference-doctrine.mdc`; `docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md`; `agents/agape/*`, `agents/rose/system_prompt.txt` (+ v1.0.0); `docs/prd/07_agents_faucets.md` (AGAPE row); scrub `SEMANTIC_SECURITY_CHECKLIST_4_0_30.md`, `WHAT_LUPOPEDIA_IS.md`, `VERSION_PLANS_3.0.82_3.0.88.md`. **KAIROS / scaffold:** `docs/prd/37_kairos_channel_memory_consolidation.md` §10–§10.6; `docs/doctrine/AGAPE_DOCTRINE.md` §1.3; `scripts/scaffold_implementation.py` (`add-status`); `docs/prd/31_implementation_folder_guidelines.md` (`add-status` + orchestration note); `docs/implementations/36_rose_multi_persona_synthetic_dialog/README.md` (edge). **Routing:** `docs/prd/18_channel_chat_display.md`, `36_rose_multi_persona_synthetic_dialog.md` §1.3, `37_…` §10.6, `31_…`, `05_…`. **Version folder (this file batch):** `docs/versions/4.0.94/` — `CHANGELOG`, `PLAN`, `TODO`, `edges`, `WHAT_TO_WORK_ON_NEXT_SESSION`, `decisions/` + `comments/` indexes. |
| **WHEN** | Evidence batch UTC **`20260404175216`** (`python bin/tick.py`). Prior in-thread stamps: **`20260404172442`**, **`20260404173921`**, **`20260404174956`** on respective PRD/doctrine edits. |
| **WHY** | **AGAPE:** Codify resilience / cooperation as **measurable** behavior; ban sentimental strings as acceptance criteria. **Temporal:** Stop filesystem order from masquerading as truth; index-first + edges. **Routing:** LILITH **Option 1** — single routing column (**`to_actor_id`** in DDL; synonym *said-to* in prose); channel + thread = read context; no `mention_actor_ids` column. |
| **HOW** | Markdown + YAML headers; `tick.py` UTC; `add-status` subcommand (legacy CLI preserved); **no** install SQL / `parent_dialog_message_id` added (documented as future only). |

## APPROVED scope (thread-verified)

- **AGAPE** — PRD 00 §14.6; `AGAPE_DOCTRINE.md`; LILITH + validator policy; ROSE synthetic metadata keys; agent pack updates.
- **KAIROS temporal** — PRD 37 §10.x; `add-status`; PRD 31 cross-links; PRD 37 §10.6 chat ingest rules.
- **Multi-actor routing** — PRDs **18, 36, 37, 31, 05** aligned to **`lupo_dialog_messages.to_actor_id`** TOON.

## WHAT NOT claimed (this thread)

- **No** PRD **16 / 26 / 30 / 31** create-reject-validator cycles from unrelated directives (see `161001` decision **WHAT NOT**).
- **No** automatic Python scan for AGAPE banned phrases in `validate_lupopedia_headers*.py` (policy text only unless a later task adds code).
- **No** runtime PHP changes for ROSE switchboard or KAIROS full-thread ingest beyond existing `KairosConsolidationService` behavior — **docs + scaffold only** here.

## Outcome

**APPROVED** as accurate **version-folder receipt** for **`docs/versions/4.0.94/`**. Follow-up work: **TODO.md** + **WHAT_TO_WORK_ON_NEXT_SESSION.md**.

This output complies with Lupopedia Constitutional Root Rules.
