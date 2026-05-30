---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: pseudocode
  when_updated: "20260405211127"
  file_path_from_root: "docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/28_semantic_monitoring_widget_constitution.pseudo.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/28_semantic_monitoring_widget_constitution.pseudo.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: pseudocode
  artifact_kind: constitution_shorthand
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# PRD 28 shorthand — Semantic monitoring widget (“The Eye”)

**Canonical:** [PRD 28](../../../../prd/28_semantic_monitoring_widget.md)

## Two layers

| Layer | Meaning |
|-------|---------|
| **Core monitoring** | Tracking, semantic navbar data, **`lupo_paths`** / **`lupo_visits`** / edges — **always-on product intent** when enabled. |
| **Visual effect** | Floating eyes / **`dynlayer.js`** — **optional**; user trait / config gated. |

## What it collects (API surface — examples)

Endpoints under **`/api/page/...`** feed UI chips: previous/next pages, referencing pages, contexts, tags, shares, inbound links, namespaces, folders, comments, truth Q/A, edges, chat status — see PRD 28 table. **All** use **`DatabaseFactory::getConnection()`**, **`LUPO_TABLE_PREFIX`**, **`is_deleted = 0`**, **`IdGenerator`**, **`gmdate('YmdHis')`**.

## External embed / federation

- Third-party sites using the widget need **trust** via **`lupo_federation_nodes`** + **`lupo_federated_trust`** (and related discovery rows) — see **PRD 21** / admin semantic-widget flows; **no** silent cross-origin trust.

## Content identity

- Prefer explicit **`slug` / content keys** from **`lupo_contents`** — **do not** assume raw foreign URL paths map automatically to internal content rows.

## API routing (constitutional)

- **Clean URLs** when `mod_rewrite` works; **query-parameter (and/or `PATH_INFO`) fallback mandatory** — **PRD 00 §2**, **§9.5**. Handlers **must not** require pretty paths.

## IP for visits

- Use **`get_ipaddress()`** (forwarded headers first) — **not** raw **`REMOTE_ADDR`** only (**RULE 93.IP_DETECTION**).
