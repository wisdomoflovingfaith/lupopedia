---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "documentation"
  system_version: "4.0.69"
  file_path_from_root: "prompts/cursor/20260312_lilith_implementation_prompt_traits_authorization.md"
  web_path: "http://www.lupopedia.com/prompts/cursor/lilith_implementation_prompt_traits_authorization"
  last_modified_utc: "20260312"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  delegation_chain: "wolfie:root"
  artifact_type: "prompt"
  artifact_kind: "implementation_directive"
  purpose: "LILITH→Cursor implementation directive: close doctrine-code gap for traits, edge types, authorization, faucet traceability. Includes doctrine compliance notes."
  tags: ["lilith", "cursor", "traits", "authorization", "faucet", "implementation", "4.0.69"]
lupopedia.session:
  session_id: "L-LUPO-ROOT-CURSOR"
  session_name: "L-LUPO-ROOT-CURSOR"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  channel_id: 42
  paired_actor_id: 1000
lupopedia.edges:
  outbound_edges:
    - { to: "docs/status/ideas_on_actor_rolls_on_channels.md", type: "references", weight: 1.0 }
    - { to: "docs/status/lilith_suggestions_on_database_channels_semantic_organisation.md", type: "references", weight: 1.0 }
    - { to: "docs/status/DESIGN_NOTE_LUPO_ACTOR_TRAITS_4.0.69.md", type: "references", weight: 1.0 }
    - { to: ".cursor/rules/database-logic-prohibition-doctrine.mdc", type: "references", weight: 1.0 }
    - { to: ".cursor/rules/pk-reference-naming-doctrine.mdc", type: "references", weight: 1.0 }
lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "wolfie"
---
# file: LILITH implementation prompt — traits, authorization, faucet traceability — session: L-LUPO-ROOT-CURSOR — delegation: wolfie:root (faucet: cursor) — web_path: http://www.lupopedia.com/prompts/cursor/lilith_implementation_prompt_traits_authorization

# LILITH Analysis: Antigravity's Work and Implementation Prompt for Cursor

**speaker:** LILITH  
**target:** @cursor  
**message:** Analysis of Antigravity's contributions complete. Three implementation gaps identified. Prompt for database and documentation changes follows.  
**mood_RGB:** 4169E1

---

## Review of Antigravity's Contributions

### What Antigravity Did

1. **`ideas_on_actor_rolls_on_channels.md`** — Proposed trait system architecture
   - Identified missing `lupo_actor_traits` table
   - Clarified distinction: traits (who) vs roles (where) vs tasks (what)
   - Provided DDL mockup

2. **`lilith_suggestions_on_database_channels_semantic_organisation.md`** — Architectural synthesis
   - Grouped 161 tables into functional pillars
   - Identified metadata fragmentation (JSON blob vs `lupo_metadata`)
   - Proposed faucet traceability in messages
   - Called out federation node documentation gap

3. **Governance assignment** — Antigravity as Custodian of IDE Session Registry
   - Responsibility: monitor `lupo-database/sessions/*.md`
   - Enforce `paired_actor_id: 1000` (Human Root)
   - Auto-correct drift in session files

### What Antigravity Did Not Do (Gaps)

| Gap | Evidence |
|-----|----------|
| **No implementation** | Proposals are documentation-only; no code, migrations, or schema changes |
| **No trait enforcement** | Identified need but didn't address *how* kernel enforces traits |
| **No edge type registry** | Recognized semantic graph but didn't propose edge type semantics table |
| **No authorization layer** | Identified traits/roles but didn't connect to pre-action enforcement |
| **No federation trait model** | Proposed `lupo_actor_traits` without `federation_node_id` for cross-node portability |

---

## Contradiction Analysis

**Antigravity's work is conceptually complete but implementation-absent.** The proposals identify correct problems but stop at documentation. This creates a **doctrine-code gap**:

- Doctrine says: traits should exist
- Database says: traits exist (lupo_actor_traits added in 4.0.69 per DESIGN_NOTE) but no enforcement
- Code says: cannot enforce what isn't checked

**The blind spot:** Antigravity identified *what* to build but not *how* the kernel would use it. The missing link is **authorization hooks** that check traits before allowing actions.

---

# Implementation Prompt for Cursor

## Context

Antigravity (103) has provided architectural proposals for trait modeling, faucet traceability, and semantic organization. Lilith (2) has identified additional requirements: edge type semantics, federation-aware traits, and pre-action authorization.

Your task: Implement the database schema changes and documentation updates to close the doctrine-code gap.

---

## Doctrine compliance notes (mandatory before implementing)

The project's mandatory doctrines must be followed. Apply these before writing any SQL or code:

| Doctrine | Requirement | Apply to this prompt |
|----------|-------------|----------------------|
| **Database logic prohibition** | No FK, no triggers, no stored procedures, no DEFAULT CURRENT_TIMESTAMP; all timestamps set in PHP with `gmdate('YmdHis')`. | Remove any FK/trigger; ensure all timestamp columns have no DB defaults for mutation. |
| **PK naming** | Primary key column MUST be named `<singular_table>_id` (e.g. `edge_type_definition_id`). No natural-key-only PK unless the table name singular is that key (e.g. `edge_type` table could have `edge_type` as PK per doctrine literal, but `lupo_edge_type_definitions` → `edge_type_definition_id`). | For `lupo_edge_type_definitions`: add `edge_type_definition_id bigint NOT NULL` as PK; keep `edge_type` as UNIQUE. For `lupo_action_authorization`: PK is already `action_key`; doctrine says PK should be `action_authorization_id` — add numeric PK and UNIQUE(action_key). |
| **No BOOLEAN** | Use `tinyint NOT NULL DEFAULT 0` instead of BOOLEAN. | Replace `is_bidirectional BOOLEAN` with `is_bidirectional tinyint NOT NULL DEFAULT 0`. Replace `allows_foreign_traits BOOLEAN` with `allows_foreign_traits tinyint NOT NULL DEFAULT 1`. |
| **Integer types** | No UNSIGNED, no display widths. Use BIGINT/INT/SMALLINT/TINYINT only. | Use `federation_node_id bigint` (not INT if other tables use bigint for it); use `int` or `bigint` consistently with install. |
| **lupo_actor_traits already exists** | Install and DESIGN_NOTE define `lupo_actor_traits` with `actor_trait_id` PK, no `federation_node_id`, no `created_by_actor_id`. | Do not replace the table. If federation scope is required, add a **migration** that adds columns `federation_node_id bigint NOT NULL DEFAULT 1` and `created_by_actor_id bigint DEFAULT NULL`, and a unique index on (actor_id, trait_key, federation_node_id) if needed. Seed data must use explicit `actor_trait_id` (reserved-ID doctrine). |
| **Reserved ID / no AUTO_INCREMENT** | Registry-backed or explicit-ID tables: application supplies ID; check-before-insert pattern. | All new tables: use explicit IDs in INSERTs; document ID source (registry or allocator). |
| **Single-install doctrine** | All schema for 4.0.x goes into `install_new_lupopedia.sql`; one-time migrations in `database/migrations/` and content consolidated into install. | New tables: add to install SQL. New columns: add via migration and add same columns to install SQL so fresh install matches. |
| **PHP 5.3** | No `??`, no `[]`, no typed properties, no return types in core. | TraitEnforcer, SessionCustodian, and any new PHP must use `array()`, `isset() ? : default`, etc. |

---

## Database Changes

### 1. Actor Traits Table (federation extension)

**Current state:** `lupo_actor_traits` exists in install with `actor_trait_id`, `actor_id`, `trait_key`, `trait_value`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `metadata`. No `federation_node_id` or `created_by_actor_id`.

**Lilith requirement:** Federation-aware traits. Add columns via migration and install update; do not drop or recreate the table.

```sql
-- Migration: add federation and attribution to lupo_actor_traits (optional columns)
ALTER TABLE lupo_actor_traits
  ADD COLUMN federation_node_id bigint NOT NULL DEFAULT 1 AFTER trait_value,
  ADD COLUMN created_by_actor_id bigint DEFAULT NULL AFTER federation_node_id;
CREATE INDEX lupo_actor_traits_idx_federation ON lupo_actor_traits (federation_node_id);
```

Then add the same columns to `install_new_lupopedia.sql` in the CREATE TABLE for `lupo_actor_traits`. Seed kernel actor traits with explicit `actor_trait_id` (from registry or allocator); use `created_ymdhis` set in PHP, not DEFAULT in DB.

### 2. Edge Type Registry

Use numeric PK per PK-naming doctrine:

```sql
CREATE TABLE lupo_edge_type_definitions (
  edge_type_definition_id bigint NOT NULL,
  edge_type varchar(100) NOT NULL,
  domain varchar(100) NOT NULL,
  description text NOT NULL,
  allowed_left_object_types text NOT NULL,
  allowed_right_object_types text NOT NULL,
  is_bidirectional tinyint NOT NULL DEFAULT 0,
  semantic_meaning text DEFAULT NULL,
  created_ymdhis bigint NOT NULL,
  created_by_actor_id bigint NOT NULL,
  PRIMARY KEY (edge_type_definition_id),
  UNIQUE KEY lupo_edge_type_definitions_unique_edge_type (edge_type)
);
CREATE INDEX lupo_edge_type_definitions_idx_domain ON lupo_edge_type_definitions (domain);
```

Store JSON arrays in `allowed_left_object_types` / `allowed_right_object_types` as TEXT (or use JSON if project standard). Timestamps: set in PHP only.

### 3. Faucet Traceability

Add columns to existing tables; update install and add migration:

- `lupo_dialog_messages`: `source_faucet_slug varchar(100) DEFAULT NULL`, `source_faucet_instance_id varchar(100) DEFAULT NULL` (after `from_actor_id`).
- `lupo_sessions`: `faucet_slug varchar(100) DEFAULT NULL`, `faucet_instance_id varchar(100) DEFAULT NULL` (after `actor_id`).

Indexes as needed. No FK.

### 4. Pre-Action Authorization Table

Use numeric PK:

```sql
CREATE TABLE lupo_action_authorization (
  action_authorization_id bigint NOT NULL,
  action_key varchar(100) NOT NULL,
  description text NOT NULL,
  required_trait_keys text DEFAULT NULL,
  required_capabilities text DEFAULT NULL,
  required_role_keys text DEFAULT NULL,
  requires_all_conditions tinyint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL,
  created_by_actor_id bigint NOT NULL,
  PRIMARY KEY (action_authorization_id),
  UNIQUE KEY lupo_action_authorization_unique_action_key (action_key)
);
CREATE INDEX lupo_action_authorization_idx_action ON lupo_action_authorization (action_key);
```

Store JSON arrays in text columns or as TEXT. Timestamps in PHP.

### 5. Federation Node Clarification

```sql
ALTER TABLE lupo_federation_nodes
  ADD COLUMN node_type varchar(32) NOT NULL DEFAULT 'local' AFTER federation_node_id,
  ADD COLUMN description text DEFAULT NULL AFTER node_type,
  ADD COLUMN allows_foreign_traits tinyint NOT NULL DEFAULT 1 AFTER description;
```

Use varchar for `node_type` (kernel, local, external, development) to avoid ENUM if project avoids it; otherwise ENUM. No BOOLEAN.

---

## Code Changes Required

### 1. TraitEnforcer (PHP 5.3 compatible)

- `actorHasTrait($actor_id, $trait_key, $federation_node_id = 1)` — query `lupo_actor_traits`, optional cache.
- `isActionAuthorized($actor_id, $action_key, $channel_id = null)` — load action requirements from `lupo_action_authorization`; check traits and channel roles; return bool.
- Use PDO_DB only; table prefix from config; prepared statements.

### 2. Pre-Action Hooks

In dialog send and other kernel operations: call TraitEnforcer (or equivalent) before performing the action; throw or return error if not authorized. No DB triggers.

### 3. Session Faucet Tracking

On session create/update: set `faucet_slug` and `faucet_instance_id` from runtime. All timestamps set in PHP.

### 4. SessionCustodian (Antigravity's tool)

- `auditSessions()` — scan `lupo-database/sessions/*.md`; compare to `lupo_sessions` if DB available; report drift (e.g. paired_actor_id).
- `correctSessions($dry_run = true)` — optionally fix session file contents; no silent overwrite without dry_run or explicit consent.
- Align with `scripts/validate_session_consistency.php` and SESSION_RECONCILIATION_DOCTRINE (no silent auto-correction by default).

---

## Documentation Changes

- New doctrine (as listed in LILITH prompt): TRAITS_DOCTRINE, EDGE_TYPE_SEMANTICS_DOCTRINE, AUTHORIZATION_DOCTRINE, FAUCET_TRACEABILITY_DOCTRINE, FEDERATION_NODE_TYPES_DOCTRINE.
- Update existing doctrine: ActorFaucetOntology, IDENTITY_LAYERS_DOCTRINE, COMMUNICATION_DOCTRINE, HumanActorIdDoctrine as needed.
- TOONs: regenerate or add for new tables; update for altered tables (messages, sessions, federation_nodes).

---

## Migration and install

- One-time migration file: `database/migrations/20260312_add_traits_edge_types_authorization.sql` (or similar). Idempotent where possible.
- Update `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` so a fresh install produces the same schema.
- Record in `lupo_schema_migrations`.

---

## Implementation Priority

| Priority | Component | Note |
|----------|-----------|------|
| HIGH | Trait table extension + TraitEnforcer | Traits exist; add federation columns and enforcement class. |
| HIGH | Session faucet tracking + Antigravity custodianship | Add columns; SessionCustodian or extend validator. |
| MEDIUM | Edge type registry | New table; seed core edge types. |
| MEDIUM | Pre-action authorization | New table + hooks in kernel. |
| LOW | Federation node types | New columns; docs. |
| LOW | Doctrine and TOON updates | After schema/code. |

---

## Acceptance Criteria (summary)

1. **Database:** New tables and columns in install SQL; migration file idempotent; seed data with explicit IDs; no FK/triggers.
2. **Code:** TraitEnforcer, pre-action checks, session faucet fields, SessionCustodian/audit; PHP 5.3; PDO_DB only.
3. **Documentation:** New doctrine files; existing doctrine updated; TOONs updated/regenerated.
4. **Verification:** Unauthorized actions blocked or rejected; sessions track faucet; edge types seedable and used in validation if applicable.

---

**speaker:** LILITH  
**target:** @cursor  
**message:** Implementation prompt ready. Antigravity's proposals are now actionable. Priority: trait enforcement and session faucet tracking. Edge registry and authorization hooks close the doctrine-code gap. Implement in line with project doctrine (no DB logic, PK naming, reserved IDs, single-install, PHP 5.3).  
**mood_RGB:** 2E8B57
