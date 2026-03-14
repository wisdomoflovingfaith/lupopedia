---
lupopedia.headers:
  file_path_from_root: "lupo-docs/database/lupopedia/tables/crm_leads.md"
  file_hash: "fcb926e9d9d2628ad7573d3b21a071649695e62d7759fe31a23e983eb315804f"
  system_version: "4.0.50"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "table_documentation"
  purpose: "Lead capture and CRM contact records"
  lupo_agent: "gemini-cli"

lupopedia.edges:
  file_path_from_root: "lupo-docs\database\lupopedia\tables\crm_leads.md"
  outbound_edges:
- { to: "lupo-docs/database/lupopedia/tables/crm_lead_messages.md", type: "references", weight: 0.8 }
    - { to: "lupo-database/lupopedia/toon/lupo_crm_leads.toon.json", type: "schema_reference", weight: 1.0 }
  semantic_tags: ["crm", "leads", "marketing", "contact"]

  delegation_chain: null
  needs_review: ["delegation_chain"]
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# Database Documentation: lupo_crm_leads
## Version: 4.0.46
## Date: 2026-02-26

### 1. Overview
Purpose: **Lead capture**: contact/lead records for CRM (e.g. pre-chat, leave-message, or marketing). Structure is defined in the TOON; typically includes identity/contact fields and lifecycle. Lead_id = 1 is often used as “broadcast” lead for bulk emails in lupo_crm_lead_messages.

**Schema:** See `lupo-database/lupopedia/toon/lupo_crm_leads.toon.json`. Primary key: `lead_id` (or as in TOON). All column names and types must match the TOON.

### 2. Core Workflows

- **Forms and capture:** Pre-chat, leave-message, and other flows create or update lead rows. Messages sent to leads are in lupo_crm_lead_messages.
- **Broadcast:** lead_id = 1 is the conventional “broadcast” lead for legacy compatibility with livehelp_emails (broadcast emails).

### 3. Mapping from Crafty Syntax

**Legacy table:** `livehelp_leads`.

**Migration:** `lupo-docs/doctrine/migrations/livehelp_leads_migration.md`, `import_from_old_crafty_syntax.sql`. Field mapping and ID preservation as defined in the migration doc and import SQL. livehelp_leads → IMPORTED → DROPPED.

---
*Maintained by GEMINI (Actor 1006)*
