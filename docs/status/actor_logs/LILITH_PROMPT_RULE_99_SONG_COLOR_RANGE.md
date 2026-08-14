---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/status/actor_logs/LILITH_PROMPT_RULE_99_SONG_COLOR_RANGE.md
  web_path: https://www.lupopedia.com/lupopedia/docs/status/actor_logs/LILITH_PROMPT_RULE_99_SONG_COLOR_RANGE.md
  status: active
  when_updated: "20260810162610"
  trust_tier: development
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/status/lilith-rule-99-prompt
  artifact_type: status
  artifact_kind: report
  channel_key: status
  federation_node_id: 0
  thread_key: rule-99-song-color
  lupopedia.schema: status
  prd_cluster: 99_A_00_C
  title: "Lilith prompt -- audit Rule 99 song color range docs"
  summary: "Copy-paste prompt for Lilith to audit RULE 99: Actor=Catalog alignment; N*100; System0/Wolfie1/Lilith2; last 143999; reject legacy start-band and 144000-Lilith."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 2
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: audit
  faucet_actor_id: 102
---
# Lilith prompt -- Rule 99 song color range

Copy the fenced block into a Lilith chat.

---

```text
LILITH PROMPT -- RULE 99 ACTOR COLOR RANGE AUDIT

WHO: Lilith (actor_id / Catalog 2). Orchestrator ERIC (auth_user_id 10000). Faucet CURSOR (102).

TASK: Audit and enforce RULE 99 as written in:
- docs/prd/99_A-i_LIMITS_FOR_EVERYTHING_AND_WHY.md (section Actor Color Range)
- HOW_TO_LUPOPEDIA_A_SONG.md
- .lilith/rules/rule-99-actor-color-range.md
- docs/prd/federation/rule_99_actor_color_range.md
- docs/prd/00_C-i_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md section 10.2 validator row

HARD FACTS Lilith MUST treat as true:
- Catalog Actor Number MUST equal OS actor_id (no mismatch)
- Max usable catalog Actors = 144000 with N in 0..143999 (NOT 167772)
- Colors per Actor = 100 = [start .. start+0x63]
- Formula start = N * 100 (NOT (N-1)*100)
- System Catalog 0 = 000000 -> 000063
- Wolfie Catalog 1 = 000064 -> 0000C7 (NOT the start band)
- Lilith Catalog 2 = 0000C8 -> 00012B (enforcer; NOT catalog 144000)
- Last usable Catalog 143999 = DBB99C -> DBB9FF
- Reserved = DBBA00 -> FFFFFF (NOT DBA000)
- Practical usable colors at cap = 14400000 (~14.4M)
- Reject legacy 256 / 0x100 song bands and 167772 Actor ceiling
- Reject Wolfie on 000000->000063; reject Lilith on catalog 144000

OUTPUT:
1) PASS/FAIL per E1-E13 in .lilith/rules/rule-99-actor-color-range.md
2) List any remaining repo prose that still says 167772 Actors, 256-wide song ranges, Wolfie start band, or Lilith=144000
3) End with <RULE_99_AUDIT_COMPLETE>

Do not invent new math. Do not densify Hawaiian fields into headers. ASCII only in repo edits.
```
