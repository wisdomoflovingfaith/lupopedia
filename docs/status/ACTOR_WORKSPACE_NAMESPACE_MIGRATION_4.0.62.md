# FLARE Header
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "status_report"
  file_path_from_root: "docs/status/ACTOR_WORKSPACE_NAMESPACE_MIGRATION_4.0.62.md"
  system_version: "4.0.62"
  channel_id: 42
  actor_id: 42
  actor_name: "antigravity"
  last_modified_utc: "20260306"
  purpose: "Analysis and proposal for namespace/workspace storage in lupo_actors"
  artifact_type: "status_report"
  artifact_kind: "proposal"
  traits: ["canonical", "migration", "v4.0.62"]
---

# ANTIGRAVITY ANALYSIS: Namespace & Workspace Storage

## 1. Current State (Discovery)

Actor workspaces and namespaces are currently handled via implicit file-based conventions or registry fields, rather than persistent database columns.

| Item | Current Implementation | Source of Truth |
|------|------------------------|-----------------|
| **Workspace** | `LUPO_ACTORS_DIR/{actor_name}` | `ContextResolver.php` + `registry.json` (`dir` field) |
| **Namespace** | Implicit / Class-based | None (Hardcoded in classes) |

### Key Findings:
- `lupo-database/lupopedia/actors/registry.json` stores a `dir` field for each actor.
- `lupo_actors` table has `actor_root_path` (defaulting to legacy `actors/{actor_id}`).
- `ContextResolver.php` constructs `workspace` at runtime using `actor_name`.
- No `php_namespace` column exists in `lupo_actors` or `lupo_agents`.

---

## 2. Proposed SQL Changes

We propose making these first-class citizens in the `lupo_actors` table to allow deeper system integration and easier workspace management without relying on file parsing for every request.

### SQL Migration Path
Created: `database/migrations/20260306_add_actor_workspace_namespace.sql`

```sql
-- Adds persistent storage for actor workspace and PHP namespace
ALTER TABLE `lupo_actors`
ADD COLUMN `workspace_path` VARCHAR(255) NULL DEFAULT NULL AFTER `actor_root_path`,
ADD COLUMN `php_namespace` VARCHAR(120) NULL DEFAULT NULL AFTER `workspace_path`;

CREATE INDEX `lupo_actors_idx_workspace_path` ON `lupo_actors` (`workspace_path`);
CREATE INDEX `lupo_actors_idx_php_namespace` ON `lupo_actors` (`php_namespace`);
```

---

## 3. Backfill Strategy

### Database Backfill
Update current actors using the name-based convention:
```sql
UPDATE `lupo_actors` SET `workspace_path` = CONCAT('lupo-actors/', `actor_name`);
```

### Registry Sync
The `registry.json` should be updated to include these fields for offline fallback:
```json
"antigravity": {
  "actor_name": "antigravity",
  "actor_id": 42,
  "dir": "lupo-actors/antigravity",
  "workspace_path": "lupo-actors/antigravity",
  "php_namespace": "Lupo\\Agents\\Antigravity"
}
```

---

## 4. Code Integration Patch (Proposal)

### ContextResolver.php
Update `_enrichActorMeta` and `_getActorTypeAndPairedFromDb` to fetch and include `workspace_path` and `php_namespace`.

```php
protected static function _getActorTypeAndPairedFromDb($db, $table_prefix, $actor_id, $actor_name)
{
    // ...
    $stmt = $db->prepare("SELECT actor_type, paired_actor_id, workspace_path, php_namespace FROM {$t} ...");
    // ...
}
```

### ContextKernel.php
Add `getWorkspace()` and `getNamespace()` accessors.

---

## 5. Verification (DOCTOR)
New command: `php lupo-bin/lupo.php doctor --check-actors`
- Verifies `workspace_path` exists on disk.
- Verifies `php_namespace` follows PSR-4 (if applicable).
- Reports orphans (DB row without directory, or directory without DB row).

---

## 6. Rollback Section
To revert:
```sql
ALTER TABLE `lupo_actors` DROP COLUMN `workspace_path`;
ALTER TABLE `lupo_actors` DROP COLUMN `php_namespace`;
```

---
**ANTIGRAVITY: Namespace & Workspace analysis complete (system actor context).**
✅ Reviewed: docs/toons + docs/actors.md + registry.json
✅ Current storage documented
✅ SQL migration proposed
✅ Backfill script drafted
Ready for apply.
