# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "database\refactors\readme.md"
  file_hash: "129feadf1f19136925d64d99ddf27951abf1142b7667acd66d788ee6fd8cc844"
  file_path_from_root: "database\refactors\readme.md"
  file_hash: "fb9e1cf5e437ef1faad6d1bfd73eb1555ef32acdbc1afc522fd8d105046c99c2"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for readme.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["database", "refactors", "readmemd"]
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
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.15
dialog.speaker: System Wolfie  
dialog.message: "Refactor directory initialized. Migration integrity preserved."
---

 

## 📁 Directory Purpose

The `refactors/` directory exists to:

- Provide a **clear, isolated mapping** for each legacy table  
- Allow Cursor (and humans) to reason about one table at a time  
- Enable automated migration scripts to loop through mappings  
- Keep the migration process deterministic, documented, and reversible  
- Prevent hallucinations or cross‑contamination between mappings  

This directory is the **source of truth** for the Crafty Syntax → Lupopedia translation layer.

---

## 📄 File Structure

refactors/
│
├── manifest.json
│
├── livehelp_autoinvite.toon
├── livehelp_channels.toon
├── livehelp_departments.toon
├── livehelp_messages.toon
├── livehelp_operator_channels.toon
├── livehelp_operator_departments.toon
├── livehelp_operator_history.toon
├── livehelp_paths_firsts.toon
├── livehelp_paths_monthly.toon
├── livehelp_qa.toon
├── livehelp_questions.toon
├── livehelp_quick.toon
├── livehelp_referers_daily.toon
├── livehelp_referers_monthly.toon
├── livehelp_sessions.toon
├── livehelp_smilies.toon
├── livehelp_transcripts.toon
├── livehelp_users.toon
├── livehelp_visits_daily.toon
├── livehelp_visits_monthly.toon
├── livehelp_visit_track.toon
├── livehelp_websites.toon
 
---

## 🧩 toon File Format
 