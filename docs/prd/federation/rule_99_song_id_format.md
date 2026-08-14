---
lupopedia.headers:
  header_format_version: "4.2.3"
  path_from_lupopedia_root: docs/prd/federation/rule_99_song_id_format.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/federation/rule_99_song_id_format.md
  status: active
  when_updated: "20260811171511"
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
  prd_cluster: 99_A_16_C
  title: "RULE 99.SONG_ID_FORMAT -- universal LUP identity plus color metadata"
  summary: "Songs use LUP:FFFFFF-RRRRRR-NN-II-LL-AA. RRRRRR is artifact identity, not color."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 1
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: federation
  faucet_actor_id: 102
lupopedia.identity:
  lupopedia_id: "LUP:000001-000005-01-00-EN-01"
  federation_id: "000001"
  artifact_hex: "000005"
  namespace_id: "01"
  iteration: "00"
  language: "EN"
  actor_aa: "01"
lupopedia.metadata:
  media_kind: song
  color_hex: "000064"
---
# RULE 99.SONG_ID_FORMAT -- bullet specification

## LUP -- Linked Universal Protocol

**LUP** stands for **Linked Universal Protocol**, the universal identity system used by Lupopedia to identify, version, translate, federate, and track provenance for any digital artifact.

LUP -- Linked Universal Protocol (Universal Artifact Identity). Not a song-only ID. Not "Lupopedia ID."

Parent: PRD 99 and PRD 16_C section 4.2.5. Music files live under install-relative /music.

Songs are **not** a special identity class.

RRRRRR is the artifact identity block, not color. NN replaces GG. AA is first-class. Color is metadata `color_hex`. Rule 99 bands are unchanged. Six-digit `actor_hex` is metadata.

## Object identity (universal)

LUP (Linked Universal Protocol) Identity Grammar:

```text
LUP:FFFFFF-RRRRRR-NN-II-LL-AA
```

```text
short:      LUP:X-000000-01
canonical:  LUP:000001-000000-01-00-EN-01
human:      LUP:X-000000-01-00-EN-01
```

AA `01` maps to `actor_id` 1. Metadata `color_hex` `000064` is Wolfie's first Rule 99 slot.

## Color and actor

```yaml
lupopedia.headers:
  actor_id: 1
lupopedia.identity:
  actor_aa: "01"
lupopedia.metadata:
  color_hex: "000064"
```

- `color_hex` MUST be inside the catalog owner's 100-slot band (`start = owner_actor_id * 100`).
- `artifact_hex` MUST NOT be required to equal `actor_id`.
- Catalogs whose owner `actor_id` > 143999 cannot publish songs.

Legacy compact label `01EN000064` is a filename/display hint only.

## Federation

- Missing FF means Node **`000001`**.
- `FF=000000` and `FF=FFFFFF` are reserved.
- Two-digit legacy FF zero-pads (`01` => `000001`).
- Unmodified migration changes **only FFFFFF**: `LUP:000001-000000-01-00-EN-01` -> `LUP:000003-000000-01-00-EN-01`.
- Modified on another node: RRRRRR becomes `originFed:artifactNumber` (colon only). Example: `LUP:000003-000002:123456-01-00-EN-01`.

## Iteration (remix / cover)

- Same-federation: same FFFFFF, RRRRRR, NN, AA, LL. Same `color_hex`. II increments.
- Cross-federation remix: new FF plus `originFed:artifactNumber`.
- Edge: `remix_of`.

## Translation

- LL changes only under translation policy.
- Recommended: same II; require `translation_of` edge.

## Forbidden

- Putting color in RRRRRR or AA.
- Changing Rule 99 band math.
- Actor as RRRRRR.
- 256-slot assumptions.
- 0x100 stride assumptions.
- Catalog Number that does not equal OS actor_id of the **namespace owner**.
- Publishing a decentralized song without FF.
