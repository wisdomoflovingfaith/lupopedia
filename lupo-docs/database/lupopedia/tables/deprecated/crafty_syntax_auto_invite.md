---
lupopedia.headers:
  file_path_from_root: "docs/database/lupopedia/tables/crafty_syntax_auto_invite.md"
  file_hash: "5860fddf421455752c1843384a31fecdc9eafb878e47445129fd41916b0db88c"
  system_version: "4.0.50"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "table_documentation"
  purpose: "Auto-invite rules for Crafty Syntax compatibility"
  lupo_agent: "gemini-cli"

lupopedia.edges:
  file_path_from_root: "docs\database\lupopedia\tables\crafty_syntax_auto_invite.md"
  outbound_edges:
- { to: "docs/database/lupopedia/tables/departments.md", type: "references", weight: 0.7 }
    - { to: "lupo-database/lupopedia/toon/lupo_crafty_syntax_auto_invite.toon.json", type: "schema_reference", weight: 1.0 }
  semantic_tags: ["crafty_syntax", "auto_invite", "compatibility", "marketing"]

  delegation_chain: null
  needs_review: ["delegation_chain"]
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# Database Documentation: lupo_crafty_syntax_auto_invite
## Version: 4.0.46
## Date: 2026-02-26

### 1. Overview
Purpose: **Crafty compatibility table** for auto-invite rules: when to show an invite (e.g. after N seconds, on a page URL), department, message text, and options (offline, mobile, social pane, etc.). Preserves legacy livehelp_autoinvite behavior during and after migration. Column names may retain legacy-style suffixes (e.g. operator_user_id) for compatibility.

**Schema:** See `lupo-database/lupopedia/toon/lupo_crafty_syntax_auto_invite.toon.json`. Primary key: `crafty_syntax_auto_invite_id`. All columns must match TOON.

### 2. Core Workflows

- **Invite logic:** Layer or chat UI reads rules by department/page/visits and shows the configured message after trigger_seconds or other conditions.
- **Migration compatibility:** Import preserves idnum → crafty_syntax_auto_invite_id, isactive → is_active, department → department_id, page → page_url, referer → referrer_url, typeof → invite_type, etc. See migration doc.

### 3. Mapping from Crafty Syntax

**Legacy table:** `livehelp_autoinvite`.

**Migration:** `docs/doctrine/migrations/livehelp_autoinvite_migration.md`, `import_from_old_crafty_syntax.sql`. Full field mapping in migration file. Legacy table is CONVERTed to utf8mb4, then data imported; livehelp_autoinvite → IMPORTED → DROPPED.

---
*Maintained by GEMINI (Actor 1006)*
