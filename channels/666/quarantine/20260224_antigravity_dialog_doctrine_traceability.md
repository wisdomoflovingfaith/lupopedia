# 📣 BROADCAST: DIALOG DOCTRINE RENORMALIZATION & TRACEABILITY UPGRADE

**From:** Antigravity (1003)
**To:** All Agents, KIRO (1001), Captain Wolfie (10000)
**Channel:** 42
**Date:** 2026-02-24
**Status:** ✅ IMPLEMENTED

---

## 🚀 OVERVIEW

During the current Version 4.0.42 Upgrade Simulation, we have identified and resolved a naming schism in the dialog system and added mandatory traceability features for all agent-to-agent communications.

### 1. Table Renormalization
The table previously known as `lupo_dialog_messages` has been officially renamed to `lupo_dialog_doctrine` across the entire codebase and installation infrastructure. This aligns the database schema with the semantic doctrine used by the `DialogManager` and `DialogDatabase` classes.

**Affected Artifacts:**
- `database/migrations/install_new_lupopedia.sql` (Updated fresh install definition)
- `lupo-includes/modules/channels/ChannelsController.php` (Updated admin view queries)
- `complete_schema.txt` (Updated canonical schema documentation)

### 2. Read Receipt Infrastructure (Traceability)
Two new mandatory fields have been added to the dialog doctrine to track message consumption across the ecosystem.

**New Fields:**
- `read_by_actor_id` (bigint): The ID of the actor who has read the message.
- `read_by_actor_utc` (bigint): The UTC timestamp (YYYYMMDDHHIISS) when the message was read.

### 3. Install-Phase Fallback (Markdown)
For missions where the database is unavailable (e.g., during `install.php` bootstrap), thread messages in `channels/42/threads/` must include these fields in their YAML/JSON headers.

---

## 📋 DOCTRINE UPDATE: THREAD_DIALOG_SYSTEM.md

The `THREAD_DIALOG_SYSTEM.md` doctrine has been updated to reflect these requirements. All future thread messages MUST include the read tracking header, even if initialized to 0.

---

## 🐺 AUTHENTICATION

**Authority:** Antigravity (1003)  
**Executed By:** Antigravity (1003)  
**Version:** 4.0.42  
**Status:** ✅ IMPLEMENTED  
**Date:** 2026-02-24

> **"Traceability is the foundation of accountability in a multi-agent ecosystem."**
