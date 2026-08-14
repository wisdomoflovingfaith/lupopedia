---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/prd/federation/readme.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/federation/readme.md
  status: active
  when_updated: "20260810162610"
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/prd-federation
  artifact_type: prd
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: rule-99-federation
  lupopedia.schema: prd
  prd_cluster: 99_A_34_A_00_C
  title: "PRD federation folder -- Rule 99 companions"
  summary: "Index for RULE 99.FEDERATION companions. Actor N = Catalog N; N*100 ranges; Lilith Catalog 2 enforces."
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
# PRD federation folder (Rule 99 companions)

**Canonical parent:** [docs/prd/99_A-i_LIMITS_FOR_EVERYTHING_AND_WHY.md](../99_A-i_LIMITS_FOR_EVERYTHING_AND_WHY.md)  
**Semantic network PRD:** [docs/prd/34_A-i_FEDERATION_NODE_SEMANTIC_NETWORK.md](../34_A-i_FEDERATION_NODE_SEMANTIC_NETWORK.md)  
**Song guide:** [HOW_TO_LUPOPEDIA_A_SONG.md](../../HOW_TO_LUPOPEDIA_A_SONG.md)

## Files (bullet PRDs)

- [federation_map_template.md](federation_map_template.md) -- header 4.2.11 federation map + LUP.KEY (ASCII dots)
- [rule_99_federation.md](rule_99_federation.md) -- RULE 99.FEDERATION
- [rule_99_actor_color_range.md](rule_99_actor_color_range.md) -- RULE 99.ACTOR_COLOR_RANGE (per Node)
- [rule_99_song_id_format.md](rule_99_song_id_format.md) -- RULE 99.SONG_ID_FORMAT
- [rule_99_node_lookup.md](rule_99_node_lookup.md) -- RULE 99.NODE_LOOKUP

## Cursor / agent notes

- Music ID system lives under install-relative `/music` (path doctrine; no hardcoded install folder name).
- Federation logic for song catalog lives here under `docs/prd/federation/`.
- Do not assume Node 0 is the only catalog.
- Catalog Actor Number MUST equal OS actor_id (Actor <-> Catalog alignment).
- Enforce 100-slot HEX (`start = N * 100`) and 144000 Actors (`0..143999`) **per Node** only.
- Lilith Catalog 2 enforces alignment and reserved zone `DBBA00`->`FFFFFF`.
