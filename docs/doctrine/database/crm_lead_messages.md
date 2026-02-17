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
