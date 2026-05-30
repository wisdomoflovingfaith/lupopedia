# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "database/lupopedia/actors/actor_id/0/README.md"
  file_hash: "35d0da17af2157f08233e20a95be16453b58add537c0e9ae15930a2907f34ed5"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "actors\0\README.md"
  file_hash: "a0cdf8af340378ad92bcb2ee389549809a64bc88eab3c09bfee1e267915e82ea"
  file_path_from_root: "actors\0\README.md"
  file_hash: "ee82a16b37b54a876c92f3fd387822deafeed364d481a1f116f32941088e105f"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for README.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["actors", "0", "readmemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
flip_version: 3
system_version: "4.0.43"
artifact_id: "sha1:actors_0_readme"
federated_node_id: 0
artifact_path: "actors/0/README.md"
artifact_filename: "README.md"
artifact_type: "actor_metadata"
artifact_kind: "actor_readme"
actor_id: 0
actor_source: "explicit"
actor_confidence: 1.0
created_ymdhis: 20260224165800
created_source: "explicit"
created_confidence: 1.0
updated_ymdhis: 20260224165800
updated_source: "explicit"
title: "System Kernel Actor (0)"
summary: "System kernel actor folder - core system operations"
why: "Actor 0 represents the system kernel for core operations"
semantic_tags: ["actor", "system", "kernel", "core"]
relations:
  - rel: "describes_actor"
    target_actor_id: 0
  - rel: "part_of_actor_folder"
    target: "actors/0/"
is_deleted: 0
deleted_ymdhis: 0
delegation_chain: "1001:10000"
---

# Actor 0: System Kernel

**Actor ID:** 0  
**Display Name:** System Kernel  
**Actor Kind:** agent  
**Agent Class:** system  
**Canonical Slug:** kernel  

## Purpose

Actor 0 represents the system kernel for core Lupopedia operations. This is a reserved system actor that handles kernel-level operations and system initialization.

## Status

- **Active:** Yes
- **Requires Supporting Actor:** No (system agent)
- **Created:** 2026-01-01 00:00:00 UTC

## Registry Entry

See `actors/registry.json` for complete actor metadata.

## Aliases

See `actors/aliases.csv` for all actor aliases.

---

<!-- FLIP_FOOTER_BEGIN -->
{
  "flip_footer": true,
  "content_sha1": "generated_on_retrofit",
  "flip_generated_ymdhis": "20260224165800",
  "import_status": "pending"
}
<!-- FLIP_FOOTER_END -->
