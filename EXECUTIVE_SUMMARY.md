---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: EXECUTIVE_SUMMARY.md
  content_id: 8051410727660352640
  version_when_written: 4.0.85
  web_path: http://www.lupopedia.com/EXECUTIVE_SUMMARY
  last_modified_utc: '20260322_235900'
  channel_id: 42
  thread_id: 2014
  actor_id: 4
  actor_name: athena
  delegation_chain: athena:strategy
  artifact_type: guide
  artifact_kind: documentation
  purpose: Executive technical summary of Lupopedia architecture and doctrine constraints.
  tags:
  - executive-summary
  - architecture
  - doctrine
  - multi-agent
  - semantic-os
lupopedia.footer:
  version: 4.0.85
  last_verified: '20260322'
  last_verified_by: athena
  orchestrator: athena
  next_action:
  - Keep summary aligned with doctrine updates and install model constraints.
  - Re-verify links and examples after major architecture edits.
---
# file: EXECUTIVE_SUMMARY.md — delegation: athena:strategy — web_path: http://www.lupopedia.com/EXECUTIVE_SUMMARY

# Lupopedia — Executive Summary

**Technical overview of the system as it exists in 4.0.85.**  
This document explains what Lupopedia is, why it is structured the way it is, and what is implemented versus still in-progress. For operational startup and reading order, use **[ONBOARDING.md](ONBOARDING.md)**.

---

## 1. What Lupopedia Is

Lupopedia is a **doctrine-driven semantic operating system** for coordinating work across **humans, actors, agents, channels, threads, documents, and decisions**.

It combines four things that usually live in separate systems:

1. a **relationship model** centered on edges, metadata, collections, and explicit linkage
2. a **channel/thread/dialog model** for ongoing collaborative work
3. a **human plus actor identity model** that distinguishes login identity from orchestration identity
4. a **deterministic rule model** in which doctrine, not hidden framework behavior, defines how the system behaves

Lupopedia is therefore not best understood as a single product category.

- It is **not just a chatbot**, because dialog is only one subsystem and every action is embedded in channel, thread, actor, and documentation context.
- It is **not just a ticket system**, because relationships, collections, metadata, decisions, and contradictions are first-class system concepts rather than attachments to tickets.
- It is **not just a graph database**, because the graph is tied to live dialog, human routing, tasks, doctrine, and filesystem artifacts.

The current 4.0.x line keeps the Crafty Syntax 3.7.5 upgrade path as the only supported install path and uses `install_new_lupopedia.sql` as schema authority.

---

## 2. Lineage and Why It Matters

Lupopedia is grounded in two distinct lineages because each solves a different architectural problem.

### Crafty Syntax lineage

Crafty Syntax contributes the **human dialog model**:

- channels as durable work contexts
- threads as bounded conversation units
- real-time dialog as the center of support and coordination
- a proven model for human interaction over more than 20 years of real-world use

Lupopedia keeps this lineage because dialog is not an afterthought. The system needs a coordination model that already proved durable in production human use.

### Doom Emacs research lineage

Doom Emacs contributes **conceptual structure**, not product features.

The relevant lessons are limited and explicit:

- relationships should be modeled clearly
- layered composition matters
- collections of relationships need structure
- ordering, gating, and validation should be visible rather than implicit

This matters because Doom/Emacs represents a compositional system with more than 40 years of maintenance lineage behind it. Lupopedia uses that as research input for edges, collections, task dependencies, contradictions, and decision lineage. In 4.0.85 this remains **research-only and partial**. It is not a claim that Lupopedia fully implements Doom-derived structures.

---

## 3. Why Edges Are Central

Edges are central because Lupopedia is about **meaningful relationships**, not just isolated records.

An edge can express that one task depends on another, one artifact validates another, one contradiction blocks a workstream, one decision follows from another, or one content object relates semantically to another. That is a better fit for the system than treating relationships as scattered prose or embedding them inside ad hoc application logic.

This is why the system invests in:

- `lupo_edges` for typed relationship storage
- `lupo_metadata` for properties on any entity
- collections and collection tabs for grouped semantic structure
- thread and artifact links inside filesystem documentation
- decision tables for future explicit decision lineage

Edges make the system explainable. Without them, channels and tasks would degrade into disconnected records.

---

## 4. Why Channels, Threads, and Dialog Are Central

Channels and threads are central because Lupopedia treats coordination as a real system concern.

- **Channels** define work context, scope, and participation boundaries.
- **Threads** define bounded units of work, investigation, review, and closure.
- **Dialog** carries the actual interaction between humans and actors.

This structure is central for both human and agent workflows. It gives every action a place, preserves continuity across handoffs, and makes audit trails readable.

In 4.0.85, dialog is **foundational but not fully complete**:

- dialog is already present in channels, threads, artifacts, and database tables
- routing from actors to supporting humans now has a corrected deterministic MVP
- current participation still relies heavily on filesystem artifacts, IDE faucets, CLI usage, and external AI workflows
- the Crafty Syntax-style web dialog experience remains a target state, not a finished reality

The correct description is therefore **in-progress but foundational**, not “done.”

---

## 5. Humans, Auth Users, Actors, Agents, and Faucets

These identities are deliberately separated because they solve different problems.

| term | role in Lupopedia |
|---|---|
| **human** | a real person participating in support, review, or operation |
| **auth_user** | the login/account identity used for authentication |
| **actor** | the orchestration identity used for attribution, coordination, and semantic participation |
| **agent** | a software participant or persona that performs specialized work |
| **faucet** | an execution surface such as an IDE or terminal through which work is performed |

This distinction matters because a single human may support multiple actors, and a single actor may have multiple supporting humans.

### Corrected actor/auth_user model

Thread 2011 established the current 4.0.85 model:

- `lupo_actor_auth_users` is the authoritative many-to-many relationship layer
- one `auth_user` can support many actors
- one actor can have many supporting humans
- `is_primary` and `routing_priority` support deterministic escalation order
- `lupo_actors.auth_user_id` remains transitional and read-only for legacy compatibility only

This correction is important because dialog routing depends on a support pool, not a one-human-per-actor shortcut.

---

## 6. Routing, Decisions, Tasks, and Contradictions

Lupopedia is structured so that operational judgment can be traced.

- **tasks** define units of work
- **contradictions** record rule conflicts, validation failures, and authority problems
- **decisions** exist as explicit schema surfaces even where full PHP lineage is not yet implemented
- **routing decisions** now exist as audited MVP behavior for actor-to-human escalation

Thread 2012 is important here. It established that routing must be deterministic, stored, and reviewable.

The corrected routing MVP now includes:

- deterministic candidate ordering from `lupo_actor_auth_users`
- explicit routing decision storage in `lupo_routing_decisions`
- database-backed idempotency via `idempotency_key`
- failure-state handling for dispatch errors
- session-bound actor identity with no client override

This is not a speculative feature description. It reflects the current audited MVP. It also remains limited in scope: advanced policies and a complete web interface are deferred.

---

## 7. Database and Filesystem Duality

Lupopedia intentionally runs as both a database system and a filesystem system.

### Database side

The database holds runtime state and canonical operational tables such as:

- actors and auth users
- channels, dialog threads, and dialog messages
- metadata and edges
- tasks, human requests, and routing decisions
- decision lineage tables prepared for later PHP use

### Filesystem side

The repository holds doctrine, thread artifacts, version documentation, and LUPOPEDIA HEADERS so that:

- work remains readable in Git
- IDE agents and external AI can navigate the system without database access
- continuity survives tool and session boundaries
- authority can be documented explicitly rather than inferred from runtime only

Neither side replaces the other. The database is the runtime substrate. The filesystem is the inspectable coordination and doctrine substrate.

---

## 8. Why Lupopedia Is Deterministic

Determinism in Lupopedia means the same inputs should produce the same accountable outcome because the rules are explicit.

That is why the system forbids or limits things that create hidden behavior:

- no foreign keys
- no triggers or stored procedures
- no DB-generated timestamp behavior
- no hidden randomness in routing
- no anonymous operational identity

Instead it uses:

- application-enforced relationships
- BIGINT UTC timestamps set in PHP
- registry-backed identity allocation where required
- doctrine and documentation as visible authority surfaces
- thread and artifact records that preserve why something was done

This is what makes the system merge-safe, auditable, and understandable across multiple agents.

---

## 9. Why 4.0.85 Uses Version Directories Instead of a Flat Changelog

4.0.85 marks a documentation governance shift.

A flat root changelog could no longer safely represent:

- schema corrections
- doctrine changes
- thread authority and task state
- contradictions and validation results
- research classification versus accepted implementation
- per-subsystem summaries such as routing, actor/auth mapping, and TOON parity

The version directory model fixes that by separating concerns:

- `README.md` in the version folder explains the version
- `OVERVIEW.md` summarizes system outcomes
- `OVERVIEW_ORGANIZATION.md` explains work and authority organization
- `TASK_REGISTRY.md` owns task state
- `CONTRADICTIONS.md` owns diagnostic contradiction state

This is a structural correction, not a documentation preference.

---

## 10. Current 4.0.85 Reality

At the end of the current 4.0.85 documentation and schema correction cycle, Lupopedia can be described accurately as follows:

- the install schema is synchronized with generated TOON references
- the authority model for task state is corrected around `TASK_REGISTRY.md`
- the actor/auth support model is corrected and COMPLIANT
- the routing MVP is corrected and COMPLIANT
- Thread 2013 issued an explicit dual verdict that the current 4.0.85 system is install-ready for the canonical reset/import/install cycle
- dialog structure exists in schema and filesystem coordination surfaces
- the end-user web dialog experience is still incomplete
- Doom-derived structural insights are documented as research, not promoted to accepted schema by implication

That is the honest state of the system.

---

## 11. Summary

Lupopedia is best understood as a deterministic semantic coordination system built from:

- **Crafty Syntax durability** for channel, thread, and human dialog structure
- **Doom/Emacs compositional durability** for explicit relationship and layered structure research
- **actor-based orchestration** distinct from authentication identity
- **database plus filesystem duality** for runtime state and inspectable doctrine
- **explicit routing, contradiction, and decision surfaces** for traceability
- **verified install readiness** after the 4.0.85 correction cycle

It is already operational in important architectural layers, but it is not finished in every interface. That distinction is part of the design truth and must remain visible in every overview surface.