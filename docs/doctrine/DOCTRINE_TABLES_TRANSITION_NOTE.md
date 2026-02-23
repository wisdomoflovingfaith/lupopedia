---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/DOCTRINE_TABLES_TRANSITION_NOTE.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/DOCTRINE_TABLES_TRANSITION_NOTE.md
---

# Doctrine Tables — Transition to {prefix}contents (Channel 42)

**Status:** Permanent note.  
**Purpose:** Clarify use of doctrine-related tables and target state.

---

## Target: {prefix}contents on channel 42

Doctrine blocks, refinement tracking, and evolution audit should be represented using **{prefix}contents** bound to **channel 42** (doctrine channel), not dedicated tables. New features must not add or rely on `{prefix}doctrine_blocks`, `{prefix}doctrine_refinements`, or `{prefix}doctrine_evolution_audit` for new design.

---

## Current state (4.0.9)

### lupo_doctrine_blocks — removed from install

- **Not used** in PHP or in the wizard/seed/importer.
- **Removed** from `install_new_lupopedia.sql`. New installs do not create this table.
- Any future doctrine-block storage should use **{prefix}contents** on **channel 42**.

### lupo_doctrine_refinements and lupo_doctrine_evolution_audit — still in install

- **Used** by CIP (CIPDoctrineRefinementModule, test_cip_analytics, examples). Tables remain in `install_new_lupopedia.sql`.
- They **should be transitioned** to use **{prefix}contents** on **channel 42** so that doctrine refinement and evolution audit are content items on the doctrine channel, not separate tables. Until that transition is done, the existing tables remain required for CIP.

---

## References in repo

- **PHP:** `lupo-includes/classes/CIPDoctrineRefinementModule.php`, `test_cip_analytics.php`, `examples/cip_system_demo.php`, `lupo-includes/classes/CIPEventPipeline.php` (array key `doctrine_refinements`).
- **MD / docs:** Various migration and changelog docs reference these tables; treat as historical. New docs should point to this transition note and to {prefix}contents / channel 42.
