---
lupopedia.headers:
  header_format_version: "4.2.11"
  path_from_lupopedia_root: docs/prd/90_A-i_COLOR_IDENTITY_DOCTRINE.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/90_A-i_COLOR_IDENTITY_DOCTRINE.md
  status: active
  when_updated: "20260816175729"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/08/90_a_color_identity_doctrine.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/prd-90-color-identity
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: prd
  prd_cluster: 90_A_16_C_82_B_80_A_01_B
  title: "PRD 90: Color Identity Doctrine"
  summary: "Canonical high-order Color Identity. HEX5 is multi-agent conflict slang. HEX6 is six-digit perceptual color. GroupColor plus ColorNickname. Domain-scoped NAME-to-HEX6. POWERED_BY is display only. Color is not a LUP KEY token. No install SQL from this PRD."
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
  LUP.HEX: PRT.HEX.000001.000030.000000.ROOT.EN.010003
  LUP.SHORT: PRT.LUP
  LUP.ROOT: PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
  LUP.OMIT: REGISTERED_SHORT_FORMS_ONLY
  LUP.DEFAULTS: PRT.NAME.000000.000000.ROOT.ROOT.EN.0
  key_specification_version: "4.2.26"
lupopedia.map:
  index: PRT.HEX.000001.000030.000000.ROOT.EN.010003
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/90_A-i_COLOR_IDENTITY_DOCTRINE.md
  path_from_lupopedia_root: docs/prd/90_A-i_COLOR_IDENTITY_DOCTRINE.md
  prd_cluster: 90_A_16_C_82_B_80_A_01_B
  edges_toon: null
  memory_toon: memory/development/canonical/1026/08/90_a_color_identity_doctrine.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/prd-90-color-identity
  questions_toon: null
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
---
# PRD 90 -- Color Identity Doctrine

Lupopedia Constitutional Series -- High-Order Identity Architecture

| Field | Value |
|-------|-------|
| PRD | 90 |
| Version | 1.0.3 |
| Packed PRD version | 010003 |
| LUP KEY specification | 4.2.26 |
| Status | Canonical doctrine |
| Classification | Constitutional -- High-Order Identity Architecture |
| Schema authority | `install_new_lupopedia.sql` |

This PRD does not write install SQL. Examples do not create registry rows.

---

## Table of Contents

1. [Purpose](#1-purpose)
2. [Constitutional Boundary](#2-constitutional-boundary)
3. [HEX5 and HEX6](#3-hex5-and-hex6)
4. [Color Identity Model](#4-color-identity-model)
5. [GroupColor](#5-groupcolor)
6. [ColorNickname](#6-colornickname)
7. [Domain-Scoped Relational Lookup](#7-domain-scoped-relational-lookup)
8. [Human-Readable POWERED_BY Signature](#8-human-readable-powered_by-signature)
9. [Canonical Rules](#9-canonical-rules)
10. [Artifact Iterations, Derivatives, and Lineage](#10-artifact-iterations-derivatives-and-lineage)
11. [Registration and Amendment](#11-registration-and-amendment)
12. [Validation and Error Conditions](#12-validation-and-error-conditions)
13. [Relational Implementation Model](#13-relational-implementation-model)
14. [Examples](#14-examples)
15. [Rationale](#15-rationale)
16. [Governance and Authority](#16-governance-and-authority)
17. [Migration from Draft v0.2](#17-migration-from-draft-v02)
18. [Status and Acceptance Criteria](#18-status-and-acceptance-criteria)
19. [Reference Addendum](#19-reference-addendum)

---

## 1. Purpose

PRD 90 defines the Color Identity Layer used across Lupopedia for:

- human-facing artifact identification
- domain-scoped semantic color lookup
- cultural indexing
- creator-facing attribution
- artifact classification
- lineage annotation
- cross-node recognition
- stable relational mapping between names and six-digit colors

Color Identity is a relational semantic layer. It does not replace the canonical artifact KEY.

The canonical artifact KEY remains:

```text
PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
```

Color identity is resolved separately:

```text
DOMAIN + COLOR.NAME.<NICKNAME> -> COLOR.HEX6.<HEX6>
```

This PRD also establishes the mandatory distinction between HEX5 and HEX6:

- **HEX5** = AI slang for multi-agent conflict
- **HEX6** = six-digit perceptual color identity

---

## 2. Constitutional Boundary

### 2.1 Artifact identity

An artifact is canonically identified by its full eight-token LUP KEY. Color does not add a ninth token and must not be substituted for the ARTIFACT, ACTOR, GROUP, or VERSION token.

Example:

```text
CCB.NAME.000001.FOOBARREMIX.WOLFIE.MUSIC.EN.000001
```

### 2.2 Color identity

Color identity is a registered relationship among:

- domain
- GroupColor
- ColorNickname
- HEX6
- language
- field type
- responsible actor
- registry status

### 2.3 Display identity

The POWERED_BY string is a human-readable display signature. It is not the canonical artifact KEY and is not valid as a database replacement for that KEY.

### 2.4 Constitutional terminology

Hawaiian, Greek, and Lupopedia constitutional terms remain governed by their applicable doctrine. Registering a term as a ColorNickname does not change its constitutional meaning and does not make it a LUP KEY token.

### 2.5 No hash-first identity

Color identity is declared through a domain registry. It is not computed from content and does not require hashing, content addressing, Merkle DAGs, CIDs, or cryptographic commitments.

---

## 3. HEX5 and HEX6

### 3.1 HEX5

HEX5 is Lupopedia AI slang for a multi-agent conflict.

It identifies a coordination condition in which two or more agents produce incompatible interpretations, mappings, assignments, or proposed repairs that cannot safely be merged without review.

HEX5 is:

- a diagnostic expression
- an agent-coordination warning
- a signal to stop guessing
- a request for conflict resolution
- noncanonical slang rather than an artifact or color identity

HEX5 does not mean:

- a five-digit hexadecimal color
- a truncated HEX6
- exactly five participating agents
- a database field format
- a LUP KEY mode
- a color registry value
- permission to invent a compromise value

Canonical diagnostic meaning:

```text
HEX5 = MULTI_AGENT_CONFLICT
```

When an agent detects HEX5, the agent must:

- preserve every conflicting proposal
- identify the exact field or rule in conflict
- stop automatic registration or mutation
- return `MULTI_AGENT_CONFLICT` or the applicable structured error
- identify the governing registry or PRD
- request human or authorized governance resolution

### 3.2 HEX6

HEX6 is the canonical six-digit perceptual color identity used by the Color Identity Layer.

Canonical grammar:

```text
^[0-9A-F]{6}$
```

Examples:

```text
D4AF37
FF6B6B
000000
808080
FFFFFF
```

Rules:

- exactly six characters
- uppercase 0-9 and A-F only
- no leading `#` in canonical storage
- `#` may be added by a display renderer
- one HEX6 may be referenced by multiple ColorNicknames
- HEX6 meaning is resolved within a domain and field type

### 3.3 Hard separation

Parsers, agents, documentation, registries, and user interfaces must never confuse HEX5 with HEX6.

| Term | Type | Meaning | Canonical storage |
|------|------|---------|-------------------|
| HEX5 | AI slang | Multi-agent conflict | Diagnostic / event record |
| HEX6 | Color format | Six-digit perceptual color identity | Registry value |

---

## 4. Color Identity Model

Color Identity has two semantic layers and one required scope:

| Component | Function |
|-----------|----------|
| Domain | Identifies the registry with authority over the mapping |
| GroupColor | Broad human-cultural color family |
| ColorNickname | Registered semantic name mapped to HEX6 |

The complete lookup context is:

```text
DOMAIN + GROUPCOLOR + COLOR.NAME.<NICKNAME> -> COLOR.HEX6.<HEX6>
```

Example:

```text
PRT.LUP + GOLD + COLOR.NAME.GOLDENWOLF -> COLOR.HEX6.D4AF37
```

### 4.1 GroupColor is not the KEY GROUP token

GroupColor is part of the Color Identity Layer. GROUP is the sixth positional token in the canonical LUP KEY.

They may happen to use the same word, but they are not the same field and must not be merged by a parser.

Example:

```text
KEY GROUP: MUSIC
COLOR GROUP: GOLD
```

### 4.2 ColorNickname is not the artifact name

A ColorNickname is a registry name. It does not replace the ARTIFACT token.

Example:

```text
ARTIFACT: FOOBARREMIX
COLOR NICKNAME: GOLDENWOLF
```

### 4.3 Identity layers

```text
Artifact KEY      -> identifies the artifact iteration
Color assignment  -> identifies its registered perceptual-semantic color
Display signature -> communicates selected identity information to humans
```

---

## 5. GroupColor

### 5.1 Definition

A GroupColor is a broad, human-readable color family used for cultural organization and discovery.

Properties:

- accessible without protocol knowledge
- culturally recognizable
- stable enough for indexing
- nontechnical at the display surface
- registered through a domain authority
- separate from the precise ColorNickname-to-HEX6 mapping

### 5.2 Base GroupColor register

PRD 90 recognizes the following base color families as eligible GroupColors:

```text
BLACK
BLUE
BROWN
GOLD
GRAY
GREEN
ORANGE
PINK
PURPLE
RED
SILVER
WHITE
YELLOW
```

GRAY is the canonical American English registry spelling. A domain may expose GREY as a language or display alias without creating a second canonical family.

### 5.3 Cultural anchors

The base register reflects long-standing cultural use of color families in music, film, literature, and visual art. Examples may include works commonly described through white, black, blue, red, green, yellow, pink, brown, gray, gold, silver, orange, or purple identity.

Cultural examples are explanatory evidence. They do not transfer ownership, endorsement, license, or identity authority to Lupopedia.

### 5.4 Adding a GroupColor

A proposed GroupColor requires:

- cultural evidence of pre-existing use
- at least three independent cultural anchors
- a clear distinction from an existing canonical family
- language and alias analysis
- domain impact review
- a formal PRD 90 amendment

Invented poetic names belong in ColorNickname, not GroupColor.

---

## 6. ColorNickname

### 6.1 Definition

A ColorNickname is a domain-registered semantic name that resolves to one canonical HEX6 within a declared domain, language, and field type.

Canonical registry key:

```text
COLOR.NAME.<NICKNAME>
```

Resolved machine key:

```text
COLOR.HEX6.<HEX6>
```

### 6.2 Properties

- human-readable at the surface
- machine-resolvable through the registry
- mapped to one HEX6 within its declared scope
- stable within one active registry iteration
- governed by the domain that registered it
- safe for relational lookup
- independent of the artifact KEY

### 6.3 Proposed semantic register from draft v0.2

The following entries are reserved proposals carried forward from draft v0.2. They are not active registry rows until the responsible domain performs an explicit registry write.

| ColorNickname | Draft semantic description | Proposed HEX6 | Domain class | Status |
|---------------|----------------------------|---------------|--------------|--------|
| AGAPE | Greek ethical stance toward another being's existence; no single English equivalent | FF6B6B | Affective/Ethical | Reserved |
| KAPU | Sacred, restricted, or forbidden | 1A1A2E | Protective | Reserved |
| KULEANA | Responsibility and accountable obligation | 2D6A4F | Ethical | Reserved |
| ALII | Authority or chiefly standing | 9B5DE5 | Hierarchical | Reserved |
| KUMU | Source, teacher, or foundation | F4A261 | Origins | Reserved |
| PONO | Right relationship, balance, and correctness | 4A9EFF | Harmony/Ethical | Reserved |
| MANA | Authority, efficacy, or spiritual power | E63946 | Authority | Reserved |
| OHANA | Family and relational belonging | 6A994E | Communal | Reserved |
| MALAMA | Care and stewardship | BC6C25 | Custodial | Reserved |
| ALOHA | Presence, relationship, and greeting | F7C948 | Affective | Reserved |
| AINA | Land and sustaining place | 5C8001 | Territorial | Reserved |
| HOKU | Star or celestial reference | F9DC5C | Celestial | Reserved |
| NALU | Wave or flow | 0077B6 | Dynamic | Reserved |
| PUKA | Gap, opening, or missing place | 6B4E3D | Liminal | Reserved |
| LEI | Garland and connection | E5989B | Connective | Reserved |
| KAI | Sea or ocean | 0077BE | Elemental | Reserved |
| ULU | Growth or inspiration | 4CAF50 | Generative | Reserved |
| IKAIKA | Strength or force | D62828 | Force | Reserved |
| MALU | Shelter or protection | 2E4057 | Defensive | Reserved |
| LANI | Sky, heaven, or elevated place | 4361EE | Celestial | Reserved |

### 6.4 Constitutional-language rule

Terms such as KAPU, PONO, KULEANA, and PUKA already carry constitutional meaning elsewhere in Lupopedia.

Therefore:

- their ColorNickname registration must reference the governing constitutional definition
- the color mapping must not replace or narrow that definition
- the term remains forbidden as an extra artifact KEY token
- the registry key remains outside the eight-token KEY
- disagreement about meaning is a governance issue, not an automatic color rewrite

### 6.5 AGAPE rule

AGAPE is Greek. It must not be reduced to romance, charity, kindness, or compassion.

Within Lupopedia governance, AGAPE describes an ethical stance toward another being's existence:

- seek root cause without blame
- understand failure conditions
- propose structural repair
- accept responsibility for learning
- preserve existence and agency

The proposed HEX6 value does not define AGAPE. It is only a domain-scoped perceptual mapping.

---

## 7. Domain-Scoped Relational Lookup

### 7.1 Canonical relation

```text
DOMAIN + COLOR.NAME.<NICKNAME> -> COLOR.HEX6.<HEX6>
```

Example:

```text
PRT.LUP + COLOR.NAME.GOLDENWOLF -> COLOR.HEX6.D4AF37
```

GOLDENWOLF is illustrative until registered in the PRT.LUP color registry.

### 7.2 Telephone-keypad model

The relationship is comparable to an old telephone keypad:

```text
2 -> A, B, C
```

Several names may resolve to one HEX6:

```text
COLOR.NAME.GOLD
COLOR.NAME.GOLDEN
COLOR.NAME.GOLDENWOLF
        -> COLOR.HEX6.D4AF37
```

Consequences:

- NAME-to-HEX6 returns one mapping within the declared scope
- HEX6-to-NAME may return multiple names
- reverse lookup is one-to-many
- a domain may designate one preferred name
- shared HEX6 does not make different names semantically identical

### 7.3 Scope

Lookup scope includes:

- domain
- field type
- language
- registry status

The same ColorNickname may resolve differently in another domain. The same HEX6 may represent different registered names in different domains.

### 7.4 No guessing

If the domain, name, field type, or language cannot resolve deterministically, the parser or agent must return an error. It must not invent a HEX6, borrow another domain's mapping, or average conflicting colors.

---

## 8. Human-Readable POWERED_BY Signature

### 8.1 Display grammar

```text
[PROTOCOL_DISPLAY] [ARTIST] [OBJECT] POWERED_BY [DOMAIN_DISPLAY] [GROUPCOLOR] [COLORNICKNAME]
```

Example:

```text
CC-BY ALTERNATE_FATE FOOBARREMIX POWERED_BY LUPOPEDIA GOLD GOLDENWOLF
```

### 8.2 Meaning

| Component | Example | Meaning |
|-----------|---------|---------|
| PROTOCOL_DISPLAY | CC-BY | Human-facing protocol or license label |
| ARTIST | ALTERNATE_FATE | Creator-facing artist name |
| OBJECT | FOOBARREMIX | Human-facing artifact name |
| POWERED_BY | POWERED_BY | Literal display delimiter |
| DOMAIN_DISPLAY | LUPOPEDIA | Display alias for the registry domain |
| GROUPCOLOR | GOLD | Broad cultural color family |
| COLORNICKNAME | GOLDENWOLF | Domain-registered semantic name |

### 8.3 Canonical backing records

The display signature must resolve to:

```text
Artifact KEY:     CCB.NAME.000001.FOOBARREMIX.WOLFIE.MUSIC.EN.000001
Color domain:     PRT.LUP
GroupColor:       GOLD
ColorNickname:    COLOR.NAME.GOLDENWOLF
HEX6:             COLOR.HEX6.D4AF37
```

### 8.4 Display-layer rules

- The signature is optional unless a specific publishing surface requires it.
- It is noncanonical and must not replace the artifact KEY.
- Hyphens, underscores, and spaces may appear according to display rules.
- Display protocol CC-BY may map to canonical KEY protocol CCB.
- Display domain LUPOPEDIA may map to registered domain short PRT.LUP.
- ARTIST does not have to equal the canonical ACTOR token.
- Every displayed ColorNickname must resolve within the displayed domain.
- If the signature cannot resolve, it is invalid as a Lupopedia identity display.

### 8.5 Intended uses

- album covers
- music and video credits
- creator dashboards
- CC-BY attribution displays
- social media
- onboarding
- federation banners
- nontechnical artifact views

### 8.6 Legal boundary

The display signature does not itself grant a license, establish copyright ownership, or replace legally required attribution. License and attribution obligations remain governed by the artifact's actual license record.

---

## 9. Canonical Rules

### 9.1 Domain is required

No ColorNickname-to-HEX6 mapping is canonical without a registered domain.

### 9.2 GroupColor must be registered

GroupColor must belong to the base register or a formally amended domain register.

### 9.3 ColorNickname must be registered

The name must exist in the responsible domain registry with the requested field type and language.

### 9.4 HEX6 must be exact

Canonical HEX6 must match:

```text
^[0-9A-F]{6}$
```

### 9.5 Both semantic color layers are required for the full display signature

A full PRD 90 display signature contains both GroupColor and ColorNickname.

Short UI surfaces may display only the preferred ColorNickname or HEX6 when their context already retains the complete color assignment record.

### 9.6 Ordering is fixed in the full display signature

```text
[PROTOCOL_DISPLAY] [ARTIST] [OBJECT] POWERED_BY [DOMAIN_DISPLAY] [GROUPCOLOR] [COLORNICKNAME]
```

### 9.7 Artifact color assignment is stable per iteration

Once assigned to an immutable artifact iteration, the accepted color assignment does not drift. A correction creates a new artifact iteration or a superseding color-assignment assertion according to the governing artifact policy.

### 9.8 No automatic registry writes

Documentation examples do not create registry entries. An authorized registry mutation is required.

### 9.9 No hash-first color identity

The registry relationship is declared. It is not computed from file content.

### 9.10 No semantic collapse

Agents must not treat any of these as interchangeable:

- KEY GROUP
- GroupColor
- ColorNickname
- HEX6
- HEX5
- Artifact name
- Actor identity

---

## 10. Artifact Iterations, Derivatives, and Lineage

### 10.1 Assignment to an iteration

A color assignment belongs to one canonical artifact iteration identified by its full eight-token KEY.

### 10.2 Derivatives

A derivative artifact must preserve its source relationship through provenance edges. It does not have to reuse the parent's ColorNickname or HEX6 unless the governing derivative profile explicitly requires it.

The derivative may:

- retain the source color assignment
- register a new color assignment
- inherit GroupColor while changing ColorNickname
- retain ColorNickname while changing another display component
- declare multiple parent color relationships in provenance metadata

### 10.3 No automatic HEX inheritance

HEX6 does not automatically pass to every remix or fork. Automatic inheritance would erase the derivative's ability to declare its own identity.

Instead:

- the parent artifact retains its immutable color assignment
- the derivative retains the parent relationship
- the derivative receives an explicit color assignment
- user interfaces may display both source and derivative colors

### 10.4 Semantic continuity

Preserving lineage means preserving the relationship to the parent, not forcing every descendant to carry the same color forever.

### 10.5 Color corrections

If an assignment was incorrect:

- preserve the original assertion
- create a correcting assertion
- identify the responsible actor
- record `created_ymdhis`
- link the correction to the prior assignment
- increment artifact VERSION when the correction changes the artifact's asserted meaning

---

## 11. Registration and Amendment

### 11.1 Color identity registration

1. Propose Domain, GroupColor, ColorNickname, HEX6, language, and field type.
2. Validate Domain authority.
3. Validate GroupColor against the applicable register.
4. Validate ColorNickname grammar and semantic definition.
5. Validate HEX6.
6. Check whether the scoped name already maps to a different HEX6.
7. Check whether the HEX6 already has registered synonyms.
8. Review constitutional-term collisions.
9. Receive authorized approval.
10. Write the registry record.
11. Record actor and timestamps.

### 11.2 New ColorNickname

A new ColorNickname requires:

- semantic definition
- proposed HEX6
- domain
- GroupColor
- field type
- language
- collision review
- constitutional review when applicable
- responsible actor
- authorized registry write

### 11.3 New GroupColor

A new GroupColor requires the amendment process in Section 5.4.

### 11.4 Preferred names

When several names resolve to one HEX6, a domain may designate one preferred name per language and field type. Nonpreferred synonyms remain valid unless separately deprecated.

### 11.5 Prohibited registration behavior

- No registration from an example alone.
- No silent overwrite.
- No cross-domain borrowing without explicit registration.
- No changing an active name's meaning in place.
- No using HEX5 as a color value.
- No assigning a five-digit substitute when HEX6 is required.

---

## 12. Validation and Error Conditions

### 12.1 Required validation order

1. Determine whether the input is a LUP KEY, color registry key, display signature, or diagnostic expression.
2. If the token is HEX5, route it to multi-agent conflict handling.
3. If HEX6 is expected, validate the six-digit grammar.
4. Resolve Domain.
5. Resolve GroupColor.
6. Resolve ColorNickname by Domain, language, and field type.
7. Confirm the NAME-to-HEX6 mapping.
8. Resolve the canonical artifact KEY when processing a display signature or assignment.
9. Return the canonical relationship or a deterministic error.

### 12.2 Required errors

| Error | Meaning |
|-------|---------|
| MULTI_AGENT_CONFLICT | HEX5 condition; incompatible agent proposals require review |
| COLOR_DOMAIN_UNKNOWN | Domain is absent or unregistered |
| GROUPCOLOR_UNKNOWN | GroupColor is not registered in scope |
| COLOR_NAME_UNKNOWN | ColorNickname is not registered in scope |
| COLOR_NAME_CONFLICT | One scoped name has conflicting HEX6 proposals |
| COLOR_HEX6_INVALID | Value does not match six-digit uppercase HEX grammar |
| COLOR_MAPPING_MISMATCH | NAME and HEX6 do not match the registry |
| COLOR_LANGUAGE_UNSUPPORTED | Requested language is unavailable |
| COLOR_FIELD_TYPE_UNSUPPORTED | Requested field type is unavailable |
| DISPLAY_SIGNATURE_MALFORMED | POWERED_BY string violates display grammar |
| DISPLAY_SIGNATURE_UNRESOLVED | Display string cannot resolve to backing records |
| COLOR_ASSIGNMENT_DRIFT | Existing immutable iteration was silently reinterpreted |
| REGISTRY_WRITE_UNAUTHORIZED | Actor lacks authority to register or amend the mapping |

### 12.3 Parser prohibitions

A parser or agent must not:

- guess a missing domain
- infer a ColorNickname from HEX6 without registry lookup
- select one agent proposal during HEX5 without authority
- treat `#` as part of canonical HEX6
- truncate or pad invalid input silently
- replace an artifact KEY with a display signature
- insert GroupColor into the KEY GROUP token automatically
- invent registry rows
- treat cultural familiarity as database registration

---

## 13. Relational Implementation Model

### 13.1 Color registry record

Conceptual fields:

```text
color_identity_id
domain_key
group_color
color_name
hex6
field_type
iso_language
is_preferred
status
actor_id
created_ymdhis
updated_ymdhis
is_deleted
deleted_ymdhis
```

### 13.2 Artifact color assignment

Conceptual fields:

```text
color_assignment_id
artifact_key
color_identity_id
assignment_iteration
status
actor_id
created_ymdhis
supersedes_assignment_id
is_deleted
deleted_ymdhis
```

### 13.3 HEX5 conflict record

Conceptual fields:

```text
conflict_id
conflict_type
subject_key
field_name
proposal_count
status
resolution_actor_id
created_ymdhis
resolved_ymdhis
```

Recommended value:

```text
conflict_type = MULTI_AGENT_CONFLICT
```

### 13.4 Database doctrine

- 18-digit IDs generated by the Lupopedia IdGenerator
- no AUTO_INCREMENT
- no foreign keys
- no triggers
- no stored procedures
- BIGINT UTC YYYYMMDDHHIISS timestamps
- explicit column lists in every INSERT
- soft deletion
- application-layer validation

This PRD defines doctrine and conceptual records. `install_new_lupopedia.sql` remains schema authority. No implementation may invent or alter production tables solely from this conceptual model.

Planning draft for table split: `docs/prd/01_B-i_COLOR_REGISTRY.md`. HEX5 is not a color column. HEX5 is a conflict event.

---

## 14. Examples

### 14.1 Valid display signature

```text
CC-BY ALTERNATE_FATE FOOBARREMIX POWERED_BY LUPOPEDIA GOLD GOLDENWOLF
```

Backing artifact KEY:

```text
CCB.NAME.000001.FOOBARREMIX.WOLFIE.MUSIC.EN.000001
```

Backing color relation:

```text
PRT.LUP + GOLD + COLOR.NAME.GOLDENWOLF -> COLOR.HEX6.D4AF37
```

The example becomes canonical only after GOLDENWOLF is registered in the PRT.LUP domain.

### 14.2 Valid technical color object

```json
{
  "domain": "PRT.LUP",
  "group_color": "GOLD",
  "name_key": "COLOR.NAME.GOLDENWOLF",
  "hex6_key": "COLOR.HEX6.D4AF37",
  "name": "GOLDENWOLF",
  "hex6": "D4AF37"
}
```

### 14.3 Many names to one HEX6

```text
PRT.LUP + COLOR.NAME.GOLD       -> COLOR.HEX6.D4AF37
PRT.LUP + COLOR.NAME.GOLDEN     -> COLOR.HEX6.D4AF37
PRT.LUP + COLOR.NAME.GOLDENWOLF -> COLOR.HEX6.D4AF37
```

Each name requires its own registry record. The shared HEX6 does not merge their meanings.

### 14.4 HEX5 event

```text
Agent A proposes GOLDENWOLF -> D4AF37
Agent B proposes GOLDENWOLF -> FFD700
Agent C proposes borrowing a mapping from another domain

Result:
HEX5 / MULTI_AGENT_CONFLICT

Required behavior:
STOP REGISTRY WRITE
PRESERVE ALL PROPOSALS
RETURN COLOR_NAME_CONFLICT
REQUEST AUTHORIZED RESOLUTION
```

### 14.5 Invalid display signatures

| Display string | Reason |
|----------------|--------|
| CC-BY ALTERNATE_FATE FOOBARREMIX POWERED_BY GOLD GOLDENWOLF | Missing domain |
| CC-BY ALTERNATE_FATE FOOBARREMIX POWERED_BY LUPOPEDIA GOLD | Missing ColorNickname |
| CC-BY ALTERNATE_FATE FOOBARREMIX POWERED_BY LUPOPEDIA GOLDENWOLF | Missing GroupColor in the full PRD 90 form |
| CC-BY ALTERNATE_FATE FOOBARREMIX POWERED_BY LUPOPEDIA GOLD FAKE | Unregistered ColorNickname |
| CC-BY ALTERNATE_FATE FOOBARREMIX POWERED_BY LUPOPEDIA GOLD HEX5 | HEX5 is not a color identity |

### 14.6 Original draft examples after normalization

The following illustrates the draft v0.2 intent without declaring unregistered mappings canonical:

```text
LUP WOLFIE DEMONS POWERED_BY LUPOPEDIA WHITE AGAPE
LUP CAPTAIN PRD90 POWERED_BY LUPOPEDIA BLACK PONO
LUP NODE12 ARTIFACT88 POWERED_BY LUPOPEDIA BLUE KAPU
LUP STUDIO66 SESSION09 POWERED_BY LUPOPEDIA RED KUMU
LUP ARCHIVE44 LOGBOOK POWERED_BY LUPOPEDIA GREEN MALAMA
LUP WOLFIE PAWPRINTS POWERED_BY LUPOPEDIA GOLD MANA
```

Each line requires a backing artifact KEY and an active domain registry mapping before use as a resolvable Lupopedia display signature.

---

## 15. Rationale

Color Identity is not merely decoration. It is a governed semantic relationship that connects human-facing language to machine-resolvable perceptual values.

Technically, the relationship is stored as registry and assignment data. Architecturally, it provides:

- cultural meaning
- semantic classification
- perceptual lookup
- creator-facing identity
- stable artifact-iteration assignments
- lineage visualization
- cross-node registry resolution
- multilingual naming

### 15.1 Why two semantic layers

GroupColor supplies a broad cultural family. ColorNickname supplies a precise registered semantic name. HEX6 supplies a perceptual machine value. Domain supplies authority and scope.

No one component replaces the others.

### 15.2 Why HEX5 exists

Multi-agent systems frequently produce incompatible confident answers. HEX5 gives Lupopedia a short human signal for the moment when agent output must stop becoming silent system mutation.

HEX5 turns confusion into an explicit governance event.

### 15.3 Why HEX6 exists

HEX6 gives user interfaces and machines a stable six-digit perceptual reference while leaving semantic authority with the domain registry.

### 15.4 Why colors do not enter the artifact KEY

The artifact KEY already identifies protocol, mode, node, artifact, actor, group, language, and version. Adding color would either create a ninth token or overload an existing field. PRD 90 forbids both.

---

## 16. Governance and Authority

### 16.1 Authority order

When sources conflict:

1. PRD 16_C and the active LUP KEY specification govern artifact KEY grammar.
2. PRD 82_B governs Hawaiian and constitutional semantics.
3. PRD 90 governs Color Identity relationships and display signatures.
4. The active domain registry governs actual NAME-to-HEX6 rows.
5. `install_new_lupopedia.sql` governs implemented database schema.
6. Examples and whitepapers explain but do not silently mutate registries or schema.

### 16.2 Domain sovereignty

Each domain may register its own:

- ColorNicknames
- HEX6 mappings
- preferred synonyms
- languages
- field types
- display aliases
- deprecation policy

Every participating domain must preserve the PRD 90 distinctions among Domain, GroupColor, ColorNickname, HEX6, HEX5, and artifact KEY.

### 16.3 Amendment

Changes to canonical rules require a PRD 90 version increment. Registry-row additions do not require a PRD version increment unless they change doctrine, base GroupColor vocabulary, constitutional meaning, or validation behavior.

---

## 17. Migration from Draft v0.2

PRD 90 v1.0.3 supersedes draft v0.2.

Binding corrections:

- HEX5 is defined as AI slang for multi-agent conflict.
- HEX6 is defined as six-digit perceptual color identity.
- Color identity is outside the eight-token artifact KEY.
- Domain is required for every canonical NAME-to-HEX6 mapping.
- GroupColor is distinct from the KEY GROUP token.
- ColorNickname is distinct from ARTIFACT and ACTOR.
- POWERED_BY is a noncanonical display signature, not a database identity.
- The full display signature includes Domain, GroupColor, and ColorNickname.
- HEX6 does not automatically propagate to every derivative.
- Derivative continuity is preserved through provenance relationships.
- Color identity does not itself grant license or remix rights.
- Constitutional terms keep their governing meanings.
- Draft ColorNickname mappings remain reserved until explicitly registered.
- Documentation examples never create registry rows.
- Hash-first identity is not part of Color Identity.

---

## 18. Status and Acceptance Criteria

### 18.1 Status

| Field | Value |
|-------|-------|
| PRD | 90 |
| Version | 1.0.3 |
| Packed version | 010003 |
| Status | Canonical doctrine |
| Classification | Constitutional -- High-Order Identity Architecture |
| LUP KEY specification | 4.2.26 |
| Schema authority | `install_new_lupopedia.sql` |

### 18.2 Acceptance criteria

An implementation conforms to PRD 90 when it:

- distinguishes HEX5 from HEX6
- validates HEX6 exactly
- resolves color through a declared domain
- keeps color outside the artifact KEY
- keeps GroupColor separate from KEY GROUP
- keeps ColorNickname separate from ARTIFACT
- rejects unregistered mappings
- stops writes during HEX5 conflict
- preserves immutable artifact-iteration assignments
- records corrections relationally
- treats POWERED_BY as display syntax
- preserves legal and licensing boundaries
- follows database doctrine
- does not infer schema from this PRD alone

---

## 19. Reference Addendum

### 19.1 Canonical formulas

```text
ARTIFACT KEY = PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION

COLOR LOOKUP = DOMAIN + COLOR.NAME.<NICKNAME> -> COLOR.HEX6.<HEX6>

FULL DISPLAY = [PROTOCOL_DISPLAY] [ARTIST] [OBJECT] POWERED_BY [DOMAIN_DISPLAY] [GROUPCOLOR] [COLORNICKNAME]

HEX5 = MULTI_AGENT_CONFLICT

HEX6 = ^[0-9A-F]{6}$
```

### 19.2 Minimal color object

```json
{
  "domain": "PRT.LUP",
  "group_color": "GOLD",
  "name_key": "COLOR.NAME.GOLDENWOLF",
  "hex6_key": "COLOR.HEX6.D4AF37",
  "name": "GOLDENWOLF",
  "hex6": "D4AF37"
}
```

### 19.3 Minimal HEX5 response

```text
ERROR: MULTI_AGENT_CONFLICT
ACTION: STOP_MUTATION
PRESERVE: ALL_PROPOSALS
AUTHORITY: REQUEST_RESOLUTION
```

### 19.4 Pono implementation statement

Human language at the surface. Registered meaning underneath. Explicit conflict when agents disagree. Six-digit color at the perceptual boundary.

PRD 90 -- Color Identity Doctrine
Version 1.0.3
HEX5: multi-agent conflict
HEX6: six-digit perceptual color identity

---

## Related

- KEY: `docs/prd/16_C-i_LUPOPEDIA_HEADERS.md` section 4.2.6
- Hawaiian / constitutional semantics: `docs/prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md`
- Database doctrine: `docs/prd/80_A-i_DATABASE_DESIGN_DOCTRINE.md`
- Table planning (thinking, not doctrine): `docs/prd/01_B-i_COLOR_REGISTRY.md`
- Protocol CSV: `docs/protocols/hex/HEX.COLORS.md`
- LUPxPEDIA Case A: `docs/doctrine/lupopedia_identity_routing_rule.md`
- Placement log: `content/federation_node/0/captains_log/origin_stories_architure/2026/08/20260816_choosing_the_prd_number_for_color_identity_doctrine.md`
