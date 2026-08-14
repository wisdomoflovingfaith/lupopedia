---
lupopedia.headers:
  header_format_version: "4.2.2"
  path_from_lupopedia_root: docs/versions/4.2.2/changelog.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.2.2/changelog.md
  status: active
  when_updated: "20260811135140"
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/4-2-2-changelog
  artifact_type: version-doc
  artifact_kind: version_specific
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: version-doc
  prd_cluster: 00_A_16_C_99_A_15_A
  title: Lupopedia 4.2.2 changelog (unstable / transitional)
  summary: "Product atom 4.2.2. Header contract 4.2.2: LUP:FFFFFF-GG-LL-II-RRRRRR. RRRRRR is artifact_hex. FF is 6 hex."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 10000
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: release
  faucet_actor_id: 102
lupopedia.identity:
  lupopedia_id: "LUP:000001-01-EN-00-000011"
  federation_id: "000001"
  group_id: "01"
  language: "EN"
  iteration: "00"
  artifact_hex: "000011"
---
# Lupopedia 4.2.2 -- Changelog

**Release class:** TRANSITIONAL / UNSTABLE
**Prior atom:** 4.2.0 (`config/global_atoms.yaml`)
**Authority:** Captain ALII -- version bump 20260811

Product version and header format are both **4.2.2**. Do not treat this as production-stable. Do not mass-rewrite the header corpus.

## Atom

- `GLOBAL_CURRENT_LUPOPEDIA_VERSION` = `4.2.2` in `config/global_atoms.yaml`
- Same atom in `config/GLOBAL_IMPORTANT_ATOMS.yaml`
- Versioning keys in `memory/channels/atoms/lupopedia_global_constants.atom.toon`

## Header contract (4.2.2)

```text
LUP:FFFFFF-GG-LL-II-RRRRRR
```

- FFFFFF = 6-digit federation ID. Missing FF = `000001`. `000000` forbidden.
- GG = catalog owner namespace.
- RRRRRR = `artifact_hex` (not actor, not color).
- `actor_id` and `color_hex` are metadata.
- Dense 28-field grid unchanged from 4.2.0.
- Dual-accept: 4.2.1 two-digit FF is WARN; new files require 4.2.2.

Normative: PRD 16_C section 4.2.5, PRD 99, PRD 16_E section 7.

## Not in this bump

- No Lupopedia-to-Lupopedia installer upgrade.
- No mass header rewrite.
- No Rule 99 color-band change.
- No Hawaiian fields in the dense grid or identity block.
