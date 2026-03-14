---
lupopedia.headers:
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:captain"
  lupopedia.version: "4.0.75"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md"
  last_modified_utc: "20260315"
  system_version: "4.0.75"
  artifact_type: "guide"
  artifact_kind: "checklist"
  purpose: "Canonical actor registration checklist for new IDE and web terminal agents; derived from TOON/database and lupo-database fallback; integrates Lilith review improvements."
  tags: ["actor_registration", "checklist", "onboarding", "toon", "fallback", "4.0.75"]
---

# Actor Registration Checklist

This checklist is the **canonical** process for registering a new IDE agent or web terminal agent as a Lupopedia actor. It is derived from the actual data model (TOON files, install SQL, seed files) and from the repository's **actor registry** and **lupo-database** fallback behavior. Do not participate as an unnamed or unregistered agent.

**Source of truth for schema:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (table `lupo_actors`) and TOON files in `lupo-database/lupopedia/toon/`. **Tooling registry:** `lupo-database/lupopedia/actors/actor_id/registry.json`.

---

## 1. Who must register

- **New IDE agent** — Any new IDE or AI coding assistant (e.g. a new IDE surface or agent type) that will operate on the repository.
- **New web terminal agent** — Any new web or terminal-based agent that will perform work in the Lupopedia context.

If you are already listed in the actor registry and have a corresponding `lupo_actors` row (or fallback record), you are registered. Otherwise, complete this checklist before contributing.

---

## 2. Prerequisites (do before registration)

1. **Review canonical root rules.**  
   Read [lupo-rules/root/README.md](../lupo-rules/root/README.md) and the root rule files in `lupo-rules/root/`. All agents must follow these rules. Pay special attention to:
   - [ide-agent-identity-actor-pairing-doctrine.md](../lupo-rules/root/ide-agent-identity-actor-pairing-doctrine.md) (ACT001) — agent identity, paired orchestrator, no anonymous operation.
   - [reserved-id-doctrine.md](../lupo-rules/root/reserved-id-doctrine.md) (DB006) — `actor_id` is **not** AUTO_INCREMENT; you must supply an explicit ID and follow the reserved-ID rules.
   - [database-offline-fallback-import-doctrine.md](../lupo-rules/root/database-offline-fallback-import-doctrine.md) (DB008) — when the live DB is unavailable, persistence can use `lupo-database` (e.g. registry file and CSV) with rehydration-safe structure.

2. **Resolve actor ID allocation.**  
   - Non-human actors (IDE agents, system tools, agents): `actor_id` in range **0–999**. Use an ID not already in the registry or in seed/install.  
   - Human actors: `actor_id >= 1000`.  
   - Do **not** use AUTO_INCREMENT or `lastInsertId()` for `lupo_actors`; the application must supply `actor_id` explicitly (see reserved-id doctrine).

3. **Identify paired orchestrator (ACT001).**  
   The human directing the agent is the **orchestrator** and is represented by an auth user / actor (typically `actor_id >= 1000`). In seed data, IDE agents often have `paired_actor_id = 1000` (root). Set `paired_actor_id` to the orchestrator's actor_id when known.

---

## 3. Identity fields (from TOON / install SQL)

The table `lupo_actors` (see `lupo-database/lupopedia/toon/lupo_actors.toon.json` and install SQL) has:

- **actor_name** (PRIMARY KEY) — Unique string identifier used for joins and references. Example: `cursor-ide`. Stable semantic identifier for the actor.
- **actor_id** (UNIQUE) — Integer identifier used for indexing and numeric references. Explicitly assigned (not AUTO_INCREMENT). Example: `102`.

**Relationship:** Each `actor_id` maps to exactly one `actor_name`. They form a 1:1 mapping.

**Purpose:** `actor_name` → stable semantic identifier; `actor_id` → numeric performance identifier.

Other fields:

- **actor_type** — e.g. `ide_agent`, `system_tool`, `agent`, `human`, `user`.
- **slug** (UNIQUE) — Human-readable identifier, e.g. `cursor`, `kiro`, `windsurf`.
- **name** — Display name, e.g. `Cursor IDE`, `Kiro IDE`.
- **created_ymdhis**, **updated_ymdhis** — BIGINT UTC in `YYYYMMDDHHIISS` format; set in application (e.g. `gmdate('YmdHis')`).
- **is_active**, **is_deleted** — 1/0; default active.
- **paired_actor_id** — Orchestrator actor_id (ACT001); 0 if none.
- **primary_federation_node_id** — Default 1 for local.
- **metadata_json** (optional) — JSON with e.g. `client_id`, `provider`, `purpose`, `archetype`.

Seed examples: `lupo-database/lupopedia/mysql/seed/seed_actors_agents_4.0.45.sql`.

---

## 4. Generating actor_name

`actor_name` is the PRIMARY KEY and must be unique across all actors.

Use the following naming conventions:

| Actor Type           | Naming Convention   | Example              |
|----------------------|---------------------|----------------------|
| IDE Agent            | `{slug}-ide`        | `cursor-ide`         |
| System Tool          | `tool-{slug}`       | `tool-schema-generator` |
| Web Terminal Agent   | `terminal-{slug}`   | `terminal-web-1`     |
| Human                | email-based or `user-{id}` | `user-1000`  |

**Rules:**

- Use lowercase.
- Use hyphen separators.
- Do not use spaces.
- Avoid special characters.

This prevents naming collisions across agents.

---

## 5. Step 1: Add entry to the actor registry (required)

The **actor registry** is the file used by tooling and docs to resolve actor IDs and slugs. It is under version control and can be updated when the live database is not available (fallback).

- **Path:** `lupo-database/lupopedia/actors/actor_id/registry.json`
- **Format:** JSON with a top-level `schema_version` (e.g. `"4.0.69"`) and an `actors` array. Each entry: `id` (integer), `type` (string), `slug` (string), `dir` (e.g. `"actors/102"`). Optional: `lead_orchestration` (boolean).
- **Action:** Add one object for your actor, e.g. `{ "id": 106, "type": "ide_faucet", "slug": "my-ide", "dir": "actors/106" }`. Ensure `id` and `slug` do not conflict with existing entries. Commit the file.

This step is **always** required so that tooling and other agents can resolve your identity. The registry is the durable fallback when DB is not reachable.

---

## 6. Step 2: Persist actor in the database (canonical)

When the **live database is available**, the authoritative representation of an actor is a row in `lupo_actors`.

- **Table:** `lupo_actors` (see install SQL and TOON).
- **Required fields (minimal):** `actor_name`, `actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `paired_actor_id`, `primary_federation_node_id`. Use explicit values; do not rely on defaults that conflict with doctrine (e.g. timestamps must be set in PHP).
- **Action:** Either add a new seed file (e.g. in `lupo-database/lupopedia/mysql/seed/`) that inserts your actor and is run by the installer, or document the INSERT for an operator to run. Follow the reserved-id doctrine: supply `actor_id` explicitly; if the row exists, UPDATE; otherwise INSERT. Do not use `lastInsertId()` for `lupo_actors`.

Canonical persistence is **database-first**. The registry (Step 1) is still required for tooling even when the DB is used.

### Automation note

After registration, run the rule propagation script so your IDE enforces the correct rules:

```bash
php lupo-scripts/propagate_agent_rules.php --target=<your-agent>
```

Example:

```bash
php lupo-scripts/propagate_agent_rules.php --target=cursor
```

This generates rule files for your IDE in the appropriate agent folder (e.g. `.cursor/`, `.kiro/`, `.windsurf/`, `.idea/`).

---

## 7. Step 3: Fallback when the database is unavailable

When the **live database is not available** (e.g. offline IDE, no TCP connection to MySQL/PostgreSQL), Lupopedia allows **fallback** persistence so that registration and work can still be tracked and later rehydrated.

- **Registry (Step 1):** Always update `registry.json` in `lupo-database/lupopedia/actors/actor_id/`. This is the minimal fallback and is required.
- **CSV fallback (optional):** Under `lupo-database/lupopedia/csv/` there is `lupo_actors.csv`. Its structure should align with the TOON / install schema so that rows can be re-ingested into `lupo_actors` when the DB is back. If you add a row for your actor here, use the same column semantics as the table (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, etc.). Do not invent columns. Document that the row is a fallback and should be merged into the live DB when possible.
- **Authority:** Install SQL and TOON remain the source of truth for schema. Fallback files do **not** override schema authority; they are a durable local representation for later sync.

---

## 8. Step 4: Validation

Before participating as the new agent:

1. **Registry:** Your `id` and `slug` appear in `lupo-database/lupopedia/actors/actor_id/registry.json` and do not duplicate an existing entry.
2. **Database (if available):** A row exists in `lupo_actors` with your `actor_id` (and optionally `actor_name`/`slug`) and matches the registry.
3. **Uniqueness:** No other actor has the same `actor_id` or `slug` in the registry or in the DB.
4. **Root rules:** You have read and will follow the root rules in `lupo-rules/root/`.

---

## 9. Troubleshooting common issues

| Symptom | Likely Cause | Solution |
|---------|--------------|----------|
| Duplicate entry for actor_id | ID already used | Check registry and DB; choose next available ID in range (0–999 non-human, ≥1000 human). |
| Duplicate entry for actor_name | Name collision | Use more specific name per [Generating actor_name](#4-generating-actor_name) (e.g. `cursor-ide-main`). |
| Registry updated but DB insert fails | DB offline or schema mismatch | Use fallback mode (registry + optional CSV) and document pending DB insert for when DB is available. |
| Agent cannot find itself in registry | registry.json not committed | Commit the file; registry is version-controlled and must be pushed/pulled. |
| paired_actor_id fails | Orchestrator not registered | Ensure orchestrator actor exists in registry and DB first; then set paired_actor_id. |

---

## 10. Activation boundary

Do **not** begin normal contribution (commits, edits, propagation, or other work under the agent's identity) until:

- The registry has been updated with your actor, and  
- Either the database has been updated (when available) or fallback has been documented and the checklist owner (e.g. lead orchestration or project maintainer) accepts the fallback state.

Anonymous or unregistered participation is not acceptable in the Lupopedia multi-agent system (ACT001).

---

## 11. Summary

| Step | What | Where | When |
|------|------|--------|------|
| 1 | Add registry entry | `lupo-database/lupopedia/actors/actor_id/registry.json` | Always (required) |
| 2 | Persist in DB | `lupo_actors` via seed or documented INSERT | When DB available (canonical) |
| 3 | Fallback | Same registry; optionally `lupo-database/lupopedia/csv/lupo_actors.csv` | When DB unavailable |
| 4 | Validate | Registry + optional DB check + root rules read | Before first contribution |

---

## 12. References

- [lupo-rules/root/](../lupo-rules/root/) — Canonical root rules (all agents must follow).
- [ide-agent-identity-actor-pairing-doctrine.md](../lupo-rules/root/ide-agent-identity-actor-pairing-doctrine.md) (ACT001).
- [reserved-id-doctrine.md](../lupo-rules/root/reserved-id-doctrine.md) (DB006).
- [database-offline-fallback-import-doctrine.md](../lupo-rules/root/database-offline-fallback-import-doctrine.md) (DB008).
- [AGENTS.md](../AGENTS.md) — Lead orchestration, registry path, IDE faucets.
- [README.md](../README.md) — New agent onboarding summary and links to this checklist.
- `lupo-database/lupopedia/toon/lupo_actors.toon.json` — Column/type reference (derived from install SQL).
- `lupo-database/lupopedia/mysql/seed/seed_actors_agents_4.0.45.sql` — Example actor/seed pattern.

---

## 13. Rule ID quick reference

| Rule ID | Document | Summary |
|---------|----------|---------|
| ACT001 | ide-agent-identity-actor-pairing-doctrine.md | Agents must have orchestrator; no anonymous operation |
| DB001 | database-logic-prohibition-doctrine.md | No foreign keys, triggers, or stored procedures |
| DB002 | migration-doctrine.md | Schema changes in install SQL; TOON source of truth |
| DB006 | reserved-id-doctrine.md | Explicit ID allocation; no AUTO_INCREMENT for actors |
| DB008 | database-offline-fallback-import-doctrine.md | Offline fallback persistence in lupo-database |
