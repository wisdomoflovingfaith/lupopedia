---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/crm_lead_messages.md"
  system_version: "4.0.46"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "table_documentation"
  purpose: "Communication history associated with CRM leads"
  lupo_agent: "gemini-cli"

flare.edges:
  outbound_edges:
- { to: "docs/database/lupopedia/tables/crm_leads.md", type: "references", weight: 1.0 }
    - { to: "docs/toons/lupo_crm_lead_messages.toon.json", type: "schema_reference", weight: 1.0 }
  semantic_tags: ["crm", "messages", "email", "history"]

flare.footer:
  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# Database Documentation: lupo_crm_lead_messages
## Version: 4.0.46
## Date: 2026-02-26

### 1. Overview
Purpose: **Messages associated with leads**: e.g. emails sent to a lead, or other contact history. Links to lupo_crm_leads via lead_id. lead_id = 1 is used for “broadcast” emails (legacy livehelp_emails behavior).

**Schema:** See `docs/toons/lupo_crm_lead_messages.toon.json`. Primary key and columns as in TOON; references to lead_id are application-managed (no FK per doctrine).

### 2. Core Workflows

- **Email history:** Sent emails (including broadcast) are stored here with lead_id. Broadcast uses lead_id = 1.
- **Lead timeline:** Per-lead message history for CRM UI and reporting.

### 3. Mapping from Crafty Syntax

**Legacy table:** `livehelp_emails`.

**Migration:** `docs/doctrine/migrations/livehelp_emails_migration.md`, `import_from_old_crafty_syntax.sql`. livehelp_emails rows map to lupo_crm_lead_messages; broadcast lead_id = 1. Legacy table → IMPORTED → DROPPED.

---
*Maintained by GEMINI (Actor 1006)*

