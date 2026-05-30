---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: pseudocode
  when_updated: "20260406150859"
  file_path_from_root: "docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/lupopedia_quickstart.pseudo.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/lupopedia_quickstart.pseudo.md"
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
# Lupopedia quickstart (for external AI agents)

**Use this page as a map.** For each topic, open the matching **`*_constitution.pseudo.md`** in **this folder** (deeper detail) or the **canonical PRD** (source of truth). If a shorthand disagrees with a PRD, **the PRD wins**.

## Bundle — Priority 1–3 (eight PRD shorthands)

| # | Shorthand file | Canonical PRD |
|---|----------------|---------------|
| 1 | [00_constitution_shorthand.pseudo.md](./00_constitution_shorthand.pseudo.md) | [PRD 00](../../../../prd/00_root_constitutional_system_requirements.md) |
| 2 | [05_auth_user_actor_agent_transformation_constitution.pseudo.md](./05_auth_user_actor_agent_transformation_constitution.pseudo.md) | [PRD 05](../../../../prd/05_auth_user_actor_agent_transformation.md) |
| 3 | [15_actors_constitution.pseudo.md](./15_actors_constitution.pseudo.md) | [PRD 15](../../../../prd/15_actors.md) |
| 4 | [16_lupopedia_headers_constitution.pseudo.md](./16_lupopedia_headers_constitution.pseudo.md) | [PRD 16](../../../../prd/16_lupopedia_headers.md) |
| 5 | [26_five_layer_documentation_architecture_constitution.pseudo.md](./26_five_layer_documentation_architecture_constitution.pseudo.md) | [PRD 26](../../../../prd/26_five_layer_documentation_architecture.md) |
| 6 | [31_implementation_folder_guidelines_constitution.pseudo.md](./31_implementation_folder_guidelines_constitution.pseudo.md) | [PRD 31](../../../../prd/31_implementation_folder_guidelines.md) |
| 7 | [28_semantic_monitoring_widget_constitution.pseudo.md](./28_semantic_monitoring_widget_constitution.pseudo.md) | [PRD 28](../../../../prd/28_semantic_monitoring_widget.md) |
| 8 | [33_softaculous_certification_4_1_0_gate_constitution.pseudo.md](./33_softaculous_certification_4_1_0_gate_constitution.pseudo.md) | [PRD 33](../../../../prd/33_softaculous_certification_4_1_0_gate.md) |

**Cross-cutting (not PRD mirrors):** [00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES_SHORTHAND.pseudo.md](../../../../decisions/pseudocode/00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES_SHORTHAND.pseudo.md) — **overrides vs training defaults** (read first). Then [00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES.pseudo.md](../../../../decisions/pseudocode/00_NON_NEGOTIABLES_IMPORTANT_OVERWRITES.pseudo.md) — expanded examples. **Digests only; PRD 00 wins**.

## What Lupopedia is (one paragraph)

Lupopedia is a **PHP** live-help / **semantic OS** continuation of **Crafty Syntax 3.7.5**: **actors** and **channels** coordinate work; **PDO_DB** and **BIGINT UTC** timestamps; **Tier 1** docs (PRDs, implementations) vs **Tier 2** runtime graph (**PRD 28** Eye / visits / paths). **Supported import path (4.0.x):** Crafty **3.7.5 →** Lupopedia **4.0.x** fresh install + seed — not Lupopedia→Lupopedia until **4.1.0** auto-installer doctrine.

## Non-negotiables (cheat sheet)

| Area | Forbidden | Required |
|------|-----------|----------|
| **Database** | FKs, triggers, `DATETIME` in schema, `AUTO_INCREMENT` on registry tables, ambiguous `id` PK | `BIGINT` UTC **`YmdHis`**, explicit PK names, soft delete, logic in PHP |
| **PHP core** | Laravel, middleware, Composer **runtime** | **7.4+** compatible paths, **`PDO_DB`**, named placeholders |
| **Installer / URLs** | Hard-fail if `.htaccess` missing | Fallback **`index.php`** + query params (**PRD 00 §2**, **§9.5**) |
| **Security** | User-built include paths, raw SQL concat | Path anchor, stream/`NUL` rejection, explicit **`INSERT` columns** |
| **Shipped UI JS** | npm/webpack **as runtime**, `eval` / string timers for animation | Vanilla JS, **`layers.js`**, **`lupo_t()`** for strings |
| **Indexing** | SEO as product assumption | **`noindex` / `robots.txt`** SHOULD (**PRD 00 §18**) |

## Identity (ultra-short)

- **Agents** — files under **`agents/`**; **`lupo_agents`** metadata.
- **Actors** — **`lupo_actors.actor_id`** runtime identity.
- **Auth users** — **`lupo_auth_users`**; login and accountability.
- **Departments** — **`lupo_auth_user_departments`** ∩ **`lupo_actor_departments`** drives **web act-as**; **many users → one actor** allowed.

## Five layers (Tier 1)

| Layer | Path |
|-------|------|
| WHAT | **`docs/prd/`** |
| HOW | **`docs/implementations/{prd_file_stem}/`** |
| WHY | **`decisions/`**, **`questions/`**, **`answers/`**, **`comments/`** |
| WHO | **`authors.md`** |
| WHERE | **`edges.md`**, **`lupopedia.edges`** |

## When in doubt

1. Open the **numbered `*_constitution.pseudo.md`** above.
2. Then open the **PRD** it references.
3. Never propose **FKs / triggers / ALTER upgrade chains (4.0.x)** / **hard deletes** on lineage tables.
4. Ask: **Is there a degraded / Survivability fallback?**

**Reviewer:** **LILITH** (`actor_id` **2**) may reject violations.

## Optional next reads (Priority 4 — service agents)

- [PRD 36](../../../../prd/36_rose_multi_persona_synthetic_dialog.md) — ROSE synthetic multi-persona batches.
- [PRD 37](../../../../prd/37_kairos_channel_memory_consolidation.md) — KAIROS memory consolidation (no shorthand file in this folder yet unless added later).
