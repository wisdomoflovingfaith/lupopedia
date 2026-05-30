# New Schema Tables — Crafty Syntax Migration Mapping
# Date: 20260406 | Author: claude-code (actor_id 102)
# Source: schema_corrected_core.sql + schema_corrected_missing.sql

This document maps every new table introduced in the corrected schema (20260406)
to its Crafty Syntax source — or documents why it has no Crafty source.

---

## Tables WITH Crafty Source Data

### lupo_actor_filesystem
**Status:** POPULATED during import
**Source:** Computed from lupo_actors (post-insert)

No Crafty table contains filesystem paths. Paths are computed deterministically:
```
actor_root_path = CONCAT('uploads/actors/', actor_id, '/')
workspace_path  = NULL  (set post-install for IDE actors only)
php_namespace   = NULL  (set post-install for IDE actors only)
```

Populated for:
- Operator actors (actor_id = 10000 + crafty user_id)
- Department hybrid actors (actor_id = 280000 + department_id)

**Why moved out of lupo_actors:** The old schema had `actor_root_path varchar(512) DEFAULT 'actors/{actor_id}'`. This was a misleading template literal — MySQL never interpolates `{actor_id}`. The corrected schema computes real paths at import time and stores them in this satellite table.

---

### lupo_actor_sync_state
**Status:** POPULATED during import
**Source:** Initialized (no Crafty source)

Crafty Syntax has no WHO.json sync concept. All imported actors start with `sync_status = 'pending'`.

Populated for:
- All imported operator actors
- All department hybrid actors

The sync process (generating WHO.json files) runs post-import via the admin wizard.

---

## Tables WITH NO Crafty Source Data

### lupo_actor_pairing
**Status:** NOT POPULATED during import
**Source:** None — Crafty has no pairing concept

The old `lupo_actors.paired_actor_id` column was removed and moved to this table. Crafty Syntax operators had no pairing relationships. This table is populated post-install when WOLFIE and HEPHAESTUS are paired, etc.

---

### lupo_actor_relationships
**Status:** NOT POPULATED during import
**Source:** None — Crafty has no adversarial/oversight concept

The old `lupo_actors.adversarial_role` and `adversarial_oversight_actor_id` columns were removed and moved here. Crafty has no such relationships. This table is seeded at install for the WOLFIE–LILITH oversight relationship.

---

### lupo_agent_definitions
**Status:** NOT POPULATED during import — seeded at install
**Source:** None — Crafty has no agent template concept

The Lupopedia agent template system (lupo-agents/ filesystem) has no Crafty equivalent. Agent definitions are seeded from the `lupo-agents/` directory during fresh install, not from Crafty data.

---

### lupo_agent_llm_configs
**Status:** NOT POPULATED during import — seeded at install
**Source:** None — Crafty has no LLM concept

Crafty Syntax predates LLM integration. These configs are set during post-install setup.

---

### lupo_agent_capabilities
**Status:** NOT POPULATED during import — seeded at install
**Source:** None

Seeded from `lupo-agents/{slug}/capabilities.json` files during install.

---

### lupo_agent_tools
**Status:** NOT POPULATED during import — seeded at install
**Source:** None

Seeded from `lupo-agents/{slug}/tools.json` files during install.

---

### lupo_agent_boundaries
**Status:** NOT POPULATED during import — seeded at install
**Source:** None

Seeded from `lupo-agents/{slug}/boundaries.json` files during install.

---

### lupo_agent_memory_config
**Status:** NOT POPULATED during import — seeded at install
**Source:** None

Default memory configuration per agent. Seeded during install.

---

### lupo_agent_performance_stats
**Status:** NOT POPULATED during import
**Source:** None — populated at runtime

Performance metrics accumulate at runtime. No Crafty equivalent.

---

### lupo_kairos_observations
**Status:** NOT POPULATED during import
**Source:** None — Crafty has no KAIROS equivalent

KAIROS memory system has no Crafty predecessor. Starts empty post-install.

---

### lupo_kairos_memory
**Status:** NOT POPULATED during import
**Source:** None — Crafty has no KAIROS equivalent

---

### lupo_actor_runtime_state
**Status:** NOT POPULATED during import
**Source:** None — initialized at first login

Runtime state is ephemeral and session-specific. First login populates this table.

---

### lupo_actor_runtime_events
**Status:** NOT POPULATED during import
**Source:** None — populated at runtime

---

### lupo_actor_versions
**Status:** NOT POPULATED during import
**Source:** None — no version history to import

Crafty operators had no versioning. Version history starts from first post-import change.

---

### lupo_agent_definition_versions
**Status:** NOT POPULATED during import
**Source:** None

---

### lupo_faucet_rules
**Status:** NOT POPULATED during import
**Source:** None — Crafty has no faucet concept

Faucet rules are configured post-install per the Faucet Proxy Pattern (v4.0.90+). Seeded for HEPHAESTUS (actor_id 102) and each IDE actor.

---

### lupo_pairing_rules
**Status:** NOT POPULATED during import
**Source:** None — configured post-install

---

### lupo_department_capabilities
**Status:** NOT POPULATED during import
**Source:** None — configured post-install

---

### lupo_identity_layers
**Status:** SEEDED at install (2 hardcoded rows)
**Source:** None — doctrine constant

```sql
INSERT INTO lupo_identity_layers VALUES
  (1, 'template', 'Agent Template Layer', 'template', 'Immutable filesystem blueprint', 0, ...),
  (2, 'runtime',  'Actor Runtime Layer',  'runtime',  'Living runtime instance', 1, ...);
```

---

### lupo_identity_context
**Status:** NOT POPULATED during import
**Source:** None — populated at runtime

---

### lupo_versions
**Status:** SEEDED at install
**Source:** None — schema version record

The install script seeds the current schema version (4.0.95). Import does not modify this.

---

### lupo_edge_types (consolidated)
**Status:** SEEDED at install (system edge types)
**Source:** Partial — system edge types are seeded, not from Crafty

The old `lupo_edge_types` and `lupo_edge_type_definitions` tables are merged. System edge types (semantic, ownership, dependency, etc.) are seeded at install. No Crafty data maps here.

---

### lupo_actor_faucets (renamed from lupo_agent_faucets)
**Status:** NOT POPULATED during import
**Source:** None — Crafty has no faucet concept

Crafty Syntax is not an IDE-integrated system. Faucet configuration is post-install.

---

### lupo_governance_overrides (PK typo fixed)
**Status:** NOT POPULATED during import
**Source:** None — configured post-install

The corrected schema fixes the typo `governance_overrid_id` → `governance_override_id`. Import SQL does not reference this table.

---

### lupo_actor_moods (PK added, is_deleted added)
**Status:** NOT POPULATED during import
**Source:** None — Crafty has no mood system

The corrected schema adds `actor_mood_id` PK and `is_deleted`. No Crafty data maps here.

---

## Summary Table

| New Table | Source | Import Action |
|---|---|---|
| lupo_actor_filesystem | Computed from actors | INSERT after actor creation |
| lupo_actor_sync_state | Initialized (no source) | INSERT 'pending' per actor |
| lupo_actor_pairing | None | Empty — seeded post-install |
| lupo_actor_relationships | None | Empty — seeded post-install |
| lupo_agent_definitions | Install seed | Not from Crafty |
| lupo_agent_llm_configs | Install seed | Not from Crafty |
| lupo_agent_capabilities | Install seed | Not from Crafty |
| lupo_agent_tools | Install seed | Not from Crafty |
| lupo_agent_boundaries | Install seed | Not from Crafty |
| lupo_agent_memory_config | Install seed | Not from Crafty |
| lupo_agent_performance_stats | Runtime | Not from Crafty |
| lupo_kairos_observations | None | Not from Crafty |
| lupo_kairos_memory | None | Not from Crafty |
| lupo_actor_runtime_state | Runtime | Not from Crafty |
| lupo_actor_runtime_events | Runtime | Not from Crafty |
| lupo_actor_versions | Runtime | Not from Crafty |
| lupo_agent_definition_versions | Runtime | Not from Crafty |
| lupo_faucet_rules | Install config | Not from Crafty |
| lupo_pairing_rules | Install config | Not from Crafty |
| lupo_department_capabilities | Install config | Not from Crafty |
| lupo_identity_layers | Install seed (2 rows) | Not from Crafty |
| lupo_identity_context | Runtime | Not from Crafty |
| lupo_versions | Install seed | Not from Crafty |
| lupo_edge_types | Install seed | Partial (system types only) |
| lupo_actor_faucets | Install config | Not from Crafty |
| lupo_governance_overrides | Install config | Not from Crafty |
| lupo_actor_moods | Runtime | Not from Crafty |

---

Author: claude-code (actor_id 102) | Date: 20260406
Companion files: schema_corrected_core.sql, schema_corrected_missing.sql, migration_impact_summary.md
