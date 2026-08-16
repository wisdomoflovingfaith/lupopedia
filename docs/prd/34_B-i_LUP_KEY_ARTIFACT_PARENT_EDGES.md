---
lupopedia.headers:
  header_format_version: "4.2.11"
  path_from_lupopedia_root: docs/prd/34_B-i_LUP_KEY_ARTIFACT_PARENT_EDGES.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/34_B-i_LUP_KEY_ARTIFACT_PARENT_EDGES.md
  status: planning
  when_updated: "20260816104950"
  trust_tier: proposed
  questions_toon: null
  memory_toon: memory/development/canonical/1026/08/34_b_lup_key_artifact_parent_edges.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/prd-34-b-parent-edges
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: prd
  prd_cluster: 16_C_34_A_34_B_42_A_38_A_80_A_09_A
  title: "PRD 34_B: LUP KEY artifact parent edges (planning)"
  summary: "Planning PRD for KEY-to-KEY provenance tables. Does not change install SQL yet. Distinct from lupo_edges and lupo_memory_edges. Aligns with whitepaper v1.9.2 and KEY spec 4.2.26."
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
  LUP.HEX: PRT.HEX.000001.000024.000000.ROOT.EN.000001
  LUP.SHORT: PRT.LUP
  LUP.ROOT: PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
  LUP.OMIT: REGISTERED_SHORT_FORMS_ONLY
  LUP.DEFAULTS: PRT.NAME.000000.000000.ROOT.ROOT.EN.0
  key_specification_version: "4.2.26"
lupopedia.map:
  index: PRT.HEX.000001.000024.000000.ROOT.EN.000001
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/34_B-i_LUP_KEY_ARTIFACT_PARENT_EDGES.md
  path_from_lupopedia_root: docs/prd/34_B-i_LUP_KEY_ARTIFACT_PARENT_EDGES.md
  prd_cluster: 16_C_34_A_34_B_42_A_38_A_80_A_09_A
  edges_toon: null
  memory_toon: memory/development/canonical/1026/08/34_b_lup_key_artifact_parent_edges.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/prd-34-b-parent-edges
  questions_toon: null
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
---
# PRD 34_B: LUP KEY artifact parent edges

**Status:** planning. Not active. No install SQL. No PHP implementation. No ALTER TABLE.

**Architecture source:** [lupopedia_whitepaper_v1_9_2.md](../protocols/lup/lupopedia_whitepaper_v1_9_2.md)

**Does not override:** PRD 16_C KEY grammar, PRD 38 memory graph, PRD 42 `lupo_edges`, PRD 80 PK doctrine, PRD 09 federation node tables.

**Product atom:** `GLOBAL_CURRENT_LUPOPEDIA_VERSION` remains **4.2.11**.

---

## 0. Why this PRD exists

The v1.9.2 whitepaper defines multi-parent provenance as **KEY-to-KEY parent-edge records**.

That contract is **not** already a table in install SQL.

Existing graph tables are different identity surfaces:

| Table | PRD | Endpoints | Identity |
|-------|-----|-----------|----------|
| `lupo_edges` | 42 / 72 | `left_object_type` + `left_object_id`, `right_object_type` + `right_object_id` | Polymorphic BIGINT objects (content, question, actor, ...) |
| `lupo_memory_edges` | 38 | `from_memory_node_id`, `to_memory_node_id` | Memory nodes only |
| `lupo_parent_edges` (this PRD) | 34_B | `child_key`, `parent_key` | Complete eight-token LUP KEYs |

Do **not** overload `lupo_edges` with LUP KEY strings. Object-id polymorphism cannot store `CCB.NAME.000001.FOOBARREMIX.WOLFIE.MUSIC.EN.000001` as a BIGINT. A mapping layer may later project a parent-edge onto `lupo_edges` **after** both artifacts have `content_id` rows. That projection is optional and later.

PRD 43 (Parent-Child Trust Ladder) is a **trust** model, not this provenance table.

---

## 1. Goal

When this PRD leaves `planning`:

1. Install SQL gains `lupo_parent_edges` (and a small type registry).
2. Application services validate eight-token KEYs, cycle rules, weights, and status.
3. Federation sync (PRD 09 / 34_A) can exchange parent-edge records without hashing.
4. JSON APIs encode 18-digit IDs as decimal strings.

Until then, the whitepaper remains architecture only.

---

## 2. Out of scope (this planning file)

- Writing `install_new_lupopedia.sql`
- One-time migrations / ALTER TABLE
- PHP classes
- Color registry SQL (HEX.COLORS.csv remains the color seed; a color-SQL PRD is separate)
- Changing `lupo_edges` or `lupo_memory_edges` DDL
- Content-addressed IDs, Merkle DAGs, CIDs, RFC 8785

---

## 3. Dependencies (must exist before implementation)

| Depends on | Why |
|------------|-----|
| PRD 16_C section 4.2.6 + 4.2.26 expansion | Eight-token KEY; REGISTERED_SHORT_FORMS_ONLY |
| [PRT.LUP.md](../protocols/lup/PRT.LUP.md) section 8 | Short-form expansion algorithm |
| PRD 80 | `parent_edge_id` = IdGenerator 18-digit BIGINT; no AUTO_INCREMENT; no FK |
| PRD 00 / 75 | BIGINT UTC `YYYYMMDDHHIISS`; soft delete |
| Whitepaper v1.9.2 | Edge types, DAG vs associative, correction/revocation |
| PRD 09 / 34_A | Federation exchange of rows (after this table exists) |
| PRD 99 | Parent cap 1024; 1 MiB provenance block |

Implementation phases (dependency order, no calendar estimates):

1. **Normative freeze** -- this PRD reviewed; status still planning until Captain ALII sets `status: development`.
2. **Type registry** -- closed enums for `edge_type`, `role`, `status`, DAG vs associative.
3. **Install SQL** -- CREATE TABLE only; required-tables audit; seed type rows if needed.
4. **PHP service** -- KEY validate, cycle check, upsert-by-id, JSON string IDs.
5. **Federation payload** -- add parent-edge records to sync (PRD 09 update).
6. **Optional projection** -- map KEY edges onto `lupo_edges` when both ends have `content_id`.

---

## 4. Identity rules (normative for this table)

Stored `child_key` and `parent_key` MUST be complete eight-token LUP KEYs:

```text
PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
```

Reject:

- token count not 8
- seven-token HEX (missing GROUP)
- unregistered short forms stored as-is (`PRT.LUP` is display; expand before INSERT)
- `042010` as packed 4.2.10
- hyphens, pipes, middle-dots
- color nicknames (`GOLDENWOLF`) as parent identity
- display lines (`CC-BY ALTERNATE_FATE POWERED_BY LUPOPEDIA GOLDENWOLF`)

Expand registered shorts in application code, then store FULL_KEY only.

VERSION on the artifact KEY is packed `0xMMmmPP` for **that artifact**. Parent-set changes create a new child KEY (new VERSION). Edge correction uses `assertion_iteration` + `supersedes_edge_id` without rewriting history.

---

## 5. Proposed tables

Planning names. Final CREATE TABLE belongs in install SQL when status is development. Column list is the contract.

### 5.1 `lupo_parent_edges`

PK: `parent_edge_id` (matches table singular + `_id`).

| Column | Type (planning) | Rule |
|--------|-----------------|------|
| `parent_edge_id` | BIGINT | IdGenerator. Runtime 18-digit. JSON string. Not AUTO_INCREMENT. |
| `federation_node_id` | BIGINT | Declaring node. Application-managed. |
| `child_key` | VARCHAR(255) | Full eight-token KEY. Uppercase. |
| `parent_key` | VARCHAR(255) | Full eight-token KEY. Uppercase. |
| `edge_type` | VARCHAR(32) | Registered. Uppercase. |
| `role` | VARCHAR(32) | Registered. Uppercase. |
| `has_weight` | TINYINT | 0 = weight omitted. 1 = `weight_bps` is meaningful. |
| `weight_bps` | INT | 0-10000 when `has_weight` = 1. Else 0. |
| `assertion_iteration` | INT | Starts at 1. Increment on correction. |
| `status` | VARCHAR(16) | ACTIVE / SUPERSEDED / REVOKED |
| `supersedes_edge_id` | BIGINT | 0 = none. Else earlier `parent_edge_id`. |
| `actor_id` | BIGINT | Assertion actor. |
| `created_ymdhis` | BIGINT | Packed UTC. For IdGenerator rows, equals 14-digit PK prefix. |
| `updated_ymdhis` | BIGINT | Packed UTC. Application-set. |
| `is_deleted` | TINYINT | Soft delete. Default 0. |
| `deleted_ymdhis` | BIGINT | Default 0. |

No FOREIGN KEY. No trigger. No view. No UNSIGNED. No display widths. No DATETIME.

Index planning (application-named, not uniqueness-enforced by FK):

- (`child_key`, `status`, `is_deleted`)
- (`parent_key`, `is_deleted`)
- (`supersedes_edge_id`)
- (`federation_node_id`, `created_ymdhis`)

Logical duplicate tuple (application, not UNIQUE constraint required):

```text
(child_key, parent_key, edge_type, role, assertion_iteration)
```

Active parent cap: 1024 rows where `child_key` = X AND `status` = ACTIVE AND `is_deleted` = 0.

Self-edge forbidden: `child_key` != `parent_key`.

### 5.2 `lupo_parent_edge_types`

Small registry. Seeded. Explicit IDs. Not AUTO_INCREMENT.

| Column | Type (planning) | Rule |
|--------|-----------------|------|
| `parent_edge_type_id` | BIGINT | Explicit seed ID. |
| `edge_type` | VARCHAR(32) | Unique in application. Uppercase. |
| `graph_class` | VARCHAR(16) | DAG or ASSOCIATIVE |
| `status` | VARCHAR(16) | ACTIVE / RETIRED |
| `created_ymdhis` | BIGINT | |
| `updated_ymdhis` | BIGINT | |
| `is_deleted` | TINYINT | |
| `deleted_ymdhis` | BIGINT | |

Seed DAG types: DERIVES, SAMPLES, MERGES, TRAINS_ON, INCLUDES, COMPOSES, REVISION_OF.

Seed associative types: REFERENCES, CITES, MENTIONS, RESPONDS_TO, RELATED_TO.

Cycle validation runs only when `graph_class` = DAG.

### 5.3 Optional later: `lupo_parent_edge_roles`

Same pattern as types if roles grow beyond STRUCTURE / SAMPLE. May stay as a closed enum in PHP until a second table is justified.

---

## 6. JSON exchange

`parent_edge_id` and `supersedes_edge_id` (when non-zero) MUST be decimal strings in JSON. IEEE-754 numbers are unsafe for 18-digit IDs.

`created_ymdhis` MAY be a JSON number (14 digits are safe).

Example (not live data):

```text
{
  "parent_edge_id": "202608161049500001",
  "child_key": "CCB.NAME.000001.FOOBARREMIX.WOLFIE.MUSIC.EN.000001",
  "parent_key": "PRT.NAME.000002.DEEPBLUE.ROOT.MUSIC.EN.000001",
  "edge_type": "DERIVES",
  "role": "STRUCTURE",
  "has_weight": 0,
  "weight_bps": 0,
  "assertion_iteration": 1,
  "status": "ACTIVE",
  "supersedes_edge_id": "0",
  "created_ymdhis": 20260816104950,
  "actor_id": 1
}
```

---

## 7. Application validation (no database logic)

Before INSERT/UPDATE of an ACTIVE DAG edge:

1. Expand any display short form. Store eight tokens only.
2. Validate both KEYs (PRD 16_C / PRT.LUP.md).
3. `edge_type` and `role` must be registered and not deleted.
4. If `has_weight` = 1, `weight_bps` in 0-10000.
5. Reject self-edge.
6. If DAG: reject if child is already an ancestor of parent (cycle).
7. Reject if ACTIVE parent count for `child_key` would exceed 1024.
8. If `supersedes_edge_id` != 0, that row must exist, same `child_key`, and not be a hard mystery ID.
9. Do not delete the superseded row. Set its `status` to SUPERSEDED or REVOKED in a separate explicit UPDATE.
10. Timestamps from `gmdate('YmdHis')`. IDs from `IdGenerator::generate()` (or `toCanonicalId` when living canonical).

KAPU: HASHING -- no content hash as KEY, parent identity, or merge authority. Optional checksum column is forbidden in v1 of this table.

---

## 8. Correction and iteration

- Correcting an edge: new `parent_edge_id`, `assertion_iteration` + 1, `supersedes_edge_id` = old id.
- Changing accepted parent **set** of an artifact: new child KEY VERSION (packed increment for that artifact), new ACTIVE edges pointing at the new `child_key`. Old KEY remains queryable.
- Soft delete only.

---

## 9. Federation

After this table exists, PRD 09 / 34_A SHOULD add:

- sync unit = parent-edge row + declaring `federation_node_id`
- merge by `parent_edge_id` + source node
- keep conflicts as separate rows (PROVENANCE_CONFLICT)
- never newest-timestamp-wins
- re-run DAG cycle check after import

Not in this planning file: wire format details. Those wait for `status: development`.

---

## 10. What this PRD must not do

- Mint `lupo_parent_edges` by editing install SQL while status is planning
- Treat `lupo_edges.edge_id` as `parent_edge_id`
- Store KEYs in `left_object_id`
- Use JSON numbers for 18-digit IDs
- Put color HEX in the KEY or in this table
- Add FK, triggers, UNIQUE constraints that encode cycle rules
- Require hashing for identity or sync

---

## 11. Open decisions (Captain ALII)

1. Table prefix name `lupo_parent_edges` vs `lupo_artifact_parent_edges`.
2. When to project onto `lupo_edges` (never / after content_id exists / never).
3. Whether associative edges share this table (`graph_class`) or a second table.
4. Living-canonical vs staging IdGenerator year band for published provenance.
5. Whether `child_key` / `parent_key` also store a shadow `content_id` (default: no; KEY is identity).

---

## 12. Acceptance (when implementation is authorized)

- Install SQL CREATE TABLE matches section 5.
- Seed type rows have explicit IDs.
- Required-tables list updated if the table is runtime-required.
- Validator rejects seven-token HEX and unexpanded shorts.
- Cycle test: A->B->C plus C->A rejected.
- JSON fixture uses string IDs.
- No product-atom bump required for this PRD alone.

---

## 13. References

- Whitepaper: [lupopedia_whitepaper_v1_9_2.md](../protocols/lup/lupopedia_whitepaper_v1_9_2.md)
- KEY: [PRT.LUP.md](../protocols/lup/PRT.LUP.md), [16_C-i_LUPOPEDIA_HEADERS.md](16_C-i_LUPOPEDIA_HEADERS.md) section 4.2.6
- Graph today: [42_A-i_CONTENT_SEEDING_AND_TRUTH_TABLES.md](42_A-i_CONTENT_SEEDING_AND_TRUTH_TABLES.md) section 3.5, [38_A-i_MEMORY_UNIFICATION.md](38_A-i_MEMORY_UNIFICATION.md) section 5.2
- PK: [80_A-i_DATABASE_DESIGN_DOCTRINE.md](80_A-i_DATABASE_DESIGN_DOCTRINE.md)
- Federation tables today: [09_A-i_FEDERATION_SYNC.md](09_A-i_FEDERATION_SYNC.md)
- Numbering: [84_A-i_PRD_NUMBER_ALLOCATION_DOCTRINE.md](84_A-i_PRD_NUMBER_ALLOCATION_DOCTRINE.md) -- group 34 is federation / semantic network (block 30-39)
