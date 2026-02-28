# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\database\lupopedia\tables\livehelp_questions_migration.md"
  file_hash: "6bad69ad353871bedfd1081cfceade912b2f2d4c4fa3fd9eabd9f8a0ab6e4317"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_questions_migration.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_questions_migrationmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
flare.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/livehelp_questions_migration.md",
  system_version: "4.0.39",
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Migration doctrine for livehelp_questions → lupo_crafty_syntax_chat_questions",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_questions", "imported"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_questions", "#pre_chat", "#imported", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 2, outbound_count: 3, centrality_score: 0.68 }
}

flip.footer: {
  inbound_edges: [
    { from: "database/migrations/import_from_old_crafty_syntax.sql", type: "implements", weight: 0.9, hashtag: "#migration" },
    { from: "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" }
  ],
  outbound_edges: [
    { to: "database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" },
    { to: "app/Services/CraftySyntax/LegacyAdmin.php", type: "used_by", weight: 0.6, hashtag: "#compatibility" },
    { to: "docs/doctrine/migrations/livehelp_departments_migration.md", type: "related_to", weight: 0.5, hashtag: "#department_config" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["database/migrations/import_from_old_crafty_syntax.sql", "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_questions_mapping", "pre_chat_form", "intake_questions", "compatibility_table"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Note: livehelp_questions
# Status: IMPORTED -> DROPPED
# Replacement: lupo_crafty_syntax_chat_questions

# 1. Summary
livehelp_questions was Crafty Syntax's table for pre-chat intake questions -- the small form visitors filled out before starting a live help session.

These questions were:

department-specific

admin-configurable

not historical data

not user-generated content

not part of any ontology

not referenced by transcripts or CRM

Lupopedia replaces this with a modern, structured table:

Code
lupo_crafty_syntax_chat_questions
The legacy table is imported and then dropped.

# 2. What the Legacy Table Did
Each row represented a single pre-chat form field:

id -> primary key

department -> which department the question belonged to

ordering -> sort order

headertext -> label shown to the visitor

fieldtype -> text, dropdown, checkbox, etc.

options -> serialized options for dropdowns

flags -> miscellaneous behavior flags

module -> which module the question belonged to

required -> Y/N

This table powered the pre-chat UI, nothing more.

It did not store:

chat messages

CRM data

Q&A content

transcripts

historical logs

It was purely configuration.

# 3. Why It Maps Cleanly to Lupopedia
Lupopedia's lupo_crafty_syntax_chat_questions is the direct conceptual successor:

crafty_syntax_chat_question_id preserves the legacy ID

department_id preserves the department mapping

sort_order preserves ordering

headertext, field_type, options, flags, module_name map 1:1

is_required is normalized from required = 'Y'

lifecycle fields are added

soft-delete is added

This is a clean, lossless migration.

Why TRUNCATE is correct
These questions are:

configuration, not content

not historical

not federated

not user-generated

not meant to merge with anything

So the migration resets the table and repopulates it cleanly.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_questions ENGINE=InnoDB;
ALTER TABLE livehelp_questions CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- Import into new table
Code
TRUNCATE lupo_crafty_syntax_chat_questions;

INSERT INTO lupo_crafty_syntax_chat_questions (...)
SELECT
    id,
    department,
    ordering,
    headertext,
    fieldtype,
    options,
    flags,
    module,
    CASE WHEN required = 'Y' THEN 1 ELSE 0 END,
    20250101000000,
    20250101000000,
    0,
    NULL
FROM livehelp_questions;
Step 4 -- Drop legacy table
Removed after migration.

# 5. Mapping Summary
Legacy -> New
Code
id          -> crafty_syntax_chat_question_id
department  -> department_id
ordering    -> sort_order
headertext  -> headertext
fieldtype   -> field_type
options     -> options
flags       -> flags
module      -> module_name
required    -> is_required (Y -> 1, else 0)
Added fields
Code
created_ymdhis = 20250101000000
updated_ymdhis = 20250101000000
is_deleted = 0
deleted_ymdhis = NULL
Dropped fields
None -- all meaningful legacy fields are preserved.

# 6. Doctrine Notes
This migration is a perfect example of:

Configuration, not content
These questions are part of the pre-chat UI, not part of the semantic OS.

Preserving intent, not mechanics
We keep:

the question text

the field type

the options

the department mapping

the required flag

We modernize:

lifecycle

naming

structure

The Slope Principle
We do not attempt to reinterpret:

flags

module names

field types

We preserve them exactly as they were.

# 7. Final Decision
Code
livehelp_questions -> IMPORTED -> DROPPED
Pre-chat questions preserved in lupo_crafty_syntax_chat_questions.
