---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: null
  file_path_from_root: "lupo-docs/actor_registration_checklist.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: guide
  artifact_kind: checklist
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# Actor Registration Checklist

This checklist is the **canonical** process for registering a new IDE agent or web terminal agent as a Lupopedia actor. It is derived from the actual data model (TOON files, install SQL, seed files) and from the repository's **actor registry** and **lupo-database** fallback behavior. Do not participate as an unnamed or unregistered agent.

**Source of truth for schema:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (table `lupo_actors`) and TOON files in `lupo-database/lupopedia/toon/`. **Actor registry (lupo_actors):** `lupo-database/lupopedia/actors/registry.json`. **lupo_agents id map:** `lupo-database/lupopedia/actors/actor_id/registry.json` (`agents` object). For resolution and propagation context, see [lupo-docs/doctrine/AGENT_REGISTRY.md](doctrine/AGENT_REGISTRY.md).

---

## Canonical Actor Identity

Some agents may appear multiple times in historical filesystem paths or legacy records.

The **canonical identity** of an actor is the entry in:

`lupo-database/lupopedia/actors/registry.json`

with the matching `slug` (and `lupo-database/lupopedia/actors/actor_id/registry.json` only for **lupo_agents** numeric `agent_id` by slug).

**Example:**

- **Cursor**
  - canonical actor_id: **102**
  - slug: `cursor`

Other cursor-related IDs such as `1002` or `1005` may exist in the filesystem as **historical artifacts** and **must not be used for new work**.

Always reference the **registry.json entry** when determining the canonical actor identity.

---

## 0. Agent status: already registered vs new vs integration-only

Check which case applies **before** doing full registration:

| State | What to do |
|-------|------------|
| **A — Agent already exists in registry** | Do **not** register again. Verify your `actor_id` and slug in [actors/registry.json](../lupo-database/lupopedia/actors/registry.json). Run rules propagation for your target (e.g. `php lupo-scripts/propagate_agent_rules.php --target=cascade`). Proceed with integration and contribution only. Example: Cascade (actor_id 105) is already registered; it needs only propagation and validation, not a new actor. |
| **B — Agent does not exist** | Follow this full checklist: registry entry, DB or fallback persistence, then rules propagation support if your IDE is not yet a supported target. |
| **C — Agent exists but not fully integrated** | No new actor. Complete: rules propagation target (see [Extending rules propagation](#extending-rules-propagation)), validation test (e.g. `lupo-tests/unit/<agent>_rules_enforcement.php`), and any agent-specific config or docs. |

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

4. **Declare default project context.**  
   New actors must operate within a project. When persisting the actor (registry and/or `lupo_actors`), declare **default_project_id** and **default_channel_id** (e.g. in metadata or config) so the agent immediately operates in a known project and channel. IDE agents infer project from workspace when not explicitly set; external actors must supply project_id and channel_id in every request. See [lupo-docs/projects/PROJECTS.md](projects/PROJECTS.md) and [lupo-docs/projects/PROJECTS_API.md](projects/PROJECTS_API.md).

5. **Channel membership and roles.**  
   To post messages or access channel content, the actor must be a member of the channel (`lupo_actor_channels`) and optionally have a role in `lupo_actor_channel_roles`. **Recommended role keys** (conventions, data-driven in `role_key`): `captain`, `orchestrator`, `developer`, `schema_coordinator`, `extension_specialist`, `documentation`, `critic`, `monitor`. Use `critic` (or `monitor` for observational access) for reviewer agents such as Lilith; use `orchestrator`, `developer`, etc. for IDE agents. Channel posting API enforces membership (or global admin); actor identity for posting always comes from session, not client input.

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

The **actor registry** is the file used by tooling and docs to resolve **lupo_actors** IDs and slugs. It is under version control and can be updated when the live database is not available (fallback).

- **Path:** `lupo-database/lupopedia/actors/registry.json`
- **Format:** JSON with top-level `actors` object; each key is an actor short name; each value includes at least `actor_id`, `actor_name`, `slug` / `faucet_slug`, `type`, `dir`, and optional `delegates_to_actor_id`, `lead_orchestration`, etc. Match existing entries’ shape.
- **Action:** Add or update one actor entry. Ensure `actor_id` and slug fields do not conflict with existing entries. Commit the file.
- **lupo_agents numeric ids (optional):** If you add a matching `lupo-agents/<slug>/` pack, add `"<slug>": <agent_id>` to the `agents` map in `lupo-database/lupopedia/actors/actor_id/registry.json` using an unused **agent_id**.

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

This generates rule files for your IDE in the appropriate agent folder (e.g. `.cursor/`, `.kiro/`, `.windsurf/`, `.cascade/`, `.idea/`). Supported targets: `cursor`, `kiro`, `windsurf`, `cascade`, `idea` (or `jetbrains`), `all`. If your agent is already registered but has no target yet, see [Extending rules propagation](#extending-rules-propagation).

---

## 7. Step 3: Fallback when the database is unavailable

When the **live database is not available** (e.g. offline IDE, no TCP connection to MySQL/PostgreSQL), Lupopedia allows **fallback** persistence so that registration and work can still be tracked and later rehydrated.

- **Registry (Step 1):** Always update `lupo-database/lupopedia/actors/registry.json` for **actors**. For **lupo_agents** slug→id, update the `agents` map in `lupo-database/lupopedia/actors/actor_id/registry.json` when applicable.
- **CSV fallback (optional):** Under `lupo-database/lupopedia/csv/` there is `lupo_actors.csv`. Its structure should align with the TOON / install schema so that rows can be re-ingested into `lupo_actors` when the DB is back. If you add a row for your actor here, use the same column semantics as the table (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, etc.). Do not invent columns. Document that the row is a fallback and should be merged into the live DB when possible.
- **Authority:** Install SQL and TOON remain the source of truth for schema. Fallback files do **not** override schema authority; they are a durable local representation for later sync.

---

## 8. Step 4: Validation

Before participating as the new agent:

1. **Registry:** Your `actor_id` and slug appear in `lupo-database/lupopedia/actors/registry.json` and do not duplicate an existing entry (and `lupo_agents` map updated in `actor_id/registry.json` if you added a pack).
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
| 1 | Add registry entry | `lupo-database/lupopedia/actors/registry.json` (+ optional `agents` map in `actor_id/registry.json`) | Always (required) |
| 2 | Persist in DB | `lupo_actors` via seed or documented INSERT | When DB available (canonical) |
| 3 | Fallback | Same registry; optionally `lupo-database/lupopedia/csv/lupo_actors.csv` | When DB unavailable |
| 4 | Validate | Registry + optional DB check + root rules read | Before first contribution |

---

## 12. Extending rules propagation

When an **already-registered** agent (e.g. Cascade, actor_id 105) does not yet have rules propagation support:

- **Script:** `lupo-scripts/propagate_agent_rules.php`. Add the target to `$validTargets`, define the output directory (e.g. `.cascade/`), implement a `write_<target>_outputs($dir, $rules)` function patterned on `write_cascade_outputs` or `write_windsurf_outputs`, and invoke it when `$target === 'all' || $target === '<target>'`.
- **Output:** Target directory gets `lupopedia_rules.json`, `rules/<slug>.md` (one per root rule), and `README.md`. Use the same LUPOPEDIA HEADERS and provenance structure as other targets.
- **Validation:** Add `lupo-tests/unit/<target>_rules_enforcement.php` following `cascade_rules_enforcement.php` to verify artifacts exist, JSON is valid, rules match canonical root, and headers are present.
- **Docs:** [ONBOARDING.md](../ONBOARDING.md) describes the same steps under “Extending rules propagation.”

---

## 13. Project Context for Actors

Actors may declare default project context during registration, but **actor identity and project identity are separate registries**:

- **Actor Registry:** `lupo_actors` table + actor registry.json for actor identity
- **Project Registry:** `lupo_projects` table + project registry.json for project identity
- **Relationship:** Actors participate in projects via junction tables, not ownership

**Default Project Assignment:**
- Actors may have `default_project_id` for convenience
- This does not make the actor "owned" by the project
- Actors can work across multiple projects regardless of default assignment

**See Also:**
- [PROJECT_REGISTRY_DOCTRINE.md](doctrine/PROJECT_REGISTRY_DOCTRINE.md) for project identity
- [PROJECT_REGISTRY_WORKFLOW.md](doctrine/PROJECT_REGISTRY_WORKFLOW.md) for project lifecycle

---

## 14. References

- [lupo-rules/root/](../lupo-rules/root/) — Canonical root rules (all agents must follow).
- [ide-agent-identity-actor-pairing-doctrine.md](../lupo-rules/root/ide-agent-identity-actor-pairing-doctrine.md) (ACT001).
- [reserved-id-doctrine.md](../lupo-rules/root/reserved-id-doctrine.md) (DB006).
- [database-offline-fallback-import-doctrine.md](../lupo-rules/root/database-offline-fallback-import-doctrine.md) (DB008).
- [AGENTS.md](../AGENTS.md) — Lead orchestration, registry path, IDE faucets.
- [README.md](../../README.md) — New agent onboarding summary and links to this checklist.
- `lupo-database/lupopedia/toon/lupo_actors.toon.json` — Column/type reference (derived from install SQL).
- `lupo-database/lupopedia/mysql/seed/seed_actors_agents_4.0.45.sql` — Example actor/seed pattern.

---

## 14. Rule ID quick reference

| Rule ID | Document | Summary |
|---------|----------|---------|
| ACT001 | ide-agent-identity-actor-pairing-doctrine.md | Agents must have orchestrator; no anonymous operation |
| DB001 | database-logic-prohibition-doctrine.md | No foreign keys, triggers, or stored procedures |
| DB002 | migration-doctrine.md | Schema changes in install SQL; TOON source of truth |
| DB006 | reserved-id-doctrine.md | Explicit ID allocation; no AUTO_INCREMENT for actors |
| DB008 | database-offline-fallback-import-doctrine.md | Offline fallback persistence in lupo-database |
