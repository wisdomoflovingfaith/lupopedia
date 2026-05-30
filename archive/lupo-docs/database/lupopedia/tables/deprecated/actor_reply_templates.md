---
lupopedia.headers:
  file_path_from_root: "lupo-docs/database/lupopedia/tables/deprecated/actor_reply_templates.md"
  file_hash: "97bb6e372486e5a3a8b806a361364a379bf0dc7fbbddd5ffbcf8fd64e7c847c0"
  system_version: "4.0.50"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "table_documentation"
  purpose: "Quick reply / canned response templates per actor"
  lupo_agent: "gemini-cli"

lupopedia.edges:
  file_path_from_root: "lupo-docs\database\lupopedia\tables\actor_reply_templates.md"
  outbound_edges:
- { to: "lupo-docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_actor_reply_templates.toon.json", type: "schema_reference", weight: 1.0 }
  semantic_tags: ["templates", "replies", "composer", "efficiency"]

  delegation_chain: null
  needs_review: ["delegation_chain"]
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# Database Documentation: lupo_actor_reply_templates
## Version: 4.0.46
## Date: 2026-02-26

### 1. Overview
Purpose: **Quick reply / canned response** templates per actor: template_key, template_text, usage_context, and actor_id. Used in the channel composer so staff can insert predefined messages. Replaces Craftyâ€™s livehelp_quick (per-user â€œquick notesâ€).

**Schema:** See `lupo-database/lupopedia/toon/lupo_actor_reply_templates.toon.json`. Primary key: `actor_reply_template_id`. Columns include actor_id, template_key, template_text, usage_context, and lifecycle fields.

### 2. Core Workflows

- **Composer:** UI offers a list of templates for the current actor; selecting one inserts template_text (and optionally sets usage_context for analytics or filtering).
- **Per-actor:** Each row is tied to an actor_id so operators have their own quick replies.

### 3. Mapping from Crafty Syntax

**Legacy table:** `livehelp_quick`.

**Migration:** `lupo-docs/doctrine/migrations/livehelp_quick_migration.md`, `import_from_old_crafty_syntax.sql`. id â†’ actor_reply_template_id, user â†’ actor_id, name â†’ template_key, message â†’ template_text, typeof â†’ usage_context. livehelp_quick â†’ IMPORTED â†’ DROPPED.

---
*Maintained by GEMINI (Actor 1006)*

