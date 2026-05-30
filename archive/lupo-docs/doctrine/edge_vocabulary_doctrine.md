---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/EDGE_VOCABULARY_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/doctrine/EDGE_VOCABULARY"
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: semantic
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: Edge Vocabulary Doctrine — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/EDGE_VOCABULARY

# Edge Vocabulary Doctrine (v4.0.69)

This document defines the **canonical vocabulary** for semantic and actor edges: allowed `edge_type`, `relationship_type`, and valid left/right object type pairs. Use it for validation, rule-based checks, and to avoid uncontrolled edge proliferation.

---

## 1. Tables in scope

- **`lupo_edges`** — Generic typed edges between any two objects (`left_object_type`, `left_object_id`, `right_object_type`, `right_object_id`, `edge_type`, `relationship_type`, etc.).
- **`lupo_actor_edges`** — Actor-to-actor edges (`source_actor_id`, `target_actor_id`, `edge_type`, `domain_id`, `weight`, `properties`).

---

## 2. lupo_edges: edge_type (canonical list)

| edge_type | Description | Typical left/right object types |
|-----------|-------------|----------------------------------|
| `inbound_edge` | References pointing to this object (e.g. file) | various → content, file |
| `semantic_relationship` | Semantic relationship between two objects | content, channel, actor |
| `HAS_CONTENT` | Channel or entity has content | channel → content |
| `references` | One artifact references another | content → content, file → file |
| `implements` | Implements or fulfills (e.g. code implements spec) | content → content |
| `belongs_to` | Belongs to channel, collection, or domain | content → channel, content → collection |
| `authored_by` | Authored by actor | content → actor |
| `version_of` | Version lineage | content → content |

*Seed data in install may use `edge_type` values such as `inbound_edge` and `semantic_relationship` (see lupo_metadata seed). New edge types SHOULD be added to this list and, if enforcement is desired, to rule-based validation or a future `lupo_edge_types` registry.*

---

## 3. lupo_edges: relationship_type

| relationship_type | Description |
|-------------------|-------------|
| `semantic` | General semantic link (default). |
| `structural` | Structural relationship (e.g. parent/child, part-of). |
| `reference` | Reference/citation. |
| `governance` | Governance or rule linkage. |

---

## 4. lupo_edges: left_object_type / right_object_type

Allowed object type labels (for validation and consistency):

| object_type | Description |
|-------------|-------------|
| `channel` | Channel (lupo_channels). |
| `content` | Content item (lupo_contents). |
| `actor` | Actor (lupo_actors). |
| `file` | File or artifact (path or identifier). |
| `collection` | Collection (lupo_collections). |
| `metadata` | Metadata row (lupo_metadata). |
| `task` | Task (lupo_tasks). |
| `thread` | Dialog thread (lupo_dialog_threads). |

Valid pairs are context-dependent; e.g. `channel` → `content` (HAS_CONTENT), `content` → `actor` (authored_by), `content` → `content` (references, version_of). Validators MAY enforce allowed pairs from this doctrine before inserting into `lupo_edges`.

---

## 5. lupo_actor_edges: edge_type

Actor–actor relationship types (examples; extend as needed):

| edge_type | Description |
|-----------|-------------|
| `paired` | Paired actor (e.g. IDE faucet session paired to human). |
| `delegates_to` | Delegation or handoff. |
| `reports_to` | Reporting / oversight. |
| `collaborates_with` | Collaboration on same channel/task. |
| `conflict` | Recorded conflict (see lupo_actor_conflicts for full records). |

---

## 6. Validation and governance

- **Rule-based validation:** Prefer validating edge_type and relationship_type (and optionally object type pairs) via **lupo_rules** and evaluator rather than introducing a new registry table immediately. This keeps the schema minimal and aligns with KIRO’s recommendation.
- **Documentation:** Any new edge_type or relationship_type added in code or seed SHOULD be documented here so the vocabulary remains the single reference.
- **Discovery:** Automated edge discovery or bulk import SHOULD use only vocabulary terms from this document (or an approved extension) to avoid semantic drift.

---

## References

- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` — `lupo_edges`, `lupo_actor_edges` schema.
- `lupo-docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md` — edges and semantic layer.
- `lupo-docs/doctrine/FEDERATION_SCOPING_DOCTRINE.md` — domain_id vs channel_id on edges.
