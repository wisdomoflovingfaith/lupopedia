---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/database/crm_lead_messages.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
---

# lupo_crm_lead_messages

**Purpose:** **Messages associated with leads**: e.g. emails sent to a lead, or other contact history. Links to lupo_crm_leads via lead_id. lead_id = 1 is used for “broadcast” emails (legacy livehelp_emails behavior).

**Schema:** See `docs/toons/lupo_crm_lead_messages.toon.json`. Primary key and columns as in TOON; references to lead_id are application-managed (no FK per doctrine).

---

## Use and need

- **Email history:** Sent emails (including broadcast) are stored here with lead_id. Broadcast uses lead_id = 1.
- **Lead timeline:** Per-lead message history for CRM UI and reporting.

---

## Mapping from Crafty Syntax

**Legacy table:** `livehelp_emails`.

**Migration:** `docs/doctrine/migrations/livehelp_emails_migration.md`, `import_from_old_crafty_syntax.sql`. livehelp_emails rows map to lupo_crm_lead_messages; broadcast lead_id = 1. Legacy table → IMPORTED → DROPPED.
