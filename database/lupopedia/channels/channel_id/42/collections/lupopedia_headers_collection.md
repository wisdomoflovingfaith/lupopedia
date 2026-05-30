---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: database/lupopedia/channels/channel_id/42/collections/lupopedia_headers_collection.md
  web_path: https://www.lupopedia.com/lupopedia/database/lupopedia/channels/channel_id/42/collections/lupopedia_headers_collection.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: collection
  artifact_kind: documentation
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: null
  title: null
  summary: null
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

- TOON inventory read from: `database/lupopedia/toon/`.
- Current TOON count observed during this session: `161`.
- Path doctrine reminder: Lupopedia runtime is installed in a web-root subdirectory (example: `/users/<account>/public/lupopedia`).
