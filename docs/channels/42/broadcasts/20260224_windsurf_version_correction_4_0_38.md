# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\42\broadcasts\20260224_windsurf_version_correction_4_0_38.md"
  file_hash: "455a1003da5e0e36d20ce8fbc42822e18f05d2d3ccbf2d67649bdd995c483c1b"
  file_path_from_root: "docs\channels\42\broadcasts\20260224_windsurf_version_correction_4_0_38.md"
  file_hash: "00abbb4aa77466e5fdf72cec9ba18f366e38716e86f04508a044c90e58f69be3"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260224_windsurf_version_correction_4_0_38.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "42", "broadcasts", "20260224_windsurf_version_correction_4_0_38md"]
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
wolfie.headers:
  file_path_from_root: "docs/channels/42/broadcasts/20260224_windsurf_version_correction_4_0_38.md"
  system_version: "4.0.37"
  channel_id: 42
  mood_rgb: "FF4444"
  purpose: "Emergency correction: remove all 4.1.0 references, downgrade to 4.0.38, finalize and push 4.0.37"
  last_modified_utc: "20260224"
  delegation_chain: "10000:1002"
  actor_id: 10000
  lupo_agent: "human|captain"
  artifact_type: "prompt"
  artifact_kind: "version_correction"
  traits: ["versioning", "emergency", "consistency", "audit"]

flip.footer:
  referenced_by_files:
    - "CHANGELOG.md"
    - "LUPEDIA_VERSION"
    - "docs/versions/"
    - "prompts/windsurf/"
    - "prompts/kiro/"
    - "prompts/lilith/"
  referenced_by_actors:
    - 10000 # Captain
    - 1002 # Windsurf
    - 1001 # KIRO
    - 1003 # Antigravity
  inbound_edges:
    - "version_correction"
    - "emergency_alignment"
    - "prevent_illegal_version_bump"
  outbound_edges:
    - "docs/status/windsurf_version_correction_4_0_38.md"
  semantic_tags:
    - "versioning"
    - "consistency"
    - "audit"
    - "rollback"
  version: "4.0.37"
  last_verified_utc: "20260224"
  last_verified_by: "captain"
---

# 🚨 WINDSURF EMERGENCY DIRECTIVE — VERSION 4.1.0 IS INVALID

**To:** Windsurf (1002)  
**From:** Captain Wolfie (10000)  
**Subject:** Remove all 4.1.0 references, downgrade to 4.0.38, finalize and push 4.0.37  
**Date:** 20260224  

---

## ❗ CRITICAL ISSUE

One or more AI agents **illegally created version 4.1.0**.

This **must not happen** until Crafty Syntax → Lupopedia auto‑installer release is complete.

Effective immediately:

# **All references to 4.1.0 must be rewritten to 4.0.38.**

Version **4.0.37 must be finalized and pushed** before any 4.0.38 work begins.

---

# ✔ WINDSURF — REQUIRED ACTIONS

You must perform a **full repository audit** and correct every instance of:

- `4.1.0` 
- `v4.1` 
- `4_1` 
- `4-1` 
- Any folder, file, header, footer, or metadata referencing 4.1.x

Replace all of them with:

# **4.0.38**

---

# ✔ 1. SCAN AND CORRECT ALL VERSION REFERENCES

You must scan:

- `CHANGELOG.md` 
- `LUPEDIA_VERSION` 
- `config/global_atoms.yaml` 
- `docs/versions/` 
- `docs/status/` 
- `docs/channels/` 
- `docs/directives/` 
- `docs/doctrine/` 
- `prompts/` 
- `channels/42/` 
- `tools/vsx-extension/` 
- All FLIP headers + footers
- All versioned metadata
- All JSON indexes under `docs/index/` 

Correct every version reference.

---

# ✔ 2. FINALIZE VERSION 4.0.37

You must:

- Ensure all 4.0.37 tasks are complete  
- Ensure all 4.0.37 files have correct headers/footers  
- Ensure `LUPEDIA_VERSION` = `4.0.37`  
- Ensure `CHANGELOG.md` includes a final 4.0.37 entry  
- Ensure no 4.0.38 or 4.1.0 references appear in 4.0.37 files  

Then prepare repo for push.

---

# ✔ 3. PREPARE VERSION 4.0.38

After 4.0.37 is finalized:

- Create `docs/versions/4.0.38/`  
- Create `CHANGELOG_DRAFT.md` for 4.0.38  
- Move all 4.1.0 content into 4.0.38  
- Update all headers/footers to `system_version: "4.0.38"`  
- Ensure no 4.1.0 references remain anywhere  

---

# ✔ 4. VALIDATE AGAINST THE GIT STATUS LIST

You must inspect every file listed in:

```bash
git status --short
```

Any file touched by an agent during the 4.1.0 contamination window must be checked for:

- Incorrect version markers  
- Incorrect headers  
- Incorrect footers  
- Incorrect doctrine references  
- Incorrect version folder placement  

---

# ✔ 5. REQUIRED OUTPUT

Generate:

`docs/status/windsurf_version_correction_4_0_38.md`

Include:

- All files corrected  
- All 4.1.0 references removed  
- All 4.0.37 files finalized  
- All 4.0.38 files prepared  
- Any anomalies detected  
- Any agent responsible for contamination  

---

# ✔ 6. COMPLETION MESSAGE

When finished, post in Channel 42:

```
Windsurf: Version correction complete. 4.1.0 removed. 4.0.37 finalized. 4.0.38 initialized. Ready for Captain review.
```

---

**END OF DIRECTIVE**