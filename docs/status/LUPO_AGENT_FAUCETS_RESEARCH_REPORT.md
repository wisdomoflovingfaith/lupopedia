---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "docs/status/LUPO_AGENT_FAUCETS_RESEARCH_REPORT.md"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260303"
  artifact_type: "report"
  purpose: "Research on lupo_agent_faucets and faucet directory proposal"
  traits: ["research", "faucets", "lupo_agent_faucets", "directory-structure"]
  tags: ["faucets", "agents", "lupo_agent_faucets", "cursor"]
  lupo_agent: "cursor"
---

# LUPO_AGENT_FAUCETS Research Report

**Date:** 2026-03-03  
**Actor:** Cursor (1003)  
**Repository:** https://github.com/wisdomoflovingfaith/lupopedia (canonical)

---

## 1. Research Summary: lupo_agent_faucets in Codebase

### 1.1 Purpose and Schema

- **Table:** `lupo_agent_faucets` — stores agent “faucet” definitions (capabilities/outputs an agent can emit or operate: LLM presets, system prompts, safety rules, model params).
- **Canonical schema:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (CREATE TABLE); TOON reference: `lupo-docs/toons/lupo_agent_faucets.toon.json` (and canonical `lupo-database/lupopedia/toon/` when present).
- **Primary key:** `agent_faucet_id` (bigint). Key columns: `actor_id`, `name`, `alias_name`, `slug`, `description`, `style_preset`, `model_name`, `provider`, `temperature`, `top_p`, `max_tokens`, `presence_penalty`, `frequency_penalty`, `system_prompt`, `safety_json`, `response_format`, `capabilities_json`, `is_default`, `domain_id`, `created_ymdhis`, `updated_ymdhis`, `deleted_ymdhis`.
- **Indexes:** `actor_id`, `slug`, `domain_id`, `is_default`.
- **Related table:** `lupo_agent_faucet_credentials` (credentials per faucet via `faucet_id` → `agent_faucet_id`).

### 1.2 Implementation and Related Files

| File | Role |
|------|------|
| `lupo-bin/faucet_loader.php` | **FaucetLoader** — loads TOON from `lupo-database/lupopedia/toon/lupo_agent_faucets.toon.json`; resolves faucet by (channel_id, actor_id). **Lookup order:** (1) per-actor `channels/{channel_id}/actors/{actor_id}/faucets.json`, (2) channel-wide `channels/{channel_id}/faucets.json`. Paths are relative to CWD (repo root). No `LUPO_DATABASE_DIR` used. |
| `lupo-bin/validate_faucets.php` | **FaucetValidator** — validates all faucet JSON files against TOON. Uses `docs/toons/lupo_agent_faucets.toon.json`; recursively scans `channels/` for `faucets.json` (channel-wide and per-actor). |
| `lupo-bin/faucet_integrity_audit.php` | **FaucetIntegrityAuditor** — cross-channel integrity (duplicate slugs, orphan files, actor dirs without faucets). Scans `channels/` only. |
| `scripts/wolfie_orms.py` | `select_one_from_lupo_agent_faucets(db)` — legacy; uses columns `agent_id`, `faucet_type`, `faucet_data`, `is_deleted` which **do not match** current table schema (actor_id, name, slug, …). Treat as drift; not used for faucet loading. |

### 1.3 Dependencies

- **Agents / actors:** Faucets are per `actor_id`; actor directories live under `lupo-database/lupopedia/actors/actor_id/<id>/` (OS-like “user” directories).
- **Channels:** Faucet resolution is channel-scoped (per-actor override then channel-wide).
- **Database:** Table is required (install SQL, REQUIRED_TABLES, TOON). Optional file-based fallback: loader reads JSON from filesystem when files exist; DB can remain source of record for IDs and sync.
- **TOON:** Schema validation in loader and validator depends on `lupo_agent_faucets` TOON (canonical path in loader: `lupo-database/lupopedia/toon/lupo_agent_faucets.toon.json`).

### 1.4 Usage and Path Mismatch

- **Current file-based faucet:** Only one faucet file found: `lupo-database/lupopedia/channels/lupo-channels/42/actors/19/faucets.json` (ANUBIS FLARE ingestion, agent_faucet_id 6).
- **Loader path:** FaucetLoader looks for `channels/{channel_id}/actors/{actor_id}/faucets.json` (relative to CWD). That resolves to repo-root `channels/42/actors/19/faucets.json`, **not** `lupo-database/lupopedia/channels/lupo-channels/42/actors/19/faucets.json`. So unless CWD is `lupo-database/lupopedia` or a symlink exists, the existing ANUBIS faucet file is **not** discovered by the current loader.
- **Doctrine (Windsurf doc):** `lupo-docs/archive/v4.0.52_windsurf_reports/windsurf_agent_faucets_explanation.md` — actors canonical at `actors/<actor_id>/`; faucets channel-scoped at `channels/<channel_id>/actors/<actor_id>/faucets.json` or `channels/<channel_id>/faucets.json`; TOON alignment required.
- **Boot:** No faucet loading in bootstrap; loader is on-demand (e.g. CLI: `php faucet_loader.php --channel=42 --actor=19`).
- **IRIS / Carmen:** `lupo-includes/class-iris.php` and `class-carmen.php` use the word “faucet” conceptually (LLM on/off, placeholders); they do not reference `lupo_agent_faucets` or the file loader.

### 1.5 Code Snippets (Relevant)

**FaucetLoader lookup order (faucet_loader.php):**

```php
// Try per-actor override first
$per_actor_file = "channels/{$channel_id}/actors/{$actor_id}/faucets.json";
if (file_exists($per_actor_file)) {
    $faucet = $this->loadAndValidate($per_actor_file);
    // ...
}
// Fall back to channel-wide faucets
$channel_wide_file = "channels/{$channel_id}/faucets.json";
```

**Table definition (install_new_lupopedia.sql):**

```sql
CREATE TABLE lupo_agent_faucets (
  agent_faucet_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  name varchar(100) NOT NULL,
  slug varchar(100) NOT NULL,
  -- ... system_prompt, safety_json, capabilities_json, domain_id, timestamps ...
  PRIMARY KEY (agent_faucet_id)
);
CREATE INDEX lupo_agent_faucets_idx_agent ON lupo_agent_faucets (actor_id);
CREATE INDEX lupo_agent_faucets_idx_slug ON lupo_agent_faucets (slug);
```

---

## 2. Evaluation: New Directory Structure `lupo-database/actors/faucets/<agent_faucet_id>/`

**Proposal:** Create `lupo-database/actors/faucets/<agent_faucet_id>/` (or, for consistency with existing layout, `lupo-database/lupopedia/actors/faucets/<agent_faucet_id>/`) for file-based faucet definitions, one directory per faucet ID.

### 2.1 Alignment with Doctrine

- **Actors as OS-like directories:** Doctrine states actor directories (e.g. `lupo-database/lupopedia/actors/actor_id/`) function like user OS directories (programs, web files, collections). A **global** faucet store under `lupo-database/lupopedia/actors/faucets/<agent_faucet_id>/` fits “programs” or shared resources keyed by faucet ID, while **per-actor** faucets remain under actor or channel paths. So:
  - **Option A:** `lupo-database/lupopedia/actors/faucets/<agent_faucet_id>/` — global faucet registry by ID (additive).
  - **Option B:** Keep channel-scoped layout only (`channels/<channel_id>/actors/<actor_id>/faucets.json`) and normalize paths to canonical root (e.g. `lupo-database/lupopedia/channels/lupo-channels/...`) in loader.
- **TOON / schema:** Any new directory must still expose JSON that matches `lupo_agent_faucets` TOON (e.g. one `faucet.json` or `definition.json` per `<agent_faucet_id>/`).

### 2.2 Integration Steps (If Adopted)

1. **Define canonical path:** e.g. `lupo-database/lupopedia/actors/faucets/<agent_faucet_id>/faucet.json` (single faucet object) or `faucets.json` (array or wrapper with schema_version).
2. **FaucetLoader:** Extend resolution to (a) resolve (channel_id, actor_id) → agent_faucet_id (from DB or a manifest under `actors/faucets/`), then (b) load from `lupo-database/lupopedia/actors/faucets/<agent_faucet_id>/faucet.json` when present; else keep current channel/actor file lookup. Use `LUPOPEDIA_PATH` (or `LUPO_DATABASE_DIR` if set) so paths are not CWD-dependent.
3. **Validate / audit scripts:** Add scan of `lupo-database/lupopedia/actors/faucets/` and validate each file against TOON; optionally cross-check with DB rows.
4. **Migrate or copy:** Move or copy existing faucet definitions (e.g. ANUBIS 6) into `lupo-database/lupopedia/actors/faucets/6/` and update loader to look there (and optionally keep channel-scoped files as override).
5. **Documentation:** Update FAUCET_RULES / Windsurf doctrine to describe both channel-scoped and ID-scoped paths; state which takes precedence.

### 2.3 Potential Impacts

- **Boot:** No change if loader remains on-demand.
- **Agents:** Positive: one directory per faucet ID gives a clear “program” per faucet; agents can reference by ID; supports fallback when DB is unavailable if loader reads file-first.
- **Fallbacks:** File-first loading from `actors/faucets/<id>/` can serve as fallback when DB is down, if loader is extended to discover by actor_id (e.g. manifest or naming convention).
- **Backward compatibility:** Existing channel/actor paths can remain as overrides so current behavior is preserved while new structure is adopted.

### 2.4 Recommendations

- **Viable:** Yes — adding `lupo-database/lupopedia/actors/faucets/<agent_faucet_id>/` is feasible and aligns with actors-as-directories and a single place per faucet ID.
- **Pros:** Single directory per faucet; easy to sync DB ↔ file; supports OS-like “program” per faucet; can hold meta (e.g. README, overrides); works with existing TOON validation.
- **Cons:** Loader currently resolves by (channel, actor), not by agent_faucet_id; need either a manifest (actor_id/channel → agent_faucet_id) or to scan directories; two sources of truth (DB + files) unless one is declared authoritative.
- **Suggested implementation plan:**
  1. Add directory layout and one example: `lupo-database/lupopedia/actors/faucets/6/faucet.json` (copy from current ANUBIS faucets.json content for agent_faucet_id 6).
  2. Update FaucetLoader to (a) use a configurable base path (e.g. `LUPOPEDIA_PATH` or `LUPO_DATABASE_DIR`), (b) try `lupo-database/lupopedia/actors/faucets/<id>/faucet.json` when agent_faucet_id is known, (c) retain channel/actor paths as override.
  3. Add resolution from (channel_id, actor_id) to agent_faucet_id: either from DB (SELECT agent_faucet_id FROM lupo_agent_faucets WHERE actor_id = ? AND domain_id = ?) or from a small manifest file under `actors/faucets/` (e.g. `by_actor.json`).
  4. Update validate_faucets.php and faucet_integrity_audit.php to include `lupo-database/lupopedia/actors/faucets/` in scans and normalize TOON path to `lupo-database/lupopedia/toon/lupo_agent_faucets.toon.json` where applicable.
  5. Document the dual layout (channel-scoped vs ID-scoped) and precedence in doctrine.

---

## 3. Proposed Code/Doc Changes (Summary)

| Change | Description |
|--------|-------------|
| **FaucetLoader path base** | Resolve paths from `LUPOPEDIA_PATH` (or `LUPO_DATABASE_DIR`) so that both `lupo-database/lupopedia/channels/lupo-channels/{id}/actors/{id}/faucets.json` and `lupo-database/lupopedia/actors/faucets/<agent_faucet_id>/faucet.json` can be used. |
| **validate_faucets.php TOON path** | Prefer canonical TOON path `lupo-database/lupopedia/toon/lupo_agent_faucets.toon.json` with fallback to `docs/toons/lupo_agent_faucets.toon.json`. |
| **New directory** | Create `lupo-database/lupopedia/actors/faucets/` and optionally `6/faucet.json` as pilot. |
| **Doctrine** | Document ID-scoped faucet directory and its interaction with channel-scoped faucets. |
| **wolfie_orms.py** | Fix or deprecate `select_one_from_lupo_agent_faucets` column names to match current schema if still used. |

---

## 4. Timestamp and Actor

- **Report generated:** 2026-03-03  
- **Actor ID:** 1003 (Cursor IDE Agent)  
- **Channel:** 42  
- **System version:** 4.0.56  

---

*End of report.*
