# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\actors\plans\20260227_actor_directory_expansion_plan.md"
  file_hash: "3caf20e398db1ab7577d7c82225845c2f464d38cbc1d4f382cfb19fefd44c850"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
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
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "actors\plans\20260227_actor_directory_expansion_plan.md"
  file_hash: "899da99727edb6b483b10436aebbe8830597e41d2a815e3db297479723bb5838"
  file_path_from_root: "actors\plans\20260227_actor_directory_expansion_plan.md"
  file_hash: "af6aa460c0dcd0cad76bc563eb0685f8ed66ee0d0b72a836ad3263c016281f18"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Plan: Actor Directory Semantic Expansion (v4.0.47 -> v4.0.48)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["actors", "plans", "20260227_actor_directory_expansion_planmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Plan: Actor Directory Semantic Expansion (v4.0.47 -> v4.0.48)

## 🎯 Overview
Following the successful implementation of the core actor directory structure and the introduction of `WHO.json` by Cascade (1005), this plan outlines the next phase of expansion. We will integrate Lilith's (2038) investigative requirements and Cascade's tracking enhancements to create a fully persistent, historical, and performance-aware actor ecosystem.

## 📂 Phase 1: Directory Structure Augmentation

### 1.1 Identity Refinement (`WHO.json` & `identity.json`)
We will unify the identity layer by ensuring all actors have a `WHO.json` matching the Cascade standard, supplemented by an `identity.json` for DB-parity.
- **Target**: All registered actors.
- **Identity.json**: Strict mirror of the `lupo_actors` table for portable reconstructed identity.

### 1.2 Web Presence (`www/`)
Addition of a `www/` directory for public-facing profiles and avatars, supporting internal and external discovery.

### 1.3 Real-time Visibility (`tasks/current_focus.json`)
Implementation of a high-visibility task monitoring file for active agents.
- **Contents**: `current_tasks` (array), `next_tasks` (array), `blockers`.

### 1.3 Achievement Tracking (`history/resume.json`)
Structured achievement logging to replace/supplement the human-readable `resume.md`.
- **Contents**: `best_work` (structured achievements), `skills_mastered`, `total_contributions`.

### 1.4 Performance Analysis (`performance/llm_stats.json`)
Capturing Lilith's requested LLM performance metrics.
- **Folder**: `actors/<id>/performance/`
- **Contents**: Task success rates, token usage per model, duration averages.

## 🗄️ Phase 2: Database Evolution (TOON Sync)

### 2.1 Schema Expansion (v4.0.48 Migration)
Integration of the 6 proposed tables and column enhancements to bridge the filesystem-database gap.

**New Tables:**
1. `lupo_actor_history`: Tracking changes in roles, capabilities, and LLM configs.
2. `lupo_actor_relationship_rules`: Validation rules for reporting lines and collaborations.
3. `lupo_capability_usage`: Detailed analytics on when and how capabilities are exercised.
4. `lupo_llm_performance`: Efficiency and quality metrics for LLM calls.
5. `lupo_federated_trust`: (Optional/Phase 3) Managing identity across instances.
6. `lupo_session_recovery`: Persistent state snapshots for seamless IDE restarts.

**Table Updates (`lupo_actors`):**
- Add `filesystem_path`
- Add `who_json_sync_status`
- Add `last_sync_ymdhis`

## 🛠️ Phase 3: Tooling & Automation

### 3.1 Script Upgrades
- **`scripts/validate_actors.py`**: Updated to check for `WHO.json`, `current_focus.json`, and `llm_stats.json`.
- **`scripts/sync_who_json.php`**: A new PHP script to perform the 1:1 mapping between `WHO.json` files and the database `metadata_json` column.

## 📅 Timeline
- **Today (4.0.47)**: Folder structure and file implementations (`WHO.json`, `current_focus.json`).
- **Phase 2 Kickoff (4.0.48)**: Database migration and TOON regeneration.

---
**Status**: Draft Plan
**Lead**: Gemini CLI (1006)
**Reviewers**: Captain Wolfie (10000), Cascade (1005), Lilith (2038)