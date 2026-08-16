---
lupopedia.headers:
  header_format_version: "4.2.11"
  path_from_lupopedia_root: docs/doctrine/lupopedia_identity_routing_rule.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/lupopedia_identity_routing_rule.md
  status: experimental
  when_updated: "20260816123123"
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
  title: "LUPOPEDIA Identity Routing Rule -- LUPxPEDIA to PRD x (experimental)"
  summary: "Experimental display-layer helper. Ordinary spelling LUPOPEDIA means PRD family 00. Digit forms such as LUP16PEDIA mean family 16. Letters and roman numerals in filenames are not the routing number. Does not change the KEY."
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

**Anchors:** PRD 00 (constitutional root), PRD 16_C (headers and KEY). Product atom remains `4.2.11`.

---

## Purpose

The LUPOPEDIA Identity Routing Rule embeds PRD family lookup in the spelling of the system name. The name tells every actor which **PRD family** (00-99) to open first.

This rule exists to reduce ambiguity, prevent misrouting, and keep multi-actor work aligned with the correct doctrinal foundation.

It is an **actor-instruction / display** rule. It is not identity storage.

---

## Core rule

```text
LUPxPEDIA  ->  PRD x
```

The character in the "O" position of the word (between `LUP` and `PEDIA`) is treated as a numeric value. That number is the PRD **family** to open first. The spelling is the instruction.

---

## PRD family lookup (simple)

PRD files are numbered **00 to 99**.

That two-digit number is the **family**. Letters (A-F) and roman numerals (i, ii, iii, ...) inside the filename are **not** part of the routing number.

```text
00  ->  the 00 family
16  ->  the 16 family
```

Open the first file in that family (`00_A`, `16_A`, ...). Open the next letter in the same family (`00_B`, `16_B`, ...) only if needed.

This is **one family lookup**. It is not a cluster. It is not a multi-PRD chain. It is not recursive.

If that family does not exist, do not validate, do not error, do not fall back. Return only:

```text
No PRD family found.
```

---

## Default assumption (most important rule)

When the word is written in its normal form -- **LUPOPEDIA** -- the letter "O" is treated as zero.

```text
LUPOPEDIA  =  LUP0PEDIA  =  PRD 00
```

This is the constitutional default.

Any time an actor, document, or system sees the ordinary spelling LUPOPEDIA, it must assume the family is **00**. Open `00_A` first. Open `00_B` only if needed.

---

## Explicit forms

| Spelling | Family | Start here | Next only if needed |
|----------|--------|------------|---------------------|
| LUPOPEDIA | 00 | 00_A | 00_B, ... |
| LUP0PEDIA | 00 | 00_A | 00_B, ... |
| LUP1PEDIA | 01 | 01_A | 01_B, ... |
| LUP2PEDIA | 02 | 02_A | 02_B, ... |
| LUP16PEDIA | 16 | 16_A | 16_B, ... |
| LUP80PEDIA | 80 | 80_A | 80_B, ... |

Multi-digit numbers are allowed and preferred once the PRD number is 10 or higher.

The digit string replaces the single letter O. Do not write `LUPO16PEDIA` (that keeps O and then extra digits). Write `LUP16PEDIA`.

---

## Formal rules

### 1. Default Zero Rule

The ordinary English spelling LUPOPEDIA is always interpreted as LUP0PEDIA and therefore points to PRD 00.

### 2. Explicit Digit Rule

When a digit or digit sequence appears in the O position, that number is the PRD family (00-99). Letters and roman numerals in the filename are ignored for routing.

If the family is missing, return `No PRD family found.` Do not guess. Do not fall back.

### 3. Routing Priority

When the name itself is the signal, open that family first (`nn_A`, then `nn_B` only if needed).

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

Lowercase `lupopedia` in prose still means the product. Treat it as the default (PRD 00) unless a document is clearly using a digit form.

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

Under this rule, that single word already means:

**Open the 00 family first (`00_A`, then `00_B` only if needed).**

When a different family is required, the spelling itself changes so the instruction is visible.

This is a display-layer helper. The KEY does not change. The name tells you which PRD family to open.

---

## Authority

| Surface | Wins |
|---------|------|
| Eight-token KEY | PRD 16_C + PRT.LUP.md (4.2.26 expansion) |
| Constitutional system | PRD 00 |
| This routing spelling | This file (experimental). If it conflicts with a PRD, the PRD wins. |

If the named family does not exist, return `No PRD family found.`

---

## Related

- PRD 00 group: `docs/prd/00_A-i_FORBIDDEN_AND_WHY.md`, `docs/prd/00_B-i_SYSTEM_CANONICAL_EXPLANATION.md`, `docs/prd/00_C-i_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md`
- KEY: `docs/prd/16_C-i_LUPOPEDIA_HEADERS.md` section 4.2.6
- Protocol: `docs/protocols/lup/PRT.LUP.md`
- PRD index: `docs/prd/prd_index.md`
- Actor routing (messages, not this spelling rule): `docs/actors/actor_routing_rules.md`
