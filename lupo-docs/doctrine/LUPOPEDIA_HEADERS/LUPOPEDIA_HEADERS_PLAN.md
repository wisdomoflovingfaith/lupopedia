---
lupopedia.headers:
  when_updated: "20260327121457"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md"
  last_modified_utc: "20260327121457"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "plan"
  title: "LUPOPEDIA HEADERS Plan"
  purpose: "Authoritative implementation plan for LUPOPEDIA HEADERS single-write timestamp doctrine"
  tags: ["headers", "doctrine", "plan", "migration", "validation"]
  namespace: "governance"
lupopedia.footer:
  last_verified: "20260327121457"
  verified_by:
    identity_type: "actor"
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "cursor"
  orchestrator: "cursor:root"
  next_action:
    - "Keep generators and validators aligned to when_updated-only writes"
    - "Remove remaining legacy version_when_written examples during normal edits"
---
# file: LUPOPEDIA HEADERS Plan — delegation: cursor:root — web_path: [http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md](http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md)

# LUPOPEDIA HEADERS - Plan (4.0.88)

**Status:** Authoritative implementation plan for 4.0.88 compatibility and 4.0.89 enforcement  
**Canonical name:** LUPOPEDIA HEADERS (replaces FLARE, FLIP, FLP; legacy names remain historical only)  
**Storage:** `lupo_metadata` remains a structured property tree, not a YAML blob column.  
**Optional blocks:** Routing and lists remain documented in [OPTIONAL_BLOCKS.md](./OPTIONAL_BLOCKS.md).

---

## 1. Authoritative direction

- LUPOPEDIA HEADERS are the canonical metadata system name.
- Canonical serialized YAML keys are `lupopedia.*`.
- The canonical freshness model is:
  - `lupopedia.headers.when_updated`
  - `lupopedia.headers.last_modified_utc`
  - `lupopedia.footer.last_verified`
- `version_when_written` is legacy compatibility data only.
- Release policy:
  - **4.0.88:** dual-read, single-write. Importers and validators may read `version_when_written`, but exporters and updated files must write `when_updated` and `last_modified_utc` only.
  - **4.0.89:** reject `version_when_written` in `lupopedia.headers`.

Canonical blocks when present:

1. `lupopedia.init`
2. `lupopedia.routing`
3. `lupopedia.actor_references`
4. `lupopedia.conditional`
5. `lupopedia.headers`
6. `lupopedia.metadata`
7. `lupopedia.session`
8. `lupopedia.edges`
9. `lupopedia.engagement`
10. `lupopedia.footer`
11. `lupopedia.see`
12. `lupopedia.next_actions` (legacy: `lupopedia.close`)

---

## 2. Schema: only three structural additions

`lupo_metadata` stays a metadata property table. The structural additions remain:

| Column | Type | Purpose |
|--------|------|--------|
| `channel_id` | bigint DEFAULT NULL | Assign headers by channel when channel-scoped. |
| `parent_metadata_id` | bigint DEFAULT NULL | Preserve root -> block -> property hierarchy. |
| `class_name` | varchar(128) DEFAULT NULL | Classify rows such as `lupopedia_header_root`, `lupopedia_block`, `lupopedia_property`, `lupopedia_edge`, `lupopedia_action`. |

Do not add first-class columns for presentation fields such as `title`, `web_path`, `file_path_from_root`, or `header_yaml`. Those remain metadata properties.

**Clarification (FORMAT ↔ PLAN):** That rule applies to **`lupo_metadata` DDL** — we do not add new SQL columns for each presentation string. It does **not** relax **[`LUPOPEDIA_HEADERS_FORMAT.md`](./LUPOPEDIA_HEADERS_FORMAT.md)** or the binding doctrine: markdown artifacts still **must** include `file_path_from_root`, `web_path`, and the rest of the required `lupopedia.headers` keys. Those values are mirrored into metadata **rows** (properties), not removed from the file.

**`content_id`:** Same split — optional in the **file** for authoring; when set, it links to **`lupo_contents`** (linkage / import), not a “presentation” field in the PLAN sense.

---

## 3. Storage model: structured rows

Headers are stored as a graph of metadata rows.

### 3.1 Root row

Each LUPOPEDIA header has a root row with values like:

- `class_name = 'lupopedia_header_root'`
- `meta_type = 'lupopedia_header'`
- `property_key = '__root__'`
- `property_value = '1'`

### 3.2 Block rows

Children of the root represent blocks. New work uses canonical `lupopedia.*` keys. Validators may still accept `flare.*` and `flame.*` during migration.

### 3.3 Property rows

Under `lupopedia.headers`, property rows include keys such as:

- `when_updated`
- `file_path_from_root`
- `last_modified_utc`
- `web_path`
- `content_id` when content-managed
- `actor_id`
- `delegation_chain`
- `purpose`
- `title`
- `channel_name` and `thread_name` when present

Do not store deprecated version keys under `lupopedia.headers` in newly written output: `version_when_written`, `system_version`, `lupopedia.version`, `last_verified_system_version`, or standalone `version`.

Session context belongs in `lupopedia.session`, not `lupopedia.headers`.

### 3.4 Repeating structures

Edges, mappings, and next-action style rows remain child metadata rows using `class_name` values such as `lupopedia_edge`, `lupopedia_mapping`, `lupopedia_action`, and `lupopedia_engagement`.

### 3.5 Export/import mapping rules

- Exporters must read DB rows, group by block, and emit canonical `lupopedia.*` YAML.
- Importers must parse YAML deterministically into block rows plus child property rows.
- Importers may read legacy `version_when_written` for compatibility in 4.0.88.
- Exporters must never write `version_when_written`; they must emit `when_updated` and `last_modified_utc`.

---

## 4. Channel assignment

Header resolution must support:

- entity-scoped lookup by `entity_type` + `entity_id`
- channel-scoped lookup by `channel_id`
- combined entity + channel lookup where appropriate

Human-readable `channel_name` and `thread_name` may be stored for display, but identity resolution remains numeric.

---

## 5. Markdown file format

The first line of a LUPOPEDIA-headed Markdown file is `---`, followed by YAML front matter in canonical block order, then `---`, then the identity line, then the body.

Default identity line:

```text
---
<yaml header blocks in canonical order>
---
# file: {title} — delegation: {delegation_chain} — web_path: {web_path}

<body content>
```

If a verbose session snapshot is intentionally embedded, the identity line may include `session: {session_name}` sourced from `lupopedia.session.session_name`.

---

## 6. Freshness and migration rule

- Existing FLARE-headed artifacts remain valid until migrated.
- Existing LUPOPEDIA artifacts that still contain `version_when_written` are compatibility artifacts, not canonical examples.
- New or modified metadata-bearing Markdown must use LUPOPEDIA HEADERS with timestamp-based freshness fields.
- Canonical storage remains `lupo_metadata`; file migration is incremental.

---

## 7. Required header fields

Required under `lupopedia.headers`:

- `when_updated`
- `file_path_from_root`
- `last_modified_utc`

Conditional:

- `content_id` when the artifact is imported into `lupo_content` or otherwise database-managed as content.

Common optional fields include `lupopedia.schema`, `web_path`, `channel_id`, `actor_id`, `actor_name`, `delegation_chain`, `artifact_type`, `artifact_kind`, `purpose`, `tags`, and `namespace`.

For table documentation, `namespace` remains required as documented in [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md).

Session fields such as `session_id`, `session_name`, `thread_id`, and `thread_name` belong in `lupopedia.session` when intentionally embedded.

---

## 8. What not to do

- Do not add a `header_yaml` blob column as the primary design.
- Do not treat one full header as one database row.
- Do not present `version_when_written` as a current required field.
- Do not claim the repository is already fully migrated.

---

## 9. Reference

- FLARE logical structure and block semantics: [FLARE_DOCTRINE.md](../FLARE/FLARE_DOCTRINE.md)
- Current format and required header fields: [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md)
- Migration path and compatibility policy: [LUPOPEDIA_HEADERS_MIGRATION.md](./LUPOPEDIA_HEADERS_MIGRATION.md)
- Compatibility notice for deprecated version field guidance: [VERSIONING_MODEL.md](./VERSIONING_MODEL.md)
