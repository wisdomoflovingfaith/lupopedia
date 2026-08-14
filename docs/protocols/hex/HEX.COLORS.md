---
lupopedia.headers:
  header_format_version: "4.2.11"
  path_from_lupopedia_root: docs/protocols/hex/HEX.COLORS.md
  web_path: https://www.lupopedia.com/lupopedia/docs/protocols/hex/HEX.COLORS.md
  status: active
  when_updated: "20260814152028"
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/hex-colors-spec
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: documentation
  prd_cluster: 16_C_34_A_99_A
  title: "HEX.COLORS -- Protocol Color Registry Specification (4.2.11)"
  summary: "Flat-file protocol color CSVs today. LRL abstracts lookup. SQL or key-value later. No lock files. NODE fallback. Color is not a KEY token."
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
  LUP.HEX: PRT.HEX.000001.000019.000000.EN.04020A
  LUP.SHORT: PRT.LUP
  LUP.ROOT: PRT.LUP.ROOT.ROOT.EN.042010
  LUP.OMIT: MODE_WHEN_NAME + ANY_DEFAULT_FIELD
  LUP.DEFAULTS: PRT.NAME.PRT.LUP.ROOT.ROOT.EN.0
lupopedia.map:
  index: PRT.HEX.000001.000019.000000.EN.04020A
  web_path: https://www.lupopedia.com/lupopedia/docs/protocols/hex/HEX.COLORS.md
  path_from_lupopedia_root: docs/protocols/hex/HEX.COLORS.md
  prd_cluster: 16_C_34_A_99_A
  edges_toon: null
  memory_toon: null
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/hex-colors-spec
  questions_toon: null
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
---
# HEX.COLORS -- Protocol Color Registry Specification (4.2.11 Contract)

This file explains the Lupopedia Protocol Color Registry System.

Protocol color registries are **currently stored as flat-file CSVs**. The Lupopedia ID system provides deterministic routing, so flat files work well for **read-heavy** workloads. SQL is not required today.

When write frequency increases, flat files become unsafe (concurrency, lost updates). Lupopedia will introduce a **Registry Layer (LRL)** that keeps the same API and lookup rules while the storage backend changes. **No `.lock` files.** Lock files are unsafe and undesirable for Lupopedia.

ASCII-safe. Dot grammar. Header contract 4.2.11. Color HEX is a CSV field, not a LUP.KEY token.

Folder guide: [README.md](README.md)
LUP seed CSV: [PRT.LUP/PRT.LUP.colors.csv](PRT.LUP/PRT.LUP.colors.csv)

## Purpose

Each protocol maintains its own color registry CSV. Each CSV maps human-readable color names to HEX values for identity mapping, routing, UI rendering, and semantic grouping.

This system is flat-file **today**. It does not override PRDs. It does not modify header authority.

## Lupopedia Registry Layer (LRL)

LRL abstracts color **lookup** and color **creation**. Callers use LRL. Callers do not open storage files directly once LRL exists.

Evolution path:

1. **Flat-file now.** Read CSV. Write CSV only for single-actor operations.
2. **Database later.** SQL or key-value storage for multi-actor concurrency.
3. **Same naming rules.** Lowercase ASCII `word`, 6-character `hex_color`, no `#`.
4. **Same fallback rules.** Requested `field_type`, then `node`, then request creation. Do not guess.
5. **Same protocol-scoped structure.** `docs/protocols/hex/<PROTOCOL>/<PROTOCOL>.colors.csv` remains the protocol identity of the registry even if rows later live in SQL.

LRL will:

- read from CSV files today
- write to CSV files safely for single-actor operations
- later support SQL or key-value storage for multi-actor concurrency
- maintain the same API and lookup rules

**No `.lock` files.** They are considered unsafe and undesirable. Do not add `*.lock` beside color CSVs. Single-actor CSV writes must be atomic replace (write temp in the same directory, then rename) without a lock file. Multi-actor writes wait for a storage backend that can serialize them.

LRL is planned protocol infrastructure. It does not override PRDs. It does not change KEY grammar.

## Location

Color registries live in:

```text
docs/protocols/hex/<PROTOCOL>/<PROTOCOL>.colors.csv
```

`<PROTOCOL>` is the protocol SHORT identity (example `PRT.LUP`).

Example:

```text
docs/protocols/hex/PRT.LUP/PRT.LUP.colors.csv
```

## Class C directory partitioning

A 6-hex Lupopedia field (NODE, ARTIFACT, or ACTOR) is three byte pairs:

```text
RRGGBB  ->  RR GG CC
example NODE 000001  ->  00 00 01
```

The **Class C** portion is the third byte pair (`CC`). That is the same idea as an IPv4 Class C shard (the last octet of a three-byte prefix): it is a stable, deterministic folder key.

The ID can route a file without a database:

```text
docs/protocols/hex/<PROTOCOL>/<CC>/
```

Example: NODE `000001` Class C = `01`

```text
docs/protocols/hex/PRT.LUP/01/
```

The protocol-level color registry (the canonical name-to-HEX table) stays at:

```text
docs/protocols/hex/<PROTOCOL>/<PROTOCOL>.colors.csv
```

Class C subfolders are optional shards for large per-node or per-artifact color sets. They do not replace the protocol CSV. Do not guess a shard. Derive `CC` from the 6-hex field in the ID.

## CSV Schema

Header row (comma-separated):

```text
word_registry_id,word,hex_color,field_type,iso_language,created_ymdhis,updated_ymdhis,source_table,usage_count,actor_hex
```

## Field Rules

- `word` is lowercase ASCII-safe.
- `hex_color` is a 6-character hex value without a `#` prefix.
- `field_type` may be: `node`, `actor`, `group`, `artifact`, `mode`, `protocol`.
- `node` is the canonical default for all other field_types.
- `iso_language` is `EN` for now.
- `created_ymdhis` and `updated_ymdhis` are `0` for seed rows.
- `source_table` is `seed` for initial entries.
- `usage_count` starts at `0`.
- `actor_hex` for seed rows is `808080`.
- `word_registry_id` is an explicit integer. Do not reuse an id inside the same protocol CSV.

## Lookup Behavior (LRL API, CSV today)

Today LRL (or a direct CSV reader until LRL ships) opens the protocol CSV and matches rows. Do not require a database for reads.

When resolving a color for a specific `field_type`:

1. First attempt: `word=<name>` AND `field_type=<requested>`
2. If missing, fallback: `word=<name>` AND `field_type=node`
3. If still missing, the system must request creation of a new color entry.

NODE is the universal fallback for all other field_types. Do not guess a HEX value.

## Missing Color Rule

If a color name does not exist in the protocol CSV:

- The system must request creation of a new entry.
- New entries must be ASCII-safe lowercase `word` values and valid 6-character hex (no `#`).
- The Captain approves new canonical color names.
- Do not invent a LUP.KEY token for the color.

## Example (LUP Protocol)

`docs/protocols/hex/PRT.LUP/PRT.LUP.colors.csv` includes seed entries such as:

```text
yellow -> FFFF00
blue   -> 0000FF
ocean  -> 1E90FF
grass  -> 0c871b
```

CSV rows:

```text
101,yellow,FFFF00,node,EN,0,0,seed,0,808080
102,blue,0000FF,node,EN,0,0,seed,0,808080
103,ocean,1E90FF,node,EN,0,0,seed,0,808080
104,grass,0c871b,node,EN,0,0,seed,0,808080
```

The LUP CSV may contain additional named-color seed rows. Do not reuse an existing `word_registry_id`.

## Authority

This file is a protocol-level specification.

- Flat-file now. Database later. Same API, naming, fallback, and protocol scope.
- The Lupopedia ID routes to protocol folder and optional Class C shard.
- No `.lock` files.
- It does not override PRDs.
- It does not modify header authority.
- Rule 99 color bands remain PRD 99. Color is metadata, not identity.

4.2.11 CSV contract:

- ASCII-safe only
- No pipes
- No middle-dot
- No hyphens in KEY grammar
- Comma-separated values
- No `#` prefix in `hex_color` fields

## VARIANTS INDEX

Navigation-only list of TYPE.LANG variants of this artifact. Not doctrine. Does not override PRDs. Does not change header authority.

### MUSIC VARIANTS

- (none yet)

### VIDEO VARIANTS

- (none yet)

### WEB VARIANTS

- (none yet)

### DOCUMENT VARIANTS

- DOC.01.EN  This specification (`docs/protocols/hex/HEX.COLORS.md`)
- DOC.02.EN  Folder guide (`docs/protocols/hex/README.md`)
- DOC.03.EN  LUP color CSV (`docs/protocols/hex/PRT.LUP/PRT.LUP.colors.csv`)

### NOTES

- Variant indexes are navigation-only.
- Do not modify lupopedia.headers, lupopedia.metadata, or lupopedia.map.
- ASCII-safe dot grammar only. No pipes. No middle-dot. No hyphens in variant IDs.
