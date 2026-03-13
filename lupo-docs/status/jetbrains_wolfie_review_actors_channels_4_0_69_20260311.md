---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "documentation"
  system_version: "4.0.69"
  file_path_from_root: "lupo-docs/status/jetbrains_wolfie_review_actors_channels_4_0_69_20260311.md"
  last_modified_utc: "20260311"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "jetbrains"
  delegation_chain: "wolfie:root"
  artifact_type: "review"
  artifact_kind: "implementation_audit"
  purpose: "Review of actor/channel orchestration documentation against CHANGELOG 4.0.69 and install SQL + TOON schema, with implementation findings and improvements."
---
# file: JetBrains Wolfie implementation review — session: L-LUPO-ROOT-JETBRAINS — delegation: wolfie:root (faucet: jetbrains)

# Review: Actors Orchestration + 4.0.69 Schema Alignment

## Findings (ordered by severity)

### 1. High — Primary key for `lupo_actors` is documented incorrectly

- In `docs/status/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md`, the actor section states primary key = `actor_id`.
- The canonical schema (`install_new_lupopedia.sql` and TOON) defines primary key = `actor_name` and `actor_id` as unique secondary key.
- This can lead to incorrect implementation assumptions in services or migrations that derive identity semantics from docs.

Evidence:
- `docs/status/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md:45` (`Primary key: actor_id`)
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql:37` (`PRIMARY KEY (actor_name)`)
- `lupo-database/lupopedia/toon/lupo_actors.toon:100` (`column_name: actor_name`)

Recommendation:
- Update section 2 and summary table to state: `actor_name` is primary key, `actor_id` is unique secondary identifier.
- Add one explicit note linking this to ACTOR PRIMARY KEY DOCTRINE in install SQL comments.

### 2. Medium — Changelog TOON count is stale

- The JetBrains/Codex 4.0.69 changelog subsection records TOON count as 161.
- Current repository TOON count is 164, and 4.0.69 entries also mention new TOON-backed tables.
- This introduces audit drift in release documentation.

Evidence:
- `CHANGELOG.md:453` (`observed table count ... 161`)
- Live check during review: `lupo-database/lupopedia/toon/` has 164 files.

Recommendation:
- Amend changelog line to 164 (or phrase as "count at time of write" with date stamp).
- Add an automated count step in release checklist: `Get-ChildItem lupo-database/lupopedia/toon -File | Measure-Object`.

### 3. Medium — Suspicious canonical sample row in `lupo_actors.toon`

- TOON sample data row has `actor_name: ''` while also containing conflicting identity hints (`slug: cursor-ide`, `name: Windsurf IDE`, `actor_id: 2031`).
- As TOONs are used as schema/documentation authority, this degrades trust in canonical examples and can mislead tooling/tests that inspect `data` samples.

Evidence:
- `lupo-database/lupopedia/toon/lupo_actors.toon:33` (`actor_name: ''`)
- `lupo-database/lupopedia/toon/lupo_actors.toon:36-37` (`slug: cursor-ide`, `name: Windsurf IDE`)

Recommendation:
- Regenerate TOONs from live DB and verify actor seed integrity.
- If sample rows are intentionally synthetic, add a doctrine note in TOON reference to prevent treating `data` sample rows as canonical identity truth.

## Confirmed aligned implementation items

- `lupo_action_authorization` exists in install SQL and TOON (matches 4.0.69 authorization narrative).
- `lupo_actor_traits` exists in install SQL and TOON (matches trait enforcement narrative).
- `lupo_edge_type_definitions` exists in install SQL and TOON.
- `lupo_dialog_messages` includes `source_faucet_slug` and `source_faucet_instance_id` in install SQL and TOON.
- `lupo_sessions` includes `faucet_slug` and `faucet_instance_id` in install SQL and TOON.
- No `CREATE TABLE lupo_threads` / `lupo_messages` remains in install SQL (dialog unification claim is consistent).

## Improvement plan (JetBrains/Wolfie)

1. Correct the actor PK statement in `HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md` and add a brief “actor_name primary, actor_id unique secondary” doctrine callout.
2. Patch changelog TOON count drift and add a lightweight "schema snapshot" line with exact date + count.
3. Regenerate TOON files from live DB and run a quick sanity check for canonical identity rows in `lupo_actors.toon`.
4. Add a doc-schema consistency check script that validates key claims in architecture docs (PK name, mandatory columns) against TOON metadata before merge.

## Review scope

Reviewed artifacts:
- `docs/status/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md`
- `CHANGELOG.md` (4.0.69 recent implementation sections)
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- TOON files under `lupo-database/lupopedia/toon/` (focused: actors, traits, action auth, edge type definitions, sessions, dialog messages, tasks, federation nodes)

