# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\doctrine\GUARDRAILS.md"
  file_hash: "9017e92105783289618800af29761d7a81ed2dae679d3d035357dda9a2ceebae"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "GUARDRAILS.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "guardrailsmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# GUARDRAILS.md

## 1. Overview

**Purpose:** Define rules for transparency, stability, and governance in Lupopedia.

**Scope:** Applies to all agents, IDEs, and operations.

**Relationship to doctrine files:** Complements LIMITS.md and STOP.md.

**Enforcement mechanisms:** Manual checks/scripts; no auto‑execution.

**Scope boundary:** Guardrails describe rules; they do not execute logic.

## 2. Version Management Guardrails

- Enforce Version Atom Doctrine.
- **Prohibited:** Hard‑coded versions, global replacements.
- **Required:** Atom references, header version handling.
- **Procedures:** Manual bump with auth check.

## 3. Architecture Guardrails

- Maintain WOLFIE system principles (no modernization/simplification).
- Require doctrine compliance.
- Enforce schema‑first.
- Protect OS‑layered structure.

## 4. Database Guardrails

- **Standards:** ID columns as BIGINT(20) UNSIGNED.
- **Prohibited:** Foreign keys, triggers, stored procedures.
- **Required:** Application‑layer logic.

## 5. File Modification Guardrails

- Require WOLFIE Headers.
- Handle source vs output files (e.g., CSS).
- Protect patterns: .lock, key docs.
- No auto‑fixing or auto‑rewriting.

## 6. Agent Behavior Guardrails

- **Protocols:** Documentation‑only mode.
- Detect/respond to STOP.flag.
- Require explicit unlock.
- No auto‑inference.

## 7. IDE Behavior Guardrails

- No background refactors.
- No unsolicited diffs.
- No auto‑generation of missing files.
- Respect .lock files.

## 8. Documentation Guardrails

- Use atoms in docs.
- **Standards:** Version references.
- Handle historical vs current versions.

## 9. Emergency Protocols

- **Mechanism:** STOP.flag presence halts all.
- **Halt procedures:** Create flag; freeze operations.
- **Reset protocols:** Review logs, remove flag.
- **Notification:** Log for admin (lupopedia‑at‑gmail‑com).

## 10. Human Authority Clause

All guardrails defer to the human administrator (lupopedia‑at‑gmail‑com).

---

## Enforcement Mechanisms (Meta Notes)

**Manual Copy:** This file is ready for manual editing.

**Auth Script:** Extend your existing log_change logic to cover this file.

**No Auto:** All enforcement is manual; no agent should implement these rules automatically.
