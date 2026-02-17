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
