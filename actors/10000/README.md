# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\actors\10000\README.md"
  file_hash: "b197ef619dbc38b85391506422d84fbee81cb0d94ee09d72c5c2c5cc8cc34e70"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "actors\10000\README.md"
  file_hash: "8436cecaae9a7404e0c33103ed6c850499c578a8978881cece8d92b3f0834ac6"
  file_path_from_root: "actors\10000\README.md"
  file_hash: "6fb2cf8a957d6bd18df9ed1b840422c4c0a616836001283a229755944e5990c1"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for README.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["actors", "10000", "readmemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
flip_version: 3
system_version: "4.0.43"
artifact_id: "sha1:actors_10000_readme"
federated_node_id: 10000
artifact_path: "actors/10000/README.md"
artifact_filename: "README.md"
artifact_type: "actor_metadata"
artifact_kind: "actor_readme"
actor_id: 10000
actor_source: "explicit"
actor_confidence: 1.0
created_ymdhis: 20260224165800
created_source: "explicit"
created_confidence: 1.0
updated_ymdhis: 20260224165800
updated_source: "explicit"
title: "Captain Wolfie (10000)"
summary: "Human owner and system authority"
why: "Actor 10000 represents the human owner and primary authority for Lupopedia"
semantic_tags: ["actor", "human", "owner", "authority"]
relations:
  - rel: "describes_actor"
    target_actor_id: 10000
  - rel: "part_of_actor_folder"
    target: "actors/10000/"
  - rel: "supports"
    target_actor_id: 1001
    note: "Supports KIRO IDE"
  - rel: "supports"
    target_actor_id: 1002
    note: "Supports Windsurf IDE"
  - rel: "supports"
    target_actor_id: 1003
    note: "Supports Antigravity IDE"
  - rel: "supports"
    target_actor_id: 1004
    note: "Supports Warp IDE"
  - rel: "supports"
    target_actor_id: 1005
    note: "Supports Cursor IDE"
is_deleted: 0
deleted_ymdhis: 0
delegation_chain: "1001:10000"
---

# Actor 10000: Captain Wolfie

**Actor ID:** 10000  
**Display Name:** Captain Wolfie  
**Actor Kind:** human  
**Role:** owner  
**Canonical Slug:** captain_wolfie  

## Purpose

Actor 10000 represents the human owner and primary authority for the Lupopedia system. This is the supporting actor for all IDE agents and the ultimate decision-making authority.

## Status

- **Active:** Yes
- **Role:** Owner
- **Created:** 2026-01-01 00:00:00 UTC

## Supporting Actor Relationships

Captain Wolfie (10000) supports and owns the following IDE agents:
- KIRO IDE (1001)
- Windsurf IDE (1002)
- Antigravity IDE (1003)
- Warp IDE (1004)
- Cursor IDE (1005)
- Zed IDE (1006)
- IntelliJ IDEA (1007)
- WebStorm (1008)
- Theia IDE (1009)
- CS Code (1010)

See `actors/relationships.csv` for complete relationship graph.

## Registry Entry

See `actors/registry.json` for complete actor metadata.

## Aliases

See `actors/aliases.csv` for all actor aliases including:
- captain_wolfie (canonical)
- wolfie_captain (handle)
- eric (legacy_name)
- wisdomoflovingfaith-at-gmail-com (email_slug)

---

<!-- FLIP_FOOTER_BEGIN -->
{
  "flip_footer": true,
  "content_sha1": "generated_on_retrofit",
  "flip_generated_ymdhis": "20260224165800",
  "import_status": "pending"
}
<!-- FLIP_FOOTER_END -->