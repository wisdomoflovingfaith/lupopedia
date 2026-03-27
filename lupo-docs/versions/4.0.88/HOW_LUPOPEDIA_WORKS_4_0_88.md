---
lupopedia.headers:
  lupopedia.version: "4.0.88"
  lupopedia.schema: "system_guide"
  file_path_from_root: "lupo-docs/versions/4.0.88/HOW_LUPOPEDIA_WORKS_4_0_88.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.88/HOW_LUPOPEDIA_WORKS_4_0_88.md"
  last_modified_utc: "20260327"
  channel_id: 42
  thread_id: "2007"
  actor_id: 102
  actor_name: "cursor"
  artifact_type: "version_documentation"
  artifact_kind: "system_orientation"
  purpose: "Practical guide to how Lupopedia works in 4.0.88 using repo and thread evidence"
  tags: ["4.0.88", "how_it_works", "organization", "hybrid_model", "onboarding"]

lupopedia.edges:
  outbound_edges:
    - { to: "README.md", type: "aligns_with", weight: 1.0 }
    - { to: "ORGANIZATION.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/ORGANIZATION.md", type: "references", weight: 1.0 }
    - { to: "AGENTS.md", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_authority", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/json/", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/", type: "references", weight: 1.0 }
    - { to: "lupo-channels/42/threads/2007/THREAD_INDEX.md", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260327"
  verified_by:
    identity_type: "actor"
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "cursor"
---

# How Lupopedia Works in 4.0.88

## 1) Core Model

Lupopedia is a hybrid system:
1. MySQL provides structured runtime authority.
2. filesystem artifacts provide coordination, documentation, and review-visible traces.

Both are required to understand ongoing work.

## 2) Runtime vs Coordination Surfaces

Runtime-heavy surfaces:
1. `lupo-includes/`
2. `lupo-database/lupopedia/mysql/`
3. `lupo-app/` and app/runtime code surfaces used by loader/bootstrap flow

Coordination-heavy surfaces:
1. `lupo-channels/`
2. `lupo-docs/`
3. `lupo-database/sessions/`
4. `lupo-sessions/`

## 3) Schema and Docs Relationship

1. Install SQL is canonical schema authority:
   - `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
2. TOON and JSON are derived exports:
   - `lupo-database/lupopedia/toon/`
   - `lupo-database/lupopedia/json/`
3. Table docs are human-readable semantic/reference surfaces:
   - `lupo-docs/database/lupopedia/tables/active/`

Practical note from 4.0.88 thread execution:
1. JSON exports were used as reliable tooling input in the regeneration/verification chain.
2. TOON/JSON do not fully capture historical metadata and rich edge semantics.

## 4) Channels and Threads

Channels are not only conceptual. They are active execution surfaces.

Pattern used in 4.0.88:
1. directives, reports, validations, and stage transitions were posted to Channel 42 Thread 2007.
2. thread index was used as canonical ledger for state.
3. version docs are expected to reflect those artifacts.

## 5) Context Layer (Current State)

Intent:
1. contexts should refine task/question/artifact grouping above channel flow.

Current reality:
1. `lupo-context/` exists.
2. implementation is incomplete and not yet mature across workflows.

## 6) Why File-Visible Artifacts Matter

For IDE agents and humans, file-visible artifacts provide:
1. auditable chronology.
2. deterministic cross-linking.
3. shared truth for handoff and checkpointing.
4. grounded claims instead of memory-based summaries.

## 7) Where To Start Reading

1. `README.md`
2. `ORGANIZATION.md`
3. `lupo-docs/ORGANIZATION.md`
4. `lupo-docs/versions/4.0.88/README.md`
5. `lupo-docs/versions/4.0.88/THREAD_2007_WORK_SUMMARY.md`
6. `lupo-docs/versions/4.0.88/MYSQL_TOON_JSON_TABLE_DOCS_AUTHORITY_MAP.md`
7. `lupo-docs/versions/4.0.88/CORRUPTION_INCIDENT_AND_REMEDIATION_STATUS.md`
