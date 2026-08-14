---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/prd/federation/rule_99_actor_color_range.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/federation/rule_99_actor_color_range.md
  status: active
  when_updated: "20260810162610"
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/prd-federation
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: rule-99-federation
  lupopedia.schema: prd
  prd_cluster: 99_A
  title: "RULE 99.ACTOR_COLOR_RANGE -- bullet specification (per Node)"
  summary: "Actor N = Catalog N; start=N*100; System 0 / Wolfie 1 / Lilith 2; last 143999; reserved DBBA00-FFFFFF; 14.4M practical cap."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 1
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: federation
  faucet_actor_id: 102
---
# RULE 99.ACTOR_COLOR_RANGE -- bullet specification (per Node)

Parent: PRD 99. Applies identically on every Federation Node.

## Hard limit

- Maximum catalog Actors per Node: 144000 (`N` in `0..143999`).
- Not 167772.
- Not full 16777216 / 100 occupancy.
- Colors per Actor: exactly 100.
- Full Node color space: 16777216.
- Colors used at cap: 14400000 (~14.4M practical assignment limit).
- Reserved buffer: 2377216.
- Global ceiling per Node: FFFFFF.

## Range mathematics

- start_int(N) = N * 100
- end_int(N) = start_int(N) + 0x63
- Inclusive span is 100 HEX values.
- System N=0: 000000 -> 000063
- Wolfie N=1: 000064 -> 0000C7
- Lilith N=2: 0000C8 -> 00012B
- Last usable N=143999: DBB99C -> DBB9FF
- Reserved: DBBA00 -> FFFFFF
- Reject wrong draft HEX DB9E9C / DB9FFF / DBA000.
- Reject legacy formula (N-1)*100 with N in 1..144000.
- Reject Wolfie on start band 000000->000063.
- Reject Lilith on catalog 144000.

## Rules

- Catalog Actor Number MUST equal OS actor_id (no mismatch).
- Every Actor gets exactly 100 colors.
- Actors may only choose colors inside their range.
- Actors may not choose colors outside their range.
- Actors may not collide with another Actor's range on the same Node.
- Actors may not claim past the reserved zone.
- New base songs use the next available HEX color inside the Actor's range.
- Manual color selection is allowed only if unclaimed and inside the Actor's range.
- Remixes and covers inherit the original base color and increment iteration.
- Free users get one Actor (100 songs) on a Node.
- Paid users can create more Actors (100 songs each) on a Node.
- No Node may redefine the 100-slot width or 144000 ceiling.

## Alignment

- Catalog Actor Number = OS lupo_actors.actor_id.
- Lilith OS / Catalog 2 enforces Rule 99 and owns 0000C8 -> 00012B.
- Catalog Actor Number 143999 owns the final usable color band on that Node.
