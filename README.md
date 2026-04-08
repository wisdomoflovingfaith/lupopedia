---
lupopedia.headers:
  header_format_version: 2
  when_updated: '20260407233334'
  lupopedia.schema: documentation
  file_path_from_root: README.md
  web_path: http://www.lupopedia.com/lupopedia/README.md
  last_modified_utc: '20260407233334'
  channel_id: 42
  thread_id: readme-4-0-96
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: project_documentation
  artifact_kind: readme
  purpose: Constitutional compliance, Y2038-safe time model, PHP 7.4+ and 64-bit production floor — root entry for humans and all agents.
  tags:
    - readme
    - 4.0.96
    - architecture
    - doctrine
    - workflow
    - y2038
    - constitution
lupopedia.init:
  required_reading:
    - path: lupo-docs/prd/00_root_constitutional_system_requirements.md
      reason: "MANDATORY FIRST READ — constitutional law for all agents and contributors. Overrides everything else."
    - path: lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/00_constitution_shorthand.pseudo.md
      reason: "Short constitutional digest (pseudo shorthand) for quick agent orientation"
    - path: lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md
      reason: "4.1.0 / auto-installer gate; clarifies no Lupopedia→Lupopedia upgrades during 4.0.x"
    - path: lupo-docs/prd/27_installer_requirements.md
      reason: "Installer and 4.0.x fresh-install model (install SQL + mysql/seed/ + optional install/ merged seed + Crafty import)"
    - path: AGENTS.md
      reason: "Canonical actor, identity-layer, and coordination rules"
    - path: ONBOARDING.md
      reason: "Operational quick-start"
    - path: lupo-rules/root/WOLFIE_DOCTRINE.md
      reason: "Engineering philosophy — read before touching any existing code"
    - path: lupo-rules/root/README.md
      reason: "Complete root rules and development constraints"
    - path: lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
      reason: "Header/footer validation doctrine"
    - path: lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md
      reason: "Canonical five-layer identity model"
    - path: lupo-docs/versions/4.0.96/README.md
      reason: "Current working version overview (4.0.95 line finalized)"
    - path: lupo-docs/versions/4.0.96/TODO.md
      reason: "Current task registry and backlog for the active patch line"
    - path: lupo-docs/versions/4.0.96/CHANGELOG.md
      reason: "Canonical patch-line changelog for 4.0.96"
    - path: lupo-docs/versions/4.0.95/decisions/
      reason: "Architecture decisions for the finalized 4.0.95 line (historical)"
    - path: lupo-channels/channel_index.md
      reason: Canonical channel map and path policy
    - path: ORGANIZATION.md
      reason: Canonical root folder map and repository write guidance
    - path: lupo-docs/doctrine/TICK_PY_DOCTRINE.md
      reason: Mandatory real UTC for headers — run tick.py; never guess timestamps
lupopedia.edges:
  comment: Snapshot of root documentation references for version-driven execution and release continuity.

  outbound_edges:
    - to: lupo-content/federation_node/0/captains_log/20260407_hello_world.md
      type: references
      weight: 1.0
      reason: Captain's Log Entry 001 — Wolfie's personal and technical journey behind Lupopedia's constitutional doctrine (2026-04-07)
    - to: lupo-docs/prd/00_root_constitutional_system_requirements.md
      type: references
      weight: 1.0
      reason: Constitutional anchor — mandatory first read for all agents
    - to: lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/00_constitution_shorthand.pseudo.md
      type: references
      weight: 1.0
      reason: Constitutional shorthand digest for agents
    - to: AGENTS.md
      type: aligns_with
      weight: 1.0
    - to: lupo-rules/root/WOLFIE_DOCTRINE.md
      type: references
      weight: 1.0
      reason: Engineering philosophy binding on all agents
    - to: lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md
      type: aligns_with
      weight: 1.0
    - to: lupo-rules/root/README.md
      type: references
      weight: 1.0
      reason: Complete root rules and development constraints
    - to: ONBOARDING.md
      type: references
      weight: 0.95
    - to: lupo-docs/versions/4.0.96/README.md
      type: references
      weight: 1.0
      reason: Current working version overview (active 4.0.96 line)
    - to: lupo-docs/versions/4.0.96/TODO.md
      type: references
      weight: 1.0
      reason: Current task tracking and backlog
    - to: lupo-docs/versions/4.0.96/CHANGELOG.md
      type: references
      weight: 1.0
      reason: Canonical patch-line changelog for 4.0.96
    - to: lupo-docs/versions/4.0.95/README.md
      type: references
      weight: 0.95
      reason: Finalized 4.0.95 line (closed)
    - to: lupo-docs/versions/4.0.95/decisions/
      type: references
      weight: 0.9
      reason: Decisions recorded under the finalized 4.0.95 line
    - to: lupo-docs/versions/4.1.0/plan.md
      type: references
      weight: 0.95
    - to: lupo-docs/versions/4.1.0/prd/README.md
      type: references
      weight: 1.0
    - to: ORGANIZATION.md
      type: references
      weight: 0.95
    - to: lupo-docs/prd/02_channels_discussions.md
      type: references
      weight: 0.95
      reason: Channel threads, THREAD_MANIFEST.md, decisions/questions/answers/comments layout
    - to: lupo-docs/doctrine/TICK_PY_DOCTRINE.md
      type: references
      weight: 1.0
      reason: Temporal anchor and tick.py workflow for all header timestamps
    - to: lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md
      type: references
      weight: 1.0
      reason: 4.1.0 release gate; no Lupopedia→Lupopedia migrations during 4.0.x
    - to: lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md
      type: references
      weight: 0.95
      reason: FTP-safe zip; WordPress-style no dotfiles; installer writes .htaccess
    - to: lupo-docs/prd/27_installer_requirements.md
      type: references
      weight: 1.0
      reason: Installer requirements; install SQL + seed pipeline (mysql/seed/, install/seed_lupopedia_4_1_0.sql) + Crafty import for 4.0.x
    - to: lupo-docs/doctrine/VERSIONING_DOCTRINE.md
      type: references
      weight: 1.0
      reason: Canonical versioning and upgrade-path doctrine
    - to: lupo-docs/prd/40_versioning_doctrine.md
      type: references
      weight: 1.0
      reason: PRD 40 — versioning doctrine (4.0.x line, Crafty path, 4.1.0 gate)
lupopedia.footer:
  last_verified: '20260407233334'
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: Cursor IDE Agent (Lead Orchestration)
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: cursor:root
  next_action:
    - Keep constitution + Y2038 narrative aligned with PRD 00 and install preflight
    - Point agents to shorthand pseudo and AGENTS.md for test commands

---
# file: Lupopedia README - delegation: cursor:root - web_path: [http://www.lupopedia.com/lupopedia/README.md](http://www.lupopedia.com/lupopedia/README.md)

# LUPOPEDIA - Constitutional AI Agent Framework

**Current version:** **4.0.96** (`GLOBAL_CURRENT_LUPOPEDIA_VERSION` in `lupo-config/global_atoms.yaml`; runtime: `LUPOPEDIA_VERSION` from `lupo-includes/version.php`).  
**Version family:** 4.0.x (Y2038-compliant packed UTC time model).  
**License:** GPL v3  
**PHP minimum:** 7.4 (**64-bit required** for production — enforced in `install.php` preflight)

## 1. What Is Lupopedia? (High-Level Summary)

Lupopedia is a doctrine-driven semantic OS and multi-agent platform that evolves the Crafty Syntax 3.7.5 lineage into a constitutional architecture for modern orchestration, truth management, and channel-based collaboration. It is built as a shared-hosting-compatible system with deterministic behavior, explicit identity boundaries, and file-backed operational continuity where required. The project treats PRDs and doctrine as implementation authority, with application logic enforcing constraints that are often delegated to frameworks or database-side automation elsewhere. Lupopedia prioritizes auditable operation across humans and agents, including structured channels, implementation mirrors, and reproducible artifact patterns. Its 4.0.x line is installer-first and doctrine-governed, with the 4.1.0 gate tied to distribution and upgrade-readiness requirements.  
**See also:** [Captain's Log Entry 001](lupo-content/federation_node/0/captains_log/20260407_hello_world.md)

## 2. Core Constitutional Principles

- **Root constitutional system requirements (supreme authority):** [PRD 00](lupo-docs/prd/00_root_constitutional_system_requirements.md)
- **Timestamp doctrine (packed UTC, deterministic clocks, Y2038-safe model):**
  - [lupo-docs/doctrine/TIMESTAMP_DOCTRINE.md](lupo-docs/doctrine/TIMESTAMP_DOCTRINE.md)
  - [lupo-rules/root/TIMESTAMP_DOCTRINE.md](lupo-rules/root/TIMESTAMP_DOCTRINE.md)
  - [PRD 15 Temporal System](lupo-docs/prd/15_temporal_system.md)
- **Identity layers doctrine (auth user, actor, department, agent, faucet):**
  - [lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md](lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md)
  - [PRD 05](lupo-docs/prd/05_auth_user_actor_agent_transformation.md)
  - [PRD 07](lupo-docs/prd/07_agents_faucets.md)
  - [PRD 15](lupo-docs/prd/15_actors.md)
  - [PRD 25](lupo-docs/prd/25_departments_system.md)
  - [PRD 32](lupo-docs/prd/32_actor_authority_agent_roles.md)
- **File-backed content doctrine and continuity model:**
  - [PRD 06 Content Management](lupo-docs/prd/06_content_management.md)
  - [FILESYSTEM_OBJECTS_AND_DATABASE_SNAPSHOTS.md](lupo-docs/doctrine/FILESYSTEM_OBJECTS_AND_DATABASE_SNAPSHOTS.md)
  - [PRD 02 Channels Discussions](lupo-docs/prd/02_channels_discussions.md)
- **Database neutral SQL doctrine:** [lupo-rules/root/DATABASE_NEUTRAL_SQL_DOCTRINE.md](lupo-rules/root/DATABASE_NEUTRAL_SQL_DOCTRINE.md)
- **Subdirectory installation doctrine:**
  - [SUBDIRECTORY_INSTALLATION_DOCTRINE.md](lupo-docs/channels/doctrine/SUBDIRECTORY_INSTALLATION_DOCTRINE.md)
  - [INSTALLATION_PATH_DOCTRINE.md](lupo-docs/doctrine/INSTALLATION_PATH_DOCTRINE.md)
  - [PRD 27 Installer Requirements](lupo-docs/prd/27_installer_requirements.md)
- **Versioning and release gate doctrine:**
  - [PRD 40 Versioning Doctrine](lupo-docs/prd/40_versioning_doctrine.md)
  - [VERSIONING_DOCTRINE.md](lupo-docs/doctrine/VERSIONING_DOCTRINE.md)
  - [PRD 33 Softaculous Certification 4.1.0 Gate](lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md)

## 3. Actor Model: Why It Is Different

Lupopedia uses a **three-layer identity model** that differs from many “one human → one private agent” products. Canonical detail: [IDENTITY_LAYERS_DOCTRINE.md](lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md), [PRD 05](lupo-docs/prd/05_auth_user_actor_agent_transformation.md), [PRD 15](lupo-docs/prd/15_actors.md), [PRD 25](lupo-docs/prd/25_departments_system.md).

### 3.1 The Layers

| Layer | What | Where | Example |
|-------|------|-------|---------|
| **Auth User** | Human or system account that authenticates | `lupo_auth_users` | Operator login (`auth_user_id` from registry / seed) |
| **Actor** | Runtime persona that does work | `lupo_actors` + optional `lupo-actors/{actor_id}/` hub | **WOLFIE** (`actor_id = 1`) |
| **Agent** | Immutable template pack (filesystem) | `lupo-agents/{agent_key}/` | `lupo-agents/wolfie/` |

### 3.2 How They Relate

```
Auth User (human)
    │
    ├── belongs to Department(s)  (lupo_auth_user_departments)
    │
    ▼
Department  (e.g. Sales, Engineering, Root / department_id = 0)
    │
    ├── has many Actors assigned  (lupo_actor_departments)
    │
    ▼
Actor (persona)
    │
    ├── aligns with an Agent template (filesystem + lupo_agents metadata)
    ├── learns from ALL Auth Users who share its department context
    └── memory diverges from the static template over time
```

### 3.3 Shared Persona (Collective Intelligence)

**Typical product:** User A → private Agent A (learns only from User A).

**Lupopedia:** Department → **shared** Actor → many humans in that department **act as the same actor**. The actor accumulates **department-scoped** behavior; each human benefits from what colleagues taught the persona.

**Example:** Five sales reps all act as a **Sales-scoped** WOLFIE-class actor. The actor’s memory and habits improve from all five, bounded by department membership—not by a private per-user bot.

### 3.4 Access Rules (Web UI)

An Auth User may act as an Actor only when **department intersection** holds: some `department_id` appears in both the user’s memberships and the actor’s assignments.

```sql
-- Actors this user may act as (illustrative; enforce in PHP + PDO_DB)
SELECT DISTINCT a.*
FROM lupo_actors a
INNER JOIN lupo_actor_departments ad
  ON ad.actor_id = a.actor_id AND ad.is_deleted = 0
WHERE ad.department_id IN (
    SELECT aud.department_id
    FROM lupo_auth_user_departments aud
    WHERE aud.auth_user_id = :current_auth_user_id
      AND aud.is_deleted = 0
)
  AND a.is_deleted = 0;
```

**Root department:** **`department_id = 0`** is the **Root** scope in current seed/import doctrine. Users with **department 0** membership participate in the unrestricted root context; resolve edge cases in application policy (see [PRD 25](lupo-docs/prd/25_departments_system.md)).

### 3.5 Access Rules (Terminal / IDE)

For local **CLI** and **IDE** workflows, the runtime typically uses a **root-equivalent** session: **department context 0** and permission to target **any `actor_id`** for tooling (no per-user department intersection). That is why maintenance scripts and IDE agents can reference **WOLFIE (`actor_id = 1`)** or other actors without mirroring a human’s web session.

**Do not** mint separate `lupo_auth_users` rows for IDE products; attribute work via **facet `actor_id`** (Cursor, Claude Code, …) per [AGENTS.md](AGENTS.md) and the actor registry.

### 3.6 Why This Matters

1. **Actors learn from groups**, not isolated accounts—**collective** intelligence inside a department boundary.
2. **Same agent pack, different actors**—e.g. sales-scoped vs engineering-scoped personas diverge by context.
3. **Memory divergence**—compare filesystem **agent** packs with **actor-scoped** memory in `lupo_memory_nodes` / `lupo_memory_edges` ([PRD 38](lupo-docs/prd/38_memory_unification.md)).
4. **Boundaries**—non-root departments do not implicitly inherit another department’s actors.

### 3.7 Root `auth_user_id` (Doctrine)

| Context | `auth_user_id` | Notes |
|---------|----------------|--------|
| Doctrine (PRD 01) | **0** | Reserved **root** auth user id |
| Web / seed rows | Per install | Concrete operator rows use ids from **IdGenerator** / seed; **effective** admin/root resolution is application-defined |
| CLI / IDE | Root-equivalent | Tooling assumes **full actor reach**; not a separate “IDE login” user |

**`auth_user_id = 0` is not the same thing as `actor_id = 1` (WOLFIE).** Auth users authenticate humans; actors orchestrate work.

### 3.8 Visual Summary

```
┌─────────────────────────────────────────────────────────────────┐
│              AUTH USER (human operator)                         │
│     auth_user_id from install; doctrine reserves root = 0       │
└─────────────────────────────────────────────────────────────────┘
                              │
                              │ lupo_auth_user_departments
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│            DEPARTMENT 0 (Root) — full context                     │
└─────────────────────────────────────────────────────────────────┘
                              │
                              │ lupo_actor_departments
              ┌───────────────┼───────────────┐
              ▼               ▼               ▼
        ┌──────────┐   ┌──────────┐   ┌──────────┐
        │  WOLFIE  │   │  LILITH  │   │  THOTH   │
        │ actor 1  │   │ actor 2  │   │ (registry)│
        └──────────┘   └──────────┘   └──────────┘
              │               │
              │ template      │
              ▼               ▼
        lupo-agents/    lupo-agents/
           wolfie/        lilith/
```

**Non-root example:** A user in **department 2 (Sales)** only intersects **Sales-scoped** actors; they do **not** automatically act as root **WOLFIE (`actor_id = 1`)** unless policy and `lupo_actor_departments` / `lupo_auth_user_departments` rows say so.

### 3.9 Memory Divergence (Install-Aligned)

`lupo_memory_nodes` uses **`owner_actor_id`** and **`owner_type`** (see `install_new_lupopedia.sql`). Actor rows hold **learned** content; **`lupo-agents/`** remains the **static** template. Relationships between nodes use **`lupo_memory_edges`** (`edge_type` by application convention). Full model: [PRD 38](lupo-docs/prd/38_memory_unification.md), [PRD 01](lupo-docs/prd/01_core_identity.md).

## 4. Repository Structure Overview

- **`lupo-content/`**: File-backed content artifacts and federation-node scoped content structure; see [PRD 06](lupo-docs/prd/06_content_management.md) and [PRD 29](lupo-docs/prd/29_project_structure.md).
- **`lupo-docs/`**: PRDs, doctrine, implementation mirrors, and governance records; see [PRD_INDEX.md](lupo-docs/prd/PRD_INDEX.md), [PRD 00](lupo-docs/prd/00_root_constitutional_system_requirements.md), and [PRD 31](lupo-docs/prd/31_implementation_folder_guidelines.md).
- **`lupo-agents/`**: Agent/faucet config surfaces and identity-linked metadata; see [PRD 07](lupo-docs/prd/07_agents_faucets.md) and [PRD_AGENT_DEFINITION_MODEL.md](lupo-docs/prd/PRD_AGENT_DEFINITION_MODEL.md).
- **`lupo-database/`**: Install SQL, seed/import paths, schema artifacts, and data-model anchors; see [PRD 02 Data Model](lupo-docs/prd/02_data_model.md), [PRD 13 Crafty Integration](lupo-docs/prd/13_crafty_integration.md), [PRD 27](lupo-docs/prd/27_installer_requirements.md), and [PRD 40](lupo-docs/prd/40_versioning_doctrine.md).
- **`lupo-includes/`**: Core runtime stack (modules/classes/themes) and shared request-path logic.
- **`app/`**: Application services implementing auth/session/domain logic under constitutional constraints.

## 5. Installation Overview

1. Prepare a supported environment and deploy Lupopedia into a subdirectory path.
2. Run `install.php` and complete installer requirements for database and runtime validation.
3. Apply canonical install/seed/import flow per the 4.0.x model (including Crafty Syntax 3.7.5 import path where applicable).
4. Run project validation/tests and confirm doctrine-aligned runtime behavior.

Details:
- [PRD 27 Installer Requirements](lupo-docs/prd/27_installer_requirements.md)
- [PRD 33 Softaculous Certification 4.1.0 Gate](lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md)
- [SOFTACULOUS_PACKAGE_BUILD.md](lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md)
- [PRD 13 Crafty Integration](lupo-docs/prd/13_crafty_integration.md)

## 6. Developer Workflow

- **File-backed content workflow:** follow content and channel doctrine before implementation details; start with [PRD 06](lupo-docs/prd/06_content_management.md), [PRD 02](lupo-docs/prd/02_channels_discussions.md), [PRD 17](lupo-docs/prd/17_decisions_format.md), and [PRD 21](lupo-docs/prd/21_thread_graduation_doctrine.md).
- **PRD-driven implementation workflow:** use PRD contracts and implementation mirrors (`lupo-docs/implementations/{prd_file_stem}/`) per [PRD 31](lupo-docs/prd/31_implementation_folder_guidelines.md) and [PRD 29](lupo-docs/prd/29_project_structure.md).
- **Agents and faucets workflow:** maintain actor authority boundaries and faucet role separation per [PRD 05](lupo-docs/prd/05_auth_user_actor_agent_transformation.md), [PRD 07](lupo-docs/prd/07_agents_faucets.md), [PRD 15](lupo-docs/prd/15_actors.md), [PRD 25](lupo-docs/prd/25_departments_system.md), and [PRD 32](lupo-docs/prd/32_actor_authority_agent_roles.md).
- **Doctrine/rule propagation workflow:** keep root rules and coordination docs aligned using [lupo-rules/root/README.md](lupo-rules/root/README.md), [AGENTS.md](AGENTS.md), and [ONBOARDING.md](ONBOARDING.md).

## 7. PRD Index (Linked)

- [00_root_constitutional_system_requirements.md](lupo-docs/prd/00_root_constitutional_system_requirements.md)
- [01_captain_wolfie_identity.md](lupo-docs/prd/01_captain_wolfie_identity.md)
- [01_core_identity.md](lupo-docs/prd/01_core_identity.md)
- [02_channels_discussions.md](lupo-docs/prd/02_channels_discussions.md)
- [02_data_model.md](lupo-docs/prd/02_data_model.md)
- [03_goals_and_success_criteria.md](lupo-docs/prd/03_goals_and_success_criteria.md)
- [03_truth_knowledge.md](lupo-docs/prd/03_truth_knowledge.md)
- [04_lupopedia_js_foundation.md](lupo-docs/prd/04_lupopedia_js_foundation.md)
- [04_tags_metadata.md](lupo-docs/prd/04_tags_metadata.md)
- [05_auth_user_actor_agent_transformation.md](lupo-docs/prd/05_auth_user_actor_agent_transformation.md)
- [05_collections_navigation.md](lupo-docs/prd/05_collections_navigation.md)
- [06_content_management.md](lupo-docs/prd/06_content_management.md)
- [07_agents_faucets.md](lupo-docs/prd/07_agents_faucets.md)
- [08_actors.md](lupo-docs/prd/08_actors.md)
- [08_governance_rules.md](lupo-docs/prd/08_governance_rules.md)
- [09_federation_sync.md](lupo-docs/prd/09_federation_sync.md)
- [10_tasks_workflow.md](lupo-docs/prd/10_tasks_workflow.md)
- [11_analytics_tracking.md](lupo-docs/prd/11_analytics_tracking.md)
- [12_api_integration.md](lupo-docs/prd/12_api_integration.md)
- [13_crafty_integration.md](lupo-docs/prd/13_crafty_integration.md)
- [14_system_operations.md](lupo-docs/prd/14_system_operations.md)
- [15_actors.md](lupo-docs/prd/15_actors.md)
- [15_temporal_system.md](lupo-docs/prd/15_temporal_system.md)
- [16_lupopedia_headers.md](lupo-docs/prd/16_lupopedia_headers.md)
- [17_decisions_format.md](lupo-docs/prd/17_decisions_format.md)
- [18_channel_chat_display.md](lupo-docs/prd/18_channel_chat_display.md)
- [19_garbage_collection_system.md](lupo-docs/prd/19_garbage_collection_system.md)
- [20_federation_intake_doctrine.md](lupo-docs/prd/20_federation_intake_doctrine.md)
- [20_vsx_extension.md](lupo-docs/prd/20_vsx_extension.md)
- [21_semantic_navbar.md](lupo-docs/prd/21_semantic_navbar.md)
- [21_thread_graduation_doctrine.md](lupo-docs/prd/21_thread_graduation_doctrine.md)
- [22_web_navigation_architecture.md](lupo-docs/prd/22_web_navigation_architecture.md)
- [23_health_check_asclepius_prd.md](lupo-docs/prd/23_health_check_asclepius_prd.md)
- [24_actor_onboarding_flow.md](lupo-docs/prd/24_actor_onboarding_flow.md)
- [24_cli_interface_prd.md](lupo-docs/prd/24_cli_interface_prd.md)
- [25_departments_system.md](lupo-docs/prd/25_departments_system.md)
- [26_five_layer_documentation_architecture.md](lupo-docs/prd/26_five_layer_documentation_architecture.md)
- [27_installer_requirements.md](lupo-docs/prd/27_installer_requirements.md)
- [28_semantic_monitoring_widget.md](lupo-docs/prd/28_semantic_monitoring_widget.md)
- [29_project_structure.md](lupo-docs/prd/29_project_structure.md)
- [30_channel_usage_patterns.md](lupo-docs/prd/30_channel_usage_patterns.md)
- [31_implementation_folder_guidelines.md](lupo-docs/prd/31_implementation_folder_guidelines.md)
- [32_actor_authority_agent_roles.md](lupo-docs/prd/32_actor_authority_agent_roles.md)
- [33_softaculous_certification_4_1_0_gate.md](lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md)
- [34_federation_node_semantic_network.md](lupo-docs/prd/34_federation_node_semantic_network.md)
- [35_mobile_native_app_separation.md](lupo-docs/prd/35_mobile_native_app_separation.md)
- [36_rose_multi_persona_synthetic_dialog.md](lupo-docs/prd/36_rose_multi_persona_synthetic_dialog.md)
- [37_kairos_channel_memory_consolidation.md](lupo-docs/prd/37_kairos_channel_memory_consolidation.md)
- [40_versioning_doctrine.md](lupo-docs/prd/40_versioning_doctrine.md)
- [PRD_AGENT_DEFINITION_MODEL.md](lupo-docs/prd/PRD_AGENT_DEFINITION_MODEL.md)
- [PRD_INDEX.md](lupo-docs/prd/PRD_INDEX.md)
- [README.md](lupo-docs/prd/README.md)
- [WHAT_TO_DO_NEXT.md](lupo-docs/prd/WHAT_TO_DO_NEXT.md)

## 8. Contributing / Stewardship Notes

- **Doctrine-first development:** always start with [PRD 00](lupo-docs/prd/00_root_constitutional_system_requirements.md) and applicable PRD contracts.
- **No schema inference:** use canonical schema and install/doctrine sources, including [PRD 02](lupo-docs/prd/02_data_model.md), [PRD 27](lupo-docs/prd/27_installer_requirements.md), and [SCHEMA_CANONICAL_SOURCES.md](lupo-docs/doctrine/SCHEMA_CANONICAL_SOURCES.md).
- **No framework dependencies in core runtime paths:** follow constitutional/runtime rules in [PRD 00](lupo-docs/prd/00_root_constitutional_system_requirements.md) and [lupo-rules/root/README.md](lupo-rules/root/README.md).
- **Packed UTC timestamps:** use doctrine-aligned packed UTC handling and validation via [TIMESTAMP_DOCTRINE.md](lupo-docs/doctrine/TIMESTAMP_DOCTRINE.md) and [TICK_PY_DOCTRINE.md](lupo-docs/doctrine/TICK_PY_DOCTRINE.md).
- **File-backed content and channel discipline:** preserve thread/artifact structure and implementation mirrors using [PRD 02](lupo-docs/prd/02_channels_discussions.md), [PRD 17](lupo-docs/prd/17_decisions_format.md), [PRD 21](lupo-docs/prd/21_thread_graduation_doctrine.md), [PRD 29](lupo-docs/prd/29_project_structure.md), and [PRD 31](lupo-docs/prd/31_implementation_folder_guidelines.md).

---

## Further Reading (Maintainers)

- [AGENTS.md](AGENTS.md)
- [ONBOARDING.md](ONBOARDING.md)
- [ORGANIZATION.md](ORGANIZATION.md)
- [lupo-docs/versions/4.0.96/README.md](lupo-docs/versions/4.0.96/README.md) — **current patch line**
- [lupo-docs/versions/4.0.96/TODO.md](lupo-docs/versions/4.0.96/TODO.md)
- [lupo-docs/versions/4.0.96/CHANGELOG.md](lupo-docs/versions/4.0.96/CHANGELOG.md)
- [lupo-docs/versions/4.0.95/README.md](lupo-docs/versions/4.0.95/README.md) — finalized **4.0.95** line
