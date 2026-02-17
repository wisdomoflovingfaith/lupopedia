# lupo_federation_nodes

**Purpose:** **Multi-site / federation registry**: each row represents a node (e.g. a website or instance) in a federation. Used for multi-site routing and discovery. Legacy livehelp_websites was the “websites” list in Crafty; it maps here.

**Schema:** See `docs/toons/lupo_federation_nodes.toon.json`. Primary key and columns as in TOON (e.g. federation_node_id, name, base_url, settings). No foreign keys; references are application-managed.

---

## Use and need

- **Federation and routing:** Channels or departments may be scoped to a federation_node_id. Cross-node references use this table for resolution.
- **Legacy websites:** Crafty’s website list becomes federation nodes so existing multi-site config is preserved.

---

## Mapping from Crafty Syntax

**Legacy table:** `livehelp_websites`.

**Migration:** `docs/doctrine/migrations/livehelp_websites_migration.md`, `import_from_old_crafty_syntax.sql`. Legacy fields map into lupo_federation_nodes columns and/or metadata as defined in the migration. livehelp_websites → IMPORTED → DROPPED.
