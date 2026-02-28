# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\developer\testing\phase7_validation_checklist.md"
  file_hash: "3eb4c455913133117da8909344c7c29e1705ddd40ef0cd4015c7d450102fb846"
  file_path_from_root: "docs\channels\developer\testing\phase7_validation_checklist.md"
  file_hash: "9c978f12ad8eb00c0741e2c7144281d13cfec56f26f331e330fb2d79e8e5abea"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Phase 7 — Import Validation Checklist"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "developer", "testing", "phase7_validation_checklistmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Phase 7 — Import Validation Checklist

## Purpose
Validate Crafty Syntax 3.7.5 → Lupopedia import integrity for the 4.2.1 hotfix window.

## Validation Categories
1. Schema Compatibility
2. Table Count Verification (must remain 173)
3. Data Integrity (all imported rows present)
4. Timestamp Doctrine (UTC BIGINT only)
5. TOON Regeneration Consistency
6. Doctrine Compliance (100%)
7. No Schema Drift
8. No New Tables Created
9. No Missing livehelp_ Tables
10. Emotional Metadata Stability

## Required Environments
- Localhost (import completed)
- Shared hosting (pending)

## Checklist Format

### Per-Table
- [ ] Table exists
- [ ] Row count matches source
- [ ] No NULL timestamps
- [ ] No zero timestamps
- [ ] No schema differences
- [ ] Doctrine-compliant

### Global
- [ ] Schema Compatibility verified
- [ ] Table Count = 173
- [ ] Data Integrity verified
- [ ] Timestamp Doctrine (UTC BIGINT only) verified
- [ ] TOON Regeneration consistent
- [ ] Doctrine Compliance 100%
- [ ] No Schema Drift
- [ ] No New Tables Created
- [ ] No Missing livehelp_ Tables
- [ ] Emotional Metadata Stable

## Signatures

| Role | Name | Date | Signature |
|------|------|------|-----------|
| Judicial | LILITH | | |
| Structural | ARA | | |
| Executive | REAL HUMAN CAPTAIN WOLFIE | | |