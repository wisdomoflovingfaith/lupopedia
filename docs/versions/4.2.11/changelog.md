---
lupopedia.headers:
  header_format_version: "4.2.11"
  path_from_lupopedia_root: docs/versions/4.2.11/changelog.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.2.11/changelog.md
  status: active
  when_updated: "20260814141353"
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/header-4-2-11
  artifact_type: documentation
  artifact_kind: changelog
  channel_key: development
  federation_node_id: 0
  thread_key: header-4-2-11
  lupopedia.schema: documentation
  prd_cluster: 16_C_16_E_99_A
  title: "Header contract 4.2.11 changelog -- federation map"
  summary: "4.2.11 KEY identity (ASCII dots) and lupopedia.map. 4.2.3-4.2.10 compiled outside Cursor."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 1
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: headers
  faucet_actor_id: 102
lupopedia.identity:
  LUPOPEDIA: PRT.LUP
  LUP.KEY: PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
  LUP.HEX: PRT.HEX.000000.000000.000000.EN.04020A
  LUP.SHORT: PRT.LUP
  LUP.ROOT: PRT.LUP.ROOT.ROOT.EN.042010
  LUP.OMIT: MODE_WHEN_NAME + ANY_DEFAULT_FIELD
  LUP.DEFAULTS: PRT.NAME.PRT.LUP.ROOT.ROOT.EN.0
lupopedia.map:
  index: PRT.HEX.000001.000015.000000.EN.04020A
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.2.11/changelog.md
  path_from_lupopedia_root: docs/versions/4.2.11/changelog.md
  prd_cluster: 16_C_16_E_99_A
  edges_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/header-4-2-11
  questions_toon: null
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
---
# Header contract 4.2.11 -- federation map (ASCII KEY)

**Header format version:** 4.2.11
**UTC:** 20260814141353
**Product atom:** `GLOBAL_CURRENT_LUPOPEDIA_VERSION` = **4.2.11** (TRANSITIONAL / UNSTABLE). Header contract is the same patch.

## Why Cursor indexed 4.2.2 then 4.2.11

Versions 4.2.3 through 4.2.10 were created outside this Cursor workspace (Claude, Gemini, DeepSeek, Qwen, ChatGPT, dream-compiler, Patreon). Cursor indexes files it touches. Last in-repo identity compile before this bump: 4.2.4. 4.2.11 is the next Cursor-indexed header contract. Do not invent 4.2.5-4.2.10 files here.

## ASCII KEY grammar

```text
LUPOPEDIA     = PRT.LUP
LUP.KEY       = PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
LUP.HEX       = PRT.HEX.000000.000000.000000.EN.04020A
LUP.SHORT     = PRT.LUP
LUP.ROOT      = PRT.LUP.ROOT.ROOT.EN.042010
LUP.OMIT      = MODE_WHEN_NAME + ANY_DEFAULT_FIELD
LUP.DEFAULTS  = PRT.NAME.PRT.LUP.ROOT.ROOT.EN.0
```

- Field delimiter: `.` (ASCII 46)
- No middle-dot, no pipe, no hyphen in KEY
- YAML storage: `key: value`
- Sibling `lupopedia.map` with `index` = document LUP.HEX
- Dense 28-field `lupopedia.headers` unchanged
- `lupopedia.metadata` stays `media_kind` + `cc_by_name`

## Surfaces updated

- `config/global_atoms.yaml` (`version` + `GLOBAL_CURRENT_LUPOPEDIA_VERSION` = 4.2.11)
- `config/GLOBAL_IMPORTANT_ATOMS.yaml`
- `memory/channels/atoms/lupopedia_global_constants.atom.toon`
- CHANGELOG.md current-version pointer
- PRD 16_C section 4.2.6 and product-semver forward note
- PRD 16_A, 16_E
- README.md
- `docs/prd/federation/federation_map_template.md`
- glossary
- `scripts/validate_lup_identity.py`

## Validator

```text
python scripts/validate_lup_identity.py PATH
```

Codes: `HDR_LUP_KEY_ORDER`, `HDR_LUP_HEX`, `HDR_LUP_MAP_REQUIRED`, `HDR_LUP_DELIM`.
