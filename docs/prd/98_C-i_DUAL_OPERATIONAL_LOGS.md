---
lupopedia.headers:
  header_format_version: "4.1.9"
  path_from_lupopedia_root: docs/prd/98_C-i_DUAL_OPERATIONAL_LOGS.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/98_C-i_DUAL_OPERATIONAL_LOGS.md
  status: active
  when_updated: "20260728131310"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/prd/canonical/1026/07/98-c-dual-operational-logs.toon
  atoms_toon: null
  transcript_jsonl: 0/prd/98-c-dual-operational-logs
  artifact_type: prd
  artifact_kind: requirements
  channel_key: prd
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: prd
  prd_cluster: 00_A_16_C_41_A_98_A_98_B_98_C
  title: "PRD 98_C: Dual Operational Logs (Captain + WOLFIE)"
  summary: "Structured Captain (human) and WOLFIE (paired AI) operational logs under docs/logs/, linked by thread_id and packed UTC, with daily bundle merge. Distinct from WHY files (98_A) and entertainment Captain Log (98_B)."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
---
# PRD 98_C: Dual Operational Logs (Captain + WOLFIE)

## 0. Numbering note (KAPU)

**PRD 98_A remains WHY Files Doctrine.** This dual-log subsystem is **PRD 98_C**.

Do **not** overwrite `docs/prd/98_A-i_WHY_FILES_DOCTRINE.md`.
Do **not** invent `/prd/PRD-98_A.md` as a second canonical path.

| PRD | Topic | Path |
|-----|-------|------|
| **98_A** | WHY files (AGAPE self-healing) | `docs/prd/98_A-i_WHY_FILES_DOCTRINE.md` |
| **98_B** | Captain's Log entertainment (zero doctrinal authority) | `docs/prd/98_B-i_THE_CAPTAINS_LOG_HUMAN_ONLY_ENTERTAINMENT_LAYER.md` |
| **98_C** | Dual operational logs (this file) | `docs/prd/98_C-i_DUAL_OPERATIONAL_LOGS.md` |

---

## 1. Purpose

Define a dual-perspective operational logging subsystem:

1. **Captain logs** -- human operator Eric (ALII, `actor_id` 10000)
2. **WOLFIE logs** -- paired AI orchestrator (`actor_id` 1)

Requirements:

- Structured JSON entries for both perspectives
- Auto-generated Lupopedia constitutional headers (22 fields, `header_format_version: "4.1.9"`)
- Storage under `docs/logs/YYYY/MM/DD/`
- Link Captain <-> WOLFIE via packed UTC + semantic `thread_id`
- Daily bundle artifact merging both perspectives
- Tooling schemas + writers (Python runnable; TypeScript tooling mirror)

---

## 2. Problem (KAPAKAI)

Without dual operational logs:

- Human intent and AI observation drift into chat or entertainment narrative
- No deterministic pairing of Captain decisions to WOLFIE analysis
- WHY files (98_A) are overloaded for non-violation operational notes
- Entertainment Captain's Log (98_B) is zero-authority and must not drive system behavior
- Agents invent channel/thread context without an auditable daily artifact

---

## 3. Desired State (PONO)

- Captain writes structured intent/decision/reasoning entries
- WOLFIE writes structured observation/analysis/recommendation entries
- Both share a `thread_id` and packed UTC clock
- A daily `bundle.json` merges entries and optional semantic links
- Headers are complete 22-field PRD 16 envelopes
- Entertainment narrative stays in `content/federation_node/0/captains_log/` (98_B)
- Violation / causal-chain artifacts stay in `docs/why/` (98_A)

---

## 4. Requirements

### MUST

1. Store operational logs only under `docs/logs/YYYY/MM/DD/`.
2. Use packed UTC `YYYYMMDDHHIISS` as the **canonical** timestamp field (`timestamp_ymdhis`).
3. May include optional display field `timestamp_iso` (ISO-8601 UTC) derived from packed UTC -- never as the sole clock.
4. Embed a full 22-field `header` object matching PRD 16 key order.
5. Use `header_format_version: "4.1.9"` (not 4.1.6).
6. Link pairs via `thread_id` (semantic string, e.g. `semantic-thread-001`).
7. Generate `bundle.json` per calendar day (UTC date from packed timestamps).
8. Keep identity unmerged: Captain = human 10000; WOLFIE = actor 1; faucet IDs never absorb either.

### MUST NOT

1. Replace or redefine WHY files (98_A).
2. Grant operational logs entertainment Captain's Log isolation exemptions incorrectly -- operational logs **may** be read by agents for work continuity; entertainment logs under 98_B remain restricted.
3. Use DATETIME / epoch seconds / local timezone math for canonical storage.
4. Require LILITH approval before every write (LIL001 -- non-interfering audit).
5. Treat WOLF markup as permission (PRD 39).

---

## 5. Architecture

```text
Human Captain (10000)          WOLFIE (1)
        |                            |
        v                            v
 writeCaptainLog()            writeWolfieLog()
        |                            |
        +---- shared thread_id ------+
        |                            |
        v                            v
 docs/logs/YYYY/MM/DD/
   captain_<ymdhis>_<seq>.json
   wolfie_<ymdhis>_<seq>.json
        \            /
         v          v
      generateDailyBundle()
               |
               v
         bundle.json
```

**Tooling paths:**

| Surface | Path |
|---------|------|
| JSON schemas | `src/logging/*_schema.json` |
| TypeScript mirror | `src/logging/header_generator.ts`, `src/logging/log_writer.ts` |
| Runnable Python | `scripts/logging/header_generator.py`, `scripts/logging/log_writer.py` |
| Examples | `docs/logs/2026/07/28/` |

---

## 6. Data Models

Canonical clock fields use packed UTC. Optional ISO is display-only.

### 6.1 Captain Log

```json
{
  "header": {},
  "type": "captain_log",
  "log_id": "captain_20260728131310_001",
  "captain_id": "Eric",
  "actor_id": 10000,
  "timestamp_ymdhis": "20260728131310",
  "timestamp_iso": "2026-07-28T13:13:10Z",
  "thread_id": "semantic-thread-001",
  "intent": "string",
  "context": "string",
  "decision": "string",
  "reasoning": "string",
  "emotional_state": "string",
  "next_actions": ["string"]
}
```

### 6.2 WOLFIE Log

```json
{
  "header": {},
  "type": "wolfie_log",
  "log_id": "wolfie_20260728131310_001",
  "wolfie_id": "Wolfie",
  "actor_id": 1,
  "timestamp_ymdhis": "20260728131310",
  "timestamp_iso": "2026-07-28T13:13:10Z",
  "thread_id": "semantic-thread-001",
  "observation": "string",
  "state": "string",
  "analysis": "string",
  "recommendations": ["string"],
  "alerts": ["string"]
}
```

### 6.3 Daily Bundle

```json
{
  "header": {},
  "bundle_date": "2026-07-28",
  "thread_id": "semantic-thread-001",
  "captain_logs": [],
  "wolfie_logs": [],
  "semantic_links": [
    {
      "captain_log_id": "string",
      "wolfie_log_id": "string",
      "relationship": "supporting"
    }
  ],
  "summary": "string"
}
```

`relationship` enum: `supporting` | `conflicting` | `clarifying`.

### 6.4 Header object (22 fields, PRD 16 order)

Exactly these keys, in order: `header_format_version`, `path_from_lupopedia_root`, `web_path`, `status`, `when_updated`, `trust_tier`, `questions_toon`, `memory_toon`, `atoms_toon`, `transcript_jsonl`, `artifact_type`, `artifact_kind`, `channel_key`, `federation_node_id`, `thread_key`, `lupopedia.schema`, `prd_cluster`, `title`, `summary`, `edges_toon`, `channel_index`, `source_timestamp`.

---

## 7. Examples

See:

- `docs/logs/2026/07/28/captain_20260728131310_001.json`
- `docs/logs/2026/07/28/wolfie_20260728131310_001.json`
- `docs/logs/2026/07/28/bundle.json`

---

## 8. CLI (Python)

```text
python scripts/logging/log_writer.py write-captain --thread-id semantic-thread-001 --intent "..." ...
python scripts/logging/log_writer.py write-wolfie --thread-id semantic-thread-001 --observation "..." ...
python scripts/logging/log_writer.py bundle --date 2026-07-28 --thread-id semantic-thread-001 --summary "..." --link captain_ID:wolfie_ID:supporting
```

On Windows PowerShell, avoid `|` inside `--link` (shell pipe). Use `:` separators as shown.
---

## 9. PUKA (open gaps)

1. **DB tables** -- filesystem-first for now; no `lupo_*` operational log tables until a seed/install proposal is approved.
2. **Auto semantic_links** -- current writer accepts explicit links; NLP/auto-infer is future.
3. **Auth gate** -- writers are CLI/dev tooling; web UI authorship control not specified.
4. **Memory sidecar per log** -- optional; not required for every JSON log write.
5. **TypeScript runtime** -- TS modules are tooling mirrors; Python is the supported runnable path (no Composer/npm requirement for core OS).

---

## 10. Cross-references

- PRD 16_C -- headers
- PRD 41 -- Captain WOLFIE identity / dual-captaincy
- PRD 98_A -- WHY files (not this subsystem)
- PRD 98_B -- entertainment Captain's Log (not this subsystem)
- `docs/actors/how_wolves_are_made.md` -- wolf maturity / hard-gate context
- TIMESTAMP doctrine -- packed UTC only for canonical clock
