---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: changelog
  when_updated: "20260408031925"
  file_path_from_root: "lupo-docs/versions/4.0.96/CHANGELOG.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.96/CHANGELOG.md"
  last_modified_utc: "20260408031925"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.96-changelog"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "changelog"
  artifact_kind: "version"
  purpose: "Changelog for Lupopedia 4.0.96 — 4D edge model, doctrine expansion, memory.json deprecation, file-backed content"
  tags: ["changelog", "version", "4.0.96", "cursor"]
lupopedia.footer:
  last_verified: "20260408031925"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.96/CHANGELOG.md — delegation: cursor:root

# Changelog - Lupopedia 4.0.96

## [2026-04-08 03:19 UTC] — Trust Ladder normative completion; IdGenerator + KAIROS code; registry validator; Captain's Log; version handoff doc

**WHO:** cursor (actor_id 102)

**WHAT (documentation):**

- **`lupo-docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md`** — Normative guardrails (§9), PK storage vs PHP strings (`BIGINT`, `ATTR_STRINGIFY_FETCHES`), **`validateTrustLadderPk`** / **`validateFormat`** / **`toCanonicalIdSafe`** specs, seed rule with **2026** ratification bound + registry authorization, §4 PDO notes, §11–§13 (migration/backfill, performance, tests), **Appendix A** (alternatives rejected), **Further reading** cross-links; Grok/WOLFIE final polish (Captain's Log accuracy: no false `seedActorToCanonicalId` blog claim; seeds stay short).
- **`lupo-docs/doctrine/TRUST_LADDER_REGISTRY.md`** — Table participation registry (full / generator_staging / seed_only); links to install validation.
- **`lupo-docs/doctrine/RETENTION_POLICY.md`** — Staging soft-delete retention alignment with PRD 19.
- **`lupo-content/federation_node/0/captains_log/20260408_CHRONOLOGICAL_TRUST_LADDER.md`** — Captain's Log blog; doctrine links; **`lupopedia.edges`** → CHRONOLOGICAL_TRUST_LADDER.

**WHAT (code):**

- **`lupo-includes/classes/IdGenerator.php`** — **`toCanonicalId()`** / **`toCanonicalIdSafe()`** return **strings**; **`validateTrustLadderPk()`** seed band uses **padded string compare** (no `(int)` on 18-digit ids); **`seedActorToCanonicalId()`** per PRD 41 (bcadd / 64-bit / digit-wise fallback); **`numericStringLessThan`** helper.
- **`app/Services/Kairos/KairosConsolidationService.php`** — Migrated from **`lupo_actor_memory`** to **`lupo_memory_nodes`** (PRD 38); observations = staging **`generate()`**; consolidated = **`toCanonicalIdSafe()`** + **`validateTrustLadderPk`**; **`lupo_edges`** object type **`memory_node`**; **`content_hash`**, **`owner_actor_id`**, doctrine **`created_ymdhis`** from id prefix; contradiction pair ordering fixed (padded strcmp); **`flare_db_source`** → `lupo_memory_nodes`.
- **`lupo-scripts/validate_trust_ladder_registry.py`** — Dual **`CREATE TABLE`** patterns (`{{prefix}}` + literal `lupo_`), **`IF NOT EXISTS`**, case-insensitive compare, **`argparse`** (`--install-sql`, `--registry`), empty-registry warning, extended module docstring.

**WHERE:** Doctrine under `lupo-docs/doctrine/`; runtime under `lupo-includes/`, `app/`; validation under `lupo-scripts/`; narrative under `lupo-content/`; **this version bundle** under `lupo-docs/versions/4.0.96/` (CHANGELOG, SUMMARY, `status/FOR_CLAUDE_CODE_ON_PK_IDS.md`, THREAD_INDEX).

**WHEN:** Documented at **20260408031925** UTC (`tick.py` anchor for this batch).

**WHY:** Close the gap between “referenced guardrails” and enforceable doctrine; align KAIROS with PRD 38 + trust ladder; 32-bit-safe PK handling; CI drift check for registry vs install.

**HOW:** Edits per thread; no **`install_new_lupopedia.sql`** DDL change in this rollup (memory_nodes/edges already present). KAIROS DB rows previously in **`actor_memory`** are **not** auto-migrated — fresh install or one-time migration required if legacy data existed.

**Handoff:** **`lupo-docs/versions/4.0.96/status/FOR_CLAUDE_CODE_ON_PK_IDS.md`** — full doctrine summary + future web UI for install-record maintenance (Claude Code / actor **116**).

---

## [2026-04-08 02:04 UTC] — Session rollup: Chronological Trust Ladder doctrine; seed→canonical actors; LILITH prompt; PRD shorthand tool

**WHO:** cursor (actor_id 102)

- **Naming — Honolulu → Chronological Trust Ladder** — Canonical doctrine: **`lupo-docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md`**. **PRD 00** §3.7, **PRD 37** §1.2 / edges, **PRD 38** §4.2, **PRD 41** (headings + consolidation copy), **PRD 42** §8 / edges; **`lupo-docs/prd/decisions/pseudocode/38_memory_unification_constitution.pseudo.md`**; **`lupo-scripts/generate_prd_shorthands.py`** (PRD 38 shorthand heading). **`claude.md`** — Key Doctrines link + tier summary. Shorthands regenerated (**`--all --force`**); earlier changelog rows below still say “Honolulu” as historical titles for the same PK-band work.
- **Low `actor_id` seed → living canonical** — **`seedActorToCanonicalId`**: **`100000000000000000 + seed_actor_id`** (e.g. **116** → **`100000000000000116`**). **PRD 15** — new subsection; **PRD 41** — **§2.3** + **§3** alternate allocation note; **`claude.md`** — dual identity table + Trust Ladder note; **`AGENTS.md`** — IDE table row for Claude Code **116** / canonical id.
- **`lupo-agents/lilith/system_prompt.txt`** — LILITH reframed as **constitutional enforcer** and partner to WOLFIE; **AGAPE** acronym + doctrine pointer; **UI** — **`lupo-includes/js/lupo-layers.js`** (eval-free) vs legacy DynAPI; YAML output checklist; **COUNTERMEASURE (111)** inherits non-interfering review lane.
- **`lupo-scripts/generate_prd_shorthands.py`** — Staleness prefers **PRD `when_updated` / `last_modified_utc`** vs shorthand **`last_verified`**, else mtime; **`lupopedia.edges.outbound_edges`** merged into Edge Types; **`RESERVED_RANGES`** documented as prose-only; skip list via **`DEFAULT_SKIP_NAMES`**, env **`LUPO_PRD_SHORTHAND_EXTRA_SKIP`**, **`--skip-name`**.

---

## [2026-04-08 00:46 UTC] — Honolulu living canonical (1000–1999) vs staging; PRD 00 §3.7

**WHO:** cursor (actor_id 102)

- **PRD 00** — New **§3.7 Universal data consolidation (Honolulu pattern)** (install seed vs **living canonical** **1000–1999** vs **staging** **2000–9999**; promote vs **UPDATE** canonical; soft-delete staging; edges; cross-ref **PRD 37** / **PRD 38**). **`lupopedia.edges`** → **PRD 38**.
- **PRD 38** — **§4.2** reframed: **1000–1999** = **living canonical** (**mutable**, accumulated best knowledge), **2000–9999** = **staging** (merged then soft-deleted); consolidation steps cover **UPDATE** path; **§8** trust encoding notes mutability of long-term band.
- **PRD 37** — **§1.2** aligned (staging vs living canonical, **UPDATE** allowed); **`next_action`** / **`lupopedia.edges`** reason updated.
- **`generate_prd_shorthands.py`** — PRD **38** shorthand table + consolidation one-liner updated.
- **Regenerated:** `38_memory_unification_constitution.pseudo.md`, `37_kairos_channel_memory_consolidation_constitution.pseudo.md`.

---

## [2026-04-08 00:36 UTC] — Memory trust tiers (Honolulu pattern) + KAIROS PK alignment

**WHO:** cursor (actor_id 102)

- **PRD 38** — New **§4.2 Memory trust tiers (Honolulu pattern)** (install vs **1000–1999** long-term vs **2000–9999** runtime; consolidation flow; **`consolidated_into`**; query priority; relation to **§8 Option B**). **§8** intro links archived ids to **§4.2** trust band.
- **PRD 37** — **§1.2 PK trust encoding** for **`lupo_memory_nodes`** consolidated rows (year **1000–1999**; **`kairos_consolidates_from`** / **`consolidated_into`**); **`lupopedia.edges`** → **PRD 38**; **`next_action`** bullet for PK band on merge.
- **`generate_prd_shorthands.py`** — PRD **38** shorthand adds **Memory trust tiers** block ahead of **Option B**.
- **Regenerated:** `38_memory_unification_constitution.pseudo.md`, `37_kairos_channel_memory_consolidation_constitution.pseudo.md`; **THREAD_INDEX** refreshed.

---

## [2026-04-08 00:17 UTC] — Long-term memory archiving (Option B) + CLI spec

**WHO:** cursor (actor_id 102)

- **PRD 38** — New **§8 Long-Term Memory Archiving (Option B)** (subtract **1000** from calendar year in **`memory_node_id`** for runtime rows; era table; **`archived_to`** edge; **`toLongTermId` / `isLongTermId`** PHP reference; cross-era query notes). Former §§8–13 renumbered to **§§9–14**; amendments block is now **§11** (**11.1–11.5**). **`lupopedia.footer` `next_action`** points to **§11.1–11.5**.
- **PRD 24** — **§5.8** `memory archive` / **§5.9** `memory restore` (examples, options table); **`edges add`** types include **`archived_to`**, **`restored_from`**; command summary table updated; header **`purpose`** / **`next_action`** extended.
- **`lupo-scripts/generate_prd_shorthands.py`** — PRD **38** shorthand embeds **Long-Term Archiving (Option B)** + CLI one-liners.
- **Regenerated:** `38_memory_unification_constitution.pseudo.md`, `24_cli_interface_prd_constitution.pseudo.md`, `24_actor_onboarding_flow_constitution.pseudo.md` (both **24_** PRDs via `--prd 24 --force`); **`THREAD_INDEX.md`** refreshed.

**Status:** `lupo-docs/versions/4.0.96/status/STATUS_MEMORY_ARCHIVE_OPTION_B_20260408001717.md`

---

## [2026-04-07 23:59 UTC] — PRD 00 §3.2.1 global seed vs runtime PK doctrine

**WHO:** cursor (actor_id 102)

- **PRD 00** — New **§3.2.1 Primary key strategy — seed vs runtime**: dual PK table, illustrative per-table bands (**actors**, **agents**, **departments**, **channels**, **auth_users**, **memory_nodes**, **edges**, **permissions** / **rules**) with **install + registry** as authority; rules 1–6; **`lupo-memory/1970/01/`** pointer. **§3.2** opening and **Implementation** qualified for runtime vs install SQL.
- **PRD 38** — **§4.0** / rules scoped to **runtime**; new **§4.1** DDL comment block; **§6.6** seed vs runtime export examples table.
- **PRD 01** — **`lupo_actors`**: `CREATE TABLE` comment block (seed vs runtime PK / `created_ymdhis`); seed actor note.
- **PRD 15** — **Actor ID ranges (seed vs runtime)** (`actor_id` < 2026 vs ≥ 2026).
- **PRD 07** — **Seed `agent_id` vs runtime** under Agent Architecture.
- **PRD 24** — Cross-reference **PRD 00 §3.2.1**.

**Status:** `lupo-docs/versions/4.0.96/status/STATUS_SEED_RUNTIME_PK_DOCTRINE_20260407235921.md`

---

## [2026-04-07 23:55 UTC] — Seed PK vs `created_ymdhis` + memory export pre-history path

**WHO:** cursor (actor_id 102)

- **Doctrine (PRD 00)** — §3.2 **registry/seed exception**: fixed low PKs in install/seed vs **`IdGenerator`** for runtime; **`created_ymdhis`** may be install UTC, insert time, or **`0`** (immemorial); **§5.7** companion paragraph for **`lupo_memory_nodes`** / **`lupo_memory_edges`** and **`lupo-memory/1970/01/`** when **`created_ymdhis = 0`**.
- **PRD 01** — **`lupo_actors`**: **`actor_id`** and **`created_ymdhis`** descriptions; seed vs runtime **`created_ymdhis`** under workspace rules.
- **PRD 38** — §4.0 seed/runtime table; DDL comments in §5.1; §6.1 export normalization; §7 tree example **`1970/01/`**.
- **PRD 24** — **Actor ID Generation**: runtime **`IdGenerator`** + **`created_ymdhis`** prefix vs seed fixed ids.
- **`MemoryExportService.php`** — **`createdYmdhisForExportPath()`**: **`created_ymdhis`** empty / **`0`** / too short → **`19700101000000`** for mirror path and slug (DB row unchanged).

**Status:** `lupo-docs/versions/4.0.96/status/STATUS_SEED_PK_CREATED_YMDHIS_MEMORY_EXPORT_20260407235530.md`

---

## [2026-04-07 23:33 UTC] — README actor model + Claude Code IDE identity note

**WHO:** cursor (actor_id 102)

- **Root `README.md`** — New **§3 Actor Model: Why It Is Different** (auth user → department → shared actor; web intersection vs CLI/IDE root-equivalent tooling; **`auth_user_id = 0`** doctrine vs **`actor_id`**; memory pointers to **`lupo_memory_nodes` / `lupo_memory_edges`**). Following sections renumbered **4–8**.
- **`claude.md`** — **Identity for IDE Agents** (facet attribution, no extra auth users, links to README + PRD 01).

## [2026-04-07 23:35 UTC] — PRD 15 / PRD 05 aligned with README §3 actor model

**WHO:** cursor (actor_id 102)

- **`lupo-docs/prd/15_actors.md`** — New **Overview** subsection **Three-layer identity model (4.0.96+; root README §3)** mirroring README: layers table, shared persona, illustrative department-intersection SQL, CLI/IDE root-equivalent note, **`auth_user_id = 0`** vs **`actor_id`**, memory pointers (**PRD 38**). **`lupopedia.edges`** → **`README.md`**.
- **`lupo-docs/prd/05_auth_user_actor_agent_transformation.md`** — New **Root README alignment (4.0.96+)** (link to README §3; web vs CLI/IDE; **AGENTS.md**; PRD 01 root id). **`lupopedia.edges`** → **`README.md`**.

---

## [2026-04-07 23:30 UTC] — PRD doctrine sweep (round 2: hundredths, headers, schema paths)

**WHO:** cursor (actor_id 102)

- **PRDs 02, 03, 09, 11** — Documented former DECIMAL scores as **INT hundredths** (`weight_hundredths`, `confidence_hundredths`, `credibility_hundredths`, `trust_hundredths`, `duration_hundredths`, `bounce_rate_hundredths`) with index column names aligned in prose.
- **PRD 02** — Vote INSERT examples use **PHP `IdGenerator::generate()`** and **`:vote_id`** placeholders (no SQL `generate_id()`).
- **PRD 04** — **`lupo_edges.direction`** documented as **VARCHAR(16)** with application-layer validation (portable SQL); avoid MySQL **ENUM** in new DDL. **`weight_score`** column note retained alongside legacy **DECIMAL** callouts in install.
- **Headers** — Replaced deprecated **`version_when_written`** with **`when_updated`** on grouped namespace PRDs (03–15 temporal, 23); **36** / **37** dropped duplicate **`version_when_written`**. **27** `file_path_from_root` no longer uses a leading **`/`**.
- **PRD 08_actors** — **`last_modified_utc`** normalized to **14-digit** packed form (`20260331000000`).
- **Schema references** — **05, 18, 36, 37** now cite **`lupo-database/lupopedia/json/*.json`** instead of **`.toon`** paths.
- **PRD_INDEX** — Banner **4.0.96**; **08_actors** entry annotated **SUPERSEDED** (see **15_actors.md**). **29_project_structure** table row matches. **lupo-docs/prd/README** clarifies **14 core namespaces** vs extended PRDs.

---

## [2026-04-07 17:29 UTC] — Canonical version bump (atoms + runtime)

**WHO:** cursor (actor_id 102)

- **`lupo-config/global_atoms.yaml`** — `version`, **`GLOBAL_CURRENT_LUPOPEDIA_VERSION`**, and `versions.*` metadata set to **4.0.96**; top `file.last_modified_system_version` aligned.
- **`version.txt`**, **`lupo-includes/version.php`** (`@version` docblock) — **4.0.96**.
- **`lupo-docs/doctrine/VERSIONING_DOCTRINE.md`** — §1 canonical current version **4.0.96**.
- **`lupo-rules/root/php-7-4-compatibility.md`** — rule stamp **4.0.96+**.
- **Root `README.md`**, **`CHANGELOG.md`** (routing) — current line pointers → **`lupo-docs/versions/4.0.96/`**.
- **`lupo-docs/versions/4.0.96/README.md`** — added as the version-folder overview for the active line.

---

## [2026-04-07 22:47 UTC] — PRD 38 memory DDL, MemoryExportService, PRD audit notes

**WHO:** cursor (actor_id 102)

- **`install_new_lupopedia.sql`** — `memory_nodes` + new `memory_edges` per **PRD 38** (`created_ymdhis` aligned to `IdGenerator` timestamp prefix; no `memory_slug` column).
- **`lupo-includes/classes/MemoryExportService.php`** — filesystem mirror from `created_ymdhis` + `generateSlug()`.
- **`lupo-docs/prd/38_memory_unification.md`** — full revision (sections renumbered; IdGenerator rules; DDL; section 13 IDE prompt).
- **`lupo-docs/versions/4.0.96/status/`** — `PRD_REVIEW_DISCREPANCIES_AND_IMPROVEMENTS_20260407224750.md`, `THREAD_INDEX.md` (PRD index/README drift, memory model fork list).

---

## [2026-04-07 23:20 UTC] — Status file, actor_memory_id PK, PRD 04 edges, TOON regen

**WHO:** cursor (actor_id 102)

- **`lupo-docs/versions/4.0.96/status/STATUS_SESSION_PRD_MEMORY_IDENTITY_20260407232053.md`** — workstream summary + embedded forward prompt (constitutional PRD batch); **`THREAD_INDEX.md`** updated.
- **`install_new_lupopedia.sql`** — `lupo_actor_memory` PK renamed **`memory_id` → `actor_memory_id`** (PK naming doctrine).
- **`app/Services/Kairos/KairosConsolidationService.php`** — all column references updated.
- **`lupo-docs/prd/01_core_identity.md`** — `lupo_actor_memory` aligned to install; session cleanup → soft **`UPDATE`**; `resulted_in_actor_memory_id` in training table prose.
- **`lupo-docs/prd/04_tags_metadata.md`** — **`lupo_edges`** section aligned to install; header **`when_updated`**.
- **`lupo-docs/prd/09_federation_sync.md`** — actor_memory PK name in summary table.
- **`lupo-database/lupopedia/json/lupo_actor_memory.json`** — PK field name aligned.
- **`python lupo-scripts/generate_toon_from_sql.py`** — ran (177 TOONs); **removed stale exports** not in install — verify **`git status`**.

---

## [2026-04-07 23:08 UTC] — PRD 01 session identity resolution

**WHO:** cursor (actor_id 102)

- **`lupo-docs/prd/01_core_identity.md`** — `lupo_sessions` column list aligned with **`install_new_lupopedia.sql`**; new **Session Identity Resolution (4.0.96+)** (proxy IP header order, Class C identity string, SESSIONID fallback chain, cookieless recovery, token generation Crafty vs `App\Auth\Session`, `metadata` mapping); edge to **`craftysyntax-reference/functions.php`**.

---

## [2026-04-07 22:53 UTC] — PRD 38 edges + export phases

**WHO:** cursor (actor_id 102)

- **`lupo-docs/prd/38_memory_unification.md`** — `lupopedia.edges`: added **amends** to **07_agents_faucets** and **15_actors**; section **6.5** documents **Phase 1 synchronous** vs **Phase 2 optional queue** export; section **10.3–10.5** states amendment scope for PRDs 07, 15, 37; header/footer UTC via **tick.py**.

---

## [2026-04-07 20:00 UTC] — Doctrine Expansion, Memory Model Deprecation, File-Backed Content

**WHO:** claude-code (actor_id 102) — doctrine expansion pass, memory model update, file-backed content system.

### Context‑Typed, Status‑Aware, Directional Edged Memory Doctrine (4.0.96) — Formal Adoption

- **Doctrine block authored and distributed** across **32 memory-related PRDs**. Each PRD now carries the canonical `## Context‑Typed, Status‑Aware, Directional Edged Memory Doctrine (4.0.96)` section at its end, appended exactly once, with no other content altered.
- **Doctrine defines a 4-dimensional edge model:** every edge in `lupo_edges` has: `edge_type` (relationship), `edge_context` (structural classification), `edge_status` (epistemic support level: `unsupported` / `supported` / `needs_review`), `direction` (`uni` / `bi` / `restricted`).
- **`review_reason` field (Option C):** when `edge_status = 'needs_review'`, a `review_reason` MUST be set. Values: `orphaned_edge`, `contradiction`, `new_doctrine`, `schema_drift`, `consolidation_candidate`, `integrity_unknown`, `human_escalation`. Each value routes to a specific agent: ANUBIS (integrity/orphan), THOTH (schema/contradiction/doctrine), KAIROS (consolidation), human operator (escalation).
- **PRD files touched:** `00_root_constitutional_system_requirements.md`, `01_captain_wolfie_identity.md`, `01_core_identity.md`, `02_channels_discussions.md`, `02_data_model.md`, `03_goals_and_success_criteria.md`, `03_truth_knowledge.md`, `04_lupopedia_js_foundation.md`, `04_tags_metadata.md`, `05_auth_user_actor_agent_transformation.md`, `07_agents_faucets.md`, `09_federation_sync.md`, `11_analytics_tracking.md`, `13_crafty_integration.md`, `15_actors.md`, `15_temporal_system.md`, `16_lupopedia_headers.md`, `17_decisions_format.md`, `18_channel_chat_display.md`, `19_garbage_collection_system.md`, `21_semantic_navbar.md`, `24_actor_onboarding_flow.md`, `26_five_layer_documentation_architecture.md`, `28_semantic_monitoring_widget.md`, `29_project_structure.md`, `31_implementation_folder_guidelines.md`, `32_actor_authority_agent_roles.md`, `33_softaculous_certification_4_1_0_gate.md`, `34_federation_node_semantic_network.md`, `36_rose_multi_persona_synthetic_dialog.md`, `37_kairos_channel_memory_consolidation.md`, `PRD_AGENT_DEFINITION_MODEL.md` (7 already carried the block from prior sessions; 25 newly appended this pass).

### 4-Dimensional Edge Columns Added to `lupo_edges` (install SQL)

- **`install_new_lupopedia.sql`** — `{{prefix}}edges` table updated with four new columns:
  - `edge_context  varchar(64)  DEFAULT NULL` — structural classification of the memory relationship.
  - `edge_status   varchar(32)  DEFAULT 'active'` — epistemic support level of the edge.
  - `direction     enum('uni','bi','restricted')  DEFAULT 'uni'` — traversal orientation.
  - `review_reason varchar(64)  DEFAULT NULL` — agent routing key when `edge_status = 'needs_review'`.
- **New indexes on `lupo_edges`:** `idx_edge_context` (edge_context), `idx_direction` (direction), `idx_status_review` (edge_status, review_reason).

### `memory.json` Deprecated — Actor/Agent Memory Model Updated

All references to `memory.json` as active storage for actor or agent learned behavior have been replaced in the following PRDs:

- **`01_core_identity.md`** — File tree: `memory.json` entry replaced with deprecation comment; Actor Creation Flow step 9: "Learned behavior stored as root memory node at `lupo-memory/YYYY/MM/{memory_slug}.json`; registered in `lupo_memory_nodes`; all memory relationships expressed via `lupo_edges` (4.0.96+). `memory.json` is deprecated."
- **`15_actors.md`** — Both workspace file trees (system actor and runtime actor sections) updated; `### memory.json (Learned from Department Context)` section replaced with `### Root Memory Node (Learned from Department Context) — 4.0.96+` section (includes example JSON for `lupo-memory/2026/04/wolfie-sales-actor-5001.json`, schema reference, edge linkage); Actor Learning Process step 5 updated to reference `lupo-memory/YYYY/MM/{memory_slug}.json` + `lupo_memory_nodes` + `lupo_edges`.
- **`07_agents_faucets.md`** — Actor workspace file tree and optional agent files tree: `memory.json` entries replaced with deprecation comments pointing to `lupo-memory/YYYY/MM/{memory_slug}.json`.
- **`24_actor_onboarding_flow.md`** — Workspace initialization step 5: "Register root memory node in `lupo_memory_nodes`"; Actor Learning paragraph: updated to reference root memory node model and `lupo_edges`; `memory.json` explicitly marked DEPRECATED.
- **`PRD_AGENT_DEFINITION_MODEL.md`** — Both file trees (agent root and versioned layouts); `### memory.json` section replaced with `### Root Memory Node (4.0.96+) — replaces memory.json`; Runtime State note updated.

**Canonical replacement language:** "Actor/agent memory is modeled as memory nodes in `lupo_memory_nodes`, linked via edges in `lupo_edges`, with the actor's root memory node exported to `lupo-memory/YYYY/MM/{memory_slug}.json`."

### File-Backed Content System — PRD 06

- **`lupo-content/` directory** reorganized to canonical structure: `lupo-content/federation_node/{id}/{folder_key}/{file_name}` and `lupo-content/actor/{actor_id}/{folder_key}/{file_name}`. Old directories (`0/`, `actor_id/`, `federation_node_id/`) removed. Files renamed to snake_case lowercase.
- **`install_new_lupopedia.sql`** — `{{prefix}}contents` table updated:
  - `storage_type varchar(16) NOT NULL DEFAULT 'database'` column added (values: `database`, `file_backed`).
  - `file_path_from_root` expanded from `varchar(255)` to `varchar(1024)`.
  - UNIQUE index added: `{{prefix}}contents_idx_slug_deleted (slug, is_deleted)`.
  - `{{prefix}}folders` table updated: `description text DEFAULT NULL` column added.
- **`lupo/install/seed_lupopedia_4_1_0.sql`** — 3 folder rows (ids 10, 11, 12) and 6 content rows (ids 1000000–1000005) added, all `storage_type='file_backed'`, `content=NULL`, `utc_cycle='daily'`, with canonical `file_path_from_root` values.
- **`lupo-docs/prd/06_content_management.md`** — Updated: canonical directory structure, slug rules, `storage_type` rules, migration SQL documented; applied schema corrections reflected; `lupo_folders.description` documented; `when_updated` and `last_verified` updated to `20260407123924`.

### README.md Lupopedia Headers — Malformed Edge Fixed

- **Root `README.md`** — `outbound_edges` YAML block repaired: first edge entry was at 2-space indent (parsed as sibling of `outbound_edges:` key rather than a list item under it), causing all 19 subsequent edges to nest as children of the first. All 20 edges normalized to 4-space indent. Trailing space on one `to:` value removed.

---

## Carried Over Tasks from 4.0.95

All open tasks from the closed **4.0.95** line were migrated to **[TODO.md](TODO.md)** (section **Carried Over from 4.0.95**) on UTC `20260407172944`. Source snapshots: **`../4.0.95/TODO.md`**, **`../4.0.95/PLAN.md`**, **`../4.0.95/README.md`** (release criteria), and the **Not completed** note formerly in **`../4.0.95/CHANGELOG.md`**.

## Planned Work for 4.0.96

- Execute backlog in dependency order (see **[TODO.md](TODO.md)**).
- Record landed work in this file with UTC-thread discipline per PRD 17.
- Use **`PRD_PATCHES/`** for PRD delta drafts and **`SCHEMA_DIFFS/`** for install/schema notes when applicable.
- Use **`NOTES/`** for scratch coordination that is not yet changelog-grade.

This output complies with Lupopedia Constitutional Root Rules.

---

## [2026-04-07 21:00 UTC] — 5W1H Thread Update: Memory, Actors, Doctrine, Schema, Ingestion, Compression

| Element | Answer |
|--------|--------|
| WHO    | Cursor IDE Agent (actor_id 102) |
| WHAT   | Major documentation, schema, and PRD updates for memory model, actor separation, doctrine, ingestion, and compression |
| WHERE  | 
  - lupo-docs/prd/01_core_identity.md
  - lupo-docs/prd/06_content_management.md
  - lupo-docs/prd/11_analytics_tracking.md
  - lupo-docs/prd/13_crafty_integration.md
  - lupo-docs/prd/15_actors.md
  - lupo-docs/prd/24_actor_onboarding_flow.md
  - lupo-docs/prd/37_kairos_channel_memory_consolidation.md
  - lupo-docs/prd/PRD_AGENT_DEFINITION_MODEL.md
  - lupo-docs/versions/4.0.96/CHANGELOG.md
  - lupo-docs/versions/4.0.96/README.md
  - lupo-docs/versions/4.0.96/SUMMARY.md
  - lupo-docs/versions/4.0.96/TODO.md
  - lupo-docs/versions/4.0.96/MIGRATION_NOTES.md
  - lupo-content/ (structure, file-backed content)
  - lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
  - lupo-database/lupopedia/actors/registry.json
  - lupo-actors/116/ (Claude Code)
  - claude.md
  - README.md (root)
| WHEN   | 2026-04-07 21:00 UTC |
| WHY    | To document and preserve all major changes, decisions, and implementation details from this thread |
| HOW    | See below for detailed summary |

### Summary of Work

1. **Memory Model 4.0.96**
  - Adopted 4D edge model (edge_type, edge_context, edge_status, direction, review_reason)
  - Deprecated memory.json; root memory nodes now in lupo-memory/YYYY/MM/{memory_slug}.json
  - PRDs updated: 01, 15, 24, PRD_AGENT_DEFINITION_MODEL, and others

2. **Actor Separation**
  - Cursor remains actor_id 102; Claude Code created as actor_id 116 (per registry)
  - lupo-actors/116/ created with identity.json, boundaries.json
  - claude.md overview created; registry updated

3. **Doctrine Expansion**
  - Context-Typed, Status-Aware, Directional Edged Memory Doctrine appended to 32 PRDs
  - review_reason routing to ANUBIS, THOTH, KAIROS, or human operator

4. **Schema Updates**
  - lupo_edges: new columns (edge_context, edge_status, direction, review_reason)
  - lupo_memory_nodes: added to install SQL
  - lupo_contents: storage_type, file_path_from_root expansion, new indexes
  - lupo_folders: description column added

5. **File-Backed Content System**
  - Canonical directory structure under lupo-content/
  - Seed file updates for file-backed content

6. **Content & Analytics Ingestion Pipeline**
  - PRD 11 extended with comprehensive pipeline for Crafty Syntax data import
  - Covers content pages, navigation paths, referrers, analytics, memory node and edge creation, KAIROS and Lossy Abbreviation Dialect integration

7. **Lossy Abbreviation Dialect**
  - Doctrine for semantic compression of memory nodes
  - New edge_context: lossy_abbrev; edge_type: abbreviates; review_reason: compression_candidate
  - KAIROS integration rules

8. **README.md Fixes**
  - outbound_edges YAML indentation corrected

### Decisions, Questions, Answers, Comments, Observations

- All major decisions, schema changes, and doctrine updates are reflected in the PRDs and version documentation above.
- No new questions, answers, or comments were added to version subfolders in this thread.
- Observations and implementation notes are embedded in the changelog and PRD update summaries.

---
