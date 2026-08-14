---
lupopedia.headers:
  header_format_version: "4.2.11"
  path_from_lupopedia_root: docs/protocols/hex/README.md
  web_path: https://www.lupopedia.com/lupopedia/docs/protocols/hex/README.md
  status: active
  when_updated: "20260814151801"
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/protocol-color-registry
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: documentation
  prd_cluster: 16_C_34_A_99_A
  title: "Protocol color registry -- CSV seed files under docs/protocols/hex"
  summary: "Flat-file color CSVs per protocol under docs/protocols/hex/<PROTOCOL>/. No database required. Spec: HEX.COLORS.md."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 10000
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: federation
  faucet_actor_id: 102
lupopedia.identity:
  LUPOPEDIA: PRT.LUP
  LUP.KEY: PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
  LUP.HEX: PRT.HEX.000001.000018.000000.EN.04020A
  LUP.SHORT: PRT.LUP
  LUP.ROOT: PRT.LUP.ROOT.ROOT.EN.042010
  LUP.OMIT: MODE_WHEN_NAME + ANY_DEFAULT_FIELD
  LUP.DEFAULTS: PRT.NAME.PRT.LUP.ROOT.ROOT.EN.0
lupopedia.map:
  index: PRT.HEX.000001.000018.000000.EN.04020A
  web_path: https://www.lupopedia.com/lupopedia/docs/protocols/hex/README.md
  path_from_lupopedia_root: docs/protocols/hex/README.md
  prd_cluster: 16_C_34_A_99_A
  edges_toon: null
  memory_toon: null
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/protocol-color-registry
  questions_toon: null
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
---
# Protocol color registry (CSV seeds)

Color words are registered **per protocol**. Spec: [HEX.COLORS.md](HEX.COLORS.md).

Lookup is **flat-file**. A database is not required. The Lupopedia ID routes to the protocol folder (and optional Class C shard).

These files are navigation and configuration aids. They are **not** doctrine. They do not override PRDs. They do not modify `lupopedia.headers`, `lupopedia.identity`, `lupopedia.map`, or `lupopedia.metadata`.

Header contract remains **4.2.11** (PRD 16_C). ASCII-safe only. No pipes, no middle-dot, no hyphens in KEY. Color HEX is a CSV field, not a LUP.KEY token.

## Files

| Protocol | CSV |
|----------|-----|
| PRT.LUP | `docs/protocols/hex/PRT.LUP/PRT.LUP.colors.csv` |

Naming pattern for a new protocol:

```text
docs/protocols/hex/<PROTOCOL>/<PROTOCOL>.colors.csv
```

Example: LUP uses `docs/protocols/hex/PRT.LUP/PRT.LUP.colors.csv`.

## CSV columns

```text
word_registry_id,word,hex_color,field_type,iso_language,created_ymdhis,updated_ymdhis,source_table,usage_count,actor_hex
```

Lookup matches CSV rows. SQL is not required.

## How to add a color (PRT.LUP)

1. Open `docs/protocols/hex/PRT.LUP/PRT.LUP.colors.csv`.
2. Append one row. Do not reuse an existing `word_registry_id`.
3. `word` is lowercase English for now (example: `grass`).
4. `hex_color` is exactly 6 hex characters (`0-9`, `A-F` or `a-f`). No `#`. No spaces.
5. `field_type` for these LUP seed rows is `node`.
6. `iso_language` is `EN` until a non-English registry is authorized.
7. `created_ymdhis` and `updated_ymdhis` are `0` for seed rows.
8. `source_table` is `seed`.
9. `usage_count` starts at `0`.
10. `actor_hex` for seed rows is `808080`.
11. Keep the file comma-separated, ASCII-only, one header row, no pipes, no emoji.

LUP seed ids `101`-`243` are in use (yellow, blue, ocean, grass, then named-color seeds). Next LUP color must not collide with an existing `word_registry_id` or `word`.

The CSV may store mixed-case hex (`grass` is `0c871b`). Lookup should treat hex as case-insensitive.

## What this is not

- Not Rule 99 color-band doctrine (that stays in PRD 99 / federation Rule 99 files).
- Not a KEY identity. Do not put color words into `LUP.KEY` / `LUP.HEX` / `LUP.SHORT` / `LUP.ROOT`.
- Not a live database. Lookup reads the CSV. No SQL required.

Protocol article: `docs/protocols/lup/PRT.LUP.md`

## VARIANTS INDEX

Navigation-only list of TYPE.LANG variants of this artifact. Not doctrine. Does not override PRDs. Does not change header authority.

### MUSIC VARIANTS

- (none yet)

### VIDEO VARIANTS

- (none yet)

### WEB VARIANTS

- (none yet)

### DOCUMENT VARIANTS

- DOC.01.EN  This registry guide (`docs/protocols/hex/README.md`)

### NOTES

- Variant indexes are navigation-only.
- Do not modify lupopedia.headers, lupopedia.metadata, or lupopedia.map.
- ASCII-safe dot grammar only. No pipes. No middle-dot. No hyphens in variant IDs.
