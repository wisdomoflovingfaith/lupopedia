---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "documentation"
  system_version: "4.0.69"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/42/collections/lupopedia_headers_collection.md"
  questions_toon: null
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "collection"
  artifact_kind: "documentation"
  purpose: "Collection for LUPOPEDIA HEADERS storage, indexing, edges, and verification workflows backed by TOON schema."
---

# Collection: Lupopedia Headers

## Description

This collection tracks the file-header identity layer used by Lupopedia and maps it to semantic indexing and edge relationships.

## TOON-backed table scope

Primary tables:
- `lupo_metadata.toon`
- `lupo_edges.toon`
- `lupo_semantic_index.toon`
- `lupo_contents.toon`
- `lupo_collections.toon`
- `lupo_collection_tabs.toon`
- `lupo_collection_tab_map.toon`
- `lupo_collection_tab_paths.toon`

Supporting tables:
- `lupo_actor_edges.toon`
- `lupo_contexts.toon`
- `lupo_contexts_map.toon`
- `lupo_truth_knowledge.toon`
- `lupo_truth_answers.toon`
- `lupo_interpretation_log.toon`

## Semantic edge notes

- Header documents are indexed as content artifacts and metadata entities.
- Header relationships are represented through `lupo_edges` using typed links (`references`, `documents`, `maps_to`, `governs`) with channel and domain scope.
- This collection is intended to support channel 42 architecture and governance workflows for agent collaboration.

## Operational notes

- TOON inventory read from: `lupo-database/lupopedia/toon/`.
- Current TOON count observed during this session: `161`.
- Path doctrine reminder: Lupopedia runtime is installed in a web-root subdirectory (example: `/users/<account>/public/lupopedia`).
