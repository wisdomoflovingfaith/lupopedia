---
lupopedia.headers:
  header_format_version: "4.2.11"
  path_from_lupopedia_root: docs/protocols/lup/lupopedia_whitepaper_v1_9_2.md
  web_path: https://www.lupopedia.com/lupopedia/docs/protocols/lup/lupopedia_whitepaper_v1_9_2.md
  status: active
  when_updated: "20260816104950"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/08/lupopedia_whitepaper_v1_9_2.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/lupopedia-whitepaper-v1-9-2
  artifact_type: documentation
  artifact_kind: whitepaper
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: documentation
  prd_cluster: 16_C_34_A_80_A_82_B_99_A
  title: "Lupopedia Whitepaper v1.9.2 -- Pono Edition (multi-parent, domain color, hash-free federation)"
  summary: "Canonical combined whitepaper. Artifact identity is the eight-token LUP KEY. Color is domain-scoped registry lookup. Provenance is relational parent edges. Does not override PRD 16_C. Does not bump the product atom."
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
  LUP.HEX: PRT.HEX.000001.000023.000000.ROOT.EN.010902
  LUP.SHORT: PRT.LUP
  LUP.ROOT: PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
  LUP.OMIT: REGISTERED_SHORT_FORMS_ONLY
  LUP.DEFAULTS: PRT.NAME.000000.000000.ROOT.ROOT.EN.0
  key_specification_version: "4.2.26"
  whitepaper_version: "1.9.2"
lupopedia.map:
  index: PRT.HEX.000001.000023.000000.ROOT.EN.010902
  web_path: https://www.lupopedia.com/lupopedia/docs/protocols/lup/lupopedia_whitepaper_v1_9_2.md
  path_from_lupopedia_root: docs/protocols/lup/lupopedia_whitepaper_v1_9_2.md
  prd_cluster: 16_C_34_A_80_A_82_B_99_A
  edges_toon: null
  memory_toon: memory/development/canonical/1026/08/lupopedia_whitepaper_v1_9_2.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/lupopedia-whitepaper-v1-9-2
  questions_toon: null
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
---
# Lupopedia Whitepaper v1.9.2 -- Pono Edition

Multi-parent provenance, domain-scoped color, and hash-free federation.

**Whitepaper version:** 1.9.2 (packed KEY VERSION `010902`)
**LUP KEY specification:** 4.2.26
**Header contract:** `header_format_version: "4.2.11"`
**Product atom:** `GLOBAL_CURRENT_LUPOPEDIA_VERSION` remains **4.2.11**
**This file HEX:** `PRT.HEX.000001.000023.000000.ROOT.EN.010902`
**Status:** Canonical combined whitepaper for identity / color / provenance architecture
**Does not override:** PRD 16_C KEY grammar, PRD 80 database doctrine, HEX.COLORS.csv seed rows
**Does not implement:** new SQL tables. Schema belongs in [PRD 34_B](../../prd/34_B-i_LUP_KEY_ARTIFACT_PARENT_EDGES.md) (planning) plus `install_new_lupopedia.sql` when that PRD leaves planning.

Whitepaper version 1.9.2 and LUP KEY version 4.2.26 are separate version namespaces.

```text
ARTIFACT IDENTITY  = canonical eight-token LUP KEY
COLOR IDENTITY     = domain-scoped NAME-to-HEX6 lookup
PROVENANCE         = relational parent-edge records
```

ASCII-safe. Dot grammar in KEYs. No pipes, no middle-dot, no hyphens in KEY tokens.

---

## Table of contents

0. Abstract
1. Why Lupopedia rejects hash-first identity
2. Simple human-readable identity line
3. Version overview
4. Canonical artifact identity
5. Domain-scoped color identity
6. Multi-parent relational provenance
7. Artifact iterations and immutability
8. Graph classes and direction
9. Conflict, correction, and revocation
10. Canonical serialization
11. Federation synchronization
12. Validation requirements
13. Resource limits
14. Backward compatibility
15. Canonical artifact object
16. Relational implementation model
17. Worked examples
18. Final architecture
19. Reference addendum and editorial notes

---

## 0. Abstract

Lupopedia uses declared semantic identity rather than content-computed identity. An artifact is identified by the canonical eight-token LUP KEY. Its color identity is resolved through a domain-scoped relational registry. Its provenance is represented by relational parent-edge records organized into an acyclic provenance graph.

Whitepaper version 1.9.2 adds multi-parent provenance without changing the LUP KEY. It also defines creator-facing display lines, separates acyclic provenance edges from potentially cyclic associative relationships, formalizes correction and revocation, and establishes hash-free federation synchronization.

This whitepaper is aligned with the LUP v4.2.26 KEY specification. It does not replace that specification.

---

## 1. Why Lupopedia rejects hash-first identity

KAPU: HASHING

Lupopedia does not use hashing, content addressing, Merkle DAGs, CIDs, or cryptographic commitments as part of canonical artifact identity or provenance authority. This is intentional and doctrinal.

KAPU: HASHING applies to **required identity and provenance architecture**. It does not forbid password hashing, TLS, git objects, or other host-security tools. Those tools must never become the artifact KEY, parent identity, conflict authority, or proof that a relationship is true.

### 1.1 Hashing is not identity in Lupopedia

The canonical artifact identity is the eight-token LUP KEY:

```text
PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
```

The KEY is human-readable, relational, domain-scoped, versioned, stable, federated, and declared rather than computed.

Hash-first systems derive object identifiers from content. Lupopedia takes a different architectural position: semantic identity is declared through registered relationships. Content does not get to replace the identity assigned by the responsible federation.

### 1.2 Hashing breaks federation sovereignty

Required hash-based identity would force participating nodes to agree on canonical serialization, canonical hashing, canonical normalization, canonical ordering, one or more hash algorithms, and the authority of computed identifiers.

Lupopedia federations are sovereign. They maintain their own registries, namespaces, actors, artifact allocations, and color tables. Hash-first identity would replace those declared relationships with a global computed-address regime.

Lupopedia instead establishes interoperability through the shared KEY grammar and federation rules while allowing each registered domain to govern its own semantic registry.

### 1.3 Hashing is unnecessary for color identity

Color identity is a telephone-keypad relational lookup (teaching metaphor, not a KEY encoding):

```text
DOMAIN + COLOR.NAME.<NICKNAME> -> COLOR.HEX6.<HEX>
```

Example (illustrative nickname until the domain registers it):

```text
PRT.LUP + COLOR.NAME.GOLDENWOLF -> COLOR.HEX6.D4AF37
```

This is a domain-scoped registry relationship, not a cryptographic commitment. Multiple names may resolve to the same HEX6, just as multiple letters share one number on a telephone keypad.

`COLOR.NAME.GOLDENWOLF` is a **registry key**. Extra dots are legal there because it is not a LUP KEY. Do not put color registry keys inside LUP.KEY tokens.

### 1.4 Hashing does not replace cycle validation

A hash stored next to a parent reference does not prevent cycles. Lupopedia prevents cycles by validating the provenance graph before accepting an edge.

Cycle validation examines relational edges and rejects any new acyclic provenance relationship that would make the child an ancestor of itself.

### 1.5 Hashing conflicts with Lupopedia iteration semantics

In Lupopedia:

- an artifact iteration is immutable
- its accepted parent set is immutable
- adding or correcting parents creates a new iteration
- earlier iterations remain preserved
- corrections are new assertions, never silent replacement of history

A new iteration receives a new VERSION token in its full KEY, but it remains part of the same declared semantic artifact lineage. Lupopedia does not replace that semantic continuity with a content-computed object identity.

### 1.6 Hashing complicates federation synchronization

Hash-based conflict authority would require agreement about serialization, normalization, ordering, algorithms, signature scopes, and cryptographic policy.

Lupopedia uses relational records:

- `parent_edge_id`
- `assertion_iteration`
- `supersedes_edge_id`
- ACTIVE, SUPERSEDED, and REVOKED status
- canonical parent and child KEYs
- actor and timestamp provenance

No hashing is required to identify, merge, correct, revoke, or traverse these relationships.

### 1.7 Hashing is KAPU

Hash-first identity introduces unnecessary complexity, weakens federation sovereignty, and contradicts Lupopedia's relational architecture.

Therefore:

Lupopedia rejects hashing as canonical identity, lineage authority, merge authority, or a required protocol mechanism.

An external checksum may be carried as optional, non-authoritative metadata. It must never become the artifact KEY, parent identity, conflict authority, or proof that a relationship is true.

---

## 2. Simple human-readable identity line

For creators, musicians, and non-technical users.

Lupopedia supports a simple human-readable identity line for creators who do not need to display the complete eight-token KEY or JSON provenance object.

This line is a **display representation**. It is valid for presentation, attribution, user interfaces, and creator-facing tools. It is **not** the canonical artifact identity stored by the system.

### 2.1 Display grammar

```text
[PROTOCOL] [ARTIST] POWERED_BY [DOMAIN] [COLOR_NAME]
```

Example:

```text
CC-BY ALTERNATE_FATE POWERED_BY LUPOPEDIA GOLDENWOLF
```

This line communicates:

- Protocol display: CC-BY (maps to KEY PROTOCOL `CCB`)
- Artist: ALTERNATE_FATE (need not equal the KEY ACTOR token)
- Federation domain display: LUPOPEDIA (maps to color domain `PRT.LUP`)
- Color name: GOLDENWOLF

The domain resolves the color name:

```text
PRT.LUP + COLOR.NAME.GOLDENWOLF -> COLOR.HEX6.D4AF37
```

### 2.2 Display rules

PROTOCOL

- May use creator-facing names such as CC-BY, CCB, LUP, or a registered domain protocol.
- Hyphens are permitted because this is a display layer, not a canonical KEY token.

ARTIST

- Contains a human-readable creator or artist name.
- Underscores are permitted.
- Does not have to equal the canonical ACTOR token.

POWERED_BY DOMAIN

- Identifies the domain whose registry resolves the color name.
- Display names such as LUPOPEDIA must map to a registered protocol SHORT (example `PRT.LUP`).
- Do not treat the product word LUPOPEDIA as a KEY PROTOCOL token.

COLOR_NAME

- Contains the human-facing registered nickname.
- The named domain resolves it to HEX6.
- Example: GOLDENWOLF -> D4AF37 after registration.

### 2.3 Intended uses

Album covers, video credits, CC-BY attribution, creator dashboards, social media, simple metadata displays, non-technical interfaces, and federation onboarding.

### 2.4 Important distinction

The display line is not the artifact KEY:

```text
CC-BY ALTERNATE_FATE POWERED_BY LUPOPEDIA GOLDENWOLF
```

The canonical identity remains an eight-token KEY:

```text
PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
```

Display tools must retain or be able to resolve the canonical KEY behind the human-readable line. A display string that cannot resolve is not a substitute canonical identity.

---

## 3. Version overview

Whitepaper v1.9.2 is a refinement release over v1.9.1. It:

- confirms hash-free identity and provenance under KAPU: HASHING
- locks domain-scoped color identity
- formalizes relational `parent_edge_id` records
- adds creator-facing identity lines
- separates provenance DAG edges from associative edges
- defines correction, revocation, and conflict preservation
- permits original artifacts with no parents
- aligns every canonical artifact example with the LUP v4.2.26 eight-token KEY

This whitepaper extends provenance behavior. It does not replace or modify the canonical KEY grammar.

Keep these namespaces separate:

| Label | Example |
|-------|---------|
| Header contract | `4.2.11` |
| KEY specification | `4.2.26` |
| This whitepaper | `1.9.2` / packed `010902` |
| Product atom | `4.2.11` |
| Worked-example artifact VERSION | `000001` = packed `0.0.1` (that artifact's first iteration) |

Do not copy the KEY specification version into a song's VERSION token. Do not copy this whitepaper version into the product atom.

---

## 4. Canonical artifact identity

### 4.1 Eight-token KEY

```text
PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
```

Example (NAME profile, eight tokens):

```text
CCB.NAME.000001.FOOBARREMIX.WOLFIE.MUSIC.EN.000001
```

Token count:

- CCB -- PROTOCOL
- NAME -- MODE
- 000001 -- NODE (always HEX6; live nodes begin at `000001`)
- FOOBARREMIX -- ARTIFACT
- WOLFIE -- ACTOR
- MUSIC -- GROUP (always stored; default `ROOT` when unspecified)
- EN -- LANGUAGE
- 000001 -- VERSION (packed `0xMMmmPP`; here packed 0.0.1)

The JSON field is:

```text
"artifact_key": "CCB.NAME.000001.FOOBARREMIX.WOLFIE.MUSIC.EN.000001"
```

HEX storage of the same iteration uses GROUP `ROOT` in HEX fill:

```text
CCB.HEX.<NODE>.<ARTIFACT>.<ACTOR>.ROOT.EN.<VERSION>
```

Storage, comparison, federation, hashing-prohibition surfaces, and APIs always use the complete eight-token KEY. Display may use registered short productions only (see PRT.LUP.md section 8). Arbitrary middle-field omission is forbidden.

### 4.2 Identity invariants

- A stored artifact KEY contains exactly eight tokens.
- Dots separate the tokens.
- Token order is fixed.
- Stored KEYs use uppercase canonical values.
- NODE is always HEX6. Default `000000` means unspecified / example. Live federation nodes begin at `000001`.
- The VERSION token identifies the immutable artifact iteration as packed `0xMMmmPP`, or `0` unversioned. `042010` is invalid. Packed 4.2.10 is `04020A`. Packed 4.2.11 is `04020B`. Packed 4.2.26 is `04021A`. Packed 1.9.2 is `010902`. Packed 0.0.1 is `000001`.
- Parent edges, colors, titles, media kinds, and display credits do not add KEY tokens.
- Hawaiian constitutional terms (KAPU, PONO, ALII, ...) are ethics / routing language (PRD 82_B). They are never KEY tokens.
- Color is never a KEY token.

### 4.3 Identity is not color

The artifact KEY identifies the artifact iteration. A color identity is a related registry value and must not replace the artifact KEY.

---

## 5. Domain-scoped color identity

### 5.1 Relational lookup

```text
DOMAIN + COLOR.NAME.<NICKNAME> -> COLOR.HEX6.<HEX>
```

DOMAIN is a registered protocol SHORT (example `PRT.LUP`, `CCB`). It is not the product word LUPOPEDIA unless that word is registered as a protocol SHORT.

Illustrative mappings (not all are present in today's CSV seed):

| Domain | NAME key | HEX6 key |
|--------|----------|----------|
| PRT.LUP | COLOR.NAME.GOLDENWOLF | COLOR.HEX6.D4AF37 |
| CCB | COLOR.NAME.EMERALD | COLOR.HEX6.50C878 |
| HARMONY | COLOR.NAME.SKYMINT | COLOR.HEX6.87CEEB |

Today's on-disk seed is `docs/protocols/hex/PRT.LUP/PRT.LUP.colors.csv`. That file currently stores lowercase CSS `word` values (`gold` -> `FFD700`, `skyblue` -> `87CEEB`). Crest nicknames such as GOLDENWOLF must be **registered** before they are canonical. Do not invent a CSV row from this whitepaper without an explicit registry write.

Canonical HEX6 storage is six uppercase hexadecimal characters with no `#`. Display may render `#D4AF37`.

### 5.2 Canonical color object

```text
{
  "domain": "PRT.LUP",
  "name_key": "COLOR.NAME.GOLDENWOLF",
  "hex6_key": "COLOR.HEX6.D4AF37",
  "name": "GOLDENWOLF",
  "hex6": "D4AF37"
}
```

The domain is required because COLOR.NAME.GOLDENWOLF is resolved within a domain registry.

### 5.3 Lookup behavior

- NAME-to-HEX6 resolves one registered mapping.
- Multiple NAME values may resolve to the same HEX6.
- Reverse HEX6 lookup may therefore return multiple NAME values.
- A domain may designate one name as preferred without deleting its synonyms.
- HEX6 contains exactly six uppercase hexadecimal characters and never includes `#` in canonical storage.
- A display layer may render `#D4AF37`.
- Current CSV lookup also uses `field_type` fallback: requested type, then `node`, then request creation. Do not guess. See HEX.COLORS.md.

### 5.4 Color registry fields

A color registry record minimally identifies:

- domain
- name key
- HEX6 key
- registered name
- HEX6 value
- language
- preferred-name status
- registry status
- responsible actor

No cryptographic computation determines the relationship. The registered domain declares it.

---

## 6. Multi-parent relational provenance

### 6.1 Parent identity

Parent references must use full canonical LUP KEYs, not color nicknames or display labels.

Correct:

```text
PRT.NAME.000002.DEEPBLUE.ROOT.MUSIC.EN.000001
CCB.NAME.000003.EMERALD.ROOT.MUSIC.EN.000001
```

Incorrect:

```text
LUP:DEEPBLUE
CC-BY:EMERALD
HARMONY:SKYMINT
PRT.LUP.WHEEL
```

Colon-bag grammar is not LUP. Short forms that are not registered productions are not LUP.

### 6.2 Parent-edge record

Each relationship is an independent relational record. A parent edge contains:

- `parent_edge_id` -- 18-digit Lupopedia ID (IdGenerator shape: packed UTC `YYYYMMDDHHIISS` plus 4-digit suffix; PRD 80)
- `parent_key` -- complete canonical parent KEY
- `edge_type` -- registered relationship type
- `role` -- registered role within the child artifact
- `weight_bps` -- optional quantitative contribution
- `assertion_iteration` -- revision of the relationship assertion
- `status` -- ACTIVE, SUPERSEDED, or REVOKED
- `created_ymdhis` -- BIGINT UTC `YYYYMMDDHHIISS`
- `actor_id` -- actor responsible for the assertion
- `supersedes_edge_id` -- earlier edge corrected or revoked by this assertion

When an edge is stored outside its child object, it also includes `child_key`.

JSON exchange MUST encode `parent_edge_id` (and `supersedes_edge_id` when present) as a **decimal string**. IEEE-754 JSON numbers are not safe for 18-digit integers.

No AUTO_INCREMENT. No foreign keys. No triggers. Application code validates references.

### 6.3 Parent count

An artifact iteration may have between 0 and 1,024 active parent edges.

- Zero parents means an original artifact or an artifact with no declared provenance.
- One parent is ordinary single-parent lineage.
- Two or more parents form multi-parent lineage.

### 6.4 Weight

`weight_bps` uses integer basis points:

- minimum: 0
- maximum: 10000
- 10000 means 100 percent
- omit the field when the relationship is not quantitative
- weights do not have to total 10000 unless the artifact type or edge profile requires proportional composition

### 6.5 Multiple roles

The same parent may contribute through more than one independently registered edge. For example, a parent may supply both STRUCTURE and SAMPLE. Each relationship receives its own `parent_edge_id`.

---

## 7. Artifact iterations and immutability

### 7.1 Immutable iteration

For one canonical artifact KEY:

- the artifact content state is immutable
- its accepted provenance parent set is immutable
- its VERSION token is fixed
- its historical parent assertions remain auditable

### 7.2 Lineage changes create iterations

Adding, correcting, or revoking an accepted parent changes the artifact's asserted meaning. The system therefore creates a new artifact iteration with an incremented VERSION token.

Example (packed 0.0.1 then packed 0.0.2):

```text
CCB.NAME.000001.FOOBARREMIX.WOLFIE.MUSIC.EN.000001
CCB.NAME.000001.FOOBARREMIX.WOLFIE.MUSIC.EN.000002
```

The first iteration remains preserved. The second iteration carries the revised parent assertion set.

Increment VERSION with packed `0xMMmmPP` arithmetic for **that artifact**, not by copying `4.2.26` or `1.9.2` into the song.

### 7.3 Assertion history

Corrections do not erase the earlier edge. A new edge assertion refers to the prior `parent_edge_id` through `supersedes_edge_id`. The effective status becomes SUPERSEDED or REVOKED, while the earlier record remains available for audit.

---

## 8. Graph classes and direction

### 8.1 Provenance DAG

The provenance graph is directed and acyclic. Registered acyclic edge types include:

- DERIVES
- SAMPLES
- MERGES
- TRAINS_ON
- INCLUDES
- COMPOSES
- REVISION_OF

Before accepting one of these edges, the federation must verify that the proposed child is not already an ancestor of the proposed parent.

This is a relational DAG. It is not a Merkle DAG. Cycle checks inspect edges. They do not hash content.

### 8.2 Associative graph

Associative relationships may contain cycles because they do not claim generative ancestry. Registered associative edge types include:

- REFERENCES
- CITES
- MENTIONS
- RESPONDS_TO
- RELATED_TO

Associative edges are not included in provenance cycle validation or topological ordering.

### 8.3 Logical direction

```text
PARENT -> CHILD
```

### 8.4 Storage direction

CHILD contains references to PARENT.

The logical graph and the storage representation describe the same relationship from different traversal perspectives.

Existing Lupopedia graph tables (`lupo_edges`, `lupo_memory_edges`) remain the current install-SQL graph. This whitepaper's parent-edge record is the provenance contract. Mapping onto install SQL is [PRD 34_B](../../prd/34_B-i_LUP_KEY_ARTIFACT_PARENT_EDGES.md) (planning). Do not invent columns here.

---

## 9. Conflict, correction, and revocation

### 9.1 Relational identity

Every parent assertion has an 18-digit `parent_edge_id`. Logical duplicate detection compares:

- `child_key`
- `parent_key`
- `edge_type`
- `role`
- `assertion_iteration`

This tuple is a relational comparison, not a cryptographic signature.

### 9.2 Conflict behavior

- Identical relational assertions are deduplicated.
- Conflicting assertions remain separate records.
- A conflict produces PROVENANCE_CONFLICT.
- A timestamp does not decide which claim is true.
- No assertion is silently overwritten.
- Federation or human policy decides whether the result is corrected, superseded, revoked, accepted as an additional relationship, or forked into another artifact iteration.

### 9.3 Correction

A correction creates a new assertion with:

- a new `parent_edge_id`
- an incremented `assertion_iteration`
- `supersedes_edge_id` referencing the earlier edge
- the corrected relationship values

### 9.4 Revocation

A revocation is recorded as a new assertion or audit event that names the earlier `parent_edge_id`. The earlier record is retained. The effective status of the earlier relationship becomes REVOKED in derived views.

### 9.5 Status

Canonical effective statuses are:

```text
ACTIVE
SUPERSEDED
REVOKED
```

Status transitions must remain auditable. Destructive deletion is forbidden. Use soft-delete doctrine (`is_deleted`, `deleted_ymdhis`) on canonical tables.

---

## 10. Canonical serialization

Lupopedia serialization is deterministic for storage, transport, comparison, and display. It is not preparation for hashing.

Rules:

- Parent relationships are represented as arrays of edge objects.
- Every edge contains `parent_edge_id` as a decimal string in JSON.
- Field names use lowercase ASCII.
- Canonical LUP KEY values use uppercase.
- `edge_type`, `role`, and `status` use uppercase.
- Timestamps use BIGINT UTC `YYYYMMDDHHIISS`.
- Parent edges serialize in ascending numeric `parent_edge_id` order.
- Null optional values serialize explicitly when the applicable schema requires stable fields.
- Database rows are authoritative; JSON is an exchange representation.
- JSON object member order has no semantic meaning.

No RFC 8785, SHA-256, multihash, CID, signature scope, or domain-separation label is required.

---

## 11. Federation synchronization

### 11.1 Merge inputs

Federation synchronization exchanges:

- artifact KEY
- color registry references when applicable
- parent-edge records
- assertion iteration
- status history
- actor attribution
- canonical timestamps

### 11.2 Merge behavior

- Merge relational records by `parent_edge_id` and their declared federation source.
- Deduplicate identical assertions.
- Preserve conflicting assertions.
- Never use newest timestamp as automatic truth.
- Never silently overwrite a local or remote assertion.
- Apply supersession and revocation only through explicit relationship records.
- Re-run provenance cycle validation after importing new acyclic edges.

### 11.3 Sovereignty

A federation may govern its own:

- artifact registry
- actor registry
- group namespace
- color names
- preferred color synonyms
- local display rules
- conflict-review policy

Every federation must still honor the shared LUP KEY grammar and exchange requirements when participating in LUP federation.

---

## 12. Validation requirements

### 12.1 Artifact KEY

The validator must require a canonical full KEY when storing or exchanging an artifact identity:

```text
PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
```

It must reject malformed token counts, illegal values, unknown protocols, invalid modes, invalid NODE values, invalid VERSION values, seven-token HEX (missing GROUP), `042010` as packed 4.2.10, and any other violation of the LUP v4.2.26 KEY specification.

Registered short forms may appear in display input. Expand them before storage. See PRT.LUP.md section 8.

### 12.2 Color identity

The validator must require:

- a registered domain (protocol SHORT)
- a registered NAME key
- a registered HEX6 key
- consistent NAME-to-HEX6 mapping within the domain
- HEX6 matching `^[0-9A-F]{6}$`

Reverse lookup may return more than one name and is not an ambiguity error unless the caller requests the preferred name and the domain has failed to designate one.

### 12.3 Parent edge

The validator must reject:

- an invalid or missing `parent_edge_id`
- a parent key that is not a complete canonical LUP KEY
- an unregistered edge type or role
- `weight_bps` outside 0-10000
- an invalid timestamp
- an invalid actor
- a nonexistent `supersedes_edge_id` when one is supplied
- a provenance self-edge
- an acyclic edge that creates a cycle
- more than 1,024 active parents for one artifact iteration

### 12.4 Display line

The human-readable line is presentation syntax. It must resolve to:

- a canonical artifact KEY
- a registered domain
- a registered domain-scoped color name

A display string that cannot resolve is not a substitute canonical identity.

---

## 13. Resource limits

Baseline limits:

- active parents per artifact iteration: 0-1024
- serialized provenance block: maximum 1 MiB
- pagination: required when relationships cannot fit within one permitted block
- traversal depth: configurable by implementation policy
- traversal must maintain a visited set to prevent repeated processing

The conceptual model supports large graphs, but no implementation is required to accept unbounded input.

---

## 14. Backward compatibility

Whitepaper v1.9.2 is semantically compatible with earlier single-parent lineage. Wire compatibility requires a version-aware parser.

Migration rule:

```text
legacy scalar parent -> one-element parents array
```

Requirements:

- legacy single-parent data is preserved
- a migrated parent receives a `parent_edge_id`
- migration records the responsible actor and timestamp
- new serializers emit the v1.9.2 array representation
- old serializers are not expected to understand multiple parents
- migration does not alter the canonical artifact KEY except when provenance correction requires a new iteration under Section 7

This is semantic compatibility, not universal backward wire compatibility.

Zero existing Lupopedia production installs. Do not add compatibility shims for invented external clients.

---

## 15. Canonical artifact object

```text
{
  "artifact_key": "CCB.NAME.000001.FOOBARREMIX.WOLFIE.MUSIC.EN.000001",
  "color": {
    "domain": "PRT.LUP",
    "name_key": "COLOR.NAME.GOLDENWOLF",
    "hex6_key": "COLOR.HEX6.D4AF37",
    "name": "GOLDENWOLF",
    "hex6": "D4AF37"
  },
  "parents": [
    {
      "parent_edge_id": "202608161026140001",
      "parent_key": "PRT.NAME.000002.DEEPBLUE.ROOT.MUSIC.EN.000001",
      "edge_type": "DERIVES",
      "role": "STRUCTURE",
      "assertion_iteration": 1,
      "status": "ACTIVE",
      "created_ymdhis": 20260816102614,
      "actor_id": 1,
      "supersedes_edge_id": null
    },
    {
      "parent_edge_id": "202608161026140002",
      "parent_key": "CCB.NAME.000003.EMERALD.ROOT.MUSIC.EN.000001",
      "edge_type": "SAMPLES",
      "role": "SAMPLE",
      "weight_bps": 4000,
      "assertion_iteration": 1,
      "status": "ACTIVE",
      "created_ymdhis": 20260816102614,
      "actor_id": 1,
      "supersedes_edge_id": null
    }
  ]
}
```

The parent-edge array is ordered by `parent_edge_id`. The child KEY is supplied once by `artifact_key`. A standalone edge exchange also includes `child_key`.

`parent_edge_id` values above are IdGenerator-shaped examples (`YYYYMMDDHHIISS` + 4-digit suffix) encoded as JSON strings.

---

## 16. Relational implementation model

### 16.1 Color registry

Conceptual record:

```text
domain
color_name
color_hex6
iso_language
is_preferred
status
actor_id
created_ymdhis
updated_ymdhis
```

Lookup:

```text
DOMAIN + COLOR.NAME.<NICKNAME> -> COLOR.HEX6.<HEX>
```

Multiple names may reference the same domain-scoped HEX6.

Current seed surface: `docs/protocols/hex/<PROTOCOL>/<PROTOCOL>.colors.csv`.

### 16.2 Parent edge

Conceptual record:

```text
parent_edge_id
child_key
parent_key
edge_type
role
weight_bps
assertion_iteration
status
created_ymdhis
actor_id
supersedes_edge_id
```

No foreign key, trigger, stored procedure, content hash, or cryptographic identifier is required. Application services validate registry references and graph constraints.

This conceptual record is **not** install SQL. Do not ALTER live schema from this whitepaper. [PRD 34_B](../../prd/34_B-i_LUP_KEY_ARTIFACT_PARENT_EDGES.md) (planning) defines table names and columns. `install_new_lupopedia.sql` receives CREATE TABLE only when that PRD leaves planning.

### 16.3 Logical duplicate signature

This is a relational comparison tuple, not a cryptographic signature:

```text
(child_key, parent_key, edge_type, role, assertion_iteration)
```

An implementation uses the tuple to detect equivalent assertions while preserving different or conflicting records.

---

## 17. Worked examples

### 17.1 Original artifact with no parents

```text
{
  "artifact_key": "CCB.NAME.000001.ORIGINALSONG.WOLFIE.MUSIC.EN.000001",
  "color": {
    "domain": "PRT.LUP",
    "name_key": "COLOR.NAME.GOLDENWOLF",
    "hex6_key": "COLOR.HEX6.D4AF37",
    "name": "GOLDENWOLF",
    "hex6": "D4AF37"
  },
  "parents": []
}
```

### 17.2 Multi-parent remix

CHILD:

```text
CCB.NAME.000001.FOOBARREMIX.WOLFIE.MUSIC.EN.000001
```

PARENTS:

```text
PRT.NAME.000002.DEEPBLUE.ROOT.MUSIC.EN.000001
CCB.NAME.000003.EMERALD.ROOT.MUSIC.EN.000001
```

Logical graph:

```text
DEEPBLUE ------ DERIVES ------> FOOBARREMIX
EMERALD ------- SAMPLES ------> FOOBARREMIX
```

### 17.3 Corrected parent assertion

Original edge:

```text
parent_edge_id: "202608161026140002"
status: ACTIVE
```

Correcting assertion:

```text
parent_edge_id: "202608161026140003"
assertion_iteration: 2
supersedes_edge_id: "202608161026140002"
status: ACTIVE
```

Derived effective view:

```text
202608161026140002 -> SUPERSEDED
202608161026140003 -> ACTIVE
```

### 17.4 Creator-facing display

```text
CC-BY ALTERNATE_FATE POWERED_BY LUPOPEDIA GOLDENWOLF
```

Resolved color domain:

```text
LUPOPEDIA (display) -> PRT.LUP (registry domain)
PRT.LUP + COLOR.NAME.GOLDENWOLF -> COLOR.HEX6.D4AF37
```

Resolved artifact KEY:

```text
CCB.NAME.000001.FOOBARREMIX.WOLFIE.MUSIC.EN.000001
```

---

## 18. Final architecture

### 18.1 Artifact identity

Eight-token LUP KEY.

Checked properties:

- eight fixed positions
- declared semantic identity
- registered protocol and node
- explicit actor, group, language, and version
- no color or provenance payload inside the KEY

### 18.2 Color identity

Domain-scoped COLOR.NAME -> COLOR.HEX6 relational lookup.

Checked properties:

- domain present (protocol SHORT)
- NAME and HEX6 keys present
- many names may share one HEX6
- no hashing

### 18.3 Provenance

Relational parent edges identified by `parent_edge_id`.

Checked properties:

- complete canonical parent KEY
- actor and timestamp attribution
- assertion iteration
- correction and revocation links
- zero to 1,024 parents
- no content-addressed identity

### 18.4 DAG validation

- normal relational graph traversal
- provenance edges remain acyclic
- associative edges may be cyclic
- cycle detection never depends on hashing

### 18.5 Federation synchronization

- merges relational edge records
- preserves conflicts
- respects domain sovereignty
- rejects silent overwrites
- revalidates imported provenance edges

### 18.6 Corrections

- new assertions supersede or revoke earlier edges
- earlier records remain auditable
- accepted lineage changes create new artifact iterations
- identity continuity remains declared and relational

---

## 19. Reference addendum and editorial notes

### 19.1 Normative terminology

- **Artifact KEY:** Canonical eight-token Lupopedia identity.
- **Color identity:** Domain-scoped relational mapping between a registered name and HEX6.
- **Parent edge:** Relational assertion connecting one canonical parent KEY to one canonical child KEY.
- **Provenance DAG:** Directed acyclic graph containing generative ancestry edges. Not a Merkle DAG.
- **Associative graph:** Relationship graph whose edges do not claim generative ancestry and may contain cycles.
- **Assertion iteration:** Revision number of a parent relationship assertion.
- **Artifact iteration:** Immutable versioned state identified by the KEY's VERSION token.

### 19.2 KAPU scope

KAPU: HASHING applies to required identity and provenance architecture. It prohibits:

- content-derived artifact identity
- hash-derived parent identity
- hash authority for conflict resolution
- required Merkle DAGs or CIDs
- mandatory cryptographic commitments
- treating a checksum as proof of semantic truth

Optional checksum metadata remains outside identity and has no canonical authority.

Password hashing, TLS, and git are host tools. They are not LUP identity.

### 19.3 Version discipline

- LUP KEY specification version: 4.2.26
- This whitepaper version: 1.9.2 (packed `010902`)
- Header contract: 4.2.11
- Product atom: 4.2.11
- Parent assertion iteration: local integer within one logical relationship history
- Artifact VERSION: packed `0xMMmmPP` for **that artifact**

These values are independent and must not be substituted for one another.

### 19.4 Pono statement

The Pono Edition favors the smallest architecture that preserves identity, meaning, provenance, correction, and federation sovereignty.

Human language at the surface. Registered meaning underneath. Relational truth at the boundary.

PONO is constitutional ethics language (PRD 82_B). It is not a KEY token.

### 19.5 Corrections applied in this file (review of the v1.9.2 paste)

The pasted draft was accepted as architecture. These corrections are binding in this canonical file:

1. Eight-token KEY always. GROUP is stored. Seven-token HEX is invalid.
2. Packed VERSION is `0xMMmmPP`. `042010` is invalid. Example iteration `000001` means packed 0.0.1, not KEY spec 4.2.26.
3. Display PROTOCOL `CC-BY` maps to KEY PROTOCOL `CCB`. Display DOMAIN `LUPOPEDIA` maps to color domain `PRT.LUP`.
4. Color nicknames such as GOLDENWOLF are illustrative until registered. They are not silently added to `PRT.LUP.colors.csv`.
5. JSON `parent_edge_id` is a decimal string. 18-digit integers are not safe as JSON numbers.
6. `parent_edge_id` follows PRD 80 IdGenerator shape (18 digits: packed UTC + 4-digit suffix).
7. No foreign keys, triggers, AUTO_INCREMENT, or schema from this whitepaper alone.
8. KAPU: HASHING is scoped to identity and provenance authority.
9. ASCII only. No em dash, no escaped YAML.
10. This document does not override PRD 16_C. KEY expansion remains REGISTERED_SHORT_FORMS_ONLY.

### 19.6 Normative file map

| Need | File |
|------|------|
| KEY grammar | [PRT.LUP.md](PRT.LUP.md) |
| Zeros / operator KEY spec | [PRT.HEX.000000.000000.000000.ROOT.EN.04020B.md](../../../PRT.HEX.000000.000000.000000.ROOT.EN.04020B.md) |
| Header contract | [16_C-i_LUPOPEDIA_HEADERS.md](../../prd/16_C-i_LUPOPEDIA_HEADERS.md) section 4.2.6 |
| Color CSV spec | [HEX.COLORS.md](../hex/HEX.COLORS.md) |
| Color seed | [PRT.LUP.colors.csv](../hex/PRT.LUP/PRT.LUP.colors.csv) |
| Database PK / IdGenerator | [80_A-i_DATABASE_DESIGN_DOCTRINE.md](../../prd/80_A-i_DATABASE_DESIGN_DOCTRINE.md) |
| Parent-edge tables (planning) | [34_B-i_LUP_KEY_ARTIFACT_PARENT_EDGES.md](../../prd/34_B-i_LUP_KEY_ARTIFACT_PARENT_EDGES.md) |
| Hawaiian fields (not KEY) | [82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md](../../prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md) |
| External KEY guide | [LUPOPEDIA.FOR.EXTERNAL.DOMAINS.md](../../../LUPOPEDIA.FOR.EXTERNAL.DOMAINS.md) |

If this whitepaper and PRD 16_C disagree on KEY grammar, **PRD 16_C plus the 4.2.26 expansion in PRT.LUP.md win**. If this whitepaper and install SQL disagree on tables, **install SQL wins until a PRD updates it**.

---

## VARIANTS INDEX

Navigation-only list of TYPE.LANG variants of this artifact. Not doctrine. Does not override PRDs. Does not change header authority.

### MUSIC VARIANTS

- (none yet)

### VIDEO VARIANTS

- (none yet)

### WEB VARIANTS

- (none yet)

### DOCUMENT VARIANTS

- DOC.01.EN  This whitepaper (`docs/protocols/lup/lupopedia_whitepaper_v1_9_2.md`)

### NOTES

- Variant indexes are navigation-only.
- Do not modify lupopedia.headers, lupopedia.metadata, or lupopedia.map.
- ASCII-safe dot grammar only. No pipes. No middle-dot. No hyphens in variant IDs.
