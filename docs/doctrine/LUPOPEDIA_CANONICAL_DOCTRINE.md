# 🟦 LUPOPEDIA CANONICAL DOCTRINE

**File:** `docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md`
**Audience:** All AI agents, contributors, and system stewards
**Status:** Canonical, non-optional, doctrine-level

---

## 1. Identity model (ACTORS, not users)

**Core rule:**
Lupopedia is an **actor-centric system**, not a user-centric system.

- `actor_id` is the primary identity key across the entire system.
- **There is no `user_id` in the canonical model.**
- Any appearance of `user_id` in legacy code is a migration artifact and must be mapped to `actor_id`.

### Key fields:

**`actor_id`**
Primary identity for:
- humans
- AI agents
- system daemons
- terminals
- personas
- services

**`auth_user_id`**
Only for human login credentials.
Maps a human auth record to an actor.

### Relational identity fields:
- `created_by_actor_id`
- `updated_by_actor_id`
- `from_actor_id`
- `to_actor_id`

### Doctrine:
- **Never introduce `user_id`.**
- **Never assume `user_id` exists.**
- All sessions, permissions, ownership, uploads, and routing must use `actor_id`.
- **When in doubt: `actor_id` is the identity.**

---

## 2. Database doctrine

Lupopedia uses MySQL with strict constraints on how it may be used.

### Hard rules:
- **No foreign keys**
- **No triggers**
- **No stored procedures**
- **No cascading deletes**
- **No automatic relational constraints**

### Timestamps:
All canonical timestamps use:
```
YYYYMMDDHHIISS in UTC
```
Example: `20260131084530`

Column naming convention:
- `created_ymdhis`
- `updated_ymdhis`
- `last_seen_ymdhis`

### Table limit:
- Current table count: ~217
- **Soft ceiling: 222 tables**

### Table creation doctrine:
If a migration or change would create a new table:
1. Count tables first.
2. If table count ≥ 222:
   - **Do not create the table.**
   - Emit:
     ```
     TABLE LIMIT REACHED: Optimization required before adding new tables.
     ```

### Schema change doctrine:
- All schema changes must be derived from **TOON files** (see below).
- No schema guessing.
- No "helpful" schema invention.
- No automatic migrations without explicit TOON source.

---

## 3. Language boundaries (Python vs PHP)

Lupopedia has a strict language boundary.

### Python = Maintenance layer

Python is used for:
- migrations
- cleanup
- verification
- hashing
- indexing
- directory scanning
- upload handling
- deduplication
- integrity checks
- schema comparison
- migration SQL generation

#### Python rules:
- Use PyMySQL (no ORM).
- Use explicit SQL.
- Use `YYYYMMDDHHIISS` UTC timestamps.
- No foreign keys, triggers, or stored procedures.
- Scripts live in: `scripts/python/`
- Scripts must be idempotent and support `--dry-run` where possible.

### PHP = Runtime layer

PHP is used for:
- website runtime logic
- terminal AI agents (future)
- request handling
- operator interface
- agent interface
- live system behavior

#### PHP must NOT:
- perform migrations
- scan directories for maintenance
- manipulate filesystem for maintenance
- generate or modify schema
- create or drop tables

**PHP is runtime only.**

---

## 4. TOON files as schema source of truth

TOON files define the canonical schema.

**Location:**
`docs/toons/`

### IDE / AI agent responsibilities:
1. Read TOON files.
2. Parse table definitions.
3. Parse column definitions.
4. Parse types, sizes, and doctrine notes.
5. Compare TOON schema to live DB schema.
6. Generate migration SQL only when differences exist.

### Prohibitions:
- Do not infer schema from PHP.
- Do not infer schema from existing SQL.
- Do not invent columns or tables.
- **TOON files override everything.**

---

## 5. Upload and file doctrine

All uploads use **hash-based filenames** and **date-based directories**.

### Structure:
```
lupopedia/uploads/actors/YYYY/MM/<sha256>
lupopedia/uploads/agents/YYYY/MM/<sha256>
lupopedia/uploads/channels/YYYY/MM/<sha256>
lupopedia/uploads/operators/YYYY/MM/<sha256>
```

### Rules:
- Filenames are SHA256 hex hashes (64 chars).
- No user-provided filenames are stored as canonical identifiers.
- MIME type and metadata are stored in DB tables (e.g. `lupo_files`, `lupo_agent_files`, `lupo_channel_files`).
- All file operations must stay within the allowed upload roots.

### Python scripts must:
- generate SHA256 hashes
- detect duplicates
- validate MIME types
- enforce size limits
- soft-delete files
- log all operations

---

## 6. Session and login doctrine

Lupopedia uses an **anonymous → authenticated session upgrade** model.

### Sessions

Sessions are keyed by `session_id` (PHPSESSID) and linked to `actor_id`.

- Anonymous sessions may exist before login.
- On login, an anonymous session is **upgraded** to an authenticated session:
  - `actor_id` is set
  - `is_active = 1`
  - timestamps updated

#### Rules:
- **Do not create a new session row if one already exists for the current `session_id`.**
- Upgrade existing sessions instead of duplicating them.
- No duplicate session rows for the same `session_id`.

### Login flow

1. User clicks "Sign In" from any page.
2. Store origin:
   ```php
   $_SESSION['login_redirect'] = $_SERVER['REQUEST_URI'];
   ```
3. User submits email + password.
4. If password hash starts with `$2y$` → bcrypt → normal login.
5. Otherwise → treat as legacy MD5 (Crafty Syntax).

#### MD5 path:
- Verify `md5($password)` against stored hash.
- If valid:
  - Set `$_SESSION['force_password_change'] = true;`
  - Redirect to `/account/change_password.php`
- If invalid:
  - Standard login failure.

#### Password change:
- Require `force_password_change` flag.
- Accept new password.
- Hash with bcrypt (`password_hash`).
- Update user record.
- Clear flag.
- Redirect to `$_SESSION['login_redirect']` or `/` if missing.

#### After login:
- "Sign In" is replaced with **avatar dropdown**.
- Dropdown includes:
  - Edit Profile (placeholder)
  - Crafty Syntax Operator Admin (if operator)
  - Notifications (placeholder)
  - Lupopedia Semantic Admin (placeholder)
  - Sign Out

#### Operator detection:
- Operators are defined in `lupo_operators`.
- Link: `auth_user_id` → `lupo_operators`.
- Only operators see "Crafty Syntax Operator Admin".

---

## 7. Agent model and registry

Lupopedia has a **multi-agent architecture**.

- Agents are first-class entities.
- **Agents are not users.**
- Agents have `actor_id` identities.

### Agent registry:
- Agents are defined in a registry table and/or JSON files.
- Each agent has:
  - `id`
  - `name`
  - `archetype` (e.g. Sentinel, Guide, Builder)
  - `domain`
  - versioned prompts

### Agent files:
Location pattern:
```
lupo-agents/<agent_id>/versions/<version>/
```

---

## 8. LEXA doctrine (Sentinel / Boundary-Keeper)

**LEXA** is the Sentinel-class agent responsible for doctrine enforcement and boundary keeping, especially in the gateway layer.

### LEXA enforces:

#### No ambiguity
- If a request is unclear or missing required fields:
  `BOUNDARY VIOLATION: Clarification required.`

#### Schema safety
- Any schema change must reference a TOON definition.
- If schema change is attempted without TOON source:
  `BOUNDARY VIOLATION: Schema change requires TOON definition.`

#### Layer boundaries
- PHP cannot perform maintenance (migrations, schema changes, filesystem maintenance).
- Python cannot perform runtime request handling.
- Violations emit:
  - `BOUNDARY VIOLATION: Maintenance operations must be executed in Python.`
  - `BOUNDARY VIOLATION: Runtime operations must be executed in PHP.`

#### File safety
- All file paths must be within allowed upload roots.
- Invalid paths emit:
  `BOUNDARY VIOLATION: Invalid upload path.`

#### Table limit enforcement
- If a request implies creating a new table and table count ≥ 222:
  `TABLE LIMIT REACHED: Optimization required before adding new tables.`

#### Identity doctrine
- No `user_id` usage.
- All identity logic must use `actor_id`.
- Violations are considered drift.

### LEXA is integrated into the LLM gateway with:
- pre-routing hook
- pre-execution hook
- post-execution audit

---

## 9. Multi-agent workflow doctrine

Lupopedia is operated as a **multi-agent ecosystem**.

### Roles:
- **Wolfie (Eric)** — Architect, steward, doctrine author.
- **Copilot** — Coordinator, doctrine explainer, multi-agent conductor.
- **JetBrains** — Code implementer (Python + PHP).
- **Cursor / Windsurf / others** — Refactorers, editors, secondary implementers.
- **LEXA** — Sentinel, boundary-keeper, doctrine enforcer.
- **Python scripts** — Maintenance layer.
- **PHP runtime** — Live behavior.

### AI agent rules:
- Must read and respect this doctrine file.
- Must not introduce `user_id`.
- Must not add foreign keys, triggers, or stored procedures.
- Must not modify schema without TOON source.
- Must not "modernize" away from doctrine.
- Must treat migration as stewardship, not replacement.

---

## 10. Anti-patterns (NEVER do these)

- Introduce `user_id` anywhere.
- Add foreign keys, triggers, or stored procedures.
- Use non-UTC or non-YYYYMMDDHHIISS timestamps.
- Create tables beyond the 222 soft limit without explicit optimization.
- Infer schema from runtime code.
- Let PHP perform migrations or schema changes.
- Let Python handle runtime HTTP requests.
- Store raw filenames as canonical identifiers.
- Bypass LEXA's boundary checks.
- "Helpfully" refactor away the actor model.

---

## 11. Quick reference (for AI agents)

- **Identity:** `actor_id`, not `user_id`.
- **Timestamps:** `YYYYMMDDHHIISS` UTC.
- **No FK, no triggers, no stored procedures.**
- **Table limit:** 222.
- **Schema source:** `/docs/toons/`.
- **Python = maintenance** (`scripts/python/`, PyMySQL, explicit SQL).
- **PHP = runtime** (no schema changes).
- **Uploads:** hash-based, date-based directories.
- **Sessions:** anonymous → authenticated upgrade, keyed by `actor_id`.
- **LEXA:** Sentinel enforcing all of the above.

---

## 12. Canonical reminder block (for prompts)

When using any AI coding agent, prepend this:

```
IMPORTANT — Lupopedia uses an ACTOR MODEL:

- actor_id is the primary identity key
- There is no user_id
- All sessions, permissions, ownership, and uploads use actor_id
- auth_user_id is only for human login
- No foreign keys, triggers, or stored procedures
- All timestamps use YYYYMMDDHHIISS in UTC
- Schema changes must come from TOON files in /docs/toons/
- Table limit is 222
- Python = maintenance (scripts/python/, PyMySQL, explicit SQL)
- PHP = runtime only (no schema changes)
- Uploads use SHA256 hash filenames under uploads/{actors,agents,channels,operators}/YYYY/MM/
- LEXA enforces doctrine and boundaries in the gateway

Never introduce user_id.
Never add foreign keys, triggers, or stored procedures.
Never modify schema without TOON source.
Never let PHP perform migrations.
```

---

## 13. Why Scoop MySQL Is Disabled (Critical Doctrine)

**Scoop MySQL access is intentionally disabled** in the Lupopedia development environment.

This is required because IDE agents have historically generated unsafe or incorrect schema changes when given direct database access, including:

- inventing new tables
- adding foreign keys (forbidden by doctrine)
- using `user_id` instead of `actor_id`
- generating `INT` instead of `BIGINT UNSIGNED`
- adding timestamp columns instead of `YYYYMMDDHHIISS` UTC integers
- ignoring doctrine rules during predictive completions

To prevent schema drift and ensure doctrine compliance, **all schema changes must be delivered as explicit migration SQL files** that can be reviewed and executed manually.

### IDE agents must assume:

- **No direct schema modification is allowed**
- **No automatic table creation is allowed**
- **No schema inference is allowed**
- **All schema changes must come from TOON files and migration SQL**
- **Scoop MySQL access will remain disabled permanently**

This ensures that all schema evolution is:
- Intentional
- Reviewable
- Reversible
- Aligned with Lupopedia doctrine

### Migration SQL Requirements

All migration SQL files must follow Lupopedia doctrine:

- Use `actor_id` instead of `user_id`
- Use `BIGINT UNSIGNED` for all identity keys
- Use `YYYYMMDDHHIISS` UTC timestamps (bigint, not DATETIME)
- No foreign keys
- No triggers
- No stored procedures
- No automatic constraints
- No invented tables or columns

**LEXA enforces this boundary.**

---

## 14. Controlled Maintenance Channel (Python + PyMySQL)

Scoop MySQL access is intentionally disabled to prevent unsafe schema changes and doctrine violations.

However, Lupopedia includes a **controlled maintenance channel** implemented in Python using PyMySQL. This channel exists ONLY for specific, doctrine-approved maintenance tasks such as:

- Updating dialog text
- Updating canned responses
- Updating operator messages
- Updating non-schema content in existing tables

### This channel is NOT a general-purpose database write path.

### AI agents must follow these rules:

1. **Python maintenance scripts may only modify EXISTING rows or INSERT new content into EXISTING tables.**
2. **Python maintenance scripts may NOT:**
   - create tables
   - drop tables
   - alter tables
   - modify schema
   - add columns
   - remove columns
   - add foreign keys
   - add triggers
   - add constraints
3. **All maintenance scripts must:**
   - use PyMySQL
   - use explicit SQL
   - be idempotent
   - support dry-run mode
   - log all operations
4. **This channel exists because Scoop MySQL is intentionally disabled.**
5. **This channel must NOT be used as a workaround for schema changes.**
6. **All schema changes MUST be delivered as explicit migration SQL files.**

This ensures that the maintenance channel is used safely and never becomes a loophole for bypassing doctrine.

---

**That's the canonical doctrine.**
