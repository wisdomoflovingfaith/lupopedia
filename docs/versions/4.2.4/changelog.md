---
lupopedia.headers:
  header_format_version: "4.2.4"
  path_from_lupopedia_root: docs/versions/4.2.4/changelog.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.2.4/changelog.md
  status: active
  when_updated: "20260811171511"
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/header-4-2-4
  artifact_type: documentation
  artifact_kind: changelog
  channel_key: development
  federation_node_id: 0
  thread_key: header-4-2-4
  lupopedia.schema: documentation
  prd_cluster: 16_C_16_E_99_A
  title: "Header contract 4.2.4 changelog -- Federation Compression"
  summary: "Identity grammar 4.2.4: FF 000001 compresses to X; RRRRRR lineage delimiter is colon."
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
  lupopedia_id: "LUP:000001-000013-01-00-EN-01"
  federation_id: "000001"
  artifact_hex: "000013"
  namespace_id: "01"
  iteration: "00"
  language: "EN"
  actor_aa: "01"
---
# Header contract 4.2.4 -- Federation Compression (Option A)

**Header format version:** 4.2.4  
**UTC:** 20260811155120  
**Scope:** Identity grammar only. Namespace, actor, language, artifact numbering, CC-BY, and Hawaiian fields unchanged.

## Rule

Federation `000001` is the canonical root node. In short-form identities, it is compressed to the symbol `X`.

```text
machine:         LUP:000001-RRRRRR-NN-II-LL-AA
human-friendly:  LUP:X-RRRRRR-NN-II-LL-AA
short:           LUP:X-RRRRRR-NN
```

- `X` is lossless compression of `000001` only.
- Validators accept `X`, expand to `000001`, reject other uses of `X`.
- Machine export / disk persistence always uses six-hex `000001`.
- Compression applies only in human-friendly output modes.

## Surfaces updated

- PRD 16_C, 16_A, 16_D, 16_E
- PRD 99 song identity
- README.md (Lupopedia software / root identity section)
- HOW_TO_LUPOPEDIA_A_SONG.md
- glossary (`docs/channels/appendix/appendix/glossary.md`)
- `scripts/validate_lup_identity.py`

## Validator

```text
python scripts/validate_lup_identity.py PATH
```

Codes: `HDR_LUP_FED_COMPRESS`, `HDR_LUP_FED_X_ON_DISK`.

## RRRRRR lineage delimiter (colon)

Colon `:` is the official lineage delimiter inside RRRRRR.

```text
original Fed 2:  LUP:000002-123456-01-00-EN-01
iterated Fed 3:  LUP:000003-000002:123456-01-00-EN-01
remixed Fed 5:   LUP:000005-000003:123456-01-00-EN-01
```

- Split RRRRRR on the first colon. Left = origin federation. Right = artifact number.
- No colon = native to the current federation.
- Colon MUST NOT appear elsewhere except the `LUP:` prefix.
- `X` remains FF compression of `000001` only. It is not a lineage joiner.
- Native IDs without lineage stay valid. Other joiners MUST migrate to `:`.

Codes: `HDR_LUP_RR_ORIGIN`, `HDR_LUP_RR_LEGACY_DELIM`, `HDR_LUP_COLON_ELSEWHERE`.
