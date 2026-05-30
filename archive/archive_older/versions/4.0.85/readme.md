---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: null
  file_path_from_root: "docs/versions/4.0.85/README.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: version_readme
  thread_id: 2014
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
# Lupopedia 4.0.85 â€” Version Documentation

## Final Consolidation Lock (Thread 2016)

This directory is the canonical final-state record for version 4.0.85.

- task authority: `TASK_REGISTRY.md` only
- thread indexes: navigation-only
- contradiction handling: diagnostic-only in `CONTRADICTIONS.md`
- runtime posture: database-first
- filesystem posture: export/read model for artifacts and continuity, not runtime authority for routing/escalation state

Version 4.0.85 final declaration:

- INSTALL READY
- SYSTEM COMPLIANT

## What Is 4.0.85?

Lupopedia 4.0.85 is a **stabilization, correction, and documentation-governance version**. It does not primarily exist to add surface features. It exists to make the current system explainable, internally consistent, and safe to build on.

The version closes several architectural gaps that directly affect how Lupopedia should be described:

- what file is authoritative for task state
- how install SQL and TOONs relate
- how actors route to supporting humans
- how dialog routing behaves in MVP form
- how mood_vector is allowed to carry authority versus numeric influence
- how version history is recorded now that one flat changelog is no longer enough

## What 4.0.85 Says Lupopedia Is

4.0.85 makes the current identity of Lupopedia clearer:

- a **semantic operating system** centered on relationships, metadata, and collections
- a **channel/thread/dialog coordination system** derived from Crafty Syntax live-help structure
- a **human plus actor collaboration system** where auth identity and orchestration identity are distinct
- a **deterministic doctrine-driven system** where important behavior is explicit, inspectable, and reviewable

This version does not claim that every planned subsystem is complete. It defines what is already true, what is implemented only as MVP, and what remains researched or deferred.

## Key Achievements in 4.0.85

### 1. Authority model corrected (Thread 1047)
`TASK_REGISTRY.md` is now the only authoritative task and question state surface. `THREAD_INDEX.md` files are navigation-only. `CONTRADICTIONS.md` is diagnostic-only. This matters because Lupopedia now has enough moving parts that mixed authority surfaces create contradictions quickly.

### 2. Schema reference corrected (Thread 2004)
Install SQL and TOON files are back in parity:

- **166 install tables**
- **166 TOON files**
- zero column-set mismatch
- zero column-order mismatch
- stale `lupo_visibility_state` projection removed

This matters because the documentation can now talk about the schema without guessing which surface is right.

### 3. Actor/auth relationship corrected (Thread 2011)
4.0.85 established the correct many-to-many relationship between `actor` and `auth_user` through `lupo_actor_auth_users`.

That gives Lupopedia a real support-pool model:

- one human can support many actors
- one actor can have many supporting humans
- primary routing and fallback ordering can be deterministic

### 4. Dialog routing MVP corrected (Thread 2012)
Routing is no longer just a design note. The current MVP has been implemented and audited as COMPLIANT for:

- deterministic candidate ordering
- database-backed idempotency
- failure-state handling
- actor binding from session context
- actor-to-auth resolution through the canonical relationship table

This is still an MVP. It is not the complete future dialog system.

### 5. mood_vector doctrine structurally resolved (Thread 2015)
4.0.85 now defines mood_vector as a hybrid system:

- authoritative canonical tokens for decision-safe behavior
- continuous RGB values for non-authoritative routing influence

This matters because the doctrine now aligns with CADUCEUS and HERMES without requiring agents to guess meaning from arbitrary valid RGB values.

### 6. Research classified without being over-promoted
4.0.85 keeps Doom Emacs and BMAD work in the correct category: **research and design input**, not silently accepted schema. This version is strict about that distinction.

### 7. Install-ready and documentation-authoritative state declared
Thread 2013 confirmed dual PASS install readiness, and the final documentation authority pass moved critical outcomes out of threads and into version-scoped domain documents.

### 8. Version-folder governance replaces flat summary overload
The root changelog remains a transition marker, but 4.0.85-specific truth now lives under this directory because the version has too many dimensions to represent safely in one root list.

## Canonical Thread Outcome Summaries

### Thread 1047

- structural authority correction completed
- `TASK_REGISTRY.md` confirmed as the single source of task/question state
- `THREAD_INDEX` surfaces demoted to navigation-only

### Thread 2004

- install SQL to TOON reconciliation completed
- TOON parity confirmed at 166/166
- Doom research classified as research input only
- stale `lupo_visibility_state` projection removed

### Thread 2011

- actor to auth_user relationship corrected to many-to-many through `lupo_actor_auth_users`
- primary routing invariant corrected and re-audited
- final compliance verdict: COMPLIANT

### Thread 2012

- deterministic MVP routing design implemented
- idempotency and loop-prevention corrections applied
- final implementation audit verdict: COMPLIANT

## Authority Map

| source | authority_scope | notes |
|---|---|---|
| `install_new_lupopedia.sql` | schema DDL | single source of truth for table/column definitions |
| `database/lupopedia/toon/*.toon` | column type reference | generated from install SQL; never hand-edited |
| `TASK_REGISTRY.md` | task/question state | single source of truth; all other status views derived |
| `CONTRADICTIONS.md` | contradiction and violation registry | diagnostic only; no execution authority |
| THREAD_INDEX files | navigation | derived only; cannot set task state or lifecycle |

## Why Version Directories Replaced a Flat Changelog

4.0.85 made it explicit that Lupopedia changes are now multi-dimensional. A single chronological root file cannot safely express all of these at once:

- schema truth
- task state
- contradiction state
- design versus implementation boundaries
- research classification
- per-subsystem documentation such as routing and actor/auth relationships

The version directory fixes that by separating overview, organization, authority, contradictions, implementation status, and research into distinct surfaces that can stay aligned without pretending they do the same job.

## Current Runtime Reality at 4.0.85

This directory describes a system that is already structurally rich but uneven across interfaces:

- channels, threads, artifacts, and dialog schema already exist
- actor-to-human escalation exists as an audited MVP
- external AI and IDE faucets can participate through filesystem artifacts and doctrine
- the web dialog interface is still incomplete and should not be described as finished

That distinction is important. 4.0.85 is the version that makes the architectural truth more accurate, not the version that claims every surface is done.

## Files in This Directory

| file | purpose |
|---|---|
| README.md | this file |
| OVERVIEW.md | system-level summary of what 4.0.85 made true |
| OVERVIEW_ORGANIZATION.md | how 4.0.85 work, authority, and overview surfaces are organized |
| TASK_REGISTRY.md | **[AUTHORITATIVE]** all task and question state |
| CONTRADICTIONS.md | **[DIAGNOSTIC]** violation and ambiguity registry |
| SYSTEM_STATE_SNAPSHOT.md | measured state at version close |
| IMPLEMENTATION_STATUS.md | implemented vs designed vs deferred |
| ACTIVE_WORKSTREAMS.md | ongoing and next-phase workstreams |
| CHANGELOG.md | what changed in 4.0.85 |
| PLAN.md | plan reference and authority lock |
| TODO.md | task snapshot (derived from TASK_REGISTRY) |
| channel_42_canonical_summary.md | single canonical summary of Channel 42 accomplishments and final compliant state |
| organization_changes/authority_and_governance_model.md | canonical Thread 1047 authority model outcome |
| database_changes/schema_reconciliation_and_toon_state.md | canonical Thread 2004 schema and TOON reconciliation outcome |
| doctrine_changes/mood_vector_hybrid_model.md | canonical version-scoped summary of Thread 2015 mood_vector structural resolution |
| TASK_BREAKDOWN.md | per-task breakdown reference |
| MIGRATION_WORKFLOW.md | migration workflow reference |
| WEB_INTERFACE_PLAN.md | web interface plan reference |
| federation/doom_emacs_research.md | Doom Emacs structural pattern research |
| federation/bmad_research.md | BMAD method workflow research |

