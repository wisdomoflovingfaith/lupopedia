---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/actor_reply_templates.md"
  file_hash: "97bb6e372486e5a3a8b806a361364a379bf0dc7fbbddd5ffbcf8fd64e7c847c0"
  system_version: "4.0.50"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "table_documentation"
  purpose: "Quick reply / canned response templates per actor"
  lupo_agent: "gemini-cli"

flare.edges:
  file_path_from_root: "docs\database\lupopedia\tables\actor_reply_templates.md"
  outbound_edges:
- { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_actor_reply_templates.toon.json", type: "schema_reference", weight: 1.0 }
  semantic_tags: ["templates", "replies", "composer", "efficiency"]

  delegation_chain: null
  needs_review: ["delegation_chain"]
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# Database Documentation: lupo_actor_reply_templates
## Version: 4.0.46
## Date: 2026-02-26

### 1. Overview
Purpose: **Quick reply / canned response** templates per actor: template_key, template_text, usage_context, and actor_id. Used in the channel composer so staff can insert predefined messages. Replaces Crafty’s livehelp_quick (per-user “quick notes”).

**Schema:** See `lupo-database/lupopedia/toon/lupo_actor_reply_templates.toon.json`. Primary key: `actor_reply_template_id`. Columns include actor_id, template_key, template_text, usage_context, and lifecycle fields.

### 2. Core Workflows

- **Composer:** UI offers a list of templates for the current actor; selecting one inserts template_text (and optionally sets usage_context for analytics or filtering).
- **Per-actor:** Each row is tied to an actor_id so operators have their own quick replies.

### 3. Mapping from Crafty Syntax

**Legacy table:** `livehelp_quick`.

**Migration:** `docs/doctrine/migrations/livehelp_quick_migration.md`, `import_from_old_crafty_syntax.sql`. id → actor_reply_template_id, user → actor_id, name → template_key, message → template_text, typeof → usage_context. livehelp_quick → IMPORTED → DROPPED.

---
*Maintained by GEMINI (Actor 1006)*