---
lupopedia.headers:
  header_format_version: "4.2.11"
  path_from_lupopedia_root: LUPOPEDIA.FOR.EXTERNAL.DOMAINS.md
  web_path: https://www.lupopedia.com/lupopedia/LUPOPEDIA.FOR.EXTERNAL.DOMAINS.md
  status: active
  when_updated: "20260816102614"
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/lupopedia-for-external-domains
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: documentation
  prd_cluster: 16_C_34_A_82_B
  title: "Lupopedia identity for external domains (KEY 4.2.26 on contract 4.2.11)"
  summary: "Self-contained external guide to the LUP KEY. Eight dotted tokens always. Colon-bag is not LUP. Artifact packed VERSION 04021A is 4.2.26. Header contract remains 4.2.11 / 04020B."
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
  LUP.HEX: PRT.HEX.000001.000022.000000.ROOT.EN.04021A
  LUP.SHORT: PRT.LUP
  LUP.ROOT: PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
  LUP.OMIT: REGISTERED_SHORT_FORMS_ONLY
  LUP.DEFAULTS: PRT.NAME.000000.000000.ROOT.ROOT.EN.0
  key_specification_version: "4.2.26"
lupopedia.map:
  index: PRT.HEX.000001.000022.000000.ROOT.EN.04021A
  web_path: https://www.lupopedia.com/lupopedia/LUPOPEDIA.FOR.EXTERNAL.DOMAINS.md
  path_from_lupopedia_root: LUPOPEDIA.FOR.EXTERNAL.DOMAINS.md
  prd_cluster: 16_C_34_A_82_B
  edges_toon: null
  memory_toon: null
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/lupopedia-for-external-domains
  questions_toon: null
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
---
# file: LUPOPEDIA.FOR.EXTERNAL.DOMAINS -- session: L-LUPO-WOLFIE -- delegation: wolfie:root -- web_path: https://www.lupopedia.com/lupopedia/LUPOPEDIA.FOR.EXTERNAL.DOMAINS.md

# Lupopedia identity for external domains

This article explains the **Lupopedia Identity System** to developers who are not inside the Lupopedia repo.

Start here if you need more than the one-page readme. You do not need Lupopedia internals, PHP classes, or the database schema to use a LUP KEY.

**Start short:** [lupopedia.protocal.readme.txt](lupopedia.protocal.readme.txt)

**Normative in-repo sources:**

- Header / map contract: [docs/prd/16_C-i_LUPOPEDIA_HEADERS.md](docs/prd/16_C-i_LUPOPEDIA_HEADERS.md) section 4.2.6
- Protocol article: [docs/protocols/lup/PRT.LUP.md](docs/protocols/lup/PRT.LUP.md)
- Detailed KEY spec: [PRT.HEX.000000.000000.000000.ROOT.EN.04020B.md](PRT.HEX.000000.000000.000000.ROOT.EN.04020B.md)
- Map template: [docs/prd/federation/federation_map_template.md](docs/prd/federation/federation_map_template.md)

**Versions (read this once):**

| Label | Meaning |
|-------|---------|
| Header contract | `header_format_version: "4.2.11"` -- dense headers, identity, map |
| KEY specification | `key_specification_version: "4.2.26"` -- expansion, registered shorts, packed VERSION |
| This guide's KEY VERSION | **04021A** -- packed 4.2.26 (`04` `02` `1A`). Content/meaning of this artifact. |
| Product atom | `GLOBAL_CURRENT_LUPOPEDIA_VERSION` is still **4.2.11** |
| Packed 4.2.11 example | **04020B** -- header-contract packing, used by the zeros spec filename |

The header contract can stay 4.2.11 without forcing every artifact KEY VERSION to stay `04020B`. This guide's eight-token HEX is `PRT.HEX.000001.000022.000000.ROOT.EN.04021A`.

---

## 1. Why this exists

Filenames, tags, and UUIDs drift. Two systems can mean different things by the same string.

LUP gives every artifact one identity that already contains:

- which protocol governs it
- whether the ID is human words or machine hex
- which federation node
- which artifact
- which actor
- which group
- which language
- which version

If you can parse eight dotted tokens, you can route, store, and compare identities without a Lupopedia database.

That is the same job ISBN does for books and DNS does for hosts. LUP does it for artifacts.

---

## 2. The KEY (normative)

```text
LUP.KEY = PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
```

Exactly **eight** dot-separated tokens. Position determines meaning. Order is fixed.

```text
SLUG    = [A-Z0-9_]{1,22}
HEX6    = [0-9A-F]{6}
VERSION = 0 | [0-9A-F]{6}
```

Valid slug examples: `LUP`, `LUPDOC`, `WOLFIE`, `MY_ARTIFACT_01`.
Invalid slug examples: `my-artifact` (hyphen), `Lup` (lowercase), a string longer than 22 characters.

| # | Token | Allowed values |
|---|-------|----------------|
| 1 | PROTOCOL | Registered protocol (`PRT`, `CCB`, ...) |
| 2 | MODE | `NAME` or `HEX` |
| 3 | NODE | 6-hex `000000` through `FFFFFE`. Live federation nodes begin at `000001`. `000000` is reserved for examples and unspecified artifacts. |
| 4 | ARTIFACT | Native: SLUG or HEX6. Lineage form: `HEX6:HEX6` only (`originNode:artifactNumber`). Origin (left) is HEX6, not a slug. Right side is HEX6. At most one colon. |
| 5 | ACTOR | slug or 6-hex (default `ROOT`) |
| 6 | GROUP | slug (default `ROOT`) |
| 7 | LANGUAGE | ISO 639-1 or `ZZ` (default `EN`) |
| 8 | VERSION | `0` or 6-hex packed version |

**Parse hint:** NODE is always 6-hex. If the token after PROTOCOL (or after MODE) is a slug such as `LUP`, it is ARTIFACT, not NODE. That is why `PRT.LUP` means artifact `LUP` on unspecified node `000000`.

### 2.1 Delimiters

- Field delimiter: ASCII dot `.` (code 46)
- No pipes
- No hyphens
- No middle-dot characters
- Colon `:` only inside **ARTIFACT**, at most once
- Native ARTIFACT (no colon): SLUG or HEX6
- Lineage form: `HEX6:HEX6` (`originNode:artifactNumber`)
- Origin (left) must be HEX6 -- not a slug
- Right side must be HEX6
- When lineage is present, origin must differ from the current NODE (4.2.26 clarification of existing validator rule `HDR_LUP_RR_ORIGIN`; native artifacts have no colon)

### 2.2 Defaults (4.2.26)

```text
LUP.DEFAULTS = PRT.NAME.000000.000000.ROOT.ROOT.EN.0
```

NAME fill: NODE / ARTIFACT = `000000`. ACTOR / GROUP = `ROOT`. LANGUAGE = `EN`. VERSION = `0`.
HEX fill: NODE / ARTIFACT / ACTOR = `000000`. GROUP = `ROOT`. LANGUAGE = `EN`. VERSION = `0`.

`PRT.LUP` is **not** `PRT.NAME.PRT.LUP.ROOT.ROOT.EN.0`. NODE is 6-hex. The registered SHORT_ARTIFACT expansion is `PRT.NAME.000000.LUP.ROOT.ROOT.EN.0`.

---

## 3. Short form, expansion, full form

Short forms are **registered productions only**. Arbitrary "omit any default field" is forbidden.

A positional grammar cannot drop middle fields. `PRT.LUP.WHEEL` cannot choose ACTOR vs GROUP. `PRT.HEX.000022` is NODE only because SHORT_MODE_NODE is registered; it is not ARTIFACT.

**Display** may use a registered short. **Storage, comparison, federation, hashing, and APIs always use the complete eight-token KEY.**

### 3.1 Algorithm (Option A)

1. Split on `.`. Empty tokens are invalid.
2. Match **exactly one** production below (first match that fits the token count and MODE precedence).
3. Fill missing slots from the NAME or HEX fill table.
4. Require eight tokens. Then apply the reject list in section 6.

MODE precedence: if token 2 exists and is exactly `NAME` or `HEX`, token 2 is MODE. Those two strings are reserved. They are not artifact slugs.

| Production | Tokens | Example in | Example out |
|------------|--------|------------|-------------|
| SHORT_PROTOCOL | 1 | `PRT` | `PRT.NAME.000000.000000.ROOT.ROOT.EN.0` |
| SHORT_MODE | 2, token2 is NAME or HEX | `PRT.HEX` | `PRT.HEX.000000.000000.000000.ROOT.EN.0` |
| SHORT_ARTIFACT | 2, token2 is not NAME or HEX | `PRT.LUP` | `PRT.NAME.000000.LUP.ROOT.ROOT.EN.0` |
| SHORT_MODE_NODE | 3 | `PRT.HEX.000001` | `PRT.HEX.000001.000000.000000.ROOT.EN.0` |
| SHORT_MODE_NODE_ARTIFACT | 4 | `PRT.NAME.000001.00ABCD` | `PRT.NAME.000001.00ABCD.ROOT.ROOT.EN.0` |
| FULL_KEY | 8 | any complete KEY | as written |

Any other shape is invalid. Do not guess.

Worked example -- registered short:

```text
PRT.LUP
```

Stored FULL_KEY:

```text
PRT.NAME.000000.LUP.ROOT.ROOT.EN.0
```

Worked example -- `LUP.ROOT` (already eight tokens, packed 4.2.10):

```text
PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
```

Worked example -- this guide's machine HEX (eight tokens, packed 4.2.26):

```text
PRT.HEX.000001.000022.000000.ROOT.EN.04021A
```

Worked example -- zeros spec filename / protocol example (eight tokens, packed 4.2.11 contract):

```text
PRT.HEX.000000.000000.000000.ROOT.EN.04020B
```

Do not store seven-token HEX strings. GROUP is part of the KEY.

---

## 4. Version semantics

Keep these four labels separate:

| Label | Example |
|-------|---------|
| `header_format_version` | `4.2.11` |
| `key_specification_version` | `4.2.26` |
| Artifact KEY VERSION (E8) | this guide `04021A`; zeros spec `04020B` |
| Product atom | `4.2.11` |

Packed VERSION is `0xMMmmPP`:

```text
4.2.10 -> 04020A
4.2.11 -> 04020B
4.2.26 -> 04021A
```

`0` = unversioned artifact. Any packed value is greater than `0`.

E8 is the **artifact's content version**. Increment it when this artifact's content or meaning changes. Do not increment E8 for metadata-only edits. Do not reuse `04020B` as this guide's E8; that packing is the header contract, not this file's revision.

`042010` is not a packed version. Packed 4.2.10 is `04020A`.

---

## 5. Historical linking

Do not embed a full KEY inside another KEY token. Extra dots land in the wrong positions.

1. **Primary:** graph edges (`edges_toon`, PARENT / remix / translation relations)
2. **Secondary (federation only):** colon lineage inside ARTIFACT as `HEX6:HEX6` (`originNode:artifactNumber`). Origin is HEX6, not a slug.

Native artifacts have no colon.

---

## 6. Validation (reject, do not guess)

After expansion, reject the string if:

- the input is not FULL_KEY and does not match a registered short-form production (section 3.1)
- token count after expansion is not 8
- any token contains hyphen, pipe, or middle-dot
- MODE is `HEX` and NODE / ARTIFACT / ACTOR are not valid 6-hex (ARTIFACT may still carry one colon lineage)
- ARTIFACT has more than one colon, or a non-hex origin, or a right side that is not 6-hex
- lineage is present and origin 6-hex equals the current NODE (`HDR_LUP_RR_ORIGIN` -- 4.2.26 clarification of existing lineage validation)
- LANGUAGE is not two letters or `ZZ`
- VERSION is neither `0` nor 6-hex
- PROTOCOL is empty, or not in your local registry of accepted protocols (see below)

**Unknown PROTOCOL:** "Unknown" means the token is not in **your local registry**. An external domain should accept only protocols it is prepared to route. The Lupopedia registry starts with `PRT` (this protocol). Other registered protocols (example `CCB`) live under `docs/protocols/` with a protocol article. A well-formed eight-token string that uses a PROTOCOL you do not register is not a LUP KEY **for your domain**. Do not guess a mapping.

A string that fails these checks is not a LUP KEY. Return an error. Do not invent a "close enough" parse.

---

## 7. What the KEY is not

| Not this | Why |
|----------|-----|
| Color / RGB / gold names | Color lives in `docs/protocols/hex/<PROTOCOL>/<PROTOCOL>.colors.csv` |
| Hawaiian constitutional terms (KAPU, PONO, ALII, ...) | Routing / ethics in PRD 82_B, not identity |
| Media kind (song, image, video) | `lupopedia.metadata.media_kind` and variant IDs such as `MUS.01.EN` |
| Human title | `lupopedia.headers.title` |
| ISO-8601 timestamps | BIGINT UTC `YYYYMMDDHHIISS` in headers / columns, not in the KEY |
| UUID / random IDs | LUP IDs are positional and deterministic |
| Colon-bag grammar | Rejected. Not part of LUP. See section 8. |

---

## 8. Colon-bag is not LUP

Drafts that joined named protocols with colons:

```text
IDENTITY.HEX.D4AF37.GOLD:LANGUAGE.EN.US:ITERATION.02.00.00
```

and treated top-level order as irrelevant, are **not** the LUP KEY.

That family (sometimes labeled 04.02.xx "colon-bag") was tried, reviewed, and **rejected**. Do not:

- join KEY fields with colons
- sort protocols alphabetically
- put color hex in the identity
- treat VERSION as decimal `4.1.0` inside the KEY
- document the colon-bag as a second live LUP system

The only colon in a LUP KEY is optional lineage **inside ARTIFACT**.

---

## 9. Using LUP IDs outside Lupopedia

### 9.1 Store the expanded KEY

Store the **eight-token** form in APIs, databases, and indexes:

```text
PRT.HEX.000001.000022.000000.ROOT.EN.04021A
```

You may *display* `PRT.LUP` to humans. Expand before compare, hash, or foreign lookup.

Column type: a string wide enough for 8 tokens plus one optional ARTIFACT colon (a `VARCHAR` / `TEXT` analogue). Do not use a UUID type. Do not split the KEY into eight SQL columns unless you also store the canonical joined string.

### 9.2 Compare after expansion

Two short forms are the same ID if they expand to the same eight tokens.

`PRT.LUP` and `PRT.NAME.000000.LUP.ROOT.ROOT.EN.0` are the same unversioned identity.

### 9.3 HTTP / path use

Prefer the HEX form in URLs. Dots are the delimiter; do not encode them as something else if you can avoid it. Do not replace dots with hyphens (hyphens are illegal in KEY tokens).

### 9.4 JSON

One string field, for example `lup_key` or `lup_hex`. Do not explode the eight tokens into a JSON object unless you also echo the canonical dotted string.

```text
"lup_key": "PRT.HEX.000001.000022.000000.ROOT.EN.04021A"
```

### 9.5 What you do not need

You do not need Composer, an ORM, or Lupopedia PHP to validate a KEY. Match a registered production (section 3.1), expand to eight tokens, then apply the reject list in section 6.

---

## 10. Header envelope (when you author Lupopedia files)

External domains that only *carry* a LUP KEY can stop at section 9.

If you author files *inside* a Lupopedia tree, each new Markdown file also has:

1. `lupopedia.headers` -- 28 discovery scalars (path, title, actor, ...)
2. `lupopedia.identity` -- the KEY constants plus this file's HEX
3. `lupopedia.map` -- `index` must equal this file's `LUP.HEX`
4. `lupopedia.metadata` -- `media_kind` and `cc_by_name` only

Those blocks are file metadata. They are not extra KEY tokens.

---

## 11. Normative file map

| Need | File |
|------|------|
| One-page start | [lupopedia.protocal.readme.txt](lupopedia.protocal.readme.txt) |
| This article | [LUPOPEDIA.FOR.EXTERNAL.DOMAINS.md](LUPOPEDIA.FOR.EXTERNAL.DOMAINS.md) |
| Header contract | [docs/prd/16_C-i_LUPOPEDIA_HEADERS.md](docs/prd/16_C-i_LUPOPEDIA_HEADERS.md) |
| Protocol narrative | [docs/protocols/lup/PRT.LUP.md](docs/protocols/lup/PRT.LUP.md) |
| Whitepaper v1.9.2 | [docs/protocols/lup/lupopedia_whitepaper_v1_9_2.md](docs/protocols/lup/lupopedia_whitepaper_v1_9_2.md) |
| Full KEY spec | [PRT.HEX.000000.000000.000000.ROOT.EN.04020B.md](PRT.HEX.000000.000000.000000.ROOT.EN.04020B.md) |
| Color (not KEY) | [docs/protocols/hex/HEX.COLORS.md](docs/protocols/hex/HEX.COLORS.md) |
| Hawaiian fields (not KEY) | [docs/prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md](docs/prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md) |

This article does not override PRDs. If a PRD and this guide disagree on headers or map shape, the PRD wins. If a short readme and this guide disagree on KEY expansion, **4.2.26 expansion in this file and in `lupopedia.protocal.readme.txt` wins** over older `PRT.NAME.PRT.LUP...` examples.

## VARIANTS INDEX

Navigation-only list of TYPE.LANG variants of this artifact. Not doctrine. Does not override PRDs. Does not change header authority.

### MUSIC VARIANTS

- (none yet)

### VIDEO VARIANTS

- (none yet)

### WEB VARIANTS

- (none yet)

### DOCUMENT VARIANTS

- (none yet)

### NOTES

- This section is reserved for future variant indexes. Currently empty.
- Variant indexes are navigation-only.
- Do not modify lupopedia.headers, lupopedia.metadata, or lupopedia.map.
- ASCII-safe dot grammar only. No pipes. No middle-dot. No hyphens in variant IDs.
