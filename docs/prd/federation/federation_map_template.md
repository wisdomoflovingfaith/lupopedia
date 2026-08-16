---
lupopedia.headers:
  header_format_version: "4.2.11"
  path_from_lupopedia_root: docs/prd/federation/federation_map_template.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/federation/federation_map_template.md
  status: active
  when_updated: "20260815212117"
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
  prd_cluster: 16_C_99_A_34_A
  title: "Federation map template -- header 4.2.11"
  summary: "Canonical 4.2.11 federation map template. ASCII field delimiter is dot. Dense 28-field grid unchanged."
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
  LUP.HEX: PRT.HEX.000001.000014.000000.ROOT.EN.04020A
  LUP.SHORT: PRT.LUP
  LUP.ROOT: PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
  LUP.OMIT: REGISTERED_SHORT_FORMS_ONLY
  LUP.DEFAULTS: PRT.NAME.000000.000000.ROOT.ROOT.EN.0
lupopedia.map:
  index: PRT.HEX.000001.000014.000000.ROOT.EN.04020A
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/federation/federation_map_template.md
  path_from_lupopedia_root: docs/prd/federation/federation_map_template.md
  prd_cluster: 16_C_99_A_34_A
  edges_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/prd-federation
  questions_toon: null
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
---
# Federation map template (header 4.2.11)

Canonical copy-paste template for Lupopedia federation maps. Parent: PRD 16_C section 4.2.6. Product atom `GLOBAL_CURRENT_LUPOPEDIA_VERSION` is **4.2.11** (same patch as this header contract).

**ASCII delimiter:** Field separator is `.` (dot, ASCII 46). No middle-dot. No pipe. No hyphen in KEY grammar. YAML storage uses `key: value` so parsers work; grammar notation may write `KEY = VALUE`.

**4.2.3 through 4.2.10:** Created outside this Cursor workspace (Claude, Gemini, DeepSeek, Qwen, ChatGPT, dream-compiler, Patreon). This workspace indexed 4.2.4 last. 4.2.11 is the next in-repo header contract.

**Unchanged:** 28-field dense `lupopedia.headers` grid, Hawaiian constitutional fields, Rule 99 color bands, CC-BY name.

## 1. Header block

```yaml
lupopedia.headers:
  header_format_version: "4.2.11"
```

New authored envelopes MUST declare `"4.2.11"`. Older 4.2.0-4.2.4 envelopes remain dual-accept until next edit (PRD 16_E). Do not mass-rewrite the corpus.

## Complete v4.2.11 header (copy-paste)

Grammar notation (`=`). YAML files MUST store identity as `key: value`. Dense discovery scalars stay in `lupopedia.headers` (28-field grid). `lupopedia.metadata` does not duplicate that grid.

```text
lupopedia.headers:
  header_format_version: "4.2.11"

lupopedia.identity:
  LUPOPEDIA     = PRT.LUP
  LUP.KEY       = PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
  LUP.HEX       = PRT.HEX.000000.000000.000000.ROOT.EN.04020B
  LUP.SHORT     = PRT.LUP
  LUP.ROOT      = PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
  LUP.OMIT      = REGISTERED_SHORT_FORMS_ONLY
  LUP.DEFAULTS  = PRT.NAME.000000.000000.ROOT.ROOT.EN.0

lupopedia.map:
  index: <LUP.HEX identity for this document>
  web_path: <canonical public URL>
  path_from_lupopedia_root: <relative path inside Lupopedia>
  prd_cluster: <cluster identifier>
  edges_toon: <toon file or null>
  memory_toon: <toon file>
  atoms_toon: <toon file>
  transcript_jsonl: <jsonl transcript path>
  questions_toon: <toon file or null>

lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
```

Fill `lupopedia.headers` with the existing 28-field dense envelope (`title`, `summary`, `status`, `when_updated`, `trust_tier`, `artifact_type`, `actor_id`, `auth_user_id`, `faucet_actor_id`, ...). Do not move those keys into metadata.

## 2. Identity block

```text
lupopedia.identity:
  LUPOPEDIA     = PRT.LUP
  LUP.KEY       = PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
  LUP.HEX       = PRT.HEX.000000.000000.000000.ROOT.EN.04020B
  LUP.SHORT     = PRT.LUP
  LUP.ROOT      = PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
  LUP.OMIT      = REGISTERED_SHORT_FORMS_ONLY
  LUP.DEFAULTS  = PRT.NAME.000000.000000.ROOT.ROOT.EN.0
```

YAML file storage of the same block uses `key: value` (required for parsers). Values and field order MUST match. No hyphens in this grammar.

| Key | Rule |
|-----|------|
| LUP.KEY | Field order is mandatory. Do not reorder. |
| LUP.DEFAULTS | Fill missing fields from this 8-token string. NODE/ARTIFACT default `000000`, not slug `PRT` or `LUP`. |
| LUP.OMIT | REGISTERED_SHORT_FORMS_ONLY. Arbitrary middle-field omission is forbidden. |
| LUP.SHORT | Registered production `PROTOCOL.ARTIFACT` (`PRT.LUP`). Display only. |
| LUP.HEX | Machine federation routing. MODE=HEX. Eight tokens always. GROUP=`ROOT`. Zeros example VERSION `04020B` (4.2.11). |
| LUP.ROOT | Full eight-token NAME form `PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A` (packed 4.2.10). |

KEY tokens:

| Token | Meaning |
|-------|---------|
| PROTOCOL | `PRT` |
| MODE | `NAME` (human) or `HEX` (machine) |
| NODE | Federation node. Always 6-hex. Default `000000` (unspecified). Live nodes begin at `000001`. |
| ARTIFACT | Artifact identity. NAME default `000000`; `PRT.LUP` sets ARTIFACT to `LUP`. HEX uses 6-hex (colon lineage allowed inside this token only). |
| ACTOR | Actor. NAME default `ROOT`. HEX default `000000`. |
| GROUP | Namespace / group. Default `ROOT`. Always present in the stored eight-token KEY. |
| LANGUAGE | ISO 639-1 or reserved `ZZ`. Default `EN`. |
| VERSION | Packed `0xMMmmPP`. `4.2.10` = `04020A`. `4.2.11` = `04020B`. Never `042010`. |

## 3. Federation map block

```yaml
lupopedia.map:
  index: <LUP.HEX identity for this document>
  web_path: <canonical public URL>
  path_from_lupopedia_root: <relative path inside Lupopedia>
  prd_cluster: <cluster identifier for federation routing>
  edges_toon: <toon file for edge relationships or null>
  memory_toon: <toon file for memory lineage>
  atoms_toon: <toon file for atom-level constants>
  transcript_jsonl: <jsonl transcript path>
  questions_toon: <toon file for Q&A or null>
```

- `index` MUST be a valid LUP.HEX identity.
- `web_path` MUST be the canonical public URL under `https://www.lupopedia.com/lupopedia/`.
- `path_from_lupopedia_root` MUST match the file location (forward slashes).
- `prd_cluster` MUST match federation routing / PRD 16 cluster rules.
- `memory_toon`, `atoms_toon`, `transcript_jsonl` MUST be valid paths or null where doctrine allows.
- `edges_toon` and `questions_toon` MAY be null.
- Map fields mirror routing pointers. They do **not** replace the dense 28-field header grid.

## 4. Metadata block

```yaml
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
```

Discovery scalars (`title`, `summary`, `status`, `when_updated`, `trust_tier`, `artifact_type`, `actor_id`, `auth_user_id`, `faucet_actor_id`, ...) stay in **`lupopedia.headers`** (28-field grid). Do not invent a second dense grid under metadata. Do not invent new metadata keys.

`when_updated` MUST come from `python bin/tick.py` (real UTC).

## Dual-accept (pre-4.2.11)

Unedited 4.2.0-4.2.4 files remain valid. Hyphen form `LUP:FFFFFF-RRRRRR-NN-II-LL-AA` is **not** part of the 4.2.11 KEY grammar. Do not mix hyphens into LUP.KEY / LUP.HEX / LUP.SHORT / LUP.ROOT.

## Validator codes

| Code | Rule |
|------|------|
| HDR_LUP_KEY_ORDER | LUP.KEY must be PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION |
| HDR_LUP_HEX | LUP.HEX / map.index must parse as HEX identity |
| HDR_LUP_MAP_REQUIRED | WARN on new 4.2.11 if lupopedia.map missing |
| HDR_LUP_DELIM | Reject middle-dot / pipe as KEY delimiter |

See `scripts/validate_lup_identity.py`.
