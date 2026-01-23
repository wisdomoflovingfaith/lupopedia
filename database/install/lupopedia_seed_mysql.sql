/* ============================================================
   L U P O P E D I A   –   B A S E   S E E D   S C R I P T
   Version: 1.0.0
   Notes:
   - No foreign keys
   - No triggers
   - No stored procedures
   - All timestamps BIGINT UTC (YYYYMMDDHHMMSS)
   - Safe for any table prefix (replace lupo_ manually or in PHP)
   ============================================================ */

/* ------------------------------------------------------------
   1. DOMAIN SEED (must be first)
   ------------------------------------------------------------ */
INSERT INTO lupo_domains (domain_id, domain_name, status, created_at)
VALUES (1, 'primary', 'active', 20260101000000);

/* ------------------------------------------------------------
   2. GROUPS (core permission groups)
   ------------------------------------------------------------ */
INSERT INTO lupo_groups (group_id, domain_id, group_name, created_at)
VALUES
  (1, 1, 'Administrators', 20260101000010),
  (2, 1, 'Editors',        20260101000020),
  (3, 1, 'Viewers',        20260101000030),
  (4, 1, 'Guests',         20260101000040);

/* ------------------------------------------------------------
   3. ACTORS (system + admin)
   ------------------------------------------------------------ */
INSERT INTO lupo_actors (actor_id, domain_id, actor_type, display_name, created_at)
VALUES
  (1, 1, 'system', 'System', 20260101000050),
  (2, 1, 'user',   'Administrator', 20260101000100);

/* ------------------------------------------------------------
   4. AUTH USERS (admin login)
   NOTE: Replace PASSWORD_HASH_HERE in PHP with real hash
   ------------------------------------------------------------ */
INSERT INTO lupo_auth_users (user_id, actor_id, username, password_hash, created_at)
VALUES
  (1, 2, 'admin', 'PASSWORD_HASH_HERE', 20260101000110);

/* ------------------------------------------------------------
   5. PERMISSION DEFAULTS (baseline ACL)
   ------------------------------------------------------------ */
INSERT INTO lupo_permission_defaults (domain_id, entity_type, default_permissions)
VALUES
  (1, 'content', '755'),
  (1, 'category', '755'),
  (1, 'collection', '755'),
  (1, 'channel', '755'),
  (1, 'atom', '755');

/* ------------------------------------------------------------
   6. BASIC TAXONOMY (categories, collections, channels)
   ------------------------------------------------------------ */
INSERT INTO lupo_categories (category_id, domain_id, category_name, created_at)
VALUES (1, 1, 'General', 20260101000120);

INSERT INTO lupo_collections (collection_id, domain_id, collection_name, created_at)
VALUES (1, 1, 'Main Collection', 20260101000130);

INSERT INTO lupo_channels (channel_id, domain_id, channel_name, created_at)
VALUES (1, 1, 'Main Channel', 20260101000140);

/* ------------------------------------------------------------
   7. ATOMS (starter semantic concepts)
   ------------------------------------------------------------ */
INSERT INTO lupo_atoms (atom_id, domain_id, atom_name, created_at)
VALUES
  (1, 1, 'home',   20260101000150),
  (2, 1, 'system', 20260101000160),
  (3, 1, 'welcome',20260101000170);

/* ------------------------------------------------------------
   8. CONTENT (initial pages)
   ------------------------------------------------------------ */
INSERT INTO lupo_contents (content_id, domain_id, title, body, created_at)
VALUES
  (1, 1, 'Welcome to Lupopedia', 'Your knowledge system is ready.', 20260101000180),
  (2, 1, 'System Overview', 'This page explains how Lupopedia works.', 20260101000190);

/* ------------------------------------------------------------
   9. NODES (semantic graph nodes)
   ------------------------------------------------------------ */
INSERT INTO lupo_nodes (node_id, domain_id, entity_type, entity_id, created_at)
VALUES
  (1, 1, 'content', 1, 20260101000200),
  (2, 1, 'content', 2, 20260101000210),
  (3, 1, 'atom',    1, 20260101000220),
  (4, 1, 'atom',    2, 20260101000230),
  (5, 1, 'atom',    3, 20260101000240);

/* ------------------------------------------------------------
   10. EDGES (starter semantic relationships)
   ------------------------------------------------------------ */
INSERT INTO lupo_edges (edge_id, domain_id, from_node, to_node, weight, created_at)
VALUES
  (1, 1, 1, 3, 1.0, 20260101000250),  -- Welcome → home
  (2, 1, 1, 5, 1.0, 20260101000260),  -- Welcome → welcome atom
  (3, 1, 2, 4, 1.0, 20260101000270);  -- System Overview → system atom

/* ------------------------------------------------------------
   11. OPTIONAL: AGENT ROLES / STYLES / CAPABILITIES
   (empty seeds; system will populate later)
   ------------------------------------------------------------ */
-- INSERT INTO lupo_agent_roles (...)
-- INSERT INTO lupo_agent_styles (...)
-- INSERT INTO lupo_agent_capabilities (...)

/* ------------------------------------------------------------
   12. OPTIONAL: HELP TREE / SUPPORT / ANALYTICS
   (left empty intentionally)
   ------------------------------------------------------------ */

/* ============================================================
   END OF BASE SEED
   ============================================================ */
