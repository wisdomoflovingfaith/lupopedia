---
lupopedia.headers:
  header_format_version: "4.2.11"
  path_from_lupopedia_root: docs/doctrine/lupopedia_identity_routing_rule.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/lupopedia_identity_routing_rule.md
  status: experimental
  when_updated: "20260816175531"
  trust_tier: proposed
  questions_toon: null
  memory_toon: memory/development/canonical/1026/08/lupopedia_identity_routing_rule.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/lupopedia-identity-routing-rule
  artifact_type: doctrine
  artifact_kind: experimental
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: documentation
  prd_cluster: 00_A_00_B_00_C_16_C
  title: "LUPOPEDIA Identity Routing Rule -- LUPxPEDIA two-tier numeric mapping (experimental)"
  summary: "Experimental display-layer helper. Case A: one digit 0-9 maps to PRD 9X and local X. Two digits 10-99 map to PRD XX and local XX. Ordinary LUPOPEDIA is LUP0PEDIA: PRD 90, local 0. Does not change the KEY. Does not mint PRD 90."
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
  LUP.HEX: PRT.HEX.000001.000025.000000.ROOT.EN.000001
  LUP.SHORT: PRT.LUP
  LUP.ROOT: PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
  LUP.OMIT: REGISTERED_SHORT_FORMS_ONLY
  LUP.DEFAULTS: PRT.NAME.000000.000000.ROOT.ROOT.EN.0
  key_specification_version: "4.2.26"
lupopedia.map:
  index: PRT.HEX.000001.000025.000000.ROOT.EN.000001
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/lupopedia_identity_routing_rule.md
  path_from_lupopedia_root: docs/doctrine/lupopedia_identity_routing_rule.md
  prd_cluster: 00_A_00_B_00_C_16_C
  edges_toon: null
  memory_toon: memory/development/canonical/1026/08/lupopedia_identity_routing_rule.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/lupopedia-identity-routing-rule
  questions_toon: null
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
---
# LUPOPEDIA Identity Routing Rule

**Official article. Experimental doctrine.**

**Status:** experimental / proposed. Not a PRD. Does not override PRD 00, PRD 16_C, or the LUP KEY.

**Anchors:** PRD 16_C (headers and KEY). High-order identity block is the 90-series (Case A). Product atom remains `4.2.11`. Color Identity doctrine: PRD 90.

---

## Purpose

The LUPOPEDIA Identity Routing Rule embeds PRD routing in the spelling of the system name. The name tells every actor which **PRD family** to open, plus a **local** number.

This is a two-tier mapping. It is not random. It is not slang. It is not a KEY change.

It is an **actor-instruction / display** rule. It is not identity storage.

---

## Core rule

```text
LUPxPEDIA  ->  (PRD family, local)
```

The character in the "O" position of the word (between `LUP` and `PEDIA`) is treated as a numeric value. Digit count chooses the tier.

---

## LUPxPEDIA numeric mapping (Case A)

### Rule 1 -- Single-digit values (0-9)

If the LUPxPEDIA number is one digit, map it to:

```text
PRD 9X
local X
```

Where `X` is that digit.

| LUPxPEDIA | PRD family | local |
|-----------|------------|-------|
| 0 | 90 | 0 |
| 1 | 91 | 1 |
| 5 | 95 | 5 |

Single digits = doctrine block (90-99). High-order identity rules live in the 90-series.

### Rule 2 -- Multi-digit values (10-99)

If the LUPxPEDIA number is two digits, map it to:

```text
PRD XX
local XX
```

Where `XX` is the number itself.

| LUPxPEDIA | PRD family | local |
|-----------|------------|-------|
| 22 | 22 | 22 |
| 16 | 16 | 16 |
| 80 | 80 | 80 |

Two digits = direct PRD mapping. Existing PRD families stay as they are.

---

## PRD family lookup (simple)

After Case A mapping, the PRD family is a number **00 to 99**.

Letters (A-F) and roman numerals (i, ii, iii, ...) inside the filename are **not** part of the routing number.

Open the first file in that family (`90_A`, `22_A`, ...). Open the next letter in the same family only if needed.

This is **one family lookup**. It is not a cluster. It is not a multi-PRD chain. It is not recursive.

If that family does not exist, do not validate, do not error, do not fall back. Return only:

```text
No PRD family found.
```

`local` is the original LUPx number (one digit or two). It is not a second PRD hop. It is not recursive.

---

## Default assumption (most important rule)

When the word is written in its normal form -- **LUPOPEDIA** -- the letter "O" is treated as zero.

```text
LUPOPEDIA  =  LUP0PEDIA  =  PRD 90, local 0
```

Any time an actor, document, or system sees the ordinary spelling LUPOPEDIA, Case A Rule 1 applies: family **90**, local **0**.

---

## Explicit forms

| Spelling | LUPx | PRD family | local | Start here |
|----------|------|------------|-------|------------|
| LUPOPEDIA | 0 | 90 | 0 | 90_A |
| LUP0PEDIA | 0 | 90 | 0 | 90_A |
| LUP1PEDIA | 1 | 91 | 1 | 91_A |
| LUP5PEDIA | 5 | 95 | 5 | 95_A |
| LUP16PEDIA | 16 | 16 | 16 | 16_A |
| LUP22PEDIA | 22 | 22 | 22 | 22_A |
| LUP80PEDIA | 80 | 80 | 80 | 80_A |

Multi-digit numbers are required once the value is 10 or higher.

The digit string replaces the single letter O. Do not write `LUPO16PEDIA` (that keeps O and then extra digits). Write `LUP16PEDIA`.

---

## Formal rules

### 1. Default Zero Rule

The ordinary English spelling LUPOPEDIA is always interpreted as LUP0PEDIA. Case A maps that to PRD 90 and local 0.

### 2. Explicit Digit Rule

Count the digits in the O position.

- One digit (0-9): PRD `9X`, local `X`.
- Two digits (10-99): PRD `XX`, local `XX`.

Letters and roman numerals in PRD filenames are ignored for routing.

If the mapped family is missing, return `No PRD family found.` Do not guess. Do not fall back.

### 3. Routing Priority

When the name itself is the signal, apply Case A, then open that family first (`nn_A`, then `nn_B` only if needed). Carry `local` with the instruction. Do not treat `local` as another family lookup.

This does not change header `prd_cluster`. This does not create a cluster or a chain.

### 4. Display vs canonical KEY

This routing form lives at the human-readable / actor-instruction layer.

It does **not** alter the eight-token LUP KEY grammar:

```text
PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
```

Digits in LUPxPEDIA are not KEY tokens. They are not MODE, not NODE, not VERSION packing. Storage, comparison, federation, hashing surfaces, and APIs still use the complete eight-token KEY.

`LUP.SHORT` remains `PRT.LUP`. Do not store `LUP16PEDIA` as a KEY.

### 5. Brand continuity

The product and brand name remains "Lupopedia".

The digit form is a routing signal, not a rename of the system.

### 6. Case and formatting

Write the routing form in uppercase when the semantic pointer is intentional:

```text
LUPOPEDIA
LUP0PEDIA
LUP1PEDIA
LUP16PEDIA
LUP80PEDIA
```

Lowercase `lupopedia` in prose still means the product. Treat it as LUP0PEDIA (PRD 90, local 0) unless a document is clearly using a digit form.

### 7. What this is not

- Not a cluster
- Not a multi-PRD chain
- Not recursive lookup
- Not a LUP KEY
- Not a color name
- Not a Hawaiian constitutional field
- Not a filename convention for repo files (filenames stay lowercase underscore)
- Not permission to skip PRD-first review for new features
- Not a substitute for `header_format_version`, `prd_cluster`, or `lupopedia.map`

---

## Why this exists

Lupopedia is PRD-first. Actors must know which doctrinal root to load. Ambiguity creates misalignment, incorrect templates, and broken federation behavior.

By encoding the required root in the name, the system gains:

- Immediate visual routing
- Reduced cognitive load
- Consistent multi-actor behavior
- Human-readable doctrine
- Zero additional infrastructure (no extra table, no extra KEY token)

---

## Captain's summary

Most of the time the system will simply say LUPOPEDIA.

Under Case A, that single word already means:

**PRD 90, local 0.** Open `90_A` first if that family exists. If it does not exist, return `No PRD family found.`

One digit goes to the 90-series. Two digits go to that PRD family directly.

This is a display-layer helper. The KEY does not change. Color Identity doctrine is PRD 90.

---

## Authority

| Surface | Wins |
|---------|------|
| Eight-token KEY | PRD 16_C + PRT.LUP.md (4.2.26 expansion) |
| Constitutional system | PRD 00 still exists as the 00 family. Case A does not delete it. Ordinary spelling LUPOPEDIA now maps to PRD 90, not PRD 00. |
| This routing spelling | This file (experimental, Case A). If it conflicts with a minted PRD, the PRD wins. |

If the named family does not exist, return `No PRD family found.`

---

## Related

- PRD 00 group (still a real family; not the LUPOPEDIA default under Case A): `docs/prd/00_A-i_FORBIDDEN_AND_WHY.md`
- Color Identity doctrine: `docs/prd/90_A-i_COLOR_IDENTITY_DOCTRINE.md`
- Draft tables (thinking): `docs/prd/01_B-i_COLOR_REGISTRY.md`
- Placement log: `content/federation_node/0/captains_log/origin_stories_architure/2026/08/20260816_choosing_the_prd_number_for_color_identity_doctrine.md`
- KEY: `docs/prd/16_C-i_LUPOPEDIA_HEADERS.md` section 4.2.6
- Protocol: `docs/protocols/lup/PRT.LUP.md`
- PRD index: `docs/prd/prd_index.md`
- Actor routing (messages, not this spelling rule): `docs/actors/actor_routing_rules.md`
