# Actor Primary Key Doctrine

**Version:** 4.0.58  
**Status:** ACTIVE  
**Purpose:** Define `actor_name` as the canonical primary key for all actor identification.

## 1. Rationale

The system originally used numeric `actor_id` as the primary key. Switching to `actor_name` (string) as primary provides:

- Human-readable identifiers in logs and code
- Self-documenting references (e.g. `captain`, `lilith`, `antigravity`)
- Reduced ID lookup errors and drift between documentation and data
- Alignment with `agent_name_identity` and FLARE headers
- Better support for multi-instance and federation (names are stable across nodes)

## 2. Canonical Actor Names

| Actor Name      | Legacy ID | Display Name              | Type   |
|-----------------|-----------|---------------------------|--------|
| system          | 0         | System Actor              | system |
| wolfie          | 1         | Captain WOLFIE             | agent  |
| lilith          | 2038      | LILITH — Heterodox Reviewer | agent  |
| lilith-legacy   | 2         | LILITH (legacy)           | agent  |
| rose            | 3         | ROSE                      | agent  |
| eris            | 4         | ERIS                      | agent  |
| metis           | 5         | METIS                     | agent  |
| anubis          | 19        | ANUBIS                    | agent  |
| vishwakarma     | 25        | VISHWAKARMA               | agent  |
| antigravity     | 42        | Antigravity               | agent  |
| cursor          | 1003      | Cursor IDE Agent          | agent  |
| captain         | 10000     | Captain                   | human  |
| kiro            | 1000      | Kiro IDE                  | agent  |
| windsurf        | 1001      | Windsurf IDE              | agent  |
| warp            | 1004      | Warp IDE                  | agent  |
| cascade         | 1005      | Cascade IDE               | agent  |
| gemini-cli      | 1006      | Gemini CLI                | agent  |
| codex           | 1007      | Codex IDE                 | agent  |
| trae            | 1008      | Trae IDE                  | agent  |

## 3. Registry Structure

The canonical registry is at:

- **Path:** `lupo-database/lupopedia/actors/registry.json`
- **Schema:** Object keyed by `actor_name`; each value has `actor_name`, `actor_id`, `display_name`, `type`, `slug`, `dir`.

Example:

```json
{
  "schema_version": "4.0.58",
  "actors": {
    "cursor": {
      "actor_name": "cursor",
      "actor_id": 1003,
      "display_name": "Cursor IDE Agent",
      "type": "agent",
      "slug": "cursor",
      "dir": "lupo-actors/cursor"
    }
  }
}
```

Legacy registry at `lupo-database/lupopedia/actors/actor_id/registry.json` remains for backward compatibility; new code should prefer the name-keyed registry.

The `dir` field in each actor is **name-based** (e.g. `lupo-actors/system`, `lupo-actors/antigravity`). After running the directory migration script, numeric paths (e.g. `0/`, `42/`) are symlinks to the name-based dirs for backward compatibility.

## 3.1 Directory Structure (Name-Based)

- **Canonical paths:** `lupo-actors/{actor_name}/` (e.g. `lupo-actors/system/`, `lupo-actors/antigravity/`).
- **Backward compatibility:** The migration script creates symlinks so that `lupo-actors/0/` → `lupo-actors/system/`, `lupo-actors/42/` → `lupo-actors/antigravity/`, etc. Legacy code that references numeric paths continues to work.
- **Resolving path in code:** Use `ActorService::getActorDir($actor_name)` to get the relative dir (e.g. `lupo-actors/system`). Use `Resolver::actorPathByDir($base_path, $actor['dir'])` to resolve to a real path under the actors root for safe file access.
- **Migration script:** Run `php lupo-database/lupopedia/mysql/migrations/20260306_actor_directory_migration.php` (optionally `--dry-run`) after the DB migration to rename numeric dirs to name-based and create symlinks. Log: `lupo-actors/directory_migration.log`.

## 4. Code Patterns

### 4.1 ActorService

All actor resolution should go through `App\Services\ActorService` when available:

- `getActorByName($actor_name)` — primary lookup
- `getActorById($actor_id)` — secondary (backward compatibility)
- `getActorDir($actor_name)` — returns registry `dir` (e.g. `lupo-actors/system`) or fallback `LUPO_ACTORS_DIR . '/' . $actor_name`
- `resolveActor($identifier)` — accepts name, id, or slug
- `getActorName($identifier)` — returns canonical `actor_name`
- `validateDelegationChain($chain)` — validates colon-separated names (e.g. `lilith:cursor:captain`)

### 4.2 ActorLookup (agents.php entry point)

`ActorLookup::fromRequest()` returns an array:

- `actor_id` (int)
- `actor_name` (string)
- `dir` (string) — relative path from registry (e.g. `lupo-actors/system`) for filesystem access

Resolves from `$_GET['actor_id']`, `$_GET['actor_name']`, or `$_GET['actor']` (slug). Use `Resolver::actorPathByDir($base_path, $actor['dir'])` for safe path resolution.

### 4.3 FLARE Headers

FLARE headers should use `actor_name` as primary when present:

```yaml
flare.headers:
  actor_name: "cursor"
  actor_id: 1003   # optional, backward compatibility
  delegation_chain: "cursor:captain"
```

### 4.4 Database Queries

- Primary key of `lupo_actors` is `actor_name`.
- Referencing tables include `actor_name` (and optionally `actor_id`) with index; no foreign keys (doctrine).
- Prefer joins on `actor_name` for new code: `WHERE actor_name = :name` or `JOIN lupo_actors a ON s.actor_name = a.actor_name`.

## 5. Backward Compatibility

During transition (v4.0.58 onward):

- Both `actor_id` and `actor_name` are accepted in requests and stored in tables.
- `actor_id` remains a unique secondary key in `lupo_actors`.
- Registry and code support lookup by either name or ID.
- New development should prefer `actor_name` for logging, FLARE, and APIs.

## 6. Migration Path

1. **Fresh install:** Use `install_new_lupopedia.sql` (actor_name as PK) and seed with `actor_name` in all actor INSERTs.
2. **Existing DB:** Run `lupo-database/lupopedia/mysql/migrations/20260306_actor_primary_key_migration.sql` to add `actor_name`, backfill from `actor_id` mapping, then switch primary key and update referencing tables.
3. **Deployment:** After migration, ensure `lupo-database/lupopedia/actors/registry.json` exists and is loaded by ActorService / ActorLookup.
4. **Filesystem (name-based dirs):** Run `php lupo-database/lupopedia/mysql/migrations/20260306_actor_directory_migration.php` (use `--dry-run` first). This renames numeric dirs to name-based and creates symlinks for backward compatibility. See section 3.1.

## 7. References

- Install schema: `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- Seed: `lupo-database/lupopedia/mysql/seed/seed_actors_agents_4.0.45.sql`
- Migration (DB): `lupo-database/lupopedia/mysql/migrations/20260306_actor_primary_key_migration.sql`
- Migration (dirs): `lupo-database/lupopedia/mysql/migrations/20260306_actor_directory_migration.php`
- Directive: `prompts/cursor/20260306_actor_primary_key_restructure.md`
