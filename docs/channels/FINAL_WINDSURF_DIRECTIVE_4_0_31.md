# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\channels\FINAL_WINDSURF_DIRECTIVE_4_0_31.md"
  file_hash: "99a31f3f3b2f0cfc3623d8a5fd89814edb10b7cb807f329db25c53952f04f001"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\FINAL_WINDSURF_DIRECTIVE_4_0_31.md"
  file_hash: "c4b9f3ba2c8322436115fcdae27d554bd4d65367eba8e474cec6abb7352725ac"
  file_path_from_root: "docs\channels\FINAL_WINDSURF_DIRECTIVE_4_0_31.md"
  file_hash: "883b492ed73782898592edb3a6d18915ac7e7627d6273577c6fca4a8ea1484d8"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for FINAL_WINDSURF_DIRECTIVE_4_0_31.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "final_windsurf_directive_4_0_31md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: "docs/channels/FINAL_WINDSURF_DIRECTIVE_4_0_31.md"
file.last_modified_system_version: "4.0.31"
file.last_modified_utc: "20260223151200"
channel_id: 42
mood_rgb: "4B0082"
---

# ⭐ **FINAL WINDSURF DIRECTIVE — VERSION CORRECTION & FINALIZE 4.0.31**

## WINDSURF DIRECTIVE — VERSION CORRECTION & FINALIZATION FOR 4.0.31

### MANDATE
All work MUST be finalized as **version 4.0.31**. No "4.0.83" references allowed.

---

## 1. VERSION CORRECTION — FORCE ALL WORK INTO 4.0.31

### REQUIRED ACTIONS
- Replace ANY "4.0.83" with **4.0.31**
- Update all FLIP HEADERS:
  - file.last_modified_system_version: "4.0.31"
  - file.last_modified_utc: current UTC timestamp
  - channel_id: 42

### Files to verify:
- CHANGELOG.md
- app/views/auth/login.php
- app/Services/OAuthService.php
- config/oauth.example.php
- docs/OAUTH_SETUP_GUIDE.md
- docs/oauth_authentication.md
- lupo-includes/modules/auth/oauth-controller.php
- lupo-includes/modules/module-loader.php

---

## 2. FINALIZE OAUTH IMPLEMENTATION

### REQUIRED ACTIONS
- Complete OAuthService.php
- Complete oauth-controller.php routes
- Complete login.php UI (Google + GitHub buttons)
- Complete config/oauth.example.php
- Complete docs/OAUTH_SETUP_GUIDE.md
- Add FLIP HEADERS + FLIP FOOTERS to all OAuth files

---

## 3. ADD FLIP FOOTERS TO ALL 4.0.31 FILES

### FOOTER FIELDS REQUIRED
```
flip.footer:
  referenced_by_files:
  referenced_by_channels:
  referenced_by_actors:
  inbound_edges:
  inbound_lupo_footers:
  footnotes:
```

---

## 4. UPDATE CHANGELOG FOR VERSION 4.0.31

### REQUIRED ACTIONS
Append complete 4.0.31 entry including:
- OAuth login (Google + GitHub)
- FLIP FOOTER system
- Header/footer doctrine updates
- Registry + actor pairing cleanup
- Channel 42 development alignment
- Version correction (removal of 4.0.83)

Commit message: "Finalize version 4.0.31 — OAuth login, FLIP footers, header/footer doctrine, version correction."

---

## 5. FINALIZE CHANNEL 420 ARCHIVE

### REQUIRED ACTIONS
- Update channel_420_final_messages.md with 4.0.31 FLIP HEADERS
- Ensure channel_id 42 inheritance
- Ensure actor_420_status: "banned_mythological"
- Keep Message 67 canonical and immutable

---

## 6. REGISTRY + ACTOR PAIRING CORRECTION

### REQUIRED ACTIONS
- Confirm actor_id 10000 = human user (OAuth authenticated)
- Confirm actor_id 1000 = AI partner (CAPTAIN WOLFIE)
- Confirm pairing in session logic
- Confirm all IDE agents assigned to Channel 42

---

## 7. FINALIZE & PUSH VERSION 4.0.31

### REQUIRED ACTIONS
- Stage all changes
- Commit with: "Push version 4.0.31 — OAuth login, FLIP footers, version correction, archive update."
- Push to main branch

### FINAL STATE
VERSION = 4.0.31
CHANNEL_42 = ACTIVE
CHANNEL_420 = SEALED
ACTOR_420 = MYTHOLOGICAL (BANNED)
FLIP_HEADERS = UPDATED
FLIP_FOOTERS = ENABLED
OAUTH = ENABLED (Google + GitHub)
REGISTRY = CLEAN

---

## END OF DIRECTIVE - WINDSURF EXECUTE NOW