---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/federation_nodes.md"
  file_hash: "746c0fe3aca3b512da278c493bd2ff47d6cc7c493fca689de5827cf1a56bf651"
  system_version: "4.0.50"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "table_documentation"
  purpose: "Registry for multi-site federation and instance discovery"
  lupo_agent: "gemini-cli"

flare.edges:
  file_path_from_root: "docs\database\lupopedia\tables\federation_nodes.md"
  outbound_edges:
- { to: "docs/database/lupopedia/tables/departments.md", type: "references", weight: 0.6 }
    - { to: "lupo-database/lupopedia/toon/lupo_federation_nodes.toon.json", type: "schema_reference", weight: 1.0 }
  semantic_tags: ["federation", "multi-site", "nodes", "registry"]

  delegation_chain: null
  needs_review: ["delegation_chain"]
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# Database Documentation: lupo_federation_nodes
## Version: 4.0.46
## Date: 2026-02-26

### 1. Overview
Purpose: **Multi-site / federation registry**: each row represents a node (e.g. a website or instance) in a federation. Used for multi-site routing and discovery. Legacy livehelp_websites was the “websites” list in Crafty; it maps here.

**Schema:** See `lupo-database/lupopedia/toon/lupo_federation_nodes.toon.json`. Primary key and columns as in TOON (e.g. federation_node_id, name, base_url, settings). No foreign keys; references are application-managed.

### 2. Core Workflows

- **Federation and routing:** Channels or departments may be scoped to a federation_node_id. Cross-node references use this table for resolution.
- **Legacy websites:** Crafty’s website list becomes federation nodes so existing multi-site config is preserved.

### 3. Mapping from Crafty Syntax

**Legacy table:** `livehelp_websites`.

**Migration:** `docs/doctrine/migrations/livehelp_websites_migration.md`, `import_from_old_crafty_syntax.sql`. Legacy fields map into lupo_federation_nodes columns and/or metadata as defined in the migration. livehelp_websites → IMPORTED → DROPPED.

---
*Maintained by GEMINI (Actor 1006)*