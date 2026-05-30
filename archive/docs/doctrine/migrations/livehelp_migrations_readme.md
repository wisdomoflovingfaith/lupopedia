---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/migrations/livehelp_migrations_readme.md"
  web_path: null
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: documentation
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: ""
  summary: ""
---
# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  

lupopedia.edges: []
  file_path_from_root: "docs\doctrine\migrations\livehelp_migrations_readme.md"
  file_hash: "92ada42309de4bc4dcff87ec5ce801f88694dd3b73800ccdd94a499ea41f336a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_migrations_readme.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "migrations", "livehelp_migrations_readmemd"]
  lupo_agent: "windsurf"

lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "docs/doctrine/migrations/livehelp_migrations_readme.md",
  file_hash: "467afcaa1e21e7aa1f2d3bcb90b5724c035eba7d2f8b40c74797f2fec7623d9c"
  system_version: "4.0.50"
  channel_id: 42,
  actor_id: 1003,
  last_modified_utc: "20260227",
  delegation_chain: "10000:1003",
  artifact_type: "documentation",
  purpose: "Relocation notice for legacy Crafty Syntax migration documents",
  mood_vector: "00FF00",
  traits: ["canonical", "documentation", "migration", "reference"],
  tags: ["database", "migrations", "legacy", "crafty_syntax", "lupopedia", "relocation"],
  lupo_agent: "antigravity"
}
flip.footer: {
  outbound_edges: [
    { to: "docs/database/lupopedia/tables/", type: "references", weight: 1.0 }
  ],
  semantic_tags: ["migration_relocation", "legacy_cleanup", "v4.1.1_deprecation"]
}
---

# Legacy Migration Documentation Relocation

## Mandatory for implementers (IDE agents and humans)

**Do not guess** table names, column names, or types when writing Lupopedia code, SQL, importers, or rollups.

1. Open the **per-table** file under **`docs/database/lupopedia/tables/migrations/`** for every legacy table you touch (e.g. **`livehelp_users_migration.md`**, **`livehelp_visit_track_migration.md`**). That file states how **`livehelp_*`** maps to **`lupo_*`** and which columns exist.
2. Confirm the **current** Lupopedia DDL in **`database/lupopedia/mysql/install/install_new_lupopedia.sql`** (and seed if needed). **TOON/JSON** exports are **not** a substitute for reading install SQL when defining queries or migrations.
3. Use this readme and **`docs/doctrine/migrations/`** for cross references and narrative; use **`docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md`** for wide mappings when applicable.

**Crafty config flags** in PHP reference code often use **`'Y'`** / **`'N'`** strings. In Lupopedia, equivalent toggles are typically **`TINYINT`**: **`1`** = enabled (was **`'Y'`**), **`0`** = disabled (anything else). Compare as integers in application code unless a documented shim says otherwise.

Failure mode to avoid: assuming a column exists because it “sounds right,” because a TOON snippet was partial, or because Crafty used a name—**read the mapping file and install SQL first** (see **PRD 33** §4.1).

---

All legacy Crafty Syntax migration files have been relocated to **`docs/database/lupopedia/tables/migrations/`** for reference purposes (per-table `*_migration.md` and related `.md` files).

This move emphasizes that legacy **`livehelp_*`** tables are not the runtime target; they document how data and semantics map into **`lupo_*`**. Legacy table names appear in **reference** PHP under **`craftysyntax-reference/`** and in import paths only. For the file list, see **Moved Files Reference** below.

## Moved Files Reference

The following files have been relocated to the database table documentation directory (**`docs/database/lupopedia/tables/migrations/`**):

1.  **MIGRATION_MAPPING_REFERENCE.md**
2.  **livehelp_autoinvite_migration.md**
3.  **livehelp_channels_migration.md**
4.  **livehelp_config_migration.md**
5.  **livehelp_departments_migration.md**
6.  **livehelp_emailque_migration.md**
7.  **livehelp_emails_migration.md**
8.  **livehelp_identity_migration.md**
9.  **livehelp_keywords_migration.md**
10. **livehelp_layerinvites_migration.md**
11. **livehelp_leads_migration.md**
12. **livehelp_leavemessage_migration.md**
13. **livehelp_messages_migration.md**
14. **livehelp_modules_dep_migration.md**
15. **livehelp_modules_migration.md**
16. **livehelp_operator_channels_migration.md**
17. **livehelp_operator_departments_migration.md**
18. **livehelp_operator_history_migration.md**
19. **livehelp_paths_firsts_migration.md**
20. **livehelp_qa_migration.md**
21. **livehelp_questions_migration.md**
22. **livehelp_quick_migration.md**
23. **livehelp_referers_daily_migration.md**
24. **livehelp_sessions_migration.md**
25. **livehelp_smilies_migration.md**
26. **livehelp_transcripts_migration.md**
27. **livehelp_users_migration.md**
28. **livehelp_visit_track_migration.md**
29. **livehelp_websites_migration.md**
30. **operator_to_roles_migration.md**

Please update any internal links or documentation that referenced these files in their original location.
