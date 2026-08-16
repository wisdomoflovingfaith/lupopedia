---
lupopedia.headers:
  header_format_version: "4.2.11"
  path_from_lupopedia_root: PRT.HEX.000000.000000.000000.ROOT.EN.04020B.md
  web_path: https://www.lupopedia.com/lupopedia/PRT.HEX.000000.000000.000000.ROOT.EN.04020B.md
  status: active
  when_updated: "20260815210351"
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/root/prt-hex-protocol-spec
  artifact_type: documentation
  artifact_kind: guide
  channel_key: root
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: documentation
  prd_cluster: 16_C_34_A_99_A
  title: "PRT.HEX zeros -- Lupopedia headers, ID system, and protocols (4.2.11 / 04020B)"
  summary: "Root detailed spec for lupopedia.headers, LUP KEY identity, federation map, protocols, and color registries. Packed HEX VERSION 04020B is 4.2.11. Does not override PRD 16_C."
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
  LUP.HEX: PRT.HEX.000000.000000.000000.ROOT.EN.04020B
  LUP.SHORT: PRT.LUP
  LUP.ROOT: PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
  LUP.OMIT: REGISTERED_SHORT_FORMS_ONLY
  LUP.DEFAULTS: PRT.NAME.000000.000000.ROOT.ROOT.EN.0
lupopedia.map:
  index: PRT.HEX.000000.000000.000000.ROOT.EN.04020B
  web_path: https://www.lupopedia.com/lupopedia/PRT.HEX.000000.000000.000000.ROOT.EN.04020B.md
  path_from_lupopedia_root: PRT.HEX.000000.000000.000000.ROOT.EN.04020B.md
  prd_cluster: 16_C_34_A_99_A
  edges_toon: null
  memory_toon: null
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/root/prt-hex-protocol-spec
  questions_toon: null
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
---
# PRT.HEX.000000.000000.000000.ROOT.EN.04020B

Root detailed specification for **Lupopedia headers**, the **Lupopedia ID system**, and **protocols**.

**This file's identity (filename = HEX):**

```text
PRT.HEX.000000.000000.000000.ROOT.EN.04020B
```

Zeros here are the **protocol example / this spec**, not a live federation node. Live federation nodes begin at `000001`. `000000` is reserved for examples and unspecified artifacts. Packed VERSION `04020B` is **4.2.11** (`04` `02` `0B`). Older templates still show HEX example `04020A` (4.2.10 packing). This document is 4.2.11.

Product atom: `GLOBAL_CURRENT_LUPOPEDIA_VERSION` = **4.2.11** (TRANSITIONAL / UNSTABLE).
Header contract: `header_format_version: "4.2.11"`.

This file does **not** override PRDs. Normative PRD remains [PRD 16_C](docs/prd/16_C-i_LUPOPEDIA_HEADERS.md). Template: [federation_map_template.md](docs/prd/federation/federation_map_template.md). Protocol article: [PRT.LUP.md](docs/protocols/lup/PRT.LUP.md). Color spec: [HEX.COLORS.md](docs/protocols/hex/HEX.COLORS.md).

ASCII-safe. Dot grammar. No pipes, no middle-dot, no hyphens in KEY.

---

## Table of contents

1. Authority and version packing
2. Lupopedia headers
3. Lupopedia ID system (LUP KEY)
4. Protocols
5. Color registries and LRL
6. Dual-accept and migration
7. Validators
8. Related files

---

## 1. Authority and version packing

| Surface | Authority |
|---------|-----------|
| Header contract / dense grid | PRD 16_C |
| KEY grammar + map | PRD 16_C section 4.2.6 and federation map template |
| Protocol narrative | `docs/protocols/lup/PRT.LUP.md` |
| Color CSV / LRL | `docs/protocols/hex/HEX.COLORS.md` |
| Product semver | `config/global_atoms.yaml` `GLOBAL_CURRENT_LUPOPEDIA_VERSION` |
| This file | Detailed operator spec. Routes to PRDs. Does not replace them. |

Packed HEX VERSION is a 24-bit integer `0xMMmmPP`:

```text
Packed = 0xMMmmPP where:
- MM = major (2 hex digits)
- mm = minor (2 hex digits)
- PP = patch (2 hex digits)

4.2.9  -> 040209
4.2.10 -> 04020A
4.2.11 -> 04020B
```

ROOT human VERSION in `LUP.ROOT` is packed `04020A` (4.2.10). Do not write `042010`. Do not conflate header contract `4.2.11`, product atom `4.2.11`, HEX packing `04020B`, KEY-grammar clarification `4.2.26`, and ROOT example `04020A`.

4.2.3 through 4.2.10 were compiled outside this Cursor workspace. Do not invent those version folders here.

---

## 2. Lupopedia headers

Every new hand-authored Markdown file MUST start with YAML front matter. Line 1 is exactly `---`.

A 4.2.11 envelope has four sibling blocks:

1. `lupopedia.headers` -- dense discovery grid (28 scalars)
2. `lupopedia.identity` -- KEY grammar constants plus this document's HEX
3. `lupopedia.map` -- federation routing index
4. `lupopedia.metadata` -- `media_kind` + `cc_by_name` only

Do not copy the dense grid into metadata. Do not invent extra metadata keys. Hawaiian constitutional fields stay out of the grid and out of identity (PRD 82_B). Color stays metadata / Rule 99 / color CSV, not KEY.

`when_updated` MUST come from `python bin/tick.py` (real UTC `YYYYMMDDHHIISS`).

### 2.1 Dense 28-field grid (`lupopedia.headers`)

Order is mandatory. Do not omit a key. Use `''` or YAML `null` only where PRD 16 allows.

```text
header_format_version
path_from_lupopedia_root
web_path
status
when_updated
trust_tier
questions_toon
memory_toon
atoms_toon
transcript_jsonl
artifact_type
artifact_kind
channel_key
federation_node_id
thread_key
lupopedia.schema
prd_cluster
title
summary
edges_toon
channel_index
source_timestamp
actor_id
auth_user_id
department_id
department_key
division_key
faucet_actor_id
```

`lupopedia.schema` MUST equal `artifact_type`.

`web_path` = `https://www.lupopedia.com/lupopedia/` + `path_from_lupopedia_root` (forward slashes). Never `file://` or drive letters.

Identity stack: `actor_id` is who speaks. `auth_user_id` is human accountability (ERIC = 10000). `faucet_actor_id` is the IDE surface (Cursor = 102). Do not set `actor_id` equal to the faucet unless the speaker truly is that facet.

### 2.2 Envelope shapes

**Markdown:** YAML between `---` lines. After the closing `---`, the body starts.

**Python / PHP:** 25-line `#` comment grid (open/close `# -----`). PHP: immediately after `<?php`. Do not emit bare `lupopedia.headers:` under `/**` without ` * ` leaders.

### 2.3 Copy-paste 4.2.11 envelope

YAML storage uses `key: value`. Grammar notation may use `=`.

```yaml
lupopedia.headers:
  header_format_version: "4.2.11"
  path_from_lupopedia_root: <relative path>
  web_path: https://www.lupopedia.com/lupopedia/<relative path>
  status: active
  when_updated: "<tick.py current_utc>"
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/<channel>/<slug>
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: documentation
  prd_cluster: 16_C
  title: "<title>"
  summary: "<one line>"
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
  LUP.HEX: PRT.HEX.<NODE>.<ARTIFACT>.<ACTOR>.ROOT.EN.04020B
  LUP.SHORT: PRT.LUP
  LUP.ROOT: PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
  LUP.OMIT: REGISTERED_SHORT_FORMS_ONLY
  LUP.DEFAULTS: PRT.NAME.000000.000000.ROOT.ROOT.EN.0
lupopedia.map:
  index: PRT.HEX.<NODE>.<ARTIFACT>.<ACTOR>.ROOT.EN.04020B
  web_path: https://www.lupopedia.com/lupopedia/<relative path>
  path_from_lupopedia_root: <relative path>
  prd_cluster: 16_C
  edges_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/<channel>/<slug>
  questions_toon: null
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
```

`map.index` MUST equal this document's LUP.HEX (not a borrowed HEX from another file).

`LUP.ROOT` in the identity block is the full eight-token NAME form `PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A` (packed `04020A` = 4.2.10). `LUP.SHORT` is `PRT.LUP` (registered `PROTOCOL.ARTIFACT`). Do not store six-token or seven-token strings.

`LUP.DEFAULTS` MUST be `PRT.NAME.000000.000000.ROOT.ROOT.EN.0` (NODE and ARTIFACT default to 6-hex `000000`). Not `PRT.NAME.PRT.LUP.ROOT.ROOT.EN.0`.

`LUP.OMIT` is `REGISTERED_SHORT_FORMS_ONLY`. Arbitrary middle-field omission is forbidden.

New files: `header_format_version: "4.2.11"`. Dual-accept 4.2.0-4.2.4 until the file is edited. Do not mass-rewrite the corpus.

---

## 3. Lupopedia ID system (LUP KEY)

LUP = Linked Universal Protocol (also Logical Universal Pointer / Lupopedia Universal Protocol). It is the universal identity layer for artifacts, not a song-only ID.

```text
LUPOPEDIA     = PRT.LUP
LUP.KEY       = PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
LUP.HEX       = PRT.HEX.000000.000000.000000.ROOT.EN.04020B
LUP.SHORT     = PRT.LUP
LUP.ROOT      = PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
LUP.OMIT      = REGISTERED_SHORT_FORMS_ONLY
LUP.DEFAULTS  = PRT.NAME.000000.000000.ROOT.ROOT.EN.0
```

`LUP.SHORT` is the registered production `PROTOCOL.ARTIFACT`. `LUP.ROOT` is the full eight-token NAME form (packed VERSION `04020A` = 4.2.10). Storage, comparison, federation, hashing, and APIs always use eight tokens.

```text
SLUG  = [A-Z0-9_]{1,22}
HEX6  = [0-9A-F]{6}
```

Valid slug examples: `LUP`, `LUPDOC`, `WOLFIE`, `MY_ARTIFACT_01`.
Invalid: `my-artifact` (hyphen), `Lup` (lowercase), more than 22 characters.

### 3.1 Markers

| Marker | Where | Meaning |
|--------|-------|---------|
| `.` | KEY / HEX / SHORT / ROOT | Field delimiter (ASCII 46) |
| `key: value` | YAML files | Storage. Colon is YAML, not a KEY delimiter. |
| `=` | Grammar notation | Prose / templates only |
| `:` | HEX ARTIFACT token only | Optional lineage `originFed:artifactNumber` |

No pipe. No hyphen. No middle-dot in KEY / HEX / SHORT / ROOT.

### 3.2 Eight KEY tokens

| Token | Meaning |
|-------|---------|
| PROTOCOL | `PRT` (or registered protocol such as `CCB`) |
| MODE | `NAME` (human) or `HEX` (machine) |
| NODE | Federation node. Always HEX6. Live federation nodes begin at `000001`. `000000` is reserved for examples and unspecified artifacts. Range `000000`..`FFFFFE`. Zeros in this filename are the protocol example. |
| ARTIFACT | Artifact identity. Slug or 6-hex. Default `000000`. `PRT.LUP` sets ARTIFACT to `LUP`. Colon lineage allowed inside this token only. |
| ACTOR | Actor. NAME default `ROOT`. HEX default `000000` (HEX6). |
| GROUP | Namespace / group. Default `ROOT`. Always present in the stored eight-token KEY. |
| LANGUAGE | ISO 639-1 or reserved `ZZ`. Default `EN`. |
| VERSION | Packed `0xMMmmPP`, or `0` unversioned. This spec file uses `04020B` (4.2.11). `LUP.ROOT` uses `04020A` (4.2.10). |

Position controls meaning. Do not infer actor/object/color from spelling or length. Color is not a KEY token.

### 3.3 Registered short forms (no arbitrary OMIT)

A positional grammar cannot omit arbitrary middle fields. `PRT.LUP.WHEEL` cannot tell ACTOR from GROUP. `PRT.HEX.000022` cannot tell NODE from ARTIFACT unless the production is registered.

`LUP.OMIT` is **REGISTERED_SHORT_FORMS_ONLY**.

Human display may use only the productions below. Storage, comparison, federation, hashing, and APIs **always** use the complete eight-token KEY.

Precedence: if the second token is exactly `NAME` or `HEX`, the production is MODE (not ARTIFACT). `NAME` and `HEX` are reserved MODE tokens, not artifact slugs.

| Production | Shape | NAME expansion | HEX expansion |
|------------|-------|----------------|---------------|
| SHORT_PROTOCOL | `PROTOCOL` | `PRT.NAME.000000.000000.ROOT.ROOT.EN.0` | (use SHORT_MODE) |
| SHORT_ARTIFACT | `PROTOCOL.ARTIFACT` | `PRT.LUP` -> `PRT.NAME.000000.LUP.ROOT.ROOT.EN.0` | n/a |
| SHORT_MODE | `PROTOCOL.MODE` | `PRT.NAME` -> `PRT.NAME.000000.000000.ROOT.ROOT.EN.0` | `PRT.HEX` -> `PRT.HEX.000000.000000.000000.ROOT.EN.0` |
| SHORT_MODE_NODE | `PROTOCOL.MODE.NODE` | `PRT.NAME.000001` -> `PRT.NAME.000001.000000.ROOT.ROOT.EN.0` | `PRT.HEX.000001` -> `PRT.HEX.000001.000000.000000.ROOT.EN.0` |
| SHORT_MODE_NODE_ARTIFACT | `PROTOCOL.MODE.NODE.ARTIFACT` | `PRT.NAME.000001.00ABCD` -> `PRT.NAME.000001.00ABCD.ROOT.ROOT.EN.0` | `PRT.HEX.000001.000010` -> `PRT.HEX.000001.000010.000000.ROOT.EN.0` |
| FULL_KEY | eight tokens | as written | as written |

HEX fill: NODE / ARTIFACT / ACTOR default `000000`. GROUP default `ROOT`.
NAME fill: NODE / ARTIFACT default `000000`. ACTOR / GROUP default `ROOT`.
LANGUAGE default `EN`. VERSION default `0`.

Any other token count or shape is invalid. Do not guess.

`LUP.SHORT` = `PRT.LUP` (SHORT_ARTIFACT).
`LUP.ROOT` = `PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A` (FULL_KEY, packed 4.2.10).

Do not pack `PARENT.CHILD` extra dots inside a KEY token. Deeper ancestry lives in edges / `lupopedia.map`. HEX artifact lineage uses colon inside ARTIFACT only.

### 3.4 NAME vs HEX profiles

NAME segments: SLUG (uppercase `A-Z` `0-9` `_`, length 1-22).

HEX positional fields: HEX6 except LANGUAGE (2 letters or `ZZ`) and VERSION (`0` or packed HEX6). Language is never converted to hex.

Protocol MODE examples:

```text
PRT.NAME
PRT.HEX
CCB.NAME
CCB.HEX
```

Invalid as 4.2.11 KEY:

```text
PRT|HEX
CCB|NAME
LUP|NAME:PRT:LUP:ROOT:ROOT:EN:040209
```

### 3.5 Class C directory partitioning

A 6-hex field is three byte pairs:

```text
000001  ->  00 00 01
```

The Class C portion is the third pair (`01`). It MAY be used as a folder shard. Derive it from the ID. Do not guess.

### 3.6 What is not a LUP token

| Not this | Why |
|----------|-----|
| Color / RGB / gold names | Color lives in CSV registries under `docs/protocols/hex/` |
| Hawaiian constitutional terms (KAPU, PONO, ALII, ...) | Routing / ethics in PRD 82_B, not identity |
| Media kind (song, image, video) | `lupopedia.metadata.media_kind` and variant IDs such as `MUS.01.EN` |
| Human title | `lupopedia.headers.title` |
| ISO-8601 timestamps | BIGINT UTC `YYYYMMDDHHIISS` in headers / columns |
| UUID / random IDs | LUP IDs are positional and deterministic |
| Filesystem path | `path_from_lupopedia_root` / `web_path`, not a KEY token |
| Colon-bag grammar | Rejected. Not part of LUP. See section 6. |

Identity is universal: songs, documents, PRDs, crests, and atoms use the same KEY grammar.

---

## 4. Protocols

Protocols live under `docs/protocols/`.

```text
docs/protocols/lup/PRT.LUP.md          LUP protocol article
docs/protocols/hex/HEX.COLORS.md       Color registry spec
docs/protocols/hex/README.md           Color folder guide
docs/protocols/hex/<PROTOCOL>/<PROTOCOL>.colors.csv
```

`<PROTOCOL>` is the protocol SHORT identity (example `PRT.LUP`).

Each protocol may register:

- a protocol article
- a color CSV
- later, other typed registries through LRL

LUP is the identity protocol. Other protocols (example `CCB` for CC-BY objects) use the same KEY order with their own PROTOCOL token.

The Lupopedia ID routes to protocol folder (and optional Class C shard) without a database. That is why flat-file registries work for read-heavy work.

---

## 5. Color registries and LRL

Colors are metadata. They are not KEY tokens.

CSV path:

```text
docs/protocols/hex/<PROTOCOL>/<PROTOCOL>.colors.csv
```

LUP example: `docs/protocols/hex/PRT.LUP/PRT.LUP.colors.csv`

Columns:

```text
word_registry_id,word,hex_color,field_type,iso_language,created_ymdhis,updated_ymdhis,source_table,usage_count,actor_hex
```

`word` lowercase ASCII. `hex_color` 6 hex, no `#`. `node` is the canonical `field_type` default.

Lookup (same rules whether CSV today or SQL later):

1. `word=<name>` AND `field_type=<requested>`
2. Else `word=<name>` AND `field_type=node`
3. Else request creation. Do not guess. Captain approves canonical names.

Examples:

```text
yellow -> FFFF00
blue   -> 0000FF
ocean  -> 1E90FF
grass  -> 0c871b
```

**Lupopedia Registry Layer (LRL):** abstracts lookup and creation. CSV read now. Safe single-actor CSV write now (atomic temp + rename). SQL or key-value later for multi-actor concurrency. Same API, naming, fallback, and protocol-scoped paths.

**No `.lock` files.** They are unsafe and undesirable.

Full spec: [docs/protocols/hex/HEX.COLORS.md](docs/protocols/hex/HEX.COLORS.md)

---

## 6. Dual-accept and migration

Unedited 4.2.0-4.2.4 files remain valid until next edit.

4.2.4 hyphen form (legacy, not 4.2.11 KEY):

```text
LUP:FFFFFF-RRRRRR-NN-II-LL-AA
```

Human root federation `000001` may compress to `X` in that hyphen form only. Machine storage stays six-hex `000001`. `FF=000000` is forbidden as a **live** node. This spec file's zeros HEX is the protocol example, not a live node.

4.2.9 / 4.2.10 pipe-colon seven-element strings are outdated. Convert positionally. Do not global search-replace markers in prose.

Colon-bag grammar (`PROTOCOL.VALUE` joined by colons, order-agnostic bags) is **not** part of LUP. Do not use it. Do not mix it with the KEY. The only colon in a LUP KEY is optional lineage inside ARTIFACT (`originFed:artifactNumber`).

Do not mix hyphens into new 4.2.11 KEY values. Do not mass-rewrite the corpus.

Migration: [PRD 16_E](docs/prd/16_E-i_LUPOPEDIA_HEADERS_MIGRATION.md)

---

## 7. Validators

```text
python scripts/validate_lup_identity.py PATH
python bin/tick.py
```

| Code | Rule |
|------|------|
| HDR_LUP_KEY_ORDER | LUP.KEY must equal PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION |
| HDR_LUP_HEX | LUP.HEX / map.index must be eight dotted HEX tokens including GROUP |
| HDR_LUP_MAP_REQUIRED | WARN on new 4.2.11 if lupopedia.map missing |
| HDR_LUP_DELIM | Reject middle-dot, pipe, or hyphen in KEY grammar values |
| HDR_LUP_RR_ORIGIN | When ARTIFACT lineage is present, origin 6-hex must differ from current NODE |

After expansion, reject the KEY if:

- token count is not 8
- any token contains hyphen, pipe, or middle-dot
- MODE is `HEX` and NODE / ARTIFACT / ACTOR are not valid HEX6 (ARTIFACT may still carry one colon lineage)
- ARTIFACT has more than one colon, or a non-hex origin, or a right side that is not HEX6
- lineage is present and origin equals the current NODE
- LANGUAGE is not two letters or `ZZ`
- VERSION is neither `0` nor HEX6
- PROTOCOL is empty, or not in your local registry of accepted protocols

A string that fails these checks is not a LUP KEY. Do not guess.

---

## 8. Related files

| Path | Role |
|------|------|
| [lupopedia.protocal.readme.txt](lupopedia.protocal.readme.txt) | One-page external start |
| [LUPOPEDIA.FOR.EXTERNAL.DOMAINS.md](LUPOPEDIA.FOR.EXTERNAL.DOMAINS.md) | External domains KEY guide |
| [PRD 16_C](docs/prd/16_C-i_LUPOPEDIA_HEADERS.md) | Normative headers |
| [federation_map_template.md](docs/prd/federation/federation_map_template.md) | Copy-paste 4.2.11 envelope |
| [PRT.LUP.md](docs/protocols/lup/PRT.LUP.md) | LUP protocol article |
| [HEX.COLORS.md](docs/protocols/hex/HEX.COLORS.md) | Color CSV + LRL |
| [README.md](README.md) | Root operator README |
| [docs/index.md](docs/index.md) | Actors Collection index (HEX artifact `000010`) |
| `config/global_atoms.yaml` | Product version atom |
| `.cursor/rules/header-4-2-11-federation-map.mdc` | Cursor KEY rule |
| `.cursor/rules/variant-index.mdc` | VARIANTS INDEX body section |

---

## VARIANTS INDEX

Navigation-only list of TYPE.LANG variants of this artifact. Not doctrine. Does not override PRDs. Does not change header authority.

### MUSIC VARIANTS

- (none yet)

### VIDEO VARIANTS

- (none yet)

### WEB VARIANTS

- (none yet)

### DOCUMENT VARIANTS

- DOC.01.EN  This spec (`PRT.HEX.000000.000000.000000.ROOT.EN.04020B.md`)
- DOC.02.EN  LUP protocol (`docs/protocols/lup/PRT.LUP.md`)
- DOC.03.EN  Headers PRD (`docs/prd/16_C-i_LUPOPEDIA_HEADERS.md`)
- DOC.04.EN  Color spec (`docs/protocols/hex/HEX.COLORS.md`)

### NOTES

- This section is reserved for future variant indexes beyond the DOCUMENT rows above.
- Variant indexes are navigation-only.
- Do not modify lupopedia.headers, lupopedia.metadata, or lupopedia.map.
- ASCII-safe dot grammar only. No pipes. No middle-dot. No hyphens in variant IDs.
