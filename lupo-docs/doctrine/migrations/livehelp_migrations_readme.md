# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/migrations/livehelp_migrations_readme.md"
  file_hash: "f0ea78fe25078175c9753082a770431bf4581b068fef7a7d5023eb4039d88cce"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\doctrine\migrations\livehelp_migrations_readme.md"
  file_hash: "92ada42309de4bc4dcff87ec5ce801f88694dd3b73800ccdd94a499ea41f336a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_migrations_readme.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "migrations", "livehelp_migrations_readmemd"]
  lupo_agent: "windsurf"

lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "lupo-docs/doctrine/migrations/livehelp_migrations_readme.md",
  file_hash: "467afcaa1e21e7aa1f2d3bcb90b5724c035eba7d2f8b40c74797f2fec7623d9c"
  system_version: "4.0.50"
  channel_id: 42,
  actor_id: 1003,
  last_modified_utc: "20260227",
  delegation_chain: "10000:1003",
  artifact_type: "documentation",
  purpose: "Relocation notice for legacy Crafty Syntax migration documents",
  mood_rgb: "00FF00",
  traits: ["canonical", "documentation", "migration", "reference"],
  tags: ["database", "migrations", "legacy", "crafty_syntax", "lupopedia", "relocation"],
  lupo_agent: "antigravity"
}
flip.footer: {
  outbound_edges: [
    { to: "lupo-docs/database/lupopedia/tables/", type: "references", weight: 1.0 }
  ],
  semantic_tags: ["migration_relocation", "legacy_cleanup", "v4.1.1_deprecation"]
}
---

# Legacy Migration Documentation Relocation

All legacy Crafty Syntax migration files have been relocated to **lupo-docs/database/lupopedia/tables/** for reference purposes. 

This move emphasizes that these tables are deprecated and should not be used in the new Lupopedia system. They serve only to document mappings to new `lupo_` tables. Legacy tables will be removed in v4.1.1+. For details, refer to the files in the new location.

## Moved Files Reference

The following files have been relocated to the database table documentation directory:

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
