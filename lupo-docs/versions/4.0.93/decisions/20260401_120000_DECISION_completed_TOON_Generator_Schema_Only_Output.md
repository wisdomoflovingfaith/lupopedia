---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_TOON_Generator_Schema_Only_Output.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_TOON_Generator_Schema_Only_Output.md"
  last_modified_utc: "20260401120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-98"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "TOON Generator — Schema-Only Output"
  tags:
  - "decisions"
  - "legacy"
  - "version-4.0.93"
lupopedia.footer:
  last_verified: "20260401120000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
---

# D-98: TOON Generator — Schema-Only Output

## Type
Decision

## Status
Completed

## Author
CURSOR (actor_id 102)

## Date
2026-04-01

### Context
`generate_toon_files.py` was writing row data (`"data"` key) into every JSON output file, and had a broken CSV subprocess that tried to invoke `admin.php` via shell. This caused two problems: (1) agents reading the JSON files were treating them as a file database rather than schema reference documents, and (2) the CSV trigger was non-functional and violated the no-CLI-execution doctrine.

### Decision
- Stripped all data-fetching functions from `generate_toon_files.py` (`fetch_all_rows`, `fetch_pk_zero_row`, `fetch_canonical_data`, `fetch_active_agents`, `row_to_data_dict`, `json_serializable`, `actor_agent_doctrine` import).
- Removed the `"data"` key entirely from the JSON payload — output is now schema-only: `table_name`, `fields`, `indexes`, `primary_key`, `doctrine_metadata`, `relationships`.
- Removed the broken CSV subprocess call.
- Removed `SKIP_DB` env var (no longer relevant).
- Updated docstring to explicitly state "schema reference documents, not a file database."

### Consequences
- JSON files are unambiguously schema-only — no agent can mistake them for a data store
- Script is simpler and faster
- No more broken subprocess side effect on every run

### Comments
*2026-04-01 CURSOR*: The "JSON files = file database" misconception was causing agents to query them as data sources. Removing the data key eliminates the ambiguity entirely.

---
