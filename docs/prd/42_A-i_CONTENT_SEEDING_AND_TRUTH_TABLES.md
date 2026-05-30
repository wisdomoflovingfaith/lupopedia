---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/42_A-i_CONTENT_SEEDING_AND_TRUTH_TABLES.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/42_A-i_CONTENT_SEEDING_AND_TRUTH_TABLES.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/42_content_seeding_and_truth_tables.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/content-seeding-and-truth-tables
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 42_A-i_00_A-i_FORBIDDEN_AND_WHY_42_A_CONTENT_SEEDING_AND_TRUTH_TABLES
  title: 'file: PRD 42 ??? Content seeding & truth tables ??? web_path: [http://www.lupopedia.com/lupopedia/docs/prd/42_content_seeding_and_truth_tables.md](http://www.lupopedia.com/lupopedia/docs/prd/42_content_seeding_and_truth_tables.md)'
  summary: null
---
# file: PRD 42 ???????? Content seeding & truth tables ???????? web_path: [http://www.lupopedia.com/lupopedia/docs/prd/42_content_seeding_and_truth_tables.md](http://www.lupopedia.com/lupopedia/docs/prd/42_content_seeding_and_truth_tables.md)

# PRD 42: Online Help, Content Seeding, and Truth Tables

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## 1. Purpose

This PRD governs **user-facing documentation and Q&A** in Lupopedia as represented in the **canonical install schema** and the **online help seed script**. It covers:

- **Content pages** (`lupo_contents`) used for help articles, guides, and long-form documentation (including `storage_type`, `file_path_from_root`, and consolidated JSON sidecars such as `question_mappings`).
- **Classic help surfaces** (`lupo_help_topics`, `lupo_help_tree`) for topic-based and navigational help.
- **Truth system tables** (`lupo_truth_questions`, `lupo_truth_answers`, `lupo_truth_evidence`, `lupo_truth_context_map`, `lupo_truth_followers`) for structured Q&A, evidence, and context links.
- **Semantic edges** (`lupo_edges`) connecting entities identified by `left_object_type` / `left_object_id` and `right_object_type` / `right_object_id`.
- **Seeding at install** via `seed_online_help_and_content.sql` (and any consolidated bundle that embeds it, e.g. `install/seed_lupopedia_4_2_0.sql`).
- **Lifecycle intent**: install-seed and tier-0 reference rows (per **PRD 41**), **living canonical** rows (mutable, stable ids), and **staging** rows (drafts / agent edits) merged per **PRD 00 ????3.7** and **PRD 41** when PKs use timestamp-shaped **`IdGenerator`** layouts; for plain **`BIGINT`** content/truth ids, the same **promote / UPDATE canonical / soft-delete staging** pattern applies in application logic.

**Out of scope for this PRD????????s schema inventory:** `lupo_memory_nodes` / `lupo_memory_edges` (see **PRD 38**). **Adjacent legacy:** `lupo_crafty_syntax_chat_questions` is Crafty-era **layer / form** metadata, not the Lupopedia truth Q&A model.

---

## 2. Normative schema source

All **table and column names** in this document are taken from:

- **`database/lupopedia/mysql/install/install_new_lupopedia.sql`** (with `{{prefix}}` ???????? `lupo_` in a default install).

If this PRD disagrees with the file, **the SQL file wins**.

---

## 3. Involved tables (from `install_new_lupopedia.sql`)

### 3.1 `lupo_contents`

**Purpose:** Canonical **content** rows (articles, help guides, pages). Supports DB-stored body and **file-backed** paths.

**Primary key:** `content_id` (`bigint NOT NULL`).

**Key columns (non-exhaustive; all from DDL):** `content_parent_id`, `federation_node_id`, `channel_id`, `department_id`, `actor_id`, `title`, `slug`, `custom_path`, `description`, `body`, `content`, `content_type`, `format`, `content_url`, `default_collection_id`, `source_url`, `source_title`, `is_template`, `status`, `visibility`, `view_count`, `created_ymdhis`, `utc_cycle`, `triage_status`, `triage_notes`, `updated_ymdhis`, `is_deleted`, `is_active`, `deleted_ymdhis`, `content_sections`, `version_number`, `storage_type`, `file_path_from_root`, `file_last_modified_system_version`, `file_last_modified_utc`, `tags`, `dialog_notes`, `atom_mappings`, `category_mappings`, `content_events`, `hashtags`, `inbound_links`, `like_users`, `media_attachments`, `question_mappings`, `content_references`, `revision_history`, `share_users`, `tag_relationships`, `like_count`, `share_count`, `comment_count`.

**DDL comment (invariant):** When `storage_type = 'database'`, `content` **MUST NOT** be `NULL` and `file_path_from_root` **MUST** be `NULL`; when `storage_type = 'file_backed'`, `content` **MUST** be `NULL` and `file_path_from_root` **MUST NOT** be `NULL`.

### 3.2 `lupo_help_topics`

**Purpose:** **Help topic** records (slug, titles, HTML/Markdown bodies, metrics).

**Primary key:** `help_topic_id`.

**Key columns:** `slug`, `title`, `content_html`, `content_markdown`, `category`, `parent_slug`, `view_count`, `helpful_count`, `not_helpful_count`, `created_ymdhis`, `updated_ymdhis`, `author_actor_id`, `is_deleted`.

### 3.3 `lupo_help_tree`

**Purpose:** **Hierarchical help navigation**; optional link to a content row.

**Primary key:** `help_tree_id`.

**Key columns:** `parent_id`, `department_id`, `content_id`, `title`, `description`, `action_type`, `action_target`, `sort_order`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`.

### 3.4 Truth stack

#### `lupo_truth_questions`

**Purpose:** **Questions** anchored to a target entity via `target_object_type` + `target_object_id` (e.g. `content` + `content_id` ???????? see runtime joins in `includes/modules/truth/truth-model.php`).

**Primary key:** `truth_question_id`.

**Key columns:** `parent_question_id`, `root_question_id`, `depth`, `target_object_type`, `target_object_id`, `question_text`, `question_summary`, `asked_by_actor_id`, `asked_in_channel_id`, `asked_in_thread_id`, `asked_in_session_id`, `question_status`, `is_answered`, `is_featured`, `view_count`, `answer_count`, `follower_count`, `created_ymdhis`, `updated_ymdhis`, `answered_ymdhis`, `closed_ymdhis`, `is_deleted`, `deleted_ymdhis`, `metadata_json`.

#### `lupo_truth_answers`

**Purpose:** **Answers** for a question.

**Primary key:** `truth_answer_id`.

**Key columns:** `truth_question_id`, `answer_text`, `answer_summary`, `answered_by_actor_id`, `answered_in_channel_id`, `answered_in_thread_id`, `answered_in_message_id`, `is_accepted`, `acceptance_votes`, `rejection_votes`, `confidence_score`, `answer_status`, `view_count`, `helpful_count`, `created_ymdhis`, `updated_ymdhis`, `accepted_ymdhis`, `is_deleted`, `deleted_ymdhis`, `metadata_json`.

#### `lupo_truth_evidence`

**Purpose:** **Evidence** rows attached to an answer, referencing a source object (`source_object_type`, `source_object_id`).

**Primary key:** `truth_evidence_id`.

**Key columns:** `truth_answer_id`, `evidence_type`, `source_object_type`, `source_object_id`, `source_federation_node_id`, `source_url`, `source_title`, `evidence_text`, `evidence_excerpt`, `reliability_score`, `relevance_score`, `is_verified`, `verified_by_actor_id`, `verified_ymdhis`, `verification_notes`, `submitted_by_actor_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`.

#### `lupo_truth_context_map`

**Purpose:** Maps questions to **context** / **collection** / **context_card** ids.

**Primary key:** `truth_context_map_id`.

**Key columns:** `truth_question_id`, `context_id`, `collection_id`, `context_card_id`, `sort_order`, `mapping_reason`, `added_by_actor_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`.

#### `lupo_truth_followers`

**Purpose:** **Followers** of a question.

**Primary key:** `truth_follower_id`.

**Key columns:** `truth_question_id`, `actor_id`, `notification_enabled`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`.

### 3.5 `lupo_edges`

**Purpose:** **Typed directed links** between two objects (no foreign keys; application enforces validity).

**Primary key:** `edge_id`.

**Key columns:** `left_object_type`, `left_object_id`, `right_object_type`, `right_object_id`, `edge_type`, `edge_category`, `edge_description`, `channel_id`, `channel_key`, `domain_id`, `weight_score`, `sort_num`, `actor_id`, `is_deleted`, `deleted_ymdhis`, `created_ymdhis`, `updated_ymdhis`, `semantic_weight`, `relationship_type`, `bidirectional`, `context_scope`, `properties`, `flare_weight`, `flare_reason`, `flare_db_source`, `flare_auto_generated`, `flare_verified`, `flare_discovered_via`, `edge_context`, `edge_status`, `direction`, `review_reason`.

### 3.6 Supporting / adjacent content tables (install)

| Table | PK column | Role (short) |
|-------|-----------|----------------|
| `lupo_legacy_content_mapping` | `mapping_id` | Legacy URL ???????? semantic URL / `content_id` |
| `lupo_channel_content` | `channel_content_id` | Channel + federation node file/web path mapping |
| `lupo_reference_objects` | `reference_object_id` | Reference objects (slug/label/meta) ???????? same install region as content graph |
| `lupo_crafty_syntax_chat_questions` | `crafty_syntax_chat_question_id` | Legacy Crafty **chat layer** question definitions (not `truth_questions`) |

---

## 4. PK tier rules for content and truth

**Binding doctrines:**

- **PRD 41** ???????? **Tier 0 (install seed)** numeric band **0????????999,999**: immutable **reference** rows; not living canonical parents; consolidation may copy forward to higher tiers.
- **PRD 00 ????3.2.1** ???????? Runtime rows for applicable tables use explicit **`IdGenerator::generate()`** style ids where product code allocates them; install/seed uses fixed low ids from SQL.
- **PRD 00 ????3.7** + **PRD 38 ????4.2.1** ???????? **`IdGenerator::generate()`** yields **staging-shaped** ids (embedded year **2000????????2099**). **Living canonical** ids use embedded year **1000????????1999** via **`toCanonicalId(...)`** before **`INSERT`** unless a draft staging row is intentional. Same **pattern** applies conceptually to **`content_id`**, **`truth_question_id`**, **`truth_answer_id`**, **`edge_id`** when those PKs use **`IdGenerator`** (product decision per table).

**PRD 42 normative intent (allocation):**

| Tier | Intended use | Mutability |
|------|----------------|------------|
| **Install / seed** | Rows shipped in `install_new_lupopedia.sql` + seed SQL; fixed **`BIGINT`** ids | Immutable reference per **PRD 41** |
| **Living canonical** | Operator- or merge-approved **published** help/truth; stable PK | **UPDATE** allowed (`updated_ymdhis`, body fields, status) |
| **Staging** | Drafts, agent proposals, unverified captures | Merge into canonical then **`is_deleted = 1`** + `deleted_ymdhis` |

**Important:** The current **`seed_online_help_and_content.sql`** uses **`content_id`** values **`1000001`????????`1000005`**, **`edge_id`** values in the **`4,000,001+`** range, etc. Those values are **outside** PRD 41????????s **0????????999,999** tier-0 band. Until the seed is renumbered or PRD 41 grants an explicit exception for this script, treat this as **documented drift** (see ????6).

---

## 5. Seeding rules (`seed_online_help_and_content.sql`)

**What the file header claims it populates:**

1. `lupo_contents` ???????? help articles  
2. `lupo_questions` ???????? user questions  
3. `lupo_answers` ???????? answers  
4. `lupo_edges` ???????? relationships  

**Schema fact:** `install_new_lupopedia.sql` does **not** define **`lupo_questions`** or **`lupo_answers`**. Canonical Q&A tables are **`lupo_truth_questions`** and **`lupo_truth_answers`**. Therefore, as of this writing, the **questions/answers INSERT blocks in the seed file cannot run against a fresh install** unless separate DDL is added or the seed is rewritten to **`truth_*`** and required NOT NULL columns are supplied.

**What the seed actually does for `lupo_contents` (verified in file):**

- Inserts rows with explicit **`content_id`** (`1000001` ???????), **`federation_node_id`**, **`channel_id`**, **`actor_id`**, **`title`**, **`slug`**, **`description`**, **`body`**, **`content_type`** (`help_guide`), **`format`** (`markdown`), **`status`**, **`visibility`**, **`created_ymdhis`**, **`updated_ymdhis`**, **`utc_cycle`**, **`tags`**, **`hashtags`**, **`file_path_from_root`** pointing under `content/0/help_documentation/`.

**`lupo_edges` rows in seed (verified):**

- **`left_object_type` / `right_object_type`** values include **`content`**, **`question`**, **`answer`**.
- **`edge_type`** values include **`references`**, **`has_answer`**, **`answers`**, **`related_to`**.
- **`edge_category`** values include **`help_content`**, **`qa`**.
- Seed supplies a subset of columns; remaining `lupo_edges` columns rely on **DDL defaults** (e.g. `domain_id`, `is_deleted`, `direction`, `edge_status`).

**Post-insert UPDATEs in seed:** Adjusts `tags` and `hashtags` JSON on **`lupo_contents`** for `content_id` between `1000001` and `1000005`.

---

## 6. Relationships: questions, answers, content, truth

### 6.1 Intended model (truth tables + content)

- **Question ???????? content (semantic target):** `lupo_truth_questions.target_object_type` + `target_object_id` (e.g. `'content'` + **`content_id`**).
- **Answer ???????? question:** `lupo_truth_answers.truth_question_id` ???????? `lupo_truth_questions.truth_question_id`.
- **Evidence ???????? answer:** `lupo_truth_evidence.truth_answer_id` ???????? `lupo_truth_answers.truth_answer_id`; evidence may reference **`source_object_type` / `source_object_id`** (e.g. another **`content`** row).
- **Context map ???????? question:** `lupo_truth_context_map.truth_question_id`.
- **Follower ???????? question:** `lupo_truth_followers.truth_question_id`.

### 6.2 Graph model (`lupo_edges`)

Edges are **polymorphic**: endpoints are (`*_object_type`, `*_object_id`). Examples **from the seed file** (documentary only; **`question` / `answer` object types require a matching persistence table** if edges are to resolve):

| Pattern | Example `edge_type` | Example endpoints |
|---------|----------------------|-------------------|
| Content ???????? content | `references` | `content` / `content_id` ???????? `content` / `content_id` |
| Question ???????? answer | `has_answer` | `question` / `question_id` ???????? `answer` / `answer_id` |
| Answer ???????? question | `answers` | `answer` / `answer_id` ???????? `question` / `question_id` |
| Content ???????? question | `related_to` | `content` / `content_id` ???????? `question` / `question_id` |

**Runtime note:** `includes/modules/content/content-controller.php` and `edge-controller.php` join **`lupo_edges`** to **`lupo_contents`** when object types are **`content`**.

### 6.3 `lupo_contents.question_mappings` (JSON)

Install DDL includes **`question_mappings`** JSON on **`lupo_contents`** (consolidated from removed normalized map tables). Application code may use this alongside or instead of certain **`lupo_edges`** Q&A links ???????? **exact write path is product-defined** in a future implementation pass; this PRD only asserts the **column exists** in install.

---

## 7. Promotion flow (install seed ???????? living canonical)

**Target behavior (not fully implemented in a single shipped UI as of this PRD draft):**

1. **Identify** tier-0 / seed rows (fixed ids from seed SQL or low-band policy per **PRD 41**).
2. **Operator or merge job** creates or updates **living canonical** rows:
   - **`lupo_contents`:** either allocate new **`content_id`** via **`IdGenerator`** (if adopted for this table) or policy-defined canonical band; **copy** title/body/slug fields; set **`updated_ymdhis`**; reconcile **`storage_type`** / **`file_path_from_root`** / **`content`** per DDL comments.
   - **`lupo_truth_questions` / `lupo_truth_answers`:** populate all **NOT NULL** columns (`question_text`, `asked_by_actor_id`, `answer_text`, `answered_by_actor_id`, timestamps, etc.).
3. **Record lineage** in **`lupo_edges`** (e.g. types such as `promoted_to` / `merged_into` ???????? **exact `edge_type` strings must match** future install seed / registry; today only examples exist for memory, not standardized here) **or** in JSON sidecars where doctrine allows.
4. **Soft-delete** or leave immutable seed rows per **PRD 41** (reference tomb / frozen row ???????? **no hard delete** on lineage tables).

---

## 8. Consolidation (staging ???????? canonical) for runtime edits

For **draft** or **agent-generated** rows (staging band ???????? either Chronological Trust Ladder **2000????????2099** embedded-year prefix when ids are 18-digit **`IdGenerator`**, or a product-defined staging id band for plain BIGINTs):

1. **Merge** non-null fields into the **living canonical** row (**UPDATE** + **`updated_ymdhis`**).
2. **Insert/retain** **`lupo_edges`** from staging ???????? canonical documenting merge.
3. **Soft-delete** staging rows (`is_deleted`, `deleted_ymdhis` on **`lupo_contents`**, **`lupo_truth_*`**, **`lupo_edges`** as applicable).
4. **Re-point** application queries to canonical ids (no DB FKs).

---

## 9. Non-goals and known gaps

1. **Seed/schema mismatch:** `lupo_questions` / `lupo_answers` **are not in** `install_new_lupopedia.sql` ???????? **fix seed or add DDL** before claiming a working fresh install path.
2. **Tier band drift:** Seed **`content_id`** values **??????? 1,000,001** vs **PRD 41** tier **0????????999,999** ???????? reconcile in seed or doctrine.
3. **`storage_type` vs `file_path_from_root`:** Seed sets **`file_path_from_root`** while default **`storage_type`** is **`database`** ???????? reconcile with DDL comments (**????3.1**).
4. **Help tree population:** `lupo_help_topics` / `lupo_help_tree` are **not** inserted by `seed_online_help_and_content.sql` in the current file ???????? navigation tables may be empty after seed unless other SQL populates them.

---

## 10. Related documentation

- **PRD 03** ???????? Truth/knowledge product narrative.  
- **PRD 14** ???????? System operations (mentions `lupo_help_topics`, `lupo_help_tree`).  
- **Table docs:** `docs/database/lupopedia/tables/active/` TOON-backed pages per table.

---

*This PRD cites only `install_new_lupopedia.sql`, `seed_online_help_and_content.sql`, and named PHP join paths; it does not invent tables or columns.*

This output complies with Lupopedia Constitutional Root Rules.
