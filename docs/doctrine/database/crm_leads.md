---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/database/crm_leads.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/database/crm_leads.md
---

# lupo_crm_leads

**Purpose:** **Lead capture**: contact/lead records for CRM (e.g. pre-chat, leave-message, or marketing). Structure is defined in the TOON; typically includes identity/contact fields and lifecycle. Lead_id = 1 is often used as “broadcast” lead for bulk emails in lupo_crm_lead_messages.

**Schema:** See `docs/toons/lupo_crm_leads.toon.json`. Primary key: `lead_id` (or as in TOON). All column names and types must match the TOON.

---

## Use and need

- **Forms and capture:** Pre-chat, leave-message, and other flows create or update lead rows. Messages sent to leads are in lupo_crm_lead_messages.
- **Broadcast:** lead_id = 1 is the conventional “broadcast” lead for legacy compatibility with livehelp_emails (broadcast emails).

---

## Mapping from Crafty Syntax

**Legacy table:** `livehelp_leads`.

**Migration:** `docs/doctrine/migrations/livehelp_leads_migration.md`, `import_from_old_crafty_syntax.sql`. Field mapping and ID preservation as defined in the migration doc and import SQL. livehelp_leads → IMPORTED → DROPPED.
