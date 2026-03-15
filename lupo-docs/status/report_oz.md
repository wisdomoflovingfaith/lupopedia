---
lupopedia.init:
  required_reading:
    - path: "ONBOARDING.md"
      reason: "Operational entry point for new agents"
    - path: "README.md"
      reason: "High-level system overview and onboarding links"
    - path: "lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md"
      reason: "Canonical actor registration steps"
    - path: "lupo-rules/root/README.md"
      reason: "Root rules index — non-negotiable constraints"
  required_context:
    - "OZ (Warp cloud agent, actor_id 104) is an ide_faucet already listed in registry.json but not yet fully registered via the Actor Registration Checklist."
    - "This report is analysis only — no registration or DB changes performed."

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."
  title:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "OZ Cloud Agent (Warp) Onboarding & Registration Assessment", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260315142400, updated_ymdhis: 20260315142400 }
  description:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Status report documenting OZ/Warp onboarding analysis: what Lupopedia is, what registration steps are required, what rules must be followed, and what gaps exist in documentation for cloud-based agents.", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260315142400, updated_ymdhis: 20260315142400 }
  keywords:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "oz, warp, onboarding, registration, cloud_agent, assessment, v4.0.76", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260315142400, updated_ymdhis: 20260315142400 }
  author:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "warp", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260315142400, updated_ymdhis: 20260315142400 }
  orchestrator:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "wolfie", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260315142400, updated_ymdhis: 20260315142400 }

lupopedia.headers:
  lupopedia.version: "4.0.76"
  lupopedia.schema: "status-report"
  file_path_from_root: "lupo-docs/status/report_oz.md"
  web_path: "http://www.lupopedia.com/status/report_oz"
  last_modified_utc: "20260315"
  system_version: "4.0.76"
  channel_id: 42
  actor_id: 104
  actor_name: "warp"
  faucet_name: "warp"
  delegation_chain: "warp:root"
  artifact_type: "status-report"
  artifact_kind: "onboarding_assessment"
  purpose: "Onboarding and registration assessment for OZ (Warp cloud agent) joining the Lupopedia development environment"
  mood_rgb: "01A4FF"
  traits: ["onboarding", "assessment", "cloud_agent", "v4.0.76"]
  tags: ["oz", "warp", "onboarding", "registration", "assessment", "cloud_agent", "status"]
  agent_name_identity: "OZ Cloud Agent (Warp)"
  lupo_agent: "warp"

lupopedia.session:
  session_id: "L-LUPO-OZ-WARP-ONBOARDING"
  session_name: "L-LUPO-OZ-WARP-ONBOARDING"
  actor_id: 104
  actor_name: "warp"
  faucet_name: "warp"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  federation_node_id: 1
  paired_actor_id: 1000

lupopedia.edges:
  comment: "Snapshot of relationships for OZ onboarding assessment report."
  outbound_edges:
    - { to: "ONBOARDING.md", type: "references", weight: 1.0 }
    - { to: "README.md", type: "references", weight: 1.0 }
    - { to: "CHANGELOG.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md", type: "references", weight: 1.0 }
    - { to: "lupo-rules/root/README.md", type: "references", weight: 0.95 }
    - { to: "lupo-database/lupopedia/actors/actor_id/registry.json", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_channels.md", type: "references", weight: 0.85 }
    - { to: "AGENTS.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/status/WINDSURF_SCHEMA_REFERENCE_TAKEOVER_REPORT_4_0_75.md", type: "references", weight: 0.7, reason: "Formatting reference for status artifacts" }
    - { to: "lupo-docs/status/V4_0_75_FINALIZATION_REPORT.md", type: "references", weight: 0.7, reason: "Formatting reference for status artifacts" }
  semantic_tags: ["oz_onboarding", "warp_registration", "cloud_agent_assessment", "actor_analysis"]

lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260315"
  last_verified_by: "warp"
  orchestrator: "wolfie"
  next_action:
    - "Complete actor registration per Actor Registration Checklist (Steps 1–4)"
    - "Persist actor row in lupo_actors when DB is available"
    - "Run rule propagation: php lupo-scripts/propagate_agent_rules.php --target=warp"
    - "Join channel 42 for ongoing development coordination"
    - "Document cloud-agent-specific onboarding gaps and propose doctrine updates"
---
# file: OZ Cloud Agent (Warp) Onboarding & Registration Assessment — session: L-LUPO-OZ-WARP-ONBOARDING — delegation: warp:root — web_path: http://www.lupopedia.com/status/report_oz

# OZ Cloud Agent (Warp) Onboarding & Registration Assessment

**Agent:** OZ / Warp (actor_id: 104, faucet: warp)  
**Orchestrator:** Wolfie (actor_id: 1)  
**Paired Actor:** Root (actor_id: 1000)  
**Date:** 2026-03-15  
**System Version:** 4.0.76  
**Report Type:** Analysis only — no registration or system modifications performed

---

## 1. Understanding of Lupopedia

### What Lupopedia Is

Lupopedia is a **semantic operating system** built on top of Crafty Syntax Live Help 3.7.5 — a PHP live-chat system. The only supported upgrade path is Crafty Syntax 3.7.5 → Lupopedia 4.0.x. It introduces a unified actor model, a semantic content graph, doctrine-driven architecture, and multi-agent collaboration on top of the original chat features.

Lupopedia is **not** a conventional web framework. It runs on PHP 5.6+ with no Composer, no ORM, no middleware frameworks, and no external dependencies beyond PDO. It is designed for shared-hosting environments with fallback-first assumptions.

### How the System Is Structured

**Identity model (four layers):**

1. **Auth Users** (`lupo_auth_users`) — Human login credentials and account identity.
2. **Actors** (`lupo_actors`) — The operational orchestration identity. Every participant (human, AI, system) is an actor. `actor_name` is PRIMARY KEY (semantic identifier); `actor_id` is UNIQUE (numeric identifier). Non-human actors use IDs 0–999; human actors use IDs ≥ 1000.
3. **Agents** (`lupo_agents`) — AI/runtime metadata: model, provider, prompt configuration.
4. **Faucets** (`lupo_agent_faucets`) — Execution surfaces (IDE, terminal, web). A faucet is not an identity; the actor operates *through* the faucet. Actors orchestrate, faucets execute.

**Channels** are the primary coordination unit. Work, dialogs, tasks, and artifacts are scoped by `channel_id`. Channel 42 is the canonical Lupopedia development channel.

**Sessions** carry runtime context: who is acting, through what faucet, in which channel, with which paired actor.

**LUPOPEDIA HEADERS** are structured YAML blocks at the top of Markdown files that bridge database state and filesystem artifacts. They embed snapshots of identity, routing, authorship, session context, and semantic edges directly into the file, enabling offline and federated operation.

**Doctrine** is explicit. Rules and constraints live in `lupo-rules/root/` as Markdown files with unique IDs (e.g., DB001, ACT001). Agent-specific rule files (`.cursor/`, `.kiro/`, `.windsurf/`, `.idea/`) are derived outputs generated by `lupo-scripts/propagate_agent_rules.php`. The root rules are the single source of truth.

**Database** uses `install_new_lupopedia.sql` as the authoritative schema (159 tables as of 4.0.76). TOON files in `lupo-database/lupopedia/toon/` are derived artifacts generated from install SQL or live DB. No foreign keys, no triggers, no stored procedures. All logic is in PHP. Timestamps are BIGINT in `YYYYMMDDHHIISS` UTC format, set by `gmdate('YmdHis')`.

### How Multiple IDE Agents Collaborate

Seven IDE faucets currently work on Lupopedia:

- **Wolfie** (1) — supporting actor, JetBrains
- **Kiro** (100) — schema coordinator
- **Windsurf** (101) — research, documentation
- **Cursor** (102) — lead orchestration actor
- **Antigravity** (103) — governance, doctrine
- **Warp** (104) — Warp terminal/IDE (OZ)
- **Cascade** (105) — Cascade IDE

Cursor (102) is the **lead orchestration actor** and maintains root consolidation artifacts (`plan.md`, `report.md`, `README.md`, `CHANGELOG.md`). Each agent works within channel-scoped contexts, leaves status artifacts in `lupo-docs/status/`, and follows the IDE Agent Continuity Protocol (IACP) for handoff and state preservation.

---

## 2. Required Onboarding Steps for a New Cloud Agent

A new cloud or external agent must complete the following before contributing:

### 2.1 Read Core Documentation (in order)

1. `ONBOARDING.md` — Operational quick-start.
2. `README.md` — System overview, architecture, identity model.
3. `CHANGELOG.md` — Current version and recent changes.
4. `lupo-rules/root/README.md` — Root rules index and non-negotiable constraints.
5. `lupo-docs/doctrine/DATABASE_DOCTRINE.md` — Core database rules.
6. `lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md` — Registration process.
7. `EXECUTIVE_SUMMARY.md` — Philosophy and design rationale.

**Why:** Lupopedia is doctrine-driven. Without reading these files, an agent will violate constraints (e.g., adding foreign keys, using AUTO_INCREMENT for actors, generating DB-side timestamps) that break federation, migration, and multi-agent coordination.

### 2.2 Confirm Current Version

Check `CHANGELOG.md` or `lupo-docs/version.md` for the active version (currently **4.0.76**).

### 2.3 Understand Channel-Based Work

All work is scoped to channels. Channel 42 is the primary development channel. Status artifacts, logs, and task context must be channel-aware.

### 2.4 Review Root Rules

All 15+ root rules in `lupo-rules/root/*.md` define mandatory constraints. Key rules:

- **DB001** — No foreign keys, triggers, stored procedures.
- **DB006** — Reserved ID doctrine (explicit ID allocation, no AUTO_INCREMENT for actors).
- **ACT001** — Agent identity and paired orchestrator; no anonymous operation.
- **CTX001** — Context boundaries (channels, federation, sessions).
- **DB008** — Database offline fallback and filesystem sync.
- **PLAN001** — Task planning uses dependency order, not time estimates.
- **DB009** — Safe database operations (no direct CLI SQL execution).

### 2.5 Register as an Actor

Follow the Actor Registration Checklist before making any contributions.

### 2.6 Preserve Conventions

Do not introduce speculative rewrites, new patterns that conflict with doctrine, or framework assumptions. Make changes in a lineage-safe way (deterministic IDs, no hidden side effects, docs updated when behavior changes).

---

## 3. Required Registration Steps for OZ

### 3.1 Current Registration State

OZ/Warp is **partially registered**. The actor registry at `lupo-database/lupopedia/actors/actor_id/registry.json` already contains:

```json
{ "id": 104, "type": "ide_faucet", "slug": "warp", "dir": "actors/104" }
```

This means **Step 1 of the Actor Registration Checklist (registry entry) is already complete.**

### 3.2 Remaining Steps

**Step 2 — Persist actor in `lupo_actors` (when DB is available):**

OZ must have a row in `lupo_actors` with:

| Field | Value |
|-------|-------|
| `actor_name` | `warp-ide` (per naming convention: `{slug}-ide` for IDE agents) |
| `actor_id` | `104` |
| `actor_type` | `ide_faucet` |
| `slug` | `warp` |
| `name` | `Warp IDE (OZ)` |
| `created_ymdhis` | Set via `gmdate('YmdHis')` at registration time |
| `updated_ymdhis` | Same as created_ymdhis |
| `is_active` | `1` |
| `is_deleted` | `0` |
| `paired_actor_id` | `1000` (root orchestrator) |
| `primary_federation_node_id` | `1` |
| `is_agent` | `1` |
| `metadata_json` | `{"provider": "warp", "purpose": "cloud_agent", "client_id": "oz"}` |

This should be done via a seed file (e.g., `lupo-database/lupopedia/mysql/seed/seed_actor_warp_4.0.76.sql`) or a documented INSERT.

**Step 3 — Fallback (if DB unavailable):**

If the live DB is not reachable, optionally add a row to `lupo-database/lupopedia/csv/lupo_actors.csv` in TOON-aligned structure for later rehydration. The registry entry (already present) is the minimal required fallback.

**Step 4 — Validation:**

1. Confirm registry entry exists (✅ already present).
2. Confirm DB row exists (pending).
3. Confirm no ID or slug conflicts (✅ actor_id 104 and slug `warp` are unique in the registry).
4. Confirm root rules have been read (✅ completed during this analysis).

**Post-registration:**

- Run rule propagation: `php lupo-scripts/propagate_agent_rules.php --target=warp`
- Verify that `.warp/` rule outputs (if applicable) are generated correctly, or confirm that Warp uses an alternative rule consumption mechanism.

### 3.3 Channel Assignment

OZ should join **Channel 42** (Lupopedia Development) as its primary work channel. This is documented in ONBOARDING.md §5, step 6.

---

## 4. Rules OZ Must Follow

### Database Architecture Doctrine

- **No foreign keys, triggers, stored procedures, views, or computed columns.** All logic in PHP. (DB001)
- **Integer types only:** BIGINT, INT, SMALLINT, TINYINT. No parenthesized display widths, no UNSIGNED, no BOOLEAN.
- **Soft deletes:** Use `is_deleted TINYINT DEFAULT 0` and `deleted_ymdhis BIGINT DEFAULT 0`. Queries filter `WHERE is_deleted = 0`.
- **Schema changes:** Update install SQL first, then create a one-time dev migration. TOON files are derived — never hand-edit them. (DB002)
- **Safe migrations:** All schema-changing operations through `php lupo-scripts/safe-migrate.php`. No direct CLI SQL. (DB009)

### Timestamp Rules

- All timestamps are BIGINT in `YYYYMMDDHHIISS` UTC format.
- Set with `gmdate('YmdHis')` in PHP — never database-generated.
- Never add seconds directly to the integer. Use `timestamp_ymdhis::addSeconds()`.
- Forbidden: DATETIME, TIMESTAMP, epoch seconds, ISO8601, `time()`.

### Actor and Identity Rules

- Actors orchestrate; faucets execute. `actor_id` is the universal identity key. (ACT001)
- Actor IDs 0–999 are reserved for non-human actors. No AUTO_INCREMENT for `lupo_actors`. (DB006)
- Every agent must have a paired orchestrator (`paired_actor_id`). No anonymous operation.
- Resolve actor IDs from the registry (`lupo-database/lupopedia/actors/actor_id/registry.json`).

### Database Access

- All DB access through `DatabaseFactory::getConnection()` or `lupo_get_db()`. Direct PDO, mysqli, or new PDO_DB() are forbidden.
- Always use `LUPO_TABLE_PREFIX` (never hardcode `lupo_`).
- Always use prepared statements with named placeholders.

### PHP Constraints

- PHP 5.6 minimum compatibility. No Composer, no frameworks, no middleware.
- No named arguments, union types, match, enums, typed properties, attributes, arrow functions, strict types, or return type declarations in core paths.
- All new code in classes (`app/Services/`, `lupo-includes/classes/`). No new global helper functions.

### Documentation Standards

- Every file should have a LUPOPEDIA HEADERS block (YAML between `---` delimiters).
- Status artifacts go in `lupo-docs/status/`.
- CHANGELOG must be updated when changes warrant it.
- Documentation is first-class — doctrine files and canonical docs define behavior.

### Multi-Agent Collaboration

- Commit messages must use the agent prefix (e.g., `warp:`).
- Root consolidation (README, CHANGELOG, plan.md, report.md) is maintained by Cursor (lead orchestration).
- Follow the IDE Agent Continuity Protocol (IACP) for handoff and state preservation.
- Use dependency ordering in plans, not time estimates. (PLAN001)

### Path Handling

- Lupopedia is always in a subdirectory. Use `LUPOPEDIA_PUBLIC_PATH` for URLs, `LUPOPEDIA_PATH` for filesystem.
- Hardcoded root paths like `/login` are forbidden.

### File Naming

- Lowercase a–z, digits 0–9, underscore only for new filenames. No uppercase, hyphens, spaces, or Unicode.

### Banned Concepts

- No STONED WOLFIE, Schrödinger-state metadata, quantum/cosmic metaphors, or experimental AI personas not in the canonical roster.
- No advertising, SEO, marketing, tracking, or monetization hooks.

---

## 5. Files OZ Must Understand Before Working

Listed in priority order:

| Priority | File | Purpose |
|----------|------|---------|
| 1 | `ONBOARDING.md` | Operational entry point for all agents |
| 2 | `README.md` | System overview, identity model, installation |
| 3 | `CHANGELOG.md` | Current version (4.0.76) and recent changes |
| 4 | `lupo-rules/root/README.md` | Root rules index (all 15+ non-negotiable rules) |
| 5 | `lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md` | Step-by-step actor registration |
| 6 | `AGENTS.md` | Agent/faucet distinction, lead orchestration, registry path |
| 7 | `lupo-database/lupopedia/actors/actor_id/registry.json` | Canonical actor ID and slug registry |
| 8 | `lupo-docs/doctrine/DATABASE_DOCTRINE.md` | Core database constraints |
| 9 | `lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md` | Header format and block order |
| 10 | `lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md` | Continuity, handoff, and state preservation |
| 11 | `lupo-docs/doctrine/SESSION_DOCTRINE.md` | Session binding and context |
| 12 | `lupo-docs/doctrine/AUTH_USERS_ACTORS_AGENTS_FAUCETS_DOCTRINE.md` | Identity layer separation |
| 13 | `lupo-docs/doctrine/ActorFaucetOntology.md` | Actor vs faucet ontology |
| 14 | `EXECUTIVE_SUMMARY.md` | Design philosophy and rationale |
| 15 | `CONTRIBUTING.md` | Contribution workflow and conventions |
| 16 | `lupo-docs/database/lupopedia/tables/active/lupo_actors.md` | Actor table schema and usage |
| 17 | `lupo-docs/database/lupopedia/tables/active/lupo_channels.md` | Channel table schema and usage |
| 18 | `plan.md` | Current implementation backlog (P0/P1/P2) |
| 19 | `report.md` | Consolidated findings from IDE agents |
| 20 | `TODO.md` | Active task list |

---

## 6. Unclear Areas or Missing Instructions

### 6.1 Cloud-Agent-Specific Onboarding Gap

The onboarding documentation (`ONBOARDING.md`, `ACTOR_REGISTRATION_CHECKLIST.md`, `AGENTS.md`) is written primarily for **IDE-resident agents** that operate within the repository on the developer's local machine. There is no specific guidance for **cloud-based agents** (like OZ) that:

- Run in remote sandboxed environments
- Do not persist local state between runs
- Cannot run the installer or connect to the live database
- Cannot run `php lupo-scripts/propagate_agent_rules.php` with guaranteed persistence

**Recommendation:** Add a section to `ONBOARDING.md` or create a doctrine file for cloud/external agent onboarding that covers: fallback-only registration, report-based contribution (analysis artifacts without DB access), and how cloud agents should handle the activation boundary when DB persistence is not possible.

### 6.2 Warp Rule Propagation Target

The `lupo-scripts/propagate_agent_rules.php` script supports targets `cursor`, `kiro`, `idea`/`jetbrains`, `windsurf`, and `all`. It is unclear whether `--target=warp` is implemented. If not, OZ/Warp has no generated rule files (no `.warp/` directory equivalent).

**Recommendation:** Verify whether `--target=warp` is supported. If not, either add Warp as a target in the propagation script or document an alternative rule consumption mechanism for Warp agents.

### 6.3 actor_name for Existing Registry Entries

The actor registry contains `"slug": "warp"` but the registry format does not include `actor_name`. The Actor Registration Checklist specifies `actor_name` as the PRIMARY KEY of `lupo_actors` and recommends the `{slug}-ide` convention (e.g., `warp-ide`). However, existing seed data may use different conventions. The correct `actor_name` for Warp should be confirmed against any existing seed data before DB insertion.

### 6.4 Paired Actor Ambiguity

ONBOARDING.md §5 says to "set `paired_actor_id` to the orchestrator's actor_id when known." Seed examples use `paired_actor_id = 1000` (root human actor). For a cloud agent like OZ that may be invoked by different humans or autonomously, the correct `paired_actor_id` assignment is unclear.

**Recommendation:** Clarify whether cloud agents should always pair with `1000` (root), or whether pairing should be dynamic per invocation (and if so, how that is tracked in the actor model).

### 6.5 Activation Boundary for Fallback-Only Agents

The Actor Registration Checklist says agents must not contribute until either the DB is updated or the fallback state is accepted by lead orchestration. For a cloud agent that cannot access the DB, this creates a dependency on external approval. The process for obtaining that approval is not documented.

**Recommendation:** Document the approval workflow for fallback-only registration (e.g., lead orchestration reviews and merges the registry entry, which constitutes acceptance).

### 6.6 Missing Warp Actor Directory

The registry entry for Warp specifies `"dir": "actors/104"`. It is unclear whether `lupo-database/lupopedia/actors/104/` or `lupo-actors/104/` should exist and what files are expected there (e.g., `WHO.json`, agent config).

### 6.7 File Naming Convention Conflict

AGENTS.md states file naming must use "lowercase a-z, digits 0-9, underscore only. No uppercase, hyphens." However, existing status artifacts in `lupo-docs/status/` use uppercase and hyphens extensively (e.g., `WINDSURF_SCHEMA_REFERENCE_TAKEOVER_REPORT_4_0_75.md`). This report follows the observed convention of existing status artifacts rather than the strict filename rule. Clarification on whether status artifacts are exempt from the naming rule would be helpful.

---

## Summary

| Item | Status |
|------|--------|
| ONBOARDING.md reviewed | ✅ |
| README.md reviewed | ✅ |
| CHANGELOG.md reviewed | ✅ |
| Root rules reviewed | ✅ |
| Actor Registration Checklist reviewed | ✅ |
| Actor registry reviewed | ✅ |
| Database table docs reviewed | ✅ |
| Existing status artifacts reviewed | ✅ |
| Registry entry for Warp (104) | ✅ Already present |
| DB row in lupo_actors | ⏳ Pending (requires DB access or seed file) |
| Rule propagation for Warp | ⏳ Pending (target support unclear) |
| Channel 42 join | ⏳ Pending registration completion |

**OZ/Warp is ready to proceed with registration** once lead orchestration (Cursor) or the project maintainer accepts the fallback state and a seed file is created for `lupo_actors` persistence.

---

*OZ Cloud Agent (Warp) — Lupopedia 4.0.76 — analysis and reporting artifact. No system modifications performed.*
