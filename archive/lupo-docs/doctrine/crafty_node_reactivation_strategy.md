---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/CRAFTY_NODE_REACTIVATION_STRATEGY.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/CRAFTY_NODE_REACTIVATION_STRATEGY.md"
  status: "active"
  when_updated: "20260403125325"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: strategy
  channel_key: null
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: CRAFTY_NODE_REACTIVATION_STRATEGY — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/CRAFTY_NODE_REACTIVATION_STRATEGY.md

# Crafty node reactivation strategy (4.0.x planning)

## Principle

**Reactivation** means a **site operator** chooses to **migrate** a dormant or active **Crafty Syntax 3.7.5** deployment to **Lupopedia 4.0.x**, preserving **their** historical analytics where the import path allows — then **optionally** participating in **federation** (see **[PRD 34](../prd/34_federation_node_semantic_network.md)**). There is **no** automatic “wake up” of remote databases from this repository; coordination is **consent-based** and **operator-driven**.

## Dependency order (completion criteria)

1. **Stable single-install Lupopedia** — Crafty → Lupopedia install and import documented and reliable (**4.0.x** focus).
2. **Clear operator docs** — what is imported, what is not, backup expectations.
3. **Federation PRD approved** — **[PRD 34](../prd/34_federation_node_semantic_network.md)** before building cross-node features.
4. **Opt-in federation** — explicit configuration; no silent exfiltration of local behavioral data.

## Dormant install (conceptual)

A **dormant** Crafty site may still hold **local** SQL with path/visit aggregates. **Lupopedia does not remotely read that data.** Reactivation path:

1. Operator obtains current Crafty DB backup or filesystem + DB.
2. Operator runs supported **Crafty 3.7.5 → Lupopedia** flow per installer/import docs.
3. Historical **`lupo_visits`**, **`lupo_paths`**, etc. populate per mapping — see **[SILENT_HARVEST_DOCTRINE.md](SILENT_HARVEST_DOCTRINE.md)**.
4. **Federation** features, when shipped, require **explicit** enablement and trust rules.

## What not to do

- Do **not** promise central access to “856,000 databases” — that is not how the architecture works.
- Do **not** market reactivation as surveillance; market it as **upgrade + ownership of data**.
- Do **not** implement bulk remote callbacks from this repo without a separate security and legal review.

This output complies with Lupopedia Constitutional Root Rules.
