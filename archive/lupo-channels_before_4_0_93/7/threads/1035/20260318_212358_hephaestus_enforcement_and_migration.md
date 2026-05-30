---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  file_path_from_root: "lupo-channels/7/threads/1035/20260318_212358_hephaestus_enforcement_and_migration.md"
  web_path: "http://www.lupopedia.com/lupo-channels/7/threads/1035/20260318_212358_hephaestus_enforcement_and_migration.md"
  questions_toon: null
  system_version: "4.0.79"
  channel_id: 7
  thread_id: 1035
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:enforcement"
  artifact_type: "thread"
  artifact_kind: "enforcement_and_migration"
  purpose: "Channel 42 thread migration audit + deterministic enforcement of global system limits"
  traits: ["system_limits", "enforcement", "channel_migration", "channel_42_copy_not_move"]
  tags: ["hephaestus", "limits", "v-validators", "migration"]
  lupo_agent: "hephaestus"
lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260318"
  last_verified_by: "hephaestus"
  orchestrator: "wolfie"
---
# file: HEPHAESTUS enforcement & migration — session: channel 42 -> functional channels + system limits — delegation: hephaestus:enforcement — web_path: http://www.lupopedia.com/lupo-channels/7/threads/1035/20260318_212358_hephaestus_enforcement_and_migration.md

## 1. Thread Migration Completed (Channel 42 -> functional channels)

Rule enforced: **COPY, NOT MOVE** for each mapped thread; originals preserved; `channel_id` and `file_path_from_root`/`web_path` updated only in the copied destination files; redirect artifacts created in the original Channel 42 thread directories: **`# MOVED TO CHANNEL <target>`**.

Redirect artifact cleanup: duplicate redirects generated during a re-run of the temporary migration script were removed so each migrated Channel 42 thread directory has exactly one redirect artifact.

### Migrated thread list (24 threads)

| Thread ID | Target Channel |
|---:|---:|
| 1001 | 51 |
| 1002 | 23 |
| 1003 | 11 |
| 1004 | 66 |
| 1005 | 23 |
| 1006 | 7 |
| 1009 | 17 |
| 1010 | 11 |
| 1011 | 7 |
| 1012 | 7 |
| 1014 | 11 |
| 1015 | 1 |
| 1016 | 31 |
| 1017 | 66 |
| 1018 | 7 |
| 1019 | 7 |
| 1020 | 11 |
| 1021 | 51 |
| 1022 | 51 |
| 1023 | 51 |
| 1024 | 1 |
| 1025 | 66 |
| 1026 | 51 |
| 1027 | 66 |

## 2. Global System Limit Enforcement (Application Layer, deterministic)

All enforcement logic is implemented in PHP application code paths (no triggers / no hidden DB automation), and failures return explicit errors.

### V-CHANNEL-LIMIT-001: Per-channel thread cap (999)

Implemented in: `lupo-includes/modules/channels/operator-accept-visitor-api.php`

Deterministic checks:
- Compute `thread_count = COUNT(DISTINCT dialog_thread_id)` where `dialog_threads.channel_id = <operator_channel_id>` and `is_deleted = 0`.
- If `thread_count >= 950`: update `lupo_channels.status_flag` to `2` (doctrine retiring mapping) with `updated_ymdhis = gmdate('YmdHis')`.
- If `950 <= thread_count < 999`: also return a deterministic JSON `warning` field while proceeding with the accept.
- If `thread_count >= 999`: block accept operation with HTTP `403` and JSON error:
  - `CHANNEL_THREAD_LIMIT_REACHED`
  - message: `Channel <id> has reached max threads (999). Channel must be retired.`

Channel retirement prep behavior:
- The channel is marked **retiring** at `>= 950` (no auto-archival yet).
- At `>= 999`, the system prevents any further pending visitor thread from being assigned to that channel.

### V-SYSTEM-LIMITS-001: Actors (999), MySQL tables (199), Repo files (10,000)

#### Actors limit (999)

Implemented in: `lupo-database/lupopedia/content/lupo-app/Services/ActorService.php`

- In `createActorForAuthUser(...)`, compute total actors with:
  - `SELECT COUNT(*) FROM <prefix>actors WHERE (is_deleted = 0 OR is_deleted IS NULL)`
- If `total_actors >= 999`, return failure (blocks new actor creation for auth user onboarding).

#### MySQL tables limit (199)

Implemented in: `lupo-scripts/safe-migrate.php`

- Before schema-altering execution, compute:
  - `total_tables = COUNT(*) FROM information_schema.tables ... WHERE table_name LIKE <LUPO_TABLE_PREFIX>%`
  - (driver-specific handling for pg/sqlite)
- If `total_tables >= 199`, log `failed` status and terminate with exit code `1`.

#### Repository files limit (10,000)

Script created: `lupo-scripts/check_repo_limits.php`

- Recursively counts regular files under repo root.
- `FAIL` if `file_count > 10000`.

### Enforcement logs & determinism notes

- Channel thread limit enforcement happens before any UPDATE that assigns the pending visitor thread to a channel.
- Actor and table limit enforcement guard clauses run before resource allocation / schema execution.
- All enforcement is explicit, repeatable, and deterministic for the same inputs (DB counts and filesystem tree state).

## 3. Validator Definitions (V-CHANNEL-LIMIT-001, V-SYSTEM-LIMITS-001)

These definitions are the deterministic acceptance criteria that match the enforcement logic above.

### V-CHANNEL-LIMIT-001

Purpose: Ensure **thread_count <= 999 per channel**.

Validator predicate:
- For a given channel `C`, compute `thread_count = COUNT(DISTINCT dialog_thread_id)` from `<prefix>dialog_threads` with:
  - `channel_id = C`
  - `is_deleted = 0`

Evaluation:
- If `thread_count >= 999`: ERROR (hard block expected by runtime enforcement).
- If `thread_count >= 950`: WARN/NOTICE (runtime marks channel `status_flag = 2`).

Expected failure behavior:
- Runtime block returns HTTP `403` with `CHANNEL_THREAD_LIMIT_REACHED`.
- Operator workflow must require creation of a new channel once retiring is in effect.

### V-SYSTEM-LIMITS-001

Purpose: Ensure global limits remain within capacity envelopes:
- Actors `<= 999`
- Tables `<= 199`
- Repo files `<= 10,000`

Validator predicate:
- Actors: `COUNT(*) FROM <prefix>actors` where `is_deleted = 0 OR is_deleted IS NULL`
- Tables: `COUNT(*) FROM information_schema.tables` where `table_name LIKE <LUPO_TABLE_PREFIX>%`
- Files: filesystem recursive count of regular files under repo root (no exclusions)

Evaluation:
- Actors `>= 999`: ERROR
- Tables `>= 199`: ERROR
- Files `> 10000`: ERROR

Expected failure behavior:
- Actor and migrate-time operations are blocked.
- CI / maintenance gates can use `lupo-scripts/check_repo_limits.php` for the repo files metric.

