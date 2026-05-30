# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/database/lupopedia/tables/migrations/livehelp_qa_migration.md"
  file_hash: "655ed400be2ca236ff313dbed9004988a87b2153cf375a67081b98d1ad3dbf6f"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  namespace: "legacy"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\database\lupopedia\tables\livehelp_qa_migration.md"
  file_hash: "d13a41cb8b76bc3ee2ba6ec3e4e9c5fea0eeda464d9895e1eb85e1df5efb4889"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_qa_migration.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_qa_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/livehelp_qa_migration.md",
  file_hash: "e113ea44e0c33f564eb6e27da987532b7cf692201381417b30d946ae7a947d45"
  system_version: "4.0.50"
  channel_id: 42,
  mood_vector: "8B4513",
  purpose: "Migration doctrine for livehelp_qa â†’ lupo_truth_questions/answers/collections",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_qa", "imported"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_qa", "#truth_system", "#imported", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 2, outbound_count: 5, centrality_score: 0.80 }
}

flip.footer: {
  inbound_edges: [
    { from: "database/migrations/import_from_old_crafty_syntax.sql", type: "implements", weight: 1.0, hashtag: "#migration" },
    { from: "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" }
  ],
  outbound_edges: [
    { to: "docs/doctrine/DATABASE_DOCTRINE.md", type: "documents", weight: 1.0, hashtag: "#target_table" },
    { to: "docs/database/lupopedia/tables/active/lupo_truth_answers.md", type: "documents", weight: 1.0, hashtag: "#target_table" },
    { to: "docs/database/lupopedia/tables/active/lupo_collections.md", type: "documents", weight: 0.9, hashtag: "#target_table" },
    { to: "docs/database/lupopedia/tables/active/lupo_collection_tabs.md", type: "documents", weight: 0.9, hashtag: "#target_table" },
    { to: "database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" }
  ],
  referenced_by_actors: [1001, 1002, 10000],
  references: {
    by_files: ["database/migrations/import_from_old_crafty_syntax.sql", "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 1002, 10000]
  },
  semantic_tags: ["livehelp_qa_mapping", "truth_system", "qa_knowledge_base", "imported"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Note: livehelp_qa
# Status: IMPORTED -> DROPPED
# Replacement:
#
# lupo_truth_questions
#
# lupo_truth_answers
#
# lupo_collections
#
# lupo_collection_tabs

# 1. Summary
livehelp_qa was Crafty Syntax's combined table for:

questions

answers

folder/grouping structure

navigation hierarchy

All stored in a single table with a typeof field:

question -> a question

answer -> an answer

folder -> a navigation grouping

Lupopedia replaces this with three separate, structured systems:

Truth Module
lupo_truth_questions

lupo_truth_answers

Navigation System
lupo_collections

lupo_collection_tabs

The legacy table is imported and then dropped.

# 2. What the Legacy Table Did
Crafty Syntax stored all Q&A content in one table:

Questions
recno -> question ID

parent -> parent question (0 = none)

question -> text

ordernum -> sort order

Answers
parent -> ID of the question being answered

question -> answer text

no scoring

no metadata

no lifecycle fields

Folders
typeof = 'folder'

question -> folder name

parent -> parent folder

ordernum -> sort order

This table mixed:

content

navigation

hierarchy

metadata

...into one structure.

# 3. Why It Maps Cleanly to Lupopedia
Lupopedia separates concerns:

Truth Module
Questions -> lupo_truth_questions

Answers -> lupo_truth_answers

Navigation
Top-level folder -> lupo_collections

Folder hierarchy -> lupo_collection_tabs

This preserves all meaning while modernizing structure.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_qa ENGINE=InnoDB;
ALTER TABLE livehelp_qa CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
5. Importing Questions -> lupo_truth_questions
Mapping
Code
recno                -> truth_question_id
parent (0->NULL)     -> truth_question_parent_id
question             -> question_text
ordernum             -> sort_num
typeof='question'    -> included
Defaults added
Code
actor_id = 0
qtype = 'unknown'
status = 'active'
format = 'text'
view_count = 0
likes_count = 0
shares_count = 0
answer_count = 0
is_featured = 0
is_verified = 0
created_ymdhis = 20250101000000
updated_ymdhis = 20250101000000
Slug
Code
qa-{recno}
Idempotency
ON DUPLICATE KEY UPDATE ensures safe re-runs.

6. Importing Answers -> lupo_truth_answers
Mapping
Code
parent               -> truth_question_id
question             -> answer_text
typeof='answer'      -> included
Defaults added
Code
actor_id = 0
confidence_score = 0.00
evidence_score = 0.00
contradiction_flag = 0
likes_count = 0
shares_count = 0
created_ymdhis = 20250101000000
updated_ymdhis = 20250101000000
Idempotency
ON DUPLICATE KEY UPDATE updates timestamps only.

7. Importing Folder Structure -> Collections + Tabs
Collection
A single collection is created:

Code
name = 'Site Navigation'
slug = 'site-navigation'
color = '666666'
description = 'Auto-generated navigation collection from Crafty Syntax'
This becomes the root navigation container.

Top-level folders -> collection tabs
Code
typeof='folder' AND parent=0
Mapping:

Code
name        -> question
slug        -> lowercased, hyphenated question
sort_order  -> ordernum
color       -> '4caf50'
Child folders -> nested collection tabs
Code
typeof='folder' AND parent != 0
Mapping:

parent folder -> parent_tab.collection_tab_id

child folder -> new tab under parent

This recreates the entire folder hierarchy.

8. Mapping Summary
Legacy -> Truth Module
Legacy Field	Truth Questions	Truth Answers
recno	truth_question_id	-
parent	truth_question_parent_id	truth_question_id
question	question_text	answer_text
ordernum	sort_num	-
typeof='question'	included	-
typeof='answer'	-	included
Legacy -> Navigation
Legacy Field	Collections	Tabs
typeof='folder'	creates collection tabs	creates nested tabs
question	name	name
parent	determines hierarchy	determines parent tab
ordernum	sort_order	sort_order
9. Doctrine Notes
This migration is a perfect example of:

Separating concerns
Crafty Syntax stored:

questions

answers

folders

...in one table.

Lupopedia splits them into:

Truth Questions

Truth Answers

Collections

Collection Tabs

Preserving meaning
All content and hierarchy is preserved.

Modernizing structure
We add:

lifecycle fields

soft-delete

metadata

slugs

JSON-ready structures

The Slope Principle
We do not attempt to reinterpret:

qtype

scoring

metadata

folder semantics

We preserve the legacy meaning and let the Truth Module evolve it later.

10. Final Decision
Code
livehelp_qa -> IMPORTED -> DROPPED
Questions -> lupo_truth_questions
Answers -> lupo_truth_answers
Folders -> lupo_collections + lupo_collection_tabs

