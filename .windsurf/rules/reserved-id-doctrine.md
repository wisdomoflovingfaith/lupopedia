---
lupopedia.init:
  file_identity: "reserved-id-doctrine.md"
  artifact_type: "windsurf_rule"
  artifact_kind: "doctrine"
  namespace: "windsurf"
  system_version: "4.0.75"
  orchestrator_actor: "windsurf"
  delegation_chain: "windsurf:captain"

lupopedia.headers:
  actor_id: 101
  actor_name: "windsurf"
  delegation_chain: "windsurf:captain"
  lupopedia.version: "4.0.75"
  lupopedia.schema: "windsurf_rule"
  file_path_from_root: ".windsurf/rules/reserved-id-doctrine.md"
  last_modified_utc: "20260314"
  system_version: "4.0.75"
  source_path: "lupo-rules/root/reserved-id-doctrine.md"
  artifact_type: "rule"
  artifact_kind: "windsurf_doctrine"
  purpose: "Windsurf-specific rule derived from canonical root rule"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "DB006"
      rule_text: "Tables with reserved IDs must not use AUTO_INCREMENT; code must supply explicit IDs"
      scope: "all_agents"
      category: "schema"
      status: "active"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260314"
    last_reviewed_by: "windsurf"
    last_reviewed_date: "20260314"
    version: "1.0"
    status: "active"
lupopedia.footer:
  version: "4.0.75"
  last_verified: "20260314"
  last_verified_by: "windsurf"
  orchestrator: "windsurf"
  next_action:
    - "Keep in sync with canonical root rules"
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

