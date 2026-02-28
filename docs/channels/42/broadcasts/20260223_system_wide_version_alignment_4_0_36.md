# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\42\broadcasts\20260223_system_wide_version_alignment_4_0_36.md"
  file_hash: "9cb7011ed63afc940abbbf2fad74d0873b9cdcdc542bae91df8b0d1b50ec3696"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260223_system_wide_version_alignment_4_0_36.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "42", "broadcasts", "20260223_system_wide_version_alignment_4_0_36md"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
wolfie.headers:
  file_path_from_root: "channels/42/broadcasts/20260223_system_wide_version_alignment_4_0_36.md"
  system_version: "4.0.36"
  channel_id: 42
  mood_rgb: "4B0082"
  purpose: "Broadcast announcing system-wide version alignment to 4.0.36"
  last_modified_utc: "20260223"
  x_lupo_forwarded: "10000:42"
  actor_id: 10000
  lupo_agent: "human|captain"

flip.footer:
  referenced_by_files:
    - "version.php"
    - "LUPEDIA_VERSION"
    - "config/global_atoms.yaml"
    - "CHANGELOG.md"
    - "docs/versions/4.0.36/CHANGELOG_DRAFT.md"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 10000
    - 1001
    - 1002
    - 1003
  inbound_edges:
    - "version_4_0_36_kickoff"
    - "version_transition"
  footnotes:
    - "All agents must update system_version to 4.0.36"
    - "All timestamps use canonical YYYYMMDD format"
    - "Location removed per doctrine"
  version: "4.0.36"
  last_verified: "20260223"
  last_verified_by: "10000"
---

# CHANNEL 42 BROADCAST — SYSTEM-WIDE VERSION ALIGNMENT TO 4.0.36

**From:** Captain Wolfie (actor_id 10000)  
**To:** All IDE Agents (KIRO 1001, Windsurf 1002, Antigravity 1003)  
**Date:** 20260223  
**Subject:** Version 4.0.36 is now active — update all version markers

---

## 🚀 VERSION 4.0.36 IS NOW THE ACTIVE DEVELOPMENT CYCLE

All agents must now align their metadata, headers, and version markers to **4.0.36**.

This includes:

- `version.php`  
- `LUPEDIA_VERSION`  
- `config/global_atoms.yaml`  
- All FLIP headers  
- All FLIP footers  
- All `system_version:` fields  
- All versioned documentation  
- All VSX Extension metadata  
- All directives and broadcasts created from this point forward  

---

# 1. REQUIRED VERSION UPDATES

### ✔ version.php  
Set:
```php
$LUPEDIA_VERSION = "4.0.36";
```

### ✔ LUPEDIA_VERSION  
Set:
```
4.0.36
```

### ✔ config/global_atoms.yaml  
Update:
```yaml
current_version: "4.0.36"
system_version: "4.0.36"
```

### ✔ All FLIP headers  
Every file must contain:
```yaml
system_version: "4.0.36"
```

### ✔ All VSX Extension metadata  
Update:
```json
"version": "4.0.36",
"lupopedia_version": "4.0.36"
```

---

# 2. DOCTRINE COMPLIANCE REQUIREMENTS

All agents must ensure:

- No lingering `4.0.35` markers  
- No outdated headers or footers  
- No outdated TODO references  
- No location fields  
- No time-of-day fields  
- All timestamps use `YYYYMMDD`  
- All `lupo_agent` fields use `type|name` format  
- All X-LUPO-FORWARDED values are numeric  

---

# 3. RESPONSIBILITIES BY AGENT

### ✔ KIRO (1001)
- Update version.php  
- Update LUPEDIA_VERSION  
- Update global_atoms.yaml  
- Sweep all FLIP headers  
- Sweep all system_version fields  
- Generate:
```
docs/status/kiro_version_marker_update_4_0_36.md
```

### ✔ Windsurf (1002)
- Verify KIRO's updates  
- Ensure no 4.0.35 markers remain  
- Validate VSX metadata alignment  
- Confirm repository consistency  

### ✔ Antigravity (1003)
- Update VSX Extension metadata  
- Ensure extension version markers match 4.0.36  
- Confirm publisher identity remains correct  
- Prepare for VSX testing under 4.0.36  

---

# 4. NEXT STEPS

- VSX Extension testing (MD-only, hybrid, db_online)  
- KIRO status query validation  
- Full Crafty Syntax 3.7.5 → Lupopedia 4.0.36 upgrade test  
- Documentation of regressions  
- Begin Phase 1: Registry Consolidation  
- Continue Phase 2: Agent Detection Automation  

---

# STATUS

**Version 4.0.36 is now active.  
All agents must update their version markers immediately.**

---

**END OF BROADCAST** 🚀

---

**Captain Wolfie (actor_id 10000)**  
**Channel 42 Command**  
**2026-02-23 14:05:00 UTC**
