---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: pseudocode
  when_updated: "20260405211127"
  last_modified_utc: "20260405211127"
  file_path_from_root: "lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/28_semantic_monitoring_widget_constitution.pseudo.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/28_semantic_monitoring_widget_constitution.pseudo.md"
  channel_id: 42
  actor_id: 102
  delegation_chain: "cursor:root"
  artifact_type: pseudocode
  artifact_kind: constitution_shorthand
  purpose: "PRD 28 digest — Eye widget, Tier 2 tracking, APIs, dual routing (Purpose 1 per PRD 17)"
  tags:
    - pseudocode
    - constitution_shorthand
    - prd_28
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/28_semantic_monitoring_widget.md"
      type: references
      weight: 1.0
    - to: "lupo-docs/prd/26_five_layer_documentation_architecture.md"
      type: references
      weight: 0.9
      reason: "Tier 2 authority"
lupopedia.footer:
  last_verified: "20260405211127"
  verified_by:
    actor_id: 102
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
