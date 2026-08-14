---
lupopedia.headers:
  header_format_version: "4.2.11"
  path_from_lupopedia_root: docs/actors/how_to_make_variant_indexes.md
  web_path: https://www.lupopedia.com/lupopedia/docs/actors/how_to_make_variant_indexes.md
  status: active
  when_updated: "20260814142913"
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/root/how-to-make-variant-indexes
  artifact_type: documentation
  artifact_kind: guide
  channel_key: root
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: documentation
  prd_cluster: 16_C_15_A
  title: "How to make variant indexes -- navigation-only TYPE.LANG lists"
  summary: "Operator guide for VARIANTS INDEX body sections. Navigation only. Does not modify headers, metadata, or map. Header contract remains PRD 16_C 4.2.11."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 10000
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: root
  faucet_actor_id: 102
lupopedia.identity:
  LUPOPEDIA: PRT.LUP
  LUP.KEY: PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
  LUP.HEX: PRT.HEX.000001.000016.000000.EN.04020A
  LUP.SHORT: PRT.LUP
  LUP.ROOT: PRT.LUP.ROOT.ROOT.EN.042010
  LUP.OMIT: MODE_WHEN_NAME + ANY_DEFAULT_FIELD
  LUP.DEFAULTS: PRT.NAME.PRT.LUP.ROOT.ROOT.EN.0
lupopedia.map:
  index: PRT.HEX.000001.000016.000000.EN.04020A
  web_path: https://www.lupopedia.com/lupopedia/docs/actors/how_to_make_variant_indexes.md
  path_from_lupopedia_root: docs/actors/how_to_make_variant_indexes.md
  prd_cluster: 16_C_15_A
  edges_toon: null
  memory_toon: null
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/root/how-to-make-variant-indexes
  questions_toon: null
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
---
# How To Make Variant Indexes (Lupopedia, 4.2.11)

A Variant Index is a **navigation-only** section in the **body** of an artifact page. It lists known `TYPE.NUM.LANG` variants of that artifact.

It does **not** modify `lupopedia.headers`, `lupopedia.identity`, `lupopedia.map`, or `lupopedia.metadata`.

Header contract remains **PRD 16_C** (`header_format_version: "4.2.11"`). This guide does not create doctrine and does not override PRDs.

Cursor enforcement: `.cursor/rules/variant-index.mdc`

## Rules

- ASCII-safe only.
- Dot grammar only.
- No pipes.
- No middle-dot.
- No hyphens in variant IDs (and none in KEY / HEX / SHORT / ROOT).
- Does not create doctrine.
- Does not override PRDs.
- Does not modify header authority.
- Lives in the body of the artifact page, after the closing YAML `---`.
- Do not mass-rewrite the corpus. Add an index only on the file you are already writing.

## When to add one

Add `## VARIANTS INDEX` when the file is a **governed artifact page** that already declares `lupopedia.identity` and `LUP.HEX`.

Do **not** add one to:

- PRDs under `docs/prd/`
- doctrine under `docs/doctrine/`
- proposals under `docs/prd_proposals/`
- version changelogs under `docs/versions/`
- Cursor rules under `.cursor/`

If `## VARIANTS INDEX` already exists, do not duplicate it.

## Structure

### MUSIC VARIANTS

- MUS.01.EN  Description or link
- MUS.01.FR  Description or link

### VIDEO VARIANTS

- VID.01.EN  Description or link

### WEB VARIANTS

- WEB.01.EN  Description or link

### DOCUMENT VARIANTS

- DOC.01.EN  Description or link

## Identity format

```text
TYPE.NUM.LANG
```

Examples:

- MUS.01.EN
- VID.01.EN
- WEB.01.EN
- DOC.02.FR

This token is **not** `LUP.KEY`. Do not copy it into `lupopedia.identity`. Do not put color, Hawaiian constitutional fields, or dense header scalars into the variant ID.

## Purpose

Variant indexes help actors navigate related artifacts without implying authority, permission, or doctrinal weight.

## VARIANTS INDEX

Navigation-only list of TYPE.LANG variants of this artifact. Not doctrine. Does not override PRDs. Does not change header authority.

### MUSIC VARIANTS

- (none yet)

### VIDEO VARIANTS

- (none yet)

### WEB VARIANTS

- (none yet)

### DOCUMENT VARIANTS

- DOC.01.EN  This guide (`docs/actors/how_to_make_variant_indexes.md`)

### NOTES

- Variant indexes are navigation-only.
- Do not modify lupopedia.headers, lupopedia.metadata, or lupopedia.map.
- ASCII-safe dot grammar only. No pipes. No middle-dot. No hyphens in variant IDs.
