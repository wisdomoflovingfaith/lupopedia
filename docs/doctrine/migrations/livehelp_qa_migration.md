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
