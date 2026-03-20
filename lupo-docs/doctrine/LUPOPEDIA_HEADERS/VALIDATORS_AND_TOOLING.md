---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md"
  web_path: "[web_path](http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING)"
  title: "LUPOPEDIA HEADERS Validators and Tooling"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
lupopedia.session:
  session_id: "L-LUPO-ROOT-CURSOR"
  session_name: "L-LUPO-ROOT-CURSOR"
  actor_id: 1003
  actor_name: "cursor"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  federation_node_id: 1
  context_source: "default"
  department_id: 0
  thread_id: 0
  agent_name: "cursor"
  actor_type: "agent"
  actor_nature: "ide"
  human_actor_name: "root"
  paired_actor_id: 10000
---
# file: LUPOPEDIA HEADERS Validators and Tooling — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: [web_path](http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS)/VALIDATORS_AND_TOOLING

# LUPOPEDIA HEADERS — Validators and tooling (4.0.69)

Validators and tooling MUST align with the following for a coherent, channel-aware LUPOPEDIA HEADERS system.

---

## 1. Validator acceptance

- **Legacy FLARE artifacts:** Accept existing FLARE-headed files (e.g. format with `---` then YAML then identity line, or identity line then `---` then YAML). Do not reject them during transition.
- **4.0.68+ LUPOPEDIA artifacts:** Accept files that follow the required format: first line `---`, then YAML in canonical block order, then `---`, then identity line `# file: ...`, then body.
- **Canonical block names (4.0.69+):** Prefer **lupopedia.*** in new files: lupopedia.init, lupopedia.conditional, lupopedia.headers, **lupopedia.session**, lupopedia.edges, **lupopedia.engagement**, lupopedia.footer, lupopedia.see, **lupopedia.next_actions** (legacy: lupopedia.close). Validators MUST accept both **lupopedia.*** and legacy **flare.*** / **flame.*** block names.
- **Canonical block order:** When validating or exporting, enforce order: lupopedia.init → lupopedia.conditional → lupopedia.headers → **lupopedia.session** → lupopedia.edges → **lupopedia.engagement** → lupopedia.footer → lupopedia.see → **lupopedia.next_actions** (or legacy lupopedia.close) (same order for legacy lupopedia.init, flare.*, lupopedia.see, lupopedia.close). Optional blocks may be absent; if present, order MUST be correct. Session fields (session_id, session_name, etc.) belong in lupopedia.session, not in lupopedia.headers.
- **Snapshot validation:** Enforce presence of `comment` or `meta` property in **lupopedia.edges** and **lupopedia.engagement** blocks stating they are snapshots of the database state.
- **V-DOC-HEADER-001**: Detect non-canonical LUPOPEDIA HEADERS top-level blocks (e.g. `lupopedia.init`, `lupopedia.metadata`) and flag as **ERROR**. This implements `lupo-docs/doctrine/HEADER_STRUCTURE_DOCTRINE.md` to prevent header drift. (Documentation note only; no code gating yet.)
- **Required fields:** Enforce minimum required fields in `lupopedia.headers`: **`version_when_written`**, **`file_path_from_root`**. Optionally require **`content_id`** only when validating content-imported / `lupo_content`-backed artifacts. All other header keys remain optional unless policy targets a specific artifact type (e.g. **`namespace`** for table documentation per [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) §2.2). **Reject** deprecated version keys in `lupopedia.headers`: `lupopedia.version`, `system_version`, `last_verified_system_version`, standalone `version`. Session-related fields (session_id, session_name, and other session-file fields) MUST be in lupopedia.session when the session block is present.
- **Baseline rewrite expectation (4.0.84+):** Validators and editors SHOULD treat **`version_when_written` &lt; 4.0.84** or presence of deprecated version keys as **ERROR** (or **WARNING** with auto-fix) on save paths so authors align with [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) §2.0, **[directives.md](../../../directives.md)**, and **[lupo-rules/root/LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md](../../../lupo-rules/root/LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md)**.
- **Session semantics:** lupopedia.headers = artifact metadata; lupopedia.session = runtime execution context. Session block in a file is optional; by default agents read session from active runtime. When present in a file, it may indicate verbose output (embedded snapshot); optional property `embedded_session_snapshot: true` means the block was captured at artifact creation time.
- **Optional channel/thread names:** Validators MAY accept and preserve **channel_name** (human-readable channel name, e.g. "Lupopedia Development (general)" for channel_id 42) and **thread_name** (human-readable thread name when the artifact is thread-scoped). These are optional; resolution remains by channel_id (and thread_id when applicable).
- **Channel-aware lookup:** Header resolution MUST support loading metadata by `channel_id` as well as by `entity_type` + `entity_id`. Validators that resolve headers from the DB must use channel when applicable.

---

## 1.1 Namespace validation (4.0.78)

Validators MUST enforce namespace per [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) §2.2:

| Check | Behavior |
|-------|----------|
| **Table documentation** | Require `namespace` in `lupopedia.headers`. Treat missing namespace as **error**. |
| **Namespace value** | If `namespace` is present, value MUST be one of: `auth`, `channels`, `core`, `content`, `analytics`, `federation`, `governance`, `integration`, `legacy`. Invalid value → **error**. |
| **Artifact-type policy** | For artifact types where namespace policy is not yet defined (e.g. API docs, rule docs, skill docs, planning, status), absence of namespace is **not** an error; if namespace is present, validator MAY **warn** that policy is undefined. Do not invent hidden required checks for those types. |
| **Inappropriate placement** | If doctrine later defines that namespace must not appear on certain artifact types, report as error; for 4.0.78 only table-doc requirement and value taxonomy are canonical. |

**Table-doc detection:** Treat files under `lupo-docs/database/lupopedia/tables/` (any subdirectory) with `artifact_type` consistent with table documentation (e.g. `table_documentation`, `database_table`, or path-based heuristic) as table docs for namespace requirement.

---

## 2. Export (DB → YAML)

- Build canonical YAML header from `lupo_metadata` rows: root → block rows → property rows → repeating structures (edges, mappings, actions).
- Emit blocks in canonical order.
- Do not rely on a single `header_yaml` column; use the structured row model.

---

## 3. Import (YAML → DB)

- Parse FLARE/LUPOPEDIA YAML into the row-based model.
- Create root row (class_name = lupopedia_header_root, meta_type = lupopedia_header, property_key = __root__).
- Create block rows (property_key = lupopedia.headers, lupopedia.edges, etc., or legacy flare.*/flame.*) as children of root.
- Create property rows under each block; create child rows for edges, mappings, actions.
- Set `channel_id` when the header is channel-scoped; set `entity_type` / `entity_id` when entity-scoped.

---

## 4. Existing tooling (4.0.77)

- **Validator:** `php lupo-bin/lupo.php headers validate <path>` and `lupo-scripts/validate_lupopedia_headers.php` — validate file structure, required blocks/fields, snapshot comments.
- **Export (file → YAML):** `php lupo-bin/lupo.php headers export <path> [--output=path] [--json]` and `lupo-scripts/export_lupopedia_headers.php` — extract the front-matter YAML block for round-trip or backup.
- **Import (YAML → file):** `php lupo-bin/lupo.php headers import <target.md> [source.yaml]` and `lupo-scripts/import_lupopedia_headers.php` — replace the header block in a Markdown file; body is preserved. Source can be file path or stdin.
- **Round-trip:** Export then import then validate; see `lupo-tests/fixtures/headers/README.md` § Round-trip validation. Content-equivalence is intended; exact byte identity is not guaranteed.
- **lupo_metadata integration:** Sync between LUPOPEDIA HEADERS in files and the `lupo_metadata` table (DB → YAML, YAML → DB) is **deferred**. Current export/import operate on files only. When implemented, export will build YAML from `lupo_metadata` rows and import will parse YAML into the row-based model per §2–3 above.
- **Legacy lupo-tools:** `flare_validate.py`, `flare_apply.py`, and related scripts should be updated to accept both legacy FLARE and 4.0.68+ LUPOPEDIA format, enforce canonical order, and (where they resolve headers from DB) support channel_id-based resolution.
- **PHP/runtime:** Any code that reads or writes header metadata should use `lupo_metadata` with the three structural columns and the row-based model; lookup by entity and/or channel as needed.

---

## 5. Migration caveats

- Do not assume all files are migrated at 4.0.68 release; validators must support both formats during transition.
- Doctrine and implementation must reflect incremental migration, not a fake instant cutover.
