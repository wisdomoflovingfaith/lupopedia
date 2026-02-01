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
 