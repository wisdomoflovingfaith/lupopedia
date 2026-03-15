---
lupopedia.headers:
  actor_id: 1
  actor_name: "jetbrains"
  delegation_chain: "jetbrains:cursor"
  lupopedia.version: "4.0.75"
  lupopedia.schema: "status_report"
  file_path_from_root: "lupo-docs/status/JETBRAINS_TO_WINDSURF_ANTIGRAVITY_SCHEMA_REFERENCE_CONTINUITY_HANDOFF_4_0_75.md"
  last_modified_utc: "20260315"
  system_version: "4.0.75"
  channel_id: 42
  artifact_type: "handoff"
  artifact_kind: "continuity_report"
  purpose: "Continuity handoff from JetBrains to Windsurf for Antigravity-interrupted canonical schema reference and related table-doc alignment work."
  tags: ["handoff", "windsurf", "jetbrains", "antigravity", "schema", "actors", "collections", "organization", "documentation", "continuity"]
---

# JetBrains to Windsurf Continuity Handoff (Antigravity Interruption, 4.0.75)

## 1. Executive Summary

Antigravity originally owned the schema-reference documentation work and went offline mid-stream.  
JetBrains picked up the task, created and refined the canonical cross-domain reference, and performed targeted alignment updates in `tables/active/`.  
JetBrains is now near token exhaustion and hands execution continuity to Windsurf.

## 2. Original Antigravity Scope

Antigravity-origin scope (from directive chain in this thread):

- Convert synthesized TOON structure notes into a canonical long-term documentation artifact.
- Choose doctrine-aligned permanent destination in `lupo-docs/` (not status/prompts scratch space).
- Cover the 18-table synthesis across actors, collections, and organization.
- Preserve doctrine constraints affecting implementation (reserved IDs, no FK, timestamp doctrine, etc.).
- Ensure cross-domain doc remains consistent with detailed `tables/active/` table docs.
- Carry forward implications for actor/collection/channel/session/federation + forensic logging boundaries.

## 3. JetBrains Scope and Actions

JetBrains actions completed in this thread:

- Reviewed `lupo-docs/` structure and selected canonical location: `lupo-docs/database/lupopedia/tables/`.
- Created canonical reference file:
  - `lupo-docs/database/lupopedia/tables/lupopedia_actors_collections_organization_reference.md`
- Updated table-doc index for discoverability:
  - `lupo-docs/database/lupopedia/tables/README.md`
- Performed refinement pass requested later in thread and integrated:
  - anti-patterns/common mistakes
  - rule cross-reference table (doctrine docs, no invented numeric IDs)
  - safe query pattern examples
  - implementation quick-reference checklists
  - cross-table integrity invariants
  - performance notes
  - ASCII cross-domain relationship map
  - TOON regeneration + documentation sync workflow
  - validation queries
- Updated `tables/active` consistency where meaning changed:
  - `lupo-docs/database/lupopedia/tables/active/lupo_actors.md`
    - corrected PK wording to `actor_name` primary, `actor_id` unique numeric mapping
    - corrected stale `lupopedia.edges` targets to existing canonical paths

JetBrains work intentionally not done:

- No broad rewrite of other `tables/active/*.md` files beyond targeted consistency change.
- No changelog entry for this pass (not strictly required by local docs flow for this change set).

## 4. Files Reviewed

Primary files reviewed during this handoff chain:

- `lupo-docs/status/TOON_DATABASE_STRUCTURE_COLLECTIONS_ACTORS_ORGANIZATION.md`
- `lupo-docs/database/README.md`
- `lupo-docs/database/lupopedia/README.md`
- `lupo-docs/database/lupopedia/SCHEMA_REGISTRY.md`
- `lupo-docs/database/lupopedia/tables/README.md`
- `lupo-docs/database/lupopedia/tables/TABLE_INDEX.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_actors.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_collections.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_channels.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_sessions.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_registry.md`
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- TOON files reviewed for column/index validation:
  - `lupo_actors.toon.json`
  - `lupo_actor_channels.toon.json`
  - `lupo_actor_channel_roles.toon.json`
  - `lupo_collections.toon.json`
  - `lupo_collection_tabs.toon.json`
  - `lupo_collection_tab_map.toon.json`
  - `lupo_collection_tab_paths.toon.json`
  - `lupo_registry.toon.json`
  - `lupo_channels.toon.json`

## 5. Files Created or Updated

Created:

- `lupo-docs/database/lupopedia/tables/lupopedia_actors_collections_organization_reference.md`
- `lupo-docs/status/JETBRAINS_TO_WINDSURF_ANTIGRAVITY_SCHEMA_REFERENCE_CONTINUITY_HANDOFF_4_0_75.md` (this file)
- `TODO_windsurf.md` (root-level handoff TODO)

Updated:

- `lupo-docs/database/lupopedia/tables/README.md` (added index entry for canonical cross-domain reference)
- `lupo-docs/database/lupopedia/tables/active/lupo_actors.md` (PK doctrine wording + edge path corrections + last_modified_utc)

Context files that existed before and were used as source continuity:

- `lupo-docs/status/TOON_DATABASE_STRUCTURE_COLLECTIONS_ACTORS_ORGANIZATION.md`

## 6. Current State of the Canonical Reference

Main canonical file:

- `lupo-docs/database/lupopedia/tables/lupopedia_actors_collections_organization_reference.md`

Current strengths:

- Correct permanent placement in canonical database table-doc domain.
- Covers actors/collections/organization with doctrine constraints.
- Includes implementation implications and current-work relation section.
- Includes review-requested refinements (anti-patterns, query patterns, integrity notes, performance, TOON/doc sync, validation queries).
- Keeps cross-domain level (does not duplicate full table-level details).

Integrated review suggestions:

- title/positioning tightened around cross-domain canonical usage
- actor PK wording precision (`actor_name` doctrinal primary semantic key; `actor_id` unique numeric mapping)
- anti-patterns/common mistakes
- rule references (doctrine docs only; no invented IDs)
- query examples
- implementation quick reference
- integrity notes
- performance notes
- relationship map
- TOON regeneration workflow
- validation queries

Open items still possible (not blockers):

- Optional wording polish for brevity if maintainers want a shorter canonical style.
- Optional alignment sweep for other `tables/active/*.md` edge-path hygiene.

## 7. Relationship to `tables/active/`

Critical continuity point:

- `lupo-docs/database/lupopedia/tables/active/` remains the detailed per-table documentation layer.
- Cross-domain reference must not replace it; it must stay aligned with it.
- If cross-domain semantic meaning shifts, relevant `active/*.md` docs must be updated.

Most relevant active docs:

- `lupo-docs/database/lupopedia/tables/active/lupo_actors.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_collections.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_channels.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_sessions.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_registry.md`

## 8. Open Work Remaining

Remaining work for Windsurf:

- Verify final text quality in canonical reference for style consistency with neighboring canonical docs.
- Run a targeted consistency sweep across the five key `tables/active` files listed above:
  - confirm PK/unique wording matches install SQL + TOON
  - confirm high-value `lupopedia.edges` targets resolve to real canonical paths
- Decide if changelog/index updates are required for this pass in your release process.
- Produce final validation summary after doc alignment.

Potentially unfinished from broader chain (outside this exact doc pass):

- Some existing table docs still contain legacy/stale edge path conventions (`tables/` vs `tables/active/` in links).

## 9. Decisions Already Made

Treat these as current working assumptions unless direct source evidence disproves:

- Canonical cross-domain reference belongs in `lupo-docs/database/lupopedia/tables/`.
- Install SQL is DDL authority.
- TOON files are generated structural references and must align to install SQL.
- `tables/active/` is the table-level detailed documentation layer.
- Cross-domain reference should stay implementation-aware but not become a giant tutorial.
- No foreign keys doctrine remains active.
- BIGINT UTC `YYYYMMDDHHIISS` timestamp doctrine remains active.
- Forensic logging architecture should not overload core identity/collection tables.

## 10. Risks / Uncertainties

- Actor/faucet identity metadata across docs is not perfectly uniform; verify before broad metadata normalization.
- Some pre-existing `tables/active` docs include legacy assumptions (example: earlier actor PK wording was incorrect before this fix).
- Some edge references in the table-doc corpus may still point to non-canonical/older paths.
- Numeric rule ID matrix for this exact domain was not introduced because stable canonical IDs were not verified for all requested buckets.
- No runtime/test execution was performed for docs (documentation-only pass).

## 11. Recommended Next Steps for Windsurf

Step 1 — Read the canonical cross-domain reference and this handoff file end-to-end.  
Step 2 — Compare the five key `tables/active` docs against install SQL + TOON for semantic consistency.  
Step 3 — Apply only table-specific corrections needed for consistency (avoid cross-domain duplication).  
Step 4 — Verify `lupopedia.edges` target path validity in touched files.  
Step 5 — Update changelog/index only if required by your release/documentation process.  
Step 6 — Write a short final validation report in `lupo-docs/status/` summarizing final state.

## 12. Takeover Notes

Do not lose this context:

- Antigravity started this documentation track.
- JetBrains performed the canonical placement and refinement pass.
- Canonical file under active management is:
  - `lupo-docs/database/lupopedia/tables/lupopedia_actors_collections_organization_reference.md`
- Keep this file cross-domain and doctrine-first.
- Keep `tables/active/` docs as the detailed layer and align only where needed.

## TODO Location Decision

`TODO_windsurf.md` was placed at project root because:

- Root `TODO.md` already exists and is an established immediate task surface.
- The requested file is takeover-oriented and should be instantly discoverable without subfolder navigation.
- Status report remains in `lupo-docs/status/` for durable historical continuity.

## What Windsurf Should Read First

1. `lupo-docs/status/JETBRAINS_TO_WINDSURF_ANTIGRAVITY_SCHEMA_REFERENCE_CONTINUITY_HANDOFF_4_0_75.md`
2. `TODO_windsurf.md`
3. `lupo-docs/database/lupopedia/tables/lupopedia_actors_collections_organization_reference.md`
4. `lupo-docs/database/lupopedia/tables/active/lupo_actors.md`
5. `lupo-docs/database/lupopedia/tables/active/lupo_collections.md`
6. `lupo-docs/database/lupopedia/tables/active/lupo_channels.md`
7. `lupo-docs/database/lupopedia/tables/active/lupo_sessions.md`
8. `lupo-docs/database/lupopedia/tables/active/lupo_registry.md`
9. `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
10. `lupo-database/lupopedia/toon/lupo_actors.toon.json`
