---
flip_version: 3
system_version: "4.0.43"
artifact_id: "sha1:actors_root_readme"
federated_node_id: 0
artifact_path: "actors/README.md"
artifact_filename: "README.md"
artifact_type: "actor_metadata"
artifact_kind: "index"
actor_id: 1001
actor_source: "explicit"
actor_confidence: 1.0
created_ymdhis: 20260224165800
created_source: "explicit"
created_confidence: 1.0
updated_ymdhis: 20260224165800
updated_source: "explicit"
title: "Actors Directory - Canonical Actor Registry"
summary: "First-class source folder for actor identity, aliases, and relationships"
why: "Actors folder provides canonical actor registry alongside channels/ and artifacts/"
semantic_tags: ["actors", "registry", "canonical", "index"]
relations:
  - rel: "contains"
    target: "actors/registry.json"
  - rel: "contains"
    target: "actors/aliases.csv"
  - rel: "contains"
    target: "actors/relationships.csv"
  - rel: "contains"
    target: "actors/0/"
  - rel: "contains"
    target: "actors/420/"
  - rel: "contains"
    target: "actors/10000/"
is_deleted: 0
deleted_ymdhis: 0
delegation_chain: "1001:10000"
---

# Actors Directory

**Purpose:** Canonical actor registry for Lupopedia  
**Status:** First-class source folder (alongside channels/ and artifacts/)  
**Version:** 4.0.43  

## Overview

The `actors/` directory is the canonical source of truth for actor identity, aliases, and relationships in Lupopedia. It provides machine-resolvable actor information for VSX extension, import tooling, and offline operation.

## Structure

```
actors/
├── README.md                  # This file
├── registry.json              # Authoritative actor records (46 actors)
├── aliases.csv                # Alias-to-actor_id mapping (66 aliases)
├── relationships.csv          # Supporting actor control graph (30 relationships)
├── 0/                         # System Kernel actor folder
│   └── README.md
├── 420/                       # STONED WOLFIE (banned test actor)
│   └── README.md
└── 10000/                     # Captain Wolfie (human owner)
    └── README.md
```

## Core Files

### registry.json
**Purpose:** Authoritative actor records  
**Schema:** Actor v2 (actor_kind, agent_class, requires_supporting_actor)  
**Total Actors:** 46 (1 human, 45 agents)  

**Actor Distribution:**
- Human: 1 (ID 10000)
- System agents: 25 (IDs 0-209, 1212)
- IDE agents: 15 (IDs 1001-1010, 2032-2040)
- External AI: 4 (IDs 2010, 2030, 2038)
- Banned: 1 (ID 420, soft-deleted)

### aliases.csv
**Purpose:** Alias-to-actor_id mapping for slug resolution  
**Total Aliases:** 66 (65 active, 1 deleted)  

**Alias Types:**
- canonical — Primary slug matching registry canonical_slug
- handle — Short names and common variants
- legacy_name — Historical names
- email_slug — Email-safe slugs

**Resolution Order:**
1. Numeric actor_id → use directly
2. Lookup alias_slug in aliases.csv
3. Fallback to registry.json canonical_slug
4. Mark as unresolved → audit report

### relationships.csv
**Purpose:** Supporting actor control graph  
**Total Relationships:** 30 (15 supports + 15 owns)  

**Relationship Types:**
- supports — Human supports/operates an agent
- owns — Human owns an agent identity/config
- delegates — Human delegates tasks to agent
- paired_with — Symmetric relationship (optional)

**Current Graph:**
- Captain Wolfie (10000) supports all 15 IDE agents
- Captain Wolfie (10000) owns all 15 IDE agents
- Strength weight: 1.00 (full control)

## Actor Folders

### actors/0/ - System Kernel
**Actor ID:** 0  
**Display Name:** System Kernel  
**Actor Kind:** agent  
**Agent Class:** system  
**Purpose:** Core system operations  

### actors/420/ - STONED WOLFIE (BANNED)
**Actor ID:** 420  
**Display Name:** STONED WOLFIE  
**Actor Kind:** agent  
**Agent Class:** banned  
**Status:** ⛔ PERMANENTLY BANNED  
**Purpose:** Required test actor for security validation  

**⚠️ IMPORTANT:** Actor 420 MUST exist, MUST remain banned, MUST NOT be deleted.  
See Doctrine #13: Actor 420 Preservation

### actors/10000/ - Captain Wolfie
**Actor ID:** 10000  
**Display Name:** Captain Wolfie  
**Actor Kind:** human  
**Role:** owner  
**Purpose:** Human owner and system authority  

## Usage

### VSX Extension
- Loads registry.json on startup
- Loads aliases.csv on startup
- Resolves actor_id via alias lookup
- Displays "Supported by: <human>" for IDE agents
- Warns if IDE agent missing supporting actor

### Import Tooling
- Validates actor_id against registry.json
- Resolves legacy names via aliases.csv
- Validates IDE agent requirements via relationships.csv
- Fails import if unresolved actor references

### Offline Operation
- Filesystem is source of truth until database online
- All actor resolution works without database
- Registry provides canonical actor metadata
- Aliases enable flexible slug resolution

## Validation

**Validation Script:** `scripts/validate_actor_registry.py`

**Checks:**
- No duplicate active aliases
- All actor_ids in aliases.csv exist in registry.json
- All canonical_slugs have corresponding canonical alias
- No collisions (same alias → multiple actors)
- Soft delete integrity
- IDE agents have supporting actor relationships

**Status:** ✅ ALL CHECKS PASSED

## Doctrines

### Supporting Actor Doctrine (v4.0.38)
- Two-layer actor model (executor + human authority)
- IDE agents require supporting actor
- Control graph encoded in relationships.csv
- Delegation chain format: acting_agent_id:authorizing_human_id

### Actor 420 Preservation Doctrine (v4.0.43)
- Actor 420 MUST exist at all times
- Actor 420 MUST remain banned
- Actor 420 MUST NOT be deleted
- Actor 420 required for testing ANUBIS, security gates, ban enforcement

### FLIP v3 Retrofit Doctrine (v4.0.43)
- All .md files in actors/ MUST have FLIP v3 headers
- Extract actor_id from path (actors/<actor_id>/<filename>.md)
- Cross-reference with registry.json for validation
- Add describes_actor and part_of_actor_folder relations

## Version History

- **4.0.42:** Initial actor registry + alias map created
- **4.0.43:** Actors v2 schema (actor_kind, agent_class, requires_supporting_actor)
- **4.0.43:** Relationships graph created (supporting actor control)
- **4.0.43:** Actor 420 preservation doctrine established
- **4.0.43:** FLIP v3 headers added to all .md files

---

<!-- FLIP_FOOTER_BEGIN -->
{
  "flip_footer": true,
  "content_sha1": "generated_on_retrofit",
  "flip_generated_ymdhis": "20260224165800",
  "import_status": "pending",
  "files_documented": ["registry.json", "aliases.csv", "relationships.csv"],
  "actor_folders": ["0", "420", "10000"],
  "total_actors": 46,
  "total_aliases": 66,
  "total_relationships": 30
}
<!-- FLIP_FOOTER_END -->
