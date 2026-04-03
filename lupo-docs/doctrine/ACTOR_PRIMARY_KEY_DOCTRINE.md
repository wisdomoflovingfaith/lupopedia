---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/ACTOR_PRIMARY_KEY_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/ACTOR_PRIMARY_KEY_DOCTRINE.md"
  last_modified_utc: "20260403210921"
  when_updated: "20260403210921"
  federation_node_id: 0
  channel_id: 42
  thread_id: "doctrine-header-repair"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  purpose: "ACTOR PRIMARY KEY DOCTRINE"
  status: active
  tags:
    - "doctrine"
    - "header_repair"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/32_actor_authority_agent_roles.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260403210921"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
  verified_via:
    type: "audit"
    script: "fix_doctrine_headers"
  next_action:
    - "Run: python lupo-scripts/apply_doctrine_prd_lineage.py --apply"
---

# file: ACTOR_PRIMARY_KEY_DOCTRINE — delegation: cursor:root

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
      "actor_id": 102,
      "display_name": "Cursor IDE Agent",
      "type": "agent",
      "slug": "cursor",
      "dir": "lupo-actors/102"
    }
  }
}
```

Legacy registry at `lupo-database/lupopedia/actors/actor_id/registry.json` remains for backward compatibility; new code should prefer the name-keyed registry.

The `dir` field in **`registry.json`** is the **actor hub** path relative to the repo root. It MUST match [PRD 00 §5.6](../prd/00_root_constitutional_system_requirements.md#56-actor-id-semantics) and **`IDENTITY_LAYERS_DOCTRINE.md` §3.5**: **`lupo-actors/{actor_id}/`** for **`actor_id` &lt; 2026**, and **`lupo-actors/YYYY/MM/{actor_id}/`** for typical runtime allocations **`actor_id` ≥ 2026**. Slug-only hubs such as **`lupo-actors/countermeasure/`** are invalid for registry-backed actors when **`dir`** is numeric.

## 3.1 Directory structure (actor_id–keyed hub)

- **Canonical hub paths:** Decimal **`actor_id`** (and date sharding when required). Examples: `lupo-actors/0/`, `lupo-actors/111/`, `lupo-actors/102/`.
- **Legacy slug directories** (e.g. `lupo-actors/wolfie/`) may still exist on disk until migrated; **`SkillService`** and **`ActorService::getActorDir()`** should follow **`registry.json` `dir`** first.
- **Standard subdirs:** `apps/`, `lupo-tools/`, `lupo-docs/`, `db-changes/`, `lupo-api/`, `needs/`, `lupo-prompts/`, `skills/`, `logs/`.
- **Resolving path in code:** Use `ActorService::getActorDir($actor_name)` to get the registry **`dir`** (e.g. `lupo-actors/111`). Use `Resolver::actorPathByDir($base_path, $actor['dir'])` where applicable for safe file access.

## 4. Code Patterns

### 4.1 ActorService

All actor resolution should go through `App\Services\ActorService` when available:

- `getActorByName($actor_name)` — primary lookup
- `getActorById($actor_id)` — secondary (backward compatibility)
- `getActorDir($actor_name)` — returns registry `dir` (e.g. `lupo-actors/0`, `lupo-actors/111`) or fallback `LUPO_ACTORS_DIR . '/' . $actor_name`
- `resolveActor($identifier)` — accepts name, id, or slug
- `getActorName($identifier)` — returns canonical `actor_name`
- `validateDelegationChain($chain)` — validates colon-separated names (e.g. `lilith:cursor:captain`)

### 4.2 ActorLookup (agents.php entry point)

`ActorLookup::fromRequest()` returns an array:

- `actor_id` (int)
- `actor_name` (string)
- `dir` (string) — relative path from registry (e.g. `lupo-actors/0`) for filesystem access

Resolves from `$_GET['actor_id']`, `$_GET['actor_name']`, or `$_GET['actor']` (slug). Use `Resolver::actorPathByDir($base_path, $actor['dir'])` for safe path resolution.

### 4.3 FLARE Headers

FLARE headers should use `actor_name` as primary when present:

```yaml
lupopedia.headers:
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
- Directive: `lupo-prompts/cursor/20260306_actor_primary_key_restructure.md`
