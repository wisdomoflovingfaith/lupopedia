---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS.md"
  web_path: "http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS"
  system_version: "4.0.71"
  last_modified_utc: "20260312"
  channel_id: 42
  actor_id: 1003
  artifact_type: "doctrine"
  artifact_kind: "reference"
  purpose: "Optional LUPOPEDIA HEADERS blocks: routing and lists (carried over from FLARE)."
  tags: ["lupopedia_headers", "routing", "lists", "optional", "4.0.71"]
lupopedia.footer:
  version: "4.0.71"
  last_verified: "20260312"
  next_action:
    - "Use lupopedia.routing / lupopedia.lists when adding new header blocks"
    - "Validate optional blocks against LUPOPEDIA_HEADERS_FORMAT §5"
---
# file: Optional blocks — web_path: http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS

# LUPOPEDIA HEADERS — Optional blocks (routing, lists)

Functionality that existed in the deprecated **FLARE** system is fully supported in LUPOPEDIA HEADERS. The following optional blocks may be used when needed; they are not required for every file.

---

## lupopedia.routing (optional)

Tracks lifecycle, delivery, and delegation of artifacts across the multi-agent ecosystem. Use **`lupopedia.routing`** in YAML (legacy name was `flare.routing`).

| Field | Type | Description | Example |
|-------|------|-------------|---------|
| to | array | Primary recipients (actor_ids or slugs) | `["all", "captain"]` |
| from | integer/string | Originating actor_id or slug | `1006` |
| forwarded_from | integer/string | Original sender if rebroadcast | `1004` |
| delegation_chain | array | Sequence of authority (Root → Higher → Executor) | `[1, 10000, 1006]` |
| channel_id | integer | Target channel ID | `42` |
| thread_id | mixed | Discussion thread ID | `"DEVELOPMENT_CYCLE_4_0_55"` |
| read_by | array | Actors who have acknowledged the message | `[1006, 10000]` |
| routing_path | array | Logical or physical directories traversed | `["lupo-channels/42/threads/"]` |

**Canonical order:** If present, place `lupopedia.routing` after `lupopedia.headers` and before `lupopedia.session` (or per LUPOPEDIA_HEADERS_PLAN block order).

---

## lupopedia.lists (optional)

Links to external CSV-based history and discussion records. Use **`lupopedia.lists`** in YAML (legacy name was `flare.lists`).

| Field | Type | Description | Example |
|-------|------|-------------|---------|
| file.dialog | string | Path to discussion CSV | `"thread_dialog.csv"` |
| file.history | string | Path to change history CSV | `"thread_history.csv"` |
| file.actors | string | Path to actors list CSV | `"thread_actors.csv"` |

**Canonical order:** If present, place after `lupopedia.edges` or before `lupopedia.footer` as documented in LUPOPEDIA_HEADERS_PLAN.

---

## lupopedia.footer — next_action (required) and engagement (optional)

When **`lupopedia.footer`** is present, it MUST include **`next_action:`** — a list of 1–3 suggested next actions (contextual, forward-looking). See [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) §5.

In addition, footer MAY include engagement fields (legacy FLARE engagement):

| Field | Type | Description |
|-------|------|-------------|
| next_action | list (required) | 1–3 suggested next actions; contextual and forward-looking |
| last_verified | string | Last verification date (YYYYMMDD) |
| last_verified_by | string | Actor who verified |
| view_count | integer | Total view count |
| like_count | integer | Total like count |
| share_count | integer | Total share count |

---

## lupopedia.edges — edge types and format

Outbound/inbound edges and semantic_tags are defined in [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md). Edge types (references, implements, schema_reference, supersedes, depends_on, etc.) and the use of `lupo_edges` for storage are unchanged from FLARE; use **`lupopedia.edges`** as the block name. See also [lupo-docs/api/FLARE_HEADERS_COMPLETE_REFERENCE.md](../../api/FLARE_HEADERS_COMPLETE_REFERENCE.md) for the full field reference (read as LUPOPEDIA HEADERS with lupopedia.* block names).
