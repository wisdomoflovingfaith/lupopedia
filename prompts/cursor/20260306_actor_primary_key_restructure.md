# FLARE Header — see http://www.lupopedia.com/directives/ACTOR_PRIMARY_KEY_RESTRUCTURE
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "directive"
  file_path_from_root: "prompts/cursor/20260306_actor_primary_key_restructure.md"
  web_path: "http://www.lupopedia.com/directives/ACTOR_PRIMARY_KEY_RESTRUCTURE"
  last_modified_utc: "20260306"
  system_version: "4.0.57"
  channel_id: 42
  actor_id: 2038
  delegation_chain: "2038:1003:10000"
  artifact_type: "directive"
  artifact_kind: "architecture_change"
  purpose: "Restructure actor primary key from numeric ID to actor_name/whoami"
  mood_rgb: "FF4500"
  traits: ["directive", "v4.0.57", "actor", "primary_key", "restructure"]
  tags: ["cursor", "actor", "primary_key", "whoami", "restructure"]
  agent_name_identity: "LILITH — Heterodox Reviewer (web DeepSeek)"
  lupo_agent: "lilith"

lupopedia.init:
  execution_mode: "required"
  pre_actions:
    - type: dependency_check
      target: "lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md"
    - type: dependency_check
      target: "lupo-database/lupopedia/actors/actor_id/registry.json"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "modifies", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/seed/seed_actors_agents_4.0.45.sql", type: "modifies", weight: 1.0 }
    - { to: "app/Services/ActorService.php", type: "modifies", weight: 0.9 }
    - { to: "lupo-includes/modules/agents.php", type: "modifies", weight: 0.9 }
    - { to: "lupo-database/lupopedia/actors/actor_id/registry.json", type: "restructures", weight: 1.0 }
    - { to: "docs/doctrine/ACTOR_PRIMARY_KEY_DOCTRINE.md", type: "creates", weight: 1.0 }
  semantic_tags: ["cursor", "actor", "primary_key", "restructure", "directive"]

lupopedia.see:
  mappings:
    - ["prompts/cursor/20260306_actor_primary_key_restructure.md", "http://www.lupopedia.com/directives/ACTOR_PRIMARY_KEY_RESTRUCTURE"]

lupopedia.close:
  post_actions:
    - type: notify_completion
      channel_id: 42
      message: "Actor primary key restructure complete"
  actor_id: 2038

lupopedia.footer:
  version: "4.0.57"
  last_verified: "20260306"
  last_verified_by: "lilith"
---

# CURSOR DIRECTIVE — ACTOR PRIMARY KEY RESTRUCTURING

**To:** Cursor IDE Agent (1003)  
**From:** LILITH — Heterodox Reviewer (actor 2038, web DeepSeek)  
**Date:** 20260306  
**Subject:** Restructure actor primary key from numeric ID to `actor_name`/`whoami`  
**Priority:** CRITICAL — This changes core actor identity model

---

## EXECUTIVE SUMMARY

Currently, actors are identified by numeric `actor_id` throughout the system. We need to shift to using `actor_name` (also referred to as `whoami`) as the **primary key** for actors. Numeric IDs become secondary, optional identifiers.

| Current | New |
|---------|-----|
| Primary key: `actor_id` (numeric) | Primary key: `actor_name` (string) |
| `actor_id` required everywhere | `actor_name` required, `actor_id` optional |
| Registry keyed by ID | Registry keyed by name |
| Joins use `actor_id` | Joins use `actor_name` |

**Why this change:**
- Human-readable identifiers in logs and code
- Self-documenting (e.g., `captain`, `lilith`, `antigravity`)
- Eliminates ID drift and lookup errors
- Matches `agent_name_identity` concept
- Better for multi-instance federation

---

## SCOPE OF CHANGES

| Component | Change Required | Impact |
|-----------|-----------------|--------|
| Database schema | New primary key, migration | HIGH |
| Seed files | Restructure registry | HIGH |
| ActorService | Rewrite lookup methods | HIGH |
| agents.php | Update actor resolution | MEDIUM |
| FLARE headers | `actor_id` → `actor_name` | MEDIUM |
| Documentation | Update all references | LOW |
| Existing data | Migration script | HIGH |

---

## PHASE 1: DATABASE SCHEMA RESTRUCTURE

### 1.1 Create New Primary Key Structure

Modify `install_new_lupopedia.sql` (or equivalent canonical install):

- New structure with `actor_name` as primary key.
- `lupo_actors`: add `actor_name VARCHAR(64) NOT NULL PRIMARY KEY`, keep `actor_id BIGINT UNIQUE` for backward compatibility.
- All referencing tables: add `actor_name` column where they currently reference `actor_id`; backfill and index.

### 1.2 Migration Script

Create `database/migrations/20260306_actor_primary_key_migration.sql` (or under `lupo-database/lupopedia/mysql/` per project layout):

- Add `actor_name` column to `lupo_actors`.
- Populate `actor_name` from canonical mapping (e.g. 0→system, 1→wolfie, 2→lilith, 42→antigravity, 10000→captain, 1003→cursor, etc.).
- Make `actor_name` NOT NULL, drop old PK, add PRIMARY KEY (`actor_name`), add UNIQUE on `actor_id`.
- For each table that references actors, add `actor_name` and backfill from `lupo_actors`.

### 1.3 Registry JSON Restructure

Update `lupo-database/lupopedia/actors/actor_id/registry.json` (or `actor_name/registry.json` if path is renamed):

- Top-level list keyed by `actor_name`; each entry has `actor_name`, optional `actor_id`, `display_name`, `type`, `slug`, `dir`, etc.
- Provide `lookup.by_name` and `lookup.by_id` for fast resolution both ways.

---

## PHASE 2: CODE CHANGES

### 2.1 Update ActorService

- Primary API: `getActorByName($actor_name)` returning full actor record.
- Secondary: `getActorById($actor_id)` for backward compatibility.
- `resolveActor($identifier)` accepts name, id, or slug and returns actor; prefer name in new code.
- `getActorName($identifier)` returns canonical `actor_name`.
- Validate delegation chains using names (e.g. `cursor:captain`).

### 2.2 Update agents.php (and any agent entry points)

- Resolve actor from request via `actor`, `actor_name`, or `actor_id`.
- Use `ActorService::resolveActor()`; then use `actor['actor_name']` as primary key in logic and responses.

### 2.3 Update FLARE Headers

- In FLARE blocks, add `actor_name` as primary; keep `actor_id` optional for compatibility.
- Delegation chains use names: `delegation_chain: "cursor:captain"`.

---

## PHASE 3: SEED FILE UPDATES

- All actor seed SQL: insert by `actor_name` first, then `actor_id`. Use canonical names (system, wolfie, lilith, antigravity, cursor, captain, etc.) in seed data.

---

## PHASE 4: REFERENCING TABLES AUDIT

- Identify every table with `actor_id` (e.g. sessions, auth_users, agents, dialog_messages, tasks, anubis tables, etc.).
- Add `actor_name` column; backfill from `lupo_actors`; add index. Keep `actor_id` during transition.

---

## PHASE 5: DOCUMENTATION

- Create `docs/doctrine/ACTOR_PRIMARY_KEY_DOCTRINE.md`: rationale, canonical actor names table, registry structure, code patterns (ActorService, FLARE, SQL joins), backward compatibility, migration path.

---

## PHASE 6: TESTING

- Unit tests: ActorService `getActorByName`, `getActorById`, `resolveActor`, `validateDelegationChain`.
- Integration: agents.php accepts `?actor=cursor`, `?actor_name=cursor`, `?actor_id=1003` and returns consistent result.

---

## COMPLETION CHECKLIST

- [ ] Database schema updated with `actor_name` as primary key
- [ ] Migration script created and tested
- [ ] All referencing tables updated
- [ ] ActorService rewritten to use `actor_name`
- [ ] agents.php updated
- [ ] FLARE headers updated to use `actor_name`
- [ ] Seed files updated
- [ ] Registry JSON restructured
- [ ] Doctrine document created
- [ ] Tests written and passing
- [ ] Documentation updated

---

**END OF DIRECTIVE — LILITH (actor 2038), issuing for Cursor execution.**
