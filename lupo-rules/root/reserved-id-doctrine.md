---
lupopedia.init:
  orchestrator_actor: "any"
  rule_set_version: "4.0.74+"
  applies_to: ["audit", "code-gen", "db-sync", "migration", "header-sync"]
  enforcement: strict

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."

lupopedia.headers:
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  lupopedia.version: "4.0.74"
  lupopedia.schema: "cursor_rule"
  file_path_from_root: "lupo-rules/root/reserved-id-doctrine.md"
  web_path: "http://www.lupopedia.com/rules/root/reserved-id-doctrine"
  last_modified_utc: "20260313"
  system_version: "4.0.74"
  rule_name: "RESERVED ID DOCTRINE"
  rule_type: "constraint"
  artifact_type: "rule"
  artifact_kind: "cursor_doctrine"
  purpose: "Tables with reserved IDs must not use AUTO_INCREMENT; code must supply explicit IDs and never use lastInsertId() for these tables"
  tags: ["cursor", "database", "reserved_id", "doctrine"]
  source_path: ".cursor/rules/reserved-id-doctrine.mdc"

lupopedia.footer:
  version: "4.0.74"
  last_verified: "20260313"
  last_verified_by: "wolfie"
  orchestrator: "cursor"
  next_action:
    - "Keep in sync with .cursor/rules/reserved-id-doctrine.mdc"
---
# file: Rule — RESERVED ID DOCTRINE — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/reserved-id-doctrine

# RESERVED ID DOCTRINE (MANDATORY)

Some tables in Lupopedia do **NOT** use AUTO_INCREMENT because certain IDs are **RESERVED** (global registry: actors, channels, users, system entities).

## Rules

- **Registry-backed tables** (e.g. `lupo_actors`, `lupo_channels`, `lupo_auth_users`, and other tables whose PKs come from the registry) must **NOT** rely on AUTO_INCREMENT.
- When inserting into these tables, the code **MUST** explicitly provide the ID (from registry or from an allocation step, e.g. `SELECT COALESCE(MAX(actor_id), 0) + 1` or a reserved constant).
- The code must **NEVER** rely on `AUTO_INCREMENT` or `lastInsertId()` for these tables.
- The code must **ALWAYS** check if a row with that ID exists before inserting: if exists → **UPDATE**; if not → **INSERT** with explicit ID.
- The installer, importer, and seed logic **MUST** preserve reserved IDs exactly. Do not "fix" or "normalize" IDs.
- Do **NOT** add AUTO_INCREMENT to these tables unless explicitly instructed.

## Insert pattern (required)

**Wrong:**

```php
$db->insert('lupo_actors', array('name' => $name, ...));
$id = $db->lastInsertId();
```

**Wrong:**

```php
INSERT INTO lupo_actors (name, ...) VALUES (...)
```

**Correct:**

```php
$actor_id = /* from registry or SELECT COALESCE(MAX(actor_id), 0) + 1 */;
$exists = $db->fetchRow("SELECT 1 FROM " . $db->quoteIdentifier($table) . " WHERE actor_id = :aid", array('aid' => $actor_id));
if ($exists) {
    $db->update($table, $data, 'actor_id = :aid', array('aid' => $actor_id));
} else {
    $data['actor_id'] = $actor_id;
    $db->insert($table, $data);
}
```

- Never use `INSERT ... ON DUPLICATE KEY UPDATE` for these tables; use explicit SELECT → UPDATE or INSERT with explicit ID.

## Scope

- **Actor creation logic** (lupo_actors)
- **Channel creation logic** (lupo_channels)
- **User / auth creation logic** (lupo_auth_users)
- **Admin UI** that creates lupo-actors/channels/users
- **API endpoints** that insert into these tables
- **Seed logic** that inserts system rows
- **Importer logic** that inserts Crafty Syntax rows (preserve original IDs)

Tables that use explicit non-auto IDs (e.g. `lupo_actor_properties`, `lupo_uploads`) must also receive an explicit PK in INSERT (e.g. `SELECT COALESCE(MAX(actor_property_id), 0) + 1`) and must **never** use `lastInsertId()` for that table.

## Upsert / replace

- Do **NOT** generate `INSERT ... ON DUPLICATE KEY UPDATE` for registry-backed or reserved-ID tables.
- Use: **SELECT** by ID (or unique key) → if row exists then **UPDATE**, else **INSERT** with explicit ID.

## Installer / importer / seed

- The **importer** inserts Crafty Syntax users (and related rows) with their **original IDs**.
- The **installer** inserts system lupo-actors/channels with **reserved IDs** from the registry.
- The **seed** file inserts system channels and entities with **reserved IDs**.
- Do **NOT** "fix" or "normalize" these IDs; preserve them exactly.

This rule is permanent.
