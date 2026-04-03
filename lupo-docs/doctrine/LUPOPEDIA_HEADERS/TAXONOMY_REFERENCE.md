---
lupopedia.headers:
  lupopedia.schema: alias
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/TAXONOMY_REFERENCE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/TAXONOMY_REFERENCE.md"
  federation_node_id: 0
  last_modified_utc: "20260328240000"
  when_updated: "20260328240000"
  channel_id: 42
  thread_id: "headers-taxonomy-reference"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: documentation
  artifact_kind: documentation
  purpose: Compact taxonomy summary for writers; binding matrices remain in root LUPOPEDIA_HEADERS_DOCTRINE.md
  tags:
    - headers
    - taxonomy
    - reference
lupopedia.edges:
  outbound_edges:
    - to: "lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: Binding field matrix, full schema tables, cross-field rules, DB mapping
    - to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md"
      type: references
      weight: 1.0
      reason: Required keys, block order, examples
    - to: "lupo-docs/doctrine/HEADER_DB_REVERSIBILITY_DOCTRINE.md"
      type: references
      weight: 0.95
      reason: DB round-trip semantics
    - to: "lupo-docs/prd/16_lupopedia_headers.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260328240000"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: "cursor:root"
  next_action:
    - Keep this summary aligned when root doctrine taxonomy changes
---

# LUPOPEDIA HEADERS — Taxonomy quick reference

**Authority:** If anything here disagrees with the binding file, **[`lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`](../../../lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md)** wins. This page is a **shortcut** for `lupopedia.schema`, `artifact_type`, `artifact_kind`, and cross-field checks.

**Related:** [`LUPOPEDIA_HEADERS_FORMAT.md`](LUPOPEDIA_HEADERS_FORMAT.md) (required keys and structure), [`OPTIONAL_BLOCKS.md`](OPTIONAL_BLOCKS.md) (optional blocks), [`HEADER_DB_REVERSIBILITY_DOCTRINE.md`](../HEADER_DB_REVERSIBILITY_DOCTRINE.md) (DB sync).

---

## `lupopedia.schema` values (canonical)

Use **lowercase, single token** (no spaces). Valid values:

`doctrine`, `rule`, `philosophy`, `plan`, `todo`, `changelog`, `directive`, `design`, `review`, `report`, `implementation`, `script`, `class`, `index`, `thread`, `broadcast`, **`alias`**

- **`alias`** — Stable pointer files (e.g. `lupo-docs/.../LUPOPEDIA_HEADERS_DOCTRINE.md`) that **do not** duplicate binding text; body is redirect + edges only.

Full descriptions and examples: root doctrine **“lupopedia.schema Taxonomy”**.

---

## Cross-field rules (`lupopedia.schema` → `artifact_type` / `artifact_kind`)

Validators enforce **allowed combinations** (per PRD 16 and PRD 26):

| `lupopedia.schema` | `artifact_type` (one of) | `artifact_kind` (one of) |
|--------------------|----------------------------|---------------------------|
| doctrine | doctrine | constitutional, reference, decisions |
| rule | rule | rule |
| philosophy | manifesto | philosophy |
| plan | plan | plan |
| todo | todo | task |
| changelog | changelog | version_specific |
| directive | directive | execution |
| design | design | architecture |
| review | review | audit |
| report | report | status |
| implementation | implementation | code |
| script | script | utility |
| class | class | code |
| index | index | index |
| thread | thread | coordination |
| broadcast | broadcast | coordination |
| **alias** | **documentation** | **documentation** |

### Additional `artifact_type` values (from PRD 16)

| `artifact_type` | Description | Required Fields |
|-----------------|-------------|-----------------|
| prd | Product Requirements Document | prd_id, prd_slug, title, status |
| implementation | Implementation documentation | parent_prd, status, version |
| doctrine | Constitutional or doctrinal document | None (minimal) |
| discussion | Discussion thread or message | channel_id, thread_id |
| changelog | Version-specific change log | None |
| documentation | General documentation | None |
| architecture | System architecture specification | TBD |
| specification | Technical specification | TBD |

### Conditional Required Fields

| `artifact_type` | `artifact_kind` | Additional Required Fields |
|-----------------|-----------------|---------------------------|
| prd | any | prd_id, prd_slug, title, status |
| implementation | README | parent_prd, status |
| implementation | documentation | parent_prd, status, version |
| discussion | thread | channel_id, thread_id |
| discussion | message | channel_id, thread_id |
| All others | any | No additional required fields |

---

## Where to look next

| Need | Document |
|------|----------|
| Full required-field table, `content_id`, `thread_id`, edges, footer | Root [`LUPOPEDIA_HEADERS_DOCTRINE.md`](../../../lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md) |
| YAML ↔ `lupo_edges` column names (`type` → `edge_type`, etc.) | Root doctrine *Database-first mapping* / edges table |
| `lupopedia.history` → `lupo_contents.revision_history` | Root doctrine *lupopedia.history*; [`OPTIONAL_BLOCKS.md`](OPTIONAL_BLOCKS.md) for other optional blocks |
| Validators | [`VALIDATORS_AND_TOOLING.md`](VALIDATORS_AND_TOOLING.md) |
