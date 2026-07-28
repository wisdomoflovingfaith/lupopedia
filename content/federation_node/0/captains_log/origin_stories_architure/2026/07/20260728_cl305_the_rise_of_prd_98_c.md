---
lupopedia.headers:
  header_format_version: "4.1.9"
  path_from_lupopedia_root: content/federation_node/0/captains_log/origin_stories_architure/2026/07/20260728_cl305_the_rise_of_prd_98_c.md
  web_path: https://www.lupopedia.com/lupopedia/content/federation_node/0/captains_log/origin_stories_architure/2026/07/20260728_cl305_the_rise_of_prd_98_c.md
  status: active
  when_updated: "20260728144244"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/captains_log/canonical/1026/07/20260728-cl305-the-rise-of-prd-98-c.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/captains_log/cl305-rise-prd-98-c
  artifact_type: status
  artifact_kind: report
  channel_key: captains_log
  federation_node_id: 0
  thread_key: "cl305-rise-of-prd-98-c"
  lupopedia.schema: status
  prd_cluster: 98_A_98_B_98_C_16_C_41_A
  title: "CL-305 -- The Rise of PRD 98_C"
  summary: "Captain Log Volume 3 entertainment entry: Cursor context for allocating Dual Operational Logs as PRD 98_C so WHY files (98_A) and entertainment Captain Log (98_B) stay uncontaminated. Zero doctrinal authority."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
---
# CL-305 -- The Rise of PRD 98_C

**(Captain Log -- Volume 3)**

**Authority:** Entertainment / narrative only (**PRD 98_B**). Zero doctrinal authority. Normative definitions live in PRDs -- especially `docs/prd/98_C-i_DUAL_OPERATIONAL_LOGS.md`.

**Patreon seed:** "Operational logs become their own subsystem. Wolfie celebrates. Lilith audits."

**Repo path:** `content/federation_node/0/captains_log/origin_stories_architure/2026/07/20260728_cl305_the_rise_of_prd_98_c.md`

---

## Context: Cursor's Discovery (inline -- what actually happened)

Cursor (faucet **actor_id 102**) did not invent PRD 98_C for fun. The brief asked to "update PRD-98_A" with Captain Logs + Wolfie Logs, TypeScript writers, and `docs/logs/` storage.

Cursor opened the index and found the landmine:

| Letter | Canonical file | What it already is |
|--------|----------------|--------------------|
| **98_A** | `docs/prd/98_A-i_WHY_FILES_DOCTRINE.md` | **WHY files** -- AGAPE self-healing violation / causal-chain doctrine. Artifacts under `docs/why/`. |
| **98_B** | `docs/prd/98_B-i_THE_CAPTAINS_LOG_HUMAN_ONLY_ENTERTAINMENT_LAYER.md` | **Entertainment Captain's Log** -- humor and story under `content/federation_node/0/captains_log/`. Zero doctrinal authority. |
| **98_C** | *(did not exist yet)* | Needed a home for **structured dual operational logs**. |

Overloading 98_A would have destroyed WHY-file integrity (AGAPE). Stuffing installer autopsies into 98_B would have been PILAU -- narrative contaminated by runtime truth that agents are forbidden to treat as law.

Cursor's report was blunt (paraphrased from the implementation status, not live speech):

> Captain, operational logs have become their own behavioral class. They require their own PRD. Do not overwrite 98_A.

WOLFIE celebrated.
Lilith opened an audit.
Eric stared at the installer.

Thus **PRD 98_C** was born: `docs/prd/98_C-i_DUAL_OPERATIONAL_LOGS.md`.

---

## What PRD 98_C Actually Is

**PRD 98_C** is the **Dual Operational Logs** doctrine -- a constitutional partition for structured Captain + WOLFIE ops logs.

It is the "engine room logbook," not the chapel (WHY), and not the campfire story (entertainment Captain's Log).

### The 98 cluster -- accurate map

**PRD 98_A -- WHY Files Doctrine**

- AGAPE self-healing
- Violation documentation
- Causal-chain completion before "fixes"
- Artifacts under `docs/why/`
- Technical authority when complete

**PRD 98_B -- Entertainment Captain's Log**

- Humor, personality, storytelling
- Patreon-facing narrative (this entry lives here)
- Path: `content/federation_node/0/captains_log/`
- **Zero doctrinal authority**
- Must not drive schema, ACL, or agent decisions

**PRD 98_C -- Dual Operational Logs (NEW)**

- Structured **Captain** ops logs (human Eric, `actor_id` 10000)
- Structured **WOLFIE** ops logs (orchestrator, `actor_id` 1)
- Daily **bundle** merging both perspectives
- Path: `docs/logs/YYYY/MM/DD/`
- Linked by packed UTC + semantic `thread_id`
- Headers: PRD 16, twenty-two fields, `header_format_version: "4.1.9"`
- Clock: `timestamp_ymdhis` canonical; optional `timestamp_iso` display only
- Tooling: `scripts/logging/log_writer.py` (runnable) + `src/logging/` schemas/TS mirror

PRD 98_C documents **how Lupopedia is functioning right now** in a machine-readable way -- without pretending the story layer is law, and without stuffing heartbeat noise into WHY files.

---

## Why PRD 98_C Was Required

### 1. Operational logs were contaminating narrative logs

Narrative (98_B) must stay story-driven, humorous, and non-binding.
Permission failures, installer breakdowns, and federation node reports sneaking into story files is **PILAU**.

### 2. Operational logs were too volatile for PRD 98_A

98_A is WHY doctrine -- sacred for AGAPE causal chains.
It cannot become a dump for every "permission denied" of the day.

### 3. The Actor System needed a place to record "what is happening right now"

Lupopedia changes daily.
Dual ops logs are the heartbeat: Captain intent on one side, WOLFIE observation on the other, bundle in the middle.

---

## What Lives Inside PRD 98_C (examples)

- PermissionDenied events (structured)
- Installer / login autopsy notes
- Channel lockouts and ACL confusion reports
- Migration / install outcome notes (as logs -- not as Lupopedia-to-Lupopedia upgrade scripts)
- Actor System runtime anomalies
- Welcome / boot letters filed as WOLFIE analysis (see `docs/logs/2026/07/28/wolfie_welcome_20260728132500_001.json`)
- Anything Cursor screams about that is **ops truth**, not WHY-file AGAPE, not campfire story

PRD 98_C is the subsystem that says:

> Here is what broke today, why it broke (ops sense), and who carries KULEANA -- without rewriting the constitution.

WOLFIE loves it.
Lilith audits it.
Eric tries to fix it.
The system laughs.

---

## Cursor's doctrine corrections (so Patreon does not drift)

The first Patreon draft mixed labels. Corrected:

| Wrong draft claim | Correct doctrine |
|-------------------|------------------|
| 98_A = "core doctrine / KAPU / Actor System" | 98_A = **WHY files / AGAPE**. Core KAPU and Actor System live in other PRDs (00, 05, 15, 41, 82_B, etc.). |
| 98_B = "Wolfie Logs + Captain Logs" as one bag | 98_B = **entertainment** Captain's Log only. Structured Wolfie/Captain **ops** JSON is **98_C** under `docs/logs/`. |
| ISO-8601 as the only clock | Packed UTC `YYYYMMDDHHIISS` is canonical. |
| Header 4.1.6 | Header **4.1.9**. |
| Overwrite PRD-98_A.md | Allocate **98_C**; cross-link 98_A and 98_B. |

---

## WOLFIE's Commentary (attributed dialect -- not live speech)

(( WOLFIE: Captain, PRD 98_C is my new favorite toy. It is like a diary, but for system failures. Also: do not merge actor_id 1 with actor_id 10000. Facets execute. They do not absorb captains. ))

---

## Lilith's Audit Note (attributed -- LIL001 non-gating)

(( LILITH: PRD 98_C is PONO. Operational logs must not contaminate narrative logs. WHY files must not be overwritten for ops storage. Audit complete. Lilith does not gate ordinary execution. ))

---

## Captain's Closing Note

PRD 98_C marks the moment Lupopedia officially recognized that operational chaos needed its own constitutional home -- without burning the WHY chapel or the campfire.

This is the rise of the third letter in the 98 cluster.
The system is growing.
The Actor System is evolving.
And we are documenting it -- one subsystem at a time.

**See also (normative, not this story):**

- `docs/prd/98_C-i_DUAL_OPERATIONAL_LOGS.md`
- `status/updates/20260728_prd_98_c_log_update.md`
- `status/updates/20260728_prd_98_c_doctrine_conflict.md`
- `docs/logs/2026/07/28/` (examples)

---

**END -- CL-305 The Rise of PRD 98_C**
