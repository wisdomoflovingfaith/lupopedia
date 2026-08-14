---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/16_E-i_LUPOPEDIA_HEADERS_MIGRATION.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/16_E-i_LUPOPEDIA_HEADERS_MIGRATION.md
  status: active
  when_updated: '20260814140129'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/headers/canonical/1026/04/lupopedia-headers.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/headers/lupopedia-headers
  artifact_type: prd
  artifact_kind: guide
  channel_key: headers
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_16_B-i_16_C-i_16_E-i
  title: 'PRD 16: Lupopedia Headers (Migration Guide)'
  summary: Migration guide including 4.2.3 to 4.2.4 Federation Compression (000001 -> X human forms). Machine storage stays 000001. LL=ZZ. Color stays metadata.
---
<!-- ASCII_ART_BLOCK -->
. . . . . . . . . ._________________ LUPOPEDIA Semantic Operating System _________________
. ./ \ ` ` `_\-\ . | A two-dimensional, finite, constitutional PRD documentation
. '/| \-''-/_ / . | architecture that lets docs build software. PRDs reference
. { . , . , . ,\ .| other PRDs, forming clusters that define behavior, truth,
. / . , . , . , \ | limits, and system identity. Each file carries a header that
./ , . "O. |"O. } | records the exact prd_cluster (reading order), the full
_| . , . , \ \ ;. | transcript_jsonl dialog, and atoms_toon for canonical truth,
. '\. . , . \ \' . | ensuring deterministic lineage and reproducibility.
.. '\_ . , . \__\ | https://www.lupopedia.com/
., , ''-_ , {\__/}|
. . , . / '-.____'| - Eric Robin Gerdes ( Captain WOLFIE ) lupopedia@gmail.com
., , /___________________________________________________________________________________
.. , _'
___-'
<!-- /ASCII_ART_BLOCK -->

<!-- HUMAN_SEMANTIC -->
This file belongs to:
??? PRD Group 16 (Identity Layer ??? Headers, Atoms, Migration)
??? Cluster 16ABCD
??? Channel: headers
??? No default collection yet

See also:
??? 00_A_FORBIDDEN_AND_WHY.md
??? 00_B_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md
??? PRD 86 ??? Immune system (no drift, no entropy)
??? Order of Operations: PRD ??? Schema ??? Mockups ??? Code
<!-- /HUMAN_SEMANTIC -->

# PRD 16 Migration Guide

## LUP -- Linked Universal Protocol

**LUP** stands for **Linked Universal Protocol**, the universal identity system used by Lupopedia to identify, version, translate, federate, and track provenance for any digital artifact.

LUP -- Linked Universal Protocol (Universal Artifact Identity). Not a song-only ID. Not "Lupopedia ID."

LUP (Linked Universal Protocol) Identity Grammar:

```text
LUP:FFFFFF-RRRRRR-NN-II-LL-AA
```

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## 1. Principle

Do not mass-migrate headers blindly. Migrate per file with validation.

**Note on Future Automation:** Starting with version 4.1.5, the manual "per-file" migration workflow is deprecated in favor of automated validator-led enforcement. Future header schema updates and alias retirements will be handled primarily by the universal validator's `--fix` capabilities.

## 2. Legacy alias policy

All legacy field names are accepted by the validator and emitted as warnings. Migrate per file.

### 2.1 v4.1.1 ??? pk_* ??? content_* (HDR_PK_LEGACY_ALIAS)

| Legacy field | Canonical field | Accepted until | Rejected from | Status |
|---|---|---|---|---|
| `pk_id` | `content_id` | 4.1.2 (warning only) | 4.1.3 (validation error) | Migrated |
| `pk_slug` | `content_slug` | 4.1.2 (warning only) | 4.1.3 (validation error) | **REMOVED** |
| `parent_pk_id` | `content_parent_id` | 4.1.2 (warning only) | 4.1.3 (validation error) | Migrated |

### 2.2 v4.0.99 ??? pre-canonical renames (HDR_LEGACY_FIELD_NAME)

| Legacy field | Canonical field | Removed in | Status |
|---|---|---|---|
| `prd_id` | `content_id` | 4.1.1 | Migrated |
| `prd_slug` | `content_slug` | 4.1.1 | **REMOVED** |
| `parent_prd` | `content_parent_id` | 4.1.1 | Migrated |
| `last_modified_utc` | `questions_toon` | 4.1.1 | Migrated |
| `module` | `atoms_toon` | 4.1.1 | Migrated |

### 2.3 v4.1.0 ??? toon renames

| Legacy field | Canonical field | Removed in |
|---|---|---|
| `memory_key` | `memory_toon` | 4.2.0 |
| `dialog_transcript` | `transcript_jsonl` | 4.1.3 |

## 3. Cutoff policy

- **4.1.3**: Removed `pk_*` aliases and `dialog_transcript`
- **4.1.5**: Removed `content_slug` entirely (now generated at ingestion time from `path_from_lupopedia_root` and/or `title`)
- **4.2.0**: Remove all remaining migration compatibility aliases
- **4.2.1**: Add sibling `lupopedia.identity`; dual-accept 4.2.0 with WARN; no mass rewrite
- **4.2.2**: Expand FF to 6 hex digits (Option C); dual-accept 4.2.1 with WARN; zero-pad; no mass rewrite

## 4. Per-file migration workflow

### 4.1 Steps

1. Read header and confirm canonical **22-key** order target (including `prd_cluster`).
2. Rename legacy fields to canonical names.
3. **Remove** any remaining `content_slug` fields.
4. Confirm `transcript_jsonl` is a DB slug, not a path.
5. Validate nullable fields (see ??4.4).
6. Run `python bin/tick.py` and set `when_updated`.
7. Validate with the universal validator.
8. Regenerate sidecar from header authority.

### 4.4 `content_parent_id` by artifact_type

| artifact_type | content_parent_id rule |
|---------------|------------------------|
| `prd` | MUST be null (PRDs have no parent) |
| `implementation` | SHOULD be integer (PRD number this implements) |
| `documentation` | MAY be integer (parent PRD if documenting specific spec) |
| `doctrine` | MUST be null (root constitutional rules) |
| `version-doc` | SHOULD be integer (PRD this version doc relates to) |
| `status` | SHOULD be integer (PRD or version doc this status tracks) |

`default_collection_id` is always nullable for all `artifact_type` values.

## 5. `when_updated` semantics (objective rule)

**Rule:** `when_updated` SHALL be set to the UTC timestamp of the **last byte change** to the file using `tick.py` output.

**Implementation:**

```bash
python bin/tick.py   # Copy current_utc to when_updated
```

## 6. Header 4.2.0 -> 4.2.1 (universal identity)

Normative companion: PRD 16_C section 4.2.5.

### 6.1 Dual-acceptance period

| Declared `header_format_version` | `lupopedia.identity` | Validator |
|----------------------------------|----------------------|-----------|
| 4.2.1 (new artifacts) | Required | Missing = ERROR (`HDR_LUP_ID_REQUIRED`) |
| 4.2.0 (existing corpus) | Optional during window | Missing = WARN |
| 4.1.9 and older | Not required | Existing legacy rules |

Do **not** mass-rewrite the corpus in the opening bump. Migrate per file when the file is otherwise edited.

### 6.2 Per-file steps (4.2.1)

1. Keep the 28-field dense grid unchanged.
2. Add sibling `lupopedia.identity` with reconstructed `lupopedia_id`.
3. Allocate `artifact_hex` as the next free artifact number inside GG (`000000` .. `FFFFFF`). Do **not** copy `actor_id` into RRRRRR.
4. Map `federation_node_id` 0 -> `federation_id` `01`. Never emit FF `00`.
5. Choose `group_id` (catalog owner), `language`, `iteration` (`00` for first generation).
6. Keep dense `actor_id` as creator metadata. If the artifact is a song, put Rule 99 slot in `lupopedia.metadata.color_hex`, **not** in `artifact_hex`.
7. Run `python bin/tick.py` and set `when_updated`.
8. Set `header_format_version: "4.2.1"` only after the identity block is complete.

### 6.3 Federation migration (FF only)

```text
LUP:01-01-EN-00-000001
->
LUP:03-01-EN-00-000001
```

Only `federation_id` / mapped `federation_node_id` / FF token may change. Record `federated_from` in `edges_toon`.

### 6.4 Node 0 -> Node 01

| Legacy | 4.2.1 |
|--------|-------|
| Missing Federation ID | Node 01 |
| `federation_node_id: 0` | `federation_id: "01"` |
| `FF=00` | Forbidden |

## 7. Header 4.2.1 -> 4.2.2 (six-digit FF, Option C)

Normative companion: PRD 16_C section 4.2.5.

### 7.1 Dual-acceptance period

| Declared `header_format_version` | `federation_id` width | Validator |
|----------------------------------|-----------------------|-----------|
| 4.2.2 (new artifacts) | 6 hex required | Short FF = ERROR (`HDR_LUP_FF_WIDTH`) |
| 4.2.1 (existing corpus) | 2 hex accepted | Short FF = WARN; zero-pad on next edit |
| 4.2.0 and older | Identity optional / absent | Existing 4.2.1 dual-accept rules |

Do **not** mass-rewrite the corpus. Expand FF when the file is otherwise edited.

### 7.2 Per-file steps (4.2.2)

1. Keep the 28-field dense grid unchanged.
2. Keep `artifact_hex`, GG, LL, II, and `actor_id` unchanged.
3. Expand `federation_id` to 6 uppercase hex digits by zero-padding (`01` => `000001`, `03` => `000003`).
4. Rebuild `lupopedia_id` as `LUP:{federation_id}-{group_id}-{language}-{iteration}-{artifact_hex}`.
5. Map `federation_node_id` 0 -> `federation_id` `000001`. Never emit `000000`.
6. Run `python bin/tick.py` and set `when_updated`.
7. Set `header_format_version: "4.2.2"` only after FF is 6 digits and `lupopedia_id` reconstructs.

### 7.3 Federation migration (FFFFFF only)

```text
LUP:000001-01-EN-00-000000
->
LUP:000003-01-EN-00-000000
```

Only the 6-digit `federation_id` / mapped `federation_node_id` / FFFFFF token may change. Record `federated_from` in `edges_toon`.

### 7.4 Node 0 -> Node 000001

| Legacy (4.2.1) | 4.2.2 |
|----------------|-------|
| Missing Federation ID | Node `000001` |
| `federation_node_id: 0` | `federation_id: "000001"` |
| `FF=01` | `FF=000001` |
| `FF=00` / `FF=000000` | Forbidden |

## 8. Header 4.2.2 -> 4.2.3 (human-friendly layout)

Normative companion: PRD 16_C section 4.2.5.

**LUP** (Linked Universal Protocol) becomes:

```text
4.2.2: LUP:FFFFFF-GG-LL-II-RRRRRR
4.2.3: LUP:FFFFFF-RRRRRR-NN-II-LL-AA
```

### 8.1 Dual-acceptance and FAIL policy

| Declared `header_format_version` | Identity | Validator |
|----------------------------------|----------|-----------|
| 4.2.3 (new artifacts) | Canonical 6-token required on disk | Legacy GG form / RGB-in-ID = ERROR unless `--migration` |
| 4.2.3 (human input) | 3 / 4 / 5 / 6 tokens | Auto-default missing fields; persist 6-token |
| 4.2.2 (existing corpus) | 6-field GG form | WARN (`HDR_LUP_LEGACY_6FIELD`) |
| 4.2.1 | 2-digit FF + 6-field | WARN; zero-pad then expand |
| Pre-4.2.1 | No LUP identity contract | **FAIL** (`HDR_LUP_PRE_421`) when identity is claimed |

Do **not** mass-rewrite the corpus. Upgrade when the file is otherwise edited.

### 8.2 Per-file steps (4.2.3)

1. Keep the 28-field dense grid unchanged. Hawaiian fields unchanged. Rule 99 color **bands** unchanged. CC-BY metadata unchanged.
2. Rename `group_id` -> `namespace_id` (GG -> NN).
3. Promote actor provenance into `actor_aa` (AA). Map `actor_id` via registry. Keep `actor_hex` as metadata.
4. Keep `color_hex` in metadata. Do **not** put color in the LUP string.
5. Reorder tokens to `FFFFFF-RRRRRR-NN-II-LL-AA`.
6. Expand short/medium/full input using defaults (II=`00`, LL=`EN`, AA=`00`).
7. Persist only the canonical 6-token `lupopedia_id`.
8. Zero-pad federation IDs. Never emit FF `000000` or `FFFFFF`.
9. Accept LL=`ZZ` as multi-language / language-agnostic. Do **not** rewrite `ZZ` to an ISO 639-1 code. Do **not** treat `ZZ` as a real ISO language.
10. Run `python bin/tick.py`. Set `header_format_version: "4.2.3"` only after reconstruct succeeds.

### 8.3 Backward compatibility

- Readers MUST accept 4.2.2 six-field IDs in **migration mode** only.
- Readers MUST accept 3/4/6 token short forms as **input**, then expand.
- Writers MUST emit canonical 6-token IDs. Color stays metadata.
- Writers MAY emit LL=`ZZ` for multi-language artifacts. Writers MUST NOT rewrite `ZZ` to an ISO code.
- `group_id` is retired. `actor_hex` stays metadata.

### 8.4 Federation after 4.2.3

```text
LUP:000001-000000-01-00-EN-01
->
LUP:000003-000000-01-00-EN-01
```

Only FFFFFF (FEDERATION) changes. Do **not** insert a colon for unmodified publish.

### 8.5 RRRRRR lineage colon (cross-federation modification)

Colon `:` is the official lineage delimiter inside RRRRRR.

1. Native IDs without a colon stay valid. Do not rewrite them.
2. IDs that used any other lineage joiner (`X` as delimiter, `/`, `_`) MUST migrate to `originFed:artifactNumber`.
3. Validators MUST reject any lineage delimiter other than `:`.
4. Split RRRRRR on the first colon. Left = origin federation (6 hex after expand). Right = artifact number (6 hex).
5. Colon MUST NOT appear anywhere else in the identity except the `LUP:` prefix.
6. Unmodified federation publish still changes only FFFFFF.

```text
LUP:000002-123456-01-00-EN-01
-> modified in Fed 3
LUP:000003-000002:123456-01-00-EN-01
```

## 9. Header 4.2.3 -> 4.2.4 (Federation Compression Rule -- Option A)

Normative companion: PRD 16_C section 4.2.5.

**Federation 000001 is the canonical root node. In short-form identities, it is compressed to the symbol `X`.**

```text
machine:         LUP:000001-RRRRRR-NN-II-LL-AA
human-friendly:  LUP:X-RRRRRR-NN-II-LL-AA
short:           LUP:X-RRRRRR-NN
```

### 9.1 Dual-acceptance

| Declared `header_format_version` | Identity | Validator |
|----------------------------------|----------|-----------|
| 4.2.4 (new artifacts) | Machine 6-token on disk (`000001`) | Accept `X` on input; expand; ERROR if `X` left on disk as machine ID |
| 4.2.3 (existing) | Same machine grammar | WARN to declare 4.2.4 when next edited |
| Human input (any) | 3/4/5/6 tokens; FF may be `X` or six hex | Expand `X` -> `000001`; persist machine |

### 9.2 Per-file steps (4.2.4)

1. Keep dense 28-field grid, Hawaiian fields, Rule 99 color bands, CC-BY, namespace/actor/language/artifact rules **unchanged**.
2. Keep machine `lupopedia_id` as `LUP:000001-...` (six-hex FF). Do **not** write `X` to disk.
3. Update human-facing docs, diagrams, and short-form examples to use `X` for root federation.
4. Ensure migration / export tools call expand(`X`)->`000001` before persistence.
5. Ensure human-friendly printers compress only `000001` -> `X`.
6. Never compress any other federation to `X`.
7. Run `python bin/tick.py`. Set `header_format_version: "4.2.4"` after reconstruct succeeds.
8. Validate with `python scripts/validate_lup_identity.py PATH`.

### 9.3 Backward compatibility

- Readers MUST accept `X` as input and expand to `000001`.
- Writers MUST emit machine six-hex `000001` for root federation on disk.
- Writers MAY emit `X` only in human-friendly / short-form **display** modes.
- Other federations remain six-hex in all modes.

## 10. Header 4.2.4 -> 4.2.11 (federation map + dotted KEY)

Normative: PRD 16_C section 4.2.6. Template: `docs/prd/federation/federation_map_template.md`.

**Do not mass-rewrite the corpus.** Upgrade a file when it is otherwise edited.

Versions 4.2.3-4.2.10 were compiled outside this Cursor workspace. In-repo identity last compiled at 4.2.4. 4.2.11 is the next Cursor-indexed header contract.

### 10.1 Per-file steps (4.2.11)

1. Keep the 28-field dense grid unchanged. Hawaiian fields unchanged. Rule 99 bands unchanged. CC-BY name unchanged.
2. Set `header_format_version: "4.2.11"`.
3. Replace hyphen `lupopedia_id` with KEY identity. No hyphens in KEY / HEX / SHORT / ROOT.
4. Field delimiter is `.` (ASCII 46). Reject middle-dot and pipe.
5. Add `lupopedia.map` with `index` = this document's LUP.HEX.
6. Keep `lupopedia.metadata` to `media_kind` + `cc_by_name`. Do not copy the dense grid into metadata.
7. Run `python bin/tick.py`. Validate with `python scripts/validate_lup_identity.py PATH`.

```text
LUP.KEY = PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
LUP.HEX = PRT.HEX.000000.000000.000000.EN.04020A
LUP.SHORT = PRT.LUP
LUP.ROOT = PRT.LUP.ROOT.ROOT.EN.042010
```