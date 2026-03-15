# Lupopedia — Executive Summary

**Technical overview of core design philosophy and architecture.**  
For new developers, IDE agents, and contributors. **For what to do first operationally** (what to read, what rules to follow, how to start or continue work), see **[ONBOARDING.md](ONBOARDING.md)**. This document explains *why* Lupopedia is designed the way it is and what makes it different from conventional database-driven systems.

---

## 1. What Lupopedia Is

Lupopedia is a **semantic operating system** built for **multi-agent development**, **federated deployments**, and **documentation-driven architecture**. It continues the lineage of Crafty Syntax Live Help (PHP live-chat) but is rebuilt around a unified actor model, explicit identity, and doctrine that prioritizes portability, deterministic behavior, merge-safe data, and cross-agent collaboration.

The system is optimized for environments where multiple IDE agents (Cursor, Windsurf, JetBrains, Antigravity, and others) work on the same codebase and documentation. Architecture and rules are expressed in doctrine files, schema is defined in install SQL and TOON snapshots, and work is scoped by **channels** and **actors** so that contributions are traceable and conflicts are manageable. The design favors clarity, auditability, and safe data movement over convenience features that lock behavior inside the database or make replication and merging unpredictable.

---

## 2. What Lupopedia Intentionally Does NOT Do

Lupopedia deliberately diverges from common database practice in three areas. Understanding these is essential.

### No Foreign Keys

Lupopedia **does not use database foreign keys**. Referential integrity is enforced in **application code**, not by the database.

**Why:** Foreign keys break federation, data portability, and safe merges. When data moves between nodes or is ingested from other systems, FK constraints cause import and migration failures. By keeping relationships logical rather than declarative in the schema, Lupopedia supports multi-node federation, deterministic replication, and merge-safe workflows. Column names still follow conventions (e.g. reference columns match the referenced table’s primary key name), but the database does not enforce them.

### No Database Logic

Lupopedia avoids **triggers**, **stored procedures**, and any business logic that runs inside the database.

**Why:** Database logic creates hidden side effects, makes merges and migrations unpredictable, and complicates debugging. If logic runs in the database, it cannot be reliably versioned, audited, or reasoned about across multiple environments. All behavior lives in application code so it is visible, reviewable, and consistent with the rest of the repository.

### No Automatic Timestamp Columns

Lupopedia **never uses** `DATETIME`, `TIMESTAMP`, or timezone-aware database fields. It does **not** use `CURRENT_TIMESTAMP`, `ON UPDATE CURRENT_TIMESTAMP`, or any DB-generated time values.

**Instead:** All timestamps are **BIGINT** in UTC, format **YYYYMMDDHHIISS** (e.g. `20260315143022`). Values are set in application code (e.g. `gmdate('YmdHis')`). Timezone is always UTC; location or other “where” dimensions are stored separately if needed.

**Why:** Integer UTC timestamps are portable across databases, avoid timezone drift, sort correctly as numbers, and make replication, diffing, and auditing deterministic. Naming follows a consistent pattern (e.g. `created_ymdhis`, `updated_ymdhis`, `deleted_ymdhis`).

---

## 3. Soft Deletes Instead of Hard Deletes

Tables that participate in the soft-delete lifecycle use **`is_deleted`** (TINYINT, 0 = active) and **`deleted_ymdhis`** (BIGINT UTC when the row was marked deleted). Active queries filter with `WHERE is_deleted = 0` by default. Timestamps are set in application code.

**Why:** Soft deletes support auditability, data lineage, recovery, and safe behavior in distributed or multi-agent contexts. Data is rarely physically deleted; normal operation is to set `is_deleted` and `deleted_ymdhis`. Not every table has these columns; they appear where the lifecycle requires them (see install SQL and doctrine).

---

## 4. Registry-Based Identity Allocation

Entities such as **actors**, **channels**, **collections**, and other system-defined entities use **explicit ID allocation** instead of `AUTO_INCREMENT`. IDs are reserved via the registry (`lupo_registry`, and where applicable `lupo_registry_open`); inserts use an explicit ID. Code must **not** use `lastInsertId()` for these tables.

**Why:** Deterministic identity allows safe data merging, reproducible migrations, and multi-node federation. Reserved IDs are shared across environments so that roles, channels, and cross-install references stay consistent. Allocation follows a documented registry workflow; the database does not auto-assign identity for these entities.

---

## 5. Actors and Identity

**Actors** are the core identity abstraction. They represent humans, AI agents, automated systems, and orchestrators. Every operation is attributable to an actor. Actors interact with the system through **channels**, **collections**, **tasks**, and **sessions**. Identity is resolved from the canonical registry and from application state; it is not inferred from the database alone.

Actor identity is first-class: IDE agents and services are registered as actors, and work is scoped and attributed by `actor_id` (and where relevant by channel and federation node). This supports multi-agent development, audit trails, and clear ownership of changes.

---

## 6. Channels: The Core Work Context

**Channels** represent conversation spaces, workspaces, and orchestration contexts. Within a channel, actors collaborate, tasks are performed, and documents and decisions are tracked. Channels define **scope boundaries**: operations and data are associated with a `channel_id`, which groups work and constrains visibility and authority.

Channels are not just chat rooms; they are the primary unit of work context. Permissions, tasks, and content are channel-scoped so that multi-agent and multi-tenant behavior stays coherent.

---

## 7. Channel-Based Task Execution

Tasks are scoped by **`channel_id`** and **`actor_id`**. Channels provide contextual boundaries, task grouping, audit trails, and a clear coordination model for multiple agents. When an IDE agent or service performs work, that work is tied to a channel and an actor. This makes it possible to reason about who did what, where, and to hand off or resume work across agents without losing context.

Channels are the **primary coordination model** for multi-agent work: they bound operations, support logging and status artifacts, and align with doctrine (e.g. continuity protocol, actor pairing).

---

## 8. Federation and Scope

Lupopedia is designed for **federation**: multiple nodes can operate independently while sharing data and identity where needed. Scope is hierarchical and can include **federation node**, **department**, **channel**, and **actor**. Queries and operations respect these boundaries so that data and permissions do not leak across nodes or contexts. Federation and scoping rules are documented in doctrine; application code enforces them.

### Core entity relationship (summary)

```text
  Federation Node (lupo_federation_nodes)
           |
           +-- Department (lupo_departments)
           |
           +-- Channel (lupo_channels)  <-- primary work context
                   |
                   +-- Actors (lupo_actors) participate via lupo_actor_channels / lupo_actor_channel_roles
                   +-- Collections (lupo_collections, lupo_collection_tabs, ...) scope content
                   +-- Tasks, dialogs, sessions scoped by channel_id
           |
  Registry (lupo_registry) allocates IDs for actors, channels, and other reserved entities.
  Edges / metadata (lupo_metadata, content graph) link artifacts and identity.
```

---

## 9. Documentation as Architecture

Documentation is **first-class architecture**. Key directories:

- **`lupo-docs/`** — Doctrine, status, database and table documentation, architecture. Doctrine files define architectural rules; contributors and agents are expected to read them before changing schema or behavior.
- **`lupo-database/`** — Install SQL, seeds, migrations, TOON schema snapshots. Install SQL is the schema authority; TOON files are generated snapshots used for validation and tooling.
- **`lupo-logs/`** — Activity and agent logs. Structured logging supports continuity and handoff between agents.
- **`lupo-scripts/`** — Utilities for schema, validation, and generation.

Documentation defines system behavior; doctrine files are the source of truth for rules that differ from typical database practice (no FKs, no DB logic, reserved IDs, timestamps, soft deletes). TOON files represent the database structure at a point in time and are used to keep code and docs aligned with the schema.

---

## 10. Multi-Agent Development

Lupopedia is built for **multiple IDE agents working simultaneously** (e.g. Cursor, Windsurf, JetBrains, Antigravity). The architecture supports this through:

- **Channel-scoped work** — Tasks and context are tied to channels and actors so work is attributable and handoff is possible.
- **Structured logging** — Agents append to `lupo-logs/` and write status artifacts so that work is durable and another agent can resume.
- **Doctrine rules** — Root rules and doctrine files (e.g. in `lupo-rules/root/`, `lupo-docs/doctrine/`) constrain schema and behavior so that all agents follow the same design (no FKs, reserved IDs, timestamps, etc.).
- **Structured documentation** — LUPOPEDIA HEADERS, table docs, and cross-domain references give agents and humans a single vocabulary and a clear place to look for authority.

New agents are expected to register as actors and to read doctrine and required reading before making changes. The system prioritizes consistency and auditability over ad hoc convenience so that multi-agent development remains predictable and merge-safe.

---

## What These Principles Look Like in Practice

| Principle | Example in Lupopedia |
|-----------|----------------------|
| No foreign keys | `lupo_actors.paired_actor_id` references another actor; no database FK constraint. Application code enforces the relationship. |
| No DB logic | Timestamps (`created_ymdhis`, `updated_ymdhis`) are set in PHP (e.g. `gmdate('YmdHis')`), not by triggers or `ON UPDATE`. |
| Registry IDs | Actors, channels: allocate via `lupo_registry` / `lupo_registry_open`; insert with explicit ID. Do not use `lastInsertId()` for these tables. |
| Soft deletes | Rows are marked `is_deleted = 1` and `deleted_ymdhis` set; active queries use `WHERE is_deleted = 0`. Physical deletes are exceptional. |
| Channel scope | Tasks, dialogs, docs, and work artifacts carry `channel_id`; operations and visibility are bounded by channel. |
| Documentation as architecture | Doctrine files (`lupo-docs/doctrine/`), root rules (`lupo-rules/root/`), and the canonical schema reference are implementation-critical; agents read them before changing schema or behavior. |

---

## Structured Logging for Agent Continuity

Lupopedia uses **append-only structured logs** so that work survives agent handoffs. A new agent may not have the original conversation thread; logs in the repository let them resume deterministically. Logging is part of the architecture, not an afterthought.

**Where to log:** Under `lupo-logs/`, e.g. `lupo-logs/admin/` (takeover/handoff), `lupo-logs/activity/`, or `lupo-logs/agents/`. All locations under `lupo-logs/` are valid; consistency matters more than the exact subfolder.

**Expected fields (in principle):** `timestamp`, `actor_id`, `event_type`, `file_path`, `task_context`, and where relevant `handoff_from` / `handoff_to`. **Timestamp preference:** BIGINT UTC `YYYYMMDDHHIISS` (e.g. `20260315143022`) is preferred for doctrine alignment; ISO8601 is acceptable only when tooling requires it.

Full rules for continuity, checkpoints, and handoff are in **[lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md](lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md)** (IACP).

---

## Where to Go Next

| If you want to... | Start here |
|-------------------|------------|
| Get started and run the app | [README.md](README.md) |
| Understand the database schema and table layout | [lupo-docs/database/lupopedia/tables/lupopedia_actors_collections_organization_reference.md](lupo-docs/database/lupopedia/tables/lupopedia_actors_collections_organization_reference.md) |
| Learn the rules all agents must follow | [lupo-rules/root/README.md](lupo-rules/root/README.md) |
| Register a new actor or IDE agent | [lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md) |
| See what changed recently | [CHANGELOG.md](CHANGELOG.md) |
| Understand channel-scoped work and context | [lupo-docs/doctrine/SESSION_DOCTRINE.md](lupo-docs/doctrine/SESSION_DOCTRINE.md), [lupo-rules/root/channels-federation-offline-session-doctrine.md](lupo-rules/root/channels-federation-offline-session-doctrine.md) (CTX001) |
| Read database and schema doctrine | [lupo-docs/doctrine/DATABASE_DOCTRINE.md](lupo-docs/doctrine/DATABASE_DOCTRINE.md) |
| Read agent continuity and logging rules | [lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md](lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md) |

For installation, upgrade, and day-to-day commands, see README and the docs it references. This document is the technical summary of design philosophy; the table above routes you to the right next document.
